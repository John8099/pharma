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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategory">
                          New Category
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="categoryTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $categoryData = getTableData("category");

                          foreach ($categoryData as $category) :
                          ?>
                            <tr>
                              <td class=" align-middle"><?= $category->category_name ?></td>
                              <td class=" align-middle"><?= $category->description ?></td>
                              <td class=" align-middle">
                                <a href="#editCategory<?= $category->id ?>" class="h5 text-info m-2" data-toggle="modal">
                                  <i class="fa fa-cog" title="Edit" data-toggle="tooltip"></i>
                                </a>

                                <a href="#" onclick="return deleteData('category', 'id', '<?= $category->id ?>')" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                  <i class="fa fa-times-circle"></i>
                                </a>
                              </td>
                            </tr>

                            <div class="modal fade" id="editCategory<?= $category->id ?>" tabindex="-1" role="dialog" aria-labelledby="New Category" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title text-secondary">
                                      Edit Category
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form method="POST">
                                    <input type="text" name="action" value="edit" hidden readonly>
                                    <input type="text" name="categoryId" value="<?= $category->id  ?>" hidden readonly>
                                    <div class="modal-body">

                                      <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                          <input type="text" name="name" class="form-control" value="<?= $category->category_name ?>" required>
                                        </div>
                                      </div>

                                      <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Description</label>
                                        <div class="col-sm-10">
                                          <textarea name="description" class="form-control" cols="30" rows="10"><?= nl2br($category->description) ?></textarea>
                                        </div>
                                      </div>

                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" onclick="handleEditCategory($(this))" class="btn btn-primary">Save</button>
                                    </div>
                                  </form>

                                </div>
                              </div>
                            </div>
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

  <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="New Category" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Category
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addCategoryForm" method="POST">
          <input type="text" name="action" value="add" hidden readonly>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Name<span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>

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
        "columns": [{
            "width": "20%"
          },
          {
            "width": "40%"
          },
          {
            "width": "5%"
          },
        ],
        buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [0, 1]
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