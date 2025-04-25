<?php
session_start();

// Check if the product ID is passed in the URL
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    // Check if the cart exists in session and if the product is in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$product_id]);
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit();
?>
