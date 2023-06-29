<div class="modal fade" id="previewCart<?= $medicine->medicine_id ?>" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-secondary">
          New Medicine
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="addMedForm" enctype="multipart/form-data">
        <div class="modal-body">
          <table id="class">
            <thead>
              <tr>
                <th>Image</th>
                <th>Generic name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
      </form>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Checkout</button>
      </div>
    </div>
  </div>
</div>