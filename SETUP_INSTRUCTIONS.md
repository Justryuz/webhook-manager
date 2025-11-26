# 🎯 Setup Instructions - Git Webhook Manager

## Langkah Mudah Setup (Bahasa Melayu)

### 1️⃣ Install Dependencies
```bash
composer install
npm install
```

### 2️⃣ Setup Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3️⃣ Konfigurasi Database (.env)
```env
# Untuk SQLite (default - paling mudah)
DB_CONNECTION=sqlite

# ATAU untuk MySQL
DB_CONNECTION=mysql
DB_DATABASE=git_webhook
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4️⃣ Run Migration
```bash
php artisan migrate
```

### 5️⃣ Build Assets
```bash
npm run build
```

### 6️⃣ Start Application

**Terminal 1 - Web Server:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker (WAJIB!):**
```bash
php artisan queue:work
```

Buka browser: **http://localhost:8000**

---

## 📝 Cara Guna

### Buat Webhook Pertama

1. **Klik "Create Webhook"**
   
2. **Isi maklumat:**
   - **Name:** Nama project (contoh: "Website Syarikat")
   - **Domain:** Domain website (optional)
   - **Git Provider:** Pilih GitHub atau GitLab
   - **Repository URL:** URL repo git (contoh: `git@github.com:username/repo.git`)
   - **Branch:** Branch nak deploy (contoh: `main`)
   - **Local Path:** Path mana nak simpan (contoh: `/var/www/html/myproject`)
   - ✅ Tick "Auto-generate SSH Key Pair"

3. **Klik "Create Webhook"**

### Setup GitHub/GitLab

#### Untuk GitHub:

**A. Add Deploy Key:**
1. Copy **Public SSH Key** dari webhook details
2. Pergi GitHub → Repository → Settings → Deploy keys → Add deploy key
3. Paste public key
4. Save (tak perlu write access)

**B. Add Webhook:**
1. Copy **Webhook URL** dan **Secret Token** dari webhook details
2. Pergi GitHub → Repository → Settings → Webhooks → Add webhook
3. Paste:
   - **Payload URL:** [URL webhook anda]
   - **Content type:** `application/json`
   - **Secret:** [Token secret anda]
4. Pilih "Just the push event"
5. Add webhook

#### Untuk GitLab:

**A. Add Deploy Key:**
1. Copy **Public SSH Key** dari webhook details
2. Pergi GitLab → Repository → Settings → Repository → Deploy Keys
3. Paste dan add key

**B. Add Webhook:**
1. Copy **Webhook URL** dan **Secret Token** 
2. Pergi GitLab → Settings → Webhooks
3. Paste URL dan Secret Token
4. Pilih "Push events"
5. Add webhook

---

## 🚀 Deploy Scripts (Contoh)

### Untuk Laravel Project:
```bash
#!/bin/bash
# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build frontend
npm install --production
npm run build

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### Untuk Node.js/React:
```bash
#!/bin/bash
npm install --production
npm run build

# Restart PM2 (kalau guna)
pm2 restart app-name
```

### Untuk Static Site:
```bash
#!/bin/bash
npm install
npm run build

# Copy build ke web directory
cp -r dist/* /var/www/html/
```

---

## ⚠️ Penting!

### Queue Worker Mesti Running
Deployment tak akan jalan kalau queue worker tak running!
```bash
php artisan queue:work
```

### Permission Folder
Pastikan folder deployment boleh write:
```bash
# Check permission
ls -la /path/to/deployment

# Set permission (kalau perlu)
sudo chown -R $USER:www-data /var/www/html/myproject
sudo chmod -R 775 /var/www/html/myproject
```

### SSH Keys
- Private repos **MESTI** guna SSH key
- Public repos boleh guna HTTPS
- SSH key auto-generate untuk setiap webhook

---

## 🐛 Troubleshooting

### Deployment Stuck "Pending"
❌ **Masalah:** Deployment tak jalan, stuck pending

✅ **Penyelesaian:** Queue worker tak running
```bash
php artisan queue:work
```

### Permission Denied (SSH)
❌ **Masalah:** Error "Permission denied" masa git clone/pull

✅ **Penyelesaian:** 
- Pastikan SSH key dah add ke GitHub/GitLab
- Check public key sama dengan yang dalam git provider

### Cannot Write to Directory
❌ **Masalah:** Error tulis ke folder deployment

✅ **Penyelesaian:**
```bash
sudo chown -R $USER:www-data /var/www/html/myproject
sudo chmod -R 775 /var/www/html/myproject
```

### Webhook Tidak Trigger
❌ **Masalah:** Push code tapi tak auto deploy

✅ **Penyelesaian:**
- Check webhook URL betul
- Check secret token sama
- Check webhook status "Active" 
- Tengok webhook delivery logs dalam GitHub/GitLab

---

## 🎨 Features

- ✅ Auto SSH key generation (unique per webhook)
- ✅ Support GitHub & GitLab
- ✅ Beautiful Bootstrap 5 dashboard
- ✅ Deployment history dengan logs
- ✅ Pre/Post deploy scripts
- ✅ Manual trigger deployment
- ✅ Webhook signature verification
- ✅ Queue system (background processing)
- ✅ PSR-compliant code

---

## 📚 Dokumentasi Lengkap

- **Quick Start:** [QUICKSTART.md](QUICKSTART.md)
- **Full Documentation:** [README.md](README.md)

---

## 💡 Tips

1. **Test dulu dengan manual deploy** sebelum setup webhook
2. **Check logs** kalau deployment failed
3. **Guna SQLite** untuk development (lebih mudah)
4. **Guna MySQL/PostgreSQL** untuk production
5. **Setup Supervisor** untuk production (auto-restart queue worker)

---

**Selamat menggunakan! 🎉**

Kalau ada masalah, check documentation atau deployment logs dalam UI.
