<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check: Users must be logged in to track a portfolio
if (!isset($_SESSION['user_id'])) {
    header("Location: ../portal/login.php");
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/db.php';

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle Form Submission: Logging a new trade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_trade'])) {
    $ticker    = trim($_POST['ticker_symbol'] ?? '');
    $tradeType = $_POST['trade_type'] ?? 'BUY';
    $quantity  = (int)($_POST['quantity'] ?? 0);

    if (empty($ticker) || $quantity <= 0) {
        $error = "Please select a valid ticker symbol and enter a share quantity greater than zero.";
    } else {
        try {
            $insertStmt = $pdo->prepare("
                INSERT INTO portfolio_trades (user_id, ticker_symbol, quantity, trade_type) 
                VALUES (:user_id, :ticker, :quantity, :trade_type)
            ");
            $insertStmt->execute([
                ':user_id'    => $userId,
                ':ticker'     => strtoupper($ticker),
                ':quantity'   => $quantity,
                ':trade_type' => $tradeType
            ]);

            $success = "Trade logged successfully! Your portfolio ledger has been updated.";
        } catch (PDOException $e) {
            error_log("Trade Logging Error: " . $e->getMessage());
            $error = "Failed to save your trade. Please try again later.";
        }
    }
}

// Fetch all recorded trades for this logged-in user
$trades = [];
$totalSharesBought = 0;
$totalTradesCount = 0;

try {
    $fetchStmt = $pdo->prepare("
        SELECT trade_id, ticker_symbol, quantity, trade_type, trade_date 
        FROM portfolio_trades 
        WHERE user_id = :user_id 
        ORDER BY trade_date DESC
    ");
    $fetchStmt->execute([':user_id' => $userId]);
    $trades = $fetchStmt->fetchAll();

    $totalTradesCount = count($trades);
    foreach ($trades as $t) {
        if ($t['trade_type'] === 'BUY') {
            $totalSharesBought += (int)$t['quantity'];
        } else {
            $totalSharesBought -= (int)$t['quantity'];
        }
    }
} catch (PDOException $e) {
    error_log("Fetch Trades Error: " . $e->getMessage());
    $error = "Could not retrieve your trade history.";
}
?>

<div class="container" style="margin-top: 30px;">
    <!-- Top Summary Banner -->
    <div class="section-card" style="border-left: 6px solid var(--primary-green); border-top: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div>
                <h2 style="color: var(--dark-navy);">Virtual Portfolio Tracker</h2>
                <p style="color: var(--text-muted); margin-top: 4px;">
                    Practice managing NSE share allocations. Log your simulated buy and sell orders below.
                </p>
            </div>
            <div style="margin-top: 10px;">
                <a href="../portal/dashboard.php" class="btn btn-secondary" style="padding: 8px 16px;">&larr; Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($error)): ?>
        <div style="background: #FFEBEB; color: #D8000C; padding: 12px; border-radius: var(--radius-md); margin-bottom: 20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="background: #EBFEEB; color: #2B7A2B; padding: 12px; border-radius: var(--radius-md); margin-bottom: 20px;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <!-- Portfolio Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <div class="section-card" style="margin-bottom: 0;">
            <p style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: bold;">Total Logged Trades</p>
            <h3 style="font-size: 1.8rem; color: var(--mku-royal-blue); margin-top: 8px;">
                <?php echo $totalTradesCount; ?>
            </h3>
        </div>
        <div class="section-card" style="margin-bottom: 0;">
            <p style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: bold;">Net Practice Shares Held</p>
            <h3 style="font-size: 1.8rem; color: var(--primary-green); margin-top: 8px;">
                <?php echo number_format(max(0, $totalSharesBought)); ?>
            </h3>
        </div>
    </div>

    <!-- Main Content Layout: Log Form + Ledger Table -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        
        <!-- Left Column: Trade Logging Form -->
        <div class="section-card">
            <h3 style="margin-bottom: 16px; color: var(--dark-navy);">Log a Practice Trade</h3>
            <form method="POST" action="index.php">
                <input type="hidden" name="log_trade" value="1">

                <label for="ticker_symbol"><strong>NSE Ticker Symbol</strong></label>
                <select id="ticker_symbol" name="ticker_symbol" required>
                    <option value="">-- Select NSE Stock --</option>
                    <option value="SCOM">SCOM — Safaricom Plc</option>
                    <option value="EQTY">EQTY — Equity Group Holdings</option>
                    <option value="KCB">KCB — KCB Group Plc</option>
                    <option value="EABL">EABL — East African Breweries</option>
                    <option value="COOP">COOP — Co-operative Bank</option>
                    <option value="ABSA">ABSA — Absa Bank Kenya</option>
                    <option value="BAMB">BAMB — Bamburi Cement</option>
                </select>

                <label for="trade_type"><strong>Action</strong></label>
                <select id="trade_type" name="trade_type" required>
                    <option value="BUY">BUY — simulated purchase</option>
                    <option value="SELL">SELL — close/trim position</option>
                </select>

                <label for="quantity"><strong>Number of Shares</strong></label>
                <input type="number" id="quantity" name="quantity" min="1" required placeholder="e.g. 100">

                <button type="submit" class="btn" style="width: 100%; margin-top: 14px;">Record Trade</button>
            </form>
        </div>

        <!-- Right Column: History Ledger Table -->
        <div class="section-card">
            <h3 style="margin-bottom: 16px; color: var(--dark-navy);">Your Trade Ledger</h3>
            
            <?php if (empty($trades)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 30px 0;">
                    No simulated trades recorded yet. Select an NSE stock on the left to start building your portfolio!
                </p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color); color: var(--text-muted);">
                                <th style="padding: 10px;">Date</th>
                                <th style="padding: 10px;">Ticker</th>
                                <th style="padding: 10px;">Action</th>
                                <th style="padding: 10px; text-align: right;">Shares</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trades as $trade): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 12px 10px; color: var(--text-muted);">
                                        <?php echo date('d M Y', strtotime($trade['trade_date'])); ?>
                                    </td>
                                    <td style="padding: 12px 10px; font-weight: bold; color: var(--mku-royal-blue);">
                                        <?php echo htmlspecialchars($trade['ticker_symbol']); ?>
                                    </td>
                                    <td style="padding: 12px 10px;">
                                        <?php if ($trade['trade_type'] === 'BUY'): ?>
                                            <span style="background: #EBFEEB; color: #2B7A2B; padding: 4px 8px; border-radius: var(--radius-sm); font-weight: bold; font-size: 0.8rem;">
                                                BUY
                                            </span>
                                        <?php else: ?>
                                            <span style="background: #FFEBEB; color: #D8000C; padding: 4px 8px; border-radius: var(--radius-sm); font-weight: bold; font-size: 0.8rem;">
                                                SELL
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px 10px; text-align: right; font-weight: 600;">
                                        <?php echo number_format($trade['quantity']); ?>
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

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>