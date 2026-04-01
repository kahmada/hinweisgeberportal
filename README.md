# 🔒 Hinweisgeberportal

Sicheres Hinweisgeberportal gemäß **Hinweisgeberschutzgesetz (HinSchG)** für vertrauliche und anonyme Meldungen.

## ✨ Features

### Für Whistleblower
- ✅ **Vollständig anonyme Hinweisabgabe** ohne Registrierung
- ✅ **Automatisch generierte Zugangsdaten** (Username + Passwort)
- ✅ **Multi-Step Formular** mit allen erforderlichen Feldern
- ✅ **Datei-Upload** für Beweise und Dokumente (max. 5 Dateien, je 10MB)
- ✅ **Sichere Kommunikation** mit der internen Stelle
- ✅ **Status-Verfolgung** des eigenen Hinweises
- ✅ **Geschützte Identität** - keine persönlichen Daten gespeichert

### Für Administratoren
- ✅ **Übersichtliches Dashboard** mit Statistiken
- ✅ **Anonymisierte Darstellung** aller Hinweise
- ✅ **Statusverwaltung** (Eingegangen, In Prüfung, Rückfrage, Abgeschlossen)
- ✅ **Bidirektionale Kommunikation** mit Whistleblowern
- ✅ **Filter-Funktionen** nach Status und Typ
- ✅ **Zugriff auf Anhänge** und vollständige Hinweis-Details

## 🛠️ Tech Stack

### Backend
- **Laravel 13** | PHP 8.5
- **SQLite** (einfach zu MySQL/PostgreSQL wechselbar)
- **Laravel Sanctum** für API-Authentifizierung

### Frontend
- **Blade Templates** mit Vanilla JavaScript
- **Responsive Design** (Mobile-friendly)
- **Multi-Step Forms** mit Client-Side Validation

## 📋 Erfüllte Anforderungen (HinSchG)

### ✅ Registrierung und Anmeldung
- Anonyme Hinweisabgabe ohne Registrierung
- Automatische Generierung von Zugangsdaten
- Sichere Session-basierte Authentifizierung

### ✅ Anonyme Hinweisabgabe
- Vollständig anonyme Submission möglich
- Automatischer "Dummy-Zugang" nach Absenden
- Eindeutige Login-Daten für späteren Zugriff
- Kommunikation ohne Aufhebung der Anonymität

### ✅ Hinweisformular
- Welche Gesellschaft ist involviert?
- Was ist der Auflegung? (Art des Verstoßes)
- Wann ist es passiert?
- Wo ist es passiert?
- Wer ist beteiligt oder betroffen?
- Einzelheiten zu Ihrem Bericht
- Upload-Möglichkeit für Anhänge

### ✅ Admin-Bereich
- Alle eingegangenen Hinweise sichtbar
- Grundsätzlich anonymisierte Darstellung
- Persönliche Daten nicht ohne Weiteres sichtbar
- Filter-, Öffnen- und Bearbeitungsfunktionen
- Statusverwaltung implementiert

### ✅ Beantwortung von Hinweisen
- Admins können Rückfragen/Antworten hinterlegen
- Whistleblower sehen Antworten über ihren Zugang
- Wechselseitige Kommunikation ohne Anonymitätsverlust

## 🚀 Installation & Setup

### Voraussetzungen
- PHP 8.3+
- Composer
- Node.js & NPM (optional, für Frontend-Assets)

### Schnellstart

```bash
# 1. Repository klonen
git clone <repository-url>
cd hinweisgeberportal

# 2. Dependencies installieren
composer install

# 3. Environment konfigurieren
cp .env.example .env
php artisan key:generate

# 4. Datenbank erstellen
touch database/database.sqlite

# 5. Migrationen ausführen
php artisan migrate

# 6. Admin-Benutzer erstellen
php artisan db:seed

# 7. Server starten
php artisan serve
```

Die Anwendung ist nun verfügbar unter: **http://127.0.0.1:8000**

## 👤 Standard-Zugangsdaten

### Admin-Login
- **URL:** http://127.0.0.1:8000/login
- **Email:** admin@example.com
- **Passwort:** password

