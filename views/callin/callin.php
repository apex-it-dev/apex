<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Call In</h3>
			 </div>
			 <div class="col-md-10"> 
				
			 </div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="row"> 
			<div class="col-md-5 col-sm-5 col-xs-12">
				<div class="card mb-3">
					<div class="card-body">
						<div class="row">
							<label for="leavetype">Select call-in type:</label>
						</div>	
						<div class="row">
							<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="opttardiness" name="callintype" value="trd" checked>
								<label class="custom-control-label radio-inline" for="opttardiness">Tardiness</label>
							</div>
							&nbsp;
							<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="optabsences" name="callintype" value="abs">
								<label class="custom-control-label radio-inline" for="optabsences">Absence</label>
							</div>
						</div>
						&nbsp;
						<div class="row" id="tardigroup">
	                   		<div class="form-group col-lg-12">
								<label>Estimated time of arrival <span style="color:red">*</span></label>
								<select name="txtETA" id="txtETA" class="form-control form-control-sm">
									<option value="00:00" disabled selected>Select time...</option>
								</select>
							</div>
						</div>
						<div id="abscencesgroup" style="display: none;">
							<div class="row">
								<div class="form-group col-lg-4" id="divLeaveType">
									<label for="leavetype">Absent Type <span style="color:red">*</span></label>
									<select id="txtAbsentType" class="form-control form-control-sm" >
									</select>
								</div>
								<div class="form-group col-lg-4" id="divLeaveDuration">
									<label for="leavetype">Duration <span style="color:red">*</span></label>
									<select id="txtLeaveDuration" class="form-control form-control-sm"></select>
								</div>
								<div class="form-group col-lg-4" id="divLeaveDuration">
									<label for="leavetype">Leave Balance</label>
									<input type="text" id="txtLeaveBalance" name="txtLeaveBalance" class="form-control form-control-sm text-center" value="" readonly="" />
								</div>
							</div>
							<div class="row" id="level1approver">
								<div class="form-group col-lg-12">
									<label for="approver">Level 1 Approver</label>
									<input id="txtApprover1" class="form-control form-control-sm" type="text" disabled />
								</div>
							</div>
							<div class="row" id="level2approver">
								<div class="form-group col-lg-12">
									<label for="approver" id="withindirectapprv" style="display: none;">Level 2 Approver</label>
									<label for="approver" id="withoutindirectapprv">Approver</label>
									<input id="txtApprover" class="form-control form-control-sm" type="text" disabled />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="form-group col-lg-12">
								<label>Reason <span style="color:red">*</span></label>
								<textarea id="txtCallInReason" class="form-control" placeholder="Put reason here" required></textarea>
							</div>
						</div>
						<div class="row text-right">
							<div class="col-md-8 col-sm-8 col-xs-12">
								<button id="btnNewRequest" type="button" class="btn btn-success btn-sm" style="display: none;">New</button>
								<button id="btnUpdateRequest" type="button" class="btn btn-primary btn-sm" style="display: none;">Update</button>
								<button id="btnCancelRequest" type="button" class="btn btn-danger btn-sm" style="display: none;">Cancel</button>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 text-right">
								<button id="btnSubmit" type="button" class="btn btn-danger btn-sm">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-7 col-sm-7 col-xs-12">
		    	<div class="row">
		    		<div class="col-sm-12 col-md-6"><h3>Call In History</h3></div>
					<!-- <div id="leavecreditfilteryear_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer col-sm-12 col-md-6" style="text-align: right;"></div> -->
		    	</div>
				<div class="table-responsive" width="101%">
					<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="width:98%; table-layout:fixed;">
						<table id="callinhistorydatatable" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
							<thead class="thead-dark">
								<tr style="cursor: pointer;">
									<th width="15%">Date</th>
									<th width="15%">Call in type</th>
									<th width="35%">Reason</th>
									<th width="15%">ETA</th>
									<th width="15%">Status</th>
								</tr>
							</thead>  
							<tbody style="font-size: 15px">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly/>
<input type="hidden" id="sesid" name="sesid" value="" readonly/>
<input type="hidden" id="holidaygroup" name="holidaygroup" value="" readonly/>
<input type="hidden" id="allofc" name="allofc" value="<?php echo hasAccess(2); ?>" readonly/>
<input type="hidden" id="txtldtls" name="txtldtls" value="" readonly/>
<input type="hidden" id="txtNoOfDays" name="txtNoOfDays" value="" readonly/>
<input type="hidden" id="txtDateFrom" name="txtDateFrom" value="<?php echo date("Y-m-d");?>" readonly/>
<input type="hidden" id="txtDateTo" name="txtDateTo" value="<?php echo date("Y-m-d");?>" readonly/>