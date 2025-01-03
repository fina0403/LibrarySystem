<?php
session_start();

// Check if the user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

// Include database connection
include('db_connect.php'); // Include the connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO books (`title`, `author`, `isbn`, `quantity`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $author, $isbn, $quantity);
    if ($stmt->execute()) {
        // Notify user but stay on the same page
        $message = "Book added successfully!";
        $message_type = "success";
    } else {
        // Notify user but stay on the same page
        $message = "Error adding book: " . $stmt->error;
        $message_type = "error";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function cancelAction() {
            window.location.href = 'Admin.php'; // Redirect to Admin.php
        }
    </script>
</head>
<body>
    <header class="p-3 bg-primary text-white text-center">
        <h1>Library Management System</h1>
    </header>
    <div class="container mt-5">
        <h2>Add Book</h2>
        
        <!-- Show message notification -->
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo ($message_type == 'success') ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="title" class="form-label">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" onclick="cancelAction()">Cancel</button>
        </form>
    </div>
    <footer class="p-3 bg-dark text-white text-center mt-5">
        <p>&copy; 2025 Library Management System</p>
    </footer>
</body>
</html>
<?php

$conn->close();
?>
