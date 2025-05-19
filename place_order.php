<?php
// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();

$order_info = $_SESSION['order_info'] ?? [];
$order_items = $_SESSION['order_items'] ?? [];
$total_price = $_SESSION['order_total'] ?? 0;

if (!$order_info || !$order_items) {
    die("Order session expired.");
}

$name = $order_info['name'];
$email = $order_info['email'];
$address = $order_info['address'];

$subject = "Your CraftAura Order Confirmation";

$message = "Hi $name,\n\nThank you for your order on CraftAura!\n\nHere are your order details:\n";
foreach ($order_items as $item) {
    $message .= "- {$item['name']} (x{$item['quantity']}): ₹" . number_format($item['price'], 2) . "\n";
}
$message .= "\nTotal: ₹" . number_format($total_price, 2);
$message .= "\n\nShipping Address:\n$address";
$message .= "\n\nPayment Method: Cash on Delivery";
$message .= "\n\nWe will contact you when your order is out for delivery.\n\nThank you,\nCraftAura Team";

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';                // SMTP server (Gmail)
    $mail->SMTPAuth = true;                        // Enable SMTP authentication
    $mail->Username = 'lakshyagarg1612@gmail.com';      // Your Gmail address
    $mail->Password = 'yfdar vkiy edai wixa';        // Your Gmail App Password
    $mail->SMTPSecure = 'tls';                     // Encryption TLS
    $mail->Port = 587;                             // TCP port to connect to

    //Recipients
    $mail->setFrom('support@craftaura.com', 'CraftAura Support');
    $mail->addAddress($email, $name);

    // Content
    $mail->isHTML(false); // Plain text email
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();

    // Clear session after successful email sent
    unset($_SESSION['cart']);
    unset($_SESSION['order_info']);
    unset($_SESSION['order_items']);
    unset($_SESSION['order_total']);

    echo "<p style='font-size:20px;'>Order placed successfully! A confirmation has been sent to your email.</p>";
    echo "<a href='index.php'>Return to Home</a>";
} catch (Exception $e) {
    echo "Failed to send confirmation email. Mailer Error: {$mail->ErrorInfo}";
}
?>
