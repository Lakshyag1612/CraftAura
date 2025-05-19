<?php
session_start();
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include 'header.php';

// Check if the user is signed in
$user_signed_in = isset($_SESSION['user_id']); // Assuming user_id exists in the session when signed in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Craftaura</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-purple-100">

<div class="max-w-7xl mx-auto py-10 px-6">

    <!-- Checkout Page Header -->
    <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>

    <div class="flex space-x-12">
        <!-- Order Summary Section -->
        <div class="w-1/3">
            <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="px-6 py-3 text-left">Product</th>
                            <th class="px-6 py-3 text-left">Quantity</th>
                            <th class="px-6 py-3 text-left">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_price = 0;
                        foreach ($_SESSION['cart'] as $product_id => $quantity) {
                            $product_query = $conn->query("SELECT * FROM products WHERE id = $product_id");
                            if ($product_query && $product_query->num_rows > 0) {
                                $product = $product_query->fetch_assoc();
                                $product_total = $product['price'] * $quantity;
                                $total_price += $product_total;
                                ?>
                                <tr class="border-b">
                                    <td class="px-6 py-3"><?= htmlspecialchars($product['name']) ?></td>
                                    <td class="px-6 py-3"><?= $quantity ?></td>
                                    <td class="px-6 py-3">₹<?= number_format($product_total, 2) ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <div class="mt-6 text-right">
                    <p class="text-xl font-bold">Total: ₹<?= number_format($total_price, 2) ?></p>
                </div>
            </div>
        </div>

        <!-- Order Form Section -->
        <div class="w-2/3">
            <h2 class="text-2xl font-bold mb-4">Your Information</h2>
            <div class="bg-white p-6 rounded-lg shadow-lg">

                <form action="final_order.php" method="POST" id="order_form">

                    <!-- Delivery Information -->
                    <div class="mb-4">
                        <h3 class="font-semibold">Delivery Information</h3>
                        <label for="name" class="block text-sm">Name</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded" required>

                        <label for="email" class="block text-sm mt-4">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded" required>

                        <label for="address" class="block text-sm mt-4">Delivery Address</label>
                        <textarea name="address" id="address" class="w-full px-4 py-2 border border-gray-300 rounded" required></textarea>

                        <div class="mt-4">
                            <label for="save_delivery" class="flex items-center space-x-2">
                                <input type="checkbox" id="save_delivery" name="save_delivery" class="h-4 w-4 border-gray-300">
                                <span class="text-sm">Save delivery information to your profile</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex justify-between">
                        <button type="button" class="px-6 py-2 bg-gray-300 text-black rounded hover:bg-gray-400" onclick="window.location.href='cart.php'">Adjust Cart</button>
                        <button type="submit" class="px-6 py-2 bg-purple-500 text-white rounded hover:bg-purple-600" >Complete Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Form validation for payment fields
    document.getElementById('order_form').addEventListener('submit', function(e) {
        const cardNumber = document.getElementById('card_number').value;
        const expiryDate = document.getElementById('expiry_date').value;
        const cvv = document.getElementById('cvv').value;
        
          });
</script>

</body>
</html>
