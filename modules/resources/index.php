<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enforce authentication and official member or admin status
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? 'USER', ['MEMBER', 'ADMIN'])) {
    header("Location: ../portal/login.php");
    exit;
}

$resources = [
    [
        'title'       => 'NSE MKU Club Constitution 2026',
        'category'    => 'constitution',
        'category_label' => 'Club Constitution',
        'description' => 'The official governing document outlining member guidelines, leadership roles, and general objectives.',
        'date'        => '2026-01-15',
        'type'        => 'pdf',
        'size'        => '850 KB',
    ],
    [
        'title'       => 'The Intelligent Investor (Study Edition)',
        'category'    => 'books',
        'category_label' => 'Investment Books',
        'description' => 'Benjamin Graham\'s value-investing classic, annotated for the club\'s beginner reading track.',
        'date'        => '2026-06-18',
        'type'        => 'pdf',
        'size'        => '4.2 MB',
    ],
    [
        'title'       => 'Introduction to the NSE Trading Floor',
        'category'    => 'webinars',
        'category_label' => 'Recorded Webinars',
        'description' => 'A recorded walkthrough of how equities are listed, matched, and settled on the Nairobi Securities Exchange.',
        'date'        => '2026-05-02',
        'type'        => 'video',
        'size'        => '312 MB',
    ],
    [
        'title'       => 'AGM Minutes - March 2026',
        'category'    => 'minutes',
        'category_label' => 'Meeting Minutes',
        'description' => 'Official record of resolutions, attendance, and committee reports from the club\'s Annual General Meeting.',
        'date'        => '2026-03-14',
        'type'        => 'doc',
        'size'        => '186 KB',
    ],
    [
        'title'       => 'Sector Rotation Strategy - Club Pitch Deck',
        'category'    => 'presentations',
        'category_label' => 'Club Presentations',
        'description' => 'Slides from the student portfolio team on rotating exposure across banking, telecom, and energy counters.',
        'date'        => '2026-04-27',
        'type'        => 'doc',
        'size'        => '5.8 MB',
    ],
    [
        'title'       => 'Safaricom PLC - FY2025 Financial Statements',
        'category'    => 'financials',
        'category_label' => 'Financial Statements',
        'description' => 'Audited annual financial statements including balance sheet, income statement, and cash flow notes.',
        'date'        => '2026-02-09',
        'type'        => 'pdf',
        'size'        => '2.1 MB',
    ],
    [
        'title'       => 'Kenya Banking Sector Outlook 2026',
        'category'    => 'research',
        'category_label' => 'Research Reports',
        'description' => 'Independent research note on interest margins, asset quality, and digital lending trends across listed banks.',
        'date'        => '2026-01-22',
        'type'        => 'pdf',
        'size'        => '1.4 MB',
    ],
    [
        'title'       => 'NSE Monthly Market Statistics - July 2026',
        'category'    => 'market-reports',
        'category_label' => 'Market Reports',
        'description' => 'Turnover, market capitalisation, and index performance summary published by the exchange for the month.',
        'date'        => '2026-08-01',
        'type'        => 'xls',
        'size'        => '740 KB',
    ],
    [
        'title'       => 'Reading Bond & T-Bill Yield Curves',
        'category'    => 'education',
        'category_label' => 'Educational PDFs',
        'description' => 'A short guide explaining how club members can read CBK yield curve data and what it signals for equities.',
        'date'        => '2025-12-11',
        'type'        => 'pdf',
        'size'        => '980 KB',
    ],
    [
        'title'       => 'Behavioural Finance for Student Investors',
        'category'    => 'books',
        'category_label' => 'Investment Books',
        'description' => 'A condensed reading pack on cognitive biases that affect trading decisions, curated for club onboarding.',
        'date'        => '2025-11-30',
        'type'        => 'doc',
        'size'        => '3.3 MB',
    ],
    [
        'title'       => 'Understanding ETFs Listed on the NSE',
        'category'    => 'webinars',
        'category_label' => 'Recorded Webinars',
        'description' => 'Guest session recording covering how exchange-traded funds are priced and traded locally.',
        'date'        => '2026-03-05',
        'type'        => 'video',
        'size'        => '198 MB',
    ],
    [
        'title'       => 'Q2 2026 Portfolio Committee Report',
        'category'    => 'financials',
        'category_label' => 'Financial Statements',
        'description' => 'Internal club report on the performance of the paper-trading portfolio for the second quarter.',
        'date'        => '2026-07-10',
        'type'        => 'xls',
        'size'        => '412 KB',
    ],
    [
        'title'       => 'Frontier & Emerging Markets Primer',
        'category'    => 'research',
        'category_label' => 'Research Reports',
        'description' => 'Overview of how frontier market classification affects foreign investor appetite for Kenyan equities.',
        'date'        => '2025-10-19',
        'type'        => 'pdf',
        'size'        => '1.9 MB',
    ],
];

