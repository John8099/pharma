<div class="page-header mb-1">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <?php if ($pageConfigIndex != "") : ?>
            <h5>
              <span class="pcoded-micon mr-2">
                <i class="<?= $links[$pageConfigIndex]["config"]["icon"] ?>"></i>
              </span>
              <span class="pcoded-mtext">
                <?= $links[$pageConfigIndex]["title"] ?>
              </span>
            </h5>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>