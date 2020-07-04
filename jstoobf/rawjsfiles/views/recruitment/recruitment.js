$(function(){
	$("#btnApplicantsSubmit").on("click", function(){
		var selected = new Array();
		$("input:checkbox[name=chkNewAppID]:checked").each(function() {
		   selected.push($(this).val());
		});
		// console.log(selected);
		if(selected.length > 0){
			if( $("#txtNewApplicantStatus").val() == "" ){
				alert("Please choose status to update selected applicants.");
				return false;
			}
			$("#txtbulkappstatus").val("new");
			updateBulkAppStatus(selected);
		}else{
			alert("Please select applicants to update status.");
			return false;
		}
	});

	$("#btnConsiApplicantsSubmit").on("click", function(){
		var selected = new Array();
		$("input:checkbox[name=chkConsiAppID]:checked").each(function() {
		   selected.push($(this).val());
		});
		// console.log(selected);
		if(selected.length > 0){
			if( $("#txtConsiApplicantStatus").val() == "" ){
				alert("Please choose status to update selected applicants.");
				return false;
			}
			$("#txtbulkappstatus").val("consi");
			updateBulkAppStatus(selected);
		}else{
			alert("Please select applicants to update status.");
			return false;
		}
	});

	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			loadDefault();
		}
	});
});

