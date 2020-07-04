$(function(){
	$("#txtIntDate").datepicker();

	$("#btnNewInterviewer").on("click", function(){
		clearInterviewerForm();
		$("#frmInterviewer").modal('show');
	});

	$("#btnCancelInterviewer").on("click", function(){
		clearInterviewerForm();
		$("#frmInterviewer").modal('hide');
	});

	$("#btnNewQuestion").on("click", function(){
		clearQuestionForm();
		$("#frmQuestion").modal('show');
	});

	$("#btnCancelQuestion").on("click", function(){
		clearQuestionForm();
		$("#frmQuestion").modal('hide');
	});

	$("#btnSaveInterviewer").on("click", function(){
		var interviewer = $("#txtInterviewer").val();
		var type = $("#txtIntType").val();
		var interviewdate = $("#txtIntDate").val();
		var interviewtime = $("#txtIntTime").val();
		var remarks = $("#txtRemarks").val();

		if(interviewer == ""){
			alert("Interviewer is required! Please select interviewer");
			return false;
		}
		if(type == ""){
			alert("Interview type is required! Please select interview type");
			return false;
		}
		if(interviewdate == ""){
			alert("Interview date is required! Please select interview date");
			return false;
		}
		if(interviewtime == ""){
			alert("Interview time is required! Please select interview time");
			return false;
		}

		$("#btnSaveInterviewer").attr("disabled", true);
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				if($("#interviewid").val() == ""){
					saveInterviewer();
				}else{
					updateInterviewer();
				}
			}
		});
	})

	$("#btnSaveQuestion").on("click", function(){
		var question = $("#txtQuestion").val();

		if(question == ""){
			alert("Question is required! Please enter your question");
			return false;
		}
		
		$("#btnSaveQuestion").attr("disabled", true);
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				if($("#questionid").val() == ""){
					saveQuestion();
				}else{
					updateQuestion();
				}
			}
		});
	})

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
	var f = "loadApplicant";
	var sesid = $("#sesid").val();
	var userid = $("#userid").val();
	var dept = $("#dept").val();

	var data = { "f": f, "id": sesid };
	// console.log(data);

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			var app = data['app']['rows'][0];
			var interviewers = data['interviewers']['rows'];
			var questions = data['questions']['rows'];
			var applicant = app['firstname'] +' '+ app['lastname'];
			var ees = data['ees']['rows'];

			var eeshtml = "";
			eeshtml += '<select id="txtInterviewer" name="txtInterviewer" class="form-control">';
				eeshtml += '<option value="">Select Employee</option>';
				for(var i=0;i<ees.length;i++){
					eeshtml += '<option value="'+ ees[i]['userid'] +'">'+ ees[i]['fname'] +' '+ ees[i]['lname'] +'</option>';
				}
			eeshtml += '</select>';
			$("#divees").html(eeshtml);

			$("#appid").val(app['applicantid']);
			$("#dataEmail").html(app['emailaddress']);
			$("#dataContactNo").html(app['contactno']);
			$("#dataAge").html(app['agedesc']);
			$("#dataGender").html(app['genderdesc']);
			$("#dataStatus").html(app['statusdesc']);
			$("#dataApplicantName").html(applicant);

			var dataCL = "";
			var dataCV = "";
			var ico1 = "";
			var ico2 = "";
			var file1 = "";
			var file2 = "";
			if(app['resume'] == null || app['resume'] == ""){ }else{
				ico1 = 'img/pdf-icon.png';
				switch(app['filetype']){
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document": case "application/msword":
							ico1 = 'img/word-icon.png';
							file1 = 'uploads/applicants/'+ app['resume'];
						break;
					default:
							file1 = 'uploads/applicants/'+ app['resume'];
						break;
				}
				dataCV = '<a href="'+ file1 +'" target="_blank"><img src="'+ ico1 +'" width="30" border="0" /></a>'
				$("#dataCV").html(dataCV);
			}

			if(app['coverletter'] == null || app['coverletter'] == ""){ }else{
				ico2 = 'img/pdf-icon.png';
				switch(app['filetype2']){
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document": case "application/msword":
							ico2 = 'img/word-icon.png';
							file2 = 'uploads/applicants/'+ app['coverletter'];
						break;
					default:
							file2 = 'uploads/applicants/'+ app['coverletter'];
						break;
				}
				dataCL = '<a href="'+ file2 +'" target="_blank"><img src="'+ ico2 +'" width="30" border="0" /></a>'
				$("#dataCL").html(dataCL);
			}

			genInterviewers(interviewers);
			genQuestions(questions);

			if(userid == 'A161215-00089' || dept == 'DEPT0006'){
				$("#btnNewInterviewer").show();
			}

			$.unblockUI();      
		}
		, error: function (request, status, err) {

		}
	});
}

