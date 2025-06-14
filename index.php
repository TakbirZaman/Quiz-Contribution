<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online quiz System</title>
  <link rel="stylesheet"href="stylee.css">  
</head>

<body>
    <div style="text-align: center;">
        <p>
            <img src="aqi.jpg" alt="The Flexbox" height="70px" width="70px" style="border-radius: 60%; border: solid;">
        </p>
        <h1>AQI</h1>
    </div>

    <div class="flex-container">
        <div id="flex1">
            <h2>Registration</h2><br>

            <form id="regForm" action="registration.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <span class="error-message" id="nameError"></span>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span class="error-message" id="emailError"></span>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <span class="error-message" id="passwordError"></span>

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="error-message" id="confirmPasswordError"></span>

                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
                <span class="error-message" id="dobError"></span>

                <label for="country">Country</label>
                <select id="country" name="country" required>
                    <option value="">Select your country</option>
                    <option value="USA">United States</option>
                    <option value="UK">United Kingdom</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="India">India</option>
                    <option value="Other">Other</option>
                </select>
                <span class="error-message" id="countryError"></span>

                <label>Gender</label>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label>

                    <input type="radio" id="female" name="gender" value="Female" required>
                    <label for="female">Female</label>

                
                </div>
                <span class="error-message" id="genderError"></span>

                <label for="color">Favorite Color</label>
                <input type="color" id="color" name="color" required>

                <div id="terms" class="terms-container">
                    <input type="checkbox" id="agreeCheckbox" required>
                    <label for="agreeCheckbox">I agree to the <a href="#top">Terms and Conditions</a></label>   
                </div>

                <button type="submit">Register</button>
            </form>



        </div>
        <div class="right-side">
            <div id="flex3" class="flex">
                <h3 style="color: black;">LOGIN</h3>
                <form id="loginform" action="login.php" method="POST">
                    <label for="email">Email</label>
       <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
    </form>
    </div>
    
            <div id="flex4" class="flex">
    <div id="innerBox" class="quiz-container">
        <h2 class="quiz-heading">Quiz Information</h2>

        <table class="quiz-table" border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Total Questions</th>
                    <th>Time Limit</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "quiz_app");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM quizzes ORDER BY category ASC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['category']}</td>
                                <td>{$row['total_questions']}</td>
                                <td>{$row['time_limit']}</td>
                                <td>{$row['created_by']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No quizzes found.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="button-group">
            <button onclick="window.location.href='performance.php'" class="performance-btn">
                Check Performance
            </button>
        </div>
    </div>
</div>



        </div>
    </div>

  
<div id="successPopup" style="display:none; position: fixed; z-index: 5000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); text-align: center;">
    <div style="background-color: white; padding: 20px; margin: 200px auto; width: 300px; border-radius: 10px;">
        <h3>Registered Successfully!</h3>
        <button onclick="document.getElementById('successPopup').style.display='none'">Close</button>
    </div>
</div>




    <script src="validation.js"></script>
</body>
</html>