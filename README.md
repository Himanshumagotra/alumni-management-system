# 🎓 Alumni Management System

A web-based Alumni Management System built using **HTML**, **Tailwind CSS**, **JavaScript**, **PHP**, and **MySQL**. This platform allows alumni of a college to register, log in, and view fellow alumni on a rich, styled homepage.

---

## 📌 Features

- 📝 Alumni Sign-Up with detailed info
- 🔐 Secure Login using PHP sessions and hashed passwords
- 🧾 Homepage with all registered alumni and their contact info
- 💻 Responsive and attractive UI using Tailwind CSS
- 📤 Alumni data stored in MySQL database
- 🚪 Logout functionality

---

## 🛠️ Technologies Used

- HTML5
- Tailwind CSS
- JavaScript
- PHP (Backend)
- MySQL (Database)
- XAMPP (Localhost server)

---

## 🚀 How to Run This Project Locally

### 1. Setup Environment
- Install [XAMPP](https://www.apachefriends.org/index.html)
- Start **Apache** and **MySQL** from the XAMPP Control Panel

### 2. Clone the Repository


git clone https://github.com/your-username/alumni-management.git

3. Move Project to XAMPP Directory
Copy the alumni-management folder into:

makefile
Copy
Edit
C:\xampp\htdocs\

4. Setup the Database
Open phpMyAdmin

Create a new database named alumni_db

Run this SQL to create the table:
CREATE TABLE alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    graduation_year VARCHAR(10),
    degree VARCHAR(100),
    occupation VARCHAR(100),
    company VARCHAR(100),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
5. Run the Website
Go to your browser and open:


http://localhost/alumni-management/signup.php


 Folder Structure
 alumni-management/
├── includes/
│   └── db.php
├── css/
├── js/
├── signup.php
├── login.php
├── logout.php
├── index.php
├── welcome.php

✨ Future Features (Optional Ideas)
✅ Admin panel

🔍 Search & filter alumni

🖼️ Profile picture upload

✉️ Email verification system

🤝 Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


