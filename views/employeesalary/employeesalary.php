<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
		<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Employee Salary</h3>
			 </div>
			 <div class="col-md-10"> 
				
			 </div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="row">
			<div class="col-md-4">
				<div class="table-responsive" width="101%">
					<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
						<table id="employeelistviewtbl" class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" width="100%" cellspacing="0">
							<thead class="thead-dark">
								<tr style="cursor: pointer;">
									<th width="35%">Employee Name</th>
									
								</tr>
							</thead>  
							<tbody style="font-size: 15px">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="table-responsive" width="101%">
					<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
						<table id="sssdeductionviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
							<thead class="thead-dark">
								<tr style="cursor: pointer;">
									<th width="30%">Basic Pay</th>
									<th width="30%">De Minimis</th>
									<th width="30%">Effectivity Date</th>
									<th width="10%">Status</th>
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
<input type="hidden" id="accesslvl" name="accesslvl" value="<?php echo hasAccess(1); ?>" readonly disabled />

