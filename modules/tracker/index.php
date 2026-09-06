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

// Handle Trade Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_trade_id'])) {
    $deleteId = (int)$_POST['delete_trade_id'];
    try {
        $delStmt = $pdo->prepare("DELETE FROM portfolio_trades WHERE trade_id = :trade_id AND user_id = :user_id");
        $delStmt->execute([':trade_id' => $deleteId, ':user_id' => $userId]);
        $successMsg = "Trade record successfully deleted.";
    } catch (PDOException $e) {
        $errorMsg = "Error deleting record. Please try again.";
    }
}

// Handle New Trade Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['ticker'], $_POST['shares'])) {
    $action = $_POST['action']; // Will map to trade_type
    $ticker = trim($_POST['ticker']); // Will map to ticker_symbol
    $shares = (int)$_POST['shares']; // Will map to quantity

    if ($shares > 0 && !empty($ticker)) {
        try {
            // Using the exact columns from your nse_club_db schema
            $insertStmt = $pdo->prepare("INSERT INTO portfolio_trades (user_id, ticker_symbol, trade_type, quantity) VALUES (:user_id, :ticker, :action, :shares)");
            $insertStmt->execute([
                ':user_id' => $userId,
                ':ticker'  => $ticker,
                ':action'  => $action,
                ':shares'  => $shares
            ]);
            $successMsg = "Practice trade successfully recorded.";
        } catch (PDOException $e) {
            $errorMsg = "Error saving trade. Please check your inputs.";
        }
    } else {
        $errorMsg = "Please enter a valid number of shares and select a ticker.";
    }
}

// Fetch Trade Ledger using the correct columns
$stmt = $pdo->prepare("SELECT trade_id, ticker_symbol, trade_type, quantity, trade_date FROM portfolio_trades WHERE user_id = :user_id ORDER BY trade_date DESC");
$stmt->execute([':user_id' => $userId]);
$trades = $stmt->fetchAll();

// Calculate Active Holdings (Net Shares per Ticker) using the correct columns
$holdStmt = $pdo->prepare("
    SELECT ticker_symbol, SUM(CASE WHEN trade_type = 'BUY' THEN quantity ELSE -quantity END) as net_shares 
    FROM portfolio_trades 
    WHERE user_id = :user_id 
    GROUP BY ticker_symbol 
    HAVING net_shares > 0
");
$holdStmt->execute([':user_id' => $userId]);
$holdings = $holdStmt->fetchAll();

$page_title = "Virtual Tracker | NSE MKU Club";
$meta_description = "Practice trading NSE shares in a risk-free simulated environment.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container py-4" style="min-height: 75vh;">
    
    <!-- Top Navigation & Header -->
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
            A <strong>portfolio</strong> is simply a collection of financial investments like stocks. This tool is a <em>simulated environment (paper trading)</em>. It allows you to practice logging buy and sell orders without using real money. Use this to maintain a record of your simulated trades and practice your portfolio allocation strategies.
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
        
        <!-- Left Column: Logging & Holdings -->
        <div class="col-lg-5">
            <!-- Form Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4" style="color: var(--mku-royal-blue);">Log a Practice Trade</h4>
                    <form method="POST" action="index.php">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NSE Ticker Symbol</label>
                            <select name="ticker" class="form-select bg-light" required>
                                <option value="">-- Select NSE Stock --</option>
                                <option value="SCOM">Safaricom Plc (SCOM)</option>
                                <option value="EQTY">Equity Group (EQTY)</option>
                                <option value="KCB">KCB Group (KCB)</option>
                                <option value="EABL">E.A. Breweries (EABL)</option>
                                <option value="KENGEN">KenGen (KENGEN)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Action</label>
                            <select name="action" class="form-select bg-light" required>
                                <option value="BUY">BUY - Simulated Purchase</option>
                                <option value="SELL">SELL - Simulated Sale</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Number of Shares</label>
                            <input type="number" name="shares" class="form-control bg-light" min="1" placeholder="e.g. 100" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold" style="background-color: var(--primary-green); border: none;">Record Trade</button>
                    </form>
                </div>
            </div>

            <!-- Active Holdings Summary -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 bg-light rounded">
                    <h5 style="color: var(--mku-royal-blue);"><i class="bi bi-pie-chart-fill me-2 text-success"></i> Current Holdings</h5>
                    <?php if (empty($holdings)): ?>
                        <p class="text-muted small mt-3 mb-0">You currently own no simulated shares.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush mt-3">
                            <?php foreach ($holdings as $holding): ?>
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <strong><?php echo htmlspecialchars($holding['ticker_symbol']); ?></strong>
                                    <span class="badge bg-success rounded-pill"><?php echo number_format($holding['net_shares']); ?> shares</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Ledger -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4" style="color: var(--mku-royal-blue);">Your Trade Ledger</h4>
                    
                    <?php if (empty($trades)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-journal-x mb-3 d-block" style="font-size: 3rem; color: #ccc;"></i>
                            <p>No simulated trades recorded yet.<br>Select an NSE stock on the left to start building your portfolio!</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Ticker</th>
                                        <th>Shares</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trades as $trade): ?>
                                        <tr>
                                            <td class="text-muted small"><?php echo date('M d, Y', strtotime($trade['trade_date'])); ?></td>
                                            <td>
                                                <span class="badge <?php echo $trade['trade_type'] === 'BUY' ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo htmlspecialchars($trade['trade_type']); ?>
                                                </span>
                                            </td>
                                            <td class="fw-bold"><?php echo htmlspecialchars($trade['ticker_symbol']); ?></td>
                                            <td><?php echo number_format($trade['quantity']); ?></td>
                                            <td>
                                                <!-- Delete Button Form -->
                                                <form method="POST" action="index.php" onsubmit="return confirm('Are you sure you want to delete this trade record?');" style="display:inline;">
                                                    <input type="hidden" name="delete_trade_id" value="<?php echo $trade['trade_id']; ?>">
                                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0" title="Delete Trade">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>