### Whistleblower
- Keine Registrierung erforderlich
- Zugangsdaten werden nach Hinweis-Einreichung automatisch generiert

## 📁 Projektstruktur

```
hinweisgeberportal/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/ReportController.php    # Admin Dashboard
│   │   ├── ReportController.php          # Hinweis-Verwaltung
│   │   ├── MessageController.php         # Kommunikation
│   │   └── AttachmentController.php      # Datei-Upload
│   ├── Models/
│   │   ├── Report.php                    # Hinweis-Model
│   │   ├── Message.php                   # Nachrichten-Model
│   │   ├── Attachment.php                # Anhänge-Model
│   │   └── User.php                      # Admin-Benutzer
│   └── Services/
│       └── AnonymousCredentialsService.php # Credentials-Generierung
├── database/
│   ├── migrations/                       # Datenbank-Schema
│   └── seeders/
│       └── AdminUserSeeder.php           # Admin-Benutzer Seeder
├── resources/views/
│   ├── report_form.blade.php             # Hinweis-Formular
│   ├── track/
│   │   ├── login.blade.php               # Whistleblower Login
│   │   └── view.blade.php                # Hinweis-Ansicht
│   └── admin/
│       ├── reports.blade.php             # Admin Dashboard
│       └── report-detail.blade.php       # Hinweis-Details
└── routes/
    ├── web.php                           # Web-Routen
    └── api.php                           # API-Routen
```

## 🔐 Sicherheitsfeatures

- **Anonymität geschützt:** Keine Speicherung persönlicher Daten
- **Sichere Passwörter:** Bcrypt-Hashing mit 12 Rounds
- **CSRF-Protection:** Laravel's eingebauter CSRF-Schutz
- **Session-basierte Auth:** Für Whistleblower
- **Token-basierte Auth:** Sanctum für Admin-API
- **Datei-Validierung:** Typ- und Größen-Beschränkungen
- **Zugriffskontrolle:** Middleware für geschützte Bereiche

## 📊 Datenbank-Schema

### Reports (Hinweise)
- Titel, Beschreibung, Status
- Gesellschaft, Verstoßart, Datum, Ort
- Beteiligte Personen
- Anonyme Zugangsdaten (Username, Password-Hash, Token)

### Messages (Kommunikation)
- Bidirektionale Nachrichten
- Sender-Typ (Admin/Whistleblower)
- Gelesen-Status

### Attachments (Anhänge)
- Dateiname, Original-Name
- MIME-Type, Größe
- Verknüpfung zum Report

## 🧪 Testing

```bash
# Unit & Feature Tests ausführen
php artisan test

# Spezifische Tests
php artisan test --filter=ReportTest
```

## 📝 API Endpoints

### Public
- `POST /api/reports` - Hinweis einreichen
- `POST /api/reports/{id}/attachments` - Dateien hochladen

### Authenticated (Admin)
- `GET /api/reports` - Alle Hinweise abrufen
- `PATCH /api/reports/{id}` - Status aktualisieren
- `GET /api/reports/{id}/messages` - Nachrichten abrufen
- `POST /api/reports/{id}/messages` - Nachricht senden

### Session-based (Whistleblower)
- `GET /report/{id}/messages` - Eigene Nachrichten
- `POST /report/{id}/messages` - Nachricht senden
- `GET /attachments/{id}/download` - Anhang herunterladen

## 🌍 Deployment

### Produktions-Checkliste
- [ ] `.env` für Produktion konfigurieren
- [ ] `APP_ENV=production` setzen
- [ ] `APP_DEBUG=false` setzen
- [ ] Starkes `APP_KEY` generieren
- [ ] Datenbank-Credentials aktualisieren
- [ ] HTTPS aktivieren
- [ ] Regelmäßige Backups einrichten
- [ ] Logs überwachen

## 📄 Lizenz

Proprietary. Alle Rechte vorbehalten.

## 🤝 Support

Bei Fragen oder Problemen:
- Issue erstellen im Repository
- Dokumentation konsultieren
- Admin kontaktieren

---

**Entwickelt gemäß Hinweisgeberschutzgesetz (HinSchG)**
