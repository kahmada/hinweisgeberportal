# Hinweisgeberportal

Sicheres Hinweisgeberportal gemäß Hinweisgeberschutzgesetz (HinSchG) für vertrauliche und anonyme Meldungen.

## Tech Stack

- Laravel 13 | PHP 8.5
- MySQL / PostgreSQL
- Docker & Nginx
- Vite | TailwindCSS

## Features

- Anonyme Hinweisabgabe mit Ende-zu-Ende-Verschlüsselung
- Rollenbasierte Zugriffskontrolle (RBAC)
- Dashboard mit Echtzeit-Statusverfolgung
- Admin-Panel zur Fallbearbeitung
- Sichere bidirektionale Kommunikation

## Setup

### Docker (Empfohlen)

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

Anwendung verfügbar unter `http://localhost:8000`

### Lokal

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
composer run dev
```

## Architektur

- Service Layer Pattern für Business-Logik
- Repository Pattern für Datenzugriff
- Event-Driven Architecture für Benachrichtigungen
- Queue-basierte asynchrone Verarbeitung

## Lizenz

Proprietary. Alle Rechte vorbehalten.