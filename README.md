🚗 CBS Core – Multi-Renter Car Booking System
<p align="center"> <img src="public/logo2.png" alt="CBS Core Logo" /> </p>

📌 About the Project

CBS Core is a multi-renter car booking and management system built with a scalable backend and dashboard architecture.
It enables multiple car renters to operate on the same platform while providing centralized administration and operational management.

The system is designed using Clean Architecture principles to ensure maintainability, scalability, and separation of concerns.

🧩 Key Features
Multi-renter car booking platform
Centralized management dashboard
Role-based access control system
Rental request workflow (approval-based onboarding for renters)
Full booking lifecycle management (approve, reject, reschedule)
Reporting and analytics for system operations
Multi-language support (Arabic / English)
👥 System Roles & Permissions

🛡️ Admin
Full system oversight and statistics
Manage users (create, disable, change roles)
Approve or reject renter requests
View full fleet of cars on the platform
Monitor bookings (read-only access)
Generate reports for cars, users, and bookings

👨‍💼 Employee
Manage all booking operations
Approve, reject, or reschedule bookings
Communicate with customers
Handle operational workflows of the system

🚗 Renter
Add and manage own cars
View bookings related to their cars
Manage availability of vehicles
Operates under admin-approved access
👤 Customer
Browse available cars
Make booking requests
Communicate with support
Submit request to become a renter


🛠️ Tech Stack
Backend: Laravel (PHP)
Authentication: Laravel Breeze
API Security: Laravel Sanctum (Token-based auth)
Permissions: Spatie Laravel Permission
Frontend Dashboard: Blade + Alpine.js + TailwindCSS
Architecture: Clean Architecture
Localization: Multi-language (AR / EN)


🏗️ Architecture
Clean separation of concerns (Controllers, Services, Repositories)
Scalable modular structure
Role-based access control via middleware & permission layer
🌍 Localization
Arabic (RTL support)
English (LTR support)


🔐 Security
Secure authentication via Sanctum
Role & permission management via Spatie
Protected dashboard routes per role


🚀 Project Purpose

This project simulates a real-world multi-renter SaaS car booking platform, developed as a university graduation project with production-level architecture and scalability in mind.
