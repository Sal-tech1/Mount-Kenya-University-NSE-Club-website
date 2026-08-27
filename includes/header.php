<?php
// Dynamically calculate the base folder path (Works for any developer's local folder name or a live server)
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_replace($docRoot, '', $projectRoot);

$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$navActive = function (string $segment) use ($currentPath): string {
    return str_contains($currentPath, $segment) ? 'active' : '';
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>NSE MKU Club | Student Investment Club</title>
  <meta name="description" content="The official student investment club of Mount Kenya University, empowering the next generation of financial leaders through education and practical market experience.">

  <!-- Favicons -->
  <link href="" rel="icon">
  <link href="" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $basePath; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $basePath; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $basePath; ?>/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?php echo $basePath; ?>/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $basePath; ?>/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?php echo $basePath; ?>/assets/css/style.css" rel="stylesheet">
</head>

<body class="starter-page-page">

  <header id="header" class="header sticky-top">
    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="<?php echo $basePath; ?>/index.php" class="logo d-flex align-items-center">
          <h1 class="sitename">NSE Club MKU</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="<?php echo $basePath; ?>/index.php" class="<?php echo $navActive('/index.php'); ?>">Home</a></li>
            <li><a href="<?php echo $basePath; ?>/modules/learning/index.php" class="<?php echo $navActive('/modules/learning/'); ?>">Learning</a></li>
            <li><a href="<?php echo $basePath; ?>/modules/resources/index.php" class="<?php echo $navActive('/modules/resources/'); ?>">Resources</a></li>
            <li class="dropdown"><a href="#" class="<?php echo $navActive('/modules/portal/'); ?>"><span>Portal</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="<?php echo $basePath; ?>/modules/portal/login.php">Sign In</a></li>
                <li><a href="<?php echo $basePath; ?>/modules/portal/register.php">Sign Up</a></li>
              </ul>
            </li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </div>
  </header>