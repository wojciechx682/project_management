# 📌 Project Management App

A web-based application for **project management, team collaboration, and task tracking**.  
Created as an MVP project to practice **full-stack web development** using PHP, MySQL, JavaScript, and modern web standards.

---

## ✨ Features

- 👤 **User authentication & roles** (Admin, Project Manager, Team Member)
- ✅ **Account approval system** (Admin verifies new users)
- 📂 **Projects** – create, edit, update, delete, assign teams
- 📝 **Tasks** – assign tasks to users, set deadlines, priorities, track status
- 👥 **Teams** – assign users to teams, view team details and members
- 💬 **Comments** – add discussion to tasks
- 🔒 **Security**
    - Sessions, password hashing (`password_hash`)
    - Validation on frontend (JS + DOMPurify) and backend (PHP)
    - reCAPTCHA integration on login/registration
    - PHPMailer for password reset and notifications
- 📊 **Dashboard** – quick info about projects and tasks
- 🎨 **UI/UX** – simple responsive design with **HTML5 + CSS3**, icons via **Fontello**

---

## 🛠️ Technologies

- **Backend:** PHP 8.2 (procedural, PDO, sessions, validations)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (AJAX + Fetch API, DOMPurify for sanitization)
- **Security:** reCAPTCHA v2, password hashing, input validation
- **Email:** PHPMailer (SMTP)
- **Icons:** Fontello
- **Other tools:** Git, phpMyAdmin, XAMPP

---

## 🗄️ Database Schema

The system uses the following main tables:

- `user` – users with roles (Admin, PM, Team Member)
- `team` – project teams
- `team_user` – many-to-many relation between users and teams
- `project` – project details (linked to team)
- `task` – tasks assigned to projects/users
- `comment` – comments for tasks
- `password_resets` – password reset tokens

➡️ Full SQL dump: [`project_management.sql`](./project_management.sql)

Screenshoots : 

![Main Login Page](assets/git/1.png)

![Register Page](assets/git/2.png)

![Password reset page](assets/git/3.png)

![Password reset page](assets/git/4.png)

![Team Member Main Page](assets/git/5.png)

![Tasks view](assets/git/6.png)

![Adding comments](assets/git/7.png)

![Projects view](assets/git/8.png)

![New Project](assets/git/9.png)

![Projects Details View](assets/git/10.png)

![Editing Project](assets/git/11.png)

![Adding New Task](assets/git/12.png)

![Editing Task](assets/git/13.png)

![Deleting Task](assets/git/14.png)

![Task Details page](assets/git/15.png)

![Editing comments](assets/git/16.png)

![Deleting Task](assets/git/17.png)

![Deleting Task](assets/git/18.png)

![Deleting Task](assets/git/19.png)

![Deleting Task](assets/git/20.png)

![Deleting Task](assets/git/21.png)