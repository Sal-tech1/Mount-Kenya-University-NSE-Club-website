<?php
// cron/update_prices.php
// This script fetches live NSE data and updates the local database.

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

// Map your local tickers to Yahoo Finance symbols for the Nairobi Securities Exchange
$tickerMap = [
    'SCOM' => 'SCOM.NR',
    'EQTY' => 'EQTY.NR',
    'KCB'  => 'KCB.NR',
    'EABL' => 'EABL.NR',
    'COOP' => 'COOP.NR',
    'ABSA' => 'ABSA.NR',
    'BAMB' => 'BAMB.NR',
    'KQ'   => 'KQ.NR',
    'KPLC' => 'KPLC.NR'
];

$symbols = implode(',', array_values($tickerMap));
$url = "https://query1.finance.yahoo.com/v7/finance/quote?symbols=" . $symbols;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// A User-Agent header ensures the API accepts the connection request
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);
    
    if (isset($data['quoteResponse']['result'])) {
        $results = $data['quoteResponse']['result'];

        // Prepare the SQL statement for inserting or updating prices
        $stmt = $pdo->prepare("
            INSERT INTO market_prices (ticker_symbol, current_price, previous_close)
            VALUES (:ticker, :price, :prev_close)
            ON DUPLICATE KEY UPDATE
            current_price = :update_price,
            previous_close = :update_prev_close
        ");

        foreach ($results as $quote) {
            $localTicker = array_search($quote['symbol'], $tickerMap);

            if ($localTicker) {
                $currentPrice = $quote['regularMarketPrice'] ?? 0;
                $prevClose = $quote['regularMarketPreviousClose'] ?? 0;

                $stmt->execute([
                    ':ticker' => $localTicker,
                    ':price' => $currentPrice,
                    ':prev_close' => $prevClose,
                    ':update_price' => $currentPrice,
                    ':update_prev_close' => $prevClose
                ]);
            }
        }
        echo "Market data synced successfully.";
    } else {
        echo "Failed to parse API response structure.";
    }
} else {
    echo "Failed to connect to the external API.";
}
?>