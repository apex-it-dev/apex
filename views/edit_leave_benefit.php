<div class="modal fade bd-example-modal-lg" role="dialog" aria-hidden="true" id="editLeaveBenefit">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle">Leave Benefit</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
        <form>
          <div class="form-group">
            <label for="position" class="col-form-label">Leave Type:</label>
            <span id="dataleavetype"></span>
          </div>
          <div class="form-group">
            <label for="txtEntitled" class="col-form-label">Entitled:</label>
            <input type="number" class="form-control" id="txtEntitled" min="0" step="0.5">
          </div>
          <div class="form-group">
            <label for="txtStatus" class="col-form-label">Status:</label>
            <select id="txtStatus" name="txtStatus" class="form-control">
              <option value="1">Enable</option>
              <option value="0">Disable</option>
            </select>
          </div>
    		  <input type="hidden" name="leavetypeid" id="leavetypeid" value="" readonly/>
    		  <input type="hidden" name="leaveid" id="leaveid" value="" readonly/>
    		  <input type="hidden" name="creditini" id="creditini" value="" readonly/>
    		  <input type="hidden" name="fiscalyr" id="fiscalyr" value="" readonly/>
    		  <input type="hidden" name="isexist" id="isexist" value="" readonly/>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="bntSaveLeaveBen">Save Changes</button>
      </div>
    </div>
  </div>
</div>
