<?php
// Prevent direct access to the script
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
    exit;
}

// Set the receiving email address for the club
$receiving_email_address = 'info@nsemkuclub.ke';

// Sanitize and capture the form data
$name = strip_tags(trim($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = strip_tags(trim($_POST['subject'] ?? ''));
$message = strip_tags(trim($_POST['message'] ?? ''));

// Validate the inputs
if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "Please complete all the fields and provide a valid email address.";
    exit;
}

// Build the email content
$email_subject = "Website Contact Form: $subject";
$email_content = "You have received a new message from your website contact form.\n\n";
$email_content .= "Name: $name\n";
$email_content .= "Email: $email\n\n";
$email_content .= "Message:\n$message\n";

// Build the email headers
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send the email and return the exact string 'OK' required by validate.js
if (mail($receiving_email_address, $email_subject, $email_content, $headers)) {
    echo 'OK';
} else {
    http_response_code(500);
    echo "Oops! Something went wrong and we couldn't send your message.";
}
?>