<?php
$medicineDivID = "editMed$medicine->medicine_id";
$medId = $medicine->medicine_id;
?>
<div class="modal fade" id="<?= $medicineDivID ?>" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-secondary">
          Update Medicine: <?= $medicine->code ?>
        </h5>
        <button type="button" class="close" onclick="closeModal('#<?= $medicineDivID ?>')">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="POST" id="<?= $medicineDivID ?>Form" enctype="multipart/form-data">
          <input type="text" value="edit" name="action" hidden readonly>
          <input type="text" value="<?= $medicine->medicine_id ?>" name="medicine_id" hidden readonly>

          <input type="text" value="No" name="isCleared" id="isCleared<?= $medId ?>" hidden readonly>
          <div class="row">

            <div class="col-md-12 mb-1">
              <div class="form-group">
                <img src="<?= getMedicineImage($medicine->medicine_id) ?>" class="rounded mx-auto d-block" style="width: 200px; height: 200px;" id="modalEdit-display<?= $medId ?>">
              </div>
              <div class="mt-3" style="display: flex; justify-content: center;" id="modalEdit-browse<?= $medId ?>">
                <button type="button" class="btn btn-primary btn-sm" onclick="return changeImage('#formInput-edit<?= $medId ?>', '<?= $medId ?>')">
                  Browse
                </button>
              </div>
              <div class="mt-3" style="display: flex; justify-content: center;" id="modalEdit-clear<?= $medId ?>">
                <button type="button" class="btn btn-danger btn-sm" onclick="return clearImg('#modalEdit-display<?= $medId ?>', '#modalEdit-clear<?= $medId ?>', '#modalEdit-browse<?= $medId ?>', '<?= $medId ?>')">
                  Clear
                </button>
              </div>
              <div class="mt-3" style="display: none;">
                <input class="form-control form-control-sm" type="file" accept="image/*" onchange="return previewFile(this, '#modalEdit-display<?= $medId ?>', '#modalEdit-clear<?= $medId ?>', '#modalEdit-browse<?= $medId ?>')" id="formInput-edit<?= $medId ?>" name="medicine_img">
              </div>
            </div>

            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Medicine Type <span class="text-danger">*</span></label>
                <select id="type<?= $medId ?>" class="choices form-select" required>
                  <option value="" disabled>Select Medicine Type</option>
                  <?php
                  $medicineTypes = getTableData("medicine_types", "status", "active");
                  foreach ($medicineTypes as $medicineType) :
                    $selectedType = isSelected($medicineType->type_id, $medicine->type_id);
                  ?>
                    <option value="<?= $medicineType->type_id ?>" <?= $selectedType ?>>
                      <?= $medicineType->name ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Manufacturers <span class="text-danger">*</span></label>
                <select id="man<?= $medId ?>" class="choices form-select" required>
                  <option value="" disabled>Select Manufacturer</option>
                  <?php
                  $manufacturerTypes = getTableData("manufacturers", "status", "active");
                  foreach ($manufacturerTypes as $manufacturer) :
                    $selectedMan = isSelected($manufacturer->manufacturer_id, $medicine->manufacturer_id);
                  ?>
                    <option value="<?= $manufacturer->manufacturer_id ?>" <?= $selectedMan ?>>
                      <?= $manufacturer->name ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="col-md-12 mb-1">
              <div class="form-group">
                <label>Therapeutic Classification <span class="text-danger">*</span></label>
                <input type="text" name="classification" class="form-control" value="<?= $medicine->classification ?>" required>
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
                <input type="text" name="brand_name" class="form-control" value="<?= $medicine->brand_name ?>" required>
              </div>
            </div>

            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Dose <span class="text-danger">*</span></label>
                <input type="text" name="dose" class="form-control" value="<?= $medicine->dose ?>" required>
              </div>
            </div>
            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Price <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" value="<?= $medicine->price ?>" required>
              </div>
            </div>

            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" value="<?= $medicine->quantity ?>" required readonly>
              </div>
            </div>
            <div class="col-md-6 mb-1">
              <div class="form-group">
                <label>Expiration <span class="text-danger">*</span></label>
                <input type="date" name="expiration" class="form-control" min="<?= date("Y-m-d") ?>" value="<?= $medicine->expiration ?>" required>
              </div>
            </div>

            <div class="col-md-12 mb-1">
              <div class="form-group">
                <label>Medicine Description</label>
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