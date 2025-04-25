<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize input
$product_id = (int) ($_POST['product_id'] ?? 0);
$rating = (int) ($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$user_name = $_SESSION['name'] ?? 'Anonymous';

if ($product_id && $rating && $comment) {
    // Insert into reviews table
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_name, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isis", $product_id, $user_name, $rating, $comment);
    if ($stmt->execute()) {
        header("Location: product.php?id=$product_id"); // Redirect back to product page
        exit();
    } else {
        echo "Error adding review.";
    }
} else {
    echo "Invalid input.";
}
?>
