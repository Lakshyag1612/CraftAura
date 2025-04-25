<?php
include 'header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $mail = new PHPMailer(true);

  try {
      // Get form inputs safely
      $email = $_POST['email'] ?? '';
      $name = $_POST['name'] ?? '';
      $message = $_POST['message'] ?? '';

      if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
          throw new Exception("Invalid email address");
      }

      // Server settings
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'lakshyagarg1612@gmail.com';
      $mail->Password   = 'fdar vkiy edai wixa';
      $mail->SMTPSecure = 'tls';
      $mail->Port       = 587;

      // Recipients
      $mail->setFrom('yourgmail@gmail.com', 'Support');
      $mail->addAddress($email);

      // Content
      $mail->isHTML(false);
      $mail->Subject = 'Thank you for contacting us!';
      $mail->Body    = "Hi $name,\n\nWe have received your complaint:\n\n\"$message\"\n\nWe'll get back to you shortly.\n\nBest,\nCustomer Support";

      $mail->send();
      $success = "Message sent successfully!";
  } catch (Exception $e) {
      $error = "Mailer Error: {$mail->ErrorInfo}";
  }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6 font-sans bg-pink-100">
  <div class="max-w-xl mx-auto bg-white p-8 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Contact Us</h2>

    <?php if (!empty($success)) echo "<p class='text-green-600 mb-4'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='text-red-600 mb-4'>$error</p>"; ?>

    <form action="" method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 font-medium text-gray-700">Name</label>
        <input type="text" name="name" required class="w-full border border-gray-300 rounded px-4 py-2">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Email</label>
        <input type="email" name="email" required class="w-full border border-gray-300 rounded px-4 py-2">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Complaint</label>
        <textarea name="message" required rows="5" class="w-full border border-gray-300 rounded px-4 py-2"></textarea>
      </div>
      <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-400 transition">
        Submit
      </button>
    </form>
  </div>
</body>
</html>
