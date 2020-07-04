<div class="">
	<div class="row">
		<div class="col-lg-7 employee-directory">
	
			<!-- <form> -->
			<div class="row">
				<div class="col-md-12">
					
				</div>
			</div>
			<div class="form-inline row mb-2">
				<!-- <label for="contact-list" class="col-sm-3 col-form-label"></label> -->
				<!-- <div class="col-sm-6">
					<b><span id="datarowsfound"></span></b> Employees found!
				</div>
				<div class="col-sm-4">
					<input type="text" id="searchEmployee" class="form-control" aria-describedby="searchEmployee" placeholder="Search employee name" />
				</div>
				<div class="col-sm-2">
					<input type="button" id="btnSearch" class="btn btn-danger btn-grad" aria-describedby="btnSearch" value=" Search " />
				</div> -->
				<!-- <div class="pull-right col-sm-2">
					<a href="#"><i class="fas fa-th-large"></i></a>
					<a href="#"><i class="fas fa-th-list"></i></a>					
				</div> -->
			</div>
			<!-- </form> -->
			<div class="col-md-12">
				<div class="table-responsive" style="width: 101%;">
					<!-- <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="table-layout: fixed; width: 98% !important;"> -->
						<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="contactlistdatatable" width="100%" cellspacing="0">
							<thead class="thead-dark">
						        <tr>
						            <th>ini</th>
						            <th>Fullname</th>
						            <th>Position</th>
						            <th>Office</th>
						        </tr>
						    </thead>
						  	<tbody style="cursor: pointer;">
						  		
						  	</tbody>
						</table>	
					<!-- </div>		 -->
				</div>				
			</div>
			
		
		</div>
		<div class="col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary">
                  <h6 class="m-0 font-weight-bold text-white">Employee Details</h6>
                  <div class="dropdown no-arrow d-admin-block">
					  <!-- <img src="img/edit.png" width="20" border="0" /> -->
					<?php 
						// if(isGM()){
						// 	echo '<p class="m-0 font-weight-bold text-white" onClick="return viewgm();" style="cursor: pointer;" id="editProfile">VIEW</p>';
						// } else if(hasAccess(2)){
					  	// 	echo '<p class="m-0 font-weight-bold text-white" onClick="return editee();" style="cursor: pointer;" id="editProfile">EDIT</p>';
						// }
					?>

                    <!-- <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Edit</a>
                      <a class="dropdown-item muted" href="#">Contact</a>

                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>

                    </div> -->
                  </div>
                </div>
				 
                <!-- Card Body -->
                <div class="card-body">
					<div class="profile-header text-center mb-4">
						<p id="profilepic"><img style="width: 50%; height: auto" src="img/ees/default.svg"></p>
						<!-- <img style="width: 50%; height: auto" id="profilepic" src="img/ees/default.svg"> -->
						<p class="node-name" id="txtFullName" style="font-size: 25px;"></p>
						<p class="node-title" id="txtPosition" style="font-size: 18px;"></p>
						<!-- <p class="node-desc" id="txtOffice"></p> -->
					</div>
					<div style="display: none">
						<h5>Personal Information</h5>
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
					</div>
					<h5>Work Contact Details</h5>
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
                                <label>Office Phone no</label>
                            </div>
                            <div class="col-md-6">
                                <p id="dataOfcPhoneNo"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtWorkEmail"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Mobile</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtMobileNo"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Skype</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtWorkSkype"></p>
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
                                <label>WeChat</label>
                            </div>
                            <div class="col-md-6">
                                <p id="txtWeChat"></p>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-md-6">
                                <label>Reporting to</label>
                            </div>
                            <div class="col-md-6">
                                <p id="dataReportingTo"></p>
                            </div>
                        </div>
						<div class="row" id="dataReportingToIndirect">
                            
                        </div>
					
					
					<!-- <h5>Emergency Contact</h5>
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
							<p id="txtRelationship"></p>
						</div>
					</div> -->
                </div>
              </div>			
		</div>
	</div>
</div>



<!--<input type="hidden" id="abaini" name="abaini" value="<?php //echo $abaini; ?>"/>-->
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>"/>
<input type="hidden" id="sesid" name="sesid" value=""/>
<input type="hidden" id="profilegroup" name="profilegroup" value="contactlist"/>
<!-- <input type="" id="eejt" name="eejt" value="<?php echo $eejt; ?>"/> -->
<!--<input type="hidden" id="ofc" name="ofc" value="<?php //echo $ofc; ?>"/>-->
