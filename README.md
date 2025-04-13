🎓 Online Learning Portal

A robust, full-stack web application built using **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**, deployed via **XAMPP**, designed to streamline digital education. 
This platform allows students to register, browse courses, enroll in them, and interact with academic content—all in one place. 
The system also supports administrator-level access to manage users, courses, and platform integrity.


🚀 Features Overview

### 👥 Multi-User Role Access
- **Admin**: Manage users, courses, and maintain platform control.
- **Student**: Register, log in, view available courses, and enroll.
- **Faculty (Optional Scope)**: Add or manage course materials (can be included for future scalability).


🧩 Modules Breakdown

### 1. 🔐 Login Page
Secure gateway for all users. It features:
- Username and password input
- Form validation for proper data entry
- Authentication via PHP & MySQL with encrypted credential matching
- Role-based redirection post-login
- Error messaging for invalid credentials
- Link to the registration page for new users


2. 📝 Registration Page
New users can register by submitting:
- Full Name
- Email Address (with format validation)
- Unique Username
- Password (securely hashed)
- Data is sanitized and securely stored in MySQL
- Upon success, users are redirected to the login page or logged in automatically



 3. 🏠 Student Dashboard
A personalized interface that includes:
- Welcome message
- Basic profile overview
- Links to:
  - Edit Profile
  - View Available Courses
  - Enroll in Courses
  - View Enrolled Courses
  - Logout
- Data dynamically retrieved using PHP and MySQL
- Responsive design for cross-device compatibility



4. 📚 Courses Available Page
Lists all courses offered through the portal:
- Course Name
- Instructor (if applicable)
- Category or Department
- Enrollment option
- Fetched from MySQL and updated in real time
- Students can browse and instantly enroll



5. ✅ Course Enrollment Page
Interactive interface for course enrollment:
- Displays course catalog with search or filter options
- Students can click 'Enroll' to register in a course
- Enrollment validations to prevent duplicate entries
- Success message displayed
- Enrolled courses are reflected in the student's dashboard



6. ⚙️ Admin Dashboard
Backend control center with features to:
- Add, update, or delete courses
- View all registered students
- Manage user profiles
- Monitor enrollments
- Maintain platform integrity through content moderation



7. 🗃️ User Data Management in MySQL
Efficient and secure storage of:
- User credentials
- Course details
- Enrollment records
- Foreign key relationships ensure relational integrity
- Passwords hashed for privacy
- Proper indexing and query optimization for fast access



🛡️ Security Practices
- Passwords stored using secure hashing (e.g., SHA-256 or bcrypt)
- Input validation and sanitization to prevent SQL injection
- Role-based access control ensures restricted access to admin/student features
- Sessions used to manage logged-in states



🛠️ Tech Stack

| Layer          | Technology        |
|----------------|-------------------|
| Frontend       | HTML, CSS, JavaScript |
| Backend        | PHP               |
| Database       | MySQL             |
| Server         | XAMPP (Apache + MySQL + PHP) |



💡 Future Enhancements
- Email notifications for new enrollments or announcements
- Chatroom or forum for course discussions
- Progress tracking and quizzes
- Certificate generation for completed courses
- Faculty login with material upload and grading system



📂 How to Run This Project Locally

1. Clone the Repository:
   ```bash
   git clone https://github.com/your-username/online-learning-portal.git
