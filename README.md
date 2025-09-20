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

Main login page (index.php): 

![Main Login Page](assets/git/1.png)

Register page:

![Register Page](assets/git/2.png)

Password reset page:

![Password reset page](assets/git/2_1.png)

![Password reset page](assets/git/2_2.png)

Main page (after log in) for Team Member (role):

![Team Member Main Page](assets/git/3.png)

Tasks View:

![Tasks view](assets/git/4.png)

Projects View (Project Manager Role):

![Projects view](assets/git/5.png)

Adding New Project:

![New Project](assets/git/6.png)

![New Project](assets/git/7.png)

Project Details View (Project Manager Role):

![Projects Details View](assets/git/8.png)

Editing Project:

![Editing Project](assets/git/9.png)

![Editing Project](assets/git/10.png)

Adding New Task:

![Adding New Task](assets/git/11.png)

Editing Task:

![Editing Task](assets/git/12.png)

![Editing Task](assets/git/13.png)

Deleting Task:

![Deleting Task](assets/git/14.png)

After Successful Registration:

![Deleting Task](assets/git/15.png)

Attempted to log in before the administrator approved the account:

![Deleting Task](assets/git/16.png)

Verification of user accounts by the administrator:

![Deleting Task](assets/git/17.png)

![Deleting Task](assets/git/18.png)

![Deleting Task](assets/git/19.png)

User profile page:

![User profile page](assets/git/20.png)

Teams page:

![Teams page](assets/git/21.png)





















