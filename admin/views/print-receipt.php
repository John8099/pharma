<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("../components/header.php"); ?>
<style>
  body {
    font-family: FS_MCF;
  }

  @media print {
    .divButton {
      display: none;
    }
  }

  .receipt {
    max-width: 300px;
    margin: 0 auto;
    padding: 10px;
  }
</style>

<body>
  <div class="container">
    <div class="receipt text-dark">
      <div class="card">
        <div class="card-body">
          <?php
          $invoiceData = getSingleDataWithWhere("invoice", "order_id", $_GET["id"]);

          $cashier = getUserById($invoiceData->user_id);
          ?>
          <!-- Date and time -->
          <div class="row">
            <div class="col-8">
              <?= date("m/d/Y", strtotime($invoiceData->date_created)) ?>
            </div>
            <div class="col-4 p-0">
              <?= date("h:i A", strtotime($invoiceData->date_created)) ?>
            </div>
          </div>

          <!-- Cashier -->
          <div class="row my-4">
            <div class="col-6">
              CASHIER:
            </div>
            <div class="col-6 text-center">
              <?= $cashier->fname ?>
            </div>
          </div>

          <!-- Items -->
          <?php
          $orderData = getSingleDataWithWhere("order_tbl", "id=$invoiceData->order_id");

          $orderDetails = getTableData("order_details", "order_id", $orderData->id);
          foreach ($orderDetails as $detail) :
            $inventory = getSingleDataWithWhere("inventory_general", "id='$detail->inventory_general_id'");
            $medicine = getSingleDataWithWhere("medicine_profile", "id='$inventory->medicine_id'");

          ?>
            <div class="row">
              <div class="col-1"><?= $detail->quantity ?></div>
              <div class="col-7 p-0"><?= $medicine->medicine_name ?></div>
              <div class="col-3 p-0"><?= $detail->order_subtotal ?></div>
            </div>
          <?php endforeach; ?>

          <?php
          $salesData = getSingleDataWithWhere("sales", "invoice_id", $_GET["id"]);
          ?>
          <!-- Totals -->
          <div class="row mt-3 ">
            <div class="col-6 mt-2">
              ITEMS
            </div>
            <div class="col-6 mt-2 mb-4 text-center">
              <?= $salesData->total_quantity_sold ?>
            </div>
            <div class="col-6 mt-1">
              SUBTOTAL
            </div>
            <div class="col-6 mt-1 text-center">
              <?= $orderData->subtotal ?>
            </div>
            <div class="col-6 mt-1">
              DISCOUNT
            </div>
            <div class="col-6 mt-1 text-center">
              <?= $orderData->discount ?>
            </div>
            <div class="col-6 mt-1">
              TOTAL
            </div>
            <div class="col-6 mt-1 text-center">
              <?= $orderData->overall_total ?>
            </div>

          </div>

          <?php
          $paymentData = getSingleDataWithWhere("payment", "order_id", $orderData->id);
          ?>
          <div class="row mt-4 text-center">
            <p class="col-12">
              XXXXXXXXXXXXXXXXXXXXXX
            </p>
            <div class="col-6">
              AMOUNT
            </div>
            <div class="col-6">
              <?= $paymentData->paid_amount ?>
            </div>
            <div class="col-6">
              CHANGE
            </div>
            <div class="col-6">
              <?= $paymentData->customer_change ?>
            </div>
          </div>

          <p class="text-center my-4">
            <small>THANKS FOR SHOPPING WITH US</small>
          </p>
        </div>
      </div>

      <div class="container text-center divButton">
        <button class="btn btn-success " style="font-family: Mada, sans-serif;" onclick="return window.print()">Print Receipt</button>
      </div>
    </div>
  </div>
  <?php include("../components/scripts.php") ?>
</body>
<script>
  window.print()

  window.onafterprint = function() {
    window.location.href = "invoice"
  };
</script>

</html>