$categories = [
    'all'            => 'All Resources',
    'constitution'   => 'Club Constitution',
    'books'          => 'Investment Books',
    'webinars'       => 'Recorded Webinars',
    'minutes'        => 'Meeting Minutes',
    'presentations'  => 'Club Presentations',
    'financials'     => 'Financial Statements',
    'research'       => 'Research Reports',
    'market-reports' => 'Market Reports',
    'education'      => 'Educational PDFs',
];

$categoryCounts = array_fill_keys(array_keys($categories), 0);
foreach ($resources as $r) {
    $categoryCounts['all']++;
    if (isset($categoryCounts[$r['category']])) {
        $categoryCounts[$r['category']]++;
    }
}

$typeMeta = [
    'pdf'   => ['icon' => 'bi-file-earmark-pdf',   'label' => 'PDF'],
    'video' => ['icon' => 'bi-camera-reels',        'label' => 'Video'],
    'doc'   => ['icon' => 'bi-file-earmark-word',   'label' => 'DOCX'],
    'xls'   => ['icon' => 'bi-file-earmark-excel',  'label' => 'XLSX'],
];

// Load the isolated CSS file for this specific page
$custom_css = ['resources.css'];
require_once __DIR__ . '/../../includes/header.php'; 
?>

<main class="container py-5">

  <div class="row g-3 align-items-center mb-4">
    <div class="col-lg-5">
      <div class="resource-search d-flex align-items-center px-3 py-2">
        <i class="bi bi-search me-2"></i>
        <input type="text" id="resourceSearch" class="form-control" placeholder="Search resources by title or keyword&hellip;">
      </div>
    </div>
    <div class="col-lg-7 text-lg-end">
      <span class="text-muted small">Showing <strong id="resultCount"><?php echo count($resources); ?> resources</strong></span>
    </div>
  </div>

  <div class="shelf-rail mb-5">
    <?php foreach ($categories as $key => $label): ?>
      <button type="button" class="shelf-tab <?php echo $key === 'all' ? 'active' : ''; ?>" data-category="<?php echo htmlspecialchars($key); ?>">
        <?php echo htmlspecialchars($label); ?><span class="count">(<?php echo $categoryCounts[$key]; ?>)</span>
      </button>
    <?php endforeach; ?>
  </div>

  <div class="row g-4">
    <?php foreach ($resources as $r):
      $meta = $typeMeta[$r['type']];
      $searchBlob = strtolower($r['title'] . ' ' . $r['description'] . ' ' . $r['category_label']);
    ?>
      <div class="col-md-6 col-xl-4 resource-card-col" data-category="<?php echo htmlspecialchars($r['category']); ?>" data-search="<?php echo htmlspecialchars($searchBlob); ?>">
        <div class="nse-card resource-card">
          <div class="spine type-<?php echo $r['type']; ?>"></div>
          <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-2">
              <div class="file-icon type-<?php echo $r['type']; ?>"><i class="bi <?php echo $meta['icon']; ?>"></i></div>
              <div>
                <div class="kicker"><?php echo htmlspecialchars($r['category_label']); ?></div>
                <div class="title"><?php echo htmlspecialchars($r['title']); ?></div>
              </div>
            </div>
            <p class="desc"><?php echo htmlspecialchars($r['description']); ?></p>
            <div class="meta">
              <span><i class="bi bi-calendar3"></i> <?php echo date('d M Y', strtotime($r['date'])); ?></span>
              <span><?php echo $meta['label']; ?> &middot; <?php echo $r['size']; ?></span>
            </div>
            <div class="actions">
              <button type="button" class="btn btn-nse-outline btn-preview" data-title="<?php echo htmlspecialchars($r['title']); ?>">
                <i class="bi bi-eye"></i> Preview
              </button>
              <a href="#" class="btn btn-nse-gold" onclick="return false;" title="Download will be enabled once files are hosted">
                <i class="bi bi-download"></i> Download
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="empty-state d-none" id="emptyState">
    <i class="bi bi-inboxes" style="font-size:2.5rem;"></i>
    <p class="mt-3 mb-0">No resources match your search. Try a different keyword or category.</p>
  </div>

</main>

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalLabel">Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="previewModalBody"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/resources.js"></script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>