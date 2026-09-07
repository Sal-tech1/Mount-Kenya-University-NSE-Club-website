<?php
// includes/email_helper.php

// Bring in the config file to access the secure API key
require_once __DIR__ . '/../config.php';

function sendBrevoEmail($toEmail, $toName, $subject, $htmlContent) {
    global $brevoApiKey; 
    
    $url = 'https://api.brevo.com/v3/smtp/email';

    $data = [
        'sender' => ['name' => 'MKU NSE Club', 'email' => 'mkunseclub@gmail.com'],
        'to' => [['email' => $toEmail, 'name' => $toName]],
        'subject' => $subject,
        'htmlContent' => $htmlContent
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $brevoApiKey,
        'content-type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
?>