<!-- <div class="row mb-3">
	<div class="col-xl-2 col-md-4 col-sm-6">
		<div class="card border-left-warning">
			<div class="card shadow">
				<div class="card-body">
				  <div class="row">
					<div class="col">
					  <h6 class="card-title text-uppercase text-muted mb-0">Employees</h6>
					  <span class="h4 font-weight-bold mb-0">250</span>
					</div>
					<div class="col-auto">
						<i class="fas fa-users" id="kpistyle"></i>
					</div>
				  </div>
				  <p class="mt-3 mb-0 text-muted text-sm">
					<span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
					<span class="text-nowrap">Since last month</span>
				  </p>
            	</div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6">
		<div class="card border-left-warning">
			<div class="card shadow">
				<div class="card-body">
				  <div class="row">
					<div class="col">
					  <h6 class="card-title text-uppercase text-muted mb-0">Present</h6>
					  <span class="h4 font-weight-bold mb-0">2,356</span>
					</div>
					<div class="col-auto">
					  <i class="fas fa-plus" id="kpistyle"></i>
					</div>
				  </div>
				  <p class="mt-3 mb-0 text-muted text-sm">
					<span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
					<span class="text-nowrap">Since last week</span>
				  </p>
            	</div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6">
		<div class="card border-left-warning">
			<div class="card shadow">
				<div class="card-body">
				  <div class="row">
					<div class="col">
					  <h6 class="card-title text-uppercase text-muted mb-0">Absent</h6>
					  <span class="h4 font-weight-bold mb-0">24</span>
					</div>
					<div class="col-auto">
					  <i class="fas fa-minus" id="kpistyle"></i>
					</div>
				  </div>
				  <p class="mt-3 mb-0 text-muted text-sm">
					<span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
					<span class="text-nowrap">Since yesterday</span>
				  </p>
            	</div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6">
		<div class="card border-left-warning">
			<div class="card shadow">
				<div class="card-body">
				  <div class="row">
					<div class="col">
					  <h6 class="card-title text-uppercase text-muted mb-0">Leaves</h6>
					  <span class="h4 font-weight-bold mb-0">49.65%</span>
					</div>
					<div class="col-auto">
					  <i class="fas fa-envelope" id="kpistyle"></i>
					</div>
				  </div>
				  <p class="mt-3 mb-0 text-muted text-sm">
					<span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
					<span class="text-nowrap">Since last month</span>
				  </p>
            	</div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6">
		<div class="card border-left-warning">
			<div class="card shadow">
				<div class="card-body">
				  <div class="row">
					<div class="col">
					  <h6 class="card-title text-uppercase text-muted mb-0">Travel</h6>
					  <span class="h4 font-weight-bold mb-0">5%</span>
					</div>
					<div class="col-auto">
					  <i class="fas fa-plane" id="kpistyle"></i>
					</div>
				  </div>
				  <p class="mt-3 mb-0 text-muted text-sm">
					<span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 2%</span>
					<span class="text-nowrap">Since last month</span>
				  </p>
            	</div>
			</div>
		</div>
	</div>
	<div class="col-xl-2 col-md-4 col-sm-6">
		<div class="card border-left-warning">
			<div class="card shadow">
				<div class="card-body">
				  <div class="row">
					<div class="col">
					  <h6 class="card-title text-uppercase text-muted mb-0">Specific Off</h6>
					  <span class="h4 font-weight-bold mb-0">5%</span>
					</div>
					<div class="col-auto">
					  <i class="fas fa-calendar-alt" id="kpistyle"></i>
					</div>
				  </div>
				  <p class="mt-3 mb-0 text-muted text-sm">
					<span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 2%</span>
					<span class="text-nowrap">Since last month</span>
				  </p>
            	</div>
			</div>
		</div>
	</div>
</div> -->

