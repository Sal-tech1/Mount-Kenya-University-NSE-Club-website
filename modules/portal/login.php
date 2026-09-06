<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Connect to database
require_once __DIR__ . '/../../includes/db.php';

$error = '';

// 2. Redirect if already logged in (MUST happen before HTML output)
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// 3. Process the login form (MUST happen before HTML output)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT user_id, full_name, password_hash, user_role FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id']   = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['user_role'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error = "An unexpected error occurred. Please try again.";
        }
    }
}

// 4. NOW load the UI Header (HTML output begins here)
$page_title = "Student Login | NSE MKU Club";
$meta_description = "Log in to access your free practice portfolio.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="max-width: 450px; margin-top: 40px; min-height: 60vh;">
    <div class="section-card">
        <h3>Student Account Login</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">Access your practice portfolio and free club resources.</p>

        <?php if (!empty($error)): ?>
            <div style="background: #FFEBEB; color: #D8000C; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email"><strong>Email Address</strong></label>
            <input type="email" id="email" name="email" required placeholder="member@student.mku.ac.ke" class="form-control mb-3">

            <label for="password"><strong>Password</strong></label>
            <input type="password" id="password" name="password" required placeholder="Enter your password" class="form-control mb-3">

            <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 10px;">Log In</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Need an account? <a href="register.php" style="color: var(--primary-green); font-weight: bold;">Register Here</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>