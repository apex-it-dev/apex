<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="workingdayItemModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle">Working Days</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
        <form autocomplete="off">
          <!-- <div class="container"> -->
            <div class="row">
              <div class="col-lg">
                <div class="form-group">
                  <label for="workingdaymodal_title" class="col-form-label">Title<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="workingdaymodal_title" name="workingdaymodal_title" maxlength="100">
                </div>
                <div class="form-group">
                  <label for="workingdaymodal_ofc" class="col-form-label">Office<span class="text-danger">*</span></label>
                  <select class="form-control" id="workingdaymodal_ofc" name="workingdaymodal_ofc">
                    
                  </select>
                </div>
                <div class="form-group">
                  <label for="workingdaymodal_startdate" class="col-form-label">Start Date<span class="text-danger">*</span></label>
                  <input id="workingdaymodal_startdate" class="form-control form-control-sm" type="text" value="" style="background-color:white;" readonly>
                </div>
                <div class="form-group">
                  <label for="workingdaymodal_enddate" class="col-form-label">End Date<span class="text-danger">*</span></label>
                  <input id="workingdaymodal_enddate" class="form-control form-control-sm" type="text" value="" style="background-color:white;" readonly>
                </div>
              </div>  
              <div class="col-lg">
                <div class="form-group">
                  <label for="workingdaymodal_region" class="col-form-label">Region<span class="text-danger">*</span></label>
                  <select class="form-control" id="workingdaymodal_region" name="workingdaymodal_region">
                    <option value="international">International</option>
                    <option value="national">National</option>
                    <option value="local">Local</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="workingdaymodal_desc" class="col-form-label">Description<span class="text-danger">*</span></label>
                  <textarea id="workingdaymodal_desc" class="form-control form-control-sm" value="" rows="8" style="resize:none;"></textarea>
                </div>
              </div>
            </div>
          <!-- </div> -->
        </form>
		  <input type="hidden" name="workingdayModalId" id="workingdayModalId" value="" readonly/>
		  <input type="hidden" name="workingdayUserId" id="workingdayUserId" value="" readonly/>
		  <input type="hidden" name="workingdayCreatedBy" id="workingdayCreatedBy" value="" readonly/>
		  <input type="hidden" name="workingdayCreatedDate" id="workingdayCreatedDate" value="" readonly/>
		  <input type="hidden" name="numberOfDaysOfWorkingday" id="numberOfDaysOfWorkingday" value="" readonly/>
      </div>
      <div class="modal-footer" id="workingday-footer">
        <button type="button" class="btn btn-danger mr-auto" id="btnWorkingdayDelete" disabled hidden>Delete</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="btnWorkingdayClick" disabled>Changes</button>
      </div>
    </div>
  </div>
</div>

<?php include_once('views/workingday/workingday-yesno.php');?>