<?php
// NSE MKU Investment Club - Public Homepage
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Home | NSE MKU Club";
$meta_description = "The official student investment club of Mount Kenya University, empowering financial leaders through education and practical market experience.";
$custom_css = ['nsetheme.css', 'dashboard.css'];

require_once __DIR__ . '/includes/header.php';
?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section" style="position: relative; background: url('<?php echo $basePath; ?>/assets/img/hero-bg.jpg') center/cover no-repeat; padding: 140px 0; min-height: 80vh; display: flex; align-items: center;">
      <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.75); z-index: 1;"></div>
      
      <div class="container" style="position: relative; z-index: 2;">
        <div class="row gy-4">
          <div class="col-lg-8 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1 style="color: #ffffff; font-weight: 800; font-size: 3.5rem;">Welcome to the <span style="color: var(--primary-green);">MKU NSE Club</span></h1>
            <p style="color: #f8f9fa; font-size: 1.2rem; max-width: 650px; margin-top: 20px; line-height: 1.6;">
              We provide foundational financial education, club-issued certificates, and exclusive opportunities to interact with actual professionals from the Nairobi Securities Exchange.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about section light-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>About</h2>
        <p><span>Find</span> <span class="description-title">Out More</span></p>
      </div>

      <div class="container">
        <div class="row gy-3">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <img src="<?php echo $basePath; ?>/assets/img/about.jpg" alt="MKU NSE Club Members" class="img-fluid rounded shadow-sm">
          </div>
          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="about-content ps-0 ps-lg-3">
              <h3 style="color: var(--mku-royal-blue);">Start Your Investment Journey</h3>
              <p class="fst-italic text-muted mt-3">
                We exist to bridge the gap between classroom theory and real-world market application for university students.
              </p>
              <ul class="mt-4">
                <li>
                  <i class="bi bi-diagram-3" style="color: var(--primary-green);"></i>
                  <div>
                    <h4>Networking & Mentorship</h4>
                    <p>Interact with passionate peers and gain insights directly from licensed professionals at the NSE.</p>
                  </div>
                </li>
                <li class="mt-3">
                  <i class="bi bi-graph-up-arrow" style="color: var(--primary-green);"></i>
                  <div>
                    <h4>Practical Education</h4>
                    <p>Complete our club-curated courses and earn certificates to build your resume and financial confidence.</p>
                  </div>
                </li>
              </ul>
              <p class="mt-3">
                Connect, share, and watch yourself grow faster with the right community.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Platform Features Section -->
    <section id="platform" class="features section py-5 bg-light">
      <div class="container section-title text-center mb-5" data-aos="fade-up">
        <h2>Platform</h2>
        <p><span>Explore Our</span> <span class="description-title">Club Features</span></p>
      </div>

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
              <div class="card-body">
                <i class="bi bi-graph-up-arrow mb-3" style="font-size: 3rem; color: var(--primary-green);"></i>
                <h4 class="card-title mt-2" style="font-weight: 600; color: var(--mku-royal-blue);">Virtual Portfolio</h4>
                <p class="card-text text-muted mt-3">
                  Practice buying and selling NSE shares with virtual capital. Test your investment strategies risk-free.
                </p>
                <a href="<?php echo $basePath; ?>/modules/tracker/index.php" class="btn btn-outline-success mt-3 px-4">Launch Tracker</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
              <div class="card-body">
                <i class="bi bi-book mb-3" style="font-size: 3rem; color: var(--primary-green);"></i>
                <h4 class="card-title mt-2" style="font-weight: 600; color: var(--mku-royal-blue);">Learning Hub</h4>
                <p class="card-text text-muted mt-3">
                  Access beginner-friendly courses, take interactive quizzes, and earn digital completion certificates.
                </p>
                <a href="<?php echo $basePath; ?>/modules/learning/index.php" class="btn btn-outline-success mt-3 px-4">View Courses</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
              <div class="card-body">
                <i class="bi bi-file-earmark-pdf mb-3" style="font-size: 3rem; color: var(--primary-green);"></i>
                <h4 class="card-title mt-2" style="font-weight: 600; color: var(--mku-royal-blue);">Resource Centre</h4>
                <p class="card-text text-muted mt-3">
                  Official members can access exclusive webinar recordings, meeting minutes, and financial books.
                </p>
                <a href="<?php echo $basePath; ?>/modules/resources/index.php" class="btn btn-outline-success mt-3 px-4">Access Library</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Faq Section -->
    <section id="faq" class="faq section">
      <div class="container section-title" data-aos="fade-up">
        <h2>F.A.Q</h2>
        <p><span>Frequently Asked</span> <span class="description-title">Questions</span></p>
      </div>

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
            <div class="faq-container">
              <div class="faq-item">
                <h3><span class="num">1.</span> How can I join the NSE MKU Club?</h3>
                <div class="faq-content">
                  <p>Any registered MKU student can join! Simply create a free account through our student portal and follow the instructions in your dashboard to become an official member.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item">
                <h3><span class="num">2.</span> Do I need money to join?</h3>
                <div class="faq-content">
                  <p>We focus on education and paper trading first. You can create a free account to learn and practice. Official membership requires a small annual contribution of KSh 200 to support club activities.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item">
                <h3><span class="num">3.</span> What will I learn in the club?</h3>
                <div class="faq-content">
                  <p>You will learn market analysis, portfolio management, risk assessment, and the fundamentals of the Nairobi Securities Exchange.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Prominent Join CTA Section -->
    <section id="join-cta" class="join-cta section light-background py-5">
      <div class="container" data-aos="zoom-in">
        <div class="section-card cta-card text-center p-5 shadow-sm border-0" style="border-radius: 12px; background: #ffffff;">
          <h2 class="mb-3" style="color: var(--mku-royal-blue); font-weight: 700;">Ready to Start Your Investment Journey?</h2>
          <p class="mb-4 text-muted mx-auto" style="max-width: 700px; font-size: 1.1rem; line-height: 1.6;">
            Join passionate MKU students learning, networking, and growing their wealth. Membership is open to all faculties and requires no prior finance experience.
          </p>
          <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
            <?php if (!isset($_SESSION['user_id'])): ?>
              <a href="<?php echo $basePath; ?>/modules/portal/register.php" class="btn btn-success btn-lg px-4" style="background-color: var(--primary-green); border: none;">Create Free Account</a>
            <?php else: ?>
              <a href="<?php echo $basePath; ?>/modules/portal/dashboard.php" class="btn btn-success btn-lg px-4" style="background-color: var(--primary-green); border: none;">Go to Dashboard</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p><span>Need Help?</span> <span class="description-title">Contact Us</span></p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-5">
            <div class="info-wrap p-4 shadow-sm rounded bg-white h-100">
              <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-geo-alt flex-shrink-0" style="color: var(--primary-green); font-size: 24px; margin-right: 15px;"></i>
                <div>
                  <h3 style="font-size: 1.2rem; color: var(--mku-royal-blue); margin-bottom: 5px;">Location</h3>
                  <p class="text-muted mb-0">Mount Kenya University, Thika, Kenya.</p>
                </div>
              </div>

              <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone flex-shrink-0" style="color: var(--primary-green); font-size: 24px; margin-right: 15px;"></i>
                <div>
                  <h3 style="font-size: 1.2rem; color: var(--mku-royal-blue); margin-bottom: 5px;">Call Us</h3>
                  <p class="text-muted mb-0">+254 714 608 132</p>
                </div>
              </div>

              <div class="info-item d-flex mb-4" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-envelope flex-shrink-0" style="color: var(--primary-green); font-size: 24px; margin-right: 15px;"></i>
                <div>
                  <h3 style="font-size: 1.2rem; color: var(--mku-royal-blue); margin-bottom: 5px;">Email Us</h3>
                  <p class="text-muted mb-0">mkunseclub@gmail.com</p>
                </div>
              </div>

              <!-- Accurate Thika Map -->
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.176313797669!2d37.07823521021461!3d-1.0456102354179377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f4e5b27c66117%3A0xb1f44110c24f60!2sMount%20Kenya%20University%20(MKU)%20-%20Main%20Campus!5e0!3m2!1sen!2ske!4v1700000000000!5m2!1sen!2ske" frameborder="0" style="border:0; width: 100%; height: 250px; border-radius: 8px; margin-top: 20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>

          <div class="col-lg-7 d-flex align-items-stretch">
            <div class="info-wrap p-5 shadow-sm rounded bg-white w-100 d-flex flex-column justify-content-center align-items-center text-center" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-envelope-paper" style="font-size: 4rem; color: var(--primary-green); margin-bottom: 20px;"></i>
              <h3 style="color: var(--mku-royal-blue); font-weight: 700;">Reach Out Directly</h3>
              <p class="text-muted mb-4" style="font-size: 1.1rem; max-width: 400px;">
                Got questions? Click the button below to open your email application and send a message directly to the club executives.
              </p>
              <a href="mailto:mkunseclub@gmail.com?subject=Inquiry%20from%20Website" class="btn btn-success btn-lg px-5 py-3 shadow" style="background-color: var(--primary-green); border: none; font-weight: 600; border-radius: 50px;">
                <i class="bi bi-send me-2"></i> Send an Email
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>