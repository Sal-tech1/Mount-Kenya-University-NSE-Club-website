<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../portal/login.php");
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/db.php';

$level = strtolower(trim($_GET['level'] ?? 'beginner'));
$dbLevel = strtoupper($level);
$passMark = 80;
$tierLabel = ucfirst($level);

// Fetch questions from MySQL instead of content.php
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE level = :level ORDER BY quiz_id ASC");
$stmt->execute([':level' => $dbLevel]);
$dbQuestions = $stmt->fetchAll();

if (empty($dbQuestions)) {
    http_response_code(404);
    ?>
    <main class="main">
        <section class="section light-background min-vh-100 d-flex align-items-center">
            <div class="container text-center">
                <h2>Assessment not found</h2>
                <p class="mb-4">Return to the learning centre and select a module assessment.</p>
                <a href="index.php" class="btn btn-primary">Learning centre</a>
            </div>
        </section>
    </main>
    <?php
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}

// Map database rows to the structure your UI expects
$questions = [];
foreach ($dbQuestions as $row) {
    $questions[] = [
        'id'      => $row['quiz_id'],
        'text'    => $row['question_text'],
        'options' => [
            'A' => $row['option_a'],
            'B' => $row['option_b'],
            'C' => $row['option_c'],
            'D' => $row['option_d']
        ],
        'correct' => $row['correct_option']
    ];
}

$results = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score  = 0;
    $total  = count($questions);
    $review = [];

    foreach ($questions as $index => $question) {
        $inputName = 'q' . $question['id'];
        $selected  = $_POST[$inputName] ?? '';
        $correct   = $question['correct'];
        $isCorrect = ($selected === $correct);

        if ($isCorrect) {
            $score++;
        }

        $review[] = [
            'question'   => $question['text'],
            'selected'   => $selected,
            'correct'    => $correct,
            'is_correct' => $isCorrect,
        ];
    }

    $percentage = $total > 0 ? round(($score / $total) * 100) : 0;

    // Database update logic to save progress
    if ($percentage >= $passMark) {
        $nextTier = 'BEGINNER';
        if ($dbLevel === 'BEGINNER') $nextTier = 'INTERMEDIATE';
        if ($dbLevel === 'INTERMEDIATE') $nextTier = 'ADVANCED';
        if ($dbLevel === 'ADVANCED') $nextTier = 'GRADUATE';
        
        $updateStmt = $pdo->prepare("UPDATE users SET learning_tier = :tier WHERE user_id = :uid AND learning_tier = :current");
        $updateStmt->execute([
            ':tier' => $nextTier,
            ':uid'  => $_SESSION['user_id'],
            ':current' => $dbLevel
        ]);
    }

    $results = [
        'score'      => $score,
        'total'      => $total,
        'percentage' => $percentage,
        'passed'     => $percentage >= $passMark,
        'review'     => $review,
    ];
}
?>

<main class="main">

    <section class="section light-background pt-5 pb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="index.php" class="text-decoration-none btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Modules</a>
                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6"><?php echo htmlspecialchars($tierLabel); ?> module</span>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h1 class="mb-3"><?php echo htmlspecialchars($tierLabel); ?> Market Quiz</h1>
                    <p class="text-muted fs-5">Test your investment knowledge against the club curriculum.</p>
                    
                    <?php if ($results === null): ?>
                        <div class="d-flex justify-content-center gap-4 mt-4">
                            <span class="fw-bold"><i class="bi bi-ui-checks text-primary"></i> <?php echo count($questions); ?> questions</span>
                            <span class="fw-bold"><i class="bi bi-bullseye text-primary"></i> <?php echo $passMark; ?>% to pass</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div class="container section">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">

        <?php if ($results !== null): ?>

            <div class="card border-0 shadow-sm mb-5 <?php echo $results['passed'] ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10'; ?>">
                <div class="card-body text-center p-5">
                    <h2 class="display-1 fw-bold <?php echo $results['passed'] ? 'text-success' : 'text-danger'; ?>"><?php echo (int) $results['percentage']; ?>%</h2>
                    <h3 class="mb-3 fw-bold <?php echo $results['passed'] ? 'text-success' : 'text-danger'; ?>"><?php echo $results['passed'] ? 'Passed!' : 'Try Again'; ?></h3>
                    <p class="mb-0 fs-5 text-dark"><?php echo (int) $results['score']; ?> out of <?php echo (int) $results['total']; ?> correct</p>
                </div>
            </div>

            <h3 class="mb-4">Answer Review</h3>
            <div class="list-group list-group-flush mb-5 shadow-sm rounded">
                <?php foreach ($results['review'] as $i => $item): ?>
                    <div class="list-group-item bg-white border-bottom py-4 px-4">
                        <h5 class="mb-4 lh-base">
                            <span class="badge bg-secondary me-2"><?php echo $i + 1; ?></span>
                            <?php echo htmlspecialchars($item['question'] ?: '-'); ?>
                        </h5>
                        
                        <div class="d-flex align-items-center mb-2 <?php echo $item['is_correct'] ? 'text-success' : 'text-danger'; ?>">
                            <i class="bi fs-5 <?php echo $item['is_correct'] ? 'bi-check-circle-fill' : 'bi-x-circle-fill'; ?> me-3"></i>
                            <div class="fs-6">
                                <strong>Your answer:</strong>&nbsp; <span class="text-dark"><?php echo htmlspecialchars($item['selected'] ?: 'Skipped'); ?></span>
                            </div>
                        </div>
                        
                        <?php if (!$item['is_correct']): ?>
                            <div class="d-flex align-items-center text-success mt-2">
                                <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                                <div class="fs-6">
                                    <strong>Correct answer:</strong>&nbsp; <span class="text-dark"><?php echo htmlspecialchars($item['correct']); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-5">
                <a href="quiz.php?level=<?php echo urlencode($level); ?>" class="btn btn-primary px-5 rounded-pill shadow-sm">Retry Assessment</a>
                <a href="index.php" class="btn btn-outline-secondary px-5 rounded-pill">Back to Modules</a>
            </div>

        <?php else: ?>

            <form method="POST" action="quiz.php?level=<?php echo urlencode($level); ?>">

                <?php foreach ($questions as $index => $question): ?>
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="card-title mb-4 lh-base">
                                <span class="badge bg-primary me-2"><?php echo $index + 1; ?></span>
                                <?php echo htmlspecialchars($question['text']); ?>
                            </h4>

                            <div class="d-flex flex-column gap-3 mt-4">
                            <?php 
                            $shuffledKeys = array_keys($question['options']);
                            shuffle($shuffledKeys);
                            foreach ($shuffledKeys as $key): 
                                $label = $question['options'][$key];
                                if (trim($label) === '') continue; 
                            ?>
                                <label class="list-group-item rounded border p-3 d-flex align-items-center bg-light" style="cursor: pointer; transition: all 0.2s ease;" onmouseover="this.classList.add('bg-white', 'shadow-sm')" onmouseout="this.classList.remove('bg-white', 'shadow-sm')">
                                    <input class="form-check-input me-3 mt-0 fs-5" type="radio" name="q<?php echo $question['id']; ?>" value="<?php echo htmlspecialchars($key); ?>" required>
                                    <div class="fs-6">
                                        <?php echo htmlspecialchars($label); ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="mt-5 text-center" style="margin-bottom: 50px;">
                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm fw-bold">Submit Assessment <i class="bi bi-arrow-right ms-2"></i></button>
                </div>

            </form>

        <?php endif; ?>

            </div>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>