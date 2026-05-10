**System Architecture**

The application uses a Client-Server architecture where the front-end is built with HTML5, CSS3, and JavaScript, while the back-end logic is handled by PHP interacting with a MySQL database (quiz_app).

────────────────────────────────────────

**🛠️ Key Features**

**1. User Authentication & Security**
- **Student Registration:** Collects detailed user profiles including DOB and country. Includes JavaScript validation to ensure passwords are at least 6 characters and email formats match university patterns (e.g., XX-XXXXX-X@student.aiub.edu).
- **Password Hashing:** Implements PASSWORD_BCRYPT for secure credential storage.
- **Role-Based Access Control (RBAC):** Uses PHP sessions to distinguish between student and admin roles, restricting access to dashboard and quiz creation tools.

**2. Quiz Management (Admin)**
- **Creation Workflow:** Admins define quiz metadata such as category, total questions, and time limits.
- **Question Bank:** Supports multiple-choice questions with four options and a correct answer key.
- **Dashboard Overview:** Displays registered users, quizzes, and student performance results.

**3. Examination Interface (Student)**
- **Interactive Testing:** Students select quizzes from a catalog. The take_quiz.php interface includes a real-time JavaScript countdown timer that auto-submits when time expires.
- **Instant Feedback:** After submission, the system calculates scores and percentages with detailed correct/incorrect breakdown.

────────────────────────────────────────

**📂 Project Organization**

- **index.php, login.php, registration.php** → Entry and account creation  
- **admin_dashboard.php, create_quiz.php, add_questions.php** → Admin management tools  
- **take_quiz.php, submit_quiz.php** → Quiz execution and grading logic  
- **delete_user.php, delete_quiz.php** → Database cleanup operations  
- **style.css, stylee.css** → Responsive UI using Flexbox  

────────────────────────────────────────

**⚙️ Installation & Usage**

**Clone the Repository:**
git clone https://github.com/MaishaAlamProva/OnlineQuizSystem.git

**Database Setup:**
- Create MySQL database: quiz_app  
- Add tables: users, quizzes, questions, quiz_results  

**Local Credentials:**
- Admin 1: 01@teacher.aiub.edu | Password: teacher123  
- Admin 2: admin2@aiub.edu | Password: admin456  

**Requirements:**
- PHP 7.4+  
- MySQL / MariaDB  
- XAMPP or WAMP local server  

**Repository Link:**
OnlineQuizSystem on GitHub
