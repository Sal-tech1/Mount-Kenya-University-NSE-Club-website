<?php
// Define SEO variables for the error page
$page_title = "Page Not Found | NSE MKU Club";
$meta_description = "The page you are looking for does not exist or has been moved.";

// Use the existing header
require_once __DIR__ . '/includes/header.php';
?>

<main class="container py-5 text-center" style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <h1 style="font-size: 6rem; color: var(--mku-royal-blue); font-weight: 700;">404</h1>
    <h2 class="mb-3">Oops! Page Not Found</h2>
    <p class="text-muted mb-4" style="max-width: 500px;">
        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>
    <a href="<?php echo $basePath; ?>/index.php" class="btn btn-accent px-4 py-2">
        <i class="bi bi-house-door-fill me-2"></i> Return to Homepage
    </a>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>