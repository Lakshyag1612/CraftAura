<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CRAFTAURA - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="common.css">
</head>

<body class="bg-gray-100">
    <nav id="mainNavbar" class="sticky top-0 bg-[#e6e1f4] shadow-md z-50 transition-transform duration-300">

        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between px-4 py-3">
            <div class="flex items-center space-x-2">
                <span class="text-lg font-bold text-gray-800">🧵 CRAFTAURA</span>
            </div>

            <div class="flex-grow hidden md:flex justify-center">
                <input type="text" placeholder="Search inventory..." class="w-1/2 px-3 py-1 rounded border border-gray-300"/>
                <button class="ml-1 px-2 py-1 text-gray-800 hover:text-black focus:outline-none">🔍</button>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative group hidden md:block">
                    <div class="relative inline-block text-left">
                        <button id="accountBtn" class="flex items-center text-sm font-medium px-4 py-2 focus:outline-none">
                            👤 Hi Admin <?= $_SESSION['name']; ?>
                        </button>

                        <div class="accountDropdown hidden absolute right-0 bg-white border rounded shadow-md mt-2 w-48 z-50">
                            <button onclick="window.location.href='profile.php'" class="block no-underline px-4 py-2 w-full hover:bg-gray-100">My Profile</button>
                            <button onclick="window.location.href='add_product.php'" class="block no-underline px-4 py-2 w-full hover:bg-gray-100">➕ Add Product</button>
                            <button onclick="window.location.href='edit_discount.php'" class="block no-underline px-4 py-2 w-full hover:bg-gray-100">✏️ Edit Discount</button>
                            <button onclick="window.location.href='logout.php'" class="block px-4 py-2 w-full hover:bg-gray-100">Logout</button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="menu-btn" class="md:hidden text-2xl">☰</button>
            </div>
        </div>

        <div class="flex hidden md:flex items-center justify-center space-x-8 text-sm font-semibold text-gray-800 border-t border-gray-300 h-12">
            <a href="admin_page.php" class="no-underline">Home</a>
            <a href="particular_product.php?category=Pottery" class="no-underline">Pottery</a>
            <a href="particular_product.php?category=Statues" class="no-underline">Statues</a>
            <a href="particular_product.php?category=Painting" class="no-underline">Painting</a>
            <a href="particular_product.php?category=Home Decor" class="no-underline ">Home Decor</a>
            <a href="particular_product.php?special=1" class="text-yellow-600 no-underline">Special Offers</a>
        </div>

        <div id="mobile-menu" class="md:hidden hidden px-4 py-2 space-y-3">
            <a href="admin_page.php" class="block font-bold">HOME</a>
            <a href="#" class="block font-bold text-yellow-700 no-underline">MANAGE</a>
            <div class="ml-4 space-y-1 text-sm text-gray-700 no-underline">
                <a href="add_product.php">Add Product</a>
                <a href="edit_discount.php">Edit Discount</a>
            </div>
            <a href="#" class="block font-bold no-underline">CATEGORIES</a>
            <a href="particular_product.php?category=Pottery" class="block font-bold">Pottery</a>
            <a href="particular_product.php?category=Painting" class="block font-bold">Painting</a>
            <a href="particular_product.php?category=Statues" class="block font-bold">Statues</a>
            <a href="particular_product.php?category=Home Decor" class="block font-bold">Home Decor</a>
            <a href="particular_product.php?special=1" class="block font-bold text-yellow-700 no-underline">Special Offers</a>
        </div>
    </nav>

    <div class="bg-blue-500 text-center text-white text-sm py-1.5">
        Admin Panel – Manage Your Marketplace
    </div>

    <script>
        const btn = document.getElementById('accountBtn');
        const dropdown = document.querySelector('.accountDropdown');

        btn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

    let lastScrollTop = 0;
    const navbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            // Scrolling down
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            navbar.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For mobile or negative scroll
    });

    </script>
</body>
</html>
