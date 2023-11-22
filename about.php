<?php
include("./backend/nodes.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php include("./components/header.php") ?>

<body>

  <div class="site-wrap">
    <?php include("./components/header-nav.php") ?>

    <div style="position: relative;">
      <img src="./public/logo.jpeg" class="img-fluid" style="object-fit: cover;height: 85vh; width: 100%;filter: brightness(0.5);">
      <div style="position: absolute; top:0; width: 98%">
        <div class="row justify-content-center align-items-center" style="height: 85vh;">
          <div class="col-lg-7 mx-auto align-self-center">
            <div class="text-center text-white">
              <img src="./public/logo-removebg-preview.png">
              <h1>About Farmacia de Central</h1>
              <p>At Farmacia de Central, we are dedicated to providing exceptional pharmaceutical services and healthcare solutions to our community. With a commitment to your health and well-being.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section bg-light custom-border-bottom" data-aos="fade">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6">
            <div class="block-16">
              <figure>
                <img src="./public/vision.jpg" alt="Image placeholder" class="img-fluid rounded">
              </figure>
            </div>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-5">


            <div class="site-section-heading pt-3 mb-4">
              <h2 class="text-black">Our Vision</h2>
            </div>
            <p class="text-black">To be the trusted community healthcare partner of choice, offering exceptional pharmaceutical services and personalized care, where every individual's well-being is our foremost priority. We envision a future where our pharmacy store is known not only for its accessibility and affordability but also for its unwavering commitment to improving the overall health and quality of life within our community, fostering a healthier and happier tomorrow for all.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section bg-light custom-border-bottom" data-aos="fade">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-6 order-md-2">
            <div class="block-16">
              <figure>
                <img src="./public/mission.jpg" alt="Image placeholder" class="img-fluid rounded">
              </figure>
            </div>
          </div>
          <div class="col-md-5 mr-auto">


            <div class="site-section-heading pt-3 mb-4">
              <h2 class="text-black">Our Mission</h2>
            </div>
            <p class="text-black">Our mission is to provide high-quality pharmaceutical care and exceptional customer service to our community. We are dedicated to ensuring the well-being and health of our customers by offering a wide range of safe and effective medications, expert guidance on their use, and personalized wellness solutions. With a commitment to integrity, compassion, and accessibility, we strive to be the go-to destination for all healthcare needs, making a positive impact on the lives we touch and promoting a healthier community.</p>
          </div>
        </div>
      </div>
    </div>

    <footer class="site-footer">
      <div class="container">
        <div class="row d-flex justify-content-center">
          <div class="col-md-6">
            <div class="block-5 mb-5">
              <h3 class="footer-heading mb-4">Contact Info</h3>
              <ul class="list-unstyled">
                <li>Store Hours: Monday-Sunday 7:00 AM-7:00 PM </li>
                <li class="address">In front of Central Philippine University</li>
                <li class="phone"><a href="tel://0822950862">082-295-0862</a></li>
                <li class="email"><a href="https://www.facebook.com/profile.php?id=100063859718852&mibextid=ZbWKwL" target="_blank"> Farmacia De Central</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>

  </div>

  <?php include("./components/scripts.php") ?>

</body>

</html>