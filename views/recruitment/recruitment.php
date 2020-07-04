<?php 
	if(!hasAccess(0)){
		echo '<script> window.location = "404"; </script>';
		exit();
	}
	$page = 'page';
?>
<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Recruitment</h3>
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
							<a class="nav-link active" id="applicants-tab" data-toggle="tab" href="#applicants" role="tab" aria-controls="applicants" aria-selected="false">Applicants</a>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link" id="consideration-tab" data-toggle="tab" href="#consideration" role="tab" aria-controls="consideration" aria-selected="false">Consideration</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link" id="interview-tab" data-toggle="tab" href="#interview" role="tab" aria-controls="interview" aria-selected="false">Interview</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="finalinterview-tab" data-toggle="tab" href="#finalinterview" role="tab" aria-controls="finalinterview" aria-selected="false">Final Interview</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="hire-tab" data-toggle="tab" href="#hire" role="tab" aria-controls="hire" aria-selected="false">Hire</a>
						</li>
					</ul>
				</div>
				<div class="tab-content details-tab" id="myTabContent">
					<div class="tab-pane fade show active" id="applicants" role="tabpanel" aria-labelledby="applicants-tab">
						<div class="row">
							<div class="col-lg-8 col-sm-8 col-xs-12"></div>
							<label class="col-lg-2 col-sm-2 col-xs-12 text-right">Position: </label>
							<div class="col-lg-2 col-sm-2 col-xs-12 text-right" id="dataPositions">
								<select id="txtPosition" name="txtPosition" class="form-control"></select>
							</div>
						</div>
						<div class="row">										
						    <div class="col-md-12 col-sm-12 col-xs-12" id="applicantsdatatable">
								<table class="table table-sm table-bordered" width="100%" cellspacing="0">
									<thead class="thead-dark">
										<tr>
											<th class="text-center" width="2%"><input type="checkbox" name="chkAll" id="chkAll" /></th>
											<th class="text-center" width="23%">Name</th>
											<th class="text-center" width="15%">Email Address</th>
											<th class="text-center" width="10%">Contact No</th>
											<th class="text-center" width="10%">Age</th>
											<th class="text-center" width="10%">Gender</th>
											<th class="text-center" width="10%">Attachments</th>
											<th class="text-center" width="10%">Position</th>
											<th class="text-center" width="10%">Status</th>
										</tr>
									</thead>  
									<tbody></tbody>
								</table>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-md-2 col-sm-2 col-xs-12 right">
										<select id="txtNewApplicantStatus" name="txtNewApplicantStatus" class="form-control">
											<option value=""></option>
											<!-- <option value="2">Consideration</option> -->
											<option value="3">Interview</option>
											<option value="-1">Not Qualified</option>
										</select>
									</div>
									<div class="col-md-10 col-sm-10 col-xs-12 right">
										<input type="button" name="btnApplicantsSubmit" id="btnApplicantsSubmit" class="btn btn-grad btn-danger" value="Submit" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="consideration" role="tabpanel" aria-labelledby="consideration-tab">
						<div class="row">										
						    <div class="col-md-12 col-sm-12 col-xs-12" id="considerationdatatable"></div>
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-md-2 col-sm-2 col-xs-12 right">
										<select id="txtConsiApplicantStatus" name="txtConsiApplicantStatus" class="form-control">
											<option value=""></option>
											<option value="3">Interview</option>
											<option value="-1">Not Qualified</option>
										</select>
									</div>
									<div class="col-md-10 col-sm-10 col-xs-12 right">
										<input type="button" name="btnConsiApplicantsSubmit" id="btnConsiApplicantsSubmit" class="btn btn-grad btn-danger" value="Submit" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="interview" role="tabpanel" aria-labelledby="interview-tab">
						<div class="row">										
						    <div class="col-md-12 col-sm-12 col-xs-12" id="interviewdatatable"></div>
						</div>
					</div>
					<div class="tab-pane fade" id="finalinterview" role="tabpanel" aria-labelledby="finalinterview-tab">
						<div class="row">										
						    <div class="col-md-12 col-sm-12 col-xs-12" id="finalinterviewdatatable"></div>
						</div>
					</div>
					<div class="tab-pane fade" id="hire" role="tabpanel" aria-labelledby="hire-tab">
						<div class="row">										
						    <div class="col-md-12 col-sm-12 col-xs-12" id="hiredatatable"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once('frmapplicant.php'); ?>

<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" />
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
<input type="hidden" id="txtbulkappstatus" name="txtbulkappstatus" value="" />