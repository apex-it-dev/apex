<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="LeaveHistModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-top: 10px solid #e02d1b;">
		<div class="modal-body"> 
			<h5><center>Leave History</center></h5>
      		<div class="row mb-3"> 
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
				   <h5>Attachments</h5>
				   <div class="row" id="viewattachments">
				   		<span style="padding-left:20px;">No attachment</span>
				   </div>

			   </div>
			   <div class="col-md-5">
					<h5>&nbsp;</h5> 
					<div id="trIndirect" style="padding-bottom:30px;">
						<div class="row">
							<div class="col-md-12">
								<span id="txtLabelIndirect" class="font-weight-normal">Indirect comments</span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<span id="txtCommentsIndirect" class="font-weight-bold" style="padding-left:20px;"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<span id="txtLabelDirect" class="font-weight-normal">Direct comments</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<span id="txtCommentsDirect" class="font-weight-bold" style="padding-left:20px;"></span>
						</div>
					</div>
			   </div>
		   </div>
			<?php if(isset($useraccess[$menuid_leave]['canrecallleaves'])){?>
				<div class="row" id="recall-container">
					<!-- js -->
				</div>
			<?php } ?>
		   <!-- <div class="modal-footer" style="height:40px"> -->
				<!-- <button type="button" class="btn btn-sm btn-primary text-center" id="btnRecallLeave" disabled>RECALL</button> -->
				<!-- <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">CLOSE</button> -->
			<!-- </div> -->
	  </div>
    </div>
  </div>
</div>
