# 🛡️ Whistleblower-Portal (HinSchG-konform)

Eine sichere und produktionsreife SaaS-Anwendung zur Einhaltung des deutschen Hinweisgeberschutzgesetzes (HinSchG) sowie der EU-Whistleblower-Richtlinie.

Die Plattform ermöglicht anonyme und sichere Meldungen von Fehlverhalten und bietet Organisationen leistungsstarke Werkzeuge zur Verwaltung, Untersuchung und Bearbeitung dieser Meldungen — ohne die Identität der Hinweisgeber zu gefährden.

---

## 🎯 Projektübersicht

Dieses Projekt implementiert ein vollständiges Whistleblower-System mit:

- Anonymen und registrierten Meldeprozessen  
- Sicherem Tracking über tokenbasierten Zugriff  
- Zwei-Wege-Kommunikation zwischen Hinweisgeber und Administrator  
- Vollständigem Audit-Logging und strikter Zugriffskontrolle  
- Starker Fokus auf Datenschutz und Sicherheit  

Das System simuliert eine reale SaaS-Compliance-Plattform.

---

## ✨ Hauptfunktionen

### 🕵️ Für Hinweisgeber

- Anonyme Meldung (kein Konto erforderlich)  
- Sicheres Tracking über:  
  - `WB-XXXXXXXX` Benutzername  
  - Einmalpasswort  
  - Individuelle Zugriffs-URL (`/track/{token}`)  
- Anonymer Zwei-Wege-Chat mit Administratoren  
- Datei-Uploads (PDF, DOC, JPG, PNG, TXT — max. 10MB)  
- Vollständiger Identitätsschutz (keine IP-Speicherung, keine personenbezogenen Daten erforderlich)  

---

### 👤 Für registrierte Benutzer

- Meldungserstellung mit Benutzerkonto  
- Persönliches Dashboard zur Verfolgung von Meldungen  
- Chat- und Benachrichtigungssystem  
- Identität standardmäßig im Admin-Bereich verborgen  

---

### 🛠️ Für Administratoren

- Zentrales Dashboard für alle Meldungen  
- Filtermöglichkeiten:
  - Status  
  - Typ (anonym / registriert)  
- Statusverwaltung:
  - Eingegangen → In Prüfung → Untersuchung → Geschlossen  
- Kontrollierte und protokollierte Identitätsfreigabe  
- Vollständige Audit-Historie aller Aktionen  
- Zugriff auf Dateien und Nachrichtenverwaltung  

---

## 🏗️ Architekturübersicht

Das System basiert auf einer sauberen MVC-Architektur mit Service- und Policy-Schichten:

- **Controller** → Verarbeitung von HTTP-Anfragen  
- **Form Requests** → Validierung  
- **Services** → Geschäftslogik  
- **Policies** → Autorisierung  
- **Models** → Datenbankstruktur  

### Zentrale Komponenten:

- `AnonymousCredentialsService` → Generierung sicherer Zugangsdaten  
- `ActivityLogService` → Audit-Logging mit Anonymisierung  
- `ReportPolicy` → Zugriffskontrolle (Anzeigen, Bearbeiten, Identitätsfreigabe)  

---

## 🛠️ Technologie-Stack

### Backend
- Laravel 13 (PHP 8.3+)  
- Authentifizierung: Laravel Sanctum + Sessions  
- E-Mail: Laravel Mail (Queue-basiert)  

### Frontend
- Blade Templates  
- Vanilla JavaScript (AJAX / Fetch)  
- Custom CSS  

### Datenbank
- MySQL 8 (Docker)  
- SQLite (lokale Entwicklung)  

### DevOps
- Docker & Docker Compose  
- Nginx (Reverse Proxy)  
- PHP-FPM  

---

## 🔒 Sicherheitsfunktionen

- 🔐 Kryptografisch sichere Token-Generierung  
- 🔑 Passwort-Hashing mit Bcrypt  
- 🛡️ CSRF-Schutz für alle Formulare  
- 🚫 Rate Limiting:
  - Meldungen: 5/Stunde  
  - Login: 10/Minute  
- 🕵️ IP-Anonymisierung für Hinweisgeber  
- 🔍 Audit-Logging von:
  - Statusänderungen  
  - Identitätsfreigaben  
  - Zugriffen auf Meldungen  
- 🔒 Strikte Autorisierung über Policies & Middleware  

---

## 🔑 Demo-Zugang

### Admin
- **E-Mail:** admin@example.com  
- **Passwort:** password  

### Anonymer Ablauf
1. Meldung erstellen  
2. Zugangsdaten speichern  
3. Zugriff über `/track/{token}`  

---

## 🚀 Installation & Setup

### 🐳 Docker (empfohlen)

```bash
git clone https://github.com/kahmada/hinweisgeberportal.git
cd hinweisgeberportal

cp .env.example .env

docker-compose up -d --build
