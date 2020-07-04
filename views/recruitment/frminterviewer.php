<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="frmInterviewer">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-body">
			<fieldset>
				<legend><h5><u>Interviewer</u></h5></legend>
		   		<div class="row">
		   			<label class="col-lg-2 col-sm-2 col-xs-12">Interviewer <span style="color: '#e74a3b';">*</span></label>
		   			<div class="col-lg-4 col-sm-4 col-xs-12" id="divees">
		   				<select id="txtInterviewer" name="txtInterviewer" class="form-control"></select>
		   			</div>
		   			<label class="col-lg-2 col-sm-2 col-xs-12">Interview Type <span style="color: '#e74a3b';">*</span></label>
		   			<div class="col-lg-4 col-sm-4 col-xs-12">
		   				<select id="txtIntType" name="txtIntType" class="form-control">
		   					<option value="ac">Audio Call</option>
		   					<option value="oo" selected>One-on-one</option>
		   					<option value="vc">Video Call</option>
		   				</select>
		   			</div>
		   		</div>
		   		<div class="row">
		   			<label class="col-lg-2 col-sm-2 col-xs-12">Interview Date <span style="color: '#e74a3b';">*</span></label>
		   			<div class="col-lg-4 col-sm-4 col-xs-12">
		   				<input type="text" id="txtIntDate" name="txtIntDate" class="form-control" readonly value="<?php echo date("D d M Y"); ?>" />
		   			</div>

		   			<label class="col-lg-2 col-sm-2 col-xs-12">Interview Time <span style="color: '#e74a3b';">*</span></label>
		   			<div class="col-lg-4 col-sm-4 col-xs-12">
		   				<select id="txtIntTime" name="txtIntTime" class="form-control">
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
		   		<span id="divRemarks" style="display: none;">
			   		<div class="row">
			   			<label class="col-lg-2 col-sm-2 col-xs-12">Remarks</label>
			   			<div class="col-lg-10 col-sm-10 col-xs-12">
			   				<textarea id="txtRemarks" name="txtRemarks" class="form-control"></textarea>
			   			</div>
			   		</div>
			   		<div class="row">
			   			<label class="col-lg-2 col-sm-2 col-xs-12"></label>
			   			<div class="col-lg-10 col-sm-10 col-xs-12">
			   				<input type="checkbox" id="chkDone" name="chkDone" /> Check me if interview is done
			   			</div>
			   		</div>
			   	</span>
		   		<br />
		   		<div class="row" id="divBtns">
			   		<div class="col-lg-12 col-sm-12 col-xs-12 text-right">
			   			<button class="btn btn-grad btn-danger" id="btnSaveInterviewer">SAVE INTERVIEWER</button>
			   			<input type="button" class="btn btn-grad btn-danger" id="btnCancelInterviewer" value=" CANCEL" >
			   		</div>
			   	</div>
			</fieldset>
	  	</div>
    </div>
  </div>
</div>