<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Leave and Attendance</h3>
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
							<a class="nav-link <?php if(!isset($_GET['opt'])){ echo 'active'; } ?>" id="request-tab" data-toggle="tab" href="#request" role="tab" aria-controls="request" aria-selected="false">Leave Request</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if(isset($_GET['opt']) && !empty($_GET['opt']) && $_GET['opt'] == 'history'){ echo 'active'; } ?>" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="true">Leave History</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if(isset($_GET['opt']) && !empty($_GET['opt']) && $_GET['opt'] == 'approval'){ echo 'active'; } ?>" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval" aria-selected="false">
								Leave Approval
								<span id="pendingcounter" style="
											min-width: 7px;
											border-radius: 10px;
											padding: 1px 4px;
											text-align: center;
											font-size: 12px;
											line-height: 12px;
											background-color: #e74a3b;
											vertical-align: super;
											color: white;
											">0</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if(isset($_GET['opt']) && !empty($_GET['opt']) && $_GET['opt'] == 'attendance'){ echo 'active'; } ?>" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab" aria-controls="attendance" aria-selected="false">Attendance</a>
						</li>
					</ul>
				</div>
				<div class="tab-content details-tab" id="myTabContent">
					<div class="tab-pane fade <?php if(!isset($_GET['opt'])){ echo 'show active'; } ?>" id="request" role="tabpanel" aria-labelledby="request-tab">
						<div class="row">
							<div class="col-md-5 col-sm-5 col-xs-12">
								<!-- <h3>Form</h3> -->
								<div class="card mb-3">
									<div class="card-body">
										<!-- <form> -->
											<div class="row">
												<div class="form-group col-lg-6">
													<label for="leavetype">Date From</label>
													<input id="txtDateFrom" class="form-control form-control-sm" type="text" readonly value="<?php echo formatDate('D d M Y',TODAY);?>">
												</div>
												<div class="form-group col-lg-6">
													<label for="leavetype">Date To</label>
													<input id="txtDateTo" class="form-control form-control-sm" type="text" readonly value="<?php echo formatDate('D d M Y',TODAY);?>">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-lg-4" id="divLeaveType">
													<label for="leavetype">Leave Type</label>
													<select id="txtLeaveType" class="form-control form-control-sm" >
													</select>
												</div>
												<div class="form-group col-lg-5" id="divLeaveDuration">
													<label for="leavetype">Leave Duration</label>
													<select id="txtLeaveDuration" class="form-control form-control-sm"></select>
												</div>
												<div class="form-group col-lg-3">
													<label for="leavetype"># Days</label>
													<input type="text" id="txtNoOfDays" name="txtNoOfDays" class="form-control form-control-sm text-center" value="1" readonly="" />
												</div>
											</div>
										  	<div class="mb-3">
												<label for="validationTextarea">Reason</label>
												<textarea id="txtLeaveReason" class="form-control" placeholder="Put reason here" required></textarea>
											</div>
											<div class="row" id="level1approver">
												<div class="form-group col-lg-12">
													<label for="approver">Level 1 Approver</label>
													<input id="txtApprover1" class="form-control form-control-sm" type="text" disabled />
												</div>
												<!-- <div class="form-group col-lg-12">
													<label for="leavetype">Approver</label>
													<span id="approvername"></span>
												</div> -->
											</div>
											<div class="row" id="level2approver">
												<div class="form-group col-lg-12">
													<label for="approver" id="withindirectapprv" style="display: none;">Level 2 Approver</label>
													<label for="approver" id="withoutindirectapprv">Approver</label>
													<input id="txtApprover" class="form-control form-control-sm" type="text" disabled />
												</div>
												<!-- <div class="form-group col-lg-12">
													<label for="leavetype">Approver</label>
													<span id="approvername"></span>
												</div> -->
											</div>

											<!-- <div class="row">
												<div class="form-group col-lg-12">
													<input type="file" class="form-control" id="customFile" placeholder="Add Attachment">
												</div>
											</div>
											<div class="custom-file">
												<input type="file" class="form-control" id="customFile">
												<label class="custom-file-label" for="customFile">Add Attachment</label>
											</div>  -->
											<div class="row" id="leaveattachment">
												<div class="form-group col-lg-12">
													<form action="controllers/leaveattachment_controller.php" method="post" id="upload_form">
														<!-- <div class="form-group"> -->
															<label for="uploadleavedocs">Leave Documents <small>(Optional)</small></label>
															<div class="input-group input-file" name="Fichier1">
																<!-- <input type="file" class="form-control form-control-sm" name="files[]" multiple>
																<span class="input-group-btn">
																	<button class="btn btn-warning btn-sm">Upload</button>
																</span> -->
																<!-- <div class="col-md-6"> -->
																	<input id="uploadattachement" type="file" class="form-control form-control-sm" name="files[]" multiple>
																<!-- </div> -->
																<!-- <div class="col-md-6"> -->
																	<button class="btn btn-primary btn-sm">Upload</button>
																<!-- </div> -->
																
															</div>

														<!-- </div> -->
													</form>
												</div>
											</div>
											<div class="row" id="output">

											</div>
											
											<!-- <form class="md-form" > -->
											  <!-- <div class="form-group"> -->
													<!-- <div class="input-group input-file" name="Fichier1"> -->
														<!-- <span class="input-group-btn"> -->
											        		<!-- <button class="btn btn-primary btn-sm btn-choose" type="button">Choose</button> -->
											    		<!-- </span> -->
											    		<!-- <input type="text" class="form-control form-control-sm" placeholder='Choose a file...' /> -->
											    		<!-- <span class="input-group-btn"> -->
											       			 <!-- <button class="btn btn-warning btn-sm" type="button">Upload</button> -->
											       			 <!-- <input type="submit" name="submit" class="btn btn-warning btn-sm submit" value="Upload"> -->
											    		<!-- </span> -->
													<!-- </div> -->
												<!-- </div> -->
											<!-- </form> -->
											<div class="row">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<button id="btnNewRequest" type="button" class="btn btn-success btn-sm" style="display: none;">New</button>
													<button id="btnUpdateRequest" type="button" class="btn btn-primary btn-sm" style="display: none;">Update</button>
													<button id="btnCancelRequest" type="button" class="btn btn-danger btn-sm" style="display: none;">Cancel</button>
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12 text-right">
													<button id="btnSubmit" type="button" class="btn btn-danger btn-sm">Submit</button>
												</div>
											</div>
										<!-- </form>	 -->
									</div>
								</div>
							</div>
							<!-- <div class="col-md-5 col-sm-5 col-xs-12">
								<h3>Leave Details</h3>
								<span id="divleavedtls">
								<table class="table table-sm table-bordered" width="100%" cellspacing="0">
									<thead class="thead-dark">
										<tr>
											<th width="30%">Date</th>
											<th width="30%">Durataion</th>
											<th width="40%">Remarks</th>
											<th width="40%">Exclude</th>
										</tr>
									</thead>  
									<tbody>
										<tr>
											<td><input type="text" id="LDTLDt[]" name="LDTLDt[]" value="<?php //echo formatDate('D d M Y',TODAY);?>" class="form-control form-control-sm" readonly /></td>
											<td><span id="divLDTLDuration"><select id="LDTLDuration[]" name="LDTLDuration[]" class="form-control form-control-sm">
												<option value="fdl">Full day leave</option>
												<option value="hdlfh">Half day - first half</option>
												<option value="hdlsh">Half day - second half</option>
											</select></span></td>
											<td><input type="text" id="LDTLRemarks[]" name="LDTLRemarks[]" value="" placeholder="Remarks here" class="form-control form-control-sm" /></td>
											<td>&nbsp;</td>
										</tr>
									</tbody> -->
									<input type="hidden" id="txtldtls" name="txtldtls" value="" /> 
									<input type="hidden" id="txtholidays" name="txtholidays" value="" /> 
								<!-- </table>
								</span>
							</div> -->
						    <div class="col-md-7 col-sm-7 col-xs-12">
						    	<div class="row">
						    		<div class="col-sm-12 col-md-6"><h3>Leave Credits</h3></div>
									<div id="leavecreditfilteryear_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer col-sm-12 col-md-6" style="text-align: right;"></div>
						    	</div>
								<div class="table-responsive" width="101%">
									<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="width:98%; table-layout:fixed;">
										<table id="leavecreditsdatatable" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
											<thead class="thead-dark">
												<tr style="cursor: pointer;">
													<th width="40%">Leave Type</th>
													<th width="15%">Entitled</th>
													<!-- <th width="17%">Carried Over</th> -->
													<th width="15%">Pending</th>
													<th width="15%">Taken</th>
													<th width="15%">Balance</th>
												</tr>
											</thead>  
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade <?php if(isset($_GET['opt']) && !empty($_GET['opt']) && $_GET['opt'] == 'history'){ echo 'show active'; } ?>" id="history" role="tabpanel" aria-labelledby="history-tab">
						<div class="col-md-12">
							<div class="table-responsive">
								<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="table-layout:fixed; width: 99% !important;">
									<table id="leavesdatatable" class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" width="100%" cellspacing="0">
										<thead class="thead-dark">
											<tr style="cursor: pointer;">
												<th width="10%">Name</th>
												<th width="10%">Request Date</th>
												<th width="10%">Leave Type</th>
												<th width="10%">Leave From</th>
												<th width="10%">Leave To</th>
												<th width="5%">Length</th>
												<th width="18%">Reason</th>
												<th width="12%">Approved by</th>
												<th width="15%">Status</th>
											</tr>
										</thead>  
										<tbody style="font-size: 13px">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade <?php if(isset($_GET['opt']) && !empty($_GET['opt']) && $_GET['opt'] == 'approval'){ echo 'show active'; } ?>" id="approval" role="tabpanel" aria-labelledby="approval-tab">
						<div class="col-md-12">
							<div class="table-responsive" style="width: 101%;"> 
								<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="table-layout:fixed; width: 99% !important;">
									<table id="pendingleavesdatatable" class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" width="100%" cellspacing="0">
										<thead class="thead-dark">
											<tr style="cursor: pointer;">
												<th width="10%">Request Date</th>
												<th width="10%">Name</th>
												<th width="10%">Request Type</th>
												<th width="10%">Leave From</th>
												<th width="10%">Leave To</th>
												<th width="5%">Length</th>
												<th width="18%">Reason</th>
												<th width="12%">Status</th>
												<th width="15%">Actions</th>
											</tr>
										</thead>  
										<tbody style="font-size: 13px">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade <?php if(isset($_GET['opt']) && !empty($_GET['opt']) && $_GET['opt'] == 'attendance'){ echo 'show active'; } ?>" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
						<?php include_once('attendance/main.php'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" />
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
<input type="hidden" id="eeid" name="eeid" value="<?php echo $userid; ?>" />
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" />
<input type="hidden" id="sesid" name="sesid" value="" />
<input type="hidden" id="leaveid" name="leaveid" value="" />
<input type="hidden" id="stat" name="stat" value="" />
<input type="hidden" id="loaded" name="loaded" value="0" />
<input type="hidden" id="loadee" name="loadee" value="0" />
<input type="hidden" id="leavebal" name="leavebal" value="0" />
<input type="hidden" id="leavepending" name="leavepending" value="0" />
<input type="hidden" id="history" name="history" value="0" />
<input type="hidden" id="hasaccess" name="hasaccess" value="<?php echo hasAccess(1); ?>" />
<input type="hidden" id="allhistory" name="allhistory" value="<?php echo hasAccess(2); ?>" />
<input type="hidden" id="localhr" name="localhr" value="<?php echo hasAccess(0); ?>" />
<input type="hidden" id="ofconly" name="ofconly" value="<?php echo officeOnly(); ?>" />
<input type="hidden" id="eejt" name="eejt" value="<?php echo $eejt; ?>" />
<input type="hidden" id="dept" name="dept" value="<?php echo $dept; ?>" />
<input type="hidden" id="rank" name="rank" value="<?php echo $rank; ?>" />
<input type="hidden" id="pos" name="pos" value="<?php echo $pos; ?>" />
<input type="hidden" id="ee" name="ee" value="<?php echo $userid; ?>" />
<input type="hidden" id="defleavetype" name="defleavetype" value="<?php echo 'AL'.date("y"); ?>" />
<input type="hidden" id="curdt" name="curdt" value="<?php echo formatDate('D d M Y',TODAY); ?>" />
<input type="hidden" id="menuid_leave" name="menuid_leave" value="<?php echo $menuid_leave; ?>" disabled readonly/>
<input type="hidden" id="menuid_attendance" name="menuid_attendance" value="<?php echo $menuid_attendance; ?>" disabled readonly/>


<input type="hidden" id="prevdtfrom" name="history" value="" />
<input type="hidden" id="prevdtto" name="history" value="" />
<!-- <input type="" id="prevleavetype" name="history" value="" /> -->
<input type="hidden" id="prevleaveduration" name="history" value="" />
<!-- <input type="" id="prevnoofdays" name="history" value="" /> -->
<!-- <input type="" id="prevleavereason" name="history" value="" /> -->


<?php 
	// include_once('views/payslip.php');
	include_once('views/leave-request-modal.php');
	include_once('views/leave-history-modal.php');
	// print_r($useraccess);
?>


