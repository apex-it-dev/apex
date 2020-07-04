<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-8"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Review Attendance<span id="period"></span></h3>
			 </div>
			 <div class="col-md-4"> 
				 <span style="color: red"><small>*Note: For testing purposes, select dates according cut off period. </small></span>
				 <span style="color: red"><small>*If period is 1, then cut dates should be bet 26-10 </small></span><br>
				 <span style="color: red"><small>*If period is 2, then cut dates should be bet 11-25 </small></span>
			 </div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="col-md-12"> 	
			<div class="card mb-3">
				<div class="card-body">
					<div class="row">
						<div class="col-md-2 col-sm-2 col-xs-12" id="dataofclist">
							<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Period</span>
								</div>
								<select id="txtperiod" name="txtperiod" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
									<option value="1">1</option>
									<option value="2">2</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12" id="forLblLogFrom" >
							<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Date From</span>
								</div>
								<input id="txtLogFrom" type="text" class="form-control form-control-sm " readonly="" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12" id="forLblLogTo" >
							<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Date To</span>
								</div>
								<input id="txtLogTo" type="text" class="form-control form-control-sm " readonly="" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
							</div>
						</div>
						
						<div class="col-md-3 col-sm-3 col-xs-12" id="forbtnretrieve">
							<input type="button" id="btnRetrieve" value=" RETRIEVE " class="btn btn-grad btn-danger btn-sm">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12"> 	
			<div class="card mb-3">
				<div class="card-body">
					<div class="table-responsive">
						<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="table-layout:fixed; width: 99% !important;">
							<table id="attendancereviewtbl" class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr style="cursor: pointer;">
										<th width="40%">Name</th>
										<th width="20%">Total Working Days</th>
										<th width="20%">Total Days Present</th>
										<th width="20%">No of Days Absent</th>
										<!-- <th width="15%">Total Mins Late</th> -->
									</tr>
								</thead>  
								<tbody style="font-size: 13px">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="row pr-3 mt-1">
						<div class="col-lg-12 col-md-12 col-sm-12" id="forbtnretrieve">
							<input title="Retrieve" type="button" id="btnProceedToPayroll" value="  Proceed to Payroll  " class="btn btn-grad btn-danger btn-sm float-right">
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
<input type="hidden" id="hasaccess" name="hasaccess" value="<?php echo hasAccess(1); ?>" />
<?php 
	include_once('views/payroll/attendancedetails.php');
?>