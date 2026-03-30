# Hinweisgeberportal

Sicheres Hinweisgeberportal gemäß Hinweisgeberschutzgesetz (HinSchG) für vertrauliche und anonyme Meldungen.

## Tech Stack

### Backend
- Laravel 13 | PHP 8.5
- MySQL / PostgreSQL
- Docker & Nginx

### Frontend
- Next.js 16 | React 19
- TypeScript
- Tailwind CSS

## Features

- Anonyme Hinweisabgabe mit Ende-zu-Ende-Verschlüsselung
- Rollenbasierte Zugriffskontrolle (RBAC)
- Dashboard mit Echtzeit-Statusverfolgung
- Admin-Panel zur Fallbearbeitung
- Sichere bidirektionale Kommunikation

## Setup

### Backend (Docker)

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

Backend verfügbar unter `http://localhost:8000`

### Frontend (Next.js)

```bash
cd frontend
npm install
npm run dev
```

Frontend verfügbar unter `http://localhost:3000`

### Lokal (Backend ohne Docker)

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
composer run dev
```

## API Endpoints

- `GET /api/reports` - Liste aller Reports
- `POST /api/reports` - Neuen Report erstellen
- `PATCH /api/reports/{id}` - Report-Status aktualisieren

## Frontend Pages

- `/` - Landing Page
- `/report` - Report-Formular
- `/admin/reports` - Admin Dashboard

## Architektur

- Service Layer Pattern für Business-Logik
- Repository Pattern für Datenzugriff
- Event-Driven Architecture für Benachrichtigungen
- Queue-basierte asynchrone Verarbeitung

## Lizenz

Proprietary. Alle Rechte vorbehalten.