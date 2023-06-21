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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAdminModal">
                          <i class="fa fa-user-plus"></i>
                          New Admin
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="adminTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date Created</th>
                            <!-- <th>Action</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($user) :
                            $query = mysqli_query(
                              $conn,
                              "SELECT * FROM users WHERE id <> $user->id"
                            );

                            while ($admin = mysqli_fetch_object($query)) :
                          ?>
                              <tr>
                                <td>
                                  <img src="<?= getAvatar($admin->id) ?>" class="img-radius" width="40px">
                                </td>
                                <td><?= getFullName($admin->id, "with_middle") ?></td>
                                <td><?= $admin->email ?></td>

                                <td>
                                  <?php if ($admin->role == "user") : ?>
                                    <span class="status text-info h6">
                                      <i class="fa fa-user"></i>
                                    </span>
                                    User
                                  <?php else : ?>
                                    <span class="status text-danger h6">
                                      <i class="fa fa-user-shield"></i>
                                    </span>
                                    Admin
                                  <?php endif; ?>
                                </td>
                                <td><?= date("Y-m-d", strtotime($admin->createdAt)); ?></td>
                                <!-- <td>
                                  <a href="#" class="h5 text-info m-2" title="Edit" data-toggle="tooltip">
                                    <i class="fa fa-cog"></i>
                                  </a>
                                  <a href="#" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                    <i class="fa fa-times-circle"></i>
                                  </a>
                                </td> -->
                              </tr>
                          <?php endwhile;
                          endif;
                          ?>
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

  <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="New Admin" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            <i class="fa fa-user-plus mr-1"></i>
            New Admin
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addAdminForm" method="POST">

          <div class="modal-body">
            <input type="text" value="admin" name="role" hidden readonly>
            <input type="text" value="addUser" name="action" hidden readonly>

            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
              </div>
              <input type="text" name="fname" class="form-control" placeholder="First name" required>
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
              </div>
              <input type="text" name="mname" class="form-control" placeholder="Middle name">
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
              </div>
              <input type="text" name="lname" class="form-control" placeholder="Last name" required>
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-mail"></i></span>
              </div>
              <input type="email" name="email" class="form-control" placeholder="Email" required>
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
    $(document).ready(function() {

      $("#addAdminForm").on("submit", function(e) {
        swal.showLoading();

        $.post(
          "<?= $SERVER_NAME ?>/backend/nodes?action=addUser",
          $(this).serialize(),
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

        e.preventDefault()
      })

      const tableId = "#adminTable";
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
        buttons: [
          // {
          //   extend: 'print',
          //   messageTop: '<h3>Pharma Admins</h3>',
          //   title: "",
          //   exportOptions: {
          //     columns: [1, 2, 3]
          //   }
          // },
          // {
          //   extend: 'pdf',
          //   messageTop: '<h3>Pharma Admins</h3>',
          //   title: "",
          //   exportOptions: {
          //     columns: [1, 2, 3]
          //   }
          // },
          // {
          //   extend: 'csv',
          //   messageTop: '<h3>Pharma Admins</h3>',
          //   title: "",
          //   exportOptions: {
          //     columns: [1, 2, 3]
          //   }
          // },
          // {
          //   extend: 'excel',
          //   messageTop: '<h3>Pharma Admins</h3>',
          //   title: "",
          //   exportOptions: {
          //     columns: [1, 2, 3]
          //   }
          // },

          {
            extend: 'searchBuilder',
            config: {
              columns: [1, 2, 3, 4]
            }
          }
        ],
        dom: 'Bfrtip',
      });

      table.buttons().container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
    });
  </script>
</body>

</html>