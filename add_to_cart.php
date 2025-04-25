<?php
session_start();

// Check if the product ID and quantity are passed
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Initialize cart if not already initialized
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the item is already in the cart, update the quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // If it's a new item, add it to the cart
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Return a response (optional, if needed for frontend updates)
    echo json_encode([
        'status' => 'success',
        'count' => count($_SESSION['cart']) // Update the cart item count
    ]);
} else {
    echo json_encode(['status' => 'error']);
}
?>
