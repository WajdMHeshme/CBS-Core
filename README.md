# 🚗 CBS Core – Multi-Lessor Car Booking System

![CBS Core](public/logo.png)

## 📌 About the Project

**CBS Core** is a multi-lessor car booking and management system built with a scalable backend and dashboard architecture.  
It enables multiple car lessors to operate on the same platform while providing centralized administration and operational management.

The system is designed using **Clean Architecture** principles to ensure maintainability, scalability, and separation of concerns.

---

## 🧩 Key Features

- Multi-lessor car booking platform
- Centralized management dashboard
- Role-based access control system
- Rental request workflow (approval-based onboarding for lessors)
- Full booking lifecycle management (approve, reject, reschedule)
- Reporting and analytics for system operations
- Multi-language support (Arabic / English)

---

## 👥 System Roles & Permissions

### 🛡️ Admin
- Full system oversight and statistics
- Manage users (create, disable, change roles)
- Approve or reject lessor requests
- View full fleet of cars on the platform
- Monitor bookings (read-only access)
- Generate reports for cars, users, and bookings

---

### 👨‍💼 Employee
- Manage all booking operations
- Approve, reject, or reschedule bookings
- Communicate with customers
- Handle operational workflows of the system

---

### 🚗 Lessor
- Add and manage own cars
- View bookings related to their cars
- Manage availability of vehicles
- Operates under admin-approved access

---

### 👤 Customer
- Browse available cars
- Make booking requests
- Communicate with support
- Submit request to become a lessor

---

## 🛠️ Tech Stack

- Laravel (PHP)
- Laravel Breeze (Authentication)
- Laravel Sanctum (API Tokens)
- Spatie Laravel Permission (Roles & Permissions)
- Blade + Alpine.js
- Tailwind CSS
- Clean Architecture
- Multi-language support (AR / EN)

---

## 🏗️ Architecture

- Clean separation of concerns (Controllers, Services, Repositories)
- Scalable modular structure
- Role-based access control using middleware & permissions
- Maintainable and extendable design

---

## 🌍 Localization

- Arabic (RTL support)
- English (LTR support)

---

## 🔐 Security

- Token-based authentication using Sanctum
- Role & permission management using Spatie
- Protected routes per user role

---

## 🚀 Project Purpose

A graduation project simulating a real-world **multi-lessor SaaS car booking system**, designed with production-level architecture, scalability, and role-based workflows.
