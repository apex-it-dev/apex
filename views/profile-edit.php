<?php
	if(!isset($_GET['id'])){
		echo '<script> window.location = "'. base_URL .'profile.php"; </script>';
	}
	if(isset($_GET['action'])){
		if(base64_decode($_GET['action']) == 'editprofile'){
			if(!(isset($accessitems->canupdate) || isset($accessitems->canupdateinactive))){
				echo '<script> window.location = "'. base_URL .'profile.php"; </script>';
				exit();
			}
		}
	}
?>
<div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
	<div class="card-header py-3 border-bottom-danger">
	  <div class="row">
		<div class="col-9 " style="margin: auto;"> 
			  <h3 class="m-0 font-weight-bold text-gray-600" id="profilename">&nbsp;</h3>
		</div> 
		<div class="col-3" align="right">
		<div class="d-xl-flex d-lg-flex d-md-flex d-sm-flex d-none float-right" ><button id="btnBack" type="reset" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i> Back</button></div>
			<div class="d-xl-none d-lg-none d-md-none d-sm-none d-flex float-right"><button id="btnBackSmall" type="reset" class="btn btn-sm btn-danger"><i class="fas fa-arrow-left"></i></button></div>
		</div>
	   </div>
	</div>
    <!-- Card Body -->
	<div class="card-body">
		<div class="row">
			<div class="col-md-2 col-sm-12">
				<div class="profile-img" id="profileimg">
					<img src="img/ees/default.svg" alt=""/>
					<div class="file btn btn-lg btn-primary" id="btnChangePhoto" style="cursor: pointer;">
						Change Photo
						<!-- <input id="btnChangePhoto" type="button" class="btn btn-sm btn-primary"> -->
					</div>
				</div>
			</div>
			<div class="col-md-10 col-sm-12">
				<div class="profile-head">
					<ul class="nav nav-tabs d-admin-block" id="myTab" role="tablist">

						<?php if(isset($accessitems->haspersoninfotab)) { ?>
						<li class="nav-item" id="personalinfotabli" hidden>
							<a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal-info" role="tab" aria-controls="personal" aria-selected="true">Personal Info</a>
						</li>
						<?php } ?>

						<li class="nav-item" hidden>
							<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact-info" role="tab" aria-controls="home" aria-selected="true">Contact Info</a>
						</li>

						<?php if(isset($accessitems->haseedatatab)) { ?>
						<li class="nav-item" id="employeedatatabli" hidden>
							<a class="nav-link" id="employee-tab" data-toggle="tab" href="#employee-info" role="tab" aria-controls="employee" aria-selected="true">Employee Data</a>
						</li>
						<?php } ?>

						<?php if(isset($accessitems->hascombentab)) { ?>
						<li class="nav-item" id="compensationandbenefits" hidden>
							<a class="nav-link" id="compensationandbenefits-tab" data-toggle="tab" href="#compensationandbenefits-info" role="tab" aria-controls="compensationandbenefits" aria-selected="true">Compensation and Benefits</a>
						</li>	
						<?php } ?>

						<li class="nav-item" id="certificationtabli" hidden>
							<a class="nav-link" id="certification-tab" data-toggle="tab" href="#certification-info" role="tab" aria-controls="certification" aria-selected="true">Certifications</a>
						</li>

						<?php if(isset($accessitems->hasacctsettingstab)) { ?>
						<li class="nav-item" id="accountsettingstabli" hidden>
							<a class="nav-link" id="accountsettings-tab" data-toggle="tab" href="#accountsettings-info" role="tab" aria-controls="accountsettings" aria-selected="true">Account Settings</a>
						</li>	
						<?php } ?>
					</ul>

				</div>
				<div class="tab-content profile-tab" id="myTabContent">

					<?php if(isset($accessitems->haspersoninfotab)) { ?>
					<div class="tab-pane fade" id="personal-info" role="tabpanel" aria-labelledby="personal-tab" >
						<h3>Personal info</h3>
						<form class="form-horizontal" role="form" autocomplete="off">	
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Salutation:<span class="text-danger" id="">*</span></label>
								<div class="col-lg-8 col-sm-8" id="divtxtSalutation">
									<select id="txtSalutation_edit" class="form-control form-control-sm">
									</select>
								</div>
							</div>										
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Last Name:<span class="text-danger" id="">*</span></label>
								<div class="col-lg-8 col-sm-8">
									<input id="txtLastname" class="form-control form-control-sm text-uppercase" type="text" value="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">First Name:<span class="text-danger" id="">*</span></label>
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
								<label class="col-lg-3 col-sm-4 control-label">Marital Status:<span class="text-danger" id="">*</span></label>
								<div class="col-lg-8 col-sm-8" id="divMaritalStat">
									<select id="txtMaritalStat" class="form-control form-control-sm">
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Date of Birth:<span class="text-danger" id="">*</span></label>
								<div class="col-lg-8 col-sm-8">
									<input id="txtBirthdate" class="form-control form-control-sm" type="text" value="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Gender:<span class="text-danger" id="">*</span></label>
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
									<input id="txtNameAbvt_edit" class="form-control form-control-sm" type="text" value="">
								</div>
								<div class="float-left" style="margin: 0.5vh 0 0 -1vh;" id="abvtChecker">
									<!-- <i class="far fa-check-circle"></i> -->
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Government ID:</label>
								<div class="col-lg-8 col-sm-8">
									<input id="txtGovertmentID_edit" class="form-control form-control-sm" type="text" value="">
								</div>
							</div>
						<h3>Passport Information</h3>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Passport No.:</label>
								<div class="col-lg-8 col-sm-8">
									<!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
									<input id="txtPassportNo_edit" class="form-control form-control-sm" type="text" value="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Issued Date:</label>
								<div class="col-lg-8 col-sm-8">
									<!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
									<input id="txtIssuedDate_edit" class="form-control form-control-sm" type="text" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Passport Expiration Date:</label>
								<div class="col-lg-8 col-sm-8">
									<!-- <input id="txtTerminationPeriod" class="form-control form-control-sm" type="date" value=""> -->
									<input id="txtExpirationDate_edit" class="form-control form-control-sm" type="text" value="" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Issued Country:</label>
								<div class="col-lg-8 col-sm-8" id="divtxtPassport">
									<!-- <input id="txtVisaProcessedbyAbac" class="form-control form-control-sm" type="text" value=""> -->
									<select name="txtPassportCountry_edit" id="txtPassportCountry_edit" class="form-control form-control-sm">
									
									</select>
								</div>
							</div>	
						</form>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label"></label>
							<div class="col-lg-8 col-sm-8">
								<input id="btnCancel1" type="reset" class="btn btn-sm btn-secondary" value="Cancel">
								<input id="btnSaveChanges1" type="button" class="btn btn-sm btn-primary" value="Save Changes">
							</div>
						</div>					
					</div>	
					<?php } ?>				

					<div class="tab-pane fade" id="contact-info" role="tabpanel" aria-labelledby="contact-tab">		
						<h3>Contact Info - Personal</h3>
						<form class="form-horizontal" role="form" autocomplete="off">									
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Personal Email:<span class="text-danger" id="">*</span></label>
								<div class="col-lg-8 col-sm-8">
									<input id="txtEmailAddress" class="form-control form-control-sm" type="text" value="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Mobile no.:<span class="text-danger" id="">*</span></label>
								<div class="col-lg-8 col-sm-8">
									<input id="txtMobileNo" class="form-control form-control-sm" type="text" value="" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Home Phone no.:<span class="text-danger" id="">*</span></label>
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
								<label class="col-lg-3 col-sm-4 control-label">Present Address:<span class="text-danger" id="">*</span></label>
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
									<span id="divCountries"><select class="form-control form-control-sm"></select></span>
								  <!-- <input id="txtCountry" class="form-control form-control-sm" type="text" value="" placeholder="Country"> -->
								</div>
								<div class="col-lg-3 col-sm-3">
								  <input id="txtZipCode" class="form-control form-control-sm" type="number" value="" placeholder="Zip Code">
								</div>
							</div>

						</form>
						<h3>Emergency Contact</h3>
						<form class="form-horizontal" role="form" autocomplete="off">
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Name:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8">
							  <input id="txtEmergencyContactPerson" class="form-control form-control-sm text-capitalize" type="text" value="">
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Contact:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8">
							  <input id="txtEmergencyPhoneNo" class="form-control form-control-sm" type="text" value="">
							</div>
						  </div>
						   <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Relationship:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8">
							  <input id="txtRelationship" class="form-control form-control-sm" type="text" value="">
							</div>
						  </div>
						</form>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label"></label>
							<div class="col-lg-8 col-sm-8">
								<input id="btnCancel2" type="reset" class="btn btn-sm btn-secondary" value="Cancel">
								<input id="btnSaveChanges2" type="button" class="btn btn-sm btn-primary" value="Save Changes">
							</div>
						</div>
					</div>

					<?php if(isset($accessitems->hascombentab)) { ?>
					<div class="tab-pane fade" id="compensationandbenefits-info" role="tabpanel" aria-labelledby="compensationandbenefits-tab">
						<form class="form-horizontal" role="form" autocomplete="off">
							<div class="row">
								<div class="col-lg-6">
									<h3>Position History</h3>
								</div>
								<div class="col-lg-6" id="addrowbtn" align="right">
									<input id="addPositionHistory" type="button" class="btn btn-sm btn-secondary" value="   ADD   "/>
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							<div class="form-group row">		
								<div class="table-responsive" id="divPositionHistory" style="width:98%;"> 
									<table class="table table-sm table-bordered" width="100%" cellspacing="0"  id="position_history_edit">
										<thead class="thead-dark">
											<tr>
												<th width="25%">Position</th>
												<th width="15%" class="text-center">Start date</th>
												<th width="15%" class="text-center">End date</th>
												<th width="15%" class="text-center">Tenure</th>
												<th width="15%" class="text-center">Rate</th>
												<th width="15%" class="text-center">Remarks</th>
												<!-- <th width="3%">Edit</th> -->
											</tr>
										</thead>  
										<tbody>
											<tr>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							 <div class="row">
	                            <div class="col-lg-6">
	                                <h3>Benefits</h3>
	                            </div>
	                            <div class="col-lg-6" style="text-align: right;"> 
	                               Year: <label style="width: 20vh; white-space: nowrap;">
									<select class="form-control form-control-sm" id="leavebenefitsyear" name="leavebenefitsyear" aria-controls="">
										<!-- <option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option> -->
									</select>
									</label>
									<span>&nbsp;&nbsp;</span>
	                            </div>
	                        </div>	
							<!-- <h3>Benefits</h3>									
							<div class="form-group row">
								<label class="col-lg-2 col-sm-2 control-label">Year:</label>
								<div class="col-lg-2 col-sm-2">
									<select name="leavebenefitsyear" id="leavebenefitsyear" class="form-control form-control-sm">
										
									</select>
								</div>
							</div> -->
							<div class="form-group row">
								<div class="table-responsive" style="width:98%;"> 
									<table class="table table-sm table-bordered" id="benefitlistdatatable" width="100%" cellspacing="0">
										<thead class="thead-dark">
											<tr>
												<th width="25%">Leave Type</th>
												<th width="15%" class="text-center">Entitled</th>
												<th width="15%" class="text-center">Pending</th>
												<th width="15%" class="text-center">Taken</th>
												<th width="15%" class="text-center">Balance</th>
												<th width="15%" class="text-center">Status</th>
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
							<div class="form-group row" hidden>
								<div class="table-responsive"> 
									<table class="table table-sm table-bordered" id="hmobenefits_edit" width="100%" cellspacing="0">
										<thead class="thead-dark">
											<tr>
												<!-- <th width="50%">Health Insurance Coverage</th>
												<th width="50%">Coverage Amount</th> -->
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
							<h3>Other Benefits</h3>
							<div class="table-responsive" style="width:99%;"> 
								<table class="table table-sm table-bordered" width="100%" cellspacing="0" id="">
									<thead class="thead-dark">
										<tr>
											<th width="70%">Name</th>
											<th width="30%" class="text-right">Amount</th>
										</tr>
									</thead>  
									<tbody>
										<tr>
											<td>Monthly gross salary in local currency shown in contract</td>
											<td><input id="txtMonthlygrossSal" class="form-control form-control-sm text-right" type="text" placeholder=""></td>
										</tr>
										<tr>
											<td>Monthly employer's contribution (mpf, mfp, sss)</td>
											<td><input id="txtxMosEmpContri" class="form-control form-control-sm text-right" type="text" placeholder=""></td>
										</tr>
										<tr>
											<td>Monthly A+ medical ins in HKD(write 0 if nil)</td>
											<td><input id ="txtmosaplusmedinshkd" class="form-control form-control-sm text-right" type="text" placeholder=""></td>
										</tr>
										<tr>
											<td>Monthly medical insurance (for supplimentary med ins, not A+ write 0 if nil)in local currency</td>
											<td><input id ="txtmosmedinsinlocalcur" class="form-control form-control-sm text-right" type="text" placeholder=""></td>
										</tr>
										<tr>
											<td>Monthly business expenses allowance written in contract in local currency (write 0 if nil)</td>
											<td><input id="txtmosbusexpinilocalcur" class="form-control form-control-sm text-right" type="text" placeholder=""></td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- <div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Monthly gross salary in local currency shown in contract</label>
								<div class="col-lg-4 col-sm-4">
								<input id="txtMonthlygrossSal" class="form-control form-control-sm" type="text" placeholder="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Monthly employer's contribution (mpf, mfp, sss)</label>
								<div class="col-lg-4 col-sm-4">
								<input id="txtxMosEmpContri" class="form-control form-control-sm" type="text" placeholder="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Monthly A+ medical ins in HKD(write 0 if nil)</label>
								<div class="col-lg-4 col-sm-4">
								<input id ="txtmosaplusmedinshkd" class="form-control form-control-sm" type="text" placeholder="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Monthly medical insurance (for supplimentary med ins, not A+ write 0 if nil)in local currency</label>
								<div class="col-lg-4 col-sm-4">
									<input id ="txtmosmedinsinlocalcur" class="form-control form-control-sm" type="text" placeholder="">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-3 col-sm-4 control-label">Monthly business expenses allowance written in contract in local currency (write 0 if nil)</label>
								<div class="col-lg-4 col-sm-4">
								<input id="txtmosbusexpinilocalcur" class="form-control form-control-sm" type="text" placeholder="">
								</div>
							</div> -->
						</form>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label"></label>
							<div class="col-lg-8 col-sm-8">
								<input id="btnCancel5" type="reset" class="btn btn-sm btn-secondary" value="Cancel">
								<input id="btnSaveChanges5" type="button" class="btn btn-sm btn-primary" value="Save Changes">
							</div>
						</div>
					</div>
					<?php } ?>	

					<?php if(isset($accessitems->haseedatatab)) { ?>
					<div class="tab-pane fade" id="employee-info" role="tabpanel" aria-labelledby="employee-tab" >
						<h3>Employment Data Info</h3>
						<form class="form-horizontal" role="form" autocomplete="off">
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Joined Date:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8">
							  <input id="txtJoinedDate" class="form-control form-control-sm" type="text" value="" readonly>
							</div>
							<!-- <div id="reportrange_right" class="form-control col-lg-3 col-sm-8">
							  <span>Mon 16 Jul 18</span> <b class="caret"></b><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
							</div> -->
				  		 </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Office:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8" id="divOffice">
								<select id="txtOffices" class="form-control form-control-sm">
								</select>
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Department:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8" id="divDepartment">
								<select id="txtDept" class="form-control form-control-sm">
								</select>
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Position:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8" id="divPosition">
								<select id="txtPositions" class="form-control form-control-sm">
								</select>
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Status:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8" id="divEEcategory">
								<select id="txtEECat" class="form-control form-control-sm">
								</select>
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Ranking:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8" id="divRanking1">
								<select id="txtRanking" class="form-control form-control-sm"></select>
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Direct Head:<span class="text-danger" id="">*</span></label>
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
							<label class="col-lg-3 col-sm-4 control-label">Shift Schedule:<span class="text-danger" id="">*</span></label>
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
							<label class="col-lg-3 col-sm-4 control-label">Email:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8">
							  <input id="txtEmailAddress1" class="form-control form-control-sm" type="email" value="">
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Office Number:<span class="text-danger" id="">*</span></label>
							<div class="col-lg-8 col-sm-8">
							  <input id="txtOfficeNo" class="form-control form-control-sm" type="text" value="">
							</div>
						  </div>
						  <div class="form-group row">
							<label class="col-lg-3 col-sm-4 control-label">Skype:<span class="text-danger" id="">*</span></label>
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
									<label class="col-lg-3 col-sm-4 control-label">End of Probation Date:</label>
									<div class="col-lg-8 col-sm-8">
									  <!-- <input id="txtProbationPeriod" class="form-control form-control-sm" type="date" value=""> -->
									  <input id="txtRegularizationDate" class="form-control form-control-sm" type="text" value="">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-4 control-label">Last Employment Date:</label>
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
							<div class="col-lg-8 col-sm-8">
								<input id="btnCancel3" type="reset" class="btn btn-sm btn-secondary" value="Cancel">
								<input id="btnSaveChanges3" type="button" class="btn btn-sm btn-primary" value="Save Changes">
							</div>
						</div>
					</div>
					<?php } ?>	

					<?php if(isset($accessitems->hasacctsettingstab)) { ?>
					<div class="tab-pane fade" id="accountsettings-info" role="tabpanel" aria-labelledby="accountsettings-tab" >
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
								<input id="btnSaveChanges4" type="button" class="btn btn-sm btn-primary" value="Save Changes">
							</div>
						</div>
					</div>	
					<?php } ?>	

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

<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly/>
<input type="hidden" id="eeid" name="eeid" value="" readonly/>
<input type="hidden" id="eeini" name="eeini" value="" readonly/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly/>
<input type="hidden" id="sesid" name="sesid" value="" readonly/>
<input type="hidden" id="profilegroup" name="profilegroup" value="" readonly/>
<input type="hidden" id="iniexist" name="iniexist" value="1" readonly/>
<input type="hidden" name="isadmin" id="isadmin" value="<?php echo hasAccess(2); ?>" readonly>