<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$name = $_POST['name'];
$price = $_POST['price'];
$category = $_POST['category'];
$description = $_POST['description'];
$special_offer = isset($_POST['special_offer']) ? 1 : 0;


$uploaded_by = $_SESSION['email']; // 👈 Important

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No image uploaded']);
    exit();
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO products (name, price, category, description, image, uploaded_by, special_offer) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdssssi", $name, $price, $category, $description, $target_file, $uploaded_by, $special_offer);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product added successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
}
