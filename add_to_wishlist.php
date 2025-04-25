<?php
session_start();

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

    $action = 'added';
    if (in_array($product_id, $_SESSION['wishlist'])) {
        // Remove if already in wishlist
        $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$product_id]);
        $action = 'removed';
    } else {
        $_SESSION['wishlist'][] = $product_id;
    }

    echo json_encode([
        'success' => true,
        'action' => $action,
        'count' => count($_SESSION['wishlist']),
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
