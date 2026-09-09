<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? 'USER', ['MEMBER', 'ADMIN'])) {
    header("Location: ../portal/login.php");
    exit;
}

$resources = [
    [
        'title'       => 'Beginner Track: Market Fundamentals',
        'category'    => 'cheat-sheets',
        'category_label' => 'Cheat Sheets',
        'description' => 'A short primer covering stock basics, dividends, CDS accounts, and NSE functions to prep for the Beginner assessment.',
        'date'        => '2026-09-01',
        'type'        => 'pdf',
        'size'        => '1.2 MB',
        'file_url'    => '/assets/docs/beginner_fundamentals.pdf'
    ],
    [
        'title'       => 'Intermediate Track: Portfolio Management',
        'category'    => 'cheat-sheets',
        'category_label' => 'Cheat Sheets',
        'description' => 'Key concepts including P/E ratios, bull vs bear markets, portfolio diversification, and reading income statements.',
        'date'        => '2026-09-02',
        'type'        => 'pdf',
        'size'        => '1.5 MB',
        'file_url'    => '/assets/docs/intermediate_portfolio.pdf'
    ],
    [
        'title'       => 'Advanced Track: Technical Analysis',
        'category'    => 'cheat-sheets',
        'category_label' => 'Cheat Sheets',
        'description' => 'Quick review of RSI, DCF objectives, market capitalization, and systematic risk for the final assessment.',
        'date'        => '2026-09-03',
        'type'        => 'pdf',
        'size'        => '1.8 MB',
        'file_url'    => '/assets/docs/advanced_analysis.pdf'
    ],
    [
        'title'       => 'Safaricom PLC FY Financial Statements',
        'category'    => 'financials',
        'category_label' => 'Financial Statements',
        'description' => 'Audited annual financial statements including balance sheet, income statement, and cash flow notes.',
        'date'        => '2026-02-09',
        'type'        => 'pdf',
        'size'        => '2.1 MB',
        'file_url'    => '/assets/docs/safaricom_financials.pdf'
    ]
];

$categories = [
    'all'            => 'All Resources',
    'cheat-sheets'   => 'Cheat Sheets',
    'financials'     => 'Financial Statements'
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
    'video' => ['icon' => 'bi-camera-reels',       'label' => 'Video'],
    'doc'   => ['icon' => 'bi-file-earmark-word',  'label' => 'DOCX'],
    'xls'   => ['icon' => 'bi-file-earmark-excel', 'label' => 'XLSX'],
];

$custom_css = ['resources.css'];
require_once __DIR__ . '/../../includes/header.php'; 
?>

