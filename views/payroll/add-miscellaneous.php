<div class="modal fade shadow-lg" data-backdrop="static" tabindex="-1" role="document" id="addmiscellaneous">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><span id="miscmodaltitle">Add Misceallaneous</span></h5>
        <input type="hidden" id="miscid" readonly>
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
              <label for="misctype" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Misceallaneous type</label>
              <div class="col-sm-8">
                <select class="form-control form-control-sm" id="misctype">
                  <option></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="miscdesc" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Description</label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="miscdesc">
              </div>
            </div>
            <div class="form-group row">
              <label for="miscAmount" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Amount</label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm text-sm-right" id="miscAmount" placeholder="0.00">
              </div>
            </div>
            <div class="form-group row">
              <label for="txtPeriodNo" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Period</label>
              <div class="col-sm-8">
                <select class="form-control form-control-sm" id="txtPeriodNo">
                <option value="1">1</option>
								<option value="2">2</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="txtpaydate" class="col-sm-4 col-form-label col-form-label-sm text-sm-left font-weight-bold">Pay date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" id="txtpaydate" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="saveBtn" style="min-width:6vw;">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width:6vw;">Cancel</button>
      </div>
    </div>
  </div>
</div>

