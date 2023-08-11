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
        swal.showLoading();
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

window.changeImage = function (inputId, medId) {
  $(inputId).click();
  $(`#isCleared${medId}`).val("No");
};

window.clearImg = function (imgDisplayId, divClearId, divBrowseId, medId) {
  const host = window.location.host === "localhost" ? "/pharma" : "";
  $("input[type=file]").val("");
  $(imgDisplayId).attr("src", `${host}/public/medicine.png`);

  $(divClearId).addClass("d-none").removeClass("d-flex");
  $(divBrowseId).addClass("d-flex").removeClass("d-none");

  // $(divClearId).hide();
  // $(divBrowseId).show();

  $(`#isCleared${medId}`).val("Yes");
};

window.previewFile = function (input, imgDisplayId, divClearId, divBrowseId) {
  let file = $(input).get(0).files[0];

  if (file) {
    let reader = new FileReader();

    reader.onload = function () {
      $(imgDisplayId).attr("src", reader.result);
    };

    reader.readAsDataURL(file);

    $(divClearId).addClass("d-flex").removeClass("d-none");
    $(divBrowseId).addClass("d-none").removeClass("d-flex");
  }
};
