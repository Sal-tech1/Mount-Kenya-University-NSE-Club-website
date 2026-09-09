<?php
$page_title = "Privacy Policy | NSE MKU Club";
$meta_description = "Learn how the NSE MKU Club collects, uses, and protects your personal data.";
$canonical_url = 'https://www.nsemkuclub.co.ke/privacy.php';

require_once __DIR__ . '/includes/header.php';
?>

<main class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="section-card nse-card p-5">
                <h2 class="mb-4" style="color: var(--mku-royal-blue);">Privacy Policy</h2>
                <p class="text-muted mb-5">Effective Date: September 2026</p>

                <h4 class="mt-4">1. Information We Collect</h4>
                <p>When you register for the Member Portal, we collect your full name, university email address, and a securely hashed password. We also track your progress in the Learning Hub to issue completion certificates.</p>

                <h4 class="mt-4">2. How We Use Your Data</h4>
                <p>Your information is used strictly to manage your club membership, authenticate your access to the Resource Centre, and maintain your virtual portfolio tracker. We do not sell or share your personal data with third parties.</p>

                <h4 class="mt-4">3. Data Security</h4>
                <p>We implement standard security measures, including password hashing and restricted database access, to protect your personal information against unauthorized access or disclosure.</p>

                <h4 class="mt-4">4. Cookies</h4>
                <p>Our website uses strictly necessary session cookies to keep you logged in to the portal. We do not use third-party tracking cookies.</p>

                <h4 class="mt-4">5. Contact Us</h4>
                <p>If you have any questions regarding this policy or wish to delete your account, please reach out to the club leadership team via our <a href="<?php echo $basePath; ?>/index.php#contact">Contact Page</a>.</p>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>