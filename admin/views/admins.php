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

  <!-- [ Main Content ] start -->
  <!-- [ Main Content ] start -->
  <div class="pcoded-main-container">
    <div class="pcoded-wrapper">
      <div class="pcoded-content">
        <div class="pcoded-inner-content">
          <div class="main-body">
            <div class="page-wrapper">
              <!-- [ breadcrumb ] start -->

              <div class="page-header mb-1">
                <div class="page-block">
                  <div class="row align-items-center">
                    <div class="col-md-12">
                      <div class="page-header-title">
                        <h5>Admins</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- [ breadcrumb ] end -->

              <!-- [ Main Content ] start -->
              <div class="row">

                <!-- product profit start -->
                <div class="col-12">
                  <div class="card">
                    <div class="card-header p-2">
                      <div class="w-100 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary btn-sm">
                          <i class="fa fa-user-plus"></i>
                          Add Admin
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <div class="table-responsive">
                        <table id="adminTable" class="table table-hover">
                          <thead>
                            <tr>
                              <th>Avatar</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Created At</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if ($user) :
                              $query = mysqli_query(
                                $conn,
                                "SELECT * FROM users WHERE `role`='admin' "
                              );

                              while ($admin = mysqli_fetch_object($query)) :
                            ?>
                                <tr>
                                  <td>
                                    <img src="<?= getAvatar($admin->id) ?>" class="img-radius" width="40px">
                                  </td>
                                  <td><?= getFullName($admin->id, "with_middle") ?></td>
                                  <td><?= $admin->email ?></td>
                                  <td><?= date("Y-m-d", strtotime($admin->createdAt)); ?></td>
                                  <td>

                                  </td>
                                </tr>
                            <?php endwhile;
                            endif;
                            ?>
                          </tbody>
                        </table>
                      </div>
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
  <!-- [ Main Content ] end -->


  <?php include("../components/scripts.php") ?>
  <script>
    $(document).ready(function() {
      var table = $('#adminTable').DataTable({
        lengthChange: false,
        buttons: ['searchBuilder'],
        dom: 'Bfrtip',
      });

      table.buttons().container()
        .appendTo('#adminTable_wrapper .col-md-6:eq(0)');
    });
  </script>
</body>

</html>