$(function() {
	let $select = jQuery("#txtETA");
	for (let hr = 7; hr < 18; hr++) {
		let hrStr = hr.toString().padStart(2, "0") + ":";
		let val = hrStr + "00";
		$select.append('<option val="' + val + '">' + val + '</option>');
		val = hrStr + "15";
		$select.append('<option val="' + val + '">' + val + '</option>');
		val = hrStr + "30";
		$select.append('<option val="' + val + '">' + val + '</option>');
		val = hrStr + "45";
		$select.append('<option val="' + val + '">' + val + '</option>')
	}

	$('#opttardiness').on('click', function() {
		if($('#opttardiness').is(':checked')) { 
			$('#tardigroup').show(); 
			$('#abscencesgroup').hide(); 
		}
	});

	$('#optabsences').on('click', function() {
		if($('#optabsences').is(':checked')) { 
			$('#tardigroup').hide(); 
			$('#abscencesgroup').show(); 
		}
	});

	$('#btnSubmit').on('click', function() {
		let eta = $('#txtETA').val(); 
		let callinreason = $('#txtCallInReason').val();
		let abesnttype = $('#txtAbsentType').val();
		if($('#opttardiness').is(':checked')) {  
			if (eta == "00:00" || eta == null) {
				alertDialog("Estimated time of arrival is required. Please select time.");
				$("#txtETA").focus();
				return false;
			}
		}
		if($('#optabsences').is(':checked')) {  
			if (abesnttype == '' || abesnttype == null) {
				alertDialog("Absent type is required. Please select absent type.");
				$("#txtAbsentType").focus();
				return false;
			}
		}
		if (callinreason == '' || callinreason == null) {
			alertDialog("Reason is required. Please enter reason.");
			$("#callinreason").focus();
			return false;
		}
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function() {
				addCallIn();
			}
		});
	});

	$("#txtAbsentType").change(function () {
		let abstype = $("#txtAbsentType").val();
		if(abstype != ""){
			$.blockUI({
				message: $('#preloader_image'),
				fadeIn: 1000,
				onBlock: function() {
					getLeaveBalance();
					getLeaveDetails();
				}
			});
		}else{
			$("#txtLeaveBalance").val('');
		}
	});


	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function() {
			loadDataCallIn();
		}
	});
});


