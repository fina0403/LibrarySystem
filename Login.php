<?php
session_start();

// Check if the user is already logged in, if so, redirect to their dashboard
if (isset($_SESSION['username'])) {
    // Redirect to the appropriate dashboard based on the user's role
    if ($_SESSION['role'] === 'admin') {
        header("Location: Admin.php");
    } else {
        header("Location: Homepage.php");
    }
    exit();
}

include 'db_connect.php';  // Ensure you have your database connection here

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

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start a session and store user data
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on the role (Admin or User)
            if ($user['role'] === 'admin') {
                header("Location: Admin.php");
            } else {
                header("Location: Homepage.php");
            }
            exit();
        } else {
            echo "<p class='text-danger text-center'>Invalid password. Please try again.</p>";
        }
    } else {
        echo "<p class='text-danger text-center'>Username not found. Please try again.</p>";
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
    </div>
</body>
</html>
