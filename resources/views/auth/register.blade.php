<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Git Webhook Manager</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        
        .split-container {
            display: flex;
            height: 100vh;
        }
        
        /* Left Side - Branding */
        .left-side {
            flex: 1;
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: white;
            padding: 3rem 3rem 3rem 5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .left-side::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .left-content {
            position: relative;
            z-index: 1;
            max-width: 500px;
            width: 100%;
        }
        
        .logo-section {
            margin-bottom: 3rem;
        }
        
        .logo-section i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .logo-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .logo-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .benefits {
            margin-top: 3rem;
        }
        
        .benefit-item {
            display: flex;
            align-items: start;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.6s ease-out backwards;
        }
        
        .benefit-item:nth-child(1) { animation-delay: 0.1s; }
        .benefit-item:nth-child(2) { animation-delay: 0.2s; }
        .benefit-item:nth-child(3) { animation-delay: 0.3s; }
        .benefit-item:nth-child(4) { animation-delay: 0.4s; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .benefit-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .benefit-icon i {
            font-size: 1.5rem;
        }
        
        .benefit-text h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .benefit-text p {
            font-size: 0.9rem;
            opacity: 0.85;
            margin: 0;
        }
        
        /* Right Side - Form */
        .right-side {
            flex: 1;
            background: white;
            padding: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
        }
        
        .form-container {
            width: 100%;
            max-width: 480px;
            margin-left: 100px;
        }
        
        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .form-header p {
            color: #6c757d;
            font-size: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        
        .form-control {
            border-left: none;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus {
            border-color: #198754;
            box-shadow: none;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #198754;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #146c43 0%, #0f5132 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .login-link {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
        }
        
        .login-link a {
            color: #198754;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 992px) {
            body {
                overflow-y: auto;
            }
            
            .split-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            .left-side {
                min-height: 30vh;
                padding: 2rem;
                position: relative;
                align-items: flex-start;
            }
            
            .benefits {
                display: none;
            }
            
            .right-side {
                padding: 2rem;
                min-height: 70vh;
            }
            
            .form-container {
                margin-left: 0;
            }
            
            .left-content {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left Side - Branding & Benefits -->
        <div class="left-side">
            <div class="left-content">
                <div class="logo-section">
                    <i class="bi bi-rocket-takeoff"></i>
                    <h1>Start Your Journey</h1>
                    <p>Join developers automating their deployments</p>
                </div>
                
                <div class="benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Free Forever</h4>
                            <p>No credit card required. Start deploying immediately</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-infinity"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Unlimited Webhooks</h4>
                            <p>Manage unlimited webhooks and deployments</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Boost Productivity</h4>
                            <p>Save hours with automated deployment workflows</p>
                        </div>
                    </div>
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Team Collaboration</h4>
                            <p>Perfect for teams and solo developers alike</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Registration Form -->
        <div class="right-side">
            <div class="form-container">
                <div class="form-header">
                    <h2>Create Account</h2>
                    <p>Get started with automated deployments in minutes</p>
                </div>
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus
                                   placeholder="John Doe">
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   placeholder="john@example.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Minimum 8 characters">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @else
                            <div class="form-text">Must be at least 8 characters long</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Re-enter your password">
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-register">
                            <i class="bi bi-person-plus me-2"></i> Create Account
                        </button>
                    </div>
                </form>

                <div class="divider">
                    <span>or</span>
                </div>

                <div class="login-link">
                    <p>Already have an account? <a href="{{ route('login') }}">Sign in instead</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
