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
                    <div class="card-header p-2">
                      <div class="w-100 d-flex justify-content-end">

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMed">
                          New Medicine
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="medsTable" class="table table-hover table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Image</th>
                            <th>Therapeutic Name</th>
                            <th>Generic name</th>
                            <th>Brand name</th>
                            <th>Therapeutic Name</th>
                            <th>Dose</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $medicineData = mysqli_query(
                            $conn,
                            "SELECT * FROM medicine_profile WHERE deleted <> 1"
                          );
                          while ($medicine = mysqli_fetch_object($medicineData)) :
                            $category = getTableData("category", "id", $medicine->category_id);
                            $brand = getTableData("brands", "id", $medicine->brand_id);
                          ?>
                            <tr>
                              <td class="align-middle">
                                <img src="<?= getMedicineImage($medicine->id) ?>" class="rounded" width="60px">
                              </td>
                              <td class="align-middle"><?= $medicine->medicine_name ?></td>
                              <td class="align-middle"><?= $medicine->generic_name ?></td>
                              <td class="align-middle"><?= count($brand) > 0 ? $brand[0]->brand_name : "<em class='text-muted'>N/A</em>" ?></td>
                              <td class="align-middle"><?= count($category) > 0 ? $category[0]->category_name : "<em class ='text-muted'>N/A</em>" ?></td>
                              <td class="align-middle"><?= $medicine->dosage ?></td>
                              <td class="align-middle">
                                <a href="#editMed<?= $medicine->id ?>" class="h5 text-info m-2" data-toggle="modal">
                                  <i class="fa fa-cog" title="Edit" data-toggle="tooltip"></i>
                                </a>
                                <a href="javascript:void()" onclick="deleteMed('<?= $medicine->id ?>')" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                  <i class="fa fa-times-circle"></i>
                                </a>

                              </td>
                            </tr>
                            <?= editMedicineModal($medicine); ?>
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

  <!-- Modal Add Medicine -->
  <?= addMedicineModal() ?>
  <!-- Modal Category -->
  <?= addCategoryModal() ?>
  <!-- Modal Brand  -->
  <?= addBrandModal(); ?>

  <?php include("../components/scripts.php") ?>
  <script>
    // $("#addMed").modal("show")

    $("#modalAdd-clear").hide()

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

    function deleteMed(medId) {
      swal.fire({
        title: "Are you sure you want to remove this item?",
        text: "You can't undo this action after successful deletion.",
        icon: "warning",
        confirmButtonText: "Delete",
        confirmButtonColor: "#dc3545",
        showCancelButton: true,
      }).then((d) => {
        if (d.isConfirmed) {
          swal.showLoading();
          $.post(
            "<?= $SERVER_NAME ?>/backend/nodes?action=delete_med", {
              medicine_id: medId
            },
            (data, status) => {
              const resp = JSON.parse(data);
              if (!resp.success) {
                swal.fire({
                  title: "Error!",
                  html: resp.message,
                  icon: "error",
                });
              } else {
                window.location.reload();
              }
            }
          ).fail(function(e) {
            swal.fire({
              title: "Error!",
              html: e.statusText,
              icon: "error",
            });
          });
        }
      });
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

    const closeModal = (modalId) => $(modalId).modal("hide")

    function handleOpenEditModal(medId) {
      const modalId = `#editMed${medId}`
      const modalEditBrowseBtn = `#modalEdit-browse${medId}`
      const modalClearBrowseBtn = `#modalEdit-clear${medId}`
      const modalDisplayImg = `#modalEdit-display${medId}`

      $(modalId).modal("show")
    }

    function handleSubmitEditMedicine(formId, medId) {
      swal.showLoading();
      const category = $(`#sel_cat${medId}`).val()
      const brand = $(`#sel_brand${medId}`).val()

      let formData = new FormData($(formId)[0]);
      formData.append("category_id", category)
      formData.append("brand_id", brand)

      handleSaveMedicine(formData)
    }

    $("#addMedForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveMedicine(new FormData(this))
      e.preventDefault()
    })

    function handleSaveMedicine(formData) {
      $.ajax({
        url: '<?= $SERVER_NAME ?>/backend/nodes?action=medicine_save',
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          const resp = JSON.parse(data);
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => resp.success ? window.location.reload() : undefined)
        },
        error: function(data) {
          swal.fire({
            title: 'Oops...',
            text: 'Something went wrong.',
            icon: 'error',
          })
        }
      });
    }

    $(document).ready(function() {

      const tableId = "#medsTable";
      var table = $(tableId).DataTable({
        paging: true,
        lengthChange: false,
        order: [
          [1, 'asc']
        ],
        info: true,
        autoWidth: false,
        responsive: true,
        language: {

          searchBuilder: {
            button: 'Filter',
          }
        },
        columnDefs: [{
          "targets": [0, 6],
          "orderable": false
        }],
        buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [1, 2, 3, 4, 5]
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