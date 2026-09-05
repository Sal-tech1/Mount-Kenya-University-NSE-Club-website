<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check: If not logged in, redirect to login page immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../includes/header.php';

// Retrieve session details
$fullName = htmlspecialchars($_SESSION['full_name']);
$userRole = $_SESSION['user_role'] ?? 'USER';
?>

<div class="container" style="margin-top: 30px; min-height: 70vh;">
    <!-- Welcome Banner -->
    <div class="section-card" style="border-left: 6px solid var(--primary-green); border-top: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div>
                <h2>Welcome Back, <?php echo $fullName; ?>!</h2>
                <p style="color: var(--text-muted); margin-top: 4px;">
                    Account Status: 
                    <strong style="color: var(--mku-royal-blue); background: #E6F0FA; padding: 2px 8px; border-radius: var(--radius-sm);">
                        <?php echo $userRole; ?>
                    </strong>
                </p>
            </div>
            <div style="margin-top: 10px; display: flex; gap: 10px; flex-wrap: wrap;">
                <?php if ($userRole === 'ADMIN'): ?>
                    <a href="admin.php" class="btn btn-accent" style="padding: 8px 16px;"><i class="bi bi-shield-lock"></i> Admin Approvals</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-secondary" style="padding: 8px 16px;">Log Out</a>
            </div>
        </div>
    </div>

    <!-- Membership Pending Notice for Basic Users -->
    <?php if ($userRole === 'USER'): ?>
        <div class="alert alert-info mt-3" style="background-color: #E6F0FA; border: 1px solid var(--border-color); border-left: 4px solid var(--mku-royal-blue); color: var(--dark-navy);">
            <strong><i class="bi bi-info-circle-fill" style="color: var(--mku-royal-blue);"></i> Membership Pending Review</strong><br>
            Your account is currently awaiting executive approval. Once an admin verifies your membership, you will unlock full access to the Club Resource Centre.
        </div>
    <?php endif; ?>

    <!-- Dashboard Quick Links -->
    <h3 style="margin-bottom: 16px; margin-top: 24px;">Your Club Access</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        <!-- Card 1: Portfolio Tracker (Available to everyone logged in) -->
        <div class="section-card">
            <h4 style="color: var(--mku-royal-blue);">Virtual Portfolio Tracker</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 10px 0;">
                Practice buying and selling NSE shares with virtual capital. Track your performance against the market.
            </p>
            <a href="../tracker/index.php" class="btn" style="display: inline-block; margin-top: 10px;">Launch Tracker</a>
        </div>

        <!-- Card 2: Learning Hub (Available to everyone logged in) -->
        <div class="section-card">
            <h4 style="color: var(--mku-royal-blue);">Learning Hub & Quizzes</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 10px 0;">
                Continue your investment courses and test your knowledge to earn digital completion badges.
            </p>
            <a href="../learning/index.php" class="btn" style="display: inline-block; margin-top: 10px;">View Courses</a>
        </div>

        <!-- Card 3: Resource Centre (Restricted based on role) -->
        <div class="section-card">
            <h4 style="color: var(--mku-royal-blue);">Club Resource Centre</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 10px 0;">
                Access exclusive webinar recordings, official meeting minutes, financial statements, and investment books.
            </p>
            <?php if ($userRole === 'MEMBER' || $userRole === 'ADMIN'): ?>
                <a href="../resources/index.php" class="btn btn-accent" style="display: inline-block; margin-top: 10px;">Access Library</a>
            <?php else: ?>
                <span style="display: inline-block; margin-top: 10px; font-size: 0.85rem; color: #D8000C; font-weight: bold;">
                    <i class="bi bi-lock-fill"></i> Official Club Members Only
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>