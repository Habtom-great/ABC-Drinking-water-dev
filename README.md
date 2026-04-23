# 💧 ABC Drinking Water Management System (ERP)

A full-featured **web-based ERP system** for managing operations of a drinking water company, including inventory, sales, HR, accounting, payroll, and reporting.

---

## 🚀 Project Overview

This system is designed to manage all core business processes of ABC Drinking Water Company including:

- 👥 Staff & HR Management  
- 📦 Inventory Management  
- 💰 Sales & Invoicing  
- 🧾 Accounting & Reports  
- 🏦 Bank Reconciliation  
- 📊 Performance Tracking  
- 🔐 User Authentication & Roles  

---

## 🏗️ Technologies Used

- PHP (Core Backend)
- MySQL (Database)
- HTML5 / CSS3
- Bootstrap 5
- JavaScript / jQuery
- DataTables
- Font Awesome
📊 Key Features
👨‍💼 HR Module
Add / Edit / Delete staff
Staff history tracking
Payroll management
Performance evaluation
📦 Inventory Module
Stock management
Product tracking
Inventory reports
Low stock alerts
💰 Sales Module
Invoice generation
Sales records
Customer tracking
Reports export (Excel/PDF)
🧾 Accounting Module
General ledger
Cash flow reports
Bank reconciliation
Financial statements
📈 Reports System
Sales reports (daily/monthly/yearly)
Inventory reports
Staff performance reports
Export to Excel / PDF / Print
🛠️ Security Features
Session-based authentication
Role-based access control (Admin / Staff)
Password hashing
SQL injection protection (prepared statements)
📌 Future Improvements
API integration
Mobile app version
Advanced dashboard analytics
Cloud deployment
Multi-branch support
👨‍💻 Developer

Developed by: Habtom

📄 License

This project is for educational and business use. All rights reserved © 2026.

## 📁 Project Structure
ABC-Drinking-water-dev/
│
├── index.php              ← ENTRY POINT (VERY IMPORTANT)
├── login.php
├── logout.php
│
├── includes/
│   ├── db.php
│   ├── auth.php
│   └── layout.php        ← ODOO UI SYSTEM (SIDEBAR + TOPBAR)
│
├── modules/
│   ├── admin/
│   │   └── views/
│   │       └── dashboard.php   ← ADMIN DASHBOARD CONTENT ONLY
│   │
│   ├── sales/
│   ├── inventory/
│   ├── hr/
│   ├── accounting/
│   └── users/
│
└── assets/

revised ABC-Drinking-water-dev/
│
├── includes/
│   ├── db.php
│   ├── auth.php
│   └── layout.php   ← Odoo UI engine
│
├── modules/
│   ├── admin/
│   │   └── views/
│   │       └── dashboard.php
│   ├── sales/
│   ├── inventory/
│   ├── purchases/
│   └── hr/
│
├── login.php
├── process_login.php
└── admin_dashboard.php (optional redirect only)