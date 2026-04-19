# Student Information Management System (SIMS)
### University of Vocational Technology (UovT) - Database Implementation Task

A secure, PHP-driven administrative portal designed to manage student records. This project was developed as a practical exercise to demonstrate core database concepts, including CRUD operations, session management, and secure password hashing.

## 🚀 Features
* **Secure Authentication:** Staff login using Bcrypt password hashing.
* **Dynamic Dashboard:** Real-time student data visualization.
* **Student Enrollment:** Form-based student registration with manual ID increment logic to maintain referential integrity.
* **Live Search:** Filter through student records by Name, Email, or Class.
* **Profile Management:** Secure "Change Password" functionality for administrative accounts.
* **Responsive UI:** Built with Tailwind CSS for a modern, mobile-friendly experience.

## 🛠️ Technical Stack
* **Frontend:** HTML5, Tailwind CSS, JavaScript
* **Backend:** PHP (Procedural)
* **Database:** MySQL / MariaDB
* **Security:** `password_hash()` & `password_verify()` for credential protection.

## 📂 Project Structure
* `db.php`: Database connection configuration using `mysqli`.
* `login.php`: Authentication logic and session initialization.
* `index.php`: The main administrative dashboard and student enrollment logic.
* `change_password.php`: Secure interface for updating user credentials.
* `/asset`: Contains university branding and logos.

## 💾 Database Schema
The system utilizes two primary tables:
1.  **`users`**: Stores administrative credentials and roles.
2.  **`students`**: Stores personal details including unique Index Numbers.

### SQL Setup
```sql
CREATE TABLE users (
    user_id INT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_reg VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role VARCHAR(20) DEFAULT 'admin'
);

CREATE TABLE students (
    student_id INT PRIMARY KEY,
    full_name VARCHAR(255),
    email VARCHAR(255),
    class VARCHAR(100),
    phone VARCHAR(20)
);
Learning Objectives Covered
Establishing a secure connection between PHP and MySQL.

Managing user states across pages using session_start().

Preventing SQL Injection using mysqli_real_escape_string.

Handling database constraints (Foreign Keys and Primary Keys).

Implementing UI/UX best practices for data entry forms.

⚙️ Installation
Clone the repository to your local server directory (e.g., htdocs or www).

Import the database schema provided in the SQL section above.

Configure db.php with your local MySQL credentials.

Access the portal via http://localhost/your-folder-name/login.php.

Created as part of the Database Implementation module at UovT.
