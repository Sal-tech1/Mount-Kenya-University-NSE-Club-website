<?php
require_once __DIR__ . '/../../includes/header.php';

$content = require __DIR__ . '/content.php';
$quizzes = $content['quizzes'];
$tiers   = $content['tiers'];

$level = strtolower(trim($_GET['level'] ?? ''));

if (!isset($quizzes[$level])) {
    http_response_code(404);
    ?>
    <main class="learning">
        <div class="container learning-body">
            <div class="quiz-empty">
                <h1>Assessment not found</h1>
                <p>Return to the learning centre and select a module assessment.</p>
                <a href="index.php" class="btn btn-secondary">Learning centre</a>
            </div>
        </div>
    </main>
    <?php
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}

$quiz       = $quizzes[$level];
$questions  = $quiz['questions'];
$passMark   = (int) ($quiz['pass_mark'] ?? 70);
$tierLabel  = $tiers[$level]['label'] ?? ucfirst($level);
$results    = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score  = 0;
    $total  = count($questions);
    $review = [];

    foreach ($questions as $index => $question) {
        $selected  = $_POST['q' . $index] ?? '';
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

    $results = [
        'score'      => $score,
        'total'      => $total,
        'percentage' => $percentage,
        'passed'     => $percentage >= $passMark,
        'review'     => $review,
    ];
}
?>

<main class="learning learning--quiz">

    <section class="quiz-topbar">
        <div class="container quiz-topbar__inner">
            <a href="index.php" class="quiz-topbar__back">Learning centre</a>
            <span class="quiz-topbar__meta"><?php echo htmlspecialchars($tierLabel); ?> module</span>
        </div>
    </section>

    <div class="container learning-body learning-body--narrow">

        <header class="quiz-header">
            <h1 class="quiz-header__title"><?php echo htmlspecialchars($quiz['title']); ?></h1>
            <?php if (!empty($quiz['description'])): ?>
                <p class="quiz-header__desc"><?php echo htmlspecialchars($quiz['description']); ?></p>
            <?php endif; ?>
            <?php if ($results === null): ?>
                <p class="quiz-header__count"><?php echo count($questions); ?> questions · <?php echo $passMark; ?>% to pass</p>
            <?php endif; ?>
        </header>

        <?php if ($results !== null): ?>

            <section class="quiz-score-panel <?php echo $results['passed'] ? 'quiz-score-panel--pass' : 'quiz-score-panel--fail'; ?>">
                <div class="quiz-score-ring" style="--score: <?php echo (int) $results['percentage']; ?>">
                    <span class="quiz-score-ring__value"><?php echo (int) $results['percentage']; ?>%</span>
                </div>
                <div class="quiz-score-panel__detail">
                    <h2><?php echo $results['passed'] ? 'Passed' : 'Not yet'; ?></h2>
                    <p><?php echo (int) $results['score']; ?> of <?php echo (int) $results['total']; ?> correct</p>
                </div>
            </section>

            <section class="quiz-review">
                <h2 class="quiz-review__heading">Answer review</h2>
                <?php foreach ($results['review'] as $i => $item): ?>
                    <article class="quiz-review-row <?php echo $item['is_correct'] ? 'quiz-review-row--ok' : 'quiz-review-row--miss'; ?>">
                        <div class="quiz-review-row__marker" aria-hidden="true"></div>
                        <div class="quiz-review-row__content">
                            <p class="quiz-review-row__q">
                                <span class="quiz-review-row__num"><?php echo $i + 1; ?></span>
                                <?php echo htmlspecialchars($item['question'] ?: '—'); ?>
                            </p>
                            <p class="quiz-review-row__a">
                                Your answer: <?php echo htmlspecialchars($item['selected'] ?: 'Skipped'); ?>
                                <?php if (!$item['is_correct']): ?>
                                    · Correct: <?php echo htmlspecialchars($item['correct']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

            <div class="quiz-actions">
                <a href="quiz.php?level=<?php echo urlencode($level); ?>" class="btn">Retry</a>
                <a href="index.php" class="btn btn-secondary">Back to modules</a>
            </div>

        <?php else: ?>

            <form method="POST" action="quiz.php?level=<?php echo urlencode($level); ?>" class="quiz-form">

                <?php foreach ($questions as $index => $question): ?>
                    <fieldset class="quiz-block">
                        <legend class="quiz-block__legend">
                            <span class="quiz-block__num"><?php echo $index + 1; ?></span>
                            <?php echo htmlspecialchars($question['text'] ?: 'Question ' . ($index + 1)); ?>
                        </legend>

                        <div class="quiz-block__options">
                            <?php foreach ($question['options'] as $key => $label): ?>
                                <?php if (trim($label) === '') continue; ?>
                                <label class="quiz-choice">
                                    <input type="radio" name="q<?php echo $index; ?>" value="<?php echo htmlspecialchars($key); ?>" required>
                                    <span class="quiz-choice__box" aria-hidden="true"></span>
                                    <span class="quiz-choice__text">
                                        <span class="quiz-choice__key"><?php echo htmlspecialchars($key); ?></span>
                                        <?php echo htmlspecialchars($label); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>

                <div class="quiz-actions">
                    <button type="submit" class="btn">Submit assessment</button>
                </div>

            </form>

        <?php endif; ?>

    </div>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
