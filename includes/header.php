<?php
// Dynamically calculate the base folder path
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_replace($docRoot, '', $projectRoot);

$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$navActive = function (string $segment) use ($currentPath): string {
    return str_contains($currentPath, $segment) ? 'active' : '';
};

// SEO and Meta Tag Defaults
$page_title = $page_title ?? 'NSE MKU Club | Student Investment Club';
$meta_description = $meta_description ?? 'The official student investment club of Mount Kenya University, empowering the next generation of financial leaders through education and practical market experience.';
$canonical_url = $canonical_url ?? 'https://www.nsemkuclub.co.ke' . $_SERVER['REQUEST_URI'];
$social_image = $social_image ?? 'https://www.nsemkuclub.co.ke/assets/img/default-social.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_description) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">

  <!-- Open Graph / Social Share -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($social_image) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonical_url) ?>">

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

  <!-- Main CSS File -->
  <link href="<?php echo $basePath; ?>/assets/css/style.css" rel="stylesheet">

  <?php
  // Dynamically load module-specific CSS to support separated stylesheets
  if (isset($custom_css)) {
      if (is_array($custom_css)) {
          foreach ($custom_css as $css_file) {
              echo '<link href="' . $basePath . '/assets/css/' . htmlspecialchars($css_file) . '" rel="stylesheet">' . "\n  ";
          }
      } elseif (is_string($custom_css) && !empty($custom_css)) {
          echo '<link href="' . $basePath . '/assets/css/' . htmlspecialchars($custom_css) . '" rel="stylesheet">' . "\n  ";
      }
  }
  ?>

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-XXXXXXXXXX');
  </script>

  <!-- Structured Data / Educational Organization Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "name": "Mount Kenya University NSE Club",
    "url": "https://www.nsemkuclub.co.ke",
    "logo": "https://www.nsemkuclub.co.ke/assets/img/logo.png",
    "description": "The official student investment club of Mount Kenya University.",
    "parentOrganization": {
      "@type": "CollegeOrUniversity",
      "name": "Mount Kenya University"
    }
  }
  </script>
</head>

<body class="starter-page-page">

  <header id="header" class="header sticky-top">
    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="<?php echo $basePath; ?>/index.php" class="logo d-flex align-items-center">
          <img src="<?php echo $basePath; ?>/assets/img/logo.png" alt="NSE Club Logo" style="max-height: 40px; margin-right: 15px;">
          <h1 class="sitename" style="margin-right: 15px;">Mount Kenya University NSE Club</h1>
          <img src="<?php echo $basePath; ?>/assets/img/mku-logo.png" alt="MKU Logo" style="max-height: 40px;">
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="<?php echo $basePath; ?>/index.php" class="<?php echo $navActive('/index.php'); ?>">Home</a></li>
            <li><a href="<?php echo $basePath; ?>/index.php#about">About</a></li>
            <li><a href="<?php echo $basePath; ?>/modules/learning/index.php" class="<?php echo $navActive('/modules/learning/'); ?>">Learning</a></li>
            <li><a href="<?php echo $basePath; ?>/modules/resources/index.php" class="<?php echo $navActive('/modules/resources/'); ?>">Resources</a></li>
            <li><a href="<?php echo $basePath; ?>/modules/dashboards/index.php" class="<?php echo $navActive('/modules/dashboards/'); ?>">Markets</a></li>
            <li><a href="<?php echo $basePath; ?>/index.php#contact">Contact</a></li>
            <li class="dropdown"><a href="#" class="<?php echo $navActive('/modules/portal/'); ?>"><span>Portal</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                  <li><a href="<?php echo $basePath; ?>/modules/portal/dashboard.php">My Dashboard</a></li>
                  <li><a href="<?php echo $basePath; ?>/modules/portal/logout.php">Log Out</a></li>
                <?php else: ?>
                  <li><a href="<?php echo $basePath; ?>/modules/portal/login.php">Sign In</a></li>
                  <li><a href="<?php echo $basePath; ?>/modules/portal/register.php">Sign Up</a></li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </div>
  </header>