<?php
// Centralized PDO Database Connection Wrapper

require_once __DIR__ . '/../config.php';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on SQL errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements for security
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    // Log error internally and display a clean message to prevent leaking DB details
    error_log("Database Connection Failure: " . $e->getMessage());
    die("<p style='color: red; font-family: sans-serif;'>Database connection error. Please contact the administrator.</p>");
}
?>