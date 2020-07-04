<div class="modal fade shadow-lg" data-backdrop="static" tabindex="-2000" role="dialog" id="markaspaid">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row ml-2 mr-2">
          <label for="amounttobepaid">Amount to be paid</label>
          <input type="text" class="form-control text-right" id="amounttobepaid" placeholder="0.00">
          
          <input type="hidden" id="amounttmp" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" id="btnMarkPaid">Mark as paid</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>