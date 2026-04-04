# Student Suggestion WebApp

A professional PHP MVC web application for managing student suggestions and feedback with anonymous submissions and admin tracking.

## Features

### Student Module
- **Anonymous Submission** - No login required, optional category tags
- **File Attachments** - Upload screenshots, photos, or documents (max 5MB)
- **Submission Confirmation** - Unique ticket code to track status
- **Urgency Levels** - Mark low/medium/high priority
- **Draft Submissions** - Save incomplete submissions
- **View Previous Suggestions** - See resolved issues from fellow students
- **Track Status** - Check progress with ticket code

### Admin/Staff Module
- **Role-Based Access** - President, Committee Member, Person In Charge
- **Status Workflow** - New → In Review → Resolved/Actioned
- **Bulk Actions** - Select multiple, mark resolved, export to CSV
- **Response Templates** - Pre-written replies for common issues
- **Anonymous Reply** - Admin can ask follow-up questions anonymously
- **Internal Notes** - Private comments between admins

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server

### Setup Steps

1. **Clone or copy the project** to your web server directory

2. **Configure Database**
   - Create a MySQL database named `student_suggestions`
   - Import the schema:
   ```bash
   mysql -u root -p student_suggestions < database/schema.sql
   ```

3. **Update Database Configuration**
   Edit `app/config/database.php`:
   ```php
   'host' => 'localhost',
   'dbname' => 'student_suggestions',
   'username' => 'your_username',
   'password' => 'your_password',
   ```

4. **Set Permissions**
   ```bash
   chmod -R 755 public/assets/uploads
   chmod -R 755 storage/sessions
   chmod -R 755 storage/logs
   ```

5. **Configure Base URL**
   Edit `app/config/app.php` and update `base_url` to match your installation path

6. **Access the Application**
   - Student Portal: `http://your-domain/public/index.php`
   - Admin Login: `http://your-domain/public/index.php?route=admin/login`

## Default Admin Credentials

- **Username:** admin
- **Password:** admin123

*Please change the password immediately after first login!*

## Directory Structure

```
/workspace
├── app/
│   ├── config/          # Configuration files
│   ├── controllers/     # Controllers (Student, Admin)
│   ├── core/            # Core classes (Database, Router, etc.)
│   ├── models/          # Data models
│   └── views/           # View templates
│       ├── admin/       # Admin views
│       └── student/     # Student views
├── database/            # SQL schema and migrations
├── public/              # Public accessible files
│   ├── assets/
│   │   ├── css/         # Stylesheets
│   │   ├── js/          # JavaScript files
│   │   └── uploads/     # Uploaded files
│   └── index.php        # Front controller
└── storage/             # Sessions and logs
```

## API Endpoints (Internal)

### Student Routes
- `GET /index.php?route=student/index` - Home page
- `GET /index.php?route=student/submit` - Submit form
- `POST /index.php?route=student/submit` - Process submission
- `GET /index.php?route=student/track` - Track submission
- `POST /index.php?route=student/saveDraft` - Save draft
- `GET /index.php?route=student/viewAll` - View all public suggestions

### Admin Routes
- `GET /index.php?route=admin/login` - Login page
- `POST /index.php?route=admin/login` - Process login
- `GET /index.php?route=admin/logout` - Logout
- `GET /index.php?route=admin/dashboard` - Dashboard
- `GET /index.php?route=admin/suggestions` - All suggestions
- `GET /index.php?route=admin/viewSuggestion/{id}` - View single suggestion
- `POST /index.php?route=admin/updateStatus` - Update status
- `POST /index.php?route=admin/addResponse` - Add response
- `POST /index.php?route=admin/bulkUpdate` - Bulk update
- `GET /index.php?route=admin/export` - Export to CSV
- `GET /index.php?route=admin/templates` - Manage templates

## Security Features

- Password hashing with bcrypt
- SQL injection prevention via PDO prepared statements
- XSS protection through output escaping
- File upload validation
- Session-based authentication
- Role-based access control

## Customization

### Adding Categories
Edit the categories dropdown in `app/views/student/submit.php`

### Modifying Status Workflow
Update the ENUM values in `database/schema.sql` and corresponding badge styles in CSS

### Email Notifications
Currently not implemented. Can be added by extending the AdminResponse model.

## Troubleshooting

### Database Connection Error
- Verify database credentials in `app/config/database.php`
- Ensure MySQL service is running
- Check database exists and user has permissions

### File Upload Issues
- Check `public/assets/uploads` directory permissions
- Verify `upload_max_filesize` and `post_max_size` in php.ini

### 404 Errors
- Ensure mod_rewrite is enabled (Apache)
- Check base_url configuration in `app/config/app.php`

## License

This project is open-source and available for educational purposes.

## Support

For issues and feature requests, please contact the development team.
