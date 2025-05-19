<?php
session_start();
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];

// Save order data temporarily in session
$_SESSION['order_info'] = [
    'name' => $name,
    'email' => $email,
    'address' => $address
];
include 'header.php';
// Fetch cart and calculate total
$total_price = 0;
$items = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_query = $conn->query("SELECT * FROM products WHERE id = $product_id");
    if ($product_query && $product_query->num_rows > 0) {
        $product = $product_query->fetch_assoc();
        $product_total = $product['price'] * $quantity;
        $total_price += $product_total;
        $items[] = [
            'name' => $product['name'],
            'quantity' => $quantity,
            'price' => $product_total
        ];
    }
}

$_SESSION['order_items'] = $items;
$_SESSION['order_total'] = $total_price;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Confirm Your Order</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-3xl mx-auto bg-white p-8 shadow rounded">
        <h2 class="text-2xl font-bold mb-6">Review & Confirm Your Order</h2>

        <h3 class="text-lg font-semibold mb-2">Shipping Info:</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($address)) ?></p>

        <h3 class="text-lg font-semibold mt-6 mb-2">Items:</h3>
        <ul class="mb-4">
            <?php foreach ($items as $item): ?>
                <li><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>): ₹<?= number_format($item['price'], 2) ?></li>
            <?php endforeach; ?>
        </ul>

        <p class="text-xl font-bold mb-6">Total: ₹<?= number_format($total_price, 2) ?></p>

        <form action="place_order.php" method="POST">
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="payment_method" value="COD" checked class="form-radio text-purple-600">
                    <span class="ml-2">Cash on Delivery (COD)</span>
                </label>
            </div>
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">Place Order</button>
        </form>
    </div>

</body>
</html>
