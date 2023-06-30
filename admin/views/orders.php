<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("../components/header.php"); ?>

<body class="">

  <?php include("../components/side-nav.php"); ?>
  <?php include("../components/header-nav.php"); ?>

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
                <div class="col-sm-12">
                  <div class="card">
                    <div class="card-header p-2">
                      <div class="w-100 d-flex justify-content-end">
                        <?php
                        $cartCount = $user ? getCartCount($user->id) : 0;
                        ?>
                        <button type="button" class="btn btn-primary btn-sm <?= $cartCount > 0 ? "pr-0" : "" ?>" onclick="return window.location.replace('<?= $SERVER_NAME ?>/admin/views/cart')" <?= $cartCount == 0 ? "disabled" : "" ?>>
                          View Cart
                          <?php if ($cartCount > 0) : ?>
                            <span class="badge badge-pill badge-danger sup">
                              <?= $cartCount ?>
                            </span>
                          <?php endif; ?>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <table id="orderTable" class="table table-hover table-border-style">
                        <thead>
                          <tr>
                            <th>Therapeutic <br> Classification</th>
                            <th>Generic name</th>
                            <th>Brand name</th>
                            <th>Dose</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $medicineData = getTableData("medicines");
                          foreach ($medicineData as $medicine) :
                            $typeData = getTableData("medicine_types", "type_id", $medicine->type_id);
                            $type = count($typeData) > 0 ? $typeData[0]->name : "";

                            $manufacturerData = getTableData("manufacturers", "manufacturer_id ", $medicine->manufacturer_id);
                            $manufacturer = count($manufacturerData) > 0 ? $manufacturerData[0]->name : "";

                            $medData = json_encode(
                              array(
                                "medicine_id" => $medicine->medicine_id,
                                "type" => $type,
                                "manufacturer" => $manufacturer,
                                "classification" => $medicine->classification,
                                "generic" => $medicine->generic_name,
                                "brand" => $medicine->brand_name,
                                "dose" => $medicine->dose,
                                "price" => $medicine->price,
                                "quantity" => $medicine->quantity,
                                "expiration" => $medicine->expiration,
                                "imgUrl" => getMedicineImage($medicine->medicine_id),
                                "description" => $medicine->description
                              )
                            );
                          ?>

                            <tr>
                              <td><?= $medicine->classification ?></td>
                              <td><?= $medicine->generic_name ?></td>
                              <td><?= $medicine->brand_name ?></td>
                              <td><?= $medicine->dose ?></td>
                              <td><?= $type ?></td>
                              <td><?= "₱" . number_format($medicine->price, 2, ".") ?></td>
                              <td><?= $medicine->quantity ?></td>
                              <td>
                                <?php if ($medicine->quantity > 0) : ?>
                                  <a href="#" onclick='handleAddToCart(<?= $medData ?>)' class="h5 text-success m-2">
                                    <i class="fa fa-cart-plus" title="Add to Cart" data-toggle="tooltip"></i>
                                  </a>
                                <?php endif; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>
              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include("../components/scripts.php") ?>
    <script>
      function handleAddToCart(selectedMed) {
        swal.showLoading()
        const html = `
                      <ul class="list-group text-dark">
                        <li class="list-group-item">
                          Therapeutic Classification: 
                          <strong>${selectedMed.classification}</strong>
                        </li>
                        <li class="list-group-item">
                          Generic name: 
                          <strong>${selectedMed.generic}</strong>
                        </li>
                        <li class="list-group-item">
                          Brand name:
                          <strong>${selectedMed.brand}</strong>
                        </li>
                        <li class="list-group-item">
                          Dose:
                          <strong>${selectedMed.dose}</strong>
                        </li>
                        <li class="list-group-item">
                          Type:
                          <strong>${selectedMed.type}</strong>
                        </li>
                        <li class="list-group-item">
                          Quantity:
                          <strong>${selectedMed.quantity}</strong>
                        </li>
                        <li class="list-group-item">
                          Price:
                          <strong>${selectedMed.price}</strong>
                        </li>
                      </ul>
        `
        swal.fire({
          title: "Medicine details",
          html: html,
          input: 'number',
          showLoaderOnConfirm: true,
          confirmButtonText: 'Add',
          showCancelButton: true,
          preConfirm: (inputVal) => {
            if (Number(inputVal) > Number(selectedMed.quantity)) {
              return Swal.showValidationMessage("Quantity should not be greater than the current quantity.")
            }
            return inputVal
          },
          allowOutsideClick: false
        }).then((res) => {
          if (res.isConfirmed) {
            swal.showLoading()
            $.post(
              "<?= $SERVER_NAME ?>/backend/nodes?action=add_to_cart", {
                quantity_to_add: res.value,
                medicine_id: selectedMed.medicine_id
              },
              (data, status) => {
                const resp = JSON.parse(data)
                swal.fire({
                  title: resp.success ? "Success!" : 'Error!',
                  html: resp.message,
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
        })
      }

      $(document).ready(function() {
        const tableId = "#orderTable";
        var table = $(tableId).DataTable({
          paging: true,
          lengthChange: false,
          ordering: true,
          info: true,
          autoWidth: false,
          responsive: true,
          language: {
            searchBuilder: {
              button: 'Filter',
            }
          },
          buttons: [{
            extend: 'searchBuilder',
            config: {
              columns: [0, 1, 2, 3, 4, 5, 6]
            }
          }],
          dom: 'Bfrtip',
        });

        table.buttons().container()
          .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
      });
    </script>
</body>

</html>