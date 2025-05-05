<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "info@aixinsolutions.com";  
    $subject = "New Contact Form Submission";

    // Get data from form
    $firstName = strip_tags(trim($_POST["username"]));
    $lastName = strip_tags(trim($_POST["lastname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $services = strip_tags(trim($_POST["services"]));
    $message = strip_tags(trim($_POST["message"]));

    // Compose the email content
    $emailBody = "You received a new message from your website contact form:\n\n";
    $emailBody .= "First Name: $firstName\n";
    $emailBody .= "Last Name: $lastName\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Service: $services\n";
    $emailBody .= "Message:\n$message\n";

    // Email headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send email
    if (mail($to, $subject, $emailBody, $headers)) {
        echo "Message sent successfully.";
    } else {
        echo "Failed to send the message.";
    }
} else {
    // Not a POST request
    http_response_code(403);
    echo "There was a problem with your submission.";
}
?>
