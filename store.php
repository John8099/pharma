<?php include("./backend/nodes.php"); ?>
<!DOCTYPE html>
<html lang="en">

<?php include("./components/header.php") ?>

<body>
  <div class="site-wrap">
    <?php include("./components/header-nav.php") ?>

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.html">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Store</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <?= isset($_GET['medicine']) ? "<h3 class=\" text-center\"> Search result for \"$_GET[medicine]\" </h3>" : "" ?>
        <div class="row">
          <?php
          $limit = 12;
          $offset = !isset($_GET["offset"]) ? 0 : $_GET["offset"];

          $inventoryQStr = "";
          if (isset($_GET['medicine'])) {
            $inventoryQStr = "SELECT 
              ig.id AS 'inventory_id',
              ig.medicine_id,
              mp.medicine_name,
              mp.generic_name,
              ig.product_number,
              (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
              (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
              FROM inventory_general ig
              LEFT JOIN medicine_profile mp
              ON mp.id = ig.medicine_id
              WHERE 
              (mp.medicine_name LIKE '%$_GET[medicine]%' OR mp.generic_name LIKE '%$_GET[medicine]%') and 
              ig.is_returned <> '1'
              ORDER BY mp.medicine_name ASC
              LIMIT $limit
              OFFSET $offset
              ";
          } else {
            $inventoryQStr = "SELECT 
              ig.id AS 'inventory_id',
              ig.medicine_id,
              mp.medicine_name,
              mp.generic_name,
              ig.product_number,
              (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
              (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
              FROM inventory_general ig
              LEFT JOIN medicine_profile mp 
              ON mp.id = ig.medicine_id
              WHERE ig.is_returned <> '1'
              ORDER BY mp.medicine_name ASC
              LIMIT $limit
              OFFSET $offset
              ";
          }
          $inventoryQ = mysqli_query($conn, $inventoryQStr);
          if (mysqli_num_rows($inventoryQ) > 0) :
            while ($inventory = mysqli_fetch_object($inventoryQ)) :
              if (get_near_expiration($inventory->medicine_id) != $inventory->inventory_id) continue;
          ?>
              <div class="col-sm-4 col-lg-3 text-center item mb-4">
                <a href="./shop-single?id=<?= $inventory->inventory_id ?>">
                  <img src="<?= getMedicineImage($inventory->medicine_id) ?>" style="width: 200px; height: 300px;" alt="Image">
                </a>
                <h3 class="text-dark">
                  <a href="./shop-single?id=<?= $inventory->inventory_id ?>">
                    <?= $inventory->medicine_name ?>
                  </a>
                </h3>
                <p class="price"><?= "₱ " . number_format($inventory->price, 2, '.', ',') ?></p>
              </div>

          <?php endwhile;
          endif;
          ?>
        </div>
        <?php
        $currentPage = !isset($_GET["page"]) ? 1 : $_GET["page"];

        if (mysqli_num_rows($inventoryQ) > 0) :
          $searchVal = isset($_GET["medicine"]) ? $_GET['medicine'] : "";
          $pageCount = getPageCount($searchVal, $limit);

          if ($pageCount > 0) :
            $filterBy = urlencode($searchVal);
        ?>

            <div class="row mt-5">
              <div class="col-md-12 text-center">
                <div class="site-block-27">
                  <ul>
                    <li>
                      <button type="button" class="btn btn-link btn-sm" onclick="previousPage('<?= $currentPage ?>','<?= $filterBy ?>', '<?= $offset ?>', '<?= $limit ?>')" <?= intval($currentPage) == 1 ? "disabled" : "" ?>>
                        <a href="javascript:void()">
                          <span>
                            &lt;
                          </span>
                        </a>
                      </button>
                    </li>

                    <?php
                    for ($i = 1; $i <= $pageCount; $i++) : ?>
                      <li class="<?= $currentPage == $i ? "active" : "" ?>">
                        <button type="button" class="btn btn-link btn-sm" onclick="changeLoc(<?= $i ?>, <?= $limit * intval($i - 1) ?>, '<?= $filterBy ?>')">
                          <a href="javascript:void()">
                            <span><?= $i ?></span>
                          </a>
                        </button>

                      </li>
                    <?php endfor; ?>
                    <li>
                      <button type="button" class="btn btn-link btn-sm " onclick="nextItemPage('<?= $currentPage ?>','<?= $filterBy ?>', '<?= $offset ?>', '<?= $limit ?>')" <?= intval($currentPage) == intval($pageCount) ? "disabled" : "" ?>>
                        <a href="javascript:void()">
                          <span>
                            &gt;
                          </span>
                        </a>
                      </button>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
        <?php endif;
        endif; ?>

      </div>
    </div>

  </div>

  <?php include("./components/scripts.php") ?>
  <script>
    function previousPage(page, filter, offset, limit) {
      const newOffset = Number(offset) - Number(limit);
      const newPage = Number(page) - 1;
      let path = "<?= $SERVER_NAME ?>/store".trim();

      if (filter == "") {
        path += `?page=${newPage}&&offset=${newOffset}`
      } else {
        path += `?medicine=${filter}&&page=${newPage}&&offset=${newOffset}`
      }

      window.location.href = path
    }

    function nextItemPage(page, filter, offset, limit) {
      const newOffset = Number(offset) + Number(limit);
      const newPage = Number(page) + 1;
      let path = "<?= $SERVER_NAME ?>/store".trim();

      if (filter == "") {
        path += `?page=${newPage}&&offset=${newOffset}`
      } else {
        path += `?medicine=${filter}&&page=${newPage}&&offset=${newOffset}`
      }

      window.location.href = path
    }

    function changeLoc(page, offset, filter) {
      let path = "<?= $SERVER_NAME ?>/store".trim();

      if (filter == "") {
        path += `?page=${page}&&offset=${offset}`
      } else {
        path += `?medicine=${filter}&&page=${page}&&offset=${offset}`
      }

      window.location.href = path
    }
  </script>

  </script>

</body>

</html>