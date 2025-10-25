<?php
session_start();
// Include the database connection file (db_connection.php)
include("db_connection.php"); 

$plainPassword = 'admin';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get and Sanitize Form Data
    // We now expect the first input to be the username (txt_uname)
    $username = trim($_POST['txt_uname']);
    $password = $_POST['txt_pw']; 

    if (empty($username) || empty($password)) {
        echo "<script>alert('Please enter both username and password.'); window.history.back();</script>";
        exit();
    }

    // 2. Prepare and Execute Database Query
    // !!! CRITICAL CHANGE: Query now looks up user by userName instead of email
    $query = "SELECT userID, userName, password, userTypeID FROM tbl_user WHERE LOWER(userName) = LOWER(?)";
    $stmt = $conn->prepare($query);
    // Bind the $username variable
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        // 3. Verify Password
        if (password_verify($password, $hashedPassword)) {
            
            // Password is correct! Create session variables.
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['username'] = $user['userName'];
            $_SESSION['user_type_id'] = $user['userTypeID']; 
            
            // Determine redirect location based on user type (Assuming 1 is Admin)
            if ($user['userTypeID'] == 1) {
                header("Location: admin_dashboard.html");
            } else {
                // Default redirect for other users
                header("Location: user_index.php"); 
            }
            exit();

        } else {
            // Invalid password
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        // No user found with that username
        echo "<script>alert('Invalid username'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Login Form</title>
 <link rel="stylesheet" href="css/signin.css">
</head>
<body>
 <div class="background"></div>
 <div class="form-container">
  <div class="logo">
   <a href="index.php"><img src="assets/img/LOGO.png" alt="Logo"></a>
  </div>
  <form action="" method="POST">
   <div class="input-group">
    <span class="icon">👤</span>
            <input type="text" name="txt_uname" placeholder="Username" required>
   </div>
   <div class="input-group">
    <span class="icon">🔒</span>
            <input type="password" name="txt_pw" placeholder="Password" required>
   </div>
   <button type="submit" class="signup-btn">LOGIN</button>
   <div class="divider">or</div>
   <div class="login-link">
    Don't have an account? <a href="signin.php">SIGN UP</a>
   </div>
  </form>
 </div>
</body>
</html>