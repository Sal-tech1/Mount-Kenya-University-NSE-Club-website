<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../portal/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$successMsg = '';
$errorMsg = '';

// Live Market Prices for Valuation
$livePrices = [
    'SCOM' => 29.85, 
    'EQTY' => 48.20, 
    'KCB'  => 41.10, 
    'EABL' => 162.50,
    'KENGEN' => 2.50
];

// Fetch User's Current Virtual Cash
$userStmt = $pdo->prepare("SELECT virtual_cash FROM users WHERE user_id = :uid");
$userStmt->execute([':uid' => $userId]);
$virtualCash = (float)$userStmt->fetchColumn();

// Handle New Trade Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['ticker'], $_POST['shares'])) {
    $action = $_POST['action']; 
    $ticker = trim($_POST['ticker']); 
    $shares = (int)$_POST['shares']; 

    if ($shares > 0 && array_key_exists($ticker, $livePrices)) {
        $proceed = true;
        $currentPrice = $livePrices[$ticker];
        $transactionValue = $shares * $currentPrice;

        if ($action === 'BUY') {
            if ($virtualCash < $transactionValue) {
                $errorMsg = "Insufficient funds. This trade requires KES " . number_format($transactionValue, 2) . " but you only have KES " . number_format($virtualCash, 2) . ".";
                $proceed = false;
            } else {
                $newCash = $virtualCash - $transactionValue;
            }
        } elseif ($action === 'SELL') {
            $checkStmt = $pdo->prepare("SELECT SUM(CASE WHEN trade_type = 'BUY' THEN quantity ELSE -quantity END) FROM portfolio_trades WHERE user_id = :uid AND ticker_symbol = :ticker");
            $checkStmt->execute([':uid' => $userId, ':ticker' => $ticker]);
            $ownedShares = (int)$checkStmt->fetchColumn();
            
            if ($shares > $ownedShares) {
                $errorMsg = "Transaction failed. You only own " . $ownedShares . " shares of " . htmlspecialchars($ticker) . ".";
                $proceed = false;
            } else {
                $newCash = $virtualCash + $transactionValue;
            }
        }

        if ($proceed) {
            try {
                // Update cash balance
                $updateCash = $pdo->prepare("UPDATE users SET virtual_cash = :newCash WHERE user_id = :uid");
                $updateCash->execute([':newCash' => $newCash, ':uid' => $userId]);
                $virtualCash = $newCash; 

                // Log the trade with the execution price
                $insertStmt = $pdo->prepare("INSERT INTO portfolio_trades (user_id, ticker_symbol, trade_type, quantity, trade_price) VALUES (:user_id, :ticker, :action, :shares, :price)");
                $insertStmt->execute([
                    ':user_id' => $userId,
                    ':ticker'  => $ticker,
                    ':action'  => $action,
                    ':shares'  => $shares,
                    ':price'   => $currentPrice
                ]);
                $successMsg = "Practice trade successfully recorded at KES " . number_format($currentPrice, 2) . " per share.";
            } catch (PDOException $e) {
                $errorMsg = "Error saving trade. Please check your inputs.";
            }
        }
    } else {
        $errorMsg = "Please enter a valid number of shares and select a valid ticker.";
    }
}

// Fetch Trade Ledger
$stmt = $pdo->prepare("SELECT trade_id, ticker_symbol, trade_type, quantity, trade_date, trade_price FROM portfolio_trades WHERE user_id = :user_id ORDER BY trade_date DESC");
$stmt->execute([':user_id' => $userId]);
$trades = $stmt->fetchAll();

