<?php
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projectRoot = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_replace($docRoot, '', $projectRoot);
?>
  <!-- Footer Section -->
  <footer id="footer" class="footer">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="<?php echo $basePath; ?>/index.php" class="d-flex align-items-center">
            <span class="sitename">NSE MKU Club</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Mount Kenya University</p>
            <p>Thika, Kenya</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+254 700 000 000</span></p>
            <p><strong>Email:</strong> <span>info@nsemkuclub.ke</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Explore</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/index.php">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/index.php#about">About</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/index.php#portfolio">Markets</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/modules/learning/index.php">Learning</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/index.php#team">Leadership</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/index.php#contact">Contact</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Club</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Cookie Policy</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy Policy</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of Service</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Student Rights</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Resources</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/modules/learning/index.php">Learning Materials</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Market Reports</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Investment Guides</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Research Papers</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="<?php echo $basePath; ?>/index.php#faq">FAQs</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Connect with Us</h4>
          <p>Follow us on our socials</p>
          <div class="social-links d-flex">
            <a href="#"><i class="bi bi-twitter-x"></i></a>
            <a href="#"><i class="bi bi-youtube"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2026 NSE MKU Investment Club.</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Developed by <a href="#">The NSE MKU Club IT Team</a>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="<?php echo $basePath; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $basePath; ?>/assets/vendor/php-email-form/validate.js"></script>
  <script src="<?php echo $basePath; ?>/assets/vendor/aos/aos.js"></script>
  <script src="<?php echo $basePath; ?>/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?php echo $basePath; ?>/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="<?php echo $basePath; ?>/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo $basePath; ?>/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="<?php echo $basePath; ?>/assets/js/main.js"></script>

</body>
</html>