# Vehicle Maintenance Tracker 🚗

A web application designed to help users track and manage vehicle maintenance history. Built with **Symfony**, **PostgreSQL**, and **Tailwind CSS**, this project aims to simplify service record-keeping for individual users or small auto shops.

 This project is still a work in progress and not yet production-ready.

---

## Features (In Progress)

- Create and manage vehicle profiles
- Log service and maintenance records
- View summaries of upcoming or overdue maintenance
- Responsive UI with clean dashboard layout

---

## Roadmap

Planned features include:

- User authentication and role-based access
- Full CRUD support for maintenance records
- Notifications for upcoming services
- Enhanced dashboard with statistics and filters
- Exporting service history to PDF

---

## Tech Stack

- **Backend**: [Symfony 6](https://symfony.com/), Doctrine ORM
- **Database**: PostgreSQL
- **Frontend**: Twig templates + [Tailwind CSS](https://tailwindcss.com/)
- **Other Tools**: Hotwire (planned), PHP 8.2, Docker (for local setup)

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- PostgreSQL
- Node.js + npm (for Tailwind)

### Installation

```bash
git clone https://github.com/YOUR_USERNAME/maintenance-tracker.git
cd maintenance-tracker

composer install
npm install
npm run dev

# Set up your environment variables
cp .env .env.local
# Update DB credentials in .env.local

# Create and migrate the database
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
