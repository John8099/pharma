<?php

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

function addSupplierModal()
{
  return ("
    <div class='modal fade' id='addSupplier' tabindex='-1' role='dialog' aria-labelledby='New Supplier' aria-hidden='true' data-backdrop='static' data-keyboard='false'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title text-secondary'>
              New Supplier
            </h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
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
