<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $firstName = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $services = filter_var($_POST['services'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.html?status=invalid_email");
        exit;
    }
    
    // Set recipient
    $to = "hello@aixinsolutions.com";
    
    // Set subject
    $subject = "New Contact Form Submission: $services";
    
    // Build message
    $email_message = "You have received a new message from your website contact form.\n\n";
    $email_message .= "Name: $firstName $lastName\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Services Interested In: $services\n\n";
    $email_message .= "Message:\n$message\n";
    
    // Set headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    
    // Send email
    if (mail($to, $subject, $email_message, $headers)) {
        header("Location: contact.html?status=success");
    } else {
        header("Location: contact.html?status=error");
    }
    exit;
} else {
    // Not a POST request, redirect to contact page
    header("Location: contact.html");
    exit;
}
?>