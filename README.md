# NORSU-GUIHULNGAN Queue System

A Laravel-based queue management system with real-time ticket display, kiosk interface, and teller management.

## Requirements

- **PHP**: 8.2 or higher
- **Laravel**: 12.0
- **PostgreSQL**: 12 or higher
- **Composer**: Latest version
- **Node.js**: v18 or higher with npm

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd "Queueing Cashier"
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env`:

```bash
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Database Setup

**Create PostgreSQL Database:**

```sql
CREATE DATABASE norsu_queue_system;
```

**Configure `.env` file:**

```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=norsu_queue_system
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Install Node Dependencies

```bash
npm install
```

### 8. Build Frontend Assets

**For production:**
```bash
npm run build
```

**For development:**
```bash
npm run dev
```

## Running the Application

### Start Laravel Reverb Server

Open a **separate terminal** and run:

```bash
php artisan reverb:start
```

**Important:** Keep this terminal open. Reverb must be running for real-time features to work.

### Start Laravel Server

**Option 1: Using Laravel's built-in server**
```bash
php artisan serve
```

**Option 2: Using XAMPP Apache**
- Start Apache from XAMPP Control Panel
- Access via: `http://localhost/Queueing Cashier/public`

## Access URLs

- **Kiosk**: `http://localhost/kiosk`
- **Display Monitor**: `http://localhost/display`
- **Admin Dashboard**: `http://localhost/admin/dashboard`
- **Teller Dashboard**: `http://localhost/teller/dashboard`
- **Login**: `http://localhost/login`

## Environment Variables

Key variables in `.env`:

```env
# Database
DB_CONNECTION=pgsql
DB_DATABASE=norsu_queue_system

# Reverb WebSocket
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Kiosk Security
KIOSK_SECRET_KEY=your-secret-key-here
```

## Quick Start Checklist

- [ ] Clone repository
- [ ] Run `composer install`
- [ ] Copy `.env.example` to `.env`
- [ ] Run `php artisan key:generate`
- [ ] Create PostgreSQL database `norsu_queue_system`
- [ ] Update database credentials in `.env`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan storage:link`
- [ ] Run `npm install`
- [ ] Run `npm run build`
- [ ] Start Reverb: `php artisan reverb:start` (separate terminal)
- [ ] Start Laravel: `php artisan serve`

## Production Deployment & Setup

### Step 1: Update from Repository

Run the deployment script to pull latest changes and update dependencies:

```bash
deploy.bat
```

This script will:
- Pull latest changes from git master
- Install PHP dependencies (Composer)
- Copy `.env.public` to `.env` (if needed)
- Generate application key (if needed)
- Run database migrations
- Create storage link
- Install Node.js dependencies
- Build frontend assets
- Clear application caches

### Step 2: Configure Apache Virtual Host

**Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`** and add:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@queueing.local
    DocumentRoot "C:/xampp/htdocs/Queueing Cashier/public"
    ServerName queueing.local
    ServerAlias 192.168.0.100
    
    <Directory "C:/xampp/htdocs/Queueing Cashier/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/queueing.local-error.log"
    CustomLog "logs/queueing.local-access.log" common
</VirtualHost>
```

**Important:** 
- Replace `192.168.0.100` with your actual server IP address
- Ensure the path uses forward slashes (`/`) not backslashes (`\`)
- Update `ServerAlias` to match your server IP

**Enable virtual hosts** in `C:\xampp\apache\conf\httpd.conf`:
- Ensure this line is uncommented: `Include conf/extra/httpd-vhosts.conf`
- Restart Apache after making changes

### Step 3: Configure Windows Hosts File

**Edit `C:\Windows\System32\drivers\etc\hosts`** (as Administrator) and add at the end:

```
192.168.1.100    queueing.local
192.168.1.100    www.queueing.local
```

**Important:** 
- Replace `192.168.1.100` with your actual server IP address
- **Note:** The IP in hosts file should match the `ServerAlias` in the virtual host configuration (update both to use the same IP)
- Save the file (you may need to run Notepad as Administrator)

### Step 4: Install Startup Files

Run the installation script to set up auto-start:

```bash
install-startup.bat
```

**Run as Administrator** (right-click → Run as administrator)

This will:
- Copy `start-reverb-background.vbs` to Windows Startup folder
- Create XAMPP Control Panel shortcut in Startup folder
- Enable automatic startup of services

**Verify installation:**
- Press `Win + R`
- Type: `shell:startup`
- Check if both files are present

### Step 5: Restart Computer

**Restart your computer.** After restart:
- XAMPP Control Panel will start automatically
- Laravel Reverb will start automatically (no security warnings)
- All services will be ready to use

**Access the application:**
- From server: `http://queueing.local` or `http://192.168.1.100`
- From network: `http://192.168.1.100` (replace with your server IP)

### Step 6: Verify Installation

1. **Check Apache:** Open `http://queueing.local` or `http://192.168.1.100` (replace with your server IP)
2. **Check Reverb:** Verify WebSocket connection in browser console (should connect to `ws://192.168.1.100:8080`)
3. **Check Services:** Open Task Manager → Check for `php.exe` process running Reverb
4. **Check Logs:** Verify no errors in Apache error logs: `C:\xampp\apache\logs\queueing.local-error.log`

## Updating the System

To update the system with latest changes from repository:

1. **Run deployment script:**
   ```bash
   deploy.bat
   ```

2. **Restart services** (if needed):
   - Restart Apache from XAMPP Control Panel
   - Reverb will restart automatically if using startup script

That's it! The system will be updated with latest code, dependencies, and migrations.