function loadDataCallIn(){
	var url = getAPIURL() + 'callin.php';
	var f = "loadDataCallin";
	var userid = $("#userid").val();
	var data = { "f": f, "userid":userid };
	// console.log(url);
	// $.unblockUI();    
	// return false;

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			console.log(data);
			// return false;
			var callinhistory = data['callins']['rows'];
			var leaveduration = data['leaveduration'];
			var leavecredits = data['leavecredits']['rows'];
			var reportsto = data['reportsto'];
			var reportstoindirect = data['reportstoindirect'];

				// debugger;
			if (reportsto.length > 0) {
				$("#txtApprover").val(reportsto[0]['eename']);
			} else {
				$("#txtApprover").val('');
			}
			if (reportstoindirect.length > 0) {
				$("#txtApprover1").val(reportstoindirect[0]['eename']);
				$("#withindirectapprv").show();
				$("#withoutindirectapprv").hide();
			} else {
				$("#level1approver").hide();
				$("#txtApprover1").val('');
			}
			// console.log(data);

			// ======================================================GENERATE CALL IN HISTORY TABLE================

			if (!$.fn.DataTable.isDataTable('#callinhistorydatatable')) {
				var callinhistorytbl = $('#callinhistorydatatable').DataTable({
					"searching": true,
					data: callinhistory,
					paging: true,
					responsive: true,
					order: [[0,'desc']],
					// lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "All"]],
					columns: [
						{ data: 'createddate', "className": "text-center" },
						{ data: 'callintypedesc' },
						{ data: 'reason' },
						{ data: 'eta' },
						// { data: 'statusdesc' },
						{ 
							data: function(data){
								let directstatus = data.reportstostatus;
								let indirectstatus = data.reportstoindirectstatus;
								let emailnotifsentstatus = data.emailnotifsent;
								let callintype = data.callintype;

								// return callintype;

								if(callintype == 'trd'){
									if(emailnotifsentstatus = 1){
										return 'Email Sent';
									}else{
										return 'Email not sent due to error.';
									}
								}else if(callintype == 'abs'){
									if(directstatus == 1){
									return 'APPROVED';
								} else if(directstatus == -1) {
									return 'REJECTED';
								} else if(directstatus == -2) {
									return 'CANCELLED';
								} else if(directstatus == 0) {
									if(reportstoindirect != ''){ // if has indirect
										if(indirectstatus == 1){
											return 'APPROVED';
										} else if(indirectstatus == -1) {
											return 'REJECTED';
										} else if(indirectstatus == -2) {
											return 'CANCELLED';
										} else {
											return 'PENDING ';
										}
									} else if(reportstoindirect == null) {
										return 'PENDING ';
									}
								} else if(directstatus == null) {
									return 'PENDING ';
								}

								}
							}
						},
					],
					columnDefs: [
						{
							targets: [0], render: function (data) {
								return '<span style="display:none;">' + toTimestamp(data) + '</span>' + moment(new Date(data)).format('ddd D MMM YYYY');
							}
						},
				      { 
							targets: [2], render: function (data) {
								return data.length > 30 ? '<div data-toggle="tooltip" data-placement="bottom" title="' + data + '">' + data.substr( 0, 30 ) +'…' : '<div data-toggle="tooltip" title="' + data + '">' + data;
							}   
				      }
					],
				});
				$.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
					$.unblockUI();
				};
			} else {
				$('#callinhistorydatatable')
					.dataTable()
					.fnClearTable();
				$('#callinhistorydatatable')
					.dataTable()
					.fnAddData(callinhistory);
				// $.unblockUI();
			}
			// =====================================================GENERATE DROPDOWN==============================
			//leavetype
			let ltthtml = "";
			ltthtml += '<option value="" selected></option>';
			leavecredits.sort((a, b) => (a.leavetypedesc > b.leavetypedesc) ? 1 : -1);
			for (let i = 0; i < leavecredits.length; i++) {
				let leavetype = leavecredits[i]['leavetype'];
				let absenttypedesc = '';
				if(leavetype == 'al' || leavetype == 'sl'){
					switch(leavetype){
						case 'al':
							absenttypedesc = 'Emergency - Annual Leave';
							break;
						// case 'ul':
						// 	absenttypedesc = 'Absence with leave - Unpaid Leave';
						// 	break;
						case 'sl':
							absenttypedesc = 'Call in Sick - Sick Leave';
							break;
					}
					ltthtml += '<option value="' + leavecredits[i]['leavetypeid'] + '">' + absenttypedesc + '</option>'
				}
			}
			ltthtml += '</select>';
			$("#txtAbsentType").html(ltthtml);

			//leaveduration
			let ldhtml ="";
			for (let i = 0; i < leaveduration.length; i++) {
				ldhtml += '<option value="' + leaveduration[i]['ddid'] + '">' + leaveduration[i]['dddescription'] + '</option>'
			}
			ldhtml += '</select>';
			$("#txtLeaveDuration").html(ldhtml);

			// ======================================================GENERATE APPROVERS============================
			// function reportsTo(reportstoindirect, reportsto) {
			// 	$("#txtApprover1").val(reportstoindirect);
			// 	if (reportstoindirect != '') {
			// 		$("#withindirectapprv").show();
			// 		$("#withoutindirectapprv").hide();
			// 		$("#level1approver").show();
			// 	} else {
			// 		$("#level1approver").hide();
			// 		$("#level1approver").hide();
			// 	}

			// 	$("#txtApprover").val(reportsto);
			// }

			// reportsTo(reportstoindirect, reportsto);
			$("#txtLeaveDuration").change(function () {
				getLeaveDetails();
			});
			$.unblockUI();
			
		}
		, error: function (request, status, err) {
			console.log(err);
		}
	});
}

function addCallIn(){
	var url = getAPIURL() + 'callin.php';
	var f = "addCallIn";
	var userid = $("#userid").val();
	var callintype = $("input[name='callintype']:checked").val();
	var eta = $("#txtETA option:selected").val();
	var callinreason = $("#txtCallInReason").val();
	var leavetype = $("#txtAbsentType").val();
	var leaveduration = $("#txtLeaveDuration").val();
	var leavedtls = $("#txtldtls").val();
	var noofdays = 1;
	var fiscalyear = new Date().getFullYear();
	var leavefrom = $("#txtDateFrom").val();
	var leaveto = $("#txtDateTo").val();
	var absenttypedesc = $( "#txtAbsentType option:selected" ).text();
	var data = { "f": f,  "userid":userid, "callintype":callintype, "eta":eta, "callinreason":callinreason, "leavetype":leavetype,
					 "leaveduration":leaveduration, "leavedtls":leavedtls, "noofdays":noofdays, "fiscalyear":fiscalyear, 
					 "leavefrom":leavefrom, "leaveto":leaveto, "absenttypedesc":absenttypedesc };

	// console.log(data);  
	// $.unblockUI(); 
	// return false;

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			loadDataCallIn();
			clearCallinFields();
			// $.unblockUI();
		}
		, error: function (request, status, err) {
			console.log(err);
		}
	});
}

