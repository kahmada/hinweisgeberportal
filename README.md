# Hinweisgeberportal (HinSchG-konform)

A secure Laravel-based whistleblower system compliant with the German Whistleblower Protection Act (HinSchG) and EU directive.

---

## 🇩🇪 Deutsche Version

### 📌 Übersicht
Das Hinweisgeberportal ermöglicht die **anonyme und sichere Meldung von Fehlverhalten**.  
Es schützt die Identität der Hinweisgeber und bietet Organisationen Tools zur Verwaltung von Meldungen.

### ⚙️ Funktionen
- Anonyme & registrierte Meldungen  
- Token-basiertes Tracking (`/track/{token}`)  
- Zwei-Wege-Kommunikation (Chat)  
- Admin-Dashboard mit Status-Workflow  
- Datei-Uploads (PDF, DOC, JPG, PNG, TXT)  
- Audit-Logs & Datenschutz (keine IP-Speicherung)

### 🛠️ Technologie
- Laravel (PHP 8.4+)  
- MySQL / SQLite  
- Docker & Nginx  
- Blade Templates (kein Node.js)

### 🚀 Schnellstart (Docker)
```bash
git clone https://github.com/kahmada/hinweisgeberportal.git
cd hinweisgeberportal
cp .env.example .env
docker-compose up -d --build
