<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
include 'header.php';

$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Get category from URL ---
$special = isset($_GET['special']) ? (int)$_GET['special'] : 0;
$category = $_GET['category'] ?? '';

if ($special) {
    $where_clause = "WHERE special_offer = 1";
    $page_title = "Special Offers";
} elseif (!empty($category)) {
    $escaped_category = $conn->real_escape_string($category);
    $where_clause = "WHERE category = '$escaped_category'";
    $page_title = htmlspecialchars($category) . " Collection";
} else {
    echo "<h2 class='text-center text-red-500 mt-10'>No category or special offer specified.</h2>";
    exit();
}


// --- Pagination ---
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- Sorting ---
$sort_options = [
    'price_asc' => 'price ASC',
    'price_desc' => 'price DESC',
    'name_asc' => 'name ASC',
    'name_desc' => 'name DESC',
    'rating_desc' => 'rating DESC'
];
$sort = $_GET['sort'] ?? 'name_asc';
$order_by = $sort_options[$sort] ?? 'name ASC';

$escaped_category = $conn->real_escape_string($category);
$where_clause = "WHERE category = '$escaped_category'";

// --- Total count ---
$total_result = $conn->query("SELECT COUNT(*) as count FROM products $where_clause")->fetch_assoc();
$total_products = $total_result['count'];
$total_pages = ceil($total_products / $limit);

// --- Fetch products ---
$query = "SELECT * FROM products $where_clause ORDER BY $order_by LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($category) ?> - Craftaura</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <div class="max-w-7xl mx-auto py-10 px-4">
  <h1 class="text-4xl font-bold text-center mb-6 text-gray-800"><?= $page_title ?></h1>

    <!-- Sort Dropdown -->
    <form method="GET" class="text-center mb-6">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
        <label class="mr-2 text-gray-700">Sort by:</label>
        <select name="sort" onchange="this.form.submit()" class="border px-3 py-1 rounded">
            <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
            <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Name Z-A</option>
            <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Price Low to High</option>
            <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Price High to Low</option>
            <option value="rating_desc" <?= $sort == 'rating_desc' ? 'selected' : '' ?>>Rating</option>
        </select>
    </form>

    <!-- Product Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition cursor-pointer" onclick="window.location='product.php?id=<?= $row['id'] ?>'">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-48 object-cover rounded">
            <h2 class="text-xl font-semibold mt-4 text-gray-800"><?= htmlspecialchars($row['name']) ?></h2>
            <p class="text-gray-600 mt-1">₹<?= number_format($row['price'], 2) ?></p>
            <p class="text-yellow-500 text-sm mt-1">⭐ <?= $row['rating'] ?? 'No Rating' ?></p>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8 space-x-2">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?category=<?= urlencode($category) ?>&sort=<?= $sort ?>&page=<?= $i ?>" class="px-3 py-1 rounded border <?= $page == $i ? 'bg-blue-500 text-white' : 'bg-white text-blue-500' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
  </div>
  
</body>
</html>