function loadDefault(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "loadDefault";

	var data = { "f": f };
	// console.log(data);
	// return false;

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			// return false;
			var apps = data['apps']['rows'];
			var careers = data['careers']['rows'];
			var interviewers = data['interviewers']['rows'];
			apps['careers'] = careers;
			apps['interviewers'] = interviewers;

			var htmlcareers = '';
			htmlcareers = '<select id="txtPosition" name="txtPosition" class="form-control">';
			htmlcareers += '<option value="">All</option>';
			for(var i=0;i<careers.length;i++){
				htmlcareers += '<option value="'+ careers[i]['careerid'] +'">'+ careers[i]['title'] +'</option>';
			}
			htmlcareers += '</select>';
			$("#dataPositions").html(htmlcareers);

			genApplicants(apps);
			// genConsideration(apps);
			genInterview(apps);
			genFinalInterview(apps);
			genHire(apps);

			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function genApplicants(data){
	// console.log(data);
	var careers = data['careers'];
	var position = '';
	var html = '';
	var getApp = '';
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th class="text-center" width="2%"><input type="checkbox" name="chkAllApp" id="chkAllApp" /></th>';
				html += '<th class="text-center" width="23%">Name</th>';
				html += '<th class="text-center" width="15%">Email Address</th>';
				html += '<th class="text-center" width="10%">Contact No</th>';
				html += '<th class="text-center" width="10%">Age</th>';
				html += '<th class="text-center" width="10%">Gender</th>';
				html += '<th class="text-center" width="10%">Attachments</th>';
				html += '<th class="text-center" width="10%">Position</th>';
				html += '<th class="text-center" width="10%">Status</th>';
			html += '</tr>';
		html += '</thead>';
		html += '<tbody>';
			var updStat = "";
			var resumeico = "";
			var coverletterico = "";
			for(var i=0;i<data.length;i++){
				if(data[i]['status'] == 0){
					html += '<tr>';
						html += '<td class="text-center"><input type="checkbox" name="chkNewAppID" id="chkNewAppID" value="'+ data[i]['applicantid'] +'" /></td>';
						html += '<td>'+ data[i]['firstname'] +' '+ data[i]['lastname'] +'</td>';
						html += '<td>'+ data[i]['emailaddress'] +'</td>';
						html += '<td>'+ data[i]['contactno'] +'</td>';
						html += '<td class="text-center">'+ data[i]['agedesc'] +'</td>';
						html += '<td class="text-center">'+ data[i]['genderdesc'] +'</td>';
						html += '<td class="text-center">';
							if(data[i]['coverletter'] == "" || data[i]['coverletter'] == null){}else{
								coverletterico = "";
								if(data[i]['filetype2'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
									coverletterico = "word";
								}else{
									coverletterico = "pdf";
								}
								html += '<a href="upload/applicants/'+ data[i]['coverletter'] +'" target="_blank"><img src="img/'+ coverletterico +'-icon.png" width="30" border="0" /></a> ';
							}

							if(data[i]['resume'] == "" || data[i]['resume'] == null){}else{
								resumeico = "";
								if(data[i]['filetype'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
									resumeico = "word";
								}else{
									resumeico = "pdf";
								}
								html += '<a href="upload/applicants/'+ data[i]['resume'] +'" target="_blank"><img src="img/'+ resumeico +'-icon.png" width="30" border="0" /></a> ';
							}
						html += '</td>';

						for(var a=0;a<careers.length;a++){
							if(careers[a]['careerid'] == data[i]['position']){
								position = careers[a]['title'];
								a = careers.length;
							}
						}
						html += '<td class="text-center">'+ position +'</td>';
						updStat = "return updateAppStatus('"+ data[i]['applicantid'] +"',this.value)";
						html += '<td class="text-center"><select class="form-control" name="txtStatus" id="txtStatus" onChange="'+ updStat +'">';
								html += '<option value="0">New</option>';
								// html += '<option value="2">Consideration</option>';
								html += '<option value="3">Interview</option>';
								html += '<option value="-1">Not Qualified</option>';
						html += '</select></td>';
					html += '</tr>';
				}
			}
		html += '</tbody>';
	html += '</table>';
	$("#applicantsdatatable").html(html);

	//select chkAllApp checkboxes
    $("#chkAllApp").change(function(){
        if ($(this).is(':checked')) {
            $('input[name=chkNewAppID]').each(function() {
                $(this).prop("checked", true); 
            });
        }
        else {
            $('input[name=chkNewAppID]').each(function() {
                $(this).prop("checked", false); 
            });
        }
    });
}

function genConsideration(data){
	// console.log(data);
	var careers = data['careers'];
	var position = '';
	var html = '';
	var getApp = '';
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th class="text-center" width="2%"><input type="checkbox" name="chkAllConsi" id="chkAllConsi" /></th>';
				html += '<th class="text-center" width="23%">Name</th>';
				html += '<th class="text-center" width="15%">Email Address</th>';
				html += '<th class="text-center" width="10%">Contact No</th>';
				html += '<th class="text-center" width="10%">Age</th>';
				html += '<th class="text-center" width="10%">Gender</th>';
				html += '<th class="text-center" width="10%">Attachments</th>';
				html += '<th class="text-center" width="10%">Position</th>';
				html += '<th class="text-center" width="10%">Status</th>';
			html += '</tr>';
		html += '</thead>';
		html += '<tbody>';
			var updStat = "";
			var resumeico = "";
			var coverletterico = "";
			for(var i=0;i<data.length;i++){
				if(data[i]['status'] == 2){
					html += '<tr>';
						html += '<td class="text-center"><input type="checkbox" name="chkConsiAppID" id="chkConsiAppID" value="'+ data[i]['applicantid'] +'" /></td>';
						html += '<td>'+ data[i]['firstname'] +' '+ data[i]['lastname'] +'</td>';
						html += '<td>'+ data[i]['emailaddress'] +'</td>';
						html += '<td>'+ data[i]['contactno'] +'</td>';
						html += '<td class="text-center">'+ data[i]['agedesc'] +'</td>';
						html += '<td class="text-center">'+ data[i]['genderdesc'] +'</td>';
						html += '<td class="text-center">';
							if(data[i]['coverletter'] == "" || data[i]['coverletter'] == null){}else{
								coverletterico = "";
								if(data[i]['filetype2'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
									coverletterico = "word";
								}else{
									coverletterico = "pdf";
								}
								html += '<a href="upload/applicants/'+ data[i]['coverletter'] +'" target="_blank"><img src="img/'+ coverletterico +'-icon.png" width="30" border="0" /></a> ';
							}

							if(data[i]['resume'] == "" || data[i]['resume'] == null){}else{
								resumeico = "";
								if(data[i]['filetype'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
									resumeico = "word";
								}else{
									resumeico = "pdf";
								}
								html += '<a href="upload/applicants/'+ data[i]['resume'] +'" target="_blank"><img src="img/'+ resumeico +'-icon.png" width="30" border="0" /></a> ';
							}
						html += '</td>';

						for(var a=0;a<careers.length;a++){
							if(careers[a]['careerid'] == data[i]['position']){
								position = careers[a]['title'];
								a = careers.length;
							}
						}
						html += '<td class="text-center">'+ position +'</td>';
						updStat = "return updateAppStatus('"+ data[i]['applicantid'] +"',this.value)";
						html += '<td class="text-center"><select class="form-control" name="txtStatus" id="txtStatus" onChange="'+ updStat +'">';
								// html += '<option value="2">Consideration</option>';
								html += '<option value="3">Interview</option>';
								html += '<option value="-1">Not Qualified</option>';
						html += '</select></td>';
					html += '</tr>';
				}
			}
		html += '</tbody>';
	html += '</table>';
	$("#considerationdatatable").html(html);

	//select chkAllApp checkboxes
    $("#chkAllConsi").change(function(){
        if ($(this).is(':checked')) {
            $('input[name=chkConsiAppID]').each(function() {
                $(this).prop("checked", true); 
            });
        }
        else {
            $('input[name=chkConsiAppID]').each(function() {
                $(this).prop("checked", false); 
            });
        }
    });
}

function genInterview(data){
	// console.log(data);
	var careers = data['careers'];
	var interviewers = data['interviewers'];
	var position = '';
	var html = '';
	var getApp = '';
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th class="text-center" width="15%">Name</th>';
				html += '<th class="text-center" width="10%">Position</th>';
				html += '<th class="text-center" width="15%">Interviewer</th>';
				// html += '<th class="text-center" width="5%">Level</th>';
				html += '<th class="text-center" width="15%">Type</th>';
				html += '<th class="text-center" width="10%">Date</th>';
				html += '<th class="text-center" width="5%">Time</th>';
				html += '<th class="text-center" width="20%">Remarks</th>';
				html += '<th class="text-center" width="10%">Status</th>';
			html += '</tr>';
		html += '</thead>';
		html += '<tbody>';
			var viewApp = "";
			var updStat = "";
			var intlvl = "";
			var intname = "";
			var inttype = "";
			var intdate = "";
			var inttime = "";
			var intrem = "";
			for(var i=0;i<data.length;i++){
				if(data[i]['status'] == 3){
					viewApp = "return getApplicant('"+ data[i]['sesid'] +"')";
					html += '<tr>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ data[i]['firstname'] +' '+ data[i]['lastname'] +'</td>';

						for(var a=0;a<careers.length;a++){
							if(careers[a]['careerid'] == data[i]['position']){
								position = careers[a]['title'];
								a = careers.length;
							}
						}

						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ position +'</td>';

						intlvl = "";
						intname = "";
						inttype = "";
						intdate = "";
						inttime = "";
						intrem = "";
						for(var b=0;b<interviewers.length;b++){
							if(interviewers[b]['applicantid'] == data[i]['applicantid'] && interviewers[b]['status'] == 0){
								intlvl = "";
								intname = interviewers[b]['interviewername'];
								inttype = interviewers[b]['interviewtypedesc'];
								intdate = interviewers[b]['interviewdt'];
								inttime = interviewers[b]['interviewtime'];
								intrem = interviewers[b]['remarks'] == "" || interviewers[b]['remarks'] == null ? "" : interviewers[b]['remarks'];
								b = interviewers.length;
							}
						}
						
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ intname +'</td>';
						// html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ intlvl +'</td>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ inttype +'</td>';
						html += '<td class="text-center" style="cursor: pointer;" onClick="'+ viewApp +'">'+ intdate +'</td>';
						html += '<td class="text-center" style="cursor: pointer;" onClick="'+ viewApp +'">'+ inttime +'</td>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ intrem +'</td>';
						updStat = "return updateAppStatus('"+ data[i]['applicantid'] +"',this.value)";
						html += '<td class="text-center"><select class="form-control" name="txtStatus" id="txtStatus" onChange="'+ updStat +'">';
								html += '<option value="3">Interview</option>';
								html += '<option value="4">Final Interview</option>';
								html += '<option value="-1">Do not Hire</option>';
						html += '</select></td>';
					html += '</tr>';
				}
			}
		html += '</tbody>';
	html += '</table>';

	$("#interviewdatatable").html(html);
}

function genFinalInterview(data){
	// console.log(data);
	var careers = data['careers'];
	var interviewers = data['interviewers'];
	var position = '';
	var html = '';
	var getApp = '';
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th class="text-center" width="15%">Name</th>';
				html += '<th class="text-center" width="10%">Position</th>';
				html += '<th class="text-center" width="15%">Interviewer</th>';
				// html += '<th class="text-center" width="5%">Level</th>';
				html += '<th class="text-center" width="15%">Type</th>';
				html += '<th class="text-center" width="10%">Date</th>';
				html += '<th class="text-center" width="5%">Time</th>';
				html += '<th class="text-center" width="20%">Remarks</th>';
				html += '<th class="text-center" width="10%">Status</th>';
			html += '</tr>';
		html += '</thead>';
		html += '<tbody>';
			var viewApp = "";
			var updStat = "";
			var intlvl = "";
			var intname = "";
			var inttype = "";
			var intdate = "";
			var inttime = "";
			var intrem = "";
			for(var i=0;i<data.length;i++){
				if(data[i]['status'] == 4){
					viewApp = "return getApplicant('"+ data[i]['sesid'] +"')";
					html += '<tr>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ data[i]['firstname'] +' '+ data[i]['lastname'] +'</td>';

						for(var a=0;a<careers.length;a++){
							if(careers[a]['careerid'] == data[i]['position']){
								position = careers[a]['title'];
								a = careers.length;
							}
						}

						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ position +'</td>';

						intlvl = "";
						intname = "";
						inttype = "";
						intdate = "";
						inttime = "";
						intrem = "";
						for(var b=0;b<interviewers.length;b++){
							if(interviewers[b]['applicantid'] == data[i]['applicantid'] && interviewers[b]['status'] == 0){
								intlvl = "";
								intname = interviewers[b]['interviewername'];
								inttype = interviewers[b]['interviewtypedesc'];
								intdate = interviewers[b]['interviewdt'];
								inttime = interviewers[b]['interviewtime'];
								intrem = interviewers[b]['remarks'] == "" || interviewers[b]['remarks'] == null ? "" : interviewers[b]['remarks'];
								b = interviewers.length;
							}
						}
						
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ intname +'</td>';
						// html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ intlvl +'</td>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ inttype +'</td>';
						html += '<td class="text-center" style="cursor: pointer;" onClick="'+ viewApp +'">'+ intdate +'</td>';
						html += '<td class="text-center" style="cursor: pointer;" onClick="'+ viewApp +'">'+ inttime +'</td>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ intrem +'</td>';
						updStat = "return updateAppStatus('"+ data[i]['applicantid'] +"',this.value)";
						html += '<td class="text-center"><select class="form-control" name="txtStatus" id="txtStatus" onChange="'+ updStat +'">';
								html += '<option value="3">Interview</option>';
								html += '<option value="4">Final Interview</option>';
								html += '<option value="-1">Do not Hire</option>';
						html += '</select></td>';
					html += '</tr>';
				}
			}
		html += '</tbody>';
	html += '</table>';

	$("#finalinterviewdatatable").html(html);
}

function genHire(data){
	// console.log(data);
	var careers = data['careers'];
	var position = '';
	var html = '';
	var getApp = '';
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th class="text-center" width="25%">Name</th>';
				html += '<th class="text-center" width="25%">Email Address</th>';
				html += '<th class="text-center" width="15%">Contact No</th>';
				html += '<th class="text-center" width="10%">Age Range</th>';
				html += '<th class="text-center" width="5%">Gender</th>';
				html += '<th class="text-center" width="15%">Position</th>';
				html += '<th class="text-center" width="10%">Status</th>';
			html += '</tr>';
		html += '</thead>';
		html += '<tbody>';
			var viewApp = "";
			var updStat = "";
			for(var i=0;i<data.length;i++){
				if(data[i]['status'] == 5){
					viewApp = "return getApplicant('"+ data[i]['sesid'] +"')";
					html += '<tr>';
						html += '<td style="cursor: pointer;" onClick="'+ viewApp +'">'+ data[i]['firstname'] +' '+ data[i]['lastname'] +'</td>';
						html += '<td>'+ data[i]['emailaddress'] +'</td>';
						html += '<td>'+ data[i]['contactno'] +'</td>';
						html += '<td class="text-center">'+ data[i]['agedesc'] +'</td>';
						html += '<td class="text-center">'+ data[i]['genderdesc'] +'</td>';

						for(var a=0;a<careers.length;a++){
							if(careers[a]['careerid'] == data[i]['position']){
								position = careers[a]['title'];
								a = careers.length;
							}
						}
						html += '<td class="text-center">'+ position +'</td>';
						updStat = "return updateAppStatus('"+ data[i]['applicantid'] +"',this.value)";
						html += '<td class="text-center"><select class="form-control" name="txtStatus" id="txtStatus" onChange="'+ updStat +'">';
								html += '<option value="5">Hire</option>';
								html += '<option value="1">On-board</option>';
								html += '<option value="-1">Do not Hire</option>';
						html += '</select></td>';
					html += '</tr>';
				}
			}
		html += '</tbody>';
	html += '</table>';

	$("#hiredatatable").html(html);
}

function getApplicant(appid){
	// $("#frmApplicant").modal('show');
	window.open("applicant.php?id="+appid);
}

function updateAppStatus(id,status){
	var url = getAPIURL() + 'recruitment.php';
	var f = "updateAppStatus";
	var userid = $("#userid").val();

	var data = { "f": f, "id":id, "status":status, "userid":userid };
	// console.log(data);

	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			$.ajax({
				type: 'POST',
				url: url,
				data: JSON.stringify({ "data": data }),
				dataType: 'json'
				, success: function (data) {
					loadDefault();
				}
				, error: function (request, status, err) {

				}
			});
		}
	});
}

function updateBulkAppStatus(data){
	var url = getAPIURL() + 'recruitment.php';
	var f = "updateBulkAppStatus";
	var userid = $("#userid").val();

	var stat = "";
	if( $("#txtbulkappstatus").val() == "new" ){
		stat = $("#txtNewApplicantStatus").val();
	}else if( $("#txtbulkappstatus").val() == "consi" ){
		stat = $("#txtConsiApplicantStatus").val();
	}

	var data = { "f":f, "userid":userid, "ids":data, "status":stat };

	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			$.ajax({
				type: 'POST',
				url: url,
				data: JSON.stringify({ "data": data }),
				dataType: 'json'
				, success: function (data) {
					// console.log(data);
					loadDefault();
					// $.unblockUI();
				}
				, error: function (request, status, err) {

				}
			});
		}
	});
}