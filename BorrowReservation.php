<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

$sql = "SELECT * FROM books WHERE quantity > 0";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $action = $_POST['action']; 
    $username = $_SESSION['username'];

    $user_sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();
    $user_id = $user['user_id'];
    $stmt->close();

    if ($action === 'borrow') {
        $borrow_sql = "INSERT INTO book_borrowings (user_id, book_id, status) VALUES (?, ?, 'borrowed')";
        $stmt = $conn->prepare($borrow_sql);
        $stmt->bind_param("ii", $user_id, $book_id);

        $update_sql = "UPDATE books SET quantity = quantity - 1 WHERE book_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $book_id);

        if ($stmt->execute() && $update_stmt->execute()) {
            $message = "You have successfully borrowed the book!";
        } else {
            $message = "Error borrowing the book. Please try again.";
        }
        $stmt->close();
        $update_stmt->close();
    } elseif ($action === 'reserve') {
        $reserve_sql = "INSERT INTO book_borrowings (user_id, book_id, status) VALUES (?, ?, 'reserved')";
        $stmt = $conn->prepare($reserve_sql);
        $stmt->bind_param("ii", $user_id, $book_id);

        if ($stmt->execute()) {
            $message = "You have successfully reserved the book!";
        } else {
            $message = "Error reserving the book. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow or Reserve Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Borrow or Reserve Books</h2>
        <hr>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                <p class="card-text">Author: <?php echo htmlspecialchars($book['author']); ?></p>
                                <p class="card-text">Available Copies: <?php echo $book['quantity']; ?></p>
                                <form method="POST" action="BorrowReservation.php">
                                    <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                    <button type="submit" name="action" value="borrow" class="btn btn-primary">Borrow</button>
                                    <button type="submit" name="action" value="reserve" class="btn btn-secondary">Reserve</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    No books are currently available.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
