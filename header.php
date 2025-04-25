<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRAFTAURA</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="common.css">
</head>

<body class="bg-gray-100">
    <!-- navbar.php -->
    <nav class="sticky top-0 bg-[#e6e1f4] shadow-md z-50">
        <!-- Top Navbar -->
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between px-4 py-3">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <span class="text-lg font-bold text-gray-800">🧵 CRAFTAURA</span>
            </div>

            <!-- Search Bar (Center in desktop) -->
            <div class="flex-grow hidden md:flex justify-center">
                <input type="text" placeholder="Search our collections" class="w-1/2 px-3 py-1 rounded border border-gray-300" />
                <button class="ml-1 px-2 py-1 text-gray-800 hover:text-black focus:outline-none">🔍</button>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Account Dropdown -->
                <div class="relative group hidden md:block">
                    <div class="relative inline-block text-left">
                        <button id="accountBtn" class="flex items-center text-sm font-medium px-4 py-2 focus:outline-none">
                            👤 Hi <?= $_SESSION['name']; ?>
                        </button>

                        <div class="accountDropdown hidden absolute right-0 bg-white border rounded shadow-md mt-2 w-44 z-50">
                            <button onclick="contact_us.php" class="block no-underline px-4 py-2 w-full hover:bg-gray-100">Contact us</button>
                            <button onclick="profile.php" class="block no-underline  px-4 py-2 w-full hover:bg-gray-100">My Profile</button>
                            <div id="wishlistCount" class="text-sm hidden w-full md:block">
                                <button class="w-full ">
                                    ❤️ Wishlist <?= isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0 ?> item<?= (isset($_SESSION['wishlist']) && count($_SESSION['wishlist']) !== 1) ? 's' : '' ?>
                                </button>
                            </div>
                            <button onclick="window.location.href='logout.php'" class="block px-4 py-2 w-full hover:bg-gray-100">Logout</button>
                        </div>
                    </div>
                </div>

                <!-- Cart -->
                <?php
$cart_count = isset($_SESSION['cart']) 
    ? array_sum(array_column($_SESSION['cart'], 'quantity')) 
    : 0;
?>

<a href="cart.php" class="text-sm hidden md:block" id="cart-count">
    🛒 <?= $cart_count ?> item<?= $cart_count != 1 ? 's' : '' ?>
</a>

                <!-- Mobile menu button -->
                <button id="menu-btn" class="md:hidden text-2xl">☰</button>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="flex hidden md:flex items-center justify-center space-x-8 text-sm font-semibold text-gray-800 border-t border-gray-300 h-12 text">
            <a href="user_page.php" class="no-underline">Home</a>
            <a href="particular_product.php?category=Pottery" class="no-underline">Pottery</a>
            <a href="particular_product.php?category=Statues" class="no-underline">Statues</a>
            <a href="particular_product.php?category=Painting" class="no-underline">Painting</a>
            <a href="particular_product.php?category=Home Decor" class="no-underline">Home Decor</a>
            <a href="particular_product.php?category=Special Offers" class="no-underline text-blue-500">SPECIAL OFFERS</a>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden px-4 py-2 space-y-3">
            <a href="#" class="block font-bold">HOME</a>
            <a href="#" class="block font-bold text-yellow-700 no-underline">SHOP</a>
            <div class="ml-4 space-y-1 text-sm text-gray-700 no-underline">
                <a href="#">By Price</a>
                <a href="#">By Rating</a>
                <a href="#">By Category</a>
                <a href="#">All Products</a>
            </div>
            <a href="#" class="block font-bold no-underline">SHADOW BOXES</a>
            <a href="#" class="block font-bold no-underline">CAKE TOPPERS</a>
            <a href="#" class="block font-bold no-underline">BLOG</a>
            <a href="#" class="block font-bold no-underline">SPECIAL OFFERS</a>
            <a href="#" class="block font-bold no-underline">ABOUT</a>
        </div>
    </nav>

    <!-- FREE DELIVERY BANNER -->
    <div class="bg-blue-500 text-center text-white text-sm py-1.5">
        FREE DELIVERY ON ORDERS OVER Rs100!
    </div>

        <script>
    function updateCartCount() {
        fetch('cart_count.php')
            .then(response => response.text())
            .then(count => {
                const cartText = `🛒 ${count} item${count != 1 ? 's' : ''}`;
                const cartElement = document.getElementById('cart-count');
                if (cartElement) {
                    cartElement.textContent = cartText;
                }
            })
            .catch(error => console.error('Cart count fetch error:', error));
    }

    // Optional: Refresh every few seconds or call manually after cart actions
    // setInterval(updateCartCount, 10000); 

        const btn = document.getElementById('accountBtn');
        const dropdown = document.querySelector('.accountDropdown');

        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Optional: Close dropdown if clicked outside
        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
