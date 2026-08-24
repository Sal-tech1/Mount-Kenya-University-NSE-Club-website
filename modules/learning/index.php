<?php
require_once __DIR__ . '/../../includes/header.php';

$content = require __DIR__ . '/content.php';
$tiers   = $content['tiers'];
$page    = $content['page'];

$totalLessons = 0;
foreach ($tiers as $tier) {
    $totalLessons += count($tier['lessons']);
}
?>

<main class="learning">

    <section class="learning-banner">
        <div class="container learning-banner__inner">
            <div class="learning-banner__copy">
                <h1 class="learning-banner__title"><?php echo htmlspecialchars($page['title']); ?></h1>
                <p class="learning-banner__subtitle"><?php echo htmlspecialchars($page['subtitle']); ?></p>
            </div>
            <dl class="learning-banner__stats">
                <div class="learning-banner__stat">
                    <dt>Modules</dt>
                    <dd><?php echo count($tiers); ?></dd>
                </div>
                <div class="learning-banner__stat">
                    <dt>Lessons</dt>
                    <dd><?php echo $totalLessons; ?></dd>
                </div>
                <div class="learning-banner__stat">
                    <dt>Assessments</dt>
                    <dd><?php echo count($tiers); ?></dd>
                </div>
            </dl>
        </div>
    </section>

    <div class="container learning-body">

        <?php $trackIndex = 1; ?>
        <?php foreach ($tiers as $slug => $tier): ?>
            <section class="track <?php echo htmlspecialchars($tier['accent']); ?>" id="<?php echo htmlspecialchars($slug); ?>">

                <header class="track__header">
                    <span class="track__index"><?php echo str_pad((string) $trackIndex, 2, '0', STR_PAD_LEFT); ?></span>
                    <div class="track__intro">
                        <h2 class="track__title"><?php echo htmlspecialchars($tier['label']); ?></h2>
                        <p class="track__subtitle"><?php echo htmlspecialchars($tier['subtitle']); ?></p>
                        <?php if (!empty($tier['description'])): ?>
                            <p class="track__description"><?php echo htmlspecialchars($tier['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="track__lessons">
                    <?php foreach ($tier['lessons'] as $i => $lesson): ?>
                        <?php
                        $lessonNum   = $i + 1;
                        $lessonTitle = trim($lesson['title'] ?? '');
                        $lessonSummary = trim($lesson['summary'] ?? '');
                        ?>
                        <article class="lesson-row">
                            <span class="lesson-row__num"><?php echo $lessonNum; ?></span>
                            <div class="lesson-row__body">
                                <h3 class="lesson-row__title">
                                    <?php echo $lessonTitle !== '' ? htmlspecialchars($lessonTitle) : 'Lesson ' . $lessonNum; ?>
                                </h3>
                                <?php if ($lessonSummary !== ''): ?>
                                    <p class="lesson-row__summary"><?php echo htmlspecialchars($lessonSummary); ?></p>
                                <?php endif; ?>
                                <div class="lesson-row__content">
                                    <!-- Add lesson HTML or paragraphs here per tier, or extend content.php -->
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <footer class="track__footer">
                    <a href="quiz.php?level=<?php echo urlencode($slug); ?>" class="track__quiz-link">
                        <?php echo htmlspecialchars($tier['label']); ?> assessment
                        <span class="track__quiz-arrow" aria-hidden="true"></span>
                    </a>
                </footer>

            </section>
            <?php $trackIndex++; ?>
        <?php endforeach; ?>

    </div>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
