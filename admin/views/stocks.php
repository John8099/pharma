<?php
include("../../backend/nodes.php");
include("../components/modals.php");

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

                <!-- product profit start -->
                <div class="col-12">
                  <div class="card">
                    <!-- <div class="card-header p-2">
                      <div class="w-100 d-flex justify-content-end">

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addStock">
                          Add Stock
                        </button>
                      </div>
                    </div> -->
                    <div class="card-body table-border-style">
                      <table id="stockTable" class="table table-hover table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <!-- <small>(Name/ Brand/ Generic)</small> -->
                            <th>Medicine </th>
                            <th>Dosage</th>
                            <th>Purchace Price</th>
                            <th>Markup</th>
                            <th>Price</th>
                            <!-- <th>Supplier Name</th> -->
                            <th>Quantity</th>
                            <!-- <th>Received</th> -->
                            <th>Expiration</th>
                            <th>Batch #</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = mysqli_query(
                            $conn,
                            "SELECT 
                              ig.id AS 'inventory_id',
                              ig.medicine_id,
                              ig.product_number,
                              mp.medicine_name,
                              mp.generic_name,
                              mp.dosage,
                              (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                              ig.price_id,
                              (SELECT supplier_name FROM supplier s WHERE s.id = ig.supplier_id) AS 'supplier_name',
                              ig.quantity,
                              ig.date_received,
                              ig.expiration_date,
                              ig.serial_number
                            FROM inventory_general ig
                            LEFT JOIN medicine_profile mp
                            ON mp.id = ig.medicine_id
                            WHERE ig.is_returned <> '1'
                              "
                          );
                          while ($inventory = mysqli_fetch_object($query)) :
                            $priceData = getTableData("price", "id", $inventory->price_id);
                            $price = count($priceData) > 0 ? $priceData[0]->price : "NULL";

                            $imgSrc = getMedicineImage($inventory->medicine_id);
                            $exploded =  explode("/", $imgSrc);
                            $alt = $exploded[count($exploded) - 1];

                          ?>
                            <tr class="<?= $inventory->quantity < 100 ? 'text-warning' : '' ?>">
                              <td class="align-middle"><?= $inventory->product_number ?></td>
                              <td class="align-middle">
                                <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage<?= $inventory->inventory_id ?>')">
                                  <!-- <?= "$inventory->medicine_name/ $inventory->brand_name/ $inventory->generic_name" ?> -->
                                  <?= $inventory->generic_name ?>
                                </button>
                              </td>
                              <td class="align-middle dosage"><?= $inventory->dosage  ?></td>
                              <td class="align-middle"><?= $priceData[0]->purchased_price ?></td>
                              <td class="align-middle"><?= $priceData[0]->markup . "%" ?></td>
                              <td class="align-middle"><?= number_format($price, 2, '.', ',') ?></td>
                              <!-- <td class="align-middle"><?= $inventory->supplier_name ?></td> -->
                              <td class="align-middle"><?= $inventory->quantity ?></td>
                              <!-- <td class="align-middle"><?= $inventory->date_received ?></td> -->
                              <td class="align-middle"><?= $inventory->expiration_date ?></td>
                              <td class="align-middle"><?= $inventory->serial_number ?></td>
                              <td class="align-middle text-center">
                                <?php
                                $expiryDate = date("Y-m-d", strtotime($inventory->expiration_date));
                                $startData = date("Y-m-d", strtotime("-1 month", strtotime($inventory->expiration_date)));
                                $dateNow = date("Y-m-d");

                                $willExpire = false;
                                if (($dateNow >= $startData) && ($dateNow <= $expiryDate)) {
                                  $willExpire = true;
                                }

                                ?>
                                <a href="javascript:void()" onclick="handleReturnToSupplier('<?= $inventory->inventory_id ?>')" class="h5 m-2" <?= $willExpire ? "" : "disabled" ?>>
                                  <i class="fa fa-undo" title="Mark return to supplier" data-toggle="tooltip"></i>
                                </a>
                              </td>
                              <!-- <td class="align-middle">
                                <a href="javascript:void()" onclick="handleAddQuantity('<?= $inventory->inventory_id ?>')" class="h5 text-success m-2">
                                  <i class="fa fa-plus-circle" title="Add Quantity" data-toggle="tooltip"></i>
                                </a>
                              </td> -->

                            </tr>
                            <div id='divModalImage<?= $inventory->inventory_id ?>' class='div-modal pt-5'>
                              <span class='close' onclick='handleClose(`divModalImage<?= $inventory->inventory_id ?>`)'>&times;</span>
                              <img class='div-modal-content' src="<?= $imgSrc  ?>">
                              <div id="imgCaption"><?= $alt ?></div>
                            </div>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- product profit end -->
              </div>

              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Add Stock -->
  <?= addStockModal() ?>
  <!-- Modal Add Medicine -->
  <?= addMedicineModal("closeMedModal(`med`)") ?>
  <!-- Modal Category -->
  <?= addCategoryModal() ?>
  <!-- Modal Brand  -->
  <?= addBrandModal(); ?>
  <!-- Modal Supplier -->
  <?= addSupplierModal("onclick='closeMedModal(`sup`)'"); ?>

  <?php include("../components/scripts.php") ?>
  <script>
    $("#select_price").editableSelect();
    $('#select_price').attr("placeholder", "Select or Type Price");

    const closeModal = (modalId) => $(modalId).modal("hide")

    // $("#addStock").modal("show")

    function handleReturnToSupplier(inventoryId) {
      swal
        .fire({
          title: "Are you sure you want to return this item to supplier?",
          text: "You can't undo this action after successful mark.",
          icon: "warning",
          confirmButtonText: "Return to supplier",
          confirmButtonColor: "#dc3545",
          showCancelButton: true,
        })
        .then((d) => {
          if (d.isConfirmed) {
            swal.showLoading();
            $.post(
              "<?= $SERVER_NAME ?>/backend/nodes?action=return_to_supplier", {
                inventory_id: inventoryId
              },
              (data, status) => {
                const resp = JSON.parse(data);
                swal.fire({
                  title: resp.success ? "Success" : "Error",
                  html: resp.message,
                  icon: resp.success ? "success" : "error"
                }).then(() => resp.success ? window.location.reload : undefined)
              }).fail(function(e) {
              swal.fire({
                title: 'Error!',
                text: e.statusText,
                icon: 'error',
              })
            });
          }
        });
    }

    $("#modalAdd-clear").hide()

    $("#btnAddSup").on("click", function() {
      $("#addSupplier").modal("show")
      $("#addStock").modal("hide")
    })

    $("#btnAddMed").on("click", function() {
      $("#addMed").modal("show")
      $("#addStock").modal("hide")
    })

    $("#btnAddBrand").on("click", function() {
      $("#addMed").modal("hide")
      $("#addBrand").modal("show")
    })

    $("#btnAddCategory").on("click", function() {
      $("#addMed").modal("hide")
      $("#addCategory").modal("show")
    })

    $("#addCategory").on("hidden.bs.modal", function() {
      $("#addMed").modal("show")
      $("#addCategoryForm").get(0).reset()
    })

    $("#addBrand").on("hidden.bs.modal", function() {
      $("#addMed").modal("show")
      $("#addBrandForm").get(0).reset()
    })

    $("#addBrandForm").on("submit", function(e) {
      swal.showLoading();
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_brand",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => {
            if (resp.success) {
              closeModal("#addBrand")
              $("#addBrandForm").get(0).reset()
              getBrand()
              $("#addMed").modal("show")
            }
          })

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
      e.preventDefault()
    })

    $("#addCategoryForm").on("submit", function(e) {
      swal.showLoading();
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_category",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => {
            if (resp.success) {
              closeModal("#addCategory")
              $("#addCategoryForm").get(0).reset()
              getCategory()
              $("#addMed").modal("show")
            }
          })

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
      e.preventDefault()
    })

    $("#addSupplierForm").on("submit", function(e) {
      swal.showLoading();
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_supplier",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => {
            if (resp.success) {
              getSupplier()
              closeMedModal("supp")
            }
          })

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
      e.preventDefault()
    })

    $("#addMedForm").on("submit", function(e) {
      swal.showLoading();
      $.ajax({
        url: '<?= $SERVER_NAME ?>/backend/nodes?action=medicine_save',
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          const resp = JSON.parse(data);
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => {
            if (resp.success) {
              getMed()
              closeMedModal("med")
            }
          })
        },
        error: function(data) {
          swal.fire({
            title: 'Oops...',
            text: 'Something went wrong.',
            icon: 'error',
          })
        }
      });
      e.preventDefault()
    })

    function handleAddQuantity(inventoryId) {
      swal.fire({
        title: 'Number of Quantity to be added',
        input: 'number',
        showLoaderOnConfirm: true,
        confirmButtonText: 'Add',
        confirmButtonColor: "#2ca961",
        showCancelButton: true,
      }).then((res) => {
        if (res.isConfirmed) {
          $.post(
            "<?= $SERVER_NAME ?>/backend/nodes?action=add_medicine_quantity", {
              quantity_to_add: res.value,
              inventory_id: inventoryId
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

    function closeMedModal(action) {
      if (action === "med") {
        $("#addMedForm").get(0).reset()
        $("#addMed").modal("hide")
      } else {
        $("#addSupplierForm").get(0).reset()
        $("#addSupplier").modal("hide")
      }
      $("#addStock").modal("show")
    }

    function getCategory() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_category",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].category_name}</option>`;
            options.push(option);
          }

          $('#select_category').empty();
          $('#select_category').html(options);
          $('#select_category').selectpicker('refresh');
        })
    }

    function getBrand() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_brand",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].brand_name}</option>`;
            options.push(option);
          }

          $('#select_brand').empty();
          $('#select_brand').html(options);
          $('#select_brand').selectpicker('refresh');
        })
    }

    function getSupplier() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_supplier",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].supplier_name}</option>`;
            options.push(option);
          }

          $('#select_supp').empty();
          $('#select_supp').html(options);
          $('#select_supp').selectpicker('refresh');
        })
    }

    function getMed() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_medicine",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].medicine_name}/ ${resp[i].brand_name}/ ${resp[i].generic_name}</option>`;
            options.push(option);
          }

          $('#select_med').empty();
          $('#select_med').html(options);
          $('#select_med').selectpicker('refresh');
        })
    }

    function handleEditStock(e) {
      swal.showLoading();
      handleSaveStock($(e[0].form).serialize())
    }

    $("#addStockForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveStock($(this).serialize())
      e.preventDefault()
    })

    function handleSaveStock(serializeData) {
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_stock",
        serializeData,
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

    $(document).ready(function() {
      const tableId = "#stockTable";
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
        columnDefs: [{
          "targets": [8],
          "orderable": false
        }],
        buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7]
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