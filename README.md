# Academic Documents Request Scheduling System

<div align="center">



*A modern, efficient platform for managing academic document requests and scheduling*

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4.svg)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-%3E%3D5.7-4479A1.svg)](https://mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952B3.svg)](https://getbootstrap.com/)

</div>

## 📋 Overview

The **Academic Documents Request Scheduling System** streamlines the traditionally complex process of requesting, scheduling, and managing academic documents. Built with modern web technologies, it provides an intuitive interface that reduces administrative overhead while enhancing the user experience for students and staff alike.

## ✨ Key Features

### 📄 **Document Management**
- **Request Submission**: Streamlined form-based document requests
- **Status Tracking**: Real-time updates on request progress
- **Document Types**: Support for transcripts, certificates, recommendations, and more

### 🗓️ **Smart Scheduling**
- **Calendar Integration**: Visual appointment booking with FullCalendar
- **Time Slot Management**: Automated availability checking
- **Conflict Prevention**: Intelligent scheduling to avoid overlaps

### 👥 **Role-Based Access**
- **Student Portal**: Intuitive request submission and tracking
- **Administrative Dashboard**: Comprehensive management tools
- **Staff Interface**: Streamlined processing workflows

### 🔔 **Communication**
- **Email Notifications**: Automated updates via PHPMailer
- **SMS Integration**: Optional Twilio-powered text alerts
- **Status Updates**: Real-time progress notifications

### 📊 **Analytics & Reporting**
- **Request Analytics**: Detailed processing statistics
- **Performance Metrics**: Efficiency tracking and insights
- **Custom Reports**: Exportable data for administrative use

## 🛠️ Technology Stack

<div align="center">

| Category | Technologies |
|----------|-------------|
| **Backend** | PHP 7.4+, MySQLi |
| **Database** | MySQL/MariaDB |
| **Frontend** | Bootstrap 5, Tailwind CSS |
| **JavaScript** | jQuery, FullCalendar, SweetAlert2 |
| **Dependencies** | Composer-managed packages |

</div>

### 📦 Composer Dependencies

```json
{
  "require": {
    "twilio/sdk": "^6.0",
    "google/apiclient": "^2.0",
    "phpmailer/phpmailer": "^6.0"
  }
}
```

## 🖼️ Screenshots

<div align="center">

### 🔐 Login Interface
<img src="https://github.com/RyuTempest/ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM/blob/61363b122d185913a4a06a3bc59dd814034a33e7/LOGINPAGE.png" alt="Login Page" width="600"/>

*Secure authentication with modern UI design*

### 👤 Student Dashboard
<img src="https://github.com/RyuTempest/ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM/blob/b98c84293e1fb395215b6f0c04e65e37727355a0/USERDASH.png" alt="User Dashboard" width="600"/>

*Intuitive student portal for request management*

### ⚙️ Administrative Panel
<img src="https://github.com/RyuTempest/ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM/blob/61363b122d185913a4a06a3bc59dd814034a33e7/ADMINDASH.png" alt="Admin Dashboard" width="600"/>

*Comprehensive administrative control center*

</div>

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- MySQL/MariaDB 5.7+
- Composer
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/RyuTempest/ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM.git
   cd ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials and API keys
   ```

4. **Database setup**
   ```bash
   # Import the database schema
   mysql -u username -p database_name < database/schema.sql
   ```

5. **Configure web server**
   ```bash
   # Point your web server document root to the project directory
   # Ensure proper permissions are set
   ```

### Environment Configuration

Create a `.env` file with the following variables:

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=adrss_database
DB_USER=your_username
DB_PASS=your_password

# Email Configuration
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password

# Optional: SMS Integration
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
```

## 📖 Usage Guide

### For Students
1. **Registration**: Create an account using your academic email
2. **Document Request**: Submit requests through the intuitive form interface
3. **Scheduling**: Book appointments for document pickup or processing
4. **Tracking**: Monitor request status in real-time

### For Administrators
1. **Dashboard Access**: Comprehensive overview of all system activities
2. **Request Management**: Process, approve, or reject document requests
3. **Schedule Coordination**: Manage appointment slots and staff availability
4. **Reporting**: Generate detailed analytics and performance reports

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### Development Workflow

1. **Fork the repository**
   ```bash
   git fork https://github.com/RyuTempest/ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM.git
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **Commit your changes**
   ```bash
   git commit -m "feat: add amazing feature"
   ```

4. **Push and create PR**
   ```bash
   git push origin feature/amazing-feature
   ```

### Contribution Guidelines

- Follow PSR-12 coding standards for PHP
- Write meaningful commit messages
- Include tests for new features
- Update documentation as needed
- Ensure backwards compatibility

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🏆 Acknowledgments

- Built with modern web development best practices
- Inspired by the need for efficient academic administration
- Special thanks to the open-source community



