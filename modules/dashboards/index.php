<?php
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

$tickerFeed = [
    ['ticker' => 'SCOM', 'price' => '29.85', 'delta' => '+0.35', 'up' => true],
    ['ticker' => 'EQTY', 'price' => '48.20', 'delta' => '-0.60', 'up' => false],
    ['ticker' => 'KCB',  'price' => '41.10', 'delta' => '+0.85', 'up' => true],
    ['ticker' => 'EABL', 'price' => '162.50', 'delta' => '+2.25', 'up' => true],
    ['ticker' => 'COOP', 'price' => '16.75', 'delta' => '-0.10', 'up' => false],
    ['ticker' => 'ABSA', 'price' => '18.90', 'delta' => '+0.20', 'up' => true],
    ['ticker' => 'BAMB', 'price' => '58.75', 'delta' => '+4.55', 'up' => true],
    ['ticker' => 'KQ',   'price' => '5.52', 'delta' => '+0.32', 'up' => true],
    ['ticker' => 'KPLC', 'price' => '3.98', 'delta' => '-0.24', 'up' => false],
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
    ['label' => 'Listed Companies', 'value' => '64', 'icon' => 'bi-buildings'],
    ['label' => 'Active Investors', 'value' => '2.1M', 'icon' => 'bi-people'],
    ['label' => 'Bond Listings', 'value' => '78', 'icon' => 'bi-file-earmark-ruled'],
    ['label' => 'ETFs', 'value' => '5', 'icon' => 'bi-collection'],
];

$custom_css = ['nse-theme.css', 'dashboard.css'];
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
<script src="../../assets/js/dashboard.js"></script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>