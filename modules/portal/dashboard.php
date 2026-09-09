<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check: If not logged in, redirect to login page immediately
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Define SEO variables for the dashboard
$page_title = "Student Dashboard | NSE MKU Club";
$meta_description = "Manage your practice trades, access learning resources, and upgrade to official club membership.";

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
                <h2 style="margin-bottom: 0;">Welcome Back, <?php echo $fullName; ?>!</h2>
                
                <?php if ($userRole === 'MEMBER' || $userRole === 'ADMIN'): ?>
                    <p style="color: var(--primary-green); margin-top: 5px; margin-bottom: 0; font-weight: 600; font-size: 0.9rem;">
                        <i class="bi bi-patch-check-fill"></i> Official Club Member
                    </p>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 10px; display: flex; gap: 10px; flex-wrap: wrap;">
                <!-- Admin button successfully removed from here -->
                <a href="logout.php" class="btn btn-secondary" style="padding: 8px 16px;">Log Out</a>
            </div>
        </div>
    </div>

    <!-- Upgrade to Official Member Notice for Basic Users -->
    <?php if ($userRole === 'USER'): ?>
        <div class="alert mt-4" style="background-color: #F8F9FA; border: 1px solid var(--primary-green); border-left: 5px solid var(--primary-green); color: #333;">
            <h5 style="color: var(--primary-green); margin-bottom: 10px;">
                <i class="bi bi-star-fill"></i> Become an Official Club Member
            </h5>
            <p style="margin-bottom: 10px; font-size: 0.95rem;">
                You currently have a free account. To access the exclusive <strong>Club Resource Centre</strong> and join our private <strong>Webinars</strong>, please upgrade to an official membership. A minimum annual contribution of <strong>KSh 200</strong> is required. This goes directly towards supporting club activities and is paid once annually.
            </p>
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; margin-top: 15px;">
                <a href="https://forms.office.com/r/vsFtA53Q2W" target="_blank" class="btn btn-accent" style="padding: 8px 20px; font-weight: bold;">
                    <i class="bi bi-credit-card"></i> Pay KSh 200 & Register
                </a>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    * After you submit the form, our executive team will verify your payment and upgrade your account.
                </span>
            </div>
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