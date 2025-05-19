<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['product_id'])) {
    header("Location: index.php");
    exit();
}

$review_id = (int) $_GET['id'];
$product_id = (int) $_GET['product_id'];

$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("DELETE FROM reviews WHERE id = $review_id");

header("Location: product.php?id=$product_id");
exit();
