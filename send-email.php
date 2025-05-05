<?php
// Always set JSON content type header first
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if the script is being accessed directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Direct access forbidden']);
    exit;
}
// Include PHPMailer
require 'vendor/autoload.php';

// Collect form data
$firstName = $_POST['username'] ?? '';
$lastName = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$services = $_POST['services'] ?? '';
$message = $_POST['message'] ?? '';

// Validate required fields
if (empty($firstName) || empty($email) || empty($message)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hello@aixinsolutions.com';
    $mail->Password = 'Hello@123Aixin';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    
    // Enable debugging (level 2 for client/server communication)
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {
        error_log("SMTP debug level $level: $str");
    };

    // Recipients
    $mail->setFrom('hello@aixinsolutions.com', 'Admin Aixin Solutions');
    $mail->addAddress('info@aixinsolutions.com');
    $mail->addReplyTo($email, $firstName . ' ' . $lastName);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';
    
    $mail->Body = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #f8f9fa; padding: 15px; text-align: center; }
            .content { padding: 20px; }
            .footer { background-color: #f8f9fa; padding: 10px; text-align: center; font-size: 12px; }
            .label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>New Contact Form Submission</h2>
            </div>
            <div class="content">
                <p><span class="label">Name:</span> ' . htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName) . '</p>
                <p><span class="label">Email:</span> ' . htmlspecialchars($email) . '</p>
                <p><span class="label">Services:</span> ' . htmlspecialchars($services) . '</p>
                <p><span class="label">Message:</span><br>' . nl2br(htmlspecialchars($message)) . '</p>
            </div>
            <div class="footer">
                This email was sent from the AIXIN Solutions contact form.
            </div>
        </div>
    </body>
    </html>';

    $mail->AltBody = "Name: $firstName $lastName\nEmail: $email\nServices: $services\n\nMessage:\n$message";

    $mail->send();
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
} catch (Exception $e) {
    error_log('Mailer Error: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $e->getMessage()]);
}
?>