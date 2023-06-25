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
                      <table id="medsTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Code</th>
                            <th>Therapeutic <br> Classification</th>
                            <th>Generic name</th>
                            <th>Brand name</th>
                            <th>Dose</th>
                            <th>Type</th>
                            <th>Manufacturer</th>
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
                          ?>
                            <tr>
                              <td><?= $medicine->code ?></td>
                              <td><?= $medicine->classification ?></td>
                              <td><?= $medicine->generic_name ?></td>
                              <td><?= $medicine->brand_name ?></td>
                              <td><?= $medicine->dose ?></td>
                              <td><?= $type ?></td>
                              <td><?= $manufacturer ?></td>
                              <td><?= $medicine->quantity ?></td>
                              <td>
                                <a href="#" onclick="" class="h5 text-info m-2" title="Preview Details" data-toggle="tooltip">
                                  <i class="fa fa-eye"></i>
                                </a>
                                <a href="#" onclick="" class="h5 text-warning m-2" title="Edit Medicine" data-toggle="tooltip">
                                  <i class="fa fa-edit"></i>
                                </a>
                                <?php if ($medicine->quantity == 0) : ?>
                                  <a href="#" onclick="" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                    <i class="fa fa-times-circle"></i>
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
                <!-- product profit end -->
              </div>

              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addMed" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Medicine
          </h5>
          <button type="button" class="close" onclick="closeModal('#addMed')">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" id="addMedForm" enctype="multipart/form-data">
          <input type="text" value="add" name="action" hidden readonly>
          <div class="modal-body">
            <div class="row">

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <img src="<?= getMedicineImage() ?>" class="rounded mx-auto d-block" style="width: 200px; height: 200px;" id="profileImgDisplay">
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;" id="divChange">
                  <button type="button" class="btn btn-primary btn-sm" onclick="changeImage()">
                    Browse
                  </button>
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;" id="divClear">
                  <button type="button" class="btn btn-danger btn-sm" onclick="clearImg()">
                    Clear
                  </button>
                </div>
                <div class="mt-3" style="display: none;">
                  <input class="form-control form-control-sm" type="file" accept="image/*" onchange="previewFile(this)" id="formFile" name="medicine_img">
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Medicine Type <span class="text-danger">*</span></label>
                  <select name="type_id" class="choices form-select" required>
                    <option value="" selected disabled>Select Medicine Type</option>
                    <?php
                    $medicineTypes = getTableData("medicine_types", "status", "active");
                    foreach ($medicineTypes as $medicineType) :
                    ?>
                      <option value="<?= $medicineType->type_id ?>"><?= $medicineType->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Manufacturers <span class="text-danger">*</span></label>
                  <select name="manufacturer_id" class="choices form-select" required>
                    <option value="" selected disabled>Select Manufacturer</option>
                    <?php
                    $manufacturerTypes = getTableData("manufacturers", "status", "active");
                    foreach ($manufacturerTypes as $manufacturer) :
                    ?>
                      <option value="<?= $manufacturer->manufacturer_id ?>"><?= $manufacturer->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label>Therapeutic Classification <span class="text-danger">*</span></label>
                  <input type="text" name="classification" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Generic name <span class="text-danger">*</span></label>
                  <input type="text" name="generic_name" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Brand name <span class="text-danger">*</span></label>
                  <input type="text" name="brand_name" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Dose <span class="text-danger">*</span></label>
                  <input type="text" name="dose" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Price <span class="text-danger">*</span></label>
                  <input type="number" name="price" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Quantity <span class="text-danger">*</span></label>
                  <input type="number" name="quantity" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Expiration <span class="text-danger">*</span></label>
                  <input type="date" name="expiration" class="form-control" required>
                </div>
              </div>

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label>Medicine Description</label>
                  <textarea class="form-control" name="med_desc" rows="5"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnAdd" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-danger" onclick="closeModal('#addMed')">Cancel</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    $("#divClear").hide()

    // $("#addMed").modal("show")
    const closeModal = (modalId) => $(modalId).modal("hide")

    function changeImage() {
      $("#formFile").click()
    }

    function clearImg() {
      $("input[type=file]").val("")
      $("#profileImgDisplay").attr(
        "src",
        "<?= $defaultMedicineImg ?>"
      );
      // $("#formFile").html("Choose file")
      $("#divClear").hide()
      $("#divChange").show()
    }

    function previewFile(input) {
      let file = $("input[type=file]").get(0).files[0];

      if (file) {
        let reader = new FileReader();

        reader.onload = function() {
          $("#profileImgDisplay").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
        // $("#formFile").html(file.name)

        $("#divChange").hide()
        $("#divClear").show()
      }
    }

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
          if (resp.success) {
            swal.fire({
              title: 'Success!',
              text: resp.message,
              icon: 'success',
            }).then(() => {
              window.location.href = 'properties.php'
            })
          } else {
            swal.fire({
              title: 'Error!',
              text: resp.message,
              icon: 'error',
            })
          }
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

    $(document).ready(function() {
      const tableId = "#medsTable";
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
            columns: [1, 2, 3, 4]
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