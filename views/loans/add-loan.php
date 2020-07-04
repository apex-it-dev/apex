<div class="modal fade shadow-lg" data-backdrop="static" tabindex="-1" role="document" id="addloan">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Employee Loan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 40%;overflow-y: auto;">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group row">
              <label for="eename" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Name</label>
              <div class="col-sm-8">
                <select class="form-control form-control-sm" id="eename">
                  <option></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="loantype" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Loan type</label>
              <div class="col-sm-8">
                <select class="form-control form-control-sm" id="loantype">
                  <option></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="paymentfreqadd" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Payment frequency</label>
              <div class="col-sm-8">
                <select class="form-control form-control-sm " id="paymentfreqadd">
                  <option></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="loanAmount" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Loan amount</label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm text-sm-right" id="loanAmount" placeholder="0.00">
              </div>
            </div>
            <div class="form-group row">
              <label for="amountDue" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Amount due per freq.</label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm text-sm-right" id="amountDue" placeholder="0.00">
              </div>
            </div>
            <div class="form-group row">
              <label for="startDate" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Start date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="startDate" value="<?php echo formatDate('D d M Y',TODAY);?>" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width:6vw;">Cancel</button>
        <button type="button" class="btn btn-danger" id="saveBtn" style="min-width:6vw;">Save</button>
      </div>
    </div>
  </div>
</div>