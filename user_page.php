<?php
session_start();
if(!isset($_SESSION['email'])){
    header('Location: login.php');
    exit();
}
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop Our Collection</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .font-title {
      font-family: 'Playfair Display', serif;
    }
    .font-body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="m-0 p-0">
<section class="relative w-full h-screen overflow-hidden">
  <div class="absolute inset-0">
    <div class="parallax-bg w-full h-full bg-center bg-cover" style="background-image: url('home.jpg');"></div>
  </div>
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>

  <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4">
    <h1 class="font-title text-4xl sm:text-5xl lg:text-7xl font-bold tracking-wide leading-tight mb-8 drop-shadow-2xl">
      SHOP OUR <br><span class="text-yellow-400">COLLECTION HERE</span>
    </h1>
    <a href="#"
       class="bg-yellow-500 no-underline text-gray-900 px-10 py-4 rounded-full text-lg sm:text-xl font-body font-semibold shadow-xl hover:bg-yellow-400 transition duration-300">
      SHOP NOW
    </a>
  </div>
</section>


  <!-- About Me -->
  <section class="bg-blue-100 py-20 px-6">
  <div class="max-w-4xl mx-auto text-center">

    <!-- Heading -->
    <h2 class="text-4xl font-bold text-gray-800 mb-4">About Me</h2>

    <!-- Description -->
    <p class="text-lg text-gray-700 leading-relaxed mb-6">
      Welcome to Craft-Aura – your one-stop destination for unique, artistic, and lovingly created handmade products.
      This platform was crafted with a deep appreciation for creativity and authenticity. Our mission is to bring soulful, sustainable, and stylish items to people who value detail and heart in every design.
      Whether you're here to shop, get inspired, or just explore, we’re glad you're part of our creative journey.
    </p>

    <!-- Button -->
    <a href="#more-info" class="inline-block bg-yellow-400 hover:bg-yellow-400 text-white font-semibold px-6 py-3 rounded transition">
      Learn More
    </a>
  </div>
</section>
<section id="products">
<?php include 'products.php'; ?>
</section>  
<?php include 'feedback.php'; ?>
  
  <script>
  window.addEventListener('scroll', function () {
    const scrolled = window.scrollY;
    const parallax = document.querySelector('.parallax-bg');
    parallax.style.transform = `translateY(${scrolled * 0.4}px)`;
  });
</script>

</body>
</html>
