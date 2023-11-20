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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategory">
                          New Therapeutic Category
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="categoryTable" class="table table-hover table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Dosage Form</th>
                            <th>Description</th>
                            <th>Prescription Required</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $categoryData = getTableData("category");

                          foreach ($categoryData as $category) :
                            $status = $category->status == "1" ? "Active" : "Inactive";
                            $prescriptionStatus = $category->prescription_required == "1" ? "Yes" : "No";
                          ?>
                            <tr>
                              <td class=" align-middle"><?= $category->category_name ?></td>
                              <td class=" align-middle"><?= $category->description ?></td>
                              <td class=" align-middle">
                                <span class="status text-<?= $category->prescription_required == "1" ? "success" : "danger" ?>">•</span>
                                <?= ucfirst($prescriptionStatus) ?>
                              </td>
                              <td class=" align-middle">
                                <span class="status text-<?= $category->status == "1" ? "success" : "danger" ?>">•</span>
                                <?= ucfirst($status) ?>
                              </td>
                              <td class=" align-middle">
                                <a href="#editCategory<?= $category->id ?>" class="h5 text-info m-2" data-toggle="modal">
                                  <i class="fa fa-cog" title="Edit" data-toggle="tooltip"></i>
                                </a>

                                <a href="javascript:void()" onclick="return deleteData('category', 'id', '<?= $category->id ?>')" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                  <i class="fa fa-times-circle"></i>
                                </a>
                              </td>
                            </tr>

                            <?= editCategoryModal($category) ?>
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

  <?= addCategoryModal() ?>

  <?php include("../components/scripts.php") ?>
  <script>
    function handleEditCategory(e) {
      swal.showLoading();
      handleSaveCategory($(e[0].form).serialize())
    }

    $("#addCategoryForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveCategory($(this).serialize())
      e.preventDefault()
    })

    function handleSaveCategory(serializeData) {
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_category",
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
      const tableId = "#categoryTable";
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
          "targets": [4],
          "orderable": false
        }],
        buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [0, 1, 2, 3]
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