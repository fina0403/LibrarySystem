<?php
session_start();

// Check if the user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Dashboard</h2>
        <p class="lead text-center">Welcome, Admin! You have full control over the library system.</p>
        <hr class="my-4">
        <!-- Add any admin-specific features here -->
        <div class="text-center">
            <a href="ManageBooks.php" class="btn btn-primary">Manage Books</a>
            <a href="ManageUsers.php" class="btn btn-secondary">Manage Users</a>
        </div>

        <hr class="my-4">
        <div class="text-center">
            <a href="Logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
