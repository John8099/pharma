  <!-- [ navigation menu ] start -->
  <nav class="pcoded-navbar menupos-fixed menu-light brand-blue ">
    <div class="navbar-wrapper ">
      <div class="navbar-brand header-logo p-0">
        <a href="<?= $SERVER_NAME ?>/admin/views/dashboard" class="b-brand">
          <img src="<?= $SERVER_NAME ?>/public/logo-text.png" class="logo images" style="width: 200px">
          <img src="<?= $SERVER_NAME ?>/public/logo.png" class="logo-thumb images" style="width: 45px">
        </a>
        <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
      </div>
      <div class="navbar-content scroll-div">
        <ul class="nav pcoded-inner-navbar">
          <?php
          include_once("links.php");

          $self = "$ORIGIN{$_SERVER['REQUEST_URI']}";

          $pageConfigIndex = "";

          foreach ($links as $key => $link) :
            $config = $link["config"];
            $isPageActive = $config["url"] == str_replace(".php", "", $self);

            if ($pageConfigIndex == "") {
              $pageConfigIndex = $isPageActive ? $key : null;
            }
          ?>
            <li class="nav-item">
              <a href="<?= $config["url"] ?>" class="nav-link">
                <span class="pcoded-micon">
                  <i class="<?= $config["icon"] ?>"></i>
                </span>
                <span class="pcoded-mtext">
                  <?= $link["title"] ?>
                </span>
                <?php if ($link["title"] == "Order") :
                  if ($user) :
                    if (getCartCount($user->id) > 0) :
                ?>
                      <span class="badge badge-danger badge-pill sup"><?= getCartCount($user->id) ?></span>
                <?php endif;
                  endif;
                endif; ?>
              </a>

            </li>
          <?php endforeach ?>
        </ul>
      </div>
    </div>
  </nav>
  <!-- [ navigation menu ] end -->