function genInterviewers(data){
	var row = data;
	var userid = $("#userid").val();

	var html = "";
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th width="20%">Name</th>';
				html += '<th width="10%">Job Title</th>';
				html += '<th width="10%">Office</th>';
				html += '<th width="10%">Type</th>';
				html += '<th width="10%">Date</th>';
				html += '<th width="10%">Time</th>';
				html += '<th width="20%">Remarks</th>';
				html += '<th width="10%">Status</th>';
			html += '</tr>';
		html += '</thead>  ';
		html += '<tbody>';
			if(row.length > 0){
				var rem = "";
				var viewInt = "";
				var statleg = "";
				for(var i=0;i<row.length;i++){
					rem = row[i]['remarks'] == "" || row[i]['remarks'] == null ? "" : row[i]['remarks'];
					viewInt = "return viewInterviewer('"+ row[i]['interviewid'] +"')";
					html += '<tr style="cursor: pointer;" onClick="'+ viewInt +'">';
						html += '<td>'+ row[i]['interviewername'] +'</td>';
						html += '<td>'+ row[i]['interviewerjobtitle'] +'</td>';
						html += '<td class="text-center">'+ row[i]['intervieweroffice'] +'</td>';
						html += '<td>'+ row[i]['interviewtypedesc'] +'</td>';
						html += '<td class="text-center">'+ row[i]['interviewdt'] +'</td>';
						html += '<td class="text-center">'+ row[i]['interviewtime'] +'</td>';
						html += '<td>'+ rem +'</td>';
						statleg = row[i]['status'] > 0 ? statleg = 'text-success' : "text-warning";
						html += '<td class="text-center '+ statleg +'">'+ row[i]['statusdesc'] +'</td>';
					html += '</tr>';

					if(row[i]['interviewer'] == userid || userid == 'A161215-00089'){
						$("#btnNewQuestion").show();
					}
				}
			}else{
				html += '<tr>';
					html += '<td colspan="8" class="text-center">No Interviewers Found!</td>';
				html += '</tr>';

				if(userid == 'A161215-00089'){
					$("#btnNewQuestion").show();
				}
			}
		html += '</tbody>';
	html += '</table>';
	$("#divInterviewers").html(html);
}

function genQuestions(data){
	var row = data;

	var html = "";
	html = '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
		html += '<thead class="thead-dark">';
			html += '<tr>';
				html += '<th width="20%">Interviewer</th>';
				html += '<th width="80%">Question</th>';
			html += '</tr>';
		html += '</thead>  ';
		html += '<tbody>';
			if(row.length > 0){
				var viewQuest = "";
				for(var i=0;i<row.length;i++){
					viewQuest = "return viewQuestion('"+ row[i]['questionid'] +"')";
					html += '<tr style="cursor: pointer;" onClick="'+ viewQuest +'">';
						html += '<td>'+ row[i]['interviewername'] +'</td>';
						html += '<td>'+ row[i]['question'] +'</td>';
					html += '</tr>';
				}
			}else{
				html += '<tr>';
					html += '<td colspan="2" class="text-center">No Questions Found!</td>';
				html += '</tr>';
			}
		html += '</tbody>';
	html += '</table>';
	$("#divQuestions").html(html);
}

function viewQuestion(id){
	$("#questionid").val(id);
	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			loadQuestion();
		}
	});
}

