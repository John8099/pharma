<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("../components/header.php"); ?>
<!-- morris css -->
<link rel="stylesheet" href="../assets/plugins/chart-morris/css/morris.css">

<body class="">

  <?php include("../components/side-nav.php"); ?>
  <?php include("../components/header-nav.php"); ?>

  <!-- [ Main Content ] start -->
  <!-- [ Main Content ] start -->
  <div class="pcoded-main-container">
    <div class="pcoded-wrapper">
      <div class="pcoded-content">
        <div class="pcoded-inner-content">
          <div class="main-body">
            <div class="page-wrapper">
              <!-- [ breadcrumb ] start -->
              <?php include("../components/page-header.php") ?>
              <!-- [ breadcrumb ] end -->
              <!-- [ Main Content ] start -->
              <div class="row">

                <!-- product profit start -->
                <div class="col-xl-3 col-md-6">
                  <div class="card prod-p-card bg-c-red">
                    <div class="card-body">
                      <?php
                      $totalProfit = mysqli_query(
                        $conn,
                        "SELECT SUM(overall_total) as profit FROM order_tbl"
                      );
                      $tProfit = mysqli_fetch_object($totalProfit);
                      ?>
                      <div class="row align-items-center m-b-25">
                        <div class="col">
                          <h6 class="m-b-5 text-white">Total Sales</h6>
                          <h3 class="m-b-0 text-white"><?= "₱ " .  number_format($tProfit->profit, 2, '.', ',') ?></h3>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="card prod-p-card bg-c-blue">
                    <div class="card-body">
                      <?php
                      $oProfitQ = mysqli_query(
                        $conn,
                        "SELECT 
                        SUM(overall_total) as profit 
                        FROM order_tbl ord 
                        INNER JOIN invoice i
                        ON i.order_id = ord.id
                        WHERE ord.type='online'"
                      );
                      $oProfit = mysqli_fetch_object($oProfitQ);
                      ?>
                      <div class="row align-items-center m-b-25">
                        <div class="col">
                          <h6 class="m-b-5 text-white">Online Sales</h6>
                          <h3 class="m-b-0 text-white"><?= "₱ " .  number_format($oProfit->profit, 2, '.', ',') ?></h3>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-database text-c-blue f-18"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="card prod-p-card bg-c-green">
                    <div class="card-body">
                      <div class="row align-items-center m-b-25">
                        <?php
                        $otcProfitQ = mysqli_query(
                          $conn,
                          "SELECT SUM(overall_total) as profit FROM order_tbl WHERE `type`='walk_in'"
                        );
                        $profit = mysqli_fetch_object($otcProfitQ);
                        ?>
                        <div class="col">
                          <h6 class="m-b-5 text-white">Over the counter Sales</h6>
                          <h3 class="m-b-0 text-white"><?= "₱ " . number_format($profit->profit, 2, '.', ',') ?></h3>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-dollar-sign text-c-green f-18"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xl-3 col-md-6">
                  <div class="card prod-p-card bg-c-yellow">
                    <div class="card-body">
                      <div class="row align-items-center m-b-25">
                        <?php
                        $soldQ = mysqli_query(
                          $conn,
                          "SELECT SUM(total_quantity_sold) as product_sold FROM sales"
                        );
                        $sold = mysqli_fetch_object($soldQ);
                        ?>
                        <div class="col">
                          <h6 class="m-b-5 text-white">Product Sold</h6>
                          <h3 class="m-b-0 text-white"><?= number_format($sold->product_sold, 0, '.', ',') ?></h3>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-tags text-c-yellow f-18"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- product profit end -->
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h5>Monthly Sales</h5>
                    </div>
                    <div class="card-body">
                      <div id="morris-bar-chart" style="height:300px"></div>
                    </div>
                  </div>
                </div>
              </div>

              <?php
              /**
               *  a = Online
               *  b = Over the counter
               * 
               */

              $months = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];

              $barData = array();

              for ($i = 0; $i < 12; $i++) {
                // date form Y-m-d
                $day = ($i + 1);
                $startDate = (date("Y") . "-" . ($day > 10 ? $day :  "0$day") . "-01");

                $onlineQuery = mysqli_query(
                  $conn,
                  "SELECT 
                  COALESCE(SUM(ord.overall_total), 0) as profit 
                  FROM order_tbl ord 
                  INNER JOIN payment p 
                  ON p.order_id = ord.id 
                  WHERE ord.type='online' and p.date_paid BETWEEN '$startDate' and LAST_DAY('$startDate')
                  "
                );

                $sumOnline = 0;
                if (mysqli_num_rows($onlineQuery) > 0) {
                  $res = mysqli_fetch_object($onlineQuery);
                  $sumOnline = $res->profit;
                }

                $otcQ = mysqli_query(
                  $conn,
                  "SELECT 
                  COALESCE(SUM(ord.overall_total), 0) as profit 
                  FROM order_tbl ord 
                  INNER JOIN payment p 
                  ON p.order_id = ord.id 
                  WHERE ord.type='walk_in' and p.date_paid BETWEEN '$startDate' and LAST_DAY('$startDate')
                  "
                );

                $sumOtc = 0;
                if (mysqli_num_rows($otcQ) > 0) {
                  $res = mysqli_fetch_object($otcQ);
                  $sumOtc = $res->profit;
                }

                $format = array(
                  "y" => $months[$i],
                  "a" => intval($sumOnline),
                  "b" => intval($sumOtc),
                );
                array_push($barData, $format);
              }

              ?>
              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->

  <!-- Required Js -->
  <script src="../assets/js/vendor-all.min.js"></script>
  <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="../assets/js/pcoded.min.js"></script>

  <!-- chart-morris Js -->
  <script src="../assets/plugins/chart-morris/js/raphael.min.js"></script>
  <script src="../assets/plugins/chart-morris/js/morris.min.js"></script>

</body>
<script>
  $(document).ready(function() {
    setTimeout(function() {
      // [ bar-simple ] chart start
      console.log(JSON.parse('<?= json_encode($barData) ?>'))
      Morris.Bar({
        element: "morris-bar-chart",
        data: JSON.parse('<?= json_encode($barData) ?>'),
        xkey: 'y',
        barSizeRatio: 0.70,
        barGap: 3,
        resize: true,
        responsive: true,
        ykeys: ["a", "b"],
        labels: ["Online", "Over the counter"],
        barColors: ["#3949AB", "#2ca961"],
      });
    }, 700);
  });
</script>

</html>