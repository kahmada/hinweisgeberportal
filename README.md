# Whistleblower Portal (HinSchG Compliant)

A secure Laravel-based application for anonymous whistleblowing, compliant with the German Whistleblower Protection Act (HinSchG) and the EU directive.

---

## 📌 Overview

The Whistleblower Portal enables **secure and anonymous reporting of misconduct**.  
It protects the identity of whistleblowers while providing organizations with tools to manage and investigate reports.

---

## ⚙️ Features

- Anonymous & registered reporting  
- Secure tracking via token (`/track/{token}`)  
- Two-way communication (chat system)  
- Admin dashboard with status workflow  
- File uploads (PDF, DOC, JPG, PNG, TXT)  
- Full audit logs  
- Strong privacy (no IP storage)

---

## 🛠️ Tech Stack

- Laravel (PHP 8.4+)  
- MySQL / SQLite  
- Docker & Nginx  
- Blade Templates (no Node.js required)

---

## 🚀 Quick Start (Docker)

```bash
git clone https://github.com/kahmada/hinweisgeberportal.git
cd hinweisgeberportal
cp .env.example .env
docker-compose up -d --build
