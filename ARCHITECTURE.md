# 🏗️ FormBuilder LMS - System Architecture

## Table of Contents
1. [System Overview](#system-overview)
2. [Architecture Pattern](#architecture-pattern)
3. [Component Architecture](#component-architecture)
4. [Data Flow](#data-flow)
5. [Security Architecture](#security-architecture)
6. [Scalability Strategy](#scalability-strategy)

---

## System Overview

### Three-Tier Architecture

```
┌─────────────────────────────────────────────────┐
│         PRESENTATION LAYER                       │
│  HTML5 | CSS3 | JavaScript | Bootstrap 5        │
│  - Responsive UI                                 │
│  - AJAX for dynamic updates                      │
│  - Form Builder Interface                        │
│  - Dashboard & Analytics                         │
└────────────────────┬────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────┐
│         BUSINESS LOGIC LAYER                     │
│  PHP 8+ | MVC Pattern | OOP                     │
│  - Controllers: Request handlers                │
│  - Services: Business logic                     │
│  - Models: Data manipulation                    │
│  - Middleware: Cross-cutting concerns          │
└────────────────────┬────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────┐
│         DATA ACCESS LAYER                        │
│  MySQL 8+ | PDO | Relational DB               │
│  - Database connection                          │
│  - Query execution                              │
│  - Transaction management                       │
└─────────────────────────────────────────────────┘
```

---

## Architecture Pattern

### MVC (Model-View-Controller)

#### **Models** (Data Access)
Encapsulate database operations and business rules.

```
User Model
├── create()
├── read()
├── update()
├── delete()
├── findByEmail()
└── verifyPassword()

Form Model
├── create()
├── publish()
├── archive()
├── getQuestions()
└── getSubmissions()
```

#### **Controllers** (Request Handling)
Handle HTTP requests and orchestrate business logic.

```
AuthController
├── login()
├── register()
├── logout()
├── forgotPassword()
└── resetPassword()

FormController
├── create()
├── edit()
├── delete()
├── preview()
└── publish()
```

#### **Views** (Presentation)
Render user interface with dynamic data.

```
Views/
├── auth/
│   ├── login.php
│   ├── register.php
│   └── forgot-password.php
├── dashboard/
│   ├── teacher-dashboard.php
│   ├── student-dashboard.php
│   └── admin-dashboard.php
└── forms/
    ├── form-builder.php
    └── form-submission.php
```

---

## Component Architecture

### Directory Structure & Responsibilities

```
app/
│
├── config/
│   ├── Config.php              # Configuration manager
│   ├── Database.php            # Database configuration
│   └── Constants.php           # Application constants
│
├── models/                     # Data models (OOP)
│   ├── BaseModel.php          # Abstract base model
│   ├── User.php               # User entity
│   ├── Form.php               # Form entity
│   ├── Question.php           # Question entity
│   ├── Submission.php         # Submission entity
│   ├── Answer.php             # Answer entity
│   ├── Class.php              # Class entity
│   ├── Grade.php              # Grade entity
│   └── Certificate.php        # Certificate entity
│
├── controllers/                # Request handlers
│   ├── BaseController.php     # Abstract controller
│   ├── AuthController.php     # Authentication
│   ├── FormController.php     # Form operations
│   ├── QuestionController.php # Question operations
│   ├── SubmissionController.php # Submission handling
│   ├── ClassroomController.php # Classroom management
│   ├── DashboardController.php # Dashboard views
│   ├── ReportController.php   # Report generation
│   └── AdminController.php    # Admin functions
│
├── services/                   # Business logic
│   ├── AuthService.php        # Authentication logic
│   ├── FormService.php        # Form operations
│   ├── QuestionService.php    # Question processing
│   ├── SubmissionService.php  # Submission handling
│   ├── GradingService.php     # Auto-grading logic
│   ├── ReportService.php      # Report generation
│   ├── NotificationService.php # Notifications
│   ├── FileService.php        # File upload/storage
│   ├── CertificateService.php # Certificate generation
│   └── ExamService.php        # Exam management
│
├── middleware/                 # Request middleware
│   ├── AuthMiddleware.php     # Authentication check
│   ├── RBACMiddleware.php     # Role-based access
│   ├── ValidationMiddleware.php # Input validation
│   ├── CSRFProtection.php     # CSRF token validation
│   ├── RateLimiter.php        # API rate limiting
│   └── LoggingMiddleware.php  # Request logging
│
├── utils/                      # Helper utilities
│   ├── Helper.php             # General helpers
│   ├── Validator.php          # Input validation
│   ├── Sanitizer.php          # Input sanitization
│   ├── Encryptor.php          # Encryption/decryption
│   ├── Logger.php             # Logging utility
│   ├── EmailSender.php        # Email sending
│   ├── ExcelExporter.php      # Excel export
│   └── PDFGenerator.php       # PDF generation
│
└── views/                      # UI templates
    ├── auth/
    ├── dashboard/
    ├── forms/
    ├── classroom/
    ├── admin/
    ├── shared/
    └── errors/
```

---

## Data Flow

### Form Submission Flow

```
User Input
    │
    ▼
Frontend Validation (JavaScript)
    │
    ▼
HTTP Request (AJAX/POST)
    │
    ▼
Router → SubmissionController
    │
    ▼
AuthMiddleware (Check authentication)
    │
    ▼
ValidationMiddleware (Input validation)
    │
    ▼
CSRFProtection (Verify CSRF token)
    │
    ▼
SubmissionService (Business logic)
    │
    ▼
Question Model (Fetch question)
    │ ▼
    │ GradingService (Auto-grade)
    │
    ▼
Answer Model (Store answer)
    │
    ▼
Submission Model (Update submission)
    │
    ▼
NotificationService (Send notification)
    │
    ▼
JSON Response
    │
    ▼
Frontend Updates UI
```

### Authentication Flow

```
User Credentials
    │
    ▼
AuthController::login()
    │
    ▼
Sanitize Input
    │
    ▼
AuthService::authenticate()
    │
    ▼
User Model::findByEmail()
    │
    ▼
Verify Password (bcrypt)
    │
    ▼
Generate JWT Token / Session
    │
    ▼
Store Session / Set Cookie
    │
    ▼
Redirect to Dashboard
```

---

## Security Architecture

### Security Layers

#### 1. **Input Layer**
```php
// Sanitization
$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Validation
$validator->validate($data, $rules);
```

#### 2. **Authentication Layer**
```php
// Password Hashing
password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// JWT Tokens (API)
Authorization: Bearer <JWT_TOKEN>

// Session Security
session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
```

#### 3. **Authorization Layer**
```php
// RBAC
if (!$user->hasRole('teacher')) {
    throw new AuthorizationException();
}

// Permission-based
if (!$user->hasPermission('create_form')) {
    throw new AuthorizationException();
}
```

#### 4. **Data Layer**
```php
// Prepared Statements (SQL Injection Prevention)
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// Encryption
$encrypted = $encryptor->encrypt($sensitiveData);
```

#### 5. **Application Layer**
```php
// CSRF Protection
$token = $_SESSION['csrf_token'];

// XSS Prevention
htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');

// Rate Limiting
$rateLimiter->checkLimit($userId, $action);
```

---

## Scalability Strategy

### Horizontal Scaling

```
Load Balancer
    │
    ├── Web Server 1 (PHP-FPM)
    ├── Web Server 2 (PHP-FPM)
    └── Web Server 3 (PHP-FPM)
           │
           └─→ MySQL Database (Master-Slave Replication)
           │
           └─→ Redis Cache Server
           │
           └─→ File Storage (S3/Cloud)
```

### Caching Strategy

```
Frontend Cache (Browser)
    ↓
CDN Cache
    ↓
Application Cache (Redis)
    ↓
Database Query Cache
    ↓
Database
```

### Database Optimization

- **Indexing** - Indexes on frequently queried columns
- **Partitioning** - Table partitioning by date/school
- **Replication** - Master-slave setup for reads
- **Connection Pooling** - Manage database connections

### API Rate Limiting

```
User Role     | Requests/Hour | Requests/Minute
--------------------------------------------------
Free User     | 1,000         | 30
Paid User     | 10,000        | 100
Admin         | Unlimited     | Unlimited
```

### Future Multi-Tenant Architecture

```
Tenant Router
    │
    ├── Tenant 1 Database
    ├── Tenant 2 Database
    └── Tenant N Database

Shared Services
├── Authentication
├── File Storage
└── Email Service
```

---

## Technology Stack

| Layer | Technology | Purpose |
|-------|-----------|----------|
| **Frontend** | HTML5, CSS3, JavaScript (ES6+) | UI/UX |
| | Bootstrap 5 | Responsive framework |
| | AJAX | Asynchronous requests |
| **Backend** | PHP 8+ | Server-side logic |
| | MVC Pattern | Architecture |
| | OOP | Code organization |
| **Database** | MySQL 8+ | Data persistence |
| | PDO | Database abstraction |
| **Security** | JWT | Token authentication |
| | bcrypt | Password hashing |
| | CSRF Tokens | CSRF protection |
| **Caching** | Redis (optional) | Performance |
| **Files** | Local/S3 | File storage |

---

## Performance Considerations

### Database Queries
- Use SELECT with specific columns, not *
- Implement pagination for large datasets
- Use JOINs efficiently
- Create appropriate indexes

### Caching
- Cache frequently accessed data
- Implement TTL (Time To Live)
- Invalidate cache on data changes

### Frontend Optimization
- Minimize CSS/JavaScript
- Lazy load images
- Implement pagination
- Use AJAX for partial updates

### Backend Optimization
- Use connection pooling
- Implement async processing for heavy tasks
- Use job queues for email/notifications
- Monitor query performance

---

## Deployment Architecture

### Development Environment
```
Local Machine
├── PHP 8+ (Built-in server)
├── MySQL 8+
└── IDE (VSCode/PhpStorm)
```

### Production Environment
```
Load Balancer (Nginx)
    │
    ├── App Server 1 (Apache + PHP-FPM)
    ├── App Server 2 (Apache + PHP-FPM)
    └── App Server N
           │
        ┌──▼──┐
        │MySQL│ (Primary - Replication)
        └─────┘
           │
        ┌──▼──┐
        │Redis│ (Caching)
        └─────┘
           │
        ┌──▼──────────┐
        │File Storage │ (S3/Cloud)
        └─────────────┘
```

---

## Conclusion

This architecture provides:
- ✅ Scalability for growth
- ✅ Security at every layer
- ✅ Maintainability through separation of concerns
- ✅ Flexibility for future enhancements
- ✅ Performance optimization strategies
- ✅ Enterprise-grade reliability