<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/email_helper.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "danger";
    } else {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT user_id, full_name FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // We always show a success message to prevent email enumeration attacks
            $message = "If an account exists with that email, a password reset link has been sent.";
            $messageType = "success";

            if ($user) {
                // Generate a secure random token and an expiration time (1 hour from now)
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", time() + 3600);

                // Update the user record
                $updateStmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
                $updateStmt->execute([
                    ':token'   => $token,
                    ':expires' => $expires,
                    ':email'   => $email
                ]);

                // Construct the reset link
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $resetLink = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;

                // Send the email via Brevo
                $subject = "Password Reset Request - MKU NSE Club";
                $htmlContent = "
                    <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
                        <h2 style='color: #003366;'>Password Reset Request</h2>
                        <p>Hello " . htmlspecialchars($user['full_name']) . ",</p>
                        <p>We received a request to reset the password for your MKU NSE Club account. Click the button below to choose a new password.</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . $resetLink . "' style='background-color: #F2A900; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: bold;'>Reset Password</a>
                        </div>
                        <p>This link will expire in 1 hour.</p>
                        <p>If you did not make this request, you can safely ignore this email.</p>
                    </div>
                ";

                sendBrevoEmail($email, $user['full_name'], $subject, $htmlContent);
            }
        } catch (PDOException $e) {
            error_log("Password Reset Error: " . $e->getMessage());
            $message = "An error occurred while processing your request. Please try again later.";
            $messageType = "danger";
        }
    }
}

$page_title = "Forgot Password | NSE MKU Club";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="max-width: 450px; margin-top: 40px; min-height: 60vh;">
    <div class="section-card">
        <h3>Reset Password</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">Enter your email address and we will send you a link to reset your password.</p>

        <?php if (!empty($message)): ?>
            <div style="background: <?php echo $messageType === 'success' ? '#EBFEEB' : '#FFEBEB'; ?>; 
                        color: <?php echo $messageType === 'success' ? '#2B7A2B' : '#D8000C'; ?>; 
                        padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="forgot_password.php">
            <label for="email"><strong>Email Address</strong></label>
            <input type="email" id="email" name="email" required placeholder="member@student.mku.ac.ke" class="form-control mb-3">

            <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 10px;">Send Recovery Link</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Remembered your password? <a href="login.php" style="color: var(--primary-green); font-weight: bold;">Log In Here</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>