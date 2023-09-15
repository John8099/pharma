<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Farmacia de Central</title>

  <style>
    @import url('http://fonts.cdnfonts.com/css/vcr-osd-mono');

    body {
      font-family: 'VCR OSD Mono';
      color: #000;
      text-align: center;
      display: flex;
      justify-content: center;
      font-size: 10px;
    }


    .bill {
      width: 300px;
      box-shadow: 0 0 3px #aaa;
      padding: 10px 10px;
      box-sizing: border-box;
    }

    .flex {
      display: flex;
    }

    .justify-between {
      justify-content: space-between;
    }

    .table {
      border-collapse: collapse;
      width: 100%;
    }

    .table .header {
      border-top: 2px dashed #000;
      border-bottom: 2px dashed #000;
    }

    .table {
      text-align: left;
    }

    .table .total td:first-of-type {
      border-top: none;
      border-bottom: none;
    }

    .table .total td {
      border-top: 2px dashed #000;
      /* border-bottom: 2px dashed #000; */
    }

    .table .net-amount td:first-of-type {
      border-top: none;
    }

    .table .net-amount td {
      border-top: 2px dashed #000;
    }

    .table .net-amount2 {
      border-bottom: 2px dashed #000;
    }

    @media print {
      body {
        transform: scale(1);
      }

      .bill {
        box-shadow: none;
        padding: 0;
        width: 100%;
      }

      .hidden-print,
      .hidden-print * {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <div class="bill" style="padding: 25px;">
    <div class="brand">
      <h4 style="margin: 10px;">Farmacia de Central</h4>
    </div>
    <div class="address">
      <h4 style="margin: 10px;">Central Philippine University Pharmacy</h4>
    </div>
    <h4 style="margin: 10px;">RETAIL INVOICE </h4>
    <div class="bill-details">
      <?php
      $invoiceData = getSingleDataWithWhere("invoice", "order_id", $_GET["id"]);

      $cashier = getUserById($invoiceData->user_id);
      ?>
      <!-- Date and time -->
      <div class="flex justify-between">
        <div>BILL DATE: <?= date("m/d/Y", strtotime($invoiceData->date_created)) ?></div>
        <div>TIME: <?= date("H:i", strtotime($invoiceData->date_created)) ?></div>
      </div>
      <br>
    </div>
    <table class="table">
      <thead>
        <tr class="header">
          <th>
            Items
          </th>
          <th>
            Price
          </th>
          <th>
            Qty
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        $orderData = getSingleDataWithWhere("order_tbl", "id=$invoiceData->order_id");

        $orderDetails = getTableData("order_details", "order_id", $orderData->id);
        foreach ($orderDetails as $detail) :
          $inventory = getSingleDataWithWhere("inventory_general", "id='$detail->inventory_general_id'");
          $medicine = getSingleDataWithWhere("medicine_profile", "id='$inventory->medicine_id'");

          $price = getSingleDataWithWhere("price", "id='$inventory->price_id'");
        ?>
          <tr>
            <td><?= $medicine->medicine_name ?></td>
            <td><?= $price->price ?></td>
            <td><?= $detail->quantity ?></td>
            <td><?= $detail->order_subtotal ?></td>
          </tr>
        <?php endforeach ?>

        <tr class="total">
          <td></td>
          <td>Subtotal</td>
          <td></td>
          <td> <?= $orderData->subtotal ?></td>
        </tr>
        <tr>
          <td></td>
          <td>Discount</td>
          <td></td>
          <td> <?= $orderData->discount ?></td>
        </tr>
        <tr>
          <td></td>
          <td>Total</td>
          <td></td>
          <td> <?= $orderData->overall_total ?></td>
        </tr>

        <?php
        $paymentData = getSingleDataWithWhere("payment", "order_id", $orderData->id);
        ?>
        <tr class="net-amount">
          <td></td>
          <td>Amount</td>
          <td></td>
          <td> <?= $paymentData->paid_amount ?></td>
        </tr>
        <tr class="net-amount2">
          <td></td>
          <td>Change</td>
          <td></td>
          <td><?= $paymentData->customer_change ?></td>
        </tr>
      </tbody>

    </table>
    <br>
    <h4 style="margin: 10px;">Payment Method: Cash</h4>
    <h4 style="margin: 10px;">Transaction ID: <?= $orderData->order_number ?></h4>
    <h4 style="margin: 10px;">Cashier: <?= $cashier->fname ?></h4>
    <h4 style="margin: 10px;">Thank You ! Please visit again</h4>

  </div>
  
</body>
<script>
  window.print()

  window.onafterprint = function() {
    window.location.href = "invoice"
  };
</script>

</html>