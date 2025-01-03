<?php
session_start();

// Check if the user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

// Include database connection
include('db_connect.php');  // Include the connection file

// Fetch books from the database
$sql = "SELECT `book_id`, `title`, `author`, `isbn`, `quantity` FROM books";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Make the body take full height */
        html, body {
            height: 100%;
        }

        /* Flexbox to make the footer stick to the bottom */
        .content-wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .footer {
            margin-top: auto; 
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="Admin.php">Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Admin.php">Manage Book</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ManageBorrowings.php">Manage Borrowings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ManageUsers.php">Manage User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

   
    <div class="content-wrapper container mt-5">
        <!-- Welcome Section -->
        <div class="jumbotron text-center mb-4">
            <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="lead">
                You are logged in as an admin. From here, you can manage the books in the library, monitor borrowing activities, and manage user accounts.
            </p>
            <hr class="my-4">
            <p>
                Select the appropriate section from the navigation bar to begin managing the library.
            </p>
        </div>

        <!-- Book List Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Book Lists</h2>
            <a href="AdminAddBook.php" class="btn btn-success">Add Book</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>
                                <a href='AdminEditBook.php?id=" . $row['book_id'] . "' class='btn btn-warning btn-sm'>
                                    <i class='bi bi-pencil'></i> Edit
                                </a>
                                <a href='AdminDeleteBook.php?id=" . $row['book_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this book?\")'>
                                    <i class='bi bi-trash'></i> Delete
                                </a>
                              </td>"; 
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No books found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer class="footer p-3 bg-dark text-white text-center">
        <p>&copy; 2025 Library Management System</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php

$conn->close();
?>
