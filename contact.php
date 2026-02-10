<?php
// HDSubmit Contact Form Handler
// This sends form submissions to info@hdsubmit.com

$to = "info@hdsubmit.com";

// Sanitize inputs
$first_name = htmlspecialchars(strip_tags($_POST['first_name'] ?? ''));
$last_name = htmlspecialchars(strip_tags($_POST['last_name'] ?? ''));
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(strip_tags($_POST['message'] ?? ''));

// Validate required fields
if (empty($first_name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo "Please fill in all required fields.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "Please provide a valid email address.";
    exit;
}

// Compose email
$subject = "HDSubmit Inquiry from {$first_name} {$last_name}";
$body = "New inquiry from HDSubmit website:\n\n";
$body .= "Name: {$first_name} {$last_name}\n";
$body .= "Email: {$email}\n\n";
$body .= "Message:\n{$message}\n";

$headers = "From: noreply@hdsubmit.com\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($to, $subject, $body, $headers)) {
    // Redirect back to the site with a success flag
    header("Location: index.html?submitted=1");
    exit;
} else {
    http_response_code(500);
    echo "Sorry, there was an error sending your message. Please email us directly at info@hdsubmit.com.";
}
?>
