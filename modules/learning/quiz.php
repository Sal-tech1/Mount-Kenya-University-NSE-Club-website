<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../portal/login.php"); exit; }

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/db.php';

$level = isset($_GET['level']) ? strtoupper(trim($_GET['level'])) : 'BEGINNER';
$allowedLevels = ['BEGINNER', 'INTERMEDIATE', 'ADVANCED', 'GRADUATE'];
if (!in_array($level, $allowedLevels)) $level = 'BEGINNER';

$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE level = :level ORDER BY quiz_id ASC");
$stmt->execute([':level' => $level]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="quiz-page" style="background-color: #f8f9fa; min-height: 80vh;">
    <header class="learning-banner" style="background-color: #002A54; padding: 40px 0; border-bottom: 4px solid var(--primary-green);">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <!-- Enforced span for white text -->
                <h1 style="margin-bottom: 5px; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                    <span style="color: #FFFFFF !important; font-weight: 800; font-size: 2.2rem; display: inline-block;">
                        <?php echo htmlspecialchars(ucfirst(strtolower($level))); ?> Assessment
                    </span>
                </h1>
                <p style="color: #E0E0E0 !important; font-size: 1.1rem; margin-bottom: 0;">Complete this assessment to unlock your next rank.</p>
            </div>
            <div><a href="index.php" class="btn btn-outline-light btn-sm fw-bold">Back to Hub</a></div>
        </div>
    </header>

    <div class="container py-5">
        <?php if (empty($questions)): ?>
            <div class="alert alert-warning">No questions found for this level.</div>
        <?php else: ?>
            <form action="grade.php" method="POST" class="bg-white p-4 p-md-5 rounded shadow-sm border-0">
                <input type="hidden" name="level" value="<?php echo htmlspecialchars($level); ?>">
                
                <?php foreach ($questions as $index => $q): 
                    // Set up and shuffle options
                    $options = ['A' => $q['option_a'], 'B' => $q['option_b'], 'C' => $q['option_c'], 'D' => $q['option_d']];
                    $keys = array_keys($options);
                    shuffle($keys);
                ?>
                    <div class="mb-5">
                        <h4 class="fw-bold mb-4" style="color: var(--mku-royal-blue); line-height: 1.5;">
                            <?php echo ($index + 1) . '. ' . htmlspecialchars($q['question_text']); ?>
                        </h4>
                        
                        <div class="ps-2 ps-md-4">
                            <?php foreach ($keys as $key): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="answers[<?php echo $q['quiz_id']; ?>]" id="q<?php echo $q['quiz_id']; ?>_<?php echo $key; ?>" value="<?php echo $key; ?>" required>
                                    <label class="form-check-label" for="q<?php echo $q['quiz_id']; ?>_<?php echo $key; ?>" style="cursor: pointer;">
                                        <?php echo htmlspecialchars($options[$key]); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if ($index < count($questions) - 1) echo '<hr class="text-muted opacity-25 my-4">'; ?>
                <?php endforeach; ?>

                <div class="text-end mt-5 pt-3 border-top">
                    <button type="submit" class="btn btn-success fw-bold px-5 py-2" style="background-color: var(--primary-green);">
                        Submit Assessment
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>