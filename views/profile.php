<div class="card shadow mb-4">
    <div class="card-header py-3 border-bottom-danger">
      <div class="row">
        <div class="col-9" style="margin: auto;"> 
            <h3 class="m-0 font-weight-bold text-gray-600" id="profilename">&nbsp;</h3>
        </div> 
        <div class="col-3" id="users">
            <input class="form-control" list="dataees" type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly/>
            <input class="form-control" list="dataees" type="hidden" id="eeid" name="eeid" value="<?php echo $userid; ?>" readonly/>
            <span>
                <button id="btnBackToEeList" type="button" class="btn btn-sm btn-danger float-right" style="margin-left:10px;" hidden><i class="fas fa-arrow-left"></i> Back</button>
                <div class="d-xl-flex d-lg-flex d-md-flex d-sm-flex d-none float-right">
                    <button id="btnEditContact" type="button" class="btn btn-sm btn-danger  " style="margin-right:5px;" hidden><i class="fas fa-edit"></i> Edit Contact Info</button>
                </div>
                <div class="d-xl-none d-lg-none d-md-none d-sm-none d-flex float-right">
                    <button id="btnEditContactSmall" type="button" class="btn btn-sm btn-danger "  style="margin-right:5px;" hidden><i class="fas fa-edit"></i></button>
                </div>
                <?php
                    if(isset($_GET['action'])){
                        $action = $_GET['action'];
                        if(isset($_GET['s'])){
                            $status = base64_decode($_GET['s']);
                        } else {
                            $status = 'inactive';
                        }
                        
                        if(base64_decode($action) == 'viewprofile'){
                            if((isset($accessitems->canupdateinactive) && $status == 'inactive') || (isset($accessitems->canupdate) && $status != 'inactive')){
                                echo '<button id="btnEditProfile" type="button" class="btn btn-sm btn-danger float-right"><i class="fas fa-edit"></i> Edit Profile</button>';
                            }
                        }
                    }
                ?>
            </span>
            <span id='datalistees'>
                <datalist id="dataees"></datalist>
            </span>
        </div>
       </div>
    </div>
    <div class="card-body profile-info">
        <div class="row"> 
            <div class="col-md-4"> 
                <div class="profile-img" id="profileimg1">
                    <img src="<?php echo IMAGES; ?>ees/default.svg" alt=""/>
                </div >
                <div class="profile-work">
                    <!-- <p><h5 class="m-0 font-weight-bold text-gray-600" id="profilename" style="text-align: center"></h5></p> -->
                    <p>Joined Date</p>
                    <h6 id="headerJoinedDate"></h6>
                    <p>Designation</p>
                    <h6 id="headerDesignation"></h6>
                    <p>Department</p>
                    <h6 id="headerDepartment"></h6>
                    <p>Employee Rank</p>
                    <h6 id="headerEmpType"></h6>
                    <p>Employee Category</p>
                    <h6 id="headerEmpCat"></h6>
                    <p>Reports to</p>
                    <h6 id="headerReportsTo"></h6>
                    <span id="headerReportsToIndirect">
                        
                    </span>
                </div>
            </div>  
            <div class="col-md-8">
                <div class="profile-head">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Personal Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Employee Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="compben-tab" data-toggle="tab" href="#compensationandbenefits" role="tab" aria-controls="compensationandbenefits" aria-selected="false">Compensation and Benefits</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="certificates-tab" data-toggle="tab" href="#certificates" role="tab" aria-controls="certificates" aria-selected="false">Certificates</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="personal-tab">
                        <h3>Personal Information</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Salutation</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtSalutation"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Last Name</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtLastname"></p>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <label>First Name</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtFirstname"></p>
                            </div>
                         </div>
                         <div class="row">
                            <div class="col-md-6">
                                <label>Chinese Name</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtChinesename"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nationality</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtNationality"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Marital Status</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtMaritalStatus"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Date of Birth</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtBirthdate"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Gender</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtGender"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Government ID</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtGovertmentID"></p>
                            </div>
                        </div>
                        <h3>Passport Information</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Passport No.</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtPassportNo"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Issued Date</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtIssuedDate"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Passport Expiration Date</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtExpirationDate"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Issued Country</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtPassportCountry"></p>
                            </div>
                        </div>
                        <h3>Contact Info - Personal</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtEmailAddress"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Mobile Number</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtMobilePhone"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Home Phone</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtHomePhone"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>We chat</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtWechat"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Skype</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtSkype"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Whatsapp</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtWhatsapp"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>LinkedIn</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtLinkedin"></p>
                            </div>
                        </div>
                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <label>Permanent Address</label>
                            </div>
                            <div class="col-md-6">
                                <p>Zamboanga City</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Present Address</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtPermanentAddress"></p>
                            </div>
                        </div>
                        <h3>Emergency Contact</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Name</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtEmergencyContactPerson"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Phone Number</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtEmergencyPhoneNo"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Relationship</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtRealtionship"></p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="employee-tab">
                        <h3>Employment Data</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Joined Date</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtJoinedDate"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Office</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtOffice"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Position</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtPosition"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Department</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtDepartment"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Status</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtEECategory"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Ranking</label>
                            </div>
                            <div class="col-md-6">
                                <p id="dataRanking"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Direct Head</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtReportsto"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Indirect Head</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtReportstoindirect"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Shift Schedule</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtShiftSched"></p>
                            </div>
                        </div>
                        <div class="row" style="display: none">
                            <div class="col-md-6">
                                <label>Subordinates</label>
                            </div>
                            <div class="col-md-6">
                                <p>Rey Castanares</p>
                            </div>
                        </div>
                        <h3>Work Contact Details</h3>						
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtEmailAddress1"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Office Number</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtOfficeNo"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Skype</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtSkype1"></p>
                            </div>
                        </div>
                        <div class="row" style="display: none">
                            <div class="col-md-6">
                                <label>Whatsapp</label>
                            </div>
                            <div class="col-md-6">
                                <p>vivencia.velasco11</p>
                            </div>
                        </div>
                        <div class="row" style="display: none">
                            <div class="col-md-6">
                                <label>WeChat</label>
                            </div>
                            <div class="col-md-6">
                                <p>vivencia.velasco</p>
                            </div>
                        </div>
						<h3>Other Information</h3>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Probation Period</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                <p id="txtProbationPeriod"></p>
                            </div>
                        </div>
                        <div id="probationdetails_view" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Probation Start Date</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                    <p id="txtProbationStartDate_view"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Probation End Date</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                    <p id="txtProbationEndDate_view"></p>
                                </div>
                            </div>
                        </div>
                        <div id="regularizationdetails_view">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Regularization Date</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                    <p id="txtRegularizationDate_view"></p>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-6">
                                    <label>Employment End Date</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                    <p id="txtEndOfEmploymentDate_view"></p>
                                </div>
                            </div>
                        </div>
                        <div id="contractdetails_view" style="display: none">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Start of Contract Date</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                    <p id="txtStartContractDate_view"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>End of Contract Date</label>
                                </div>
                                <div class="col-md-6">
                                    <!-- <p>January 1, 2019 to June 1, 2019</p> -->
                                    <p id="txtEndContractDate_view"></p>
                                </div>
                            </div>
                        </div>
                        <h5>Visa Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Type of Visa</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>Yes</p> -->
                                <p id="txtTypeOfVisa_view"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Visa Start Date</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>Yes</p> -->
                                <p id="txtVisaStartDate_view"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Visa expiration date</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtVisaExpDate"></p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label>Visa processed by Abacare?</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>Yes</p> -->
                                <p id="dataVisaProcessedbyAbac"></p>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Termination Period</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>N/A</p> -->
                                <p id="txtTerminationPeriod"></p>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Probation Completion Date</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtProbationComplete_view"></p>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Last working date</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtLastWorkingDt_view"></p>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Effective Date</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtEffectiveDate_view"></p>
                            </div>
                        </div>
                        
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Co name of 1st contract signed</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtcompanynamefirstctrcsigned_view"></p>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Date of most recent contract effective</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtRecentCtrctDt_view"></p>
                            </div>
                        </div>
                        <div class="row" hidden>
                            <div class="col-md-6">
                                <label>Actual place of work currently</label>
                            </div>
                            <div class="col-md-6">
                                <!-- <p>01/03/27</p> -->
                                <p id="txtActPlaceofwork_view"></p>
                            </div>
                        </div>
                    </div>                      
                    <div class="tab-pane fade" id="compensationandbenefits" role="tabpanel" aria-labelledby="compensationandbenefits-tab">
                        <h3>Position History</h3>
                        <div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0">
								<thead class="thead-dark">
									<tr>
										<th width="30%">Position</th>
										<th width="20%" class="text-right">Start date</th>
										<th width="20%" class="text-right">End date</th>
										<th width="20%" class="text-right">Tenure</th>
										<th width="10%" class="text-right">Rate</th>
										<th width="20%" class="text-right">Remarks</th>
									</tr>
								</thead>  
								<tbody id="position_history">
									<tr>
										
									</tr>
								</tbody>
							</table>
						</div>
                        <div class="row">
                                <div class="col-lg-6">
                                    <h3>Benefits</h3>
                                </div>
                                <div class="col-lg-6" style="text-align: right;"> 
                                   Year: <label style="padding-right: 2vh; width: 20vh; white-space: nowrap;">
                                    <select class="form-control form-control-sm" id="benefitsleaveyear" name="benefitsleaveyear" aria-controls="">
                                        <!-- <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option> -->
                                    </select>
                                </div>
                            </div>  
						<div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0" id="leave_benefits">
								<thead class="thead-dark">
									<tr>
                                        <th width="60%">Leave Type</th>
                                        <th width="10%" class="text-center">Entitled</th>
                                        <th width="10%" class="text-center">Pending</th>
                                        <th width="10%" class="text-center">Taken</th>
                                        <th width="10%" class="text-center">Balance</th>
									</tr>
								</thead>  
								<tbody>
									<!-- <tr>
										<td>Annual Leave</td>
										<td>20 day(s)</td>
										<td>10 day(s)</td>
									</tr> -->
								</tbody>
							</table>
						</div>
						<h3>Insurance</h3>
						<div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0" id="hmo_benefits">
								<thead class="thead-dark">
									<tr>
										<th width="80%">Health Insurance Coverage</th>
										<th width="20%" class="text-right">Coverage Amount</th>
									</tr>
								</thead>  
								<tbody>
									<tr>
										<!-- <td>Full Coverage</td>
										<td>$5,000 HKD</td> -->
									</tr>
								</tbody>
							</table>
						</div>
                        <h3>Other Benefits</h3>
                        <div class="table-responsive"> 
							<table class="table table-sm table-bordered" width="100%" cellspacing="0" id="">
								<thead class="thead-dark">
									<tr>
										<th width="80%">Name</th>
										<th width="20%" class="text-right">Amount</th>
									</tr>
								</thead>  
								<tbody>
									<tr>
                                        <td>Monthly gross salary in local currency shown in contract</td>
										<td id="txtMonthlygrossSal_view" class="text-right"></td>
									</tr>
									<tr>
                                        <td>Monthly employer's contribution (mpf, mfp, sss)</td>
										<td id="txtxMosEmpContri_view" class="text-right"></td>
									</tr>
									<tr>
                                        <td>Monthly A+ medical ins in HKD(write 0 if nil)</td>
										<td id="txtmosaplusmedinshkd_view" class="text-right"></td>
									</tr>
									<tr>
                                        <td>Monthly medical insurance (for supplimentary med ins, not A+ write 0 if nil)in local currency</td>
										<td id="txtmosmedinsinlocalcur_view" class="text-right"></td>
									</tr>
									<tr>
                                        <td>Monthly business expenses allowance written in contract in local currency (write 0 if nil)</td>
										<td id="txtmosbusexpinilocalcur_view" class="text-right"></td>
									</tr>
								</tbody>
							</table>
						</div>
                    </div>
                    <div class="tab-pane fade" id="certificates" role="tabpanel" aria-labelledby="certificates-tab">
                        <div class="row">
							<div class="col-lg-6">
								<h3>Certification</h3>
							</div>
						</div>
						<form class="form-horizontal" role="form">
							<div class="table-responsive" width="101%">
								<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="width:98%; table-layout:fixed;">
									<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="certificatedatatable_view" width="100%" cellspacing="0">
										<thead class="thead-dark">
											<tr>
												<th width="35%">Name</th>
												<th width="35%">Organization</th>
												<th width="15%">Issued Date</th>
												<th width="15%">Expiry Date</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>	
								</div>		
							</div>
                            <small style="float:right; color:red; margin-right:1.5vw;">* Click to show the attachment</small>			
						</form>
                    </div>
                </div>
            </div>
        </div>  
    </div> 
</div>
<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly disabled />
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly disabled />
<input type="hidden" id="eeid" name="eeid" value="" readonly disabled />
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly disabled />
<input type="hidden" id="sesid" name="sesid" value="" readonly disabled />
<input type="hidden" id="profilegroup" name="profilegroup" value="profile" readonly disabled />    