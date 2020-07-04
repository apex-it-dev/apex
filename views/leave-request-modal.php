<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="LeaveReqApvl">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-body"> 
			<h5><center>Leave Request</center></h5>
      		<div class="row"> 
				<div class="col-md-7"> 
					<table class="table table-sm table-borderless">
					<h5>Leave Information</h5> 
					<tbody>
						<tr>
							<th class="font-weight-normal">Employee Name</th>
							<td class="font-weight-bold"><span id="dataeename"></span></td>
						</tr>
						<tr>
							<th class="font-weight-normal">Leave Type</th>
							<td class="font-weight-bold"><span id="dataleavetype"></span></td>
						</tr>
						<tr>
							<th class="font-weight-normal">Reason</th>
							<td class="reason font-weight-bold"><span id="datareason"></span></td>
						</tr>
						<!-- <tr> -->
							<!-- <th class="font-weight-normal">Status</th>
							<td class="font-weight-bold"><span id="datastatus"></span></td> -->
		<!--
							<td class="font-weight-bold">Level 2 Approval Pending</td>
							<td class="font-weight-bold">Level 3 Approval Pending</td>
		-->
						<!-- </tr> -->
						<tr>
							<th class="font-weight-normal">Leave From</th>
							<td class="font-weight-bold"><span id="dataleavefrom"></span></td>
						</tr>
						<tr>
							<th class="font-weight-normal">Leave To:</th>
							<td class="font-weight-bold"><span id="dataleaveto"></span></td>
						</tr>
						<tr>
							<th class="font-weight-normal">Leave Days</th>
							<td class="font-weight-bold"><span id="datanoofdays"></span></td>
						</tr>
						<tr>
							<th class="font-weight-normal">Request Date</th>
							<td class="font-weight-bold"><span id="datacreadt"></span></td>
						</tr>
					</tbody>
				   </table>
				  <!--  <table class="table table-sm table-borderless" >
					<h5>Attachments</h5> 
					<tbody id="attachmentview">
						<tr>
							<td></td>
						</tr>
						
					</tbody>
				   </table> -->
				   <h5>Attachments</h5>
				   <div class="row" id="viewattachments">
				   		<span style="padding-left:20px;">No attachment</span>
				   </div>

			   </div>
			   <div class="col-md-5">
	<!--		   <div class="row">-->
				   <label for="txtComments">Comments (Optional)</label>
	    		   <textarea class="form-control mb-2" id="txtComments" rows="8"></textarea>
	<!--			</div>-->
	<!--			<div class="row">-->
				    
					<button type="button" id="btnApprove" class="btn btn-danger btn-sm">Approve</button>
				    <button type="button" id="btnDispprove" class="btn btn-secondary btn-sm">Reject</button>
			    	
			    	
	<!--
					<input id="btnSaveChanges" type="button" class="btn btn-primary" value="Save">
					<input id="btnSaveChanges" type="button" class="btn btn-primary" value="Save">
	-->
	<!--			</div>-->
			   </div>
		   </div>
		   
	  </div>
    </div>
  </div>
</div>
