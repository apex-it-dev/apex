<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Manage Payroll</h3>
			 </div>
			 <div class="col-md-10"> 
				
			 </div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="col-md-12"> 
			<div class="profile-head">
				<ul class="nav nav-tabs" id="myTabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="payrollhistory-tab" data-toggle="tab" href="#payrollhistory" role="tab" aria-controls="payrollhistory-" aria-selected="false">Payroll History</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="reviewpayroll-tab" data-toggle="tab" href="#reviewpayroll" role="tab" aria-controls="reviewpayroll" aria-selected="true">Review Payroll</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="validatepayroll-tab" data-toggle="tab" href="#validatepayroll" role="tab" aria-controls="validatepayroll" aria-selected="true">Validate Payroll</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="approvepayroll-tab" data-toggle="tab" href="#approvepayroll" role="tab" aria-controls="approvepayroll" aria-selected="true">Approve Payroll</a>
					</li>
				</ul>
			</div>
			<div class="tab-content payrollhistory-tab" id="myTabContent">
				<div class="tab-pane fade show active" id="payrollhistory" role="tabpanel" aria-labelledby="payrollhistory-tab">
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<div id="custom_search">
								<div class="input-group mb-1 input-group-sm col-sm-12 col-lg-4 col-md-4 float-right">
									<div class="input-group-prepend">
										<span class="input-group-text" style="background-color: #5a5c69; color: white; min-width: 60px;">Search</span>
									</div>
									<input type="text" class="form-control" id="loan_ee_list_search" title="Search">
								</div>
							</div>
							<div id="office_filter">
								<div class="input-group mb-1 input-group-sm col-sm-12 col-lg-3 col-md-3 float-right">
									<div class="input-group-prepend">
										<span class="input-group-text" style="background-color: #5a5c69; color: white; min-width: 60px;">Office</span>
									</div>
									<select class="form-control" id="officeList" title="Office filter">
										<option selected>All offices</option>
									</select>
								</div>
							</div>
							<div class="table-responsive" width="101%">
								<!-- <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;"> -->
									<table id="payrollhistorytblview" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
										<thead class="thead-dark" style="font-size: 12px">
											<tr style="cursor: pointer;">
												
											</tr>
										</thead>  
										<tbody style="font-size: 12px">
										</tbody>
										
									</table>
								<!-- </div> -->
							</div>
						</div>
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