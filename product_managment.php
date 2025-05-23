<?php
session_start();
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "No product selected.";
    exit();
}

$product_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $discount = $_POST['discount'];

    // Handle image
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $target_file = $product['image'];
    }

    $update = $conn->prepare("UPDATE products SET name=?, price=?, category=?, description=?, image=?, discount=? WHERE id=?");
    $update->bind_param("sdsssdi", $name, $price, $category, $desc, $target_file, $discount, $product_id);
     $update->execute();
    
    ob_start();
    header("Location: product.php?id=" . $product_id);    
    ob_end_flush();
    exit();
    
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
  include 'header_admin.php';
} else {
  include 'header.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

  <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 text-center">Edit Product</h1>
    <?php if (isset($_GET['updated'])): ?>
  <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
    Product updated successfully!
  </div>
<?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-4">
        <label class="block text-gray-700">Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="w-full p-2 border border-gray-300 rounded" required>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Category</label>
        <select name="category" class="w-full p-2 border border-gray-300 rounded" required>
          <?php
          $categories = ['Pottery', 'Statues', 'Painting', 'Home Decor', 'Special Offers'];
          foreach ($categories as $cat) {
              $selected = $cat === $product['category'] ? 'selected' : '';
              echo "<option value=\"$cat\" $selected>$cat</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Description</label>
        <textarea name="description" rows="4" class="w-full p-2 border border-gray-300 rounded" required><?= htmlspecialchars($product['description']) ?></textarea>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Price (Rs)</label>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="w-full p-2 border border-gray-300 rounded" required>
      </div>
  
      <div class="mb-4">
        <label class="block text-gray-700">Product Image</label>
        <input type="file" name="image" class="w-full">
        <img src="<?= $product['image'] ?>" alt="Current Image" class="mt-2 h-32">
      </div>


      <div class="mb-4">
  <label class="block text-gray-700">Discount (%)</label>
  <input type="number" step="0.01" name="discount" value="<?= isset($product['discount']) ? $product['discount'] : 0 ?>" class="w-full p-2 border border-gray-300 rounded" required>
</div>

      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
        Save Changes
      </button>
    </form>
  </div>
</body>
</html>
