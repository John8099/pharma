<div class="site-navbar py-2">

  <div class="search-wrap">
    <div class="container">
      <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
      <form action="#" method="post">
        <input type="text" class="form-control" placeholder="Search keyword and hit enter...">
      </form>
    </div>
  </div>

  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <div class="logo">
        <div class="site-logo">
          <a href="index.html" class="js-logo-clone">Pharma</a>
        </div>
      </div>
      <div class="main-nav d-none d-lg-block">
        <nav class="site-navigation text-right text-md-center" role="navigation">
          <ul class="site-menu js-clone-nav d-none d-lg-block">
            <?php
            include("links.php");
            $self = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

            foreach ($links as $index => $link) :
            ?>
              <li class="<?= $link["url"] == str_replace(".php", "", $self) ? "active" : ""  ?>">
                <a href="<?= $link["url"] ?>">
                  <?= $link["title"] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>

      <div class="icons">
        <a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a>
        <a href="cart.html" class="icons-btn d-inline-block bag">
          <span class="icon-shopping-bag"></span>
          <span class="number">2</span>
        </a>
        <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
        
      </div>
    </div>
  </div>
</div>