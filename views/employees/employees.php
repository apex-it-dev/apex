<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Employees</h3>
			 </div>
			<div class="col-md-2" align="right">
				<?php if(isset($accessitems->cansave)) { ?>
					<button type="button" class="btn btn-danger btn-sm" id="AddNewEmployee"><i class="fa fa-plus" style="font-size:14px"></i> Add Employee</button>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="col-md-12">  
			<div class="profile-head">
				<ul class="nav nav-tabs" id="myTabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="employeelists-tab" data-toggle="tab" href="#employeelists" role="tab" aria-controls="employeelists" aria-selected="true">Employee Lists</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" hidden id="onboarding-tab" data-toggle="tab" href="#onboarding-" role="tab" aria-controls="onboarding-" aria-selected="false">Onboarding</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" hidden id="employeemovement-tab" data-toggle="tab" href="#employeemovement" role="tab" aria-controls="employeemovement" aria-selected="true">Employee Movement</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" hidden id="onboard-tab" data-toggle="tab" href="#onboard" role="tab" aria-controls="onboard" aria-selected="true">Onboarding</a>
					</li>
				</ul>
			</div>
		<!--			Employee Lists-->
			<div class="tab-content employeelists-tab" id="myTabContent">
				<div class="tab-pane fade show active" id="employeelists" role="tabpanel" aria-labelledby="employeelists-tab">
					<div class="row">
						<div class="col-md-4">
							<h3></h3>
						</div>
						
						<!-- <div class="col-md-4">
							<div class="basic-search">
								<div class="input-field">
									<div class="input-field">
										<input id="search" type="text" placeholder="Search Employee"><i class="fas fa-search"></i>
									</div>
								</div>
							</div>
						</div> -->
					</div>
					<!-- <div class="row"> -->
					<div class="table-responsive" style="width:101%">
						<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="table-layout: fixed; width:99%!important;">
							<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="abaeelistdatatable" width="100%" cellspacing="0">
								<thead class="thead-dark table table-hover">
							        <tr>
										<th scope="col">Employee Name</th>
										<th scope="col">Position</th>
										<th scope="col">Department</th>
										<th scope="col">Station</th>
										<th scope="col">Joining Date</th>
										<th scope="col">Status</th>
										<!-- <th scope="col"></th> -->
									</tr>
							    </thead>
							  	<tbody style="cursor:pointer;">
							  		
							  	</tbody>
							</table>	
						</div>		
					</div>

					<!-- </div> -->
				</div> 
		<!--			Onboarding-->
				<div class="tab-pane fade" id="onboarding-" role="tabpanel" aria-labelledby="onboarding-tab">
					<div class="row">
						<div class="col-3">
							<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
								<a class="nav-link active" id="v-pills-basicinfo-tab" data-toggle="pill" href="#v-pills-basicinfo1" role="tab" aria-controls="v-pills-basicinfo" aria-selected="true">Step 1: Basic Information</a>
								<a class="nav-link" id="v-pills-personalinfo-tab" data-toggle="pill" href="#v-pills-personalinfo" role="tab" aria-controls="v-pills-personalinfo" aria-selected="false">Step 2: Personal Info</a>
								<a class="nav-link" id="v-pills-contactinfo-tab" data-toggle="pill" href="#v-pills-contactinfo" role="tab" aria-controls="v-pills-contactinfo" aria-selected="false">Step 3: Contact Info</a>
								<a class="nav-link" id="v-pills-reportsto-tab" data-toggle="pill" href="#v-pills-reportsto" role="tab" aria-controls="v-pills-reportsto" aria-selected="false">Step 4: Reports To</a>
								<a class="nav-link" id="v-pills-employeeroles-tab" data-toggle="pill" href="#v-pills-employeeroles" role="tab" aria-controls="v-pills-employeeroles" aria-selected="false">Step 5: Employee Roles</a>
								<a class="nav-link" id="v-pills-docs-tab" data-toggle="pill" href="#v-pills-docs" role="tab" aria-controls="v-pills-docs" aria-selected="false">Step 6: Documents</a>
							</div>
						</div>
						<!--STEP 1-->
						<div class="col-9">
							<div class="tab-content" id="v-pills-basicinfo">
								<div class="tab-pane fade show active" id="v-pills-basicinfo1" role="tabpanel" aria-labelledby="v-pills-basicinfo-tab">
									<h5>Step 1: Employee Basic Information</h5>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Company</label>
										<div class="col-8">2
											<select class="form-control" id="companyname">
											<option>abahk</option> 
											<option>abasg</option> 
											<option>abarunbei</option> 
											<option>abarunsha</option> 
											<option>ssceb</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Station</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
											<option>abahk</option> 
											<option>abasg</option> 
											<option>abarunbei</option> 
											<option>abarunsha</option> 
											<option>ssceb</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Department</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
												<option>abahk</option> 
												<option>abasg</option> 
												<option>abarunbei</option> 
												<option>abarunsha</option> 
												<option>ssceb</option> 
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Employee Type</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
												<option>Temporary</option> 
												<option>Permanent</option> 
												<option>Consultant</option> 
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Employee Category</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
												<option>Regular Employee</option> 
												<option>Consultant</option> 
												<option>Intern</option> 
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Work Shift</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
												<option>1st shift: 7:00 AM</option> 
												<option>2nd shift: 10:00 AM</option> 
												<option>Regular shift</option> 
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Grade</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
											<option>-</option> 
											<option>N/A</option> 
											</select>
										</div>
									</div>
									<h5>Employee Name</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">First Name</label>
										<div class="col-8">
											<input class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Last Name</label>
										<div class="col-8">
											<input class="form-control">
										</div>
									</div>
									<h5>User Information</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">User Name</label>
										<div class="col-8">
											<input class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Password</label>
										<div class="col-8">
											<input class="form-control" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-date-input" class="col-4 col-form-label">Joining Date</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Gender</label>
										<div class="col-8">
											<select class="form-control" id="companyname">
												<option>Female</option> 
												<option>Male</option> 
												<option>Transgender</option> 
											</select>
										</div>
									</div>
									<button type="button" class="btn btn-danger m-2">Next</button>
								</div>
								<!--	STEP 2-->
								<div class="tab-pane fade" id="v-pills-personalinfo" role="tabpanel" aria-labelledby="v-pills-personalinfo-tab">
									<h5>Step 2: Employee Personal Information</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Email Adress</label>
										<div class="col-8">
											<input type="email" class="form-control" id="exampleFormControlInput1" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Government ID</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="Default input">
										</div>
									</div>
									<h5>Personal Information</h5>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Salutation</label>
										<div class="col-8">
										<select class="form-control" id="salutation">
											<option>Ms.</option> 
											<option>Mr.</option> 
											<option>Mrs.</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-date-input" class="col-4 col-form-label">Date of Birth</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Nationality</label>
										<div class="col-8">
										<select class="form-control" id="nationality">
										<option>Chinese</option> 
										<option>Austrilian</option> 
										<option>French</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-text-input" class="col-4 col-form-label">Marital Status</label>
										<div class="col-8">
											<select class="form-control" id="maritalstatus">
												<option>Single</option> 
												<option>Married</option> 
												<option>Divorced</option> 
												<option>Separated</option> 
												<option>Widowed</option> 
												<option>Common Law</option> 
											</select>
										</div>
									</div>
									<h5>Passposrt Information</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Passport Number</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="Default input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Passport Expiration Date</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Passport Country</label>
										<div class="col-8">
											<select class="form-control" id="nationality">
												
											</select>
										</div>
									</div>
									<h5>Custom Fields</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Title abvt</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Name abvt</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Chinese Name</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Full Name in ID/Passport</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Probation period</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Termination Notice</label>
										<div class="col-8">
											<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Annual leave quota</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Monthly gross salary in local currency shown in contract</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Monthly employer's contribution (mpf, mfp, sss)</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Monthly A+ medical ins in HKD(write 0 if nil)</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Monthly medical insurance (for supplimentary med ins, not A+ write 0 if nil)in local currency</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Monthly business expenses allowance written in contract in local currency (write 0 if nil)</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Co name of 1st contract signed</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Date of most recent contract effective</label>
										<div class="col-8">
										<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Actual place of work currently</label>
										<div class="col-8">
										<select class="form-control" id="nationality">
											<option>China</option> 
											<option>Austrilia</option> 
											<option>France</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Bank name, acct no</label>
										<div class="col-8">
										<input class="form-control" type="text" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Work visa by abacare</label>
										<div class="col-8">
										<select class="form-control" id="nationality">
											<option>Yes</option> 
											<option>No</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Work Visa exp date</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Probation Completion Date</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Last working date</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Personal Email</label>
										<div class="col-8">
											<input type="email" class="form-control" id="exampleFormControlInput2" placeholder="name@example.com">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Wechat</label>
										<div class="col-8">
											<input class="form-control" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Office Tel Number</label>
										<div class="col-8">
											<input class="form-control" type="number">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Effective Date</label>
										<div class="col-8">
											<input class="form-control" type="date" value="2011-08-19" id="example-date-input">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Remarks</label>
										<div class="col-8">
											<input class="form-control" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Skype</label>
										<div class="col-8">
											<input class="form-control" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Education Background</label>
										<div class="col-8">
											<select class="form-control" id="nationality">
												<option>Phd</option> 
												<option>Masters Degree</option> 
												<option>Bachelor's Degree</option> 
											</select>
										</div>
									</div>
									<button type="button" class="btn btn-danger m-2">Next</button>
								</div>
								<!--	STEP 3-->
								<div class="tab-pane fade" id="v-pills-contactinfo" role="tabpanel" aria-labelledby="v-pills-contactinfo-tab">
									<h5>Step 3: Employee Contact Information</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Address</label>
										<div class="col-8">
											<input type="text" class="form-control" id="inputAddress" placeholder="">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">City</label>
										<div class="col-8">
											<input type="text" class="form-control" id="inputAddress1" placeholder="">
										</div>
									</div>
									<div class="form-group row">
									<label for="example-search-input" class="col-4 col-form-label">State/Province</label>
									<div class="col-8">
										<select class="form-control" id="nationality">
											<option>Manila</option> 
											<option>Cebu</option> 
											<option>Davao</option> 
										</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Zip Code</label>
										<div class="col-8">
											<input class="form-control" type="number">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Country</label>
										<div class="col-8">
											<select class="form-control" id="nationality">
												<option>Argentina</option> 
												<option>France</option> 
												<option>Philippines</option> 
											</select>
										</div>
									</div>
									<h5>Phone Numbers</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Home Phone Number</label>
										<div class="col-8">
											<input class="form-control" type="number">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Office Phone Number</label>
										<div class="col-8">
											<input class="form-control" type="number">
										</div>
									</div>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Mobile Number</label>
										<div class="col-8">
											<input class="form-control" type="number">
										</div>
									</div>
									<button type="button" class="btn btn-danger m-2">Next</button>
								</div>
								<!--	STEP 4-->
								<div class="tab-pane fade" id="v-pills-reportsto" role="tabpanel" aria-labelledby="v-pills-reportsto-tab">
									<h5>Step 4: Employee Reporting Line</h5> 
									<h5 mb-2>Direct Reporting Line</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Reports To(Line Manager)</label>
										<div class="col-8">
											<select class="form-control" id="reportsto">
												<option>a</option> 
												<option>b</option> 
												<option>c</option> 
											</select>
										</div>
									</div>  
									<h5 mb-2>Indirect Reporting Line</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Reports To(Indirect Manager)</label>
										<div class="col-8">
											<select class="form-control" id="reportsto">
												<option>a</option> 
												<option>b</option> 
												<option>c</option> 
											</select>
										</div>
									</div>  
								</div>
								<!--	STEP 5-->
								<div class="tab-pane fade" id="v-pills-employeeroles" role="tabpanel" aria-labelledby="v-pills-employeeroles-tab">
									<h5>Step 5: Employee Roles</h5> 
									<h5 mb-2>Roles Template</h5>
									<div class="form-group row">
										<label for="example-search-input" class="col-4 col-form-label">Roles Template:</label>
										<div class="col-8">
											<select class="form-control" id="reportsto">
												<option>No Access</option> 
												<option>Local HR Manager/GM</option> 
												<option>Regular Employee</option> 
												<option>Dashboard/Report Only</option> 
												<option>System Admin</option> 
												<option>Supervisory/Managerial role</option> 
											</select>
										</div>
									</div>  
								</div>
								<!--	STEP 6-->
								<div class="tab-pane fade" id="v-pills-docs" role="tabpanel" aria-labelledby="v-pills-docs-tab">
									<h5 mb-5>Step 6: Employee Documents</h5> 
									<div class="custom-file">
										<input type="file" class="custom-file-input form" id="customFile">
										<label class="custom-file-label" for="customFile">Add Attachment</label>
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--PAN-->
				<div class="tab-pane fade" id="employeemovement" role="tabpanel" aria-labelledby="employeemovement-tab">
					<div class="row">
						<div class="col-3">
							<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
								<a class="nav-link active" id="v-pills-movement-tab" data-toggle="pill" href="#v-pills-pmovement" role="tab" aria-controls="v-pills-movement" aria-selected="true">Movement</a>
								<a class="nav-link" id="v-pills-exit-tab" data-toggle="pill" href="#v-pills-exit" role="tab" aria-controls="v-pills-exit" aria-selected="false">Employees Exit</a>
							</div>
						</div>
						<!--Movement-->
						<div class="col-9">
							<div class="tab-content" id="v-pills-movement-tab">
								<div class="tab-pane fade show active" id="v-pills-movement" role="tabpanel" aria-labelledby="v-pills-movement-tab">
									<h5>Movement</h5>
									<div class="row">
										<div class="col-md-4">
											<h3></h3>
										</div>
										<div class="col-md-4">
											<button type="button" class="btn btn-danger">Add Record</button>
											<i class="fas fa-filter"></i>
											<i class="fas fa-sync"></i>
										</div>
										<div class="col-md-4">
											<div class="basic-search">
												<div class="input-field">
													<div class="input-field">
														<input id="search" type="text" placeholder="Search Employee"><i class="fas fa-search"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 m-3">
											<table class="table table-hover">
												<thead>
												<tr>
												  <th scope="col">Employee Name</th>
												  <th scope="col">Department</th>
												  <th scope="col">promotion Date</th>
												  <th scope="col">Previous Position</th>
												  <th scope="col">Promoted To:</th>
												  <th scope="col"></th>	
												</tr>
												</thead>
												<tbody>
												<tr>
												  <th scope="row">Vivencia Velasco</th>
												  <td>IT</td>
												  <td>January 10, 2010</td>
												  <td>IT Manager</td>
												  <td>CEO</td>
												  <td><i class="fas fa-bars"></i></td>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<!--Employees Exit-->
								<div class="tab-pane fade" id="v-pills-exit" role="tabpanel" aria-labelledby="v-pills-exit-tab">
									<h5>Employees Exit</h5>
									<div class="row">
										<div class="col-md-4">
											<h3></h3>
										</div>
										<div class="col-md-4">
											<button type="button" class="btn btn-danger">Add Record</button>
											<i class="fas fa-filter"></i>
											<i class="fas fa-sync"></i>
										</div>
										<div class="col-md-4">
											<div class="basic-search">
												<div class="input-field">
													<div class="input-field">
														<input id="search1" type="text" placeholder="Search Employee"><i class="fas fa-search"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 m-3">
											<table class="table table-hover">
												<thead>
												<tr>
												  <th scope="col">Employee Name</th>
												  <th scope="col">Exit Type</th>
												  <th scope="col">Exit Date</th>
												  <th scope="col"></th>	
												</tr>
												</thead>
												<tbody>
												<tr>
												  <th scope="row">Vivencia Velasco</th>
												  <td>Resignation</td>
												  <td>January 10, 2010</td>
												  <td><i class="fas fa-bars"></i></td>
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
				<div class="tab-pane fade" id="onboard" role="tabpanel" aria-labelledby="onboard-tab">
					<div class="card-body">
						<!-- <div class="row"> -->
							<div class="col-md-12 col-sm-12">
								<div class="profile-head">
									<ul class="nav nav-tabs d-admin-block" id="myTab" role="tablist">
										<li class="nav-item " id="personalinfotabli">
											<a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal-info" role="tab" aria-controls="personal" aria-selected="true">Personal Info</a>
										</li>
										<li class="nav-item">
											<a class="nav-link disabled"  id="contact-tab" data-toggle="tab" href="#contact-info" role="tab" aria-controls="home" aria-selected="true">Contact Info</a>
										</li>
										<li class="nav-item"  id="employeedatatabli">
											<a class="nav-link disabled"  id="employee-tab" data-toggle="tab" href="#employee-info" role="tab" aria-controls="employee" aria-selected="true">Employee Data</a>
										</li>	
										<li class="nav-item"  id="compensationandbenefits">
											<a class="nav-link disabled"  id="compensationandbenefits-tab" data-toggle="tab" href="#compensationandbenefits-info" role="tab" aria-controls="compensationandbenefits" aria-selected="true">Compensation and Benefits</a>
										</li>
										<li class="nav-item"  id="certification">
											<a class="nav-link disabled"  id="certification-tab" data-toggle="tab" href="#certification-info" role="tab" aria-controls="certification" aria-selected="true">Certifications</a>
										</li>
										<li class="nav-item"  id="accountsettingstabli">
											<a class="nav-link disabled" id="accountsettings-tab" data-toggle="tab" href="#accountsettings-info" role="tab" aria-controls="accountsettings" aria-selected="true">Account Settings</a>
										</li>						
									</ul>

								</div>
								<div class="tab-content profile-tab" id="myTabContent">
									<div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-tab" >
										<h3>Personal info</h3>
										<form class="form-horizontal" role="form" autocomplete="off">	
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Salutation:<span class="text-danger">*</span></label>
												<div class="col-lg-8 col-sm-8" id="divtxtSalutation">
													<select id="txtSalutation" class="form-control form-control-sm">
													</select>
												</div>
											</div>								
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Last Name:<span class="text-danger">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtLastname" class="form-control form-control-sm text-uppercase" type="text" value="" >
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label" >First Name:<span class="text-danger" id="reqFirstname">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtFirstname" class="form-control form-control-sm text-capitalize" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Chinese Name:</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtChinesename" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Nationality:</label>
												<div class="col-lg-8 col-sm-8" id="divNationalities">
													<select id="txtNatl" class="form-control form-control-sm">
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Marital Status:<span class="text-danger" id="reqMarital">*</span></label>
												<div class="col-lg-8 col-sm-8" id="divMaritalStat">
													<select id="txtMaritalStat" class="form-control form-control-sm">
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Date of Birth:<span class="text-danger" id="reqDOB">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtBirthdate" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Gender:<span class="text-danger" id="reqGender">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<select id="txtgender" class="form-control form-control-sm">
														<option value="" selected></option>
														<option value="f">Female</option>
														<option value="m">Male</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Name abvt:<span class="text-danger" id="reqabaini">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtNameAbvt" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Government ID:</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtGovertmentID" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<h3>Passport Information</h3>
											<form class="form-horizontal" role="form" autocomplete="off">
											  <div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Passport No.</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtPassportNo" class="form-control form-control-sm" type="text" value="">
												</div>
											  </div>
											  <div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Issued Date</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtIssuedDate" class="form-control form-control-sm" type="text" value="">
												</div>
											  </div>
											  <div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Passport Expiration Date</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtExpirationDate" class="form-control form-control-sm" type="text" value="">
												</div>
											  </div>
											  <div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Issued Country:</label>
												<div class="col-lg-8 col-sm-8" id="divtxtPassport">
												  <!-- <input id="txtVisaProcessedbyAbac" class="form-control form-control-sm" type="text" value=""> -->
												  <select name="txtPassportCountry" id="txtPassportCountry" class="form-control form-control-sm">
												  	
												  </select>
												</div>
											  </div>
											</form>
											</form>
											<div class="form-group row" align="right">
												<label class="col-lg-3 col-sm-4 control-label"></label>
												<div class="col-lg-8 col-sm-8">
													<!-- <input id="NextToContactInfo" type="button" class="btn btn-sm btn-secondary" value="Next"> -->
													<button type="button" class="btn btn-primary next-step float-right btn-sm" id="NextToContactInfo">Next</button>
													<!-- <input id="btnCancel1" type="reset" class="btn btn-sm btn-secondary" value="Cancel">
													<input id="btnSaveChanges1" type="button" class="btn btn-sm btn-primary" value="Save Changes"> -->
												</div>
											</div>						
										</div>						
									<div class="tab-pane fade" id="contact-info" role="tabpanel" aria-labelledby="contact-tab" >		
										<h3>Contact Info - Personal</h3>
										<form class="form-horizontal" role="form" autocomplete="off">									
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Personal Email:<span class="text-danger">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtEmailAddress" class="form-control form-control-sm" type="email" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Mobile no.:<span class="text-danger">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtMobileNo" class="form-control form-control-sm" type="text" value="" >
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Home Phone no.:<span class="text-danger">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtHomePhone" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Wechat:</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtWeChat" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Whatsapp:</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtWhatsapp" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Skype:</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtSkype" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">LinkedIn:</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtLinkedIn" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Present Address:<span class="text-danger">*</span></label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtPresentAdr" class="form-control form-control-sm text-capitalize" type="text" value="" placeholder="Street">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label"></label>
												<div class="col-lg-5 col-sm-5">
												  <input id="txtCity" class="form-control form-control-sm text-capitalize" type="text" value="" placeholder="City">
												</div>
												<div class="col-lg-3 col-sm-3">
												  <input id="txtState" class="form-control form-control-sm text-capitalize" type="text" value="" placeholder="State/Province">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label"></label>
												<div class="col-lg-5 col-sm-5">
													<span id="divCountries"><select id="txtCountry" class="form-control form-control-sm"></select></span>
												  <!-- <input id="txtCountry" class="form-control form-control-sm" type="text" value="" placeholder="Country"> -->
												</div>
												<div class="col-lg-3 col-sm-3">
												  <input id="txtZipCode" class="form-control form-control-sm" type="number" value="" placeholder="Zip Code" min="0">
												</div>
											</div>
										</form>
										<h3>Emergency Contact</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Name:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtEmergencyContactPerson" class="form-control form-control-sm text-capitalize" type="text" value="">
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Contact:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtEmergencyPhoneNo" class="form-control form-control-sm" type="text" value="">
											</div>
										  </div>
										   <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Relationship:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtRelationship" class="form-control form-control-sm text-capitalize" type="text" value="">
											</div>
										  </div>
										</form>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label"></label>
											<div class="col-lg-8 col-sm-8" align="right">
												<button type="button" class="btn btn-outline-primary btn-sm" id="BackToPersoInfo">Previous</button>
												<!-- <input id="NextEmpData" type="button" class="btn btn-sm btn-secondary" value="Next"> -->
												<!-- <button type="button" class="btn btn-outline-primary prev-step btn-sm">Previous</button> -->
												<button type="button" class="btn btn-primary next-step btn-sm">Next</button>
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="compensationandbenefits-info" role="tabpanel" aria-labelledby="compensationandbenefits-tab">
										<form class="form-horizontal" role="form" autocomplete="off">
											<!-- <div class="row">
												<div class="col-lg-6">
													<h3>Position History</h3>
												</div>
												<div class="col-lg-6" id="addrowbtn" align="right">
												
												</div>
											</div>
											<div class="form-group row">		
												<div class="table-responsive" id="divPositionHistory"> 
													<table class="table table-sm table-bordered"width="100%" cellspacing="0"  id="position_history_edit">
														<thead class="thead-dark">
															<tr>
																<th width="55%">Position</th>
																<th width="20%">Rate</th>
																<th width="20%">Effective Date</th>
																<th width="5%">Edit</th>
															</tr>
														</thead>  
														<tbody>
															<tr>
															</tr>
														</tbody>
													</table>
												</div>
											</div>	 -->
											<div class="row">
												<div class="col-md-6">
													<h3>Benefits</h3>
												</div>
												<div class="col-md-6" id="addrowbtn" align="right">
													<button type="button" class="btn btn-outline-primary btn-sm" id="genLeaveQuota">Generate Leave Quota</button>
												</div>
											</div>
											<div class="form-group row">
												<div class="table-responsive" id="divleavequotas"> 
													<table class="table table-sm table-bordered table-hover" id="benefitlistdatatable" width="100%" cellspacing="0" >
														<thead class="thead-dark ">
															<tr>
																<th width="50%">Leave Type</th>
																<th width="40%">Entitle</th>
																<th width="10%">Status</th>
															</tr>
														</thead>  
														<tbody>
															<tr>
																<!-- leave list -->
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<h3>Insurance</h3>
												</div>
												<div class="col-md-6" id="addrowbtn" align="right">
													<button type="button" class="btn btn-outline-primary btn-sm" id="genHMO">Generate HMO</button>
												</div>
											</div>
											<div class="form-group row">
												<div class="table-responsive"> 
													<table class="table table-sm table-bordered" id="hmobenefits_edit" width="100%" cellspacing="0">
														<thead class="thead-dark">
															<tr>
																<th width="50%">Health Insurance Coverage</th>
																<th width="50%">Coverage Amount</th>
															</tr>
														</thead>  
														<tbody>
															<tr>
																<!-- hmo list -->
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</form>
										<form class="form-horizontal" role="form" autocomplete="off">
											<h3>Other Benefits</h3>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Monthly gross salary in local currency shown in contract</label>
												<div class="col-lg-4 col-sm-4">
												<input id="txtMonthlygrossSal" class="form-control form-control-sm" type="number" placeholder="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Monthly employer's contribution (mpf, mfp, sss)</label>
												<div class="col-lg-4 col-sm-4">
												<input id="txtxMosEmpContri" class="form-control form-control-sm" type="number" placeholder="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Monthly A+ medical ins in HKD(write 0 if nil)</label>
												<div class="col-lg-4 col-sm-4">
												<input id ="txtmosaplusmedinshkd" class="form-control form-control-sm" type="number" placeholder="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Monthly medical insurance (for supplimentary med ins, not A+ write 0 if nil)in local currency</label>
												<div class="col-lg-4 col-sm-4">
													<input id ="txtmosmedinsinlocalcur" class="form-control form-control-sm" type="number" placeholder="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Monthly business expenses allowance written in contract in local currency (write 0 if nil)</label>
												<div class="col-lg-4 col-sm-4">
												<input id="txtmosbusexpinilocalcur" class="form-control form-control-sm" type="number" placeholder="">
												</div>
											</div>
										</form>
										<div class="form-group row">
											<label class="col-lg-4 col-sm-4 control-label"></label>
											<div class="col-lg-8 col-sm-8" align="right">
												<button type="button" class="btn btn-outline-primary btn-sm" id="BackToEEData">Previous</button>
												<button type="button" class="btn btn-primary next-step btn-sm">Next</button>
												<!-- <input  type="button" class="btn btn-sm btn-primary" value="Save"> -->
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="employee-info" role="tabpanel" aria-labelledby="employee-tab" >
										<h3>Employment Data info</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Joined Date:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtJoinedDate" class="form-control form-control-sm" type="text" value="">
											</div>
											<!-- <div id="reportrange_right" class="form-control col-lg-3 col-sm-8">
											  <span>Mon 16 Jul 18</span> <b class="caret"></b><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
											</div> -->
								  		 </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Office:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8" id="divOffice">
												<select id="txtOffices" class="form-control form-control-sm">
												</select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Department:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8" id="divDepartment">
												<select id="txtDept" class="form-control form-control-sm">
												</select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Position:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8" id="divPosition">
												<select id="txtPositions" class="form-control form-control-sm">
												</select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Status:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8" id="divEEcategory">
												<select id="txtEECat" class="form-control form-control-sm">
												</select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Ranking:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8" id="divRanking1">
												<select id="txtRanking" class="form-control form-control-sm"></select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Direct Head:<span class="text-danger">*</span></label>
											<div class="col-lg-8 col-sm-8" id="divReportsTo">
											  <!-- <input id="txtReportsto" class="form-control form-control-sm" type="text" value=""> -->
											  <select id="txtRepto" class="form-control form-control-sm">
											  	</select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Indirect Head:</label>
											<div class="col-lg-8 col-sm-8" id="divReportsToIndirect">
											  <!-- <input id="txtReportstoindirect" class="form-control form-control-sm" type="text" value=""> -->
											  <select id="txtReptoindirect" class="form-control form-control-sm">
												</select>
											</div>
										  </div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Shift Schedule:<span class="text-danger">*</span></label>
												<div class="col-lg-4 col-sm-4" id="divShiftschedFrom">
													FROM:
												<!-- <input id="txtReportstoindirect" class="form-control form-control-sm" type="text" value=""> -->
												<select id="shiftschedFrom" class="form-control form-control-sm">
												</select>
												</div>
												<div class="col-lg-4 col-sm-4" id="divShiftschedTo">
													TO:
												<!-- <input id="txtReportstoindirect" class="form-control form-control-sm" type="text" value=""> -->
												<select id="shiftschedTo" class="form-control form-control-sm">
												</select>
												</div>
											</div>
										</form>
										<h3>Work Contact Details</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Email:<span class="text-danger" id="reqGender">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtEmailAddress1" class="form-control form-control-sm" type="email" value="">
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Office Number:<span class="text-danger" id="reqGender">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtOfficeNo" class="form-control form-control-sm" type="text" value="">
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Skype:<span class="text-danger" id="reqGender">*</span></label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtSkype1" class="form-control form-control-sm" type="text" value="">
											</div>
										  </div>
										
										</form>
										
										<h3>Other Information</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Probation Period:</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtProbationPeriod" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div id="probationdetails" style="display: none">
												<div class="form-group row">
													<label class="col-lg-3 col-sm-4 control-label">Probation Start Date:</label>
													<div class="col-lg-8 col-sm-8">
													  <!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
													  <input id="txtProbationStartDate" class="form-control form-control-sm" type="text" value="">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-sm-4 control-label">Probation End Date:</label>
													<div class="col-lg-8 col-sm-8">
													  <!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
													  <input id="txtProbationEndDate" class="form-control form-control-sm" type="text" value="">
													</div>
												</div>
											</div>
											<div id="regularizationdetails">
												<div class="form-group row">
													<label class="col-lg-3 col-sm-4 control-label">Regularization Date:</label>
													<div class="col-lg-8 col-sm-8">
													  <!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
													  <input id="txtRegularizationDate" class="form-control form-control-sm" type="text" value="">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-sm-4 control-label">Employment End Date:</label>
													<div class="col-lg-8 col-sm-8">
													  <!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
													  <input id="txtEndOfEmploymentDate" class="form-control form-control-sm" type="text" value="">
													</div>
												</div>
											</div>
											<div id="contractdetails" style="display: none">
												<div class="form-group row">
													<label class="col-lg-3 col-sm-4 control-label">Start of Contract Date:</label>
													<div class="col-lg-8 col-sm-8">
													  <!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
													  <input id="txtStartContractDate" class="form-control form-control-sm" type="text" value="">
													</div>
												</div>
												<div class="form-group row">
													<label class="col-lg-3 col-sm-4 control-label">End of Contract Date:</label>
													<div class="col-lg-8 col-sm-8">
													  <!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
													  <input id="txtEndContractDate" class="form-control form-control-sm" type="text" value="">
													</div>
												</div>
											</div>
											<h5>Visa Details</h5>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Type of Visa:</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtVisaExpDate" class="form-control form-control-sm" type="date" value=""> -->
												  <!-- <input id="txtTypeOfVisa" class="form-control form-control-sm" type="text" value=""> -->
												  <select name="txtTypeOfVisa" id="txtTypeOfVisa" class="form-control form-control-sm">
												  	<option value="" selected ></option>
												  	<option value="workvisa" >Work Visa</option>
												  	<option value="travelvisa">Travel Visa</option>
												  </select>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Visa Start Date:</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtVisaExpDate" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtStartOfVisa" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Visa expiration date:</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtVisaExpDate" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtVisaExpDate" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-lg-3 col-sm-4 control-label">Visa processed by Abacare:</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtVisaProcessedbyAbac" class="form-control form-control-sm" type="text" value=""> -->
												  <select name="txtVisaProcessedbyAbac" id="txtVisaProcessedbyAbac" class="form-control form-control-sm">
												  	<option value="" selected ></option>
												  	<option value="n" >No</option>
												  	<option value="y">Yes</option>
												  </select>
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Termination Period:</label>
												<div class="col-lg-8 col-sm-8">
												  <!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
												  <input id="txtTerminationPeriod" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Probation Completion Date</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtProbationComplete" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Last working date</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtLastWorkingDt" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Effective Date</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtEffectiveDate" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Co name of 1st contract signed</label>
												<div class="col-lg-8 col-sm-8">
												<input id="txtcompanynamefirstctrcsigned" class="form-control form-control-sm" type="text" placeholder="">
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Date of most recent contract effective</label>
												<div class="col-lg-8 col-sm-8">
													<input id="txtRecentCtrctDt" class="form-control form-control-sm" type="text" value="">
												</div>
											</div>
											<div class="form-group row" hidden>
												<label class="col-lg-3 col-sm-4 control-label">Actual place of work currently</label>
												<div class="col-lg-8 col-sm-8" id="divtxtActPlaceofwork">
												<select class="form-control  form-control-sm" id="txtActPlaceofwork">
													
												</select>
												</div>
											</div>
											
										</form>

										<div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label"></label>
											<div class="col-lg-8 col-sm-8" align="right">
												<button type="button" class="btn btn-outline-primary btn-sm" id="BackToContactInfo">Previous</button>
												<button type="button" class="btn btn-primary next-step btn-sm" id="btnSaveChanges3">Save and Continue</button>
												<!-- <input  type="button" class="btn btn-sm btn-primary" value="Save"> -->
											</div>
										</div>
										
									</div>
									<div class="tab-pane fade" id="certification-info" role="tabpanel" aria-labelledby="certification-tab" >
										<div class="row">
											<div class="col-lg-6">
												<h3>Certification</h3>
											</div>
											<div class="col-lg-6" align="right" style="padding-right: 3%;">
												<button type="button" class="btn btn-sm btn-secondary" id="addCertBtn"> ADD </button>
											</div>
										</div>
										<form class="form-horizontal" role="form" autocomplete="off">
											<div class="table-responsive" width="101%">
												<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="width:98%; table-layout:fixed;">
													<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="certificatedatatable" width="100%" cellspacing="0">
														<thead class="thead-dark">
															<tr>
																<th width="30%">Name</th>
																<th width="35%">Organization</th>
																<th width="15%">Issued Date</th>
																<th width="15%">Expiry Date</th>
																<th width="5%"></th>
															</tr>
														</thead>
														<tbody>
															
														</tbody>
													</table>	
												</div>		
											</div>	
													
										</form>
										<div class="form-group row">
											<label class="col-lg-4 col-sm-4 control-label"></label>
											<div class="col-lg-8 col-sm-8" align="right">
												<button type="button" class="btn btn-outline-primary btn-sm" id="BackToCompBen">Previous</button>
												<button type="button" class="btn btn-primary next-step btn-sm">Next</button>
												<!-- <input  type="button" class="btn btn-sm btn-primary" value="Save"> -->
											</div>
										</div>	
									</div>

									<div class="tab-pane fade" id="accountsettings-info" role="tabpanel" aria-labelledby="accountsettings-tab" >
										<!-- <h3>User Settings</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Username:</label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtusername" class="form-control form-control-sm" type="text" value="">
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Password:</label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtpassword" class="form-control form-control-sm" type="text" value="">
											</div>
										  </div>
										</form> -->
										<!-- <h3>Profile Picture</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
											<div class="col-md-3 col-sm-12" style="margin: auto;">
												<div class="profile-img" id="profileimg">
													<img id="upimage" src="img/ees/default.svg" alt=""/>
													<input id="btnUploadPhoto" type="file" class="btn btn-sm btn-primary"> -->
													<!-- <div class="file btn btn-lg btn-primary" id="btnUploadPhoto" style="cursor: pointer;"> -->
														<!-- Upload Photo -->
														<!-- <input id="btnChangePhoto" type="button" class="btn btn-sm btn-primary"> -->
													<!-- </div> -->
													<!-- <div class="file btn btn-lg btn-primary" id="btnChangePhoto" style="cursor: pointer;">
														Change Photo
													</div> -->
												<!-- </div>
											</div>
										</form> -->
										<h3>ZKTeco Settings</h3>
										<form class="form-horizontal" role="form" autocomplete="off">
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">Office:</label>
											<div class="col-lg-8 col-sm-8" id="divZktecoOffices">
											  <select name="txtZkOffice" id="txtZkOffice" class="form-control form-control-sm">
											  	
											  </select>
											</div>
										  </div>
										  <div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label">ZK ID:</label>
											<div class="col-lg-8 col-sm-8">
											  <input id="txtZKID" class="form-control form-control-sm" type="text" value="">
											</div>
										  </div>
										</form>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-4 control-label"></label>
											<div class="col-lg-8 col-sm-8">
												<input id="btnCancel4" type="reset" class="btn btn-sm btn-secondary" value="Cancel">
												<input id="btnUpdate" type="button" class="btn btn-sm btn-primary" value="Done">
											</div>
										</div>
									</div>				
								</div>							
							</div>					
						<!-- </div>			 -->
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>
<?php include_once('views/frmuploadphoto.php');?>
<?php include_once('views/edit_position_history.php');?>
<?php include_once('views/edit_leave_benefit.php');?>
<?php include_once('views/profile-certification-modal.php');?>

<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly disabled/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly disabled/>
<input type="hidden" id="eeid" name="eeid" value="" readonly disabled/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly disabled/>
<input type="hidden" id="sesid" name="sesid" value="" readonly disabled/>
<input type="hidden" id="accesslvl" name="accesslvl" value="<?php echo hasAccess(1); ?>" readonly disabled />
<input type="hidden" id="profilegroup" name="profilegroup" value="" readonly disabled/>
<input type="hidden" id="iniexist" name="iniexist" value="" readonly disabled/>
<?php
	$output = '';
	if(isset($accessitems)){
		foreach ($accessitems as $item) {
			if(isset($item['foreignkey']))
				if(strpos($item['foreignkey'], 'SO') !== FALSE) 
					$output .= $item['foreignkey'] .',';
		}
		$output = rtrim($output, ',');
	}
?>
<input type="hidden" id="viewofc" name="viewofc" value="<?php echo $output; unset($output); ?>" disabled readonly/>