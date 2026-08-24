<?php
// Dynamically calculate the base folder path (Works for any developer's local folder name or a live server)
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_replace($docRoot, '', $projectRoot);

$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$navActive = function (string $segment) use ($currentPath): string {
    return str_contains($currentPath, $segment) ? ' nav-link--active' : '';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSE MKU Investment Club | Learn & Trade</title>
    <!-- Dynamic CSS Link -->
    <link rel="stylesheet" href="<?php echo $basePath; ?>/assets/css/style.css">
</head>
<body>
<header>
    <h1>Mount Kenya University — NSE Investment Club</h1>
    <p>Unlocking Infinite Possibilities in Financial Markets</p>
</header>
<nav>
    <a href="<?php echo $basePath; ?>/index.php" class="nav-link<?php echo $navActive('/index.php'); ?>">Home</a>
    <a href="<?php echo $basePath; ?>/modules/learning/index.php" class="nav-link<?php echo $navActive('/modules/learning/'); ?>">Learning Hub</a>
    <a href="<?php echo $basePath; ?>/modules/resources/index.php" class="nav-link<?php echo $navActive('/modules/resources/'); ?>">Resource Centre</a>
    <a href="<?php echo $basePath; ?>/modules/portal/login.php" class="nav-link<?php echo $navActive('/modules/portal/'); ?>">Member Portal</a>
</nav>