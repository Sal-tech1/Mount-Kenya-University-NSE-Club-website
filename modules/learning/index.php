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

<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-8 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1><?php echo htmlspecialchars($page['title']); ?></h1>
            <p><?php echo htmlspecialchars($page['subtitle']); ?></p>
            <div class="d-flex mt-4 gap-4 flex-wrap">
               <div class="d-flex align-items-center gap-2"><i class="bi bi-journal-richtext fs-4 text-primary"></i> <strong><?php echo count($tiers); ?></strong> Modules</div>
               <div class="d-flex align-items-center gap-2"><i class="bi bi-book fs-4 text-primary"></i> <strong><?php echo $totalLessons; ?></strong> Lessons</div>
               <div class="d-flex align-items-center gap-2"><i class="bi bi-ui-checks fs-4 text-primary"></i> <strong><?php echo count($tiers); ?></strong> Assessments</div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Hero Section -->

    <?php $trackIndex = 1; ?>
    <?php foreach ($tiers as $slug => $tier): ?>
    <section id="<?php echo htmlspecialchars($slug); ?>" class="section <?php echo $trackIndex % 2 == 0 ? 'light-background' : ''; ?>">
      
      <div class="container section-title" data-aos="fade-up">
        <h2>Module <?php echo str_pad((string) $trackIndex, 2, '0', STR_PAD_LEFT); ?></h2>
        <p><span><?php echo htmlspecialchars($tier['label']); ?></span> <span class="description-title"><?php echo htmlspecialchars($tier['subtitle']); ?></span></p>
        <?php if (!empty($tier['description'])): ?>
            <p class="mt-2 text-muted"><?php echo htmlspecialchars($tier['description']); ?></p>
        <?php endif; ?>
      </div>

      <div class="container">
        <div class="row gy-4">
            <?php foreach ($tier['lessons'] as $i => $lesson): ?>
            <?php
                $lessonNum   = $i + 1;
                $lessonTitle = trim($lesson['title'] ?? '');
                $lessonSummary = trim($lesson['summary'] ?? '');
                if ($lessonTitle === '') $lessonTitle = 'Lesson ' . $lessonNum;
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $lessonNum * 100; ?>">
                <div class="section-card event-card h-100 d-flex flex-column">
                    <div class="event-date-badge">
                        <span class="day"><?php echo $lessonNum; ?></span>
                    </div>
                    <h4><?php echo htmlspecialchars($lessonTitle); ?></h4>
                    <?php if ($lessonSummary !== ''): ?>
                    <p class="flex-grow-1"><?php echo htmlspecialchars($lessonSummary); ?></p>
                    <?php else: ?>
                    <p class="flex-grow-1">Learn the fundamental concepts of this lesson.</p>
                    <?php endif; ?>
                    <div class="mt-auto">
                        <a href="#" class="btn btn-secondary mt-3">Start Lesson</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 text-center" data-aos="fade-up" data-aos-delay="300">
            <a href="quiz.php?level=<?php echo urlencode($slug); ?>" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                Take <?php echo htmlspecialchars($tier['label']); ?> Assessment
            </a>
        </div>

      </div>
    </section>
    <?php $trackIndex++; ?>
    <?php endforeach; ?>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
