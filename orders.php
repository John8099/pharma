<?php
include("./backend/nodes.php");
include("./components/components.php");
if (!$isLogin) {
  header("location: ./");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>

<body>

  <div class="site-wrap">

    <?php include("./components/header-nav.php") ?>

    <div class="site-section">
      <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active text-uppercase" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="preparing-tab" data-toggle="tab" href="#preparing" role="tab" aria-controls="preparing" aria-selected="false">Preparing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="to-claim-tab" data-toggle="tab" href="#to-claim" role="tab" aria-controls="to-claim" aria-selected="false">To Claim</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="claimed-tab" data-toggle="tab" href="#claimed" role="tab" aria-controls="claimed" aria-selected="false">Claimed</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="declined-tab" data-toggle="tab" href="#declined" role="tab" aria-controls="declined" aria-selected="false">Declined</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-uppercase" id="canceled-tab" data-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">Canceled</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <?= orderTables("pending") ?>
          </div>
          <div class="tab-pane fade" id="preparing" role="tabpanel" aria-labelledby="preparing-tab">
            <?= orderTables("preparing") ?>
          </div>
          <div class="tab-pane fade" id="to-claim" role="tabpanel" aria-labelledby="to-claim-tab">
            <?= orderTables("to claim") ?>
          </div>
          <div class="tab-pane fade" id="claimed" role="tabpanel" aria-labelledby="claimed-tab">
            <?= orderTables("claimed") ?>
          </div>
          <div class="tab-pane fade" id="declined" role="tabpanel" aria-labelledby="declined-tab">
            <?= orderTables("declined") ?>
          </div>
          <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
            <?= orderTables("canceled") ?>
          </div>
        </div>

      </div>
    </div>

  </div>
  <?php include("./components/scripts.php") ?>

</body>
<script>
  function handleCancelOrder(orderId) {
    swal.showLoading()
    $.post(
      "<?= $SERVER_NAME ?>/backend/nodes?action=cancel_order", {
        order_id: orderId
      },
      (data, status) => {
        const resp = JSON.parse(data)
        swal.fire({
          title: resp.success ? "Success!" : 'Error!',
          text: resp.message,
          icon: resp.success ? "success" : 'error',
        }).then(() => resp.success ? window.location.reload() : undefined)

      }).fail(function(e) {
      swal.fire({
        title: 'Error!',
        text: e.statusText,
        icon: 'error',
      })
    });
  }
</script>

</html>