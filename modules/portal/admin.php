<?php
session_start();
// Adjust the path to your database connection file if necessary
require_once __DIR__ . '/../../includes/db.php'; 

// Strictly enforce ADMIN access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: dashboard.php");
    exit;
}

$message = '';

// Handle the upgrade action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve_member') {
    $target_user_id = (int)$_POST['user_id'];
    
    // Using PDO mapping to user_role and user_id
    $stmt = $pdo->prepare("UPDATE users SET user_role = 'MEMBER' WHERE user_id = :id AND user_role = 'USER'");
    if ($stmt->execute(['id' => $target_user_id])) {
        $message = "<div class='alert alert-success'>Success! User has been upgraded to MEMBER and can now access the Resource Centre.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: Failed to upgrade user.</div>";
    }
}

// Fetch all users currently awaiting promotion using correct column names
$stmt = $pdo->query("SELECT user_id, full_name, email, created_at FROM users WHERE user_role = 'USER' ORDER BY created_at DESC");
$pending_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Load UI headers
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="container py-5" style="min-height: 70vh;">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="color: var(--mku-royal-blue);">Admin Dashboard</h2>
            <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Portal</a>
        </div>
        <p class="text-muted mt-2">Manage pending membership approvals.</p>
    </div>

    <?= $message ?>

    <div class="section-card nse-card p-4">
        <h4 class="mb-4">Pending Approvals</h4>
        
        <?php if (empty($pending_users)): ?>
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3">All caught up!</h5>
                <p class="text-muted">There are no users waiting for membership approval at this time.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_users as $user): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($user['full_name']) ?></strong></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></td>
                                <td class="text-end">
                                    <form method="POST" action="admin.php" class="d-inline">
                                        <input type="hidden" name="action" value="approve_member">
                                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-accent">
                                            <i class="bi bi-person-check-fill"></i> Approve Member
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>