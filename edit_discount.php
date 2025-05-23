<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['discount'] as $product_id => $new_discount) {
        $new_discount = floatval($new_discount);
        $stmt = $conn->prepare("UPDATE products SET discount = ? WHERE id = ?");
        $stmt->bind_param("di", $new_discount, $product_id);
        $stmt->execute();
    }
    $success = "Discounts updated successfully!";
}

// Fetch all products
$products = [];
$result = $conn->query("SELECT id, name, price, discount FROM products");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Discounts - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<?php include 'header_admin.php'; ?>

<div class="max-w-5xl mx-auto my-8 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-center">Edit Product Discounts</h1>

    <?php if (isset($success)): ?>
        <div class="mb-4 p-2 bg-green-100 text-green-700 border border-green-300 rounded">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Product</th>
                        <th class="px-4 py-2 text-left">Price (Rs)</th>
                        <th class="px-4 py-2 text-left">Current Discount (%)</th>
                        <th class="px-4 py-2 text-left">New Discount (%)</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php foreach ($products as $product): ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars($product['name']) ?></td>
                            <td class="px-4 py-2">Rs <?= number_format($product['price'], 2) ?></td>
                            <td class="px-4 py-2"><?= number_format($product['discount'], 1) ?></td>
                            <td class="px-4 py-2">
                                <input type="number" step="0.1" name="discount[<?= $product['id'] ?>]" value="<?= $product['discount'] ?>" class="w-24 px-2 py-1 border rounded">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-center">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Update Discounts
            </button>
        </div>
    </form>
</div>
</body>
</html>
