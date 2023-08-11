<?php
$medicineDivID = "editMed$medicine->id";
$medId = $medicine->id;
?>
<div class="modal fade" id="<?= $medicineDivID ?>" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-secondary">
          Update Medicine
        </h5>
        <button type="button" class="close" onclick="closeModal('#<?= $medicineDivID ?>')">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="POST" id="<?= $medicineDivID ?>Form" enctype="multipart/form-data">
          <input type="text" value="edit" name="action" hidden readonly>
          <input type="text" value="<?= $medicine->id ?>" name="medicine_id" hidden readonly>

          <input type="text" value="No" name="isCleared" id="isCleared<?= $medId ?>" hidden readonly>
          <div class="row">

            <div class="col-md-12 mb-1">
              <?php
              $imgPath = getMedicineImage($medicine->id);

              $explodedImgSrc = explode("/", $imgPath);
              $isDefaultImg = $explodedImgSrc[count($explodedImgSrc) - 2] == "public" ? true : false;

              $hideBrowseButtonLogic = $isDefaultImg ? "flex" : "none";
              $hideClearButtonLogic = $isDefaultImg ? "none" : "flex";
              ?>
              <div class="form-group">
                <img src="<?= $imgPath ?>" class="rounded mx-auto d-block" style="width: 150px; height: 150px;" id="modalEdit-display<?= $medId ?>">
              </div>
              <div class="mt-3 d-<?= $hideBrowseButtonLogic ?> justify-content-center" id="modalEdit-browse<?= $medId ?>">
                <button type="button" class="btn btn-primary btn-sm" onclick="return changeImage('#formInput-edit<?= $medId ?>', '<?= $medId ?>')">
                  Browse
                </button>
              </div>
              <div class="mt-3 d-<?= $hideClearButtonLogic ?> justify-content-center" id="modalEdit-clear<?= $medId ?>">
                <button type="button" class="btn btn-danger btn-sm" onclick="return clearImg('#modalEdit-display<?= $medId ?>', '#modalEdit-clear<?= $medId ?>', '#modalEdit-browse<?= $medId ?>', '<?= $medId ?>')">
                  Clear
                </button>
              </div>
              <div class="mt-3" style="display: none;">
                <input class="form-control form-control-sm" type="file" accept="image/*" onchange="return previewFile(this, '#modalEdit-display<?= $medId ?>', '#modalEdit-clear<?= $medId ?>', '#modalEdit-browse<?= $medId ?>')" id="formInput-edit<?= $medId ?>" name="medicine_img">
              </div>
            </div>

            <div class="col-md-12 mb-1">
              <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= $medicine->medicine_name ?>" required>
              </div>
            </div>

            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label> Category <span class="text-danger">*</span> </label>

                <select class="selectpicker form-control" id="sel_cat<?= $medicine->id ?>" name="category_id" data-container="body" data-live-search="true" title="-- select category --" required>
                  <?php
                  $categoryData = getTableData("category");
                  foreach ($categoryData as $category) {
                    echo "<option value='$category->id' " . ($category->id == $medicine->category_id ? "selected" : "") . ">$category->category_name</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Dose <span class="text-danger">*</span></label>
                <input type="text" name="dose" class="form-control" value="<?= $medicine->dosage ?>" required>
              </div>
            </div>

            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Generic name <span class="text-danger">*</span></label>
                <input type="text" name="generic_name" class="form-control" value="<?= $medicine->generic_name ?>" required>
              </div>
            </div>
            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Brand name <span class="text-danger">*</span></label>
                <select name="brand_id" data-live-search="true" id="sel_brand<?= $medicine->id ?>" class="selectpicker form-control" title="-- select brand --" required>
                  <?php
                  $brandData = getTableData("brands");
                  foreach ($brandData as $brand) {
                    echo "<option value='$brand->id' " . ($brand->id == $medicine->brand_id ? "selected" : "") . ">$brand->brand_name</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-md-12 mb-1">
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="med_desc" rows="5"><?= nl2br($medicine->description) ?></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-warning" onclick="return handleSubmitEditMedicine('#<?= $medicineDivID ?>Form', '<?= $medId ?>')">Update</button>
        <button type="button" class="btn btn-danger" onclick="closeModal('#<?= $medicineDivID ?>')">Cancel</button>
      </div>
    </div>
  </div>
</div>