# Hinweisgeberportal (HinSchG-konform)

Eine sichere, produktionsreife Laravel-Anwendung zur Einhaltung des deutschen Hinweisgeberschutzgesetzes (HinSchG) und der EU-Whistleblower-Richtlinie.

Die Plattform ermöglicht die anonyme und sichere Meldung von Fehlverhalten und bietet Organisationen leistungsstarke Werkzeuge zur Verwaltung, Untersuchung und Bearbeitung dieser Meldungen — ohne die Identität der Hinweisgeber zu gefährden.

---

## Inhaltsverzeichnis

 1.⁠ ⁠[Projektübersicht](#projektübersicht)
 2.⁠ ⁠[Hauptfunktionen](#hauptfunktionen)
 3.⁠ ⁠[Technologie-Stack](#technologie-stack)
 4.⁠ ⁠[Systemanforderungen](#systemanforderungen)
 5.⁠ ⁠[Schnellstart mit Docker (empfohlen)](#schnellstart-mit-docker-empfohlen)
 6.⁠ ⁠[Lokale Installation ohne Docker](#lokale-installation-ohne-docker)
 7.⁠ ⁠[Umgebungsvariablen (.env)](#umgebungsvariablen-env)
 8.⁠ ⁠[Datenbank](#datenbank)
 9.⁠ ⁠[Demo-Zugangsdaten](#demo-zugangsdaten)
10.⁠ ⁠[Projektstruktur](#projektstruktur)
11.⁠ ⁠[Routen-Übersicht](#routen-übersicht)
12.⁠ ⁠[Tests ausführen](#tests-ausführen)
13.⁠ ⁠[Fehlerbehebung](#fehlerbehebung)
14.⁠ ⁠[Sicherheitsfunktionen](#sicherheitsfunktionen)
15.⁠ ⁠[Ratenbegrenzung](#ratenbegrenzung)

---

## Projektübersicht

Dieses Projekt implementiert ein vollständiges Hinweisgebersystem mit:

•⁠ ⁠Anonymen *und* registrierten Meldeprozessen
•⁠ ⁠Sicherer Nachverfolgung über tokenbasierten Zugriff (`/track/{token}`)
•⁠ ⁠Zwei-Wege-Kommunikation zwischen Hinweisgeber und Administrator
•⁠ ⁠Vollständiger Audit-Protokollierung und strenger Zugriffskontrolle (Laravel Policies)
•⁠ ⁠Starkem Fokus auf Datenschutz und Sicherheit (IP-Anonymisierung, verschlüsselte Zugangsdaten)

---

## Hauptfunktionen

### Für Hinweisgeber (anonym)
•⁠ ⁠Einreichen einer Meldung ohne Kontoerstellung
•⁠ ⁠Sichere Nachverfolgung durch:
  - Benutzername `WB-XXXXXXXX` (automatisch generiert)
  - Einmalpasswort (12 Zeichen, kryptografisch sicher)
  - Individuelle Zugangs-URL (`/track/{token}`)
•⁠ ⁠Anonymer Zwei-Wege-Chat mit Administratoren
•⁠ ⁠Dateiuploads: PDF, DOC, JPG, PNG, TXT — max. 10 MB
•⁠ ⁠Vollständiger Identitätsschutz (keine IP-Speicherung)

### Für registrierte Benutzer
•⁠ ⁠Erstellung von Meldungen, die mit einem Benutzerkonto verknüpft sind
•⁠ ⁠Persönliches Dashboard, um eigene Meldungen zu verfolgen
•⁠ ⁠Chat- und Benachrichtigungssystem
•⁠ ⁠Die Identität ist standardmäßig vor der Admin-Ansicht verborgen — die Offenlegung erfordert eine explizite, protokollierte Admin-Aktion

### Für Administratoren
•⁠ ⁠Zentrales Dashboard für alle Meldungen
•⁠ ⁠Filter nach Status und Typ (anonym / registriert)
•⁠ ⁠Status-Workflow: `eingegangen` → `in_prüfung` → `untersuchung` → `geschlossen`
•⁠ ⁠Kontrollierte und protokollierte Identitätsoffenlegung
•⁠ ⁠Vollständiger Audit-Verlauf aller Aktionen
•⁠ ⁠Datei-Downloads und Nachrichtenverwaltung

---

## Technologie-Stack

•⁠ ⁠*Backend:* PHP 8.4+, Laravel 13.x
•⁠ ⁠*Auth:* Laravel Sanctum 4, sitzungsbasiert
•⁠ ⁠*Frontend:* Blade-Templates, Vanilla JS, benutzerdefiniertes CSS
•⁠ ⁠*Datenbank:* MySQL 8.0 (Produktion / Docker), SQLite (Tests)
•⁠ ⁠*Webserver:* Nginx (Alpine)
•⁠ ⁠*App-Server:* PHP-FPM 8.4
•⁠ ⁠*Container:* Docker & Docker Compose
•⁠ ⁠*Testing:* PHPUnit 12
•⁠ ⁠*Code-Stil:* Laravel Pint

    *Hinweis:* Das Frontend verwendet klassische Blade-Templates. *Kein Node.js / npm / Vite* erforderlich.

---

## Systemanforderungen

### Option A — Docker (empfohlen)
•⁠ ⁠*Docker* ≥ 20.10
•⁠ ⁠*Docker Compose* ≥ 2.0
•⁠ ⁠Freie Ports: `8000` (Web) und `3306` (MySQL)

### Option B — Lokal (ohne Docker)
•⁠ ⁠*PHP 8.4+* mit folgenden Erweiterungen:
  `pdo`, `pdo_mysql`, `mbstring`, `bcmath`, `gd`, `exif`, `pcntl`, `xml`, `openssl`, `tokenizer`, `ctype`, `fileinfo`
•⁠ ⁠*Composer* ≥ 2.9
•⁠ ⁠*MySQL 8.0+* oder *SQLite 3*

---

## Schnellstart mit Docker (empfohlen)

Mit Docker läuft das Projekt in *unter 5 Minuten*. Migrationen und Seeder werden beim ersten Start **automatisch** ausgeführt.

### 1. Repository klonen

```bash
git clone https://github.com/kahmada/hinweisgeberportal.git
cd hinweisgeberportal
```

### 2. Umgebungsdatei erstellen

```bash
cp .env.example .env
```

Die Standardwerte in `.env.example` sind *bereits auf die Docker-Dienste abgestimmt* (Host `db`, Datenbank `laravel_app`, Benutzer `laravel`, Passwort `secret`). Für den ersten Start sind keine Änderungen erforderlich.

### 3. Container bauen und starten

```bash
docker-compose up -d --build
```

Dies startet drei Dienste:

•⁠ ⁠*app* — Container `app`, interner Port — Laravel-Anwendung (PHP 8.4-FPM)
•⁠ ⁠*nginx* — Container `webserver`, erreichbar unter `8000:80` — Webserver (Reverse Proxy zu PHP-FPM)
•⁠ ⁠*db* — Container `database`, erreichbar unter `3306:3306` — MySQL 8.0 mit einem persistenten Volume

Bei jedem Start führt das Entrypoint-Skript (`docker/entrypoint.sh`) automatisch folgende Schritte aus:

 1.⁠ ⁠*PHP-Abhängigkeiten installieren*, falls `vendor/` fehlt (`composer install`)
 2.⁠ ⁠*`.env` erstellen* aus `.env.example`, falls sie nicht existiert
 3.⁠ ⁠*`APP_KEY` generieren*, falls noch nicht gesetzt (`php artisan key:generate`)
 4.⁠ ⁠*Berechtigungen* für `storage/` und `bootstrap/cache` anpassen
 5.⁠ ⁠*Auf MySQL warten*
 6.⁠ ⁠*Migrationen ausführen* (`php artisan migrate --force`)
 7.⁠ ⁠*Seeder ausführen* (`php artisan db:seed --force` — erstellt den Admin-Benutzer)
 8.⁠ ⁠*Konfiguration und Cache leeren*
 9.⁠ ⁠*PHP-FPM starten*

    *Hinweis:* Der erste Start dauert 1-2 Minuten länger, da Composer die Abhängigkeiten lädt. Folgestarts sind schnell.

### 4. Anwendung öffnen

Öffnen Sie Ihren Browser: **http://localhost:8000**

### 5. Container-Status prüfen

```bash
docker-compose ps
docker-compose logs -f app       # Logs verfolgen
```

### Container stoppen / entfernen

```bash
docker-compose down              # Stoppt Container
docker-compose down -v           # Stoppt und entfernt Volumes (DB-Daten)
```

---

## Lokale Installation ohne Docker

### 1. Klonen und Abhängigkeiten installieren

```bash
git clone https://github.com/kahmada/hinweisgeberportal.git
cd hinweisgeberportal
composer install
```

### 2. Umgebungsdatei einrichten

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Datenbank erstellen

*Option A — MySQL:*

```sql
CREATE DATABASE laravel_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON laravel_app.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
```

Danach `.env` aktualisieren:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_app
DB_USERNAME=laravel
DB_PASSWORD=secret
```

    *Wichtig:* Die Standard-`.env.example` verwendet `DB_HOST=db` (Docker-Dienstname). Wenn Sie lokal *ohne* Docker arbeiten, ändern Sie dies in `127.0.0.1`.

*Option B — SQLite (schnell, für lokale Entwicklung):*

```bash
touch database/database.sqlite
```

In `.env` setzen:

```env
DB_CONNECTION=sqlite
# kommentieren Sie DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD aus
```

### 4. Migrationen und Seeder ausführen

```bash
php artisan migrate
php artisan db:seed
```

Der `DatabaseSeeder` ruft den `AdminUserSeeder` auf, der den Standard-Admin-Benutzer erstellt (siehe [Demo-Zugangsdaten](#demo-zugangsdaten)).

### 5. Storage-Link erstellen (für Dateiuploads)

```bash
php artisan storage:link
```

### 6. Entwicklungsserver starten

```bash
php artisan serve
```

Die Anwendung ist unter **http://localhost:8000** erreichbar.

---

## Umgebungsvariablen (.env)

Vollständige Referenz der Variablen aus `.env.example`. Variablen in *fett* sind obligatorisch für die Produktion.

### Anwendung

```env
APP_NAME=Laravel                  # Anwendungsname
APP_ENV=local                     # local / staging / production
APP_KEY=                          # ERFORDERLICH — wird generiert mit `php artisan key:generate`
APP_DEBUG=true                    # In Produktion auf false setzen
APP_URL=http://localhost          # Öffentliche Basis-URL

APP_LOCALE=en                     # Standardsprache (de / en unterstützt)
APP_FALLBACK_LOCALE=en            # Fallback-Sprache
APP_FAKER_LOCALE=en_US            # Faker-Gebietsschema

APP_MAINTENANCE_DRIVER=file       # Wartungsmodus-Treiber
BCRYPT_ROUNDS=12                  # Bcrypt Arbeitsfaktor
```

### Protokollierung (Logging)

```env
LOG_CHANNEL=stack                 # Protokollierungskanal
LOG_STACK=single                  # Stapelkonfiguration
LOG_DEPRECATIONS_CHANNEL=null     # Kanal für Veraltung
LOG_LEVEL=debug                   # Protokollierungsstufe
```

### Datenbank

```env
DB_CONNECTION=mysql               # Treiber: mysql / sqlite / pgsql
DB_HOST=db                        # ERFORDERLICH — 'db' für Docker, '127.0.0.1' für lokal
DB_PORT=3306                      # ERFORDERLICH
DB_DATABASE=laravel_app           # ERFORDERLICH — Datenbankname
DB_USERNAME=laravel               # ERFORDERLICH — Benutzername
DB_PASSWORD=secret                # ERFORDERLICH — Passwort
DB_ROOT_PASSWORD=root_secret      # MySQL Root-Passwort (nur Docker)
```

    *Hinweis:* `DB_HOST=db` funktioniert nur innerhalb von Docker. Lokal ohne Docker auf `127.0.0.1` ändern.

### Sitzung, Cache, Warteschlange, Dateisystem

```env
SESSION_DRIVER=database           # Sitzungen in der DB speichern
SESSION_LIFETIME=120              # Lebensdauer in Minuten
SESSION_ENCRYPT=false             # Sitzungsdaten verschlüsseln
SESSION_PATH=/                    # Sitzungs-Cookie-Pfad
SESSION_DOMAIN=null               # Sitzungs-Cookie-Domäne

CACHE_STORE=database              # Cache-Treiber
QUEUE_CONNECTION=database         # Warteschlangen-Treiber
BROADCAST_CONNECTION=log          # Broadcast-Treiber
FILESYSTEM_DISK=local             # Standard-Speicherplatte
```

    *Wichtig:* Da `SESSION_DRIVER`, `CACHE_STORE` und `QUEUE_CONNECTION` auf `database` gesetzt sind, *müssen* Migrationen ausgeführt werden, bevor die Anwendung startet.

### Mail

```env
MAIL_MAILER=log                        # "log" schreibt E-Mails nach storage/logs
MAIL_SCHEME=null                       # null / tls / ssl
MAIL_HOST=127.0.0.1                    # SMTP Host
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

NOTIFY_ADMIN_EMAIL=admin@example.com   # Empfänger für Benachrichtigungen bei neuen Meldungen
```

    Für die lokale Entwicklung können Sie `MAIL_MAILER=log` beibehalten. Ausgehende E-Mails werden in `storage/logs/laravel.log` geschrieben.

---

## Datenbank

### Haupttabellen

•⁠ ⁠*`users`* — Admins und registrierte Hinweisgeber (`is_admin` Flag)
•⁠ ⁠*`reports`* — Meldungen (anonym oder registriert)
•⁠ ⁠*`messages`* — Zwei-Wege-Chat zwischen Admin und Hinweisgeber
•⁠ ⁠*`attachments`* — Datei-Uploads an Meldungen angehängt
•⁠ ⁠*`activity_logs`* — Vollständiger Audit-Verlauf aller Aktionen
•⁠ ⁠*`personal_access_tokens`* — Sanctum-API-Tokens
•⁠ ⁠*`sessions`* — Sitzungsspeicher

### Migrationen

Alle Migrationen befinden sich unter `database/migrations/`. Wichtige Migrationen:

•⁠ ⁠`create_reports_table` — Basistabelle für Meldungen
•⁠ ⁠`add_anonymous_fields_to_reports_table` — Anonymitätsfelder
•⁠ ⁠`add_detailed_fields_to_reports_table` — Detailfelder
•⁠ ⁠`add_identity_reveal_to_reports_table` — Identitätsoffenlegungs-Tracking
•⁠ ⁠`create_messages_table` / `create_attachments_table`

### Seeder

•⁠ ⁠*`DatabaseSeeder`* — Hauptseeder, ruft alle anderen Seeder auf
•⁠ ⁠*`AdminUserSeeder`* — Erstellt den Standard-Admin-Benutzer

```bash
php artisan db:seed
```

---

## Demo-Zugangsdaten

Nach dem Seeden steht folgender Admin-Benutzer zur Verfügung:

•⁠ ⁠*E-Mail:* `admin@example.com`
•⁠ ⁠*Passwort:* `password`
•⁠ ⁠*URL:* http://localhost:8000/login

### Anonymer Test-Ablauf

 1.⁠ ⁠Startseite öffnen: `http://localhost:8000`
 2.⁠ ⁠Meldeformular ausfüllen und absenden
 3.⁠ ⁠*Zugangsdaten speichern* (Benutzername `WB-XXXXXXXX`, Passwort, Tracking-Link)
 4.⁠ ⁠Über `/track/{token}` die Meldung erneut aufrufen
 5.⁠ ⁠Als Admin anmelden, um die Meldung im Admin-Bereich einzusehen

---

## Projektstruktur

```text
hinweisgeberportal/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Admin-Controller
│   │   │   ├── Auth/             # Authentifizierung
│   │   │   ├── User/             # Dashboard für registrierte Benutzer
│   │   │   ├── ReportController.php
│   │   │   ├── MessageController.php
│   │   │   └── AttachmentController.php
│   │   └── Middleware/
│   ├── Models/
│   ├── Policies/
│   │   └── ReportPolicy.php      # Zugriffsregeln für Meldungen
│   ├── Services/
│   │   ├── AnonymousCredentialsService.php   # Anonyme Zugangsdaten generieren
│   │   └── ActivityLogService.php            # Audit-Protokollierung
│   └── Notifications/
├── config/                        # Laravel-Konfigurationsdateien
├── database/
│   ├── migrations/                # 10+ Migrationen
│   └── seeders/                   # DatabaseSeeder, AdminUserSeeder
├── docker/
│   ├── entrypoint.sh              # Start-Skript
│   └── nginx/default.conf         # Nginx-Konfiguration
├── resources/
│   ├── views/                     # Blade-Templates
│   └── lang/                      # Übersetzungen (de / en)
├── routes/
│   ├── web.php                    # Web-Routen (Blade)
│   └── api.php                    # API-Routen (Sanctum)
├── tests/
├── Dockerfile                     # PHP 8.4-FPM Image
├── docker-compose.yml             # app + nginx + mysql
└── .env.example
```

---

## Routen-Übersicht

### Öffentlich

•⁠ ⁠`GET  /` — Meldeformular
•⁠ ⁠`GET  /language/{locale}` — Sprache wechseln
•⁠ ⁠`GET  /register` — Benutzerregistrierung
•⁠ ⁠`GET  /login` — Admin-Login
•⁠ ⁠`GET  /track/{token}` — Anonymer Tracking-Login

### Registrierte Benutzer

•⁠ ⁠`GET  /user/dashboard` — Persönliches Dashboard
•⁠ ⁠`GET  /user/reports/{id}` — Eigene Meldung anzeigen

### Admin

•⁠ ⁠`GET  /admin/reports` — Alle Meldungen auflisten
•⁠ ⁠`GET  /admin/reports/{id}` — Detailansicht mit Audit-Log

### API (Sanctum)

•⁠ ⁠`POST  /api/login` — API-Login
•⁠ ⁠`POST  /api/reports` — Meldung erstellen (Ratenbegrenzung: 5/h)
•⁠ ⁠`POST  /api/reports/{id}/attachments` — Datei hochladen (10/h)
•⁠ ⁠`GET   /api/reports` — Meldungen auflisten (Admin)
•⁠ ⁠`PATCH /api/reports/{id}` — Status aktualisieren (Admin)
•⁠ ⁠`POST  /api/reports/{id}/reveal-identity` — Identität offenlegen (Admin)
•⁠ ⁠`GET   /api/reports/{id}/messages` — Nachrichten auflisten
•⁠ ⁠`POST  /api/reports/{id}/messages` — Nachricht senden

---

## Tests ausführen

Tests verwenden eine In-Memory-SQLite-Datenbank und erfordern keine zusätzliche Konfiguration.

```bash
# Docker
docker-compose exec app php artisan test

# Lokal
php artisan test
```

### Code-Stil

```bash
./vendor/bin/pint          # Automatisch formatieren
./vendor/bin/pint --test   # Nur prüfen
```

---

## Fehlerbehebung

### `SQLSTATE[HY000] [2002] Connection refused`
Die Datenbank ist noch nicht bereit. Warten Sie einige Sekunden und versuchen Sie es erneut.

### `No application encryption key has been specified`
```bash
php artisan key:generate
```

### Port 8000 oder 3306 bereits belegt
Ändern Sie die Ports in `docker-compose.yml`, z.B. `8080:80` statt `8000:80`.

### Cache-Probleme nach Änderungen
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Lokale Ausführung: `getaddrinfo ... for db failed`
Sie arbeiten lokal ohne Docker, aber `.env` enthält noch `DB_HOST=db`. Ändern Sie dies zu `DB_HOST=127.0.0.1`.

---

## Sicherheitsfunktionen

•⁠ ⁠Kryptografisch sichere Token-Generierung (`random_bytes`)
•⁠ ⁠Passwort-Hashing mit Bcrypt (12 Runden)
•⁠ ⁠CSRF-Schutz für alle Formulare
•⁠ ⁠IP-Anonymisierung für anonyme Hinweisgeber
•⁠ ⁠Audit-Protokollierung von Statusänderungen und Zugriffen
•⁠ ⁠Strenge Autorisierung über Laravel Policies

---

## Ratenbegrenzung

Die Anwendung schützt sensible Endpunkte mit der Laravel `throttle` Middleware:

•⁠ ⁠*`POST /api/reports`* → `throttle:5,60` — *5 Meldeeinreichungen pro 60 Minuten* pro IP
•⁠ ⁠*`POST /api/reports/{report}/attachments`* → `throttle:10,60` — *10 Dateiuploads pro 60 Minuten* pro IP
•⁠ ⁠*`POST /track/login`* → `throttle:10,1` — *10 anonyme Anmeldeversuche pro Minute* pro IP
•⁠ ⁠*Admin-Login-Formular* → *5 fehlgeschlagene Versuche* vor Sperrung.

Bei Tests können Sie die Ratenbegrenzung durch Leeren des Caches zurücksetzen:

```bash
php artisan cache:clear
```

---

## Lizenz

Dieses Projekt wurde im Rahmen einer Projektaufgabe entwickelt.