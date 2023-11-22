<?php

function addStockWithId($showClose = false)
{
  return ("
    <div class='modal fade' id='addStock' tabindex='-1' role='dialog' aria-labelledby='Add Stock' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered ' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              Add Stock
            </h5>
            " . ($showClose ? "<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span> </button>" : "") . "
          </div>
          <form id='addStockForm' method='POST'>
          <input type='text' name='returned_id' id='returnedId' hidden readonly>
            <div class='modal-body'>
              <div class='form-group '>
                <label class='form-label'>Medicine <small class='text-muted'>(Name/ Brand/ Generic)</small> <span class='text-danger'>*</span></label>
                <input type='text' name='medicine_id' id='medicineId' hidden readonly>
                <input type='text' id='medicineName' class='form-control' readonly>
              </div>

              <div class='form-group '>
                <label class='form-label'>Supplier Name <span class='text-danger'>*</span></label>
                <input type='text' name='supplier_id' id='supplierId' hidden readonly>
                <input type='text' id='supplierName' class='form-control' readonly>
              </div>

              <div class='form-group '>
                <div class='row'>
                  <div class='col-md-6'>
                    <label class='form-label'>Purchase Price <span class='text-danger'>*</span></label>
                    <input type='text' name='purchased_price' id='purchasePrice' step='.01' class='form-control' readonly>
                  </div>
                  <div class='col-md-6'>
                    <label class='form-label'>Markup %<span class='text-danger'>*</span></label>
                    <input type='number' name='mark_up' id='markUp' value='30' class='form-control' required>
                  </div>

                </div>
              </div>

              <div class='form-group '>
                <div class='row'>
                  <div class='col-md-6'>
                    <label class='form-label'>Price <span class='text-danger'>*</span></label>
                    <input type='number' id='price' name='price' step='.01' class='form-control' readonly required>
                  </div>
                  <div class='col-md-6'>
                    <label class='form-label'>Quantity <span class='text-danger'>*</span></label>
                    <input type='number' id='quantity' name='quantity' class='form-control' readonly required>
                  </div>

                </div>
              </div>

              <div class='form-group '>

                <div class='row'>
                  <div class='col-md-6'>
                    <label class='form-label'>Received Date <span class='text-danger'>*</span></label>
                    <input type='date' name='received_date' id='receiveDate' class='form-control' required>
                  </div>
                  <div class='col-md-6'>
                    <label class='form-label'>Expiration Date <span class='text-danger'>*</span></label>
                    <input type='date' name='expiration_date' min='" . (date('Y-m-d')) . "' class='form-control' required>
                  </div>

                </div>
              </div>

              <div class='form-group '>
                <label class='form-label'>Batch # <span class='text-danger'>*</span></label>
                <input type='text' name='serial_number' class='form-control' required>
              </div>

              <div class='row'>
                <div class='col-md-6'>
                  <div class='form-group row'>
                    <label class='col-6 col-form-label'>is Vatable</label>
                    <div class='col-3 p-0'>
                      <label class='switch'>
                        <input type='checkbox' name='isVatable' id='checkIsVatable' checked />
                        <span class='slider round'></span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class='col-md-6'>
                  <div class='form-group row'>
                    <label class='col-6 col-form-label'>is Discountable</label>
                    <div class='col-3 p-0'>
                      <label class='switch'>
                        <input type='checkbox' name='isDiscountable' id='checkIsDiscountable' checked />
                        <span class='slider round'></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary'>Save</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  ");
}

function addStockModal()
{
  $medicineData = getTableData('medicine_profile');

  $medicineOption = array();

  foreach ($medicineData as $medicine) {
    $brandData = getTableData('brands', 'id', $medicine->brand_id);
    $brand = '';
    if (count($brandData) > 0) {
      $brand = $brandData[0]->brand_name;
    }
    array_push(
      $medicineOption,
      "<option value='$medicine->id'>$medicine->medicine_name/ " . ($brand ? "$brand/ " : "") . "$medicine->generic_name ($medicine->dosage)</option>"
    );
  }

  $supplierData = getTableWithWhere('supplier', 'status = 1');

  $supplierOption = array();

  foreach ($supplierData as $supplier) {
    array_push($supplierOption, "<option value='$supplier->id'>$supplier->supplier_name</option>");
  }

  return ("
    <div class='modal fade' id='addStock' tabindex='-1' role='dialog' aria-labelledby='Add Stock' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              Add Stock
            </h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <form id='addStockForm' method='POST'>
            <div class='modal-body'>
              <div class='form-group '>
                <label class='form-label'>Medicine <small class='text-muted'>(Name/ Brand/ Generic)</small> <span class='text-danger'>*</span></label>
                <div class='row'>
                  <div class='col-10'>
                    <select name='medicine_id' id='select_med' data-live-search='true' class='selectpicker form-control' title='-- select medicine --' required>
                      " . (implode("", $medicineOption)) . "
                    </select>
                  </div>
                  <div class='col-2 d-flex justify-content-center align-items-center'>
                    <button id='btnAddMed' class='btn btn-sm btn-primary' type='button'>New</button>
                  </div>
                </div>
              </div>

              <div class='form-group '>
                <label class='form-label'>Supplier Name <span class='text-danger'>*</span></label>
                <div class='row'>
                  <div class='col-10'>
                    <select name='supplier_id' id='select_supp' data-live-search='true' class='selectpicker form-control' title='-- select supplier --' required>
                      " . (implode("", $supplierOption)) . "
                    </select>
                  </div>
                  <div class='col-2 d-flex justify-content-center align-items-center'>
                    <button id='btnAddSup' class='btn btn-sm btn-primary' type='button'>New</button>
                  </div>
                </div>
              </div>

              <div class='form-group '>

                <div class='row'>
                  <div class='col-md-6'>
                    <label class='form-label'>Price <span class='text-danger'>*</span></label>
                    <input type='number' name='price' step='.01' class='form-control' required>
                  </div>
                  <div class='col-md-6'>
                    <label class='form-label'>Quantity <span class='text-danger'>*</span></label>
                    <input type='number' name='quantity' class='form-control' required>
                  </div>

                </div>
              </div>

              <div class='form-group '>

                <div class='row'>
                  <div class='col-md-6'>
                    <label class='form-label'>Received Date <span class='text-danger'>*</span></label>
                    <input type='date' name='received_date' value='" . (date('Y-m-d')) . "' class='form-control' required>
                  </div>
                  <div class='col-md-6'>
                    <label class='form-label'>Expiration Date <span class='text-danger'>*</span></label>
                    <input type='date' name='expiration_date' min='" . (date('Y-m-d')) . "' class='form-control' required>
                  </div>

                </div>
              </div>

              <div class='form-group '>
                <label class='form-label'>Batch # <span class='text-danger'>*</span></label>
                <input type='text' name='serial_number' class='form-control' required>
              </div>

              <div class='row'>
                <div class='col-md-6'>
                  <div class='form-group row'>
                    <label class='col-6 col-form-label'>is Vatable</label>
                    <div class='col-3 p-0'>
                      <label class='switch'>
                        <input type='checkbox' name='isVatable' checked />
                        <span class='slider round'></span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class='col-md-6'>
                  <div class='form-group row'>
                    <label class='col-6 col-form-label'>is Discountable</label>
                    <div class='col-3 p-0'>
                      <label class='switch'>
                        <input type='checkbox' name='isDiscountable' checked />
                        <span class='slider round'></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary'>Save</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  ");
}

function addMedicineModal($closeMedFunc = null)
{
  $categoryOptions = array();

  $categoryData = getTableWithWhere('category', 'status=1');
  if (count($categoryData) > 0) {
    foreach ($categoryData as $category) {
      array_push($categoryOptions, "<option value='$category->id'>$category->category_name</option>");
    }
  }

  $brandOptions = array();
  $brandData = getTableWithWhere('brands', 'status=1');
  foreach ($brandData as $brand) {
    array_push($brandOptions, "<option value='$brand->id'>$brand->brand_name</option>");
  }

  return ("
    <div class='modal fade' id='addMed' tabindex='-1' role='dialog' aria-labelledby='New Medicine' aria-hidden='true' data-backdrop='static' data-keyboard='false' style='overflow-y: scroll;'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              New Medicine
            </h5>
            <button type='button' class='close' onclick='" . ($closeMedFunc ? $closeMedFunc : "closeModal(`#addMed`)") . "'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <form method='POST' id='addMedForm' enctype='multipart/form-data'>
            <input type='text' value='add' name='action' hidden readonly>
            <div class='modal-body'>
              <div class='row'>

                <div class='col-md-12 mb-1'>
                  <div class='form-group'>
                    <img src='" . (getMedicineImage()) . "' class='rounded mx-auto d-block' style='width: 150px; height: 150px;' id='modalAdd-display'>
                  </div>
                  <div class='mt-3' style='display: flex; justify-content: center;' id='modalAdd-browse'>
                    <button type='button' class='btn btn-primary btn-sm' onclick='return changeImage(`#formInput-add`)'>
                      Browse
                    </button>
                  </div>
                  <div class='mt-3' style='display: flex; justify-content: center;' id='modalAdd-clear'>
                    <button type='button' class='btn btn-danger btn-sm' onclick='return clearImg(`#modalAdd-display`, `#modalAdd-clear`, `#modalAdd-browse`)'>
                      Clear
                    </button>
                  </div>
                  <div class='mt-3' style='display: none;'>
                    <input class='form-control form-control-sm' type='file' accept='image/*' onchange='return previewFile(this, `#modalAdd-display`, `#modalAdd-clear`, `#modalAdd-browse`)' id='formInput-add' name='medicine_img'>
                  </div>
                </div>

                <div class='col-md-12 mb-1'>
                  <div class='form-group'>
                    <label>Name <span class='text-danger'>*</span></label>
                    <input type='text' name='name' class='form-control' required>
                  </div>
                </div>

                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label> New Therapeutic Category <span class='text-danger'>*</span> </label>
                    <button type='button' id='btnAddCategory' class='btn btn-sm btn-primary mr-0' style='float: right;'>New</button>

                    <select class='selectpicker form-control' name='category_id' id='select_category' data-container='body' data-live-search='true' title='-- select therapeutic category  --' required>
                      " . (implode("", $categoryOptions)) . "
                    </select>
                  </div>
                </div>
                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label>Dose <span class='text-danger'>*</span></label>
                    <input type='text' name='dose' class='form-control' required>
                  </div>
                </div>

                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label>Generic name <span class='text-danger'>*</span></label>
                    <input type='text' name='generic_name' class='form-control' required>
                  </div>
                </div>
                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label>Brand name <span class='text-danger'>*</span></label>
                    <button id='btnAddBrand' type='button' class='btn btn-sm btn-primary mr-0' style='float: right;'>New</button>
                    <select name='brand_id' id='select_brand' data-live-search='true' class='selectpicker form-control' title='-- select brand --' required>
                    " . (implode("", $brandOptions)) . "
                    </select>
                  </div>
                </div>

                <div class='col-md-12 mb-1'>
                  <div class='form-group'>
                    <label>Description</label>
                    <textarea class='form-control' name='med_desc' rows='5'></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class='modal-footer'>
              <button type='submit' id='btnAdd' class='btn btn-primary'>Submit</button>
              <button type='reset' class='btn btn-warning'>Reset</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  ");
}

function editMedicineModal($medicine)
{
  $medicineDivID = "editMed$medicine->id";
  $medId = $medicine->id;

  $categoryOptions = array();

  $categoryData = getTableWithWhere('category', 'status=1');
  if (count($categoryData) > 0) {
    foreach ($categoryData as $category) {
      array_push(
        $categoryOptions,
        "<option value='$category->id'" . ($category->id == $medicine->category_id ? 'selected' : '') . ">
        $category->category_name
        </option>"
      );
    }
  }

  $brandOptions = array();
  $brandData = getTableWithWhere('brands', 'status=1');
  foreach ($brandData as $brand) {
    array_push(
      $brandOptions,
      "<option value='$brand->id'" . ($brand->id == $medicine->brand_id ? 'selected' : '') . ">
      $brand->brand_name
      </option>"
    );
  }

  $imgPath = getMedicineImage($medicine->id);

  $explodedImgSrc = explode('/', $imgPath);
  $isDefaultImg = $explodedImgSrc[count($explodedImgSrc) - 2] == 'public' ? true : false;

  $hideBrowseButtonLogic = $isDefaultImg ? 'flex' : 'none';
  $hideClearButtonLogic = $isDefaultImg ? 'none' : 'flex';

  return ("
    <div class='modal fade' id='$medicineDivID' tabindex='-1' role='dialog' aria-labelledby='New Medicine' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              Update Medicine
            </h5>
            <button type='button' class='close' onclick='closeModal(`#$medicineDivID`)'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>

          <div class='modal-body'>
            <form method='POST' id='" . ($medicineDivID) . "Form' enctype='multipart/form-data'>
              <input type='text' value='edit' name='action' hidden readonly>
              <input type='text' value='$medicine->id' name='medicine_id' hidden readonly>

              <input type='text' value='No' name='isCleared' id='isCleared$medId' hidden readonly>
              <div class='row'>

                <div class='col-md-12 mb-1'>
                  <div class='form-group'>
                    <img src='$imgPath' class='rounded mx-auto d-block' style='width: 150px; height: 150px;' id='modalEdit-display$medId'>
                  </div>
                  <div class='mt-3 d-$hideBrowseButtonLogic justify-content-center' id='modalEdit-browse$medId'>
                    <button type='button' class='btn btn-primary btn-sm' onclick='return changeImage(`#formInput-edit$medId`, `$medId`)'>
                      Browse
                    </button>
                  </div>
                  <div class='mt-3 d-$hideClearButtonLogic justify-content-center' id='modalEdit-clear$medId'>
                    <button type='button' class='btn btn-danger btn-sm' onclick='return clearImg(`#modalEdit-display$medId`, `#modalEdit-clear$medId`, `#modalEdit-browse$medId`, `$medId`)'>
                      Clear
                    </button>
                  </div>
                  <div class='mt-3' style='display: none;'>
                    <input class='form-control form-control-sm' type='file' accept='image/*' onchange='return previewFile(this, `#modalEdit-display$medId`, `#modalEdit-clear$medId`, `#modalEdit-browse$medId`)' id='formInput-edit$medId' name='medicine_img'>
                  </div>
                </div>

                <div class='col-md-12 mb-1'>
                  <div class='form-group'>
                    <label>Name <span class='text-danger'>*</span></label>
                    <input type='text' name='name' class='form-control' value='$medicine->medicine_name' required>
                  </div>
                </div>

                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label> Category <span class='text-danger'>*</span> </label>

                    <select class='selectpicker form-control' id='sel_cat$medicine->id' name='category_id' data-container='body' data-live-search='true' title='-- select category --' required>
                      " . (implode("", $categoryOptions)) . "
                    </select>
                  </div>
                </div>
                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label>Dose <span class='text-danger'>*</span></label>
                    <input type='text' name='dose' class='form-control' value='$medicine->dosage' required>
                  </div>
                </div>

                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label>Generic name <span class='text-danger'>*</span></label>
                    <input type='text' name='generic_name' class='form-control' value='$medicine->generic_name' required>
                  </div>
                </div>
                <div class='col-md-6 mb-1'>
                  <div class='form-group'>
                    <label>Brand name <span class='text-danger'>*</span></label>
                    <select name='brand_id' data-live-search='true' id='sel_brand$medicine->id' class='selectpicker form-control' title='-- select brand --' required>
                    " . (implode("", $brandOptions)) . "
                    </select>
                  </div>
                </div>

                <div class='col-md-12 mb-1'>
                  <div class='form-group'>
                    <label>Description</label>
                    <textarea class='form-control' name='med_desc' rows='5'>" . (nl2br($medicine->description)) . "</textarea>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class='modal-footer'>
            <button type='button' class='btn btn-warning' onclick='return handleSubmitEditMedicine(`#" . ($medicineDivID) . "Form`, `$medId`)'>Update</button>
            <button type='button' class='btn btn-danger' onclick='closeModal(`#$medicineDivID`)'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  ");
}

function addBrandModal()
{
  return ("
        <div
        class='modal fade'
        id='addBrand'
        tabindex='-1'
        role='dialog'
        aria-labelledby='New Brand'
        aria-hidden='true'
        data-backdrop='static'
        data-keyboard='false'
      >
        <div class='modal-dialog modal-dialog-centered' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title text-secondary'>New Brand</h5>
              <button
                type='button'
                class='close'
                data-dismiss='modal'
                aria-label='Close'
              >
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <form id='addBrandForm' method='POST'>
              <input type='text' name='action' value='add' hidden readonly />
              <div class='modal-body'>
                <div class='form-group row'>
                  <label class='col-sm-3 col-form-label'
                    >Name <span class='text-danger'>*</span></label
                  >
                  <div class='col-sm-9'>
                    <input type='text' name='name' class='form-control' required />
                  </div>
                </div>

                <div class='form-group row'>
                  <label class='col-sm-3 col-form-label'>Description</label>
                  <div class='col-sm-9'>
                    <textarea
                      name='description'
                      class='form-control'
                      cols='30'
                      rows='10'
                    ></textarea>
                  </div>
                </div>

                <div class='form-group row'>
                  <label class='col-sm-3 col-form-label'>is Active</label>
                  <div class='col-sm-9'>
                    <label class='switch'>
                      <input type='checkbox' name='isActive' checked />
                      <span class='slider round'></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class='modal-footer'>
                <button type='submit' class='btn btn-primary'>Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>  
  ");
}

function editBrandModal($brand)
{
  return ("
    <div class='modal fade' id='editBrand$brand->id' tabindex='-1' role='dialog' aria-labelledby='Edit Brand' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              Edit Brand
            </h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <form method='POST'>
            <input type='text' name='action' value='edit' hidden readonly>
            <input type='text' name='brandId' value='$brand->id' hidden readonly>
            <div class='modal-body'>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Name <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='name' class='form-control' value='$brand->brand_name' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Description</label>
                <div class='col-sm-9'>
                  <textarea name='description' class='form-control' cols='30' rows='10'>" . (nl2br($brand->brand_description)) . "</textarea>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>is Active</label>
                <div class='col-sm-9'>
                  <label class='switch'>
                    <input type='checkbox' name='isActive' " . ($brand->status == '1' ? 'checked' : '') . ">
                    <span class='slider round'></span>
                  </label>
                </div>
              </div>

            </div>
            <div class='modal-footer'>
              <button type='button' onclick='handleEditBrand($(this))' class='btn btn-primary'>Save</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  ");
}

function addCategoryModal()
{
  return ("
    <div class='modal fade' id='addCategory' tabindex='-1' role='dialog' aria-labelledby='New Category' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
    <div class='modal-dialog modal-dialog-centered' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h5 class='modal-title text-secondary'>
            New Therapeutic Category
          </h5>
          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <form id='addCategoryForm' method='POST'>
          <input type='text' name='action' value='add' hidden readonly>
          <div class='modal-body'>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>Therapeutic Name<span class='text-danger'>*</span></label>
              <div class='col-sm-9'>
                <input type='text' name='name' class='form-control' required>
              </div>
            </div>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>Description</label>
              <div class='col-sm-9'>
                <textarea name='description' class='form-control' cols='30' rows='10'></textarea>
              </div>
            </div>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>Prescription Required</label>
              <div class='col-sm-9'>
                <label class='switch'>
                  <input type='checkbox' name='prescriptionRequired'>
                  <span class='slider round'></span>
                </label>
              </div>
            </div>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>is Active</label>
              <div class='col-sm-9'>
                <label class='switch'>
                  <input type='checkbox' name='isActive' checked>
                  <span class='slider round'></span>
                </label>
              </div>
            </div>

          </div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary'>Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  ");
}

function editCategoryModal($category)
{
  return ("
    <div class='modal fade' id='editCategory$category->id' tabindex='-1' role='dialog' aria-labelledby='New Category' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
    <div class='modal-dialog modal-dialog-centered' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h5 class='modal-title text-secondary'>
            Edit Therapeutic Category
          </h5>
          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <form method='POST'>
          <input type='text' name='action' value='edit' hidden readonly>
          <input type='text' name='categoryId' value='$category->id' hidden readonly>
          <div class='modal-body'>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>Therapeutic Name<span class='text-danger'>*</span></label>
              <div class='col-sm-9'>
                <input type='text' name='name' class='form-control' value='$category->category_name' required>
              </div>
            </div>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>Description</label>
              <div class='col-sm-9'>
                <textarea name='description' class='form-control' cols='30' rows='10'>" . (nl2br($category->description)) . "</textarea>
              </div>
            </div>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>Prescription Required</label>
              <div class='col-sm-9'>
                <label class='switch'>
                  <input type='checkbox' name='prescriptionRequired'  " . ($category->prescription_required == '1' ? 'checked' : '') . ">
                  <span class='slider round'></span>
                </label>
              </div>
            </div>

            <div class='form-group row'>
              <label class='col-sm-3 col-form-label'>is Active</label>
              <div class='col-sm-9'>
                <label class='switch'>
                  <input type='checkbox' name='isActive' " . ($category->status == '1' ? 'checked' : '') . ">
                  <span class='slider round'></span>
                </label>
              </div>
            </div>

          </div>
          <div class='modal-footer'>
            <button type='button' onclick='handleEditCategory($(this))' class='btn btn-primary'>Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  ");
}

function addSupplierModal($closeClick = null)
{
  return ("
    <div class='modal fade' id='addSupplier' tabindex='-1' role='dialog' aria-labelledby='New Supplier' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              New Supplier
            </h5>
            <button type='button' class='close' " . ($closeClick ? $closeClick : "data-dismiss='modal'") . " aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <form id='addSupplierForm' method='POST'>
            <input type='text' name='action' value='add' hidden readonly>
            <div class='modal-body'>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Name <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='name' class='form-control' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Address <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='address' class='form-control' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Contact <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='contact' class='form-control' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>is Active</label>
                <div class='col-sm-9'>
                  <label class='switch'>
                    <input type='checkbox' name='isActive' checked>
                    <span class='slider round'></span>
                  </label>
                </div>
              </div>

            </div>
            <div class='modal-footer'>
              <button type='submit' class='btn btn-primary'>Save</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  ");
}

function editSupplierModal($supplier)
{
  return ("
      <div class='modal fade' id='editMan$supplier->id' tabindex='-1' role='dialog' aria-labelledby='Edit Supplier' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              Edit Supplier
            </h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <form method='POST'>
            <input type='text' name='action' value='edit' hidden readonly>
            <input type='text' name='supplierId' value='$supplier->id' hidden readonly>
            <div class='modal-body'>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Name <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='name' value='$supplier->supplier_name' class='form-control' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Address <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='address' value='$supplier->address' class='form-control' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>Contact <span class='text-danger'>*</span></label>
                <div class='col-sm-9'>
                  <input type='text' name='contact' value='$supplier->contact' class='form-control' required>
                </div>
              </div>

              <div class='form-group row'>
                <label class='col-sm-3 col-form-label'>is Active</label>
                <div class='col-sm-9'>
                  <label class='switch'>
                    <input type='checkbox' name='isActive'  " . ($supplier->status == '1' ? 'checked' : '') . " >
                    <span class='slider round'></span>
                  </label>
                </div>
              </div>

            </div>
            <div class='modal-footer'>
              <button type='button' onclick='handleEditSupplier($(this))' class='btn btn-primary'>Save</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  
  ");
}
