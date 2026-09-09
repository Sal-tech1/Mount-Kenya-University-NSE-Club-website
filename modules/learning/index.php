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

// Fetch the user's current learning tier from the database
$stmt = $pdo->prepare("SELECT learning_tier FROM users WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$userTier = $stmt->fetchColumn() ?: 'BEGINNER';

// Define the hierarchy to compare levels mathematically
$tierLevels = [
    'BEGINNER'     => 1,
    'INTERMEDIATE' => 2,
    'ADVANCED'     => 3,
    'GRADUATE'     => 4
];

$currentLevel = $tierLevels[$userTier] ?? 1;

// Helper function to determine if a section should be unlocked
function canAccess($moduleTier, $currentLevel, $tierLevels) {
    return $currentLevel >= $tierLevels[$moduleTier];
}

// Fetch all lessons from the database and group them by tier
$lessonStmt = $pdo->query("SELECT * FROM lessons ORDER BY sort_order ASC, lesson_id ASC");
$allLessons = $lessonStmt->fetchAll(PDO::FETCH_ASSOC);

$lessonsByTier = [
    'BEGINNER' => [],
    'INTERMEDIATE' => [],
    'ADVANCED' => []
];

foreach ($allLessons as $lesson) {
    if (array_key_exists($lesson['tier'], $lessonsByTier)) {
        $lessonsByTier[$lesson['tier']][] = $lesson;
    }
}
?>

<main class="learning">
    <!-- Hub Banner -->
    <header class="learning-banner">
        <div class="container learning-banner__inner">
            <div>
                <h1 class="learning-banner__title text-white">Learning Hub</h1>
                <p class="learning-banner__subtitle text-white">Master the financial markets through our structured curriculum.</p>
                <div class="text-white mt-3" style="font-size: 0.95rem;">
                    <strong>Step 1:</strong> Study the materials in our <a href="../resources/index.php" class="text-warning fw-bold text-decoration-none">Resource Centre</a>.<br>
                    <strong>Step 2:</strong> Return here to pass your assessments and level up your rank.
                </div>
            </div>
            <dl class="learning-banner__stats">
                <div class="learning-banner__stat">
                    <dt>Current Rank</dt>
                    <dd><?php echo htmlspecialchars($userTier); ?></dd>
                </div>
            </dl>
        </div>
    </header>

    <div class="container learning-body learning-body--narrow">
        
        <!-- BEGINNER MODULE (Always Unlocked) -->
        <section class="track track--green">
            <header class="track__header">
                <div class="track__index">01</div>
                <div>
                    <h2 class="track__title">Beginner</h2>
                    <div class="track__subtitle">Market Fundamentals</div>
                    <p class="track__description">Learn the basics of equities, bonds, and how the Nairobi Securities Exchange operates.</p>
                </div>
            </header>
            
            <?php if (!empty($lessonsByTier['BEGINNER'])): ?>
            <div class="track__lessons">
                <?php foreach ($lessonsByTier['BEGINNER'] as $index => $lesson): ?>
                    <div class="lesson-row">
                        <div class="lesson-row__num"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></div>
                        <div>
                            <div class="lesson-row__title"><?php echo htmlspecialchars($lesson['title']); ?></div>
                            <div class="lesson-row__summary"><?php echo htmlspecialchars($lesson['summary']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <footer class="track__footer">
                <a href="quiz.php?level=beginner" class="track__quiz-link">
                    Take Assessment <span class="track__quiz-arrow"></span>
                </a>
            </footer>
        </section>

        <!-- INTERMEDIATE MODULE -->
        <?php $hasInt = canAccess('INTERMEDIATE', $currentLevel, $tierLevels); ?>
        <section class="track track--blue" style="<?php echo $hasInt ? '' : 'opacity: 0.6; filter: grayscale(100%); pointer-events: none;'; ?>">
            <header class="track__header">
                <div class="track__index">02</div>
                <div>
                    <h2 class="track__title">Intermediate <?php if(!$hasInt) echo '<i class="bi bi-lock-fill text-danger" style="font-size: 1.2rem; margin-left: 8px;"></i>'; ?></h2>
                    <div class="track__subtitle">Portfolio Management</div>
                    <p class="track__description">Dive into fundamental analysis, reading financial statements, and building a balanced portfolio.</p>
                </div>
            </header>
            
            <?php if (!empty($lessonsByTier['INTERMEDIATE'])): ?>
            <div class="track__lessons">
                <?php foreach ($lessonsByTier['INTERMEDIATE'] as $index => $lesson): ?>
                    <div class="lesson-row">
                        <div class="lesson-row__num"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></div>
                        <div>
                            <div class="lesson-row__title"><?php echo htmlspecialchars($lesson['title']); ?></div>
                            <div class="lesson-row__summary"><?php echo htmlspecialchars($lesson['summary']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <footer class="track__footer">
                <?php if($hasInt): ?>
                <a href="quiz.php?level=intermediate" class="track__quiz-link">
                    Take Assessment <span class="track__quiz-arrow"></span>
                </a>
                <?php else: ?>
                <span style="color: var(--text-muted); font-weight: 600;">Score 80% in Beginner to unlock</span>
                <?php endif; ?>
            </footer>
        </section>

        <!-- ADVANCED MODULE -->
        <?php $hasAdv = canAccess('ADVANCED', $currentLevel, $tierLevels); ?>
        <section class="track track--gold" style="<?php echo $hasAdv ? '' : 'opacity: 0.6; filter: grayscale(100%); pointer-events: none;'; ?>">
            <header class="track__header">
                <div class="track__index">03</div>
                <div>
                    <h2 class="track__title">Advanced <?php if(!$hasAdv) echo '<i class="bi bi-lock-fill text-danger" style="font-size: 1.2rem; margin-left: 8px;"></i>'; ?></h2>
                    <div class="track__subtitle">Technical Analysis & Valuation</div>
                    <p class="track__description">Master chart patterns, advanced valuation models, and algorithmic trading concepts.</p>
                </div>
            </header>
            
            <?php if (!empty($lessonsByTier['ADVANCED'])): ?>
            <div class="track__lessons">
                <?php foreach ($lessonsByTier['ADVANCED'] as $index => $lesson): ?>
                    <div class="lesson-row">
                        <div class="lesson-row__num"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></div>
                        <div>
                            <div class="lesson-row__title"><?php echo htmlspecialchars($lesson['title']); ?></div>
                            <div class="lesson-row__summary"><?php echo htmlspecialchars($lesson['summary']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <footer class="track__footer">
                <?php if($hasAdv): ?>
                <a href="quiz.php?level=advanced" class="track__quiz-link">
                    Take Assessment <span class="track__quiz-arrow"></span>
                </a>
                <?php else: ?>
                <span style="color: var(--text-muted); font-weight: 600;">Score 80% in Intermediate to unlock</span>
                <?php endif; ?>
            </footer>
        </section>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>