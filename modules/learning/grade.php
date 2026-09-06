<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/db.php';

$level = $_POST['level'] ?? 'BEGINNER';
$submittedAnswers = $_POST['answers'] ?? [];
$userId = $_SESSION['user_id'];

// Fetch correct answers
$stmt = $pdo->prepare("SELECT quiz_id, correct_option FROM quizzes WHERE level = :level");
$stmt->execute([':level' => $level]);
$correctAnswers = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$totalQuestions = count($correctAnswers);
if ($totalQuestions === 0) die("Error: No questions found.");

// Calculate score
$score = 0;
foreach ($correctAnswers as $quizId => $correctOption) {
    if (isset($submittedAnswers[$quizId]) && $submittedAnswers[$quizId] === $correctOption) {
        $score++;
    }
}

$percentage = ($totalQuestions > 0) ? round(($score / $totalQuestions) * 100) : 0;
$passed = $percentage >= 80;

// Promotion Logic
if ($passed) {
    $nextTierMap = ['BEGINNER' => 'INTERMEDIATE', 'INTERMEDIATE' => 'ADVANCED', 'ADVANCED' => 'GRADUATE'];
    
    $stmt = $pdo->prepare("SELECT learning_tier FROM users WHERE user_id = :uid");
    $stmt->execute([':uid' => $userId]);
    $currentTier = $stmt->fetchColumn() ?: 'BEGINNER';

    // Only promote if they are taking the quiz for their current level
    if ($currentTier === $level && isset($nextTierMap[$level])) {
        $newTier = $nextTierMap[$level];
        $update = $pdo->prepare("UPDATE users SET learning_tier = :tier WHERE user_id = :uid");
        $update->execute([':tier' => $newTier, ':uid' => $userId]);
    }
}
?>

<main style="background-color: #f8f9fa; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="container text-center py-5">
        <div class="bg-white p-5 rounded shadow-sm mx-auto" style="max-width: 600px;">
            <?php if ($passed): ?>
                <h1 class="text-success mb-3"><i class="bi bi-trophy-fill"></i> Congratulations!</h1>
                <p class="fs-4">You scored <strong><?php echo $percentage; ?>%</strong> (<?php echo $score; ?>/<?php echo $totalQuestions; ?>)</p>
                <p class="text-muted mb-4">You have successfully passed the <?php echo ucfirst(strtolower($level)); ?> assessment.</p>
            <?php else: ?>
                <h1 class="text-danger mb-3"><i class="bi bi-x-circle-fill"></i> Keep Trying!</h1>
                <p class="fs-4">You scored <strong><?php echo $percentage; ?>%</strong> (<?php echo $score; ?>/<?php echo $totalQuestions; ?>)</p>
                <p class="text-muted mb-4">You need at least 80% to pass. Review the materials and try again.</p>
            <?php endif; ?>
            
            <a href="index.php" class="btn btn-primary fw-bold px-4 py-2" style="background-color: var(--mku-royal-blue); border: none;">
                Return to Learning Hub
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>