function loadQuestion(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "getQuestion";
	var questionid = $("#questionid").val();

	var data = { "f":f, "questionid":questionid };
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
			var question = data['question']['rows'][0];
			$("#questionid").val(question['questionid']);
			$("#txtQuestion").val(question['question']);
			$("#frmQuestion").modal('show');
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function saveInterviewer(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "saveInterviewer";
	var userid = $("#userid").val();
	var appid = $("#appid").val();
	var interviewer = $("#txtInterviewer").val();
	var type = $("#txtIntType").val();
	var interviewdate = $("#txtIntDate").val();
	var interviewtime = $("#txtIntTime").val();

	var data = { "f":f, "userid":userid, "id":appid, "interviewer":interviewer, "interviewtype":type, "interviewdate":interviewdate, "interviewtime":interviewtime };
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
			var interviewers = data['interviewers']['rows'];
			genInterviewers(interviewers);
			$("#frmInterviewer").modal('hide');
			clearInterviewerForm();
			$("#btnSaveInterviewer").attr("disabled", false);
			$.unblockUI();      
		}
		, error: function (request, status, err) {

		}
	});
}

function updateInterviewer(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "updateInterviewer";
	var userid = $("#userid").val();
	var appid = $("#appid").val();
	var interviewid = $("#interviewid").val();
	var interviewer = $("#txtInterviewer").val();
	var type = $("#txtIntType").val();
	var interviewdate = $("#txtIntDate").val();
	var interviewtime = $("#txtIntTime").val();
	var remarks = $("#txtRemarks").val();
	var status = 0;
	if( $("input[name='chkDone']").is(":checked") ){
		status = 1;
	}

	var data = { "f":f, "userid":userid, "appid":appid, "id":interviewid, "interviewer":interviewer, "interviewtype":type, "interviewdate":interviewdate, "interviewtime":interviewtime, "remarks":remarks, "status":status };
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
			var interviewers = data['interviewers']['rows'];
			genInterviewers(interviewers);
			$("#frmInterviewer").modal('hide');
			clearInterviewerForm();
			$("#btnSaveInterviewer").attr("disabled", false);
			$.unblockUI();      
		}
		, error: function (request, status, err) {

		}
	});
}

function viewInterviewer(id){
	$("#interviewid").val(id);
	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			loadInterviewer();
		}
	});
}

function loadInterviewer(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "getInterviewer";
	var interviewid = $("#interviewid").val();

	var data = { "f":f, "interviewid":interviewid };
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
			clearInterviewerForm();
			var interviewer = data['interviewer']['rows'][0];
			$("#txtInterviewer option[value='" + interviewer['interviewer'] + "']").prop('selected', true);
			$("#txtIntType option[value='" + interviewer['interviewtype'] + "']").prop('selected', true);
			$("#txtIntTime option[value='" + interviewer['interviewtime'] + "']").prop('selected', true);
			$("#txtIntDate").val(interviewer['interviewdt']);
			$("#txtRemarks").val(interviewer['remarks']);
			if( interviewer['status'] > 0 ){
				$("#divBtns").hide();
				$('#chkDone').prop('checked',true);
			}

			$("#divRemarks").show();
			$("#frmInterviewer").modal('show');
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function saveQuestion(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "saveQuestion";
	var userid = $("#userid").val();
	var appid = $("#appid").val();
	var question = $("#txtQuestion").val();

	var data = { "f":f, "userid":userid, "id":appid, "question":question };
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
			var questions = data['questions']['rows'];
			genQuestions(questions);
			$("#frmQuestion").modal('hide');
			clearQuestionForm();
			$("#btnSaveQuestion").attr("disabled", false);
			$.unblockUI();  
		}
		, error: function (request, status, err) {

		}
	});
}

function updateQuestion(){
	var url = getAPIURL() + 'recruitment.php';
	var f = "updateQuestion";
	var appid = $("#appid").val();
	var questionid = $("#questionid").val();
	var question = $("#txtQuestion").val();

	var data = { "f":f, "id":appid, "questionid":questionid, "question":question };
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
			var questions = data['questions']['rows'];
			genQuestions(questions);
			$("#frmQuestion").modal('hide');
			clearQuestionForm();
			$("#btnSaveQuestion").attr("disabled", false);
			$.unblockUI();  
		}
		, error: function (request, status, err) {

		}
	});
}

function clearInterviewerForm(){
	$("#interviewerid").val("");
	$('#txtInterviewer option').prop('selected', function () { return this.defaultSelected; });
	$('#txtIntType option').prop('selected', function () { return this.defaultSelected; });
	$('#txtIntTime option').prop('selected', function () { return this.defaultSelected; });
	$("#divRemarks").hide();
	$('#chkDone').prop('checked',false);
	$("#divBtns").show();
}

function clearQuestionForm(){
	$("#txtQuestion").val("");
}