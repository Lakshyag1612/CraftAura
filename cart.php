<?php
session_start();

// Ensure cart exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2 class='text-center text-red-500 mt-10'>Your cart is empty.</h2>";
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'header_admin.php';
  } else {
    include 'header.php';
  }
// Connect to the database
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product details from the cart
$cart_items = $_SESSION['cart'];
$product_ids = array_keys($cart_items); // Get all product IDs in the cart

// Fetch product details for each item in the cart
if (!empty($product_ids)) {
    $product_ids_str = implode(',', $product_ids);  // Join product IDs for SQL query
    $product_query = $conn->query("SELECT * FROM products WHERE id IN ($product_ids_str)");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Craftaura</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-purple-100">

<div class="max-w-7xl mx-auto py-10 px-6">
    <h1 class="text-3xl font-bold text-center mb-8">Your Cart 🛒</h1>
    
    <?php if (isset($product_query) && $product_query && $product_query->num_rows > 0): ?>
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left">Product</th>
                        <th class="px-6 py-3 text-left">Quantity</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($product = $product_query->fetch_assoc()): ?>
                        <tr class="border-b">
                            <td class="px-6 py-3">
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-20 h-20 object-cover rounded">
                                <span class="ml-4"><?= htmlspecialchars($product['name']) ?></span>
                            </td>
                            <td class="px-6 py-3">
                                <!-- Quantity Control -->
                                <form action="update_cart.php" method="POST" class="flex items-center space-x-2">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" name="action" value="decrease" class="px-3 py-1 bg-gray-300 text-black rounded">-</button>
                                    <input type="number" name="quantity" value="<?= $_SESSION['cart'][$product['id']] ?>" min="1" class="w-12 text-center border border-gray-300 rounded">
                                    <button type="submit" name="action" value="increase" class="px-3 py-1 bg-gray-300 text-black rounded">+</button>
                                </form>
                            </td>
                            <td class="px-6 py-3">₹<?= number_format($product['price'], 2) ?></td>
                            <td class="px-6 py-3">₹<?= number_format($product['price'] * $_SESSION['cart'][$product['id']], 2) ?></td>
                            <td class="px-6 py-3">
                                <a href="remove_from_cart.php?id=<?= $product['id'] ?>" class="text-red-500 hover:underline">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Cart Summary -->
            <div class="mt-6 text-right">
                <p class="text-xl font-bold">Total: ₹
                    <?php
                    $total_price = 0;
                    foreach ($cart_items as $product_id => $quantity) {
                        $product_query = $conn->query("SELECT price FROM products WHERE id = $product_id");
                        if ($product_query && $product_query->num_rows > 0) {
                            $product = $product_query->fetch_assoc();
                            $total_price += $quantity * $product['price'];
                        }
                    }
                    echo number_format($total_price, 2);
                    ?>
                </p>
                
                <a href="checkout_page.php" class="px-6 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 mt-4 inline-block">Proceed to Checkout</a>
       </div>
    <?php else: ?>
        <p class="text-center text-gray-500">No products found in the cart.</p>
    <?php endif; ?>
</div>

</body>
</html>