function getLeaveBalance() {
	var url = getAPIURL() + 'leaves.php';
	var f = "getLeaveBalance";
	var userid = $("#userid").val();
	var leavetype = $("#txtAbsentType").val();

	var data = { "f": f, "userid": userid, "leavetype": leavetype };

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

			var bal = data['leavebal']['rows'][0]['leavebalance'];
			var pending = data.leavebal.rows[0].pending;
			$("#txtLeaveBalance").val(bal);
			if(bal<1){
				$("#txtLeaveBalance").css({
					color: 'red'
				});
			}
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function getLeaveDetails() {
	var url = getAPIURL() + 'leaves.php';
	var f = "genLeaveDetails";
	var userid = $("#userid").val();
	var dtfm = $("#txtDateFrom").val();
	var dtto = $("#txtDateTo").val();
	// var noofdays = $("#txtNoOfDays").val();
	var ofc = $("#ofc").val();
	var dur = $("#txtLeaveDuration").val();
	var pts = 1;

	switch (dur) {
		case "a": case "p": pts = 0.5; break;
		default: break;
	}

	var data = { "f": f, "userid": userid, "dtfrom": dtfm, "dtto": dtto, "ofc": ofc, "duration": dur };

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
			var noofdays = data['noofdays'];
			var holidays = data['holidays']['rows'];
			var workingdays = data['workingdays']['rows'];
			var dates = data['dates'];

			// var dtshtml = "";
			// dtshtml += '<table class="table table-sm table-bordered" width="100%" cellspacing="0">';
			// dtshtml += '<thead class="thead-dark">';
			// dtshtml += '<tr style="cursor: pointer;">';
			// dtshtml += '<th width="30%">Date</th>';
			// dtshtml += '<th width="30%">Durataion</th>';
			// dtshtml += '<th width="40%">Remarks</th>';
			// dtshtml += '<th width="40%">Exclude</th>';
			// dtshtml += '</tr>';
			// dtshtml += '</thead>';
			// dtshtml += '<tbody>';

			var dtlarray = "";
			var exclude = "";
			var weekend = "";
			var hol = "";
			var wor = "";
			var rem = "";
			var a = 0;
			var disabled = "";
			var cnt = 0;
			var ofc = $('#ofc').val();
			for (var i = 0; i < dates.length; i++) {
				disabled = "";
				rem = "";
				weekend = "";
				hol = "";
				// leaves count
				switch (dates[i]['dayofdate']) {
					case "SAT": case "SUN":
						weekend = 'WEEKEND';
						disabled = 'disabled';
						break;
					default: break;
				}

				for (a = 0; a < workingdays.length; a++) {
					if (workingdays[a]['officename'] == ofc) {
						if (workingdays[a]['workingdaycode'] == dates[i]['dtymd']) {
							weekend = '';
							wor = 'WORKINGDAY';
							disabled = '';
						}
					}
				}

				for (a = 0; a < holidays.length; a++) {
					if (holidays[a]['holidaycode'] == dates[i]['dtymd']) {
						hol = 'HOLIDAY';
						disabled = 'disabled';
					}
				}
				rem = weekend + ' ' + hol;

				exclude = "";
				exclude = "return exclude('" + dates[i]['dtymd'] + "')"
				// dtshtml += '<tr>';
				// dtshtml += '<td><input type="text" id="LDTLDt[]" name="LDTLDt[]" value="' + dates[i]['formatted'] + '" class="form-control form-control-sm" readonly /></td>';
				// dtshtml += '<td><span id="divLDTLDuration"><select id="LDTLDuration[]" name="LDTLDuration[]" class="form-control form-control-sm" ' + disabled + '>';
				// dtshtml += '<option value="fdl">Full day leave</option>';
				// dtshtml += '<option value="hdlfh">Half day - first half</option>';
				// dtshtml += '<option value="hdlsh">Half day - second half</option>';
				// dtshtml += '</select></span></td>';
				// dtshtml += '<td><input type="text" id="LDTLRemarks[]" name="LDTLRemarks[]" value="' + rem + '" placeholder="Remarks here" class="form-control form-control-sm" ' + disabled + ' /></td>';

				if (dates.length > 1 && weekend == "" && hol == "") {
					// dtshtml += '<td class="text-center"><a href="#" onClick="' + exclude + '" title="Disapprove" class="px-1"><i class="fa fa-times-circle text-secondary"></i></a></td>';
					dtlarray += dates[i]['dtymd'] + "::" + dur + "::" + pts + "::||";
				} else {
					if (weekend == "" && hol == "") {
						dtlarray += dates[i]['dtymd'] + "::" + dur + "::" + pts + "::||";
					}
					// dtshtml += '<td>&nbsp;</td>';
				}
				// dtshtml += '</tr>';

				if (disabled == "") {
					cnt = (cnt + pts);
				}
			}
			dtlarray = dtlarray.substr(0, dtlarray.length - 2);
			// dtshtml += '</tbody>';
			// dtshtml += '<input type="hidden" id="txtldtls" name="txtldtls" value="' + dtlarray + '" /> ';
			// dtshtml += '<input type="hidden" id="txtholidays" name="txtholidays" value="" /> ';
			// dtshtml += '</table>';

			// $("#divleavedtls").html(dtshtml);
			$("#txtldtls").val(dtlarray);
			$("#txtNoOfDays").val(cnt);
			// console.log(dtlarray);
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function clearCallinFields(){
	$("#txtETA option[value='00:00'").prop('selected', true);
	$("#txtCallInReason").val('');
	$("#txtAbsentType option[value=''").prop('selected', true);
	$("#txtLeaveBalance").val('');
	
}