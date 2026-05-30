# 🎓 FormBuilder LMS - Enterprise-Grade Form Builder & Learning Management System

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.0-brightgreen.svg)](https://www.php.net/)
[![MySQL Version](https://img.shields.io/badge/mysql-%3E%3D8.0-blue.svg)](https://www.mysql.com/)
[![Status](https://img.shields.io/badge/status-active-success.svg)](https://github.com/kimhuy957/FormBuilder-LMS)

A comprehensive, enterprise-grade web-based platform combining features of **Google Forms**, **Microsoft Forms**, **Typeform**, **Moodle**, and **Google Classroom**. Built with PHP 8, MySQL 8, and modern frontend technologies.

## ✨ Key Features

### 📋 Form Builder
- **Drag-and-drop form builder** - Intuitive interface similar to Google Forms
- **30+ field types** - Text, choice, education, survey, and interactive fields
- **Smart form settings** - Timer, password protection, grading, certificates
- **Real-time preview** - See changes instantly
- **Customizable themes** - Colors, logos, cover images
- **Conditional logic** - Show/hide fields based on answers (future release)

### 🏫 Classroom Management
- **Create & manage classes** - Organize students by classes
- **Student enrollment** - Join codes and direct invitations
- **Assignment distribution** - Assign forms, exams, and homework
- **Progress tracking** - Monitor student submissions and performance
- **Real-time notifications** - Deadline reminders and alerts

### 📝 Examination System
- **Online exams** - Secure exam environment
- **Auto-grading** - Instant results for objective questions
- **Anti-cheating** - Browser lock mode, fullscreen enforcement
- **Random questions/answers** - Prevent cheating
- **Practice tests** - Unlimited attempts
- **Mock exams** - Simulate real exam conditions

### 📊 Analytics & Reports
- **Comprehensive dashboards** - For teachers, students, and admins
- **Real-time analytics** - Submission rates, scores, participation
- **Visual charts** - Performance trends, completion rates
- **Export reports** - Excel, PDF, CSV formats
- **Student performance** - Individual and class analytics

### 🔐 Security Features
- **CSRF Protection** - Token-based validation
- **XSS Prevention** - Input sanitization
- **SQL Injection Prevention** - Parameterized queries
- **Password hashing** - bcrypt with salt
- **Role-Based Access Control (RBAC)** - 5 user roles
- **Session security** - Secure cookie handling
- **Rate limiting** - API throttling
- **Audit logs** - Track all activities

### 📧 Notifications
- **Email notifications** - Submission confirmations, reminders
- **In-app notifications** - Real-time alerts
- **Deadline reminders** - Automatic notifications
- **Customizable alerts** - Control notification preferences

### 🎖️ Certificates
- **Auto-generation** - Generate on exam pass or course completion
- **PDF certificates** - Professional formatting
- **QR verification** - Verify certificate authenticity
- **Digital signatures** - Tamper-proof certificates

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────┐
│          Frontend (HTML5, CSS3, JavaScript)         │
│  Bootstrap 5 | AJAX | Responsive Design             │
└──────────────────┬──────────────────────────────────┘
                   │
        ┌──────────▼──────────┐
        │   MVC Architecture   │
        │  Controllers/Views   │
        └──────────┬───────────┘
                   │
   ┌───────────────┼───────────────┐
   │               │               │
   ▼               ▼               ▼
┌──────────┐ ┌──────────┐ ┌──────────┐
│ Models   │ │ Services │ │Middleware│
│ (OOP)    │ │ (Logic)  │ │(Security)│
└──────────┘ └──────────┘ └──────────┘
   │               │               │
   └───────────────┼───────────────┘
                   │
        ┌──────────▼──────────┐
        │   REST API (v1)      │
        │  JSON Responses      │
        └──────────┬───────────┘
                   │
        ┌──────────▼──────────┐
        │  MySQL 8+ Database   │
        │ Relational Schema    │
        └──────────────────────┘
```

## 📁 Project Structure

```
FormBuilder-LMS/
├── public/                 # Web root
│   ├── index.php          # Entry point
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Static images
│
├── app/                    # Core application
│   ├── config/            # Configuration files
│   ├── models/            # Data models (OOP)
│   ├── controllers/       # Request handlers
│   ├── services/          # Business logic
│   ├── middleware/        # Request middleware
│   ├── utils/             # Helper utilities
│   └── views/             # Templates
│
├── api/                    # REST API endpoints
│   ├── v1/               # API version 1
│   └── responses.php      # Standard responses
│
├── database/               # Database files
│   ├── schema.sql         # Database schema
│   ├── migrations/        # Migration scripts
│   └── seeds/             # Seed data
│
├── tests/                  # Unit tests
│
├── docs/                   # Documentation
│   ├── API.md             # API documentation
│   ├── ARCHITECTURE.md    # Architecture details
│   ├── DATABASE.md        # Database design
│   └── SETUP.md           # Installation guide
│
└── .env.example           # Environment template
```

## 🗄️ Database Schema

### Core Tables
- **users** - User accounts with roles
- **roles** - User roles (Admin, Teacher, Student, etc.)
- **permissions** - System permissions
- **user_roles** - Role assignments
- **schools** - School/organization information

### Form Management
- **forms** - Form metadata
- **sections** - Form sections
- **questions** - Form questions
- **options** - Question options
- **form_settings** - Form configurations

### Submissions & Grading
- **submissions** - Form submissions
- **answers** - Individual answers
- **grades** - Grading records
- **grades_details** - Detailed grading info

### Classroom Management
- **classes** - Classroom records
- **class_members** - Student-class relationships
- **assignments** - Class assignments

### Additional Features
- **certificates** - Certificate records
- **notifications** - Notification history
- **files** - File upload records
- **audit_logs** - System activity logs

## 🔐 Security Implementation

### Authentication
- ✅ JWT-based authentication (API)
- ✅ Session-based authentication (Web)
- ✅ Secure password hashing (bcrypt)
- ✅ Email verification
- ✅ Forgot password recovery

### Authorization
- ✅ Role-Based Access Control (RBAC)
- ✅ Permission-based features
- ✅ Resource-level permissions

### Data Protection
- ✅ CSRF tokens on all forms
- ✅ XSS input sanitization
- ✅ SQL injection prevention (PDO)
- ✅ Rate limiting on API
- ✅ HTTPS recommended

### Audit & Monitoring
- ✅ Activity logging
- ✅ Failed login tracking
- ✅ Sensitive action logging
- ✅ Automatic session timeout

## 🚀 Getting Started

### Prerequisites
- **PHP 8.0+** - Web server
- **MySQL 8.0+** - Database
- **Apache/Nginx** - Web server
- **Composer** - PHP package manager (optional)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/kimhuy957/FormBuilder-LMS.git
   cd FormBuilder-LMS
   ```

2. **Setup environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Create database**
   ```bash
   mysql -u root -p
   CREATE DATABASE formbuilder_lms;
   USE formbuilder_lms;
   SOURCE database/schema.sql;
   ```

4. **Configure web server**
   - Set document root to `public/` directory
   - Enable `mod_rewrite` for Apache
   - Configure nginx rewrite rules

5. **Set permissions**
   ```bash
   chmod 755 -R app/
   chmod 777 uploads/
   chmod 777 logs/
   ```

6. **Access application**
   - Open `http://localhost` in browser
   - Default admin credentials: admin@example.com / password

### Detailed Setup Guide
See [SETUP.md](docs/SETUP.md) for step-by-step installation instructions.

## 📚 API Documentation

RESTful API with JSON responses. See [API.md](docs/API.md) for complete documentation.

### Example Endpoints

```
POST   /api/v1/auth/login
POST   /api/v1/auth/register
POST   /api/v1/auth/logout

GET    /api/v1/forms
POST   /api/v1/forms
GET    /api/v1/forms/{id}
PUT    /api/v1/forms/{id}
DELETE /api/v1/forms/{id}

POST   /api/v1/submissions
GET    /api/v1/submissions/{id}
GET    /api/v1/submissions/form/{formId}

GET    /api/v1/reports/form/{formId}
GET    /api/v1/reports/student/{studentId}

GET    /api/v1/users
POST   /api/v1/users
GET    /api/v1/users/{id}
PUT    /api/v1/users/{id}
```

## 👥 User Roles

1. **Super Admin** - Full system access, manage schools, users
2. **School Admin** - Manage school, teachers, students
3. **Teacher** - Create forms, manage classes, grade students
4. **Student** - Submit forms, view grades, progress
5. **Guest** - Anonymous form submission (if allowed)

## 🎯 Core Features by Role

### Teacher
- Create and publish forms/exams
- Manage classes and students
- Auto-grade objective questions
- View analytics and reports
- Generate certificates
- Send notifications

### Student
- Join classes using code
- Submit forms and exams
- View grades and feedback
- Track learning progress
- Download certificates
- View class materials

### Admin
- Manage users and roles
- Monitor system health
- View comprehensive reports
- Manage school settings
- Access audit logs

## 🔄 Workflow Examples

### Creating and Submitting a Form
1. Teacher creates form with drag-and-drop builder
2. Teacher configures settings (timer, grading, etc.)
3. Teacher publishes form to class
4. Student receives notification
5. Student completes form
6. Auto-grading evaluates answers
7. Results displayed to both teacher and student

### Taking an Exam
1. Teacher creates exam form
2. Sets timer and passing score
3. Assigns to class with deadline
4. Student clicks exam link
5. Exam starts in fullscreen mode
6. Auto-submit on timeout
7. Immediate results and feedback
8. Certificate generated if passed

## 📊 Analytics Features

### Real-time Dashboards
- Form completion rates
- Student performance trends
- Answer distribution by question
- Submission timeline
- Pass/fail statistics
- Class rankings

### Reports
- Excel export capability
- PDF report generation
- CSV for external analysis
- Custom date ranges
- Comparison reports

## 🔄 Future Enhancements

- 🤖 **AI Question Generator** - Auto-generate questions
- 🧠 **AI Grading** - Essay and short answer grading
- 🎮 **Gamification** - Points, badges, leaderboards
- 📱 **Mobile App** - Native iOS/Android apps
- 🎥 **Video Learning** - Embed video content
- 💬 **Discussion Forums** - Student collaboration
- 👥 **Multi-Tenant** - Multiple organizations
- 🌐 **Multi-language** - Internationalization
- 🔔 **Push Notifications** - Real-time alerts
- 📊 **Advanced Analytics** - Learning analytics engine

## 📖 Documentation

- [Architecture Guide](docs/ARCHITECTURE.md)
- [Database Design](docs/DATABASE.md)
- [API Documentation](docs/API.md)
- [Security Guide](docs/SECURITY.md)
- [Deployment Guide](docs/DEPLOYMENT.md)
- [Installation Guide](docs/SETUP.md)

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📝 License

This project is licensed under the MIT License - see [LICENSE](LICENSE) file for details.

## 👨‍💼 Support

For support, email support@formbuilder-lms.com or create an issue on GitHub.

## 🙏 Acknowledgments

- Inspired by Google Forms, Microsoft Forms, Typeform, Moodle, Google Classroom
- Built with PHP, MySQL, Bootstrap 5
- Community contributions and feedback

---

**⭐ If you find this project useful, please star the repository!**

**Made with ❤️ by the FormBuilder LMS Team**