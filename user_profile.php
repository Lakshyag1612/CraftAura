<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
include 'header.php';
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - CraftAura</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold text-center mb-6">👤 Welcome, <?= htmlspecialchars($name) ?></h1>

        <!-- Order History -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">🧾 Order History</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4 text-left">Order ID</th>
                        <th class="py-2 px-4 text-left">Product</th>
                        <th class="py-2 px-4 text-left">Price</th>
                        <th class="py-2 px-4 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT o.id, p.name, p.price, o.order_date 
                                            FROM orders o 
                                            JOIN products p ON o.product_id = p.id 
                                            WHERE o.user_email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                        <tr class="border-t">
                            <td class="py-2 px-4"><?= $row['id'] ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="py-2 px-4">Rs <?= $row['price'] ?></td>
                            <td class="py-2 px-4"><?= $row['order_date'] ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Basic Details -->
        <section>
            <h2 class="text-xl font-semibold mb-2">📄 Basic Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        </section>
    </div>
</body>
</html>
