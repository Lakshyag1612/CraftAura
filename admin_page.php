<?php
session_start();
if(!isset($_SESSION['email'])){
    header('Location: login.php');
    exit();
  }
  include 'header_admin.php';
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


<?php include 'products.php'; ?>
  
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
