<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';

$error = '';
$success = '';
$token = $_GET['token'] ?? '';
$validToken = false;

// 1. Verify the token exists and is valid
if (empty($token)) {
    $error = "No reset token provided. Please click the link in your email.";
} else {
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE reset_token = :token AND reset_expires > NOW() LIMIT 1");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch();

        if ($user) {
            $validToken = true;
        } else {
            $error = "This password reset link is invalid or has expired. Please request a new one.";
        }
    } catch (PDOException $e) {
        $error = "Database error verifying token.";
    }
}

// 2. Process the new password submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm)) {
        $error = "Please fill out all fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Update the password and wipe the token data so it cannot be reused
            $updateStmt = $pdo->prepare("UPDATE users SET password_hash = :hash, reset_token = NULL, reset_expires = NULL WHERE user_id = :uid");
            $updateStmt->execute([
                ':hash' => $hash,
                ':uid'  => $user['user_id']
            ]);
            
            $success = "Your password has been successfully reset. You can now log in.";
            $validToken = false; // Hide the form after success
        } catch (PDOException $e) {
            error_log("Password Update Error: " . $e->getMessage());
            $error = "An unexpected error occurred. Please try again.";
        }
    }
}

$page_title = "Set New Password | NSE MKU Club";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="max-width: 450px; margin-top: 40px; min-height: 60vh;">
    <div class="section-card">
        <h3>Create New Password</h3>
        
        <?php if (!empty($error)): ?>
            <div style="background: #FFEBEB; color: #D8000C; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="background: #EBFEEB; color: #2B7A2B; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <a href="login.php" class="btn btn-accent" style="width: 100%;">Go to Login</a>
        <?php endif; ?>

        <?php if ($validToken): ?>
            <p style="color: var(--text-muted); margin-bottom: 20px;">Please enter your new password below.</p>
            
            <form method="POST" action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>">
                <label for="password"><strong>New Password</strong></label>
                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" required placeholder="Minimum 6 characters" class="form-control">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <label for="confirm_password"><strong>Confirm New Password</strong></label>
                <div class="input-group mb-3">
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter password" class="form-control">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 10px;">Update Password</button>
            </form>
        <?php endif; ?>

    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        
        input.setAttribute('type', type);
        
        if (type === 'password') {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>