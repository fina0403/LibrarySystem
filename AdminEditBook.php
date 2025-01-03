<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}


include('db_connect.php'); 

// Fetch the book details
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT `book_id`, `title`, `author`, `isbn`, `quantity` FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        header("Location: Admin.php");
        exit();
    }
    $stmt->close();
} else {
    header("Location: Admin.php"); 
    exit();
}

// Handle form submission to update book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $quantity = $_POST['quantity'];

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, quantity = ? WHERE book_id = ?");
    $stmt->bind_param("sssii", $title, $author, $isbn, $quantity, $book_id);

    if ($stmt->execute()) {
        $message = "Book updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating book: " . $stmt->error;
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
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function cancelAction() {
            window.location.href = 'Admin.php'; 
        }
    </script>
</head>
<body>
    <header class="p-3 bg-primary text-white text-center">
        <h1>Library Management System</h1>
    </header>
    <div class="container mt-5">
        <h2>Edit Book</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo ($message_type == 'success') ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="title" class="form-label">Book Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($book['quantity']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
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
