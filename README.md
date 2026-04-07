# 🛡️ Whistleblower Portal (HinSchG Compliant)

A secure and production-ready SaaS application designed to comply with the German Hinweisgeberschutzgesetz (HinSchG) and the EU Whistleblower Directive.

The platform enables anonymous and secure reporting of misconduct, while providing organizations with powerful tools to manage, investigate, and respond to reports — without compromising the identity of the whistleblower.

## 🎯 Project Overview

This project implements a complete whistleblower system with:

- Anonymous and registered reporting flows
- Secure tracking via token-based access
- Two-way communication between whistleblower and admin
- Full audit logging and strict access control
- Strong focus on data protection and security

The system is designed to simulate a real-world SaaS compliance platform.

## ✨ Core Features

### 🕵️ For Whistleblowers
- Anonymous report submission (no account required)
- Secure tracking via:
  - `WB-XXXXXXXX` username
  - One-time password
  - Unique access URL (`/track/{token}`)
- Two-way anonymous chat with administrators
- File attachments (PDF, DOC, JPG, PNG, TXT — max 10MB)
- Full identity protection (no IP logging, no personal data required)

### 👤 For Registered Users
- Account-based report submission
- Personal dashboard to track reports
- Chat and notification system
- Identity hidden by default in admin view

### 🛠️ For Administrators
- Central dashboard for all reports
- Filtering by:
  - Status
  - Type (anonymous / registered)
- Status management:
  - Received → Under Review → Inquiry → Closed
- Secure identity reveal (logged and controlled)
- Full audit trail of all actions
- File access and message management

## 🏗️ Architecture Overview

The system follows a clean MVC architecture with service and policy layers:

- **Controllers** → handle HTTP requests
- **Form Requests** → validation
- **Services** → business logic
- **Policies** → authorization
- **Models** → database structure

**Key Components:**
- `AnonymousCredentialsService` → generates secure credentials
- `ActivityLogService` → audit logging with anonymization
- `ReportPolicy` → access control (view, update, reveal identity)

## 🛠️ Tech Stack

**Backend**
- Laravel 13 (PHP 8.3+)
- Authentication: Laravel Sanctum + Sessions
- Email: Laravel Mail (queued)

**Frontend**
- Blade Templates
- Vanilla JavaScript (AJAX / Fetch)
- Custom CSS

**Database**
- MySQL 8 (Docker)
- SQLite (local development)

**DevOps**
- Docker & Docker Compose
- Nginx (reverse proxy)
- PHP-FPM

## 🔒 Security Features

Security is a core part of the system:

- 🔐 Cryptographically secure token generation
- 🔑 Bcrypt password hashing
- 🛡️ CSRF protection on all forms
- 🚫 Rate limiting:
  - Reports: 5/hour
  - Login: 10/min
- 🕵️ IP anonymization for whistleblowers
- 🔍 Audit logging of:
  - Status changes
  - Identity reveals
  - Report access
- 🔒 Strict authorization via Policies & Middleware

## 🔑 Demo Access

**Admin**
- **Email:** admin@example.com
- **Password:** password

**Anonymous Flow**
1. Submit a report
2. Save generated credentials
3. Access via `/track/{token}`

## 🚀 Installation & Setup

### 🐳 Option 1: Docker (Recommended)
```bash
git clone https://github.com/yourusername/hinweisgeberportal.git
cd hinweisgeberportal

cp .env.example .env

docker-compose up -d --build
```
App will be available at: http://localhost:8000

### 💻 Option 2: Local Development (SQLite)
```bash
composer install
cp .env.example .env

php artisan key:generate

touch database/database.sqlite

php artisan migrate --seed
php artisan serve
```

## 📖 Usage

**Anonymous Reporting**
1. Go to `/`
2. Submit report via 4-step form
3. Save credentials
4. Track via `/track/{token}`

**Admin Workflow**
1. Login via `/login`
2. View reports in dashboard
3. Change status
4. Reply via chat
5. Check audit logs

## 📂 Project Structure
```text
app/
 ├── Http/
 ├── Models/
 ├── Services/
 ├── Policies/
 ├── Mail/

resources/views/
routes/
database/
docker/
```
