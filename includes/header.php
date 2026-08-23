<?php
// Dynamic Asset Path: Defaults to root (''), but deeper folders can override it
$asset_path = isset($asset_path) ? $asset_path : '';

// Dynamic Page Title and Active State Logic
$pageTitle = isset($pageTitle) ? $pageTitle : 'NSE MKU Investment Club';
$bodyClass = isset($bodyClass) ? $bodyClass : '';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $pageTitle; ?></title>
  <meta name="description" content="The official student investment club of Mount Kenya University...">

  <!-- Favicons -->
  <link href="<?php echo $asset_path; ?>/assets/img/favicon.png" rel="icon">
  <link href="<?php echo $asset_path; ?>/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
    <div class="branding d-flex align-items-cente">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="<?php echo $asset_path; ?>/index.php" class="logo d-flex align-items-center">
          <h1 class="sitename">MKU NSE Club</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="<?php echo $asset_path; ?>/index.php" class="<?php echo ($currentPage == 'index') ? 'active' : ''; ?>">Home</a></li>
            <li class="dropdown">
              <a href="#"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="<?php echo $asset_path; ?>/about.php" class="<?php echo ($currentPage == 'about') ? 'active' : ''; ?>">About Us</a></li>
                <li><a href="<?php echo $asset_path; ?>/leadership.php" class="<?php echo ($currentPage == 'leadership') ? 'active' : ''; ?>">Leadership</a></li>
                <li><a href="<?php echo $asset_path; ?>/gallery.php" class="<?php echo ($currentPage == 'gallery') ? 'active' : ''; ?>">Gallery</a></li>
              </ul>
            </li>
            <li><a href="<?php echo $asset_path; ?>/index.php#learning">Learning</a></li>
            <li><a href="<?php echo $asset_path; ?>/index.php#portfolio">Markets</a></li>
            <li class="dropdown">
              <a href="#"><span>Portal</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="<?php echo $asset_path; ?>/modules/portal/login.php">Sign In</a></li>
                <li><a href="<?php echo $asset_path; ?>/modules/portal/register.php">Sign Up</a></li>
              </ul>
            </li>
            <li><a href="<?php echo $asset_path; ?>/index.php#contact">Contact</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </div>
  </header>

  <main class="main">