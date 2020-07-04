<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="CallIn">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		<div class="modal-body"> 
			<h5><center>Call In</center></h5>
      		<div class="row">
				<div class="form-group col-sm-6">
					<label for="leavetype">Date From</label>
					<input id="txtDateFrom" class="form-control form-control-sm" type="text" readonly value="<?php echo formatDate('D d M Y',TODAY);?>">
				</div>
				<div class="form-group col-sm-6">
					<label for="leavetype">Time</label>
					<input id="txtDateTo" class="form-control form-control-sm" type="text" readonly value="<?php echo formatDate('D d M Y',TODAY);?>">
				</div>
			</div>
			<div class="mb-3">
				<label for="validationTextarea">Reason</label>
				<textarea id="txtLeaveReason" class="form-control" placeholder="Put reason here" required></textarea>
			</div>
			<div class="row">
				<div class="form-group col-sm-4" id="divLeaveType">
					<label for="leavetype">Leave Type</label>
					<select id="txtLeaveType" class="form-control form-control-sm" >
					</select>
				</div>
				<div class="form-group col-sm-5" id="divLeaveDuration">
					<label for="leavetype">Leave Duration</label>
					<select id="txtLeaveDuration" class="form-control form-control-sm"></select>
				</div>
				<div class="form-group col-sm-3">
					<label for="leavetype"># Days</label>
					<input type="text" id="txtNoOfDays" name="txtNoOfDays" class="form-control form-control-sm text-center" value="1" readonly="" />
				</div>
			</div>
	  	</div>
    </div>
  </div>
</div>
