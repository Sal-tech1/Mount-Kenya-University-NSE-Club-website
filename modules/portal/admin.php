<?php
session_start();
require_once __DIR__ . '/../../includes/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ADMIN') {
    header("Location: dashboard.php");
    exit;
}

$message = '';

// Handle Admin Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // User Management Actions
    if (isset($_POST['user_id'])) {
        $target_user_id = (int)$_POST['user_id'];
        
        if ($action === 'approve_member') {
            $stmt = $pdo->prepare("UPDATE users SET user_role = 'MEMBER' WHERE user_id = :id AND user_role = 'USER'");
            if ($stmt->execute(['id' => $target_user_id])) {
                $message = "<div class='alert alert-success'>User upgraded to MEMBER.</div>";
            }
        } elseif ($action === 'make_admin') {
            $stmt = $pdo->prepare("UPDATE users SET user_role = 'ADMIN' WHERE user_id = :id");
            if ($stmt->execute(['id' => $target_user_id])) {
                $message = "<div class='alert alert-success'>User promoted to ADMIN.</div>";
            }
        } elseif ($action === 'reset_cash') {
            $stmt = $pdo->prepare("UPDATE users SET virtual_cash = 100000.00 WHERE user_id = :id");
            if ($stmt->execute(['id' => $target_user_id])) {
                $message = "<div class='alert alert-success'>Virtual cash reset to KES 100,000.</div>";
            }
        }
    }
    
    // Resource Upload Action
    if ($action === 'upload_resource') {
        $title = trim($_POST['title']);
        $category = $_POST['category'];
        
        if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['resource_file'];
            $uploadDir = __DIR__ . '/../../assets/docs/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $safeFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', basename($file['name']));
            $targetPath = $uploadDir . $safeFileName;
            $dbPath = '/assets/docs/' . $safeFileName;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $stmt = $pdo->prepare("INSERT INTO resources (title, category, file_path) VALUES (:title, :category, :file_path)");
                if ($stmt->execute([':title' => $title, ':category' => $category, ':file_path' => $dbPath])) {
                    $message = "<div class='alert alert-success'>Resource uploaded successfully!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Database error during upload.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Failed to move uploaded file. Check directory permissions.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Please select a valid file to upload.</div>";
        }
    }

    // Add Lesson Action
    if ($action === 'add_lesson') {
        $tier = $_POST['tier'];
        $title = trim($_POST['title']);
        $summary = trim($_POST['summary']);
        
        $stmt = $pdo->prepare("INSERT INTO lessons (tier, title, summary) VALUES (:tier, :title, :summary)");
        if ($stmt->execute([':tier' => $tier, ':title' => $title, ':summary' => $summary])) {
            $message = "<div class='alert alert-success'>Lesson added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to add lesson.</div>";
        }
    }

    // Add Quiz Question Action
    if ($action === 'add_quiz') {
        $level = $_POST['level'];
        $question = trim($_POST['question_text']);
        $optA = trim($_POST['option_a']);
        $optB = trim($_POST['option_b']);
        $optC = trim($_POST['option_c']);
        $optD = trim($_POST['option_d']);
        $correct = $_POST['correct_option'];

        $stmt = $pdo->prepare("INSERT INTO quizzes (level, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (:lvl, :q, :a, :b, :c, :d, :corr)");
        if ($stmt->execute([
            ':lvl' => $level, ':q' => $question,
            ':a' => $optA, ':b' => $optB, ':c' => $optC, ':d' => $optD,
            ':corr' => $correct
        ])) {
            $message = "<div class='alert alert-success'>Quiz question added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to add quiz question.</div>";
        }
    }
}

// Fetch Data Arrays
$pendingStmt = $pdo->query("SELECT user_id, full_name, email, created_at FROM users WHERE user_role = 'USER' ORDER BY created_at DESC");
$pending_users = $pendingStmt->fetchAll(PDO::FETCH_ASSOC);

$allUsersStmt = $pdo->query("SELECT user_id, full_name, email, user_role, virtual_cash FROM users ORDER BY full_name ASC");
$all_users = $allUsersStmt->fetchAll(PDO::FETCH_ASSOC);

$leaderboardStmt = $pdo->query("SELECT full_name, learning_tier, virtual_cash FROM users ORDER BY virtual_cash DESC LIMIT 10");
$leaderboard = $leaderboardStmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../../includes/header.php';
?>

