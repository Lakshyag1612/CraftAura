<?php
session_start();

$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    include 'header_admin.php';
  } else {
    include 'header.php';
  }

$email = $_SESSION['email'];
$name = $_SESSION['name'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Welcome, <?= htmlspecialchars($name) ?></h1>

        <?php if ($role === 'admin'): ?>
            <h2 class="text-xl font-semibold mb-4">Your Uploaded Products</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Name</th>
                        <th class="py-2">Category</th>
                        <th class="py-2">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM products WHERE uploaded_by = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr class="border-t">
                            <td class="py-2"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="py-2"><?= htmlspecialchars($row['category']) ?></td>
                            <td class="py-2">Rs <?= $row['price'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2 class="text-xl font-semibold mt-6 mb-2">Basic Admin Info</h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>

        <?php else: ?>
            <h2 class="text-xl font-semibold mb-4">My Orders</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Order ID</th>
                        <th class="py-2">Product</th>
                        <th class="py-2">Price</th>
                        <th class="py-2">Date</th>
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
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr class="border-t">
                            <td class="py-2"><?= $row['id'] ?></td>
                            <td class="py-2"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="py-2">Rs <?= $row['price'] ?></td>
                            <td class="py-2"><?= $row['order_date'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2 class="text-xl font-semibold mt-6 mb-2">Basic Details</h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
