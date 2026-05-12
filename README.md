# Task Tracker App

A full-featured task management and productivity application built with Laravel, designed to help users efficiently organize, schedule, and manage daily tasks with a clean and scalable architecture.

## Overview

Task Tracker App provides a modern workflow for managing tasks and recurring activities while maintaining a clean user experience and maintainable backend structure.  
The project follows Laravel best practices and focuses on clean code, separation of concerns, and scalability.

## Features

- Secure User Authentication & Authorization
- Create, Update, Delete, and Manage Tasks
- Recurring Tasks System
- Task Categories Management
- RESTful Application Structure
- Form Request Validation
- Responsive User Interface
- Clean Architecture Principles
- Reusable Services & Resources
- Error Handling & Validation Feedback

## Tech Stack

- Laravel
- PHP
- MySQL
- Blade Templates
- Tailwind CSS
- Docker
- Eloquent ORM

## Architecture & Design

The project is structured to keep the codebase clean, maintainable, and scalable by separating responsibilities into dedicated layers such as:

- Controllers
- Services
- Actions
- Form Requests
- API Resources
- Models
- Custom Validation Logic

## Getting Started

### Clone the repository

```bash
git clone https://github.com/hamadagarhy/Task-Tracker-App.git
```

### Install dependencies

```bash
composer install
```

### Setup environment variables

```bash
cp .env.example .env
```

### Start Docker containers

```bash
docker compose up -d
```

### Generate application key

```bash
php artisan key:generate
```

### Run database migrations

```bash
php artisan migrate
```

## Future Improvements

- Notifications & Reminders
- Task Priorities
- Calendar Integration
- Team Collaboration
- Drag & Drop Task Boards
- API Authentication

## Author

Hamada Ismael
