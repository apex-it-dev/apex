<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="frmApplicant">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<h5><u>Lou AMAYA</u></h5>
		      		<div class="row"> 
						<label class="col-lg-2 col-sm-3 col-xs-12"><b>Email Address</b></label>
						<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> lou.amaya@abacare.com</div>

				   		<label class="col-lg-2 col-sm-2 col-xs-12"><b>Age</b></label>
						<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> 41-45</div>
				   	</div>
				   	<div class="row"> 
						<label class="col-lg-2 col-sm-2 col-xs-12"><b>Contact No</b></label>
						<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> 123456789</div>
				   
						<label class="col-lg-2 col-sm-2 col-xs-12"><b>Gender</b></label>
						<div class="col-lg-4 col-sm-2 col-xs-12"><b>:</b> Male</div>
				   	</div>
				   	<div class="row"> 
						<label class="col-lg-2 col-sm-2 col-xs-12"><b>Status</b></label>
						<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> New</div>

						<label class="col-lg-2 col-sm-2 col-xs-12"><b>Attachments</b></label>
						<div class="col-lg-4 col-sm-4 col-xs-12"><b>:</b> 
							<a href="#" target="_blank"><img src="img/pdf-icon.png" width="30" border="0" /></a>
							<a href="#" target="_blank"><img src="img/word-icon.png" width="30" border="0" /></a>
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
							<tbody>
								<tr>
									<td>Patrick Marie HERBET </td>
									<td>CEO</td>
									<td>Hong Kong</td>
									<td>Call</td>
									<td>Wed 26 Feb 20</td>
									<td>14:00</td>
									<td> </td>
									<td>On Going</td>
								</tr>
							</tbody>
						</table>
						<div class="row">
					   		<div class="col-lg-12 col-sm-12 col-xs-12 text-right">
					   			<input type="button" class="btn btn-grad btn-danger" id="btnNewInterviewer" value=" ADD NEW INTERVIEWER" >
					   		</div>
					   	</div>
				   	</div>
				   	
				   	<div class="row" id="divNewInterviewers" style="display: none;">
				   		<div class="col-lg-12 col-sm-12 col-xs-12">
					   		<div class="row">
					   			<label class="col-lg-2 col-sm-2 col-xs-12">Interviewee</label>
					   			<div class="col-lg-4 col-sm-4 col-xs-12" id="divee">
					   				<select id="txtInterviewee" name="txtInterviewer" class="form-control"></select>
					   			</div>
					   			<label class="col-lg-2 col-sm-2 col-xs-12">Interview Type</label>
					   			<div class="col-lg-4 col-sm-4 col-xs-12">
					   				<select id="txtIntType" name="txtIntType" class="form-control">
					   					<option value="oo">One-on-one</option>
					   					<option value="ac">Audio Call</option>
					   					<option value="vc">Video Call</option>
					   				</select>
					   			</div>
					   		</div>
					   		<div class="row">
					   			<label class="col-lg-2 col-sm-2 col-xs-12">Interview Date</label>
					   			<div class="col-lg-4 col-sm-4 col-xs-12">
					   				<input type="text" id="txtIntDate" name="txtIntDate" class="form-control" readonly />
					   			</div>

					   			<label class="col-lg-2 col-sm-2 col-xs-12">Interview Time</label>
					   			<div class="col-lg-4 col-sm-4 col-xs-12">
					   				<select id="txtIntType" name="txtIntType" class="form-control">
						   				<option value="00:00">00:00</option>
			                            <option value="00:30">00:30</option>
			                            <option value="01:00">01:00</option>
			                            <option value="01:30">01:30</option>
			                            <option value="02:00">02:00</option>
			                            <option value="02:30">02:30</option>
			                            <option value="03:00">03:00</option>
			                            <option value="03:30">03:30</option>
			                            <option value="04:00">04:00</option>
			                            <option value="04:30">04:30</option>
			                            <option value="05:00">05:00</option>
			                            <option value="05:30">05:30</option>
			                            <option value="06:00">06:00</option>
			                            <option value="06:30">06:30</option>
			                            <option value="07:00">07:00</option>
			                            <option value="07:30">07:30</option>
			                            <option value="08:00">08:00</option>
			                            <option value="08:30">08:30</option>
			                            <option value="09:00">09:00</option>
			                            <option value="09:30">09:30</option>
			                            <option value="10:00">10:00</option>
			                            <option value="10:30">10:30</option>
			                            <option value="11:00">11:00</option>
			                            <option value="11:30">11:30</option>
			                            <option value="12:00">12:00</option>
			                            <option value="12:30">12:30</option>
			                            <option value="13:00">13:00</option>
			                            <option value="13:30">13:30</option>
			                            <option value="14:00">14:00</option>
			                            <option value="14:30">14:30</option>
			                            <option value="15:00">15:00</option>
			                            <option value="15:30">15:30</option>
			                            <option value="16:00">16:00</option>
			                            <option value="16:30">16:30</option>
			                            <option value="17:00">17:00</option>
			                            <option value="17:30">17:30</option>
			                            <option value="18:00">18:00</option>
			                            <option value="18:30">18:30</option>
			                            <option value="19:00">19:00</option>
			                            <option value="19:30">19:30</option>
			                            <option value="20:00">20:00</option>
			                            <option value="20:30">20:30</option>
			                            <option value="21:00">21:00</option>
			                            <option value="21:30">21:30</option>
			                            <option value="22:00">22:00</option>
			                            <option value="22:30">22:30</option>
			                            <option value="23:00">23:00</option>
			                            <option value="23:30">23:30</option>
			                        </select>
					   			</div>
					   		</div>
					   		<div class="row">
					   			<label class="col-lg-2 col-sm-2 col-xs-12">Remarks</label>
					   			<div class="col-lg-10 col-sm-10 col-xs-12">
					   				<textarea id="txtRemarks" name="txtRemarks" class="form-control"></textarea>
					   			</div>
					   		</div>
					   		<div class="row">
						   		<div class="col-lg-12 col-sm-12 col-xs-12 text-right">
						   			<input type="button" class="btn btn-grad btn-danger" id="btnSaveInterviewer" value=" SAVE INTERVIEWER" >
						   			<input type="button" class="btn btn-grad btn-danger" id="btnCancelInterviewer" value=" CANCEL" >
						   		</div>
						   	</div>
						</div>
				   	</div>

				   	<br />
				   	<h5><u>Questions</u></h5>
				   	<div class="row" id="divQuestions">
				   		<table class="table table-sm table-bordered" width="100%" cellspacing="0">
							<thead class="thead-dark">
								<tr>
									<th width="20%">Interviewer</th>
									<th width="80%">Question</th>
								</tr>
							</thead>  
							<tbody>
								<tr>
									<td>Patrick Marie HERBET </td>
									<td>Tell me about yourself?</td>
								</tr>
							</tbody>
						</table>
						<div class="row">
					   		<div class="col-lg-12 col-sm-12 col-xs-12 text-right">
					   			<input type="button" class="btn btn-grad btn-danger" id="btnNewQuestion" value=" ADD NEW QUESTION" >
					   		</div>
					   	</div>
				   	</div>

				   	<div class="row" id="divNewQuestions" style="display: none;">
				   		<div class="col-lg-12 col-sm-12 col-xs-12">
					   		<div class="row">
					   			<label class="col-lg-2 col-sm-2 col-xs-12">Question</label>
					   			<div class="col-lg-10 col-sm-10 col-xs-12" id="divee">
					   				<textarea id="txtQuestion" name="txtQuestion" class="form-control"></textarea>
					   			</div>
					   		</div>
					   		<div class="row">
						   		<div class="col-lg-12 col-sm-12 col-xs-12 text-right">
						   			<input type="button" class="btn btn-grad btn-danger" id="btnSaveQuestion" value=" SAVE QUESTION" >
						   			<input type="button" class="btn btn-grad btn-danger" id="btnCancelQuestion" value=" CANCEL" >
						   		</div>
						   	</div>
						</div>
				   	</div>
				</div>
			</div>
	  	</div>
    </div>
  </div>
</div>