<main class="container py-5" style="min-height: 80vh;">

  <div class="alert border-0 shadow-sm mb-5 d-flex align-items-center" role="alert" style="background-color: #e7f1ff; color: #002A54; border-left: 5px solid var(--primary-green) !important;">
      <i class="bi bi-info-circle-fill fs-4 me-3 text-success"></i>
      <div>
          <strong>Notice:</strong> Additional reading materials, official documentation, and recorded walkthroughs are currently being curated and will be available soon. Use the core guides provided below to prep for your learning tier assessments!
      </div>
  </div>

  <div class="row g-3 align-items-center mb-4">
    <div class="col-lg-5">
      <div class="resource-search d-flex align-items-center px-3 py-2 bg-white rounded shadow-sm border">
        <i class="bi bi-search me-2 text-muted"></i>
        <input type="text" id="resourceSearch" class="form-control border-0 shadow-none bg-transparent" placeholder="Search resources by title or keyword...">
      </div>
    </div>
    <div class="col-lg-7 text-lg-end">
      <span class="text-muted small">Showing <strong id="resultCount"><?php echo count($resources); ?> resources</strong></span>
    </div>
  </div>

  <div class="shelf-rail mb-5 pb-2" style="overflow-x: auto; white-space: nowrap;">
    <?php foreach ($categories as $key => $label): ?>
      <button type="button" class="btn btn-outline-secondary rounded-pill me-2 mb-2 shelf-tab <?php echo $key === 'all' ? 'active bg-secondary text-white' : ''; ?>" data-category="<?php echo htmlspecialchars($key); ?>">
        <?php echo htmlspecialchars($label); ?> <span class="badge bg-light text-dark ms-1 rounded-pill"><?php echo $categoryCounts[$key]; ?></span>
      </button>
    <?php endforeach; ?>
  </div>

  <div class="row g-4" id="resourceGrid">
    <?php foreach ($resources as $r):
      $meta = $typeMeta[$r['type']];
      $searchBlob = strtolower($r['title'] . ' ' . $r['description'] . ' ' . $r['category_label']);
    ?>
      <div class="col-md-6 col-xl-4 resource-card-col" data-category="<?php echo htmlspecialchars($r['category']); ?>" data-search="<?php echo htmlspecialchars($searchBlob); ?>">
        <div class="card h-100 shadow-sm border-0 resource-card" style="border-top: 4px solid var(--mku-royal-blue);">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="text-danger fs-1"><i class="bi <?php echo $meta['icon']; ?>"></i></div>
              <div>
                <div class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;"><?php echo htmlspecialchars($r['category_label']); ?></div>
                <h5 class="fw-bold mt-1 mb-0" style="color: var(--mku-royal-blue);"><?php echo htmlspecialchars($r['title']); ?></h5>
              </div>
            </div>
            
            <p class="text-secondary small mb-4 flex-grow-1"><?php echo htmlspecialchars($r['description']); ?></p>
            
            <div class="d-flex justify-content-between align-items-center mb-4 text-muted small fw-semibold">
              <span><i class="bi bi-calendar3 me-1"></i> <?php echo date('d M Y', strtotime($r['date'])); ?></span>
              <span><?php echo $meta['label']; ?> &middot; <?php echo $r['size']; ?></span>
            </div>
            
            <div class="d-flex gap-2 mt-auto">
              <!-- Prepended $basePath to the file links -->
              <a href="<?php echo $basePath . htmlspecialchars($r['file_url']); ?>" target="_blank" class="btn btn-outline-secondary w-50 fw-bold">
                <i class="bi bi-eye"></i> Preview
              </a>
              <a href="<?php echo $basePath . htmlspecialchars($r['file_url']); ?>" download class="btn btn-success w-50 fw-bold" style="background-color: var(--primary-green); border: none;">
                <i class="bi bi-download"></i> Download
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  
  <div class="text-center py-5 d-none" id="emptyState">
    <i class="bi bi-inboxes text-muted" style="font-size: 4rem;"></i>
    <h4 class="mt-3 text-muted">No resources found</h4>
    <p class="mb-0 text-secondary">Try adjusting your search terms or category filters.</p>
  </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('resourceSearch');
    const tabs = document.querySelectorAll('.shelf-tab');
    const cards = document.querySelectorAll('.resource-card-col');
    const resultCount = document.getElementById('resultCount');
    const emptyState = document.getElementById('emptyState');

    function filterResources() {
        const query = searchInput.value.toLowerCase();
        const activeTab = document.querySelector('.shelf-tab.active').getAttribute('data-category');
        let visibleCount = 0;

        cards.forEach(card => {
            const matchesSearch = card.getAttribute('data-search').includes(query);
            const matchesCategory = (activeTab === 'all' || card.getAttribute('data-category') === activeTab);
            
            if (matchesSearch && matchesCategory) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });

        resultCount.textContent = visibleCount + (visibleCount === 1 ? ' resource' : ' resources');
        
        if (visibleCount === 0) {
            emptyState.classList.remove('d-none');
        } else {
            emptyState.classList.add('d-none');
        }
    }

    searchInput.addEventListener('input', filterResources);

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-secondary', 'text-white');
            });
            this.classList.add('active', 'bg-secondary', 'text-white');
            filterResources();
        });
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>