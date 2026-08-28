<footer class="bd-footer pt-5 pb-4">
  <div class="container-xxl">
    <div class="row gy-5">
      <div class="col-lg-5">
        <div class="d-flex align-items-center gap-2 mb-4">
          <span class="brand-word text-white fs-2">BD</span>
          <span class="brand-dot"></span>
          <span class="brand-word text-white fs-2">HOTEL</span>
        </div>
        <p class="font-serif-luxe text-white fs-3 lh-sm mb-4" style="max-width:460px;">
          A quiet address for the discerning traveller.
        </p>
        <form class="newsletter-form" method="post" action="<?= $__base ?>/contact.php?newsletter=1">
          <?= csrf_field() ?>
          <div class="d-flex align-items-center gap-2 border-bottom border-secondary pb-2">
            <input type="email" name="newsletter_email" required placeholder="Your email address" class="form-control bg-transparent border-0 text-white shadow-none">
            <button class="btn text-gold" type="submit">Subscribe →</button>
          </div>
        </form>
      </div>
      <div class="col-6 col-lg-2">
        <div class="label-eyebrow text-muted mb-3">Explore</div>
        <ul class="list-unstyled small">
          <li><a class="text-white-50" href="<?= $__base ?>/rooms.php">Rooms</a></li>
          <li><a class="text-white-50" href="<?= $__base ?>/gallery.php">Gallery</a></li>
          <li><a class="text-white-50" href="<?= $__base ?>/about.php">About</a></li>
          <li><a class="text-white-50" href="<?= $__base ?>/contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <div class="label-eyebrow text-muted mb-3">Account</div>
        <ul class="list-unstyled small">
          <li><a class="text-white-50" href="<?= $__base ?>/login.php">Sign In</a></li>
          <li><a class="text-white-50" href="<?= $__base ?>/register.php">Register</a></li>
          <li><a class="text-white-50" href="<?= $__base ?>/profile.php">My Bookings</a></li>
        </ul>
      </div>
      <div class="col-lg-3">
        <div class="label-eyebrow text-muted mb-3">Concierge</div>
        <ul class="list-unstyled small text-white-50">
          <li class="mb-2"><i class="fa-solid fa-location-dot me-2"></i>42 Palm Avenue, Coastal District</li>
          <li class="mb-2"><i class="fa-solid fa-phone me-2"></i>+1 (555) 020 9917</li>
          <li class="mb-2"><i class="fa-solid fa-envelope me-2"></i>concierge@bdhotel.com</li>
        </ul>
        <div class="d-flex gap-3 mt-3 fs-5">
          <a class="text-white-50" href="#"><i class="fa-brands fa-instagram"></i></a>
          <a class="text-white-50" href="#"><i class="fa-brands fa-facebook"></i></a>
          <a class="text-white-50" href="#"><i class="fa-brands fa-x-twitter"></i></a>
          <a class="text-white-50" href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>
    </div>
    <hr class="border-secondary mt-5">
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small text-white-50">
      <span>© <?= date('Y') ?> BD Hotel. All rights reserved.</span>
      <span>Crafted with quiet care</span>
    </div>
  </div>
</footer>

<a href="https://wa.me/15550209917" class="float-whatsapp" target="_blank" aria-label="WhatsApp">
  <i class="fa-brands fa-whatsapp"></i>
</a>
<a href="tel:+15550209917" class="float-call" aria-label="Call">
  <i class="fa-solid fa-phone"></i>
</a>
<button id="backToTop" class="btn bd-btn-primary" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<!-- Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script src="<?= $__base ?>/js/script.js"></script>
<script src="<?= $__base ?>/js/swiper.js"></script>
<script src="<?= $__base ?>/js/validation.js"></script>
</body>
</html>
