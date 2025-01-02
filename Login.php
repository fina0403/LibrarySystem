<?php
// Start output buffering
ob_start();
session_start();

// Include database connection
include 'db_connect.php'; 

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

        // Check if it's an admin login with pre-defined credentials
        if ($username == 'admin' && $password == 'adminpassword') {
            // Predefined admin credentials
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'admin';  // Admin role
    
            // Redirect to the admin dashboard
            header("Location: Admin.php");
            exit();
        }
        
    // Prepare and execute the SQL query
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: Admin.php");
            } else {
                header("Location: Homepage.php");
            }
            exit();
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "Username not found. Please try again.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Library Management System - Login</h2>
        <form method="POST" action="Login.php" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <hr>
        <p class="text-center">Don't have an account? <a href="Signup.php">Sign up</a></p>  <!-- Sign up link -->
        <?php if (!empty($error_message)): ?>
            <p class="text-danger text-center mt-3"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
// End output buffering
ob_end_flush();
?>
