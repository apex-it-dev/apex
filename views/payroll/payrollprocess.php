<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10 col-sm-10"> 
				<small><b><span id="logfm" hidden>Mon 9 Mar, 2020</span></b><b><span id="logto" hidden>Mon 9 Mar, 2020</span></b></small>
				<h3 class="m-0 ml-3 font-weight-bold text-gray-600">Process Payroll</h3>
			</div>
			<!-- <div class="col-md-2 col-md-2"> 
				<span style="float:right"><i class="fa fa-cog" aria-hidden="true"></i></span>
				<select class="pick-animation__select form-control">
					<option value="scaleIn">ScaleIn</option>
					<option value="scaleOut">ScaleOut</option>
					<option value="slideHorz selected="selected"">SlideHorz</option>
					<option value="slideVert">SlideVert</option>
					<option value="fadeIn">FadeIn</option>
				</select>
			</div> -->
		</div>
	</div>
	<div class="card-body"> 
		<div class="col-md-12">
			<div class="container" style="display:none">
				<!--content title-->
				<h2 class="content__title content__title--m-sm">Pick animation type</h2>
				<!-- animations form -->
				<form class="pick-animation my-4">
					<div class="form-row">
						<div class="col-5 m-auto">
							<select class="pick-animation__select form-control">
								<option value="scaleIn">ScaleIn</option>
								<option value="scaleOut">ScaleOut</option>
								<option value="slideHorz selected="selected"">SlideHorz</option>
								<option value="slideVert">SlideVert</option>
								<option value="fadeIn">FadeIn</option>
							</select>
						</div>
					</div>
				</form>
			</div>
			<!--multisteps-form-->
			<div class="multisteps-form">
				<!--progress bar-->
				<div class="row pt-2">
					<div class="col-md-12 ml-auto mr-auto mb-4 ">
						<div class="multisteps-form__progress">
							<button class="multisteps-form__progress-btn js-active" type="button" name="multibtn" name="multibtn"title="Order Info" >Employee Salary</button>
							<button class="multisteps-form__progress-btn" type="button" name="multibtn" title="Salary Adjustments">Salary Adjustment</button>
							<button class="multisteps-form__progress-btn" type="button" name="multibtn" title="Order Info">Government Deduction</button>
							<button class="multisteps-form__progress-btn" type="button" name="multibtn" title="Income Tax">Taxable Income</button>
							<button class="multisteps-form__progress-btn" type="button" name="multibtn" title="Loans">Loans</button>
							<button class="multisteps-form__progress-btn" type="button" name="multibtn" title="Miscellaneous">Miscellaneous</button>
							<button class="multisteps-form__progress-btn" type="button" name="multibtn" title="Review Payroll">Review Payroll</button>
						</div>
					</div>
				</div>
				<!--form panels-->
				<div class="col-md-12">
					<form class="multisteps-form__form">
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
							<h3 class="multisteps-form__title">Employee Salary(<span id = frequency></span>)</h3>
							<div class="multisteps-form__content">
								<div class="table-responsive"> 
										<table id="employeesalaryviewtbl" class="table table-sm table-bordered no-footer display nowrap" style="width: 100%">
											<thead class="thead-dark">
												<tr style="cursor: pointer;">
													<th width="25%">Name</th>
													<th width="12%"><span id="frequencybasicpay">Semi-Monthly Basic</span></th>
													<th width="12%"><span id="frequencydeminimis">Semi-Monthly Deminimis</th>
													<th width="12%"><span id="frequencyrate">Semi-Monthly Rate</span></th>
												</tr>
											</thead>  
											<tbody style="font-size: 15px">
												
											</tbody>
											<tfoot>
												<tr>
													<th colspan="3" style="text-align:right">Total:</th>
													<th><span id="ttlsalary"></span></th>	
												</tr>
											</tfoot>
										</table>
								</div>
								<div class="row">
									<div class="button-row d-flex mt-4 col-12">
										<button class="btn btn-primary ml-auto js-btn-next" type="button" title="btnSave" id="btnSaveSalary">Save and Continue</button>
									</div>
								</div>
							</div>
						</div>
						
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
							<div class="row">
								<div class="col-md-10">
									<h3 class="multisteps-form__title">Salary Adjusment</h3>
								</div>
								<div class="col-md-2">
									<button type="button" id="btnAddSalAdjustment" class="btn btn-grad btn-danger btn-sm" style="float: right" title="Add miscellaneous payments">
										<i class="fa fa-plus" aria-hidden="true"></i>
									</button>
								</div>
							</div>
							<div class="multisteps-form__content">
								<div class="table-responsive">
									<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
										<table id="salaryadjusmentsviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
											<thead class="thead-dark">
												<tr style="cursor: pointer;">
													<th width="35%">Name</th>
													<th width="15%">Adjustment Type</th>
													<th width="15%">Description</th>
													<th width="15%">Adjusment Amount</th>
													<th width="15%">Deminimis Amount</th>
													<th width="15%">Total Amount</th>
												</tr>
											</thead>  
											<tbody style="font-size: 15px">
												
											</tbody>
											<tfoot>
												<tr>
													<th colspan="5" style="text-align:right">Total:</th>
													<th></th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="button-row d-flex mt-4 col-12">
										<button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
										<button class="btn btn-primary ml-auto js-btn-next" type="button" title="btnSave" id="btnSaveSalaryAdjustment">Save and Continue</button>
									</div>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
							<div class="multisteps-form__content">
								<div class="col-md-12">  
									<div class="profile-head">
										<ul class="nav nav-tabs" id="myTabs" role="tablist">
											<li class="nav-item" id="ssstab">
												<a class="nav-link active" id="sssdeduction-tab" data-toggle="tab" href="#sssdeduction" role="tab" aria-controls="sssdeduction" aria-selected="false">SSS Deduction</a>
											</li>
											<li class="nav-item" id="philtab">
												<a class="nav-link" id="philhealthdeduction-tab" data-toggle="tab" href="#philhealthdeduction" role="tab" aria-controls="philhealthdeduction" aria-selected="true">PhilHealth Deduction</a>
											</li>
											<li class="nav-item" id="pagibigtab">
												<a class="nav-link" id="pagibigdeduction-tab" data-toggle="tab" href="#pagibigdeduction" role="tab" aria-controls="pagibigdeduction" aria-selected="true">PagIbig Deduction</a>
											</li>
											<li class="nav-item" id="summtab">
												<a class="nav-link" id="summarygovdeduction-tab" data-toggle="tab" href="#summarygovdeduction" role="tab" aria-controls="summarygovdeduction" aria-selected="true">Summary</a>
											</li>
										</ul>
									</div>

									<div class="tab-content sssdeduction-tab" id="myTabContent">
										<div class="tab-pane fade show active" id="sssdeduction" role="tabpanel" aria-labelledby="sssdeduction-tab">
											<div class="row">
												<h3 class="multisteps-form__title">SSS Deduction</h3>
												<div class="table-responsive" width="101%">
													<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
														<table id="sssdeductionviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
															<thead class="thead-dark">
																<tr style="cursor: pointer;">
																	<th width="35%">Name</th>
																	<th width="15%">Basic Pay</th>
																	<th width="15%">Employee Contribution</th>
																	<th width="15%">Employer Contribution</th>
																	<th width="15%">Total Contribution</th>
																</tr>
															</thead>  
															<tbody style="font-size: 15px">
																
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="4" style="text-align:right">Total:</th>
																	<th></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="philhealthdeduction" role="tabpanel" aria-labelledby="philhealthdeduction-tab">
											<div class="row">
												<h3 class="multisteps-form__title">PhilHealth Deduction</h3>
												<div class="table-responsive" width="101%">
													<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
														<table id="phildeductionviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
															<thead class="thead-dark">
																<tr style="cursor: pointer;">
																	<th width="35%">Name</th>
																	<th width="15%">Basic Pay</th>
																	<th width="15%">Employee Contribution</th>
																	<th width="15%">Employer Contribution</th>
																	<th width="15%">Total Contribution</th>
																</tr>
															</thead>  
															<tbody style="font-size: 15px">
																
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="4" style="text-align:right">Total:</th>
																	<th></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="pagibigdeduction" role="tabpanel" aria-labelledby="pagibigdeduction-tab">
											<div class="row">
												<h3 class="multisteps-form__title">PagIbig Deduction</h3>
												<div class="table-responsive" width="101%">
													<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
														<table id="pagibigdeductionviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
															<thead class="thead-dark">
																<tr style="cursor: pointer;">
																	<th width="35%">Name</th>
																	<th width="15%">Basic Pay</th>
																	<th width="15%">Employee Contribution</th>
																	<th width="15%">Employer Contribution</th>
																	<th width="15%">Total Contribution</th>
																</tr>
															</thead>  
															<tbody style="font-size: 15px">
																
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="4" style="text-align:right">Total:</th>
																	<th></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="summarygovdeduction" role="tabpanel" aria-labelledby="summarygovdeduction-tab">
											<div class="row">
												<!-- <h3 class="multisteps-form__title">SSS Deduction</h3> -->
												<div class="table-responsive" width="101%">
													<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
														<table id="summarygovdeductionviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
															<thead class="thead-dark">
																<tr style="cursor: pointer;">
																	<th width="35%">Name</th>
																	<th width="15%">SSS Deduction</th>
																	<th width="15%">PhilHealth Deduction</th>
																	<th width="15%">PagIbig Deduction</th>
																	<th width="15%">Total Deduction</th>
																</tr>
															</thead>  
															<tbody style="font-size: 15px">
																
															</tbody>
															<tfoot>
																<tr>
																	<th colspan="4" style="text-align:right">Total:</th>
																	<th></th>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="button-row d-flex mt-4">
									<button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
									<button class="btn btn-primary ml-auto js-btn-next" type="button" title="btnSave" id="btnSaveGovernmentDeduction">Save and Continue</button>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
							<h3 class="multisteps-form__title">Taxable Income</h3>
							<div class="multisteps-form__content">
								<div class="table-responsive" width="101%">
									<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
										<table id="withholdingtaxviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
											<thead class="thead-dark">
												<tr style="cursor: pointer;">
													<th width="25%">Name</th>
													<th width="15%">Taxable Income</th>
													<th width="15%">Minimum Tax Due</th>
													<th width="15%">Excess over limit</th>
													<th width="15%">Total Tax Due</th>
													<th width="15%">Basic Pay After Tax</th>
												</tr>
											</thead>  
											<tbody style="font-size: 15px">
												
											</tbody>
											<tfoot>
												<tr>
													<th colspan="5" style="text-align:right">Total:</th>
													<th></th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="button-row d-flex mt-4 col-12">
										<button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
										<button class="btn btn-primary ml-auto js-btn-next" type="button" title="btnSave" id="btnSaveTaxableIncome">Save and Continue</button>
									</div>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
							<div class="row">
								<div class="col-md-10">
									<h3 class="multisteps-form__title">Loans</h3>
								</div>
								<div class="col-md-2">
									<button type="button" id="btnAddLoans" class="btn btn-grad btn-danger btn-sm" style="float: right" title="Add miscellaneous payments">
										<i class="fa fa-plus" aria-hidden="true"></i>
									</button>
								</div>
							</div>
							<div class="multisteps-form__content">
								<div class="table-responsive" width="101%">
									<!-- <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;"> -->
										<table id="loansviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
											<thead class="thead-dark">
												<tr style="cursor: pointer;">
													<th width="25%">Name</th>
													<th width="12%">Loan Type</th>
													<th width="12%">Due Date</th>
													<th width="12%">Running Balance</th>
													<th width="12%">Amount Due</th>
												</tr>
											</thead>  
											<tbody style="font-size: 15px">

											</tbody>
											<tfoot>
												<tr>
													<th colspan="4" style="text-align:right">Total:</th>
													<th></th>
												</tr>
											</tfoot>
										</table>
									<!-- </div> -->
								</div>
								<div class="row">
									<div class="button-row d-flex mt-4 col-12">
										<button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
										<button class="btn btn-primary ml-auto js-btn-next" type="button" title="btnSave" id="btnSaveLoans">Save and Continue</button>
									</div>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
							<div class="row">
								<div class="col-md-10">
									<h3 class="multisteps-form__title">Miscellaneous</h3>
								</div>
								<div class="col-md-2">
									<button type="button" id="btnAddMiscellaneous" class="btn btn-grad btn-danger btn-sm" style="float: right" title="Add miscellaneous payments">
										<i class="fa fa-plus" aria-hidden="true"></i>
									</button>
								</div>
							</div>
							<div class="multisteps-form__content">
								<div class="table-responsive" width="101%">
									<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
										<table id="miscviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
											<thead class="thead-dark">
												<tr style="cursor: pointer;">
													<th width="25%">Name</th>
													<th width="12%">Miscellaneous type</th>
													<th width="12%">Description</th>
													<th width="12%">Miscellaneous Amount</th>
												</tr>
											</thead>  
											<tbody style="font-size: 15px">

											</tbody>
											<tfoot>
												<tr>
													<th colspan="3" style="text-align:right">Total:</th>
													<th></th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="button-row d-flex mt-4 col-12">
										<button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
										<button class="btn btn-primary ml-auto js-btn-next" type="button" title="btnSave" id="btnSaveMisc">Save and Continue</button>
									</div>
								</div>
							</div>
						</div>
						<!--single form panel-->
						<div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
							<div class="row py-2">
								<div class="col-6" id="forLblpayrollperiod" >
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Cut off Period</span>
										</div>
										<input id="payrollperiod" type="text" class="form-control form-control-sm " readonly="" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
									</div>
								</div>
								<div class="col-6" id="forLblpaydate" >
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Payroll Pay Date</span>
										</div>
										<input id="paydate" type="text" class="form-control form-control-sm " readonly="" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
									</div>
								</div>
							</div>							
							<div class="multisteps-form__content table-responsive">
								<div class="table-responsive" width="101%">
									<!-- <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;"> -->
										<table id="reviewpayrollviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
											<thead class="thead-dark">
												<tr style="cursor: pointer; font-size: 12px">
													<th width="25%">Name</th>
													<th width="8%">Basic Pay</th>
													<th width="8%">Deminis</th>
													<th width="8%">Salary Adjustment</th>
													<th width="8%">SSS Deduction</th>
													<th width="8%">PhilHealth Deduction</th>
													<th width="8%">PagIbig Deduction</th>
													<th width="8%">Tardiness</th>
													<th width="8%">Absences</th>
													<th width="8%">Withholding Tax</th>
													<th width="8%">Loans</th>
													<th width="8%">Miscellaneous</th>
													<th width="8%">Net Take Home Pay</th>

												</tr>
											</thead>  
											<tbody style="font-size: 12px">
												
											</tbody>
											<tfoot>
												<tr style="font-size: 12px">
												<th colspan="12" style="text-align:right">Total:</th>
													<th></th>
												</tr>
											</tfoot>
										</table>
									<!-- </div> -->
								</div>
								<div class="button-row d-flex mt-4">
									<button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
									<button class="btn btn-success ml-auto" type="button" title="Submit" id="btnSubmit">Submit</button>
								</div>
							</div>
						</div>
					</form>
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

<?php 
	include_once('views/payroll/add-miscellaneous.php');
	include_once('views/payroll/add-salaryadjusment.php');
?>

