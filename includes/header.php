<?php
// Dynamic Asset Path: Defaults to root (''), but deeper folders can override it
$asset_path = isset($asset_path) ? $asset_path : '';

// Dynamically calculate the base folder path
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_replace($docRoot, '', $projectRoot);

// Dynamic Page Title and Active State Logic
$pageTitle = isset($pageTitle) ? $pageTitle : 'NSE MKU Investment Club';
$bodyClass = isset($bodyClass) ? $bodyClass : '';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Active navigation helper
$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$navActive = function (string $segment) use ($currentPath): string {
    return str_contains($currentPath, $segment) ? ' nav-link--active' : '';
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <!-- Vendor CSS Files -->
  <link href="<?php echo $asset_path; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $asset_path; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $asset_path; ?>/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?php echo $asset_path; ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $asset_path; ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?php echo $asset_path; ?>/assets/css/style.css" rel="stylesheet">
</head>

<body class="<?php echo $bodyClass; ?>">

  <header id="header" class="header sticky-top">
    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-between">

        <a href="<?php echo $asset_path; ?>/index.php" class="logo d-flex align-items-center">
          <h1 class="sitename">MKU NSE Club</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>

            <li>
              <a href="<?php echo $asset_path; ?>/index.php"
                 class="<?php echo ($currentPage == 'index') ? 'active' : ''; ?>">
                Home
              </a>
            </li>

            <li class="dropdown">
              <a href="#">
                <span>About</span>
                <i class="bi bi-chevron-down toggle-dropdown"></i>
              </a>
              <ul>
                <li>
                  <a href="<?php echo $asset_path; ?>/about.php"
                     class="<?php echo ($currentPage == 'about') ? 'active' : ''; ?>">
                    About Us
                  </a>
                </li>
                <li>
                  <a href="<?php echo $asset_path; ?>/leadership.php"
                     class="<?php echo ($currentPage == 'leadership') ? 'active' : ''; ?>">
                    Leadership
                  </a>
                </li>
                <li>
                  <a href="<?php echo $asset_path; ?>/gallery.php"
                     class="<?php echo ($currentPage == 'gallery') ? 'active' : ''; ?>">
                    Gallery
                  </a>
                </li>
              </ul>
            </li>

            <li>
              <a href="<?php echo $asset_path; ?>/modules/learning/index.php"
                 class="<?php echo str_contains($currentPath, '/modules/learning/') ? 'active' : ''; ?>">
                Learning Hub
              </a>
            </li>

            <li>
              <a href="<?php echo $asset_path; ?>/modules/resources/index.php"
                 class="<?php echo str_contains($currentPath, '/modules/resources/') ? 'active' : ''; ?>">
                Resource Centre
              </a>
            </li>

            <li>
              <a href="<?php echo $asset_path; ?>/index.php#portfolio"
                 class="<?php echo ($currentPage == 'index' && isset($_GET['portfolio'])) ? 'active' : ''; ?>">
                Markets
              </a>
            </li>

            <li class="dropdown">
              <a href="#">
                <span>Portal</span>
                <i class="bi bi-chevron-down toggle-dropdown"></i>
              </a>
              <ul>
                <li>
                  <a href="<?php echo $asset_path; ?>/modules/portal/login.php"
                     class="<?php echo str_contains($currentPath, '/modules/portal/') ? 'active' : ''; ?>">
                    Sign In
                  </a>
                </li>
                <li>
                  <a href="<?php echo $asset_path; ?>/modules/portal/register.php"
                     class="<?php echo str_contains($currentPath, '/modules/portal/') ? 'active' : ''; ?>">
                    Sign Up
                  </a>
                </li>
              </ul>
            </li>

            <li>
              <a href="<?php echo $asset_path; ?>/index.php#contact"
                 class="<?php echo ($currentPage == 'index' && isset($_GET['contact'])) ? 'active' : ''; ?>">
                Contact
              </a>
            </li>

          </ul>

          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>
    </div>
  </header>

  <main class="main">
