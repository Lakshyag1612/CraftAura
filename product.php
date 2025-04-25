<?php
session_start();
// Initialize Cart and Wishlist in session if not already
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

include 'header.php';
$conn = new mysqli("localhost", "root", "Lakshya@16", "users_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product by ID
$product_id = $_GET['id'] ?? 0;
$product_id = (int)$product_id;

$product_query = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $product_query->fetch_assoc();

if (!$product) {
    echo "<h2 class='text-center text-red-500 mt-10'>Product not found.</h2>";
    exit();
}

// Get reviews
$reviews_query = $conn->query("SELECT * FROM reviews WHERE product_id = $product_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - Craftaura</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-purple-100">
<div class="max-w-7xl mx-auto py-10 px-6">

  <!-- Product Section -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-8 rounded-lg shadow-lg">
    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-[400px] object-cover rounded">

    <div>
      <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-xl text-purple-600 font-semibold mb-1">₹<?= number_format($product['price'], 2) ?></p>
      <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($product['category']) ?></p>
      <p class="text-gray-700 mb-6"><?= htmlspecialchars($product['description']) ?></p>
      <div class="mt-4 text-gray-700">
  <h3 class="text-lg font-semibold mb-2">Description</h3>
  <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
</div>

      <!-- Frame Size Dropdown -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Frame Size:</label>
        <select class="border px-3 py-2 rounded w-full">
          <option>22.86 x 22.86cm | 9x9</option>
          <option>30 x 30cm | 12x12</option>
          <option>40 x 40cm | 16x16</option>
        </select>
      </div>

      <!-- Quantity Input -->
      <div class="flex items-center mb-6">
        <label class="text-gray-700 mr-3">Quantity:</label>
        <input type="number" value="1" min="1" class="border w-20 px-2 py-1 rounded">
      </div>

      <!-- Buttons -->
      <div class="flex space-x-4">
  <button id="wishlistBtn" class="px-6 py-2 bg-pink-400 text-white rounded hover:bg-pink-500">❤️ Add to Wishlist</button>
  <button id="addToCartBtn" class="px-6 py-2 bg-purple-500 text-white rounded hover:bg-purple-600" data-id="<?= $product['id'] ?>">
  Add to Cart 🛒
</button>
<a href="product_managment.php?id=<?= $product['id'] ?>" class="text-blue-600 hover:underline">Edit</a>

</div>

    </div>
  </div>

  <!-- Reviews Section -->
  <div class="bg-white p-8 rounded-lg shadow-lg mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Reviews:</h2>

    <?php if ($reviews_query->num_rows > 0): ?>
      <?php while($review = $reviews_query->fetch_assoc()): ?>
        <div class="border-b border-gray-200 pb-4 mb-4">
          <p class="font-semibold"><?= htmlspecialchars($review['user_name']) ?> 
            <span class="text-sm text-gray-500"><?= date('d M Y', strtotime($review['created_at'])) ?></span> 
            <span class="text-yellow-400 ml-2">⭐ <?= $review['rating'] ?></span>
          </p>
          <p class="text-gray-700 mt-1"><?= htmlspecialchars($review['comment']) ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-500">No reviews yet.</p>
    <?php endif; ?>
  </div>

  <!-- Leave a Review -->
  <div class="bg-white p-8 rounded-lg shadow-lg mt-10">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Leave a Review:</h2>
    <form method="POST" action="add_review.php" class="space-y-4">
      <input type="hidden" name="product_id" value="<?= $product_id ?>">
      
      <div>
        <label class="block text-gray-700 mb-2">Stars:</label>
        <select name="rating" class="border px-3 py-2 rounded w-full">
          <option value="5">⭐ 5 - Excellent</option>
          <option value="4">⭐ 4 - Good</option>
          <option value="3">⭐ 3 - Average</option>
          <option value="2">⭐ 2 - Poor</option>
          <option value="1">⭐ 1 - Terrible</option>
        </select>
      </div>

      <div>
        <label class="block text-gray-700 mb-2">Review:</label>
        <textarea name="comment" rows="4" class="border px-3 py-2 rounded w-full" placeholder="Write your review..."></textarea>
      </div>

      <button type="submit" class="bg-purple-500 text-white px-6 py-2 rounded hover:bg-purple-600">Add Review</button>
    </form>
  </div>

</div><script>
document.addEventListener("DOMContentLoaded", function () {
    const wishlistBtn = document.getElementById('wishlistBtn');
    const cartBtn = document.getElementById('addToCartBtn');

    // Wishlist Button
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function () {
            const productId = <?= $product_id ?>;  // Ensure $product_id is properly inserted here

            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'added') {
                        wishlistBtn.innerText = '❤️ In Wishlist';
                        wishlistBtn.classList.remove('bg-pink-400');
                        wishlistBtn.classList.add('bg-green-500');
                    } else {
                        wishlistBtn.innerText = '❤️ Add to Wishlist';
                        wishlistBtn.classList.remove('bg-green-500');
                        wishlistBtn.classList.add('bg-pink-400');
                    }

                    const wishlistCount = document.getElementById('wishlistCount');
                    if (wishlistCount) {
                        wishlistCount.innerText = `❤️ Wishlist ${data.count} item${data.count !== 1 ? 's' : ''}`;
                    }
                }
            });
        });
    }

    // Cart Button
    if (cartBtn) {
        cartBtn.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const quantityInput = document.querySelector('input[type="number"]');
            const quantity = quantityInput ? quantityInput.value : 1;

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update the button text to "Added ✅" and disable it
                    cartBtn.innerText = "Added ✅";
                    cartBtn.disabled = true; // Disable the button to prevent multiple clicks

                    // Update the cart count dynamically without reloading the page
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.innerText = `🛒 ${data.count} item${data.count !== 1 ? 's' : ''}`;
                    }
                } else {
                    console.log('Error:', data.message);  // Log errors if any
                }
            })
            .catch(error => {
                console.log('Request failed:', error);
            });
        });
    }
});

</script>

</body>
</html>
