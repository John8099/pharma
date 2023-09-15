<?php
include("./backend/nodes.php");
?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>

<body>

  <div class="site-wrap">

    <?php include("./components/header-nav.php") ?>

    <div class="site-blocks-cover" style="background-image: url('./assets/images/hero_1.jpg');">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 mx-auto order-lg-2 align-self-center">
            <div class="site-block-cover-content text-center">
              <h2 class="sub-title">Effective Medicine, New Medicine Everyday</h2>
              <h1>Welcome To Pharma</h1>
              <p>
                <a href="<?= "$SERVER_NAME/store" ?>" class="btn btn-primary px-5 py-3">Shop Now</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    $popularQuery = mysqli_query(
      $conn,
      "SELECT
        inventory_general_id,
        SUM(quantity) AS totalQuantity
        FROM order_details od
        WHERE inventory_general_id IS NOT NULL
        GROUP BY inventory_general_id
      ORDER BY totalQuantity DESC
      LIMIT 12"
    );
    if (mysqli_num_rows($popularQuery) > 0) :
    ?>
      <div class="site-section">
        <div class="container">
          <div class="row">
            <div class="title-section text-center col-12">
              <h2 class="text-uppercase">Popular Products</h2>
            </div>
          </div>
          <div class="row">
            <?php
            while ($popular = mysqli_fetch_object($popularQuery)) :
              $inventoryQStr = mysqli_query(
                $conn,
                "SELECT 
              ig.id AS 'inventory_id',
              ig.medicine_id,
              mp.medicine_name,
              ig.quantity,
              (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
              FROM inventory_general ig
              LEFT JOIN medicine_profile mp
              ON mp.id = ig.medicine_id
              WHERE ig.id = '$popular->inventory_general_id'
              "
              );
              if (mysqli_num_rows($inventoryQStr) > 0) :
                $inventory = mysqli_fetch_object($inventoryQStr);
            ?>
                <div class="col-sm-6 col-lg-4 text-center item mb-4">
                  <!-- <span class="tag">Sale</span> -->
                  <a href="<?= $SERVER_NAME . "/shop-single?id=$inventory->inventory_id" ?>">
                    <img src="<?= getMedicineImage($inventory->medicine_id) ?>" alt="Image" class="img-fluid p-5">
                  </a>
                  <h3 class="text-dark">
                    <a href="<?= $SERVER_NAME . "/shop-single?id=$inventory->inventory_id" ?>"><?= $inventory->medicine_name ?></a>
                  </h3>
                  <?= "₱ " . number_format($inventory->price, 2, '.', ',') ?>
                </div>
              <?php endif; ?>
            <?php endwhile; ?>

          </div>
          <div class="row mt-5">
            <div class="col-12 text-center">
              <a href="<?= "$SERVER_NAME/store" ?>" class="btn btn-primary px-4 py-3">View All Products</a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>


    <?php
    $newInventoryQStr = mysqli_query(
      $conn,
      "SELECT 
      ig.id AS 'inventory_id',
      ig.medicine_id,
      mp.medicine_name,
      (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
      FROM inventory_general ig
      LEFT JOIN medicine_profile mp
      ON mp.id = ig.medicine_id
      ORDER BY ig.id DESC
      LIMIT 12
    "
    );
    if (mysqli_num_rows($newInventoryQStr) > 0) :
    ?>

      <div class="site-section bg-light">
        <div class="container">
          <div class="row">
            <div class="title-section text-center col-12">
              <h2 class="text-uppercase">New Products</h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 block-3 products-wrap">
              <div class="nonloop-block-3 owl-carousel">
                <?php
                if (mysqli_num_rows($newInventoryQStr) > 0) :
                  while ($newInventory = mysqli_fetch_object($newInventoryQStr)) :
                ?>
                    <div class="text-center item mb-4">
                      <a href="<?= $SERVER_NAME . "/shop-single?id=$newInventory->inventory_id" ?>">
                        <img src="<?= getMedicineImage($newInventory->medicine_id) ?>" alt="Image" class="img-fluid p-5">
                      </a>
                      <h3 class="text-dark">
                        <a href="<?= $SERVER_NAME . "/shop-single?id=$newInventory->inventory_id" ?>"><?= $newInventory->medicine_name ?></a>
                      </h3>
                      <p class="price">
                        <?= "₱ " . number_format($newInventory->price, 2, '.', ',') ?>
                      </p>
                    </div>
                  <?php endwhile; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>

  <?php include("./components/scripts.php") ?>

</body>

</html>