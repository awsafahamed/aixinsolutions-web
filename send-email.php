<?php
// Enable error reporting (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set JSON header for AJAX responses
header('Content-Type: application/json');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load PHPMailer classes
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';
require __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Sanitize and validate form data
$firstName = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) ?? '';
$lastName = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING) ?? '';
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
$services = filter_input(INPUT_POST, 'services', FILTER_SANITIZE_STRING) ?? '';
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) ?? '';

// Validate required fields
if (empty($firstName) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings for SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';    // Your SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hello@aixinsolutions.com'; // SMTP username
    $mail->Password   = 'Hello@123Aixin';      // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom('noreply@aixinsolutions.com', 'AIXIN Contact Form');
    $mail->addAddress('hello@aixinsolutions.com');
    $mail->addReplyTo($email, $firstName . ' ' . $lastName);

    // Content
    $mail->isHTML(false); // Set email format to plain text
    $mail->Subject = "New Contact Form Submission: $services";
    $mail->Body    = "You have received a new message from your website contact form.\n\n"
                   . "Name: $firstName $lastName\n"
                   . "Email: $email\n"
                   . "Services: $services\n\n"
                   . "Message:\n$message";

    // Send email
    $mail->send();
    
    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Your message has been sent successfully!'
    ]);
    
} catch (Exception $e) {
    // Error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Message could not be sent. Error: {$mail->ErrorInfo}"
    ]);
}
?>