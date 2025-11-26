<?php

namespace App\Services;

use App\Models\Deployment;
use App\Models\Webhook;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class DeploymentService
{
    public function __construct(
        protected SshKeyService $sshKeyService
    ) {
    }

    /**
     * Execute deployment for a webhook.
     */
    public function deploy(Webhook $webhook, array $payload = []): Deployment
    {
        $deployment = Deployment::create([
            'webhook_id' => $webhook->id,
            'status' => 'running',
            'commit_hash' => $payload['commit_hash'] ?? null,
            'commit_message' => $payload['commit_message'] ?? null,
            'author' => $payload['author'] ?? null,
            'started_at' => now(),
        ]);

        try {
            $output = $this->executeDeployment($webhook);

            $deployment->update([
                'status' => 'success',
                'output' => $output,
                'completed_at' => now(),
            ]);

            $webhook->update(['last_deployed_at' => now()]);
        } catch (\Exception $e) {
            Log::error('Deployment failed: ' . $e->getMessage(), [
                'webhook_id' => $webhook->id,
                'deployment_id' => $deployment->id,
            ]);

            $deployment->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);
        }

        return $deployment;
    }

    /**
     * Execute the deployment process.
     */
    protected function executeDeployment(Webhook $webhook): string
    {
        $localPath = $webhook->local_path;
        $branch = $webhook->branch;
        $output = [];

        // Setup SSH key if available
        $sshKey = $webhook->sshKey;
        $keyPath = null;
        $gitSshCommand = '';

        if ($sshKey) {
            $keyPath = $this->sshKeyService->saveTempPrivateKey($sshKey);
            $gitSshCommand = "ssh -i {$keyPath} -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null";
        }

        try {
            // Check if directory exists
            if (!File::isDirectory($localPath)) {
                // Clone repository
                $output[] = "Cloning repository...";
                $result = Process::env([
                    'GIT_SSH_COMMAND' => $gitSshCommand,
                ])->run([
                    'git',
                    'clone',
                    '-b', $branch,
                    $webhook->repository_url,
                    $localPath,
                ]);

                $output[] = $result->output();

                if ($result->failed()) {
                    throw new \Exception("Git clone failed: " . $result->errorOutput());
                }
            } else {
                // Pull latest changes
                $output[] = "Pulling latest changes...";

                // Fetch
                $result = Process::path($localPath)
                    ->env(['GIT_SSH_COMMAND' => $gitSshCommand])
                    ->run(['git', 'fetch', 'origin', $branch]);

                $output[] = $result->output();

                if ($result->failed()) {
                    throw new \Exception("Git fetch failed: " . $result->errorOutput());
                }

                // Reset to origin
                $result = Process::path($localPath)->run([
                    'git',
                    'reset',
                    '--hard',
                    "origin/{$branch}",
                ]);

                $output[] = $result->output();

                if ($result->failed()) {
                    throw new \Exception("Git reset failed: " . $result->errorOutput());
                }
            }

            // Run pre-deploy script
            if ($webhook->pre_deploy_script) {
                $output[] = "\nRunning pre-deploy script...";
                $result = Process::path($localPath)
                    ->timeout(300)
                    ->run(['bash', '-c', $webhook->pre_deploy_script]);

                $output[] = $result->output();

                if ($result->failed()) {
                    $output[] = "Warning: Pre-deploy script failed: " . $result->errorOutput();
                }
            }

            // Run post-deploy script
            if ($webhook->post_deploy_script) {
                $output[] = "\nRunning post-deploy script...";
                $result = Process::path($localPath)
                    ->timeout(300)
                    ->run(['bash', '-c', $webhook->post_deploy_script]);

                $output[] = $result->output();

                if ($result->failed()) {
                    $output[] = "Warning: Post-deploy script failed: " . $result->errorOutput();
                }
            }

            $output[] = "\n✓ Deployment completed successfully!";
        } finally {
            // Clean up temporary key
            if ($keyPath) {
                $this->sshKeyService->deleteTempPrivateKey($keyPath);
            }
        }

        return implode("\n", $output);
    }

    /**
     * Parse GitHub webhook payload.
     */
    public function parseGithubPayload(array $payload): array
    {
        return [
            'commit_hash' => $payload['after'] ?? null,
            'commit_message' => $payload['head_commit']['message'] ?? null,
            'author' => $payload['head_commit']['author']['name'] ?? null,
        ];
    }

    /**
     * Parse GitLab webhook payload.
     */
    public function parseGitlabPayload(array $payload): array
    {
        return [
            'commit_hash' => $payload['checkout_sha'] ?? $payload['after'] ?? null,
            'commit_message' => $payload['commits'][0]['message'] ?? null,
            'author' => $payload['commits'][0]['author']['name'] ?? $payload['user_name'] ?? null,
        ];
    }
}
