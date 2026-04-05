# 🛡️ Whistleblower Portal (HinSchG Compliant)

A highly secure, production-ready SaaS application designed to comply with the German Whistleblower Protection Act (HinSchG) and the EU Whistleblower Directive. It provides a secure, anonymous channel for employees and stakeholders to report misconduct, while offering organizations a comprehensive management dashboard to process cases.

---

## 🎯 Project Overview

This platform facilitates bidirectional, anonymous communication between whistleblowers and internal compliance officers. Engineered focusing on strict data privacy, it ensures that reporters can track their case status and exchange messages without ever revealing their identity, utilizing automated cryptographic token generation and strict access control policies.

## ✨ Core Features

### For Whistleblowers
- **Anonymous Reporting**: Submit detailed reports without creating an account.
- **Secure Tracking**: Auto-generated credentials (`WB-XXXXX` + unique passphrase) for returning users.
- **Two-Way Communication**: Anonymous chat interface allowing follow-up questions from admins.
- **File Attachments**: Support for evidential uploads (PDF, DOC, JPG, PNG) up to 10MB per file.
- **Identity Protection**: Complete IP anonymization and zero-knowledge session handling.
- **Registered Reporting**: Optional standard account creation for users who prefer non-anonymous reporting.

### For Administrators
- **Case Management Dashboard**: Centralized view of all incoming reports, categorized by current status.
- **Advanced Filtering**: Filter cases by status, submission type (Anonymous vs. Registered), and keywords.
- **Audit Trails**: Immutable activity logs tracking status changes, message timestamps, and system events.
- **Status Workflows**: Standard cases transitions (`Received` ➡️ `Under Review` ➡️ `Inquiry` ➡️ `Closed`).
- **Identity Revelation Framework**: Strict protocols (logged permanently) for unmasking a registered user's identity if legally required.

## 🛠️ Tech Stack

**Backend Architecture**
- **Framework**: Laravel 11.x (PHP 8.3)
- **Database**: SQLite (Local) / MySQL 8.0 (Production via Docker)
- **Email**: Laravel Mailables + queues for asynchronous notification delivery

**Frontend**
- **Engine**: Laravel Blade templating
- **Styling**: Custom CSS (Utility-driven, pure CSS architecture)
- **Interactivity**: Vanilla JavaScript with AJAX/Fetch API for seamless chat and updates

**DevOps & Deployment**
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx Alpine

## 🏗️ Architecture Overview

The application follows the MVC (Model-View-Controller) design pattern integrated with Repository and Service layers to maintain clean boundaries between HTTP requests and business logic. 

**Key Architectural Decisions:**
1. **Separation of Concerns**: Dedicated `ReportService`, `ActivityLogService`, and `AnonymousCredentialsService` classes.
2. **Strict Authorization**: Extensive use of Laravel Policies and Middleware to prevent unauthorized horizontal or vertical access.
3. **Optimized I/O**: Eager loading (`with()`, `withCount()`) applied globally to prevent N+1 query performance bottlenecks.

## 🔒 Security Posture

Security is the cornerstone of this application, given the sensitivity of whistleblower data.

- **CSPRNG Tokens**: Utilization of cryptographically secure pseudo-random number generators for tracking links.
- **Password Hashing**: Bcrypt encryption for all stored passwords and anonymous passphrases.
- **Protection Against Scraping / Brute Force**: Rate-limiting applied specifically on authentication routes (`throttle:10,1`).
- **Session Isolation**: Strong differentiation between standard authenticated sessions and ephemeral token-based whistleblower sessions.
- **Input Sanitization & CSRF**: Global CSRF protection and strict request validation (`FormRequests`) mitigating SQLi and XSS vectors.

## 🚀 Installation & Setup

### Option 1: Docker (Recommended for Production/Assessment)

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/ephilos-portal.git
   cd ephilos-portal
   ```

2. Setup Environment Variables:
   ```bash
   cp .env.example .env
   ```
   *Note: Docker is configured to use MySQL by default. Modify `DB_*` in `.env` if needed.*

3. Spin up the containers:
   ```bash
   docker compose up -d --build
   ```

4. Install dependencies and run migrations (inside container):
   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```

The application will be accessible at `http://localhost:8000`.

### Option 2: Local Development (SQLite)

1. Ensure PHP 8.3 and Composer are installed.
2. Configure `.env` to use SQLite:
   ```env
   DB_CONNECTION=sqlite
   # Remove DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
   ```
3. Run initialization commands:
   ```bash
   composer install
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate --seed
   php artisan serve
   ```

## 📖 Usage Guide

### 1. The Anonymous Flow
- Navigate to the **Home Page**.
- Select "Anonymous" and submit a mock report.
- Carefully copy the generated Auto-Credentials (`WB-...` and password/link).
- Log out (or open an incognito window), navigate to `/track/{token}`, and log in to view the active chat.

### 2. The Admin Experience
- **Login Credentials** (if seeded via `DatabaseSeeder`):
  - Email: `admin@hinweisgeberportal.de`
  - Password: `password`
- Visit the Dashboard to see incoming reports.
- Reply to the anonymous report you just submitted. Check the audit logs.

## 📸 Screenshots

| Public Reporting Form | Admin Dashboard |
|:---:|:---:|
| <img src="docs/screenshots/form-placeholder.png" width="400" alt="Reporting Form"> | <img src="docs/screenshots/dashboard-placeholder.png" width="400" alt="Admin Dashboard"> |

| Anonymous Chat Interface | Case Detail & Audit Log |
|:---:|:---:|
| <img src="docs/screenshots/chat-placeholder.png" width="400" alt="Chat UI"> | <img src="docs/screenshots/audit-placeholder.png" width="400" alt="Audit Log"> |

*(Placeholders: Add actual screenshots to a `docs/screenshots` folder)*

## 🔮 Future Improvements

Though the portal is production-ready, planned roadmap features include:
- **AES-256 File Encryption**: At-rest encryption for sensitive attachments to protect against server compromise.
- **Two-Factor Authentication (2FA)**: Mandatory TOTP (Google Authenticator) for Admin accounts.
- **Data Export capabilities**: Generating PDF summaries and CSV exports for case archiving.
- **Analytics Module**: Visualizing report volume, categorizations, and resolution times via dynamic charts.

## 👨‍💻 Author

**Khadija Ahmada**
- **Role**: Senior Full-Stack Developer
- **LinkedIn**: [Your LinkedIn Profile](https://linkedin.com/in/yourprofile)
- **GitHub**: [Your GitHub Profile](https://github.com/yourusername)

*Built to demonstrate scalable architecture, robust security principles, and compliant SaaS design.*
