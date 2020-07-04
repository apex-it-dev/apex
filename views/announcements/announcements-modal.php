<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="holidayItemModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle">Holiday</h5>
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
                  <label for="holidaymodal_title" class="col-form-label">Title<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="holidaymodal_title" name="holidaymodal_title" maxlength="100">
                </div>
                <div class="form-group">
                  <label for="holidaymodal_ofc" class="col-form-label">Office<span class="text-danger">*</span></label>
                  <select class="form-control" id="holidaymodal_ofc" name="holidaymodal_ofc">
                    
                  </select>
                </div>
                <div class="form-group">
                  <label for="holidaymodal_startdate" class="col-form-label">Start Date<span class="text-danger">*</span></label>
                  <input id="holidaymodal_startdate" class="form-control form-control-sm" type="text" value="" style="background-color:white;" readonly>
                </div>
                <div class="form-group">
                  <label for="holidaymodal_enddate" class="col-form-label">End Date<span class="text-danger">*</span></label>
                  <input id="holidaymodal_enddate" class="form-control form-control-sm" type="text" value="" style="background-color:white;" readonly>
                </div>
              </div>  
              <div class="col-lg">
                <div class="form-group">
                  <label for="holidaymodal_region" class="col-form-label">Region<span class="text-danger">*</span></label>
                  <select class="form-control" id="holidaymodal_region" name="holidaymodal_region">
                    <option value="international">International</option>
                    <option value="national">National</option>
                    <option value="local">Local</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="holidaymodal_desc" class="col-form-label">Description<span class="text-danger">*</span></label>
                  <textarea id="holidaymodal_desc" class="form-control form-control-sm" value="" rows="8" style="resize:none;"></textarea>
                </div>
              </div>
            </div>
          <!-- </div> -->
        </form>
		  <input type="hidden" name="holidayModalId" id="holidayModalId" value="" readonly/>
		  <input type="hidden" name="holidayUserId" id="holidayUserId" value="" readonly/>
		  <input type="hidden" name="holidayCreatedBy" id="holidayCreatedBy" value="" readonly/>
		  <input type="hidden" name="holidayCreatedDate" id="holidayCreatedDate" value="" readonly/>
		  <input type="hidden" name="numberOfDaysOfHoliday" id="numberOfDaysOfHoliday" value="" readonly/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-auto" id="btnHolidayDelete" disabled hidden>Delete</button>
        <button type="button" class="btn btn-info" id="btnHolidayPublish">Publish</button>
        <!-- <input type="checkbox" id="btnHolidayPublish" data-toggle="toggle" data-on="Published" data-off="Unpublished" data-onstyle="success" data-offstyle="info" data-width="130px" data-height="38px" hidden> -->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="btnHolidayClick" disabled>Changes</button>
      </div>
    </div>
  </div>
</div>

<?php include_once('views/holidays/holiday-yesno.php');?>