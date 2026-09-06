<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Connect to database
require_once __DIR__ . '/../../includes/db.php';

$error = '';
$success = '';

// 2. Redirect if already logged in (MUST happen before HTML output)
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// 3. Process the registration form (MUST happen before HTML output)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($fullName) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        try {
            $checkStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
            $checkStmt->execute([':email' => $email]);
            
            if ($checkStmt->fetch()) {
                $error = "An account with this email already exists.";
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $insertStmt = $pdo->prepare("
                    INSERT INTO users (full_name, email, password_hash, user_role) 
                    VALUES (:full_name, :email, :password_hash, 'USER')
                ");
                $insertStmt->execute([
                    ':full_name'     => $fullName,
                    ':email'         => $email,
                    ':password_hash' => $passwordHash
                ]);

                $success = "Registration successful! You can now log in.";
            }
        } catch (PDOException $e) {
            error_log("Registration Error: " . $e->getMessage());
            $error = "An unexpected error occurred. Please try again later.";
        }
    }
}

// 4. NOW load the UI Header (HTML output begins here)
$page_title = "Create a Free Account | NSE MKU Club";
$meta_description = "Sign up for a free student account to access the NSE MKU Club Virtual Tracker and Learning Hub.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="max-width: 500px; margin-top: 40px; min-height: 60vh;">
    <div class="section-card">
        <h3>Create a Free Account</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">
            Sign up to use the Virtual Tracker. Official club membership (KSh 200/year) can be activated inside your dashboard.
        </p>

        <?php if (!empty($error)): ?>
            <div style="background: #FFEBEB; color: #D8000C; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="background: #EBFEEB; color: #2B7A2B; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="full_name"><strong>Full Name</strong></label>
            <input type="text" id="full_name" name="full_name" required placeholder="e.g. John Doe" class="form-control mb-3">

            <label for="email"><strong>Email Address</strong></label>
            <input type="email" id="email" name="email" required placeholder="e.g. member@student.mku.ac.ke" class="form-control mb-3">

            <label for="password"><strong>Password</strong></label>
            <input type="password" id="password" name="password" required placeholder="Minimum 6 characters" class="form-control mb-3">

            <label for="confirm_password"><strong>Confirm Password</strong></label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter password" class="form-control mb-3">

            <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 10px;">Register Account</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="login.php" style="color: var(--primary-green); font-weight: bold;">Log In Here</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>