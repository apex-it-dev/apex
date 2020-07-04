<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="editPorfileHistory">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle">Position History</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
        <form>
          <div class="form-group">
            <label for="position" class="col-form-label">Department:</label>
            <select class="form-control" id="department" name="department">
              
            </select>
          </div>
          <div class="form-group">
            <label for="position" class="col-form-label">Position:</label>
            <select class="form-control" id="position" name="position" disabled="disabled">
              
            </select>
          </div>
          <div class="form-group">
            <label for="rate" class="col-form-label">Rate:</label>
            <input type="text" class="form-control text-right" id="rate" name="rate" value="0.00" placeholder="0.00">
          </div>
          <div class="form-group">
            <label for="effectivedate" class="col-form-label">Start Date:</label>
            <input type="text" class="form-control" id="effectivedate" name="effectivedate" readonly>
          </div>
          <div class="form-group">
            <input type="checkbox" id="activepresent" name="activepresent" value="">
            <label for="activepresent"> Present</label>
          </div>
          <div class="form-group">
            <label for="enddate" class="col-form-label">End Date:</label>
            <input type="text" class="form-control" id="enddate" name="enddate" readonly>
          </div>
          <div class="form-group">
            <label for="remarks" class="col-form-label">Remarks:</label>
            <textarea type="text" class="form-control" id="remarks" name="remarks" rows="4"></textarea>
          </div>
          <input type="hidden" name="index_id" id="index_id" value="" readonly>
          <input type="hidden" name="user_id" id="user_id" value="" readonly>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary mr-auto" id="positionBtnDelete" style="min-width: 40px;">Delete</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 40px;">Cancel</button>
        <button type="button" class="btn btn-danger" onClick="return updatedPositionHistory();" id="positionBtnSave" disabled="true" style="min-width: 70px;">Save Changes</button>
      </div>
    </div>
  </div>
</div>
