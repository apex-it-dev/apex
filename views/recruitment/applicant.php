<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Applicant</h3>
			 </div>
			<div class="col-md-2">
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-12">
				<h5><u><span id="dataApplicantName"></span></u></h5>
	      		<div class="row"> 
					<label class="col-lg-2 col-sm-3 col-xs-12"><b>Email Address</b></label>
					<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> <span id="dataEmail"></span></div>

			   		<label class="col-lg-2 col-sm-2 col-xs-12"><b>Age</b></label>
					<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> <span id="dataAge"></span></div>
			   	</div>
			   	<div class="row"> 
					<label class="col-lg-2 col-sm-2 col-xs-12"><b>Contact No</b></label>
					<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> <span id="dataContactNo"></span></div>
			   
					<label class="col-lg-2 col-sm-2 col-xs-12"><b>Gender</b></label>
					<div class="col-lg-4 col-sm-2 col-xs-12"><b>:</b> <span id="dataGender"></span></div>
			   	</div>
			   	<div class="row"> 
					<label class="col-lg-2 col-sm-2 col-xs-12"><b>Status</b></label>
					<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> <span id="dataStatus"></span></div>

					<label class="col-lg-2 col-sm-2 col-xs-12"><b>Attachments</b></label>
					<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> 
						<span id="dataCL"></span>
						<span id="dataCV"></span>
						<!-- <a href="#" target="_blank"><img src="img/pdf-icon.png" width="30" border="0" /></a>
						<a href="#" target="_blank"><img src="img/word-icon.png" width="30" border="0" /></a> -->
					</div>
			   	</div>

			   	<br />
			   	<h5><u>Interviewers</u></h5>
			   	<div class="row" id="divInterviewers">
			   		<table class="table table-sm table-bordered" width="100%" cellspacing="0">
						<thead class="thead-dark">
							<tr>
								<th width="20%">Name</th>
								<th width="10%">Job Title</th>
								<th width="10%">Office</th>
								<th width="10%">Type</th>
								<th width="10%">Date</th>
								<th width="10%">Time</th>
								<th width="20%">Remarks</th>
								<th width="10%">Status</th>
							</tr>
						</thead>  
						<tbody></tbody>
					</table>
				</div>
				<div class="row">
			   		<div class="col-lg-12 col-sm-12 col-xs-12">
			   			<input type="button" class="btn btn-grad btn-danger" id="btnNewInterviewer" value=" ADD NEW INTERVIEWER" style="display: none;" />
			   		</div>
			   	</div>
			   	
			   	<hr />

			   	<h5><u>Questions</u></h5>
			   	<div class="row" id="divQuestions">
			   		<table class="table table-sm table-bordered" width="100%" cellspacing="0">
						<thead class="thead-dark">
							<tr>
								<th width="20%">Interviewer</th>
								<th width="80%">Question</th>
							</tr>
						</thead>  
						<tbody></tbody>
					</table>
				</div>
				<div class="row">
			   		<div class="col-lg-12 col-sm-12 col-xs-12">
			   			<input type="button" class="btn btn-grad btn-danger" id="btnNewQuestion" value=" ADD NEW QUESTION" style="display: none;" />
			   		</div>
			   	</div>
			</div>
		</div>
	</div>
</div>

<?php include_once('frminterviewer.php'); ?>
<?php include_once('frmquestion.php'); ?>

<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" />
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" />
<input type="hidden" id="dept" name="dept" value="<?php echo $dept; ?>" />
<input type="hidden" id="sesid" name="sesid" value="<?php echo $_GET['id']; ?>" />
<input type="hidden" id="appid" name="appid" value="" />
<input type="hidden" id="interviewerid" name="interviewerid" value="" />
<input type="hidden" id="questionid" name="questionid" value="" />
<input type="hidden" id="interviewid" name="interviewid" value="" />