// Calculate Active Holdings
$holdStmt = $pdo->prepare("
    SELECT ticker_symbol, SUM(CASE WHEN trade_type = 'BUY' THEN quantity ELSE -quantity END) as net_shares 
    FROM portfolio_trades 
    WHERE user_id = :user_id 
    GROUP BY ticker_symbol 
    HAVING net_shares > 0
");
$holdStmt->execute([':user_id' => $userId]);
$holdings = $holdStmt->fetchAll();

$totalHoldingsValue = 0;

$page_title = "Virtual Tracker | NSE MKU Club";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container py-4" style="min-height: 75vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 style="color: var(--mku-royal-blue); margin: 0; font-weight: 700;">Virtual Portfolio Tracker</h2>
        <a href="../portal/dashboard.php" class="text-muted text-decoration-none fw-bold" style="font-size: 0.95rem;">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Educational Guide Box -->
    <div class="alert shadow-sm mb-4" style="background-color: #f8f9fa; border-left: 5px solid var(--mku-royal-blue);">
        <h5 style="color: var(--mku-royal-blue);"><i class="bi bi-info-circle-fill me-2"></i> How This Tracker Works</h5>
        <p style="font-size: 0.95rem; color: #444; margin-bottom: 10px;">
            A <strong>portfolio</strong> is a collection of financial investments like stocks. This tool is a <em>simulated environment (paper trading)</em>. It allows you to practice logging buy and sell orders without using actual capital. 
        </p>
        <p style="font-size: 0.95rem; color: #444; margin-bottom: 10px;">
            <strong>Simulator Rules:</strong> Every member starts with a budget of <strong>KES 100,000</strong> in virtual cash. Trades are executed using real-time market rates and are permanent. You must sell your shares to regain purchasing power.
        </p>
        <hr>
        <p style="font-size: 0.9rem; margin-bottom: 0;">
            <strong>Ready for the real market?</strong> Once you are confident, you can open an official CDS account and trade real capital. Visit the <a href="https://www.nse.co.ke/" target="_blank" class="text-success fw-bold">Official NSE Website</a> or find a <a href="https://www.cma.or.ke/index.php/licensees" target="_blank" class="text-success fw-bold">CMA-Approved Broker</a> to get started.
        </p>
    </div>
    
    <?php if ($successMsg): ?>
        <div class="alert alert-success py-2"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
        <div class="alert alert-danger py-2"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <div class="row gy-4">
        
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4" style="color: var(--mku-royal-blue);">Execute Trade</h4>
                    <form method="POST" action="index.php">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NSE Ticker Symbol</label>
                            <select name="ticker" class="form-select bg-light" required>
                                <option value="">-- Select NSE Stock --</option>
                                <?php foreach ($livePrices as $sym => $price): ?>
                                    <option value="<?php echo htmlspecialchars($sym); ?>"><?php echo htmlspecialchars($sym); ?> (KES <?php echo number_format($price, 2); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Action</label>
                            <select name="action" class="form-select bg-light" required>
                                <option value="BUY">BUY</option>
                                <option value="SELL">SELL</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Number of Shares</label>
                            <input type="number" name="shares" class="form-control bg-light" min="1" placeholder="e.g. 100" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold" style="background-color: var(--primary-green); border: none;">Submit Order</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 bg-light rounded">
                    <h5 style="color: var(--mku-royal-blue);"><i class="bi bi-pie-chart-fill me-2 text-success"></i> Account Summary</h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                        <span class="text-muted fw-bold">Available Cash:</span>
                        <span class="fw-bold text-success fs-5">KES <?php echo number_format($virtualCash, 2); ?></span>
                    </div>
                    
                    <hr>
                    <span class="text-muted fw-bold d-block mb-2">Active Holdings:</span>
                    <?php if (empty($holdings)): ?>
                        <p class="text-muted small mb-0">You currently own no simulated shares.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($holdings as $holding): 
                                $tick = $holding['ticker_symbol'];
                                $qty = $holding['net_shares'];
                                $currentPrice = $livePrices[$tick] ?? 0;
                                $assetValue = $qty * $currentPrice;
                                $totalHoldingsValue += $assetValue;
                            ?>
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong class="d-block"><?php echo htmlspecialchars($tick); ?></strong>
                                        <span class="text-muted small"><?php echo number_format($qty); ?> shares @ KES <?php echo number_format($currentPrice, 2); ?></span>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block fw-bold text-dark">KES <?php echo number_format($assetValue, 2); ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-6">Net Worth (Cash + Assets)</span>
                        <span class="fw-bold fs-4" style="color: var(--mku-royal-blue);">KES <?php echo number_format($virtualCash + $totalHoldingsValue, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4" style="color: var(--mku-royal-blue);">Your Trade Ledger</h4>
                    
                    <?php if (empty($trades)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-journal-x mb-3 d-block" style="font-size: 3rem; color: #ccc;"></i>
                            <p>No simulated trades recorded yet.<br>Select an NSE stock on the left to execute your first order!</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($trades as $trade): ?>
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom">
                                    <div>
                                        <span class="badge <?php echo $trade['trade_type'] === 'BUY' ? 'bg-success' : 'bg-danger'; ?> me-2">
                                            <?php echo htmlspecialchars($trade['trade_type']); ?>
                                        </span>
                                        <strong class="fs-5"><?php echo htmlspecialchars($trade['ticker_symbol']); ?></strong>
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-calendar-event me-1"></i> <?php echo date('M d, Y h:i A', strtotime($trade['trade_date'])); ?>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block fw-bold"><?php echo number_format($trade['quantity']); ?> Shares</span>
                                        <?php if ($trade['trade_price'] > 0): ?>
                                            <span class="text-muted small">@ KES <?php echo number_format($trade['trade_price'], 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>