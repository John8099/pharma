window.deleteData = function (table, col, val) {
  swal
    .fire({
      title: "Are you sure you want to remove this item?",
      text: "You can't undo this action after successful deletion.",
      icon: "warning",
      confirmButtonText: "Delete",
      confirmButtonColor: "#dc3545",
      showCancelButton: true,
    })
    .then((d) => {
      if (d.isConfirmed) {
        const host = window.location.host === "localhost" ? "/pharma" : "";
        $.post(
          `${window.location.origin}${host}/backend/nodes.php?action=delete_item`,
          {
            table: table,
            column: col,
            val: val,
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
        ).fail(function (e) {
          swal.fire({
            title: "Error!",
            html: e.statusText,
            icon: "error",
          });
        });
      }
    });
};
