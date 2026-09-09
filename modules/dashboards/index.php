<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';

// ============================================================================
// AUTOMATED MARKET SHUFFLE (Traffic-Triggered Execution)
// ============================================================================
// Check the timestamp of the last market update
$lastUpdateStmt = $pdo->query("SELECT MAX(last_updated) FROM market_prices");
$lastUpdate = $lastUpdateStmt->fetchColumn();

// If it has been more than 24 hours (86400 seconds) since the last update, shuffle the market
if (!$lastUpdate || (time() - strtotime($lastUpdate)) > 86400) {
    $priceStmt = $pdo->query("SELECT ticker_symbol, current_price FROM market_prices");
    $stocks = $priceStmt->fetchAll(PDO::FETCH_ASSOC);

    $shuffleStmt = $pdo->prepare("
        UPDATE market_prices 
        SET previous_close = current_price, 
            current_price = :new_price,
            last_updated = CURRENT_TIMESTAMP
        WHERE ticker_symbol = :ticker
    ");

    foreach ($stocks as $stock) {
        $ticker = $stock['ticker_symbol'];
        $currentPrice = (float)$stock['current_price'];
        
        if ($currentPrice <= 0) $currentPrice = 20.00; 
        
        // Random shift between -3.5% and +3.8%
        $percentageChange = (rand(-35, 38) / 1000.0); 
        $newPrice = round($currentPrice * (1 + $percentageChange), 2);
        
        if ($newPrice < 1.00) $newPrice = 1.00;

        $shuffleStmt->execute([
            ':new_price' => $newPrice,
            ':ticker' => $ticker
        ]);
    }
}
// ============================================================================

// Fetch the newly updated (or current) live prices for the ticker feed
$feedStmt = $pdo->query("SELECT ticker_symbol, current_price, previous_close FROM market_prices");
$liveFeedData = $feedStmt->fetchAll(PDO::FETCH_ASSOC);

$tickerFeed = [];
foreach ($liveFeedData as $row) {
    $current = (float)$row['current_price'];
    $previous = (float)$row['previous_close'];
    $deltaValue = $current - $previous;
    
    $tickerFeed[] = [
        'ticker' => $row['ticker_symbol'],
        'price'  => number_format($current, 2),
        'delta'  => ($deltaValue > 0 ? '+' : '') . number_format($deltaValue, 2),
        'up'     => $deltaValue >= 0
    ];
}

// Ensure there is fallback data if the database table is completely empty
if (empty($tickerFeed)) {
    $tickerFeed = [
        ['ticker' => 'SCOM', 'price' => '29.85', 'delta' => '+0.00', 'up' => true],
        ['ticker' => 'EQTY', 'price' => '48.20', 'delta' => '+0.00', 'up' => true],
        ['ticker' => 'KCB',  'price' => '41.10', 'delta' => '+0.00', 'up' => true]
    ];
}

// Static fallback data for UI elements not yet connected to the database
$marketSummary = [
    ['label' => 'NSE All Share Index', 'value' => '128.46', 'delta' => '+0.62%', 'up' => true,  'icon' => 'bi-graph-up-arrow'],
    ['label' => 'NSE 20 Share Index',  'value' => '1,926.14', 'delta' => '+0.94%', 'up' => true, 'icon' => 'bi-bar-chart'],
    ['label' => 'Market Capitalization', 'value' => 'KES 2.14T', 'delta' => '+0.41%', 'up' => true, 'icon' => 'bi-bank'],
    ['label' => 'Daily Volume', 'value' => '9.8M shares', 'delta' => '-3.2%', 'up' => false, 'icon' => 'bi-arrow-left-right'],
    ['label' => 'Daily Turnover', 'value' => 'KES 312.7M', 'delta' => '+1.8%', 'up' => true, 'icon' => 'bi-cash-stack'],
];

$topGainers = [
    ['name' => 'Bamburi Cement PLC', 'ticker' => 'BAMB', 'pct' => '+8.42%'],
    ['name' => 'Kenya Airways PLC',  'ticker' => 'KQ',   'pct' => '+6.15%'],
    ['name' => 'BAT Kenya PLC',      'ticker' => 'BATK', 'pct' => '+4.73%'],
    ['name' => 'Diamond Trust Bank', 'ticker' => 'DTK',  'pct' => '+3.28%'],
    ['name' => 'Jubilee Holdings',   'ticker' => 'JUB',  'pct' => '+2.91%'],
];

$topLosers = [
    ['name' => 'Kenya Power & Lighting', 'ticker' => 'KPLC', 'pct' => '-5.64%'],
    ['name' => 'WPP Scangroup PLC',      'ticker' => 'WPP',  'pct' => '-4.02%'],
    ['name' => 'Sameer Africa PLC',      'ticker' => 'SMER', 'pct' => '-3.15%'],
    ['name' => 'Car & General (K) Ltd',  'ticker' => 'C&G',  'pct' => '-2.47%'],
    ['name' => 'Eveready East Africa',   'ticker' => 'EVRD', 'pct' => '-1.88%'],
];

$marketNews = [
    [
        'tag' => 'Market Update',
        'headline' => 'NSE 20 Share Index closes above 1,900 for the first time in eight months',
        'snippet' => 'Renewed foreign investor interest in banking counters lifted the benchmark index in Friday trading.',
        'date' => 'Aug 01, 2026',
    ],
    [
        'tag' => 'Regulation',
        'headline' => 'CMA issues updated guidance on online forex trading platforms',
        'snippet' => 'The Capital Markets Authority clarified licensing requirements for platforms marketing to Kenyan retail investors.',
        'date' => 'Jul 28, 2026',
    ],
    [
        'tag' => 'Corporate',
        'headline' => 'Safaricom PLC announces interim dividend ahead of half-year results',
        'snippet' => 'The telco confirmed a payout date for shareholders on record, alongside preliminary half-year guidance.',
        'date' => 'Jul 24, 2026',
    ],
];

$upcomingEvents = [
    ['d' => '14', 'm' => 'Aug', 'title' => 'KCB Group PLC — Annual General Meeting', 'type' => 'AGM'],
    ['d' => '19', 'm' => 'Aug', 'title' => 'Safaricom PLC — Dividend Payment Date', 'type' => 'Dividend'],
    ['d' => '22', 'm' => 'Aug', 'title' => 'Club Webinar: Reading Company Financial Statements', 'type' => 'Webinar'],
    ['d' => '05', 'm' => 'Sep', 'title' => 'NSE Investor Education Day', 'type' => 'NSE Event'],
];

$quickStats = [
    ['label' => 'Total Listed Companies', 'value' => '63', 'icon' => 'bi-building'],
    ['label' => 'Active Trading Accounts', 'value' => '1.5M+', 'icon' => 'bi-person-lines-fill'],
    ['label' => 'Foreign Participation', 'value' => '42%', 'icon' => 'bi-globe'],
    ['label' => 'Bonds Turnover', 'value' => 'KES 4.2B', 'icon' => 'bi-file-earmark-text'],
];

$custom_css = ['nsetheme.css', 'dashboard.css'];
require_once __DIR__ . '/../../includes/header.php';
?>

<header class="nse-topband py-4">
  <div class="container">
    <div class="eyebrow mb-2"><i class="bi bi-mortarboard"></i>&nbsp; MKU NSE CLUB</div>
    <h1 class="h2 mb-1">Market &amp; Economic Dashboard</h1>
    <p class="mb-0">A daily snapshot of the Nairobi Securities Exchange, built for club members tracking the market.</p>
  </div>
</header>

<div class="ticker-wrap">
  <div class="ticker-track">
    <?php
      $doubledFeed = array_merge($tickerFeed, $tickerFeed); // duplicate for seamless loop
      foreach ($doubledFeed as $t):
    ?>
      <span class="ticker-item <?php echo $t['up'] ? 'up' : 'down'; ?>">
        <span class="sym"><?php echo htmlspecialchars($t['ticker']); ?></span>
        <?php echo htmlspecialchars($t['price']); ?>
        <i class="bi <?php echo $t['up'] ? 'bi-caret-up-fill' : 'bi-caret-down-fill'; ?>"></i>
        <?php echo htmlspecialchars($t['delta']); ?>
      </span>
    <?php endforeach; ?>
  </div>
</div>

<main class="container py-5">

  <section class="mb-5">
    <h2 class="h5 mb-3">Market Summary</h2>
    <div class="row g-3">
      <?php foreach ($marketSummary as $s): ?>
        <div class="col-6 col-lg">
          <div class="nse-card stat-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="icon-chip"><i class="bi <?php echo $s['icon']; ?>"></i></span>
              <span class="delta <?php echo $s['up'] ? 'up' : 'down'; ?>">
                <i class="bi <?php echo $s['up'] ? 'bi-arrow-up-right' : 'bi-arrow-down-right'; ?>"></i> <?php echo $s['delta']; ?>
              </span>
            </div>
            <div class="label"><?php echo htmlspecialchars($s['label']); ?></div>
            <div class="value"><?php echo htmlspecialchars($s['value']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <div class="row g-4 mb-2">
    <div class="col-lg-6">
      <div class="nse-card h-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="h6 mb-0"><i class="bi bi-graph-up-arrow text-success me-1"></i> Top Gainers</h2>
          <span class="badge badge-gain rounded-pill">Today</span>
        </div>
        <?php foreach ($topGainers as $g): ?>
          <div class="mover-row">
            <div>
              <div class="co"><?php echo htmlspecialchars($g['name']); ?></div>
              <div class="tk"><?php echo htmlspecialchars($g['ticker']); ?></div>
            </div>
            <span class="pct text-success"><?php echo htmlspecialchars($g['pct']); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="nse-card h-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="h6 mb-0"><i class="bi bi-graph-down-arrow text-danger me-1"></i> Top Losers</h2>
          <span class="badge badge-loss rounded-pill">Today</span>
        </div>
        <?php foreach ($topLosers as $l): ?>
          <div class="mover-row">
            <div>
              <div class="co"><?php echo htmlspecialchars($l['name']); ?></div>
              <div class="tk"><?php echo htmlspecialchars($l['ticker']); ?></div>
            </div>
            <span class="pct text-danger"><?php echo htmlspecialchars($l['pct']); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <section class="my-5">
    <h2 class="h5 mb-3">Market Charts</h2>
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="nse-card chart-card p-4 h-100">
          <h3 class="h6">Sector Turnover (KES M)</h3>
          <canvas id="sectorTurnoverChart"></canvas>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="nse-card chart-card p-4 h-100">
          <h3 class="h6">Market Cap by Sector</h3>
          <canvas id="marketCapChart"></canvas>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="nse-card chart-card p-4 h-100">
          <h3 class="h6">NSE 20 Trend</h3>
          <canvas id="nseIndexChart"></canvas>
        </div>
      </div>
    </div>
  </section>

  <div class="row g-4 mb-2">
    <div class="col-lg-7">
      <h2 class="h5 mb-3">Market News</h2>
      <div class="d-flex flex-column gap-3">
        <?php foreach ($marketNews as $n): ?>
          <div class="nse-card news-card p-4">
            <span class="tag badge-gold badge d-inline-block mb-2"><?php echo htmlspecialchars($n['tag']); ?></span>
            <h3 class="headline h6"><?php echo htmlspecialchars($n['headline']); ?></h3>
            <p class="snippet mb-2"><?php echo htmlspecialchars($n['snippet']); ?></p>
            <span class="date"><i class="bi bi-clock-history"></i> <?php echo htmlspecialchars($n['date']); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-lg-5">
      <h2 class="h5 mb-3">Upcoming Events</h2>
      <div class="nse-card p-4">
        <?php foreach ($upcomingEvents as $e): ?>
          <div class="event-row">
            <div class="event-date-chip">
              <div class="d"><?php echo htmlspecialchars($e['d']); ?></div>
              <div class="m"><?php echo htmlspecialchars($e['m']); ?></div>
            </div>
            <div>
              <div class="fw-semibold"><?php echo htmlspecialchars($e['title']); ?></div>
              <span class="badge badge-navy"><?php echo htmlspecialchars($e['type']); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <section class="mt-5">
    <h2 class="h5 mb-3">Quick Statistics</h2>
    <div class="row g-3">
      <?php foreach ($quickStats as $q): ?>
        <div class="col-6 col-lg-3">
          <div class="nse-card stat-card text-center h-100">
            <div class="icon-chip mx-auto mb-2"><i class="bi <?php echo $q['icon']; ?>"></i></div>
            <div class="value"><?php echo htmlspecialchars($q['value']); ?></div>
            <div class="label"><?php echo htmlspecialchars($q['label']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="<?php echo $basePath; ?>/assets/js/dashboard.js"></script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>