<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

include('db_connect.php'); 

// Handle book deletion
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        header("Location: Admin.php"); 
        exit();
    } else {
        echo "Error deleting book: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: Admin.php"); 
    exit();
}

$conn->close();
?>
