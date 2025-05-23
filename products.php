<?php
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Pagination ---
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- Sorting ---
$sort_options = [
    'price_asc' => '(price - (price * discount / 100)) ASC',
    'price_desc' => '(price - (price * discount / 100)) DESC',
    'discount_asc' => 'discount ASC',
    'discount_desc' => 'discount DESC',
    'name_asc' => 'name ASC',
    'name_desc' => 'name DESC',
    'rating_desc' => 'rating DESC'
];


$sort = $_GET['sort'] ?? 'name_asc';
$order_by = $sort_options[$sort] ?? 'name ASC';

// Get total count
$total_result = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc();
$total_products = $total_result['count'];
$total_pages = ceil($total_products / $limit);

// Fetch products
$query = "SELECT * FROM products ORDER BY $order_by LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

$category = $_GET['category'] ?? null;
$where_clause = $category ? "WHERE category = '" . $conn->real_escape_string($category) . "'" : '';

// Count total products (filtered by category if exists)
$total_result = $conn->query("SELECT COUNT(*) as count FROM products $where_clause")->fetch_assoc();
$total_products = $total_result['count'];
$total_pages = ceil($total_products / $limit);

// Fetch products with sorting, pagination, and category filter
$query = "SELECT * FROM products $where_clause ORDER BY $order_by LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
// Example: Fetching all products
$products = mysqli_query($conn, "SELECT * FROM products");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <style>
        .card-container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .card { border: 1px solid #ddd; padding: 15px; width: 250px; text-align: center; box-shadow: 2px 2px 10px #ccc; cursor: pointer; }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .pagination a { margin: 0 5px; text-decoration: none; }
        .sort-form { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
<h1 style="text-align: center;">
    <?= $category ? htmlspecialchars($category) . ' Products' : 'All Products' ?>
</h1>


<!-- Sorting Form (now above) -->
<form method="GET" class="sort-form">
    <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
    <label><strong>Sort by:</strong></label>
    <select name="sort" onchange="this.form.submit()">
        <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
        <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Name Z-A</option>
        <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Price Low to High</option>
        <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Price High to Low</option>
        <option value="rating_desc" <?= $sort == 'rating_desc' ? 'selected' : '' ?>>Rating High to Low</option>
    </select>
</form>

<!-- Products Grid -->
<div class="card-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <?php
            $originalPrice = $row['price'];
            $discount = $row['discount'];
            $finalPrice = $originalPrice;

            if ($discount > 0) {
                $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
            }
        ?>
        <a href="product.php?id=<?= $row['id'] ?>" class="card" style="text-decoration: none; color: inherit;">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <h3><?= htmlspecialchars($row['name']) ?></h3>

            <?php if ($discount > 0): ?>
                <p style="color: red; font-weight: bold;">
                    €<?= number_format($finalPrice, 2) ?>
                    <span style="text-decoration: line-through; color: gray; font-size: 0.9em;">
                        €<?= number_format($originalPrice, 2) ?>
                    </span>
                    <span style="background-color: #ffe4e6; color: #dc2626; font-size: 0.8em; padding: 2px 5px; border-radius: 5px;">
                        <?= $discount ?>% OFF
                    </span>
                </p>
            <?php else: ?>
                <p>€<?= number_format($originalPrice, 2) ?></p>
            <?php endif; ?>

            <p><?= htmlspecialchars($row['category']) ?></p>
            <p class="text-gray-600 text-sm mt-2"><?= htmlspecialchars($row['description']) ?></p>
        </a>
    <?php endwhile; ?>
</div>


<!-- Pagination -->
<div class="pagination" style="text-align:center; margin-top: 20px;">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&sort=<?= $sort ?><?= $category ? '&category=' . urlencode($category) : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

</body>
</html>
