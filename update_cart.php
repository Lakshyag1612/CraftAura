<?php
session_start();

// Ensure cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id and quantity are set
if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'];

    // Get current quantity
    $current_quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;

    // Update quantity based on action
    if ($action === 'increase') {
        $_SESSION['cart'][$product_id] = $current_quantity + 1;
    } elseif ($action === 'decrease' && $current_quantity > 1) {
        $_SESSION['cart'][$product_id] = $current_quantity - 1;
    }

    // Redirect back to the cart page
    header('Location: cart.php');
    exit();
}
?>
