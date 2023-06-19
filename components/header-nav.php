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
          <a href="index.html" class="js-logo-clone d-flex align-items-center">
            <img src="<?= $SERVER_NAME ?>/public/logo.png" class="logo mr-2">
            Pharma
          </a>
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
            <?php endforeach;

            if (!$isLogin) : ?>
              <li class="auth d-lg-none">
                <button type="button" class="btn btn-link btn-sm" style="color: blue;" onclick="return window.location.href = './auth?page=sign-in&&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>'">
                  Sign in
                </button> |
                <button type="button" class="btn btn-link btn-sm" style="color: blue;" onclick="return window.location.href = './auth?page=sign-up&&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>'">
                  Sign up
                </button>
              </li>
            <?php else : ?>
              <li class="d-lg-none">
                <a href="">
                  Profile
                </a>
              </li>
              <li class="d-lg-none">
                <a href="">
                  Orders
                </a>
              </li>
              <li class="d-lg-none">
                <a class="dropdown-item" href="<?= $SERVER_NAME ?>/backend/nodes?action=logout&&location=user">
                  Sign Out
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>

      <div class="icons">
        <a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a>
        <a href="cart.html" class="icons-btn d-inline-block bag">
          <span class="icon-shopping-cart"></span>
          <span class="number">2</span>
        </a>
        <?php if (!$isLogin) : ?>
          <a href="#" class="icons-btn d-inline-block user-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="icon-user-circle-o"></span>
          </a>
        <?php else : ?>
          <a href="#" class="icons-btn d-inline-block user-icon ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="<?= getAvatar($user->id) ?>" alt="img" class="user-img">
          </a>
        <?php endif; ?>

        <div class="dropdown-menu">
          <?php if (!$isLogin) : ?>
            <a class="dropdown-item" href="./auth?page=sign-in&&url=<?= urlencode($_SERVER["REQUEST_URI"]) ?>">
              <i class="icon-sign-in mr-1 mr-2"></i>
              Sign in
            </a>
            <a class="dropdown-item" href="./auth?page=sign-up&&url=<?= urlencode($_SERVER["REQUEST_URI"]) ?>">
              <i class="icon-edit mr-1 mr-2"></i>
              Sign up
            </a>
          <?php else : ?>
            <a class="dropdown-item" href="">
              <i class="icon-shopping-bag mr-2"></i>
              Orders
            </a>
            <a class="dropdown-item" href="">
              <i class="icon-user-circle-o mr-2"></i>
              Profile
            </a>
            <a class="dropdown-item" href="<?= $SERVER_NAME ?>/backend/nodes?action=logout&&location=user">
              <i class="icon-sign-out mr-1 mr-2"></i>
              Sign Out
            </a>
          <?php endif; ?>
        </div>

        <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
      </div>
    </div>
  </div>
</div>