<main class="container py-5" style="min-height: 70vh;">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="color: var(--mku-royal-blue);">Admin Dashboard</h2>
            <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Portal</a>
        </div>
        <p class="text-muted mt-2">Manage users, track portfolios, and control content.</p>
    </div>

    <?= $message ?>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">Pending</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">Users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="leaderboard-tab" data-bs-toggle="tab" data-bs-target="#leaderboard" type="button" role="tab">Leaderboard</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button" role="tab">Uploads</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculum" type="button" role="tab">Curriculum</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="adminTabsContent">
        
        <!-- Pending Approvals Tab -->
        <div class="tab-pane fade show active section-card nse-card p-4" id="pending" role="tabpanel">
            <h4 class="mb-4">Pending Approvals</h4>
            <?php if (empty($pending_users)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">All caught up!</h5>
                    <p class="text-muted">There are no users waiting for membership approval.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($pending_users as $user): ?>
                        <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
                            <div class="mb-2 mb-md-0">
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($user['full_name']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($user['email']) ?> &middot; Joined <?= date('d M Y', strtotime($user['created_at'])) ?></small>
                            </div>
                            <form method="POST" action="admin.php">
                                <input type="hidden" name="action" value="approve_member">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-accent w-100">
                                    <i class="bi bi-person-check-fill"></i> Approve
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Management Tab -->
        <div class="tab-pane fade section-card nse-card p-4" id="users" role="tabpanel">
            <h4 class="mb-4">Global User Directory</h4>
            <div class="list-group">
                <?php foreach ($all_users as $u): ?>
                    <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
                        <div class="mb-3 mb-md-0">
                            <h6 class="mb-1 fw-bold">
                                <?= htmlspecialchars($u['full_name']) ?>
                                <span class="badge bg-secondary ms-2"><?= htmlspecialchars($u['user_role']) ?></span>
                            </h6>
                            <div class="text-muted small"><?= htmlspecialchars($u['email']) ?></div>
                            <div class="text-success small fw-bold mt-1">Cash: KES <?= number_format($u['virtual_cash'], 2) ?></div>
                        </div>
                        <form method="POST" action="admin.php" class="d-flex gap-2">
                            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                            <select name="action" class="form-select form-select-sm" style="min-width: 140px;">
                                <option value="">-- Select Action --</option>
                                <option value="reset_cash">Reset Cash</option>
                                <?php if ($u['user_role'] !== 'ADMIN'): ?>
                                    <option value="make_admin">Make Admin</option>
                                <?php endif; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Execute</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Leaderboard Tab -->
        <div class="tab-pane fade section-card nse-card p-4" id="leaderboard" role="tabpanel">
            <h4 class="mb-4">Top Simulated Portfolios</h4>
            <div class="list-group">
                <?php $rank = 1; foreach ($leaderboard as $l): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="fs-5 fw-bold text-muted" style="min-width: 30px;">#<?= $rank++ ?></span>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($l['full_name']) ?></h6>
                                <small class="text-warning fw-bold"><i class="bi bi-award"></i> <?= htmlspecialchars($l['learning_tier']) ?></small>
                            </div>
                        </div>
                        <span class="fs-5 fw-bold text-success">KES <?= number_format($l['virtual_cash'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Resource Upload Tab -->
        <div class="tab-pane fade section-card nse-card p-4" id="resources" role="tabpanel">
            <h4 class="mb-4">Upload Digital Resource</h4>
            <form method="POST" action="admin.php" enctype="multipart/form-data" class="bg-light p-4 rounded border">
                <input type="hidden" name="action" value="upload_resource">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Document Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Safaricom FY Financials">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Resource Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <option value="WEBINAR">Webinar Recording</option>
                        <option value="CONSTITUTION">Club Constitution</option>
                        <option value="FINANCIALS">Financial Statements</option>
                        <option value="MINUTES">Meeting Minutes</option>
                        <option value="BOOK">Educational Book</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Select File (PDF, DOCX, etc.)</label>
                    <input type="file" name="resource_file" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-success w-100 fw-bold" style="background-color: var(--primary-green); border: none;">
                    <i class="bi bi-cloud-upload me-2"></i> Upload to Server
                </button>
            </form>
        </div>

        <!-- Curriculum Management Tab -->
        <div class="tab-pane fade section-card nse-card p-4" id="curriculum" role="tabpanel">
            <h4 class="mb-4">Learning Hub Content</h4>
            
            <div class="row g-4">
                <!-- Add Lesson Form -->
                <div class="col-lg-6">
                    <div class="bg-light p-4 rounded border h-100">
                        <h5 class="mb-3" style="color: var(--mku-royal-blue);">Add New Lesson</h5>
                        <form method="POST" action="admin.php">
                            <input type="hidden" name="action" value="add_lesson">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Learning Tier</label>
                                <select name="tier" class="form-select" required>
                                    <option value="BEGINNER">Beginner</option>
                                    <option value="INTERMEDIATE">Intermediate</option>
                                    <option value="ADVANCED">Advanced</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lesson Title</label>
                                <input type="text" name="title" class="form-control" required placeholder="e.g. Introduction to Equities">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Lesson Summary</label>
                                <textarea name="summary" class="form-control" rows="3" required placeholder="A brief description of the lesson content..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-accent w-100 fw-bold">Publish Lesson</button>
                        </form>
                    </div>
                </div>

                <!-- Add Quiz Question Form -->
                <div class="col-lg-6">
                    <div class="bg-light p-4 rounded border h-100">
                        <h5 class="mb-3" style="color: var(--mku-royal-blue);">Add Quiz Question</h5>
                        <form method="POST" action="admin.php">
                            <input type="hidden" name="action" value="add_quiz">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Learning Tier</label>
                                <select name="level" class="form-select" required>
                                    <option value="BEGINNER">Beginner</option>
                                    <option value="INTERMEDIATE">Intermediate</option>
                                    <option value="ADVANCED">Advanced</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Question</label>
                                <textarea name="question_text" class="form-control" rows="2" required placeholder="What is a dividend?"></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <input type="text" name="option_a" class="form-control form-control-sm" required placeholder="Option A">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="option_b" class="form-control form-control-sm" required placeholder="Option B">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="option_c" class="form-control form-control-sm" required placeholder="Option C">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="option_d" class="form-control form-control-sm" required placeholder="Option D">
                                    </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Correct Answer</label>
                                <select name="correct_option" class="form-select" required>
                                    <option value="A">Option A</option>
                                    <option value="B">Option B</option>
                                    <option value="C">Option C</option>
                                    <option value="D">Option D</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-secondary w-100 fw-bold">Save Question</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>