<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Compensation and Benefits</h3>
			 </div>
			<div class="col-md-2">
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row"> 
			<div class="col-md-12">  
				<div class="profile-head">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="leave-tab" data-toggle="tab" href="#leave" role="tab" aria-controls="leave" aria-selected="false">Leave</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab" aria-controls="attendance" aria-selected="true">Attendance</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="payroll-tab" data-toggle="tab" href="#payroll" role="tab" aria-controls="payroll" aria-selected="false">Payroll</a>
						</li>
					</ul>
				</div>
				<div class="tab-content details-tab" id="myTabContent">
					<div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
						<h3>Position History</h3>
						<div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th width="50%">Position</th>
										<th width="25%">Rate</th>
										<th width="25%">Tenure</th>
									</tr>
								</thead>  
								<tbody>
									<tr>
										<td>Client Servicing Executive</td>
										<td>$5,000 HKD/month</td>
										<td>2018-2019</td>
									</tr>
								</tbody>
							</table>
						</div>
						<h3>Benefits</h3>
						<div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th width="50%">Leave Type</th>
										<th width="25%">Entitled</th>
										<th width="25%">Taken</th>
									</tr>
								</thead>  
								<tbody>
									<tr>
										<td>Annual Leave</td>
										<td>20 day(s)</td>
										<td>10 day(s)</td>
									</tr>
									<tr>
										<td>Sick Leave</td>
										<td>10 day(s)</td>
										<td>1 day(s)</td>
									</tr>
									<tr>
										<td>Birthday-Paid Leave</td>
										<td>1 day(s)</td>
										<td>1 day(s)</td>
									</tr>
									<tr>
										<td>Compensation Leave</td>
										<td>2 day(s)</td>
										<td>1 day(s)</td>
									</tr>
									<tr>
										<td>Misc</td>
										<td>2 day(s)</td>
										<td>1 day(s)</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th width="50%">Health Insurance Coverage</th>
										<th width="25%">Out-patient</th>
										<th width="25%">In-patient</th>
									</tr>
								</thead>  
								<tbody>
									<tr>
										<td>Full Coverage</td>
										<td>$5,000 HKD</td>
										<td>$50,000 HKD</td>
									</tr>
								</tbody>
							</table>
						</div>
						<h3>Other Benefits</h3>
						</div>
					<div class="tab-pane fade" id="leave" role="tabpanel" aria-labelledby="leave-tab">
						<div class="row">
							<div class="col-md-6">
								<h3>Leave Request</h3>
								<div class="card mb-3">
									<div class="card-body">
										<form>
											<div class="row">
												<div class="form-group col-lg-6">
													<label for="leavetype">Date From</label>
													<input id="txtDateFrom" class="form-control form-control-sm" type="text" value="<?php echo formatDate('D d M Y',TODAY);?>">
												</div>
												<div class="form-group col-lg-6">
													<label for="leavetype">Date To</label>
													<input id="txtDateTo" class="form-control form-control-sm" type="text" value="<?php echo formatDate('D d M Y',TODAY);?>">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-lg-6" id="divLeaveType">
													<label for="leavetype">Leave Type</label>
													<select id="txtLeaveType" class="form-control form-control-sm" >
													</select>
												</div>
												<div class="form-group col-lg-6" id="divLeaveDuration">
													<label for="leavetype">Leave Duration</label>
													<select id="txtLeaveDuration" class="form-control form-control-sm">
													</select>
												</div>
											</div>
											<!-- <div class="row">
												<div class="form-group col-lg-6" id="divLeaveDuration">
													<label for="leavetype">Leave Period</label>
													<select id="txtLeavePeriod" class="form-control form-control-sm">
													</select>
												</div>
											</div> -->
										  	<div class="mb-3">
												<label for="validationTextarea">Reason</label>
												<textarea id="txtLeaveReason" class="form-control" placeholder="Put reason here" required></textarea>
											</div>
											<div class="custom-file">
												<input type="file" class="custom-file-input form" id="customFile">
												<label class="custom-file-label" for="customFile">Add Attachment</label>
											</div> <br> </br>
											<button id="btnSubmit" type="button" class="btn btn-primary btn-sm" >Submit</button>
										</form>	
									</div>
								</div>
							</div>
						    <div class="col-md-6">
								<h3>Leave Credits</h3>
								<div class="col-md-12"> 
									<table id="leavecreditsdatatable" class="table table-sm table-bordered" style="overflow-x: hidden;" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th width="15%">Leave Type</th>
										<th width="5%">Entitled</th>
										<th width="5%">Taken</th>
										<th width="5%">Balance</th>
									</tr>
								</thead>  
								<tbody>
									
								</tbody>
									</table>
								</div>
							</div>
						</div>
					<div class="col-md-12">
						<h3>Leave History</h3>
						<div class="table-responsive"> 
							<table id="leavesdatatable" class="table table-sm table-bordered" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th width="15%">Leave Type</th>
										<th width="15%">Leave From</th>
										<th width="15%">Leave To</th>
										<th width="5%">Length</th>
										<th width="40%">Reason</th>
										<th width="10%">Status</th>
									</tr>
								</thead>  
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
					</div>
					<div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab"> 
						<h3>Attendance</h3>
						<div class="table-responsive"> 
							<table class="table table-sm table-bordered" id="dataTableAttendance" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th>Sign-in</th>
										<th>Sign-out</th>
										<th>Approval Status</th>
									</tr>
								</thead> 
								<tbody>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
									<tr>
										<td>8:59:59 AM</td>
										<td>06:01:11 PM</td>
										<td>Approved</td>
									</tr>
								</tbody>
							</table>
					  </div>
				  </div>
				  <div class="tab-pane fade" id="payroll" role="tabpanel" aria-labelledby="payroll-tab">
					  <div class="row"> 
						  <div class="col-md-10"> </div>
						  <div class="col-md-2"> <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#frmpayslip" >Pay Slip</button> 
						  </div>
					  </div>
					  <div class="table-responsive"> 
					 	<table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
							<thead class="thead-dark">
								<tr>
									<th width="2%">
<!--
									<div class="custom-control custom-checkbox m-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck0" name="customCheck0">
										  <label class="custom-control-label" for="customCheck0"></label>
									</div>
-->
									</th>
									<th width="48%">Pay Period</th>
									<th width="50%">Net Income</th>
								</tr>
							</thead>  
							<tbody>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox m-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck1" name="customCheck1">
										  <label class="custom-control-label" for="customCheck1"></label>
									</div>
-->
									</td>
									<td>Mar-26 to Apr 10</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck2" name="customCheck2">
										  <label class="custom-control-label" for="customCheck2"></label>
									</div>
-->
									</td>
									<td>Apr 10 to Apr 26</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck3" name="customCheck3">
										  <label class="custom-control-label" for="customCheck3"></label>
									</div>
-->
									</td>
									<td>Apr 26 to May 10</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck4" name="customCheck4">
										  <label class="custom-control-label" for="customCheck4"></label>
									</div>
-->
									</td>
									<td>May 10 to May 26</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck5" name="customCheck5">
										  <label class="custom-control-label" for="customCheck5"></label>
									</div>
-->
									</td>
									<td>May 26 to June 10</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck6" name="customCheck6">
										  <label class="custom-control-label" for="customCheck6"></label>
									</div>
-->
									</td>
									<td>Jun 10 to Jun 26</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck7" name="customCheck7">
										  <label class="custom-control-label" for="customCheck7"></label>
									</div>
-->
									</td>
									<td>Jun 26 to Jul 10</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck8" name="customCheck8">
										  <label class="custom-control-label" for="customCheck8"></label>
									</div>
-->
									</td>
									<td>Jul 10 to Jul 26</td>
									<td>200,000</td>
								</tr>
								<tr>
									<td>
<!--
									<div class="custom-control custom-checkbox mb-3">
										  <input type="checkbox" class="custom-control-input" id="customCheck9" name="customCheck9">
										  <label class="custom-control-label" for="customCheck9"></label>
									</div>
-->
									</td>
									<td>Jul 10 to Jul 26</td>
									<td>200,000</td>
								</tr>				
							</tbody>
						</table>
					  </div>
				  </div>
				</div>
			</div>  
		</div> 
	</div>
</div>
<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" />
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" />
<input type="hidden" id="sesid" name="sesid" value="" />
<?php include_once('views/payslip.php');?>
<?php include_once('views/leave-request-modal.php');?>
