# Hinweisgeberportal - Frontend

Next.js frontend for the Whistleblower Portal application.

## Tech Stack

- Next.js 16
- React 19
- TypeScript
- Tailwind CSS

## Features

- **Submit Report** (`/report`) - Anonymous report submission form
- **Admin Dashboard** (`/admin/reports`) - View and manage all reports with status updates

## Prerequisites

- Node.js >= 18
- Backend Laravel API running on `http://localhost:8000`

## Installation

```bash
cd frontend
npm install
```

## Development

```bash
npm run dev
```

The application will be available at `http://localhost:3000`

## Pages

### Home (`/`)
Landing page with navigation to report submission and admin dashboard.

### Submit Report (`/report`)
- Form with title and description fields
- Submits to Laravel API endpoint `POST /api/reports`
- Shows success/error messages

### Admin Dashboard (`/admin/reports`)
- Lists all reports from `GET /api/reports`
- Displays: ID, title, description, status, created date
- Update status via dropdown (open, under_review, closed)
- Updates via `PATCH /api/reports/{id}`

## API Endpoints

The frontend connects to these Laravel backend endpoints:

- `GET /api/reports` - Fetch all reports
- `POST /api/reports` - Create new report
- `PATCH /api/reports/{id}` - Update report status

## Build

```bash
npm run build
npm start
```

## Project Structure

```
frontend/
├── app/
│   ├── page.tsx              # Home page
│   ├── report/
│   │   └── page.tsx          # Report submission form
│   └── admin/
│       └── reports/
│           └── page.tsx      # Admin dashboard
├── public/                   # Static assets
└── package.json
```
