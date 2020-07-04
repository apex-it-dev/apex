let leave_access = null;
let ofclist = [];
let ofclist_attendance = [];
let viewtype = 'self';
let viewtype_attendance = 'self';
let canApprReject = false;
let canCancelLeaves = false;
$(async function () {
	leave_access = await getAccess($('#menuid_leave').val());
	attendance_access = await getAccess($('#menuid_attendance').val());

	const foreignkey = 'foreignkey';

	if(leave_access !== null) {
		for(eachaccess in leave_access) {
			if(typeof leave_access[eachaccess] === 'object' && leave_access[eachaccess] !== null)
				if(foreignkey in leave_access[eachaccess])
					ofclist.push(leave_access[eachaccess][foreignkey]);
		}
		
		if('viewteam' in leave_access) {
			viewtype = 'department';
		} else if(ofclist.length > 0){
			viewtype = 'ofclist';
		}
		canApprReject = 	'canapprreject' in leave_access;
		canCancelLeaves = 	'cancancelleaves' in leave_access;
	}

	if(attendance_access !== null) {
		for(eachaccess in attendance_access) {
			if(typeof attendance_access[eachaccess] === 'object' && attendance_access[eachaccess] !== null)
				if(foreignkey in attendance_access[eachaccess])
					ofclist_attendance.push(attendance_access[eachaccess][foreignkey]);
		}
		
		if('viewteam' in attendance_access) {
			viewtype_attendance = 'department';
		} else if(ofclist_attendance.length > 0){
			viewtype_attendance = 'ofclist';
		}
	}

	$('#upload_form').submit(function (e) {
		// alertDialog('Hi!');
		e.preventDefault();
		$.ajax({
			url: 'controllers/leaveattachment_controller.php',
			type: 'post',
			data: new FormData(this),
			contentType: false,
			processData: false,
			success: function (result) {
				// console.log(result);
				$('#output').html(result);
				// $('#uploadattachement').val('');
			}
		});
	});
	serverDateNow((today) => {
		$("#txtDateFrom,#txtDateTo").datepicker({
			// minDate: minDateLeave,	// temp
			minDate: -10,
			dateFormat: "D dd M y",
			changeMonth: true,
			changeYear: true,
			yearRange: `1900:${today.getFullYear()}`
		});
	})

	$("#txtDateFrom").change(function () {
		var leavefrom = $("#txtDateFrom").val();
		var leaveto = $("#txtDateTo").val();
		var leavefromdt = new Date(leavefrom);
		var leavetodt = new Date(leaveto);

		if (leavetodt < leavefromdt) {
			// alertDialog("Date from should be less that Date to.");
			$("#txtDateTo").val($("#txtDateFrom").val());
			leaveto = $("#txtDateTo").val();
			// return false;
		}

		computeNoOfDays(leavefrom, leaveto, function () {
			genLeaveDetails();
		});
	});

	$("#txtDateTo").change(function () {
		var leavefrom = $("#txtDateFrom").val();
		var leaveto = $("#txtDateTo").val();
		var leavefromdt = new Date(leavefrom);
		var leavetodt = new Date(leaveto);

		if (leavetodt < leavefromdt) {
			// alertDialog("Date to should be greater that Date from.");
			$("#txtDateFrom").val($("#txtDateTo").val());
			leavefrom = $("#txtDateFrom").val();
			// return false;
		}

		computeNoOfDays(leavefrom, leaveto, function () {
			genLeaveDetails();
		});
	});

	$("#txtLogFrom").change(function () {
		var logfrom = $("#txtLogFrom").val();
		var logto = $("#txtLogTo").val();
		var logfromdt = new Date(logfrom);
		var logtodt = new Date(logto);

		if (logtodt < logfromdt) {
			// alertDialog("Date from should be less that Date to.");
			$("#txtLogTo").val($("#txtLogFrom").val());
			// logto = $("#txtLogTo").val();
			// return false;
		}

		// 	$.blockUI({ 
		//       message: $('#preloader_image'), 
		//       fadeIn: 1000, 
		//       onBlock: function() {
		//       	loadAttendance();
		//       }
		//     });
	});


	$("#txtLogTo").change(function () {
		var logfrom = $("#txtLogFrom").val();
		var logto = $("#txtLogTo").val();
		var logfromdt = new Date(logfrom);
		var logtodt = new Date(logto);

		if (logtodt < logfromdt) {
			// alertDialog("Date from should be less that Date to.");
			$("#txtLogFrom").val($("#txtLogTo").val());
			// logfrom = $("#txtLogFrom").val();
			// return false;
		}

		// $.blockUI({ 
		//      message: $('#preloader_image'), 
		//      fadeIn: 1000, 
		//      onBlock: function() {
		//      	loadAttendance();
		//      }
		//    });
	});

	$('#history-tab').one('click', function () {
		$("#history").val(1);
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				loadLeaveHistory();
			}
		});
    });
    $('#history-tab').on('dblclick', function () {
		$("#history").val(1);
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				loadLeaveHistory();
			}
		});
	});

	$('#approval-tab').one('click', function () {
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				loadPendingLeaveRequests();
			}
		});
    });
    $('#approval-tab').on('dblclick', function () {
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				loadPendingLeaveRequests();
			}
		});
	});

	$('#attendance-tab').one('click', function () {
		$("#txtLogFrom,#txtLogTo").datepicker({
			dateFormat: "D d M y",
			changeMonth: true,
			changeYear: true,
			yearRange: "1900:2020"
		});

		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				loadAttendance();
				$('#txtofc').trigger('change');
			}
		});
	});
	$("#request-tab").on('mousedown',function(){
        if($('#leaveid').val() != '')
            $.blockUI({
                message: $('#preloader_image'),
                fadeIn: 1000,
                onBlock: function () {
                    clearLeaveFields();
                    loadDefault();
                }
            });
	});
	// $("#txtMonth,#txtYear").change(function(){
	// 	$.blockUI({ 
	//    message: $('#preloader_image'), 
	//    fadeIn: 1000, 
	//    onBlock: function() {
	//    	loadAttendance();
	//    }
	//  });
	// })

	$("#btnSubmit").on("click", function () {
		var leavefrom = $("#txtDateFrom").val();
		var leaveto = $("#txtDateTo").val();
		var leavetype = $("#txtLeaveType").val();
		var leaveduration = $("#txtLeaveDuration").val();
		var leavereason = $("#txtLeaveReason").val();
		var noofdays = $("#txtNoOfDays").val();
		var leavebal = parseFloat($("#leavebal").val());
		var leavepending = parseFloat($("#leavepending").val());

		if (leavefrom == "") {
			alertDialog("Date From is required! Please enter date.");
			$("#txtDateFrom").focus();
			return false;
		}
		if (leaveto == "") {
			alertDialog("Date To is required! Please enter date.");
			$("#txtDateTo").focus();
			return false;
		}
		if (leavetype == "") {
			alertDialog("Leave type is required! Please select leave type.");
			$("#txtLeaveType").focus();
			return false;
		}
		if (leaveduration == "") {
			alertDialog("Leave duration is required! Please select leave duration.");
			$("#txtLeaveDuration").focus();
			return false;
		}
		if (leavereason == "") {
			alertDialog("Reason is required! Please enter reason.");
			$("#txtLeaveReason").focus();
			return false;
		}
		if (noofdays <= 0) {
			alertDialog("No of days is required! Please select date range to take leave.");
			return false;
		}
		if (leavebal < noofdays) {
			// console.log(leavebal +' '+noofdays);
			alertDialog("Insufficient leave balance! Leave balance is lesser than the no of days request.");
			return false;
		}
		if (leavebal - leavepending <= 0) {
			alertDialog("Leave balance have been used up in your pending leaves.");
			return false;
		}

		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				// if($("#leaveid").val() == ""){
				addRequestLeave();
				// }else{
				// 	updRequestLeave();
				// }
			}
		});
	});


	$('#btnRetrieve').on('click', function () {
		
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				// $('#collapseKpi').collapse('hide');
				loadAttendance();
			}
		});
	});

	$('#btnNewRequest').on('click', function () {
		clearLeaveFields();
		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				$('#eeid').val($('#userid').val());
				loadDefault();
			}
		});
	});

	$('#btnUpdateRequest').on('click', function () {

		$.blockUI({
			message: $('#preloader_image'),
			fadeIn: 1000,
			onBlock: function () {
				updateLeaveRequest();
			}
		});
	});

	$('#btnCancelRequest').on('click', function () {
        confirmDialog("Are you sure you want to cancel your leave request?", function(){
            $.blockUI({
                message: $('#preloader_image'),
                fadeIn: 1000,
                onBlock: function () {
                    cancelLeaveRequest();
                }
            });
        });

	});
	$('#btnGeneratePayroll').on('click', function () {
        var office = $("#txtofc").val();
        var ees = $("#txtee").val();
		if (office == "" ) {
			alertDialog("Please select office");
			return false;
		}
		if (ees !== "" ) {
			alertDialog("Please select all employees");
			return false;
        }
        office = btoa(office);
        window.location = 'attendancepayrollreview.php?office=' + office ;
		// checkPeriodIfExist();
		// window.location = 'payrollprocess.php';
	});
	$("#txtofc").on('change', function(){
		getEePerOfc($(this).val());
	});
	
	setInterval(() => {
		qryData('leaves', 'getPendingCount', {userid: $('#userid').val(), canapprreject: canApprReject, ofclist: ofclist, viewtype: viewtype}, pendingcount => {
			if($('#pendingcounter').html() != pendingcount) $('#pendingcounter').html(pendingcount);
		});
	}, 3000);

	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			loadDefault();
		}
	});
});

function getEePerOfc(ofc){
	if($('#txtofc option').length === 0) return;
	$('#txtee')
		.html('<option value="" selected>Please wait...</option>')
		.prop('disabled',true)
		.css('cursor','no-drop');
	$('#btnRetrieve')
		.prop('disabled',true)
		.css('cursor','no-drop');

	const data = { "office": ofc, viewtype: viewtype_attendance, ofclist: ofclist_attendance, userid: $('#userid').val() };

	qryData('attendance', 'getEePerOfc', data, data => {
            // console.log(data);
		let eedata = data.eedata.rows;
		let eedropdown = '';
		let eename = '';
		eedropdown += '<option value="" selected>All Employees</option>';
		eedata.forEach(ee => {
			eename = '';
			if(ee.eename !== null) {
				eename = (ee.eename).replace(/\s+/g,' ').trim();
			}
			eedropdown += `<option value="${ee.userid}">${eename}</option>`;
		});
		$('#txtee')
			.html(eedropdown)
			.prop('disabled',false)
			.css('cursor','default');
		$('#btnRetrieve')
			.prop('disabled',false)
			.css('cursor','default');
	});
}

//loads leave history table and leave credits
function loadDefault(result) {
	var url = getAPIURL() + 'leaves.php';
	var f = "loadDefault";
	var userid = $("#eeid").val();
	var dept = $("#dept").val();
	var pos = $("#pos").val();
	var rank = $("#rank").val();
	var ofc = $("#ofc").val();


	var fiscalyearEle = document.getElementById("yearFilterLeaveCredit");
	if (fiscalyearEle == null) {
		var fiscalyear = new Date().getFullYear();
		fiscalyear = fiscalyear.toString();
	} else {
		fiscalyear = fiscalyearEle.value;
	}

	var data = { "f": f, "userid": userid, "fiscalyear": fiscalyear };
	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			// return false;
			var leavecredits = data['leavecredits']['rows'];
			var leaveduration = data['leaveduration'];
			var eeinfo = data['eeinfo'][0];
			var ofcs = data['offices']['rows'];
			var ofc = $("#ofc").val();
			var reportsto = data['reportsto'];
			var reportstoindirect = data['reportstoindirect'];


			$('#pendingcounter').html(data.pendingcount);

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
			if (!$.fn.DataTable.isDataTable('#leavecreditsdatatable')) {
				var leavecreditlist = $('#leavecreditsdatatable').DataTable({
					data: leavecredits,
					paging: false,
					searching: false,
					info: false,
					language:
					{
						emptyTable: '<center>No leave credits recorded</center>'
					},
					responsive: true,
					columns: [
						{ 'data': 'leavetypedesc' },
						{ 'data': 'entitleddays', "className": "text-right" },
						// { 'data': 'carriedover', "className": "text-right" },
						{ 'data': 'forapproval', "className": "text-right" },
						{ 'data': 'noofdaystaken', "className": "text-right" },
						{ 'data': 'leavebalance', "className": "text-right" },
					],

					fnDrawCallback: function () {
						if ($('#yearFilterLeaveCredit').length == 0) {
							let startYear = 2019;
							let defaultYear = new Date().getFullYear();
							// let yearLatest = defaultYear + 1;
							let yearSelect = '';
							yearSelect += 'Year: <label style="padding-right: 3vh; width: 20vh; white-space: nowrap;">';
							yearSelect +=
								'<select class="form-control form-control-sm" id="yearFilterLeaveCredit" name="yearFilterLeaveCredit" aria-controls="leavecreditsdatatable">';
							for (let yearStart = startYear; yearStart <= defaultYear; yearStart++) {
								if (yearStart == defaultYear) {
									yearSelect += '<option value="' + yearStart + '" selected>' + yearStart + '</option>';
								} else {
									yearSelect += '<option value="' + yearStart + '">' + yearStart + '</option>';
								}
							}
							yearSelect += '</select>';
							yearSelect += '</label>';
							$('#leavecreditfilteryear_wrapper').prepend(yearSelect);
						}

					},
				});
			} else {
				if (leavecredits.length > 0) {
					// console.log('true');
					$('#leavecreditsdatatable').dataTable().fnClearTable();
					$('#leavecreditsdatatable').dataTable().fnAddData(leavecredits);
				} else {
					// console.log('false');
					$('#leavecreditsdatatable').dataTable().fnClearTable();
					// $('#leavecreditsdatatable').dataTable().fnAddData([]);
				}
			}

			$('#yearFilterLeaveCredit').on('change', function () {
				loadDefault();
			});

			//leavetype
			var ltthtml = "";
			ltthtml += '<label for="leavetype">Leave Type</label>';
			ltthtml += '<select id="txtLeaveType" class="form-control form-control-sm" >';
			ltthtml += '<option value="" selected></option>';
			leavecredits.sort((a, b) => (a.leavetypedesc > b.leavetypedesc) ? 1 : -1);
			for (var i = 0; i < leavecredits.length; i++) {
				ltthtml += '<option value="' + leavecredits[i]['leavetypeid'] + '">' + leavecredits[i]['leavetypedesc'] + '</option>'
			}
			ltthtml += '</select>';
			$("#divLeaveType").html(ltthtml);

			$("#txtLeaveType").change(function (e) {

                $("#txtDateFrom,#txtDateTo").datepicker("destroy");
				let selectedValue = e.target.value;
				
				serverDateNow((today) => {
					if(selectedValue.substr(0, selectedValue.length-2) == 'AL'){
						let fullday = today.getFullYear() + '' + (today.getMonth() + 1) + '' + today.getDate();
						let minDateLeave = fullday >= 20200420 ? -10 : -20; 
						$("#txtDateFrom,#txtDateTo").datepicker({
							// minDate: minDateLeave,	// temp
							minDate: minDateLeave,
							dateFormat: "D dd M y",
							changeMonth: true,
							changeYear: true,
							yearRange: `1900:${today.getFullYear()}`
						});
					} else {
						$("#txtDateFrom,#txtDateTo").datepicker({
							// minDate: minDateLeave,	// temp
							minDate: -10,
							dateFormat: "D dd M y",
							changeMonth: true,
							changeYear: true,
							yearRange: `1900:${today.getFullYear()}`
						});
					}
				});

				if ($("#txtLeaveType").val() == "") {
					$("#leavebal").val(0);
					return false;
				}
				$('#txtDateTo').trigger('change');
				$.blockUI({
					message: $('#preloader_image'),
					fadeIn: 1000,
					onBlock: function () {
						getLeaveBalance();
					}
				});
			});

			//leaveduration
			var ldhtml = "";
			ldhtml += '<label for="leaveDuration">Leave Duration</label>';
			ldhtml += '<select id="txtLeaveDuration" class="form-control form-control-sm" >';
			for (var i = 0; i < leaveduration.length; i++) {
				ldhtml += '<option value="' + leaveduration[i]['ddid'] + '">' + leaveduration[i]['dddescription'] + '</option>'
			}
			ldhtml += '</select>';
			$("#divLeaveDuration").html(ldhtml);

			$("#txtLeaveDuration").change(function () {
				$.blockUI({
					message: $('#preloader_image'),
					fadeIn: 1000,
					onBlock: function () {
						genLeaveDetails();
					}
				});
			});

			var ofchtml = "";
			if(ofclist_attendance.length > 1) ofchtml += '<option value="">All Offices</option>';
			const allOfc = ofclist_attendance.length === ofcs.length;
			
			ofcs.map(eachofc => {
				if (!allOfc) {
					if (ofclist_attendance.find(ofcval => ofcval == eachofc.salesofficeid) !== undefined) {
						ofchtml += `<option value="${eachofc.salesofficeid}" selected>${eachofc.description}</option>`;
					}
				} else {
					ofchtml += `<option value="${eachofc.salesofficeid}">${eachofc.description}</option>`;
				}
			});
			
			$("#txtofc").html(ofchtml);

			// if ($('#hasaccess') == 1) {
			// 	for (var i = 0; i < ofcs.length; i++) {
			// 		ofchtml += '<option value="' + ofcs[i]['description'] + '">' + ofcs[i]['description'] + '</option>';
			// 		cntofc++;
			// 	}
			// } else {
			// 	for (var i = 0; i < ofcs.length; i++) {
			// 		if (ofcs[i]['description'] == ofc) {
			// 			ofchtml += '<option value="' + ofcs[i]['description'] + '">' + ofcs[i]['description'] + '</option>';
			// 			cntofc++;
			// 		}
			// 	}
			// }

			// if (isHead || $('#hasaccess').val() == 1) {
			// 	$("#txtofc").html(ofchtml);
			// 	$("#txtofc option[value='" + ofc + "']").prop('selected', true);
			// 	if (cntofc == 1) {
			// 		$("#dataofclist").hide();
			// 		$("#dataeelist").hide();
			// 	}

			// 	$("#txtofc").change(function () {
			// 		$('#txtee option').prop('selected', function () { return this.defaultSelected; });
			// 	});
			// } else {
			// 	$("#dataofclist").hide();
			// 	$("#dataeelist").hide();
			// }

			getLeaveDetails();
			if (result != undefined) {
				result(true);
			}
			// $.unblockUI();          
		}
		, error: function (request, status, err) {

		}
	});
}

function loadLeaveCredits(data) {
	$('#leavecreditsdatatable').dataTable().fnClearTable();
	$('#leavecreditsdatatable').dataTable().fnAddData(leavecredits);
}

function loadLeaveHistory() {
	function statusApproval(status, abaini) {
		let return_val;
		if (!isNaN(status)) {
			switch (parseInt(status)) {
				case 0:
					return_val = 'PENDING';
					break;
				case 1:
					return_val = 'APPROVED';
					break;
				case -1:
					return_val = 'REJECTED';
					break;
				case -2:
					return_val = 'CANCELLED';
					break;
				case -3:
					return_val = 'RECALLED';
					break;
				default:
					break;
			}
		}
		return return_val;
	}

	var data = {
		userid:		$("#userid").val(), 
		viewtype: 	viewtype, 
		ofclist: 	ofclist, 
		userofc:	$('#ofc').val()
	};
	
	qryData('leaves', 'loadLeaveHistory', data, data => {
		let leaves = data.leaves.rows;
		// let allnotpending = leaves.filter(leave => leave.status != 0);
		// console.table(allnotpending);
		// var leavelist = "";
		let eeinfo = data.eeinfo;

		// if (allnotpending.length > 0) {
		function filterDefault(datatable_v, filtered_s, filtered_n) {
			let statIsFiltered = filtered_s != 'all' && filtered_s != '';
			let nameIsFiltered = filtered_n != 'all' && filtered_n != '';
			let final_filter;

			if (statIsFiltered && nameIsFiltered) {
				final_filter = datatable_v.filter(leave => {
					let matchedInfo = eeinfo.find(employee => employee.userid == leave.userid);
					let reportstoindirect = matchedInfo.reportstoindirectini;
					let reportstodirect = matchedInfo.reportstoini;

					if (reportstoindirect == '' || leave.approvalstatusindirect == 1) {
						return statusApproval(leave.status, reportstodirect).split(' ')[0] == filtered_s && leave.eename == filtered_n;
					} else {
						return statusApproval(leave.approvalstatusindirect, reportstoindirect).split(' ')[0] == filtered_s && leave.eename == filtered_n;
					}
				});
			} else if (nameIsFiltered) {
				final_filter = datatable_v.filter(leave => {
					return leave.eename == filtered_n;
				});
			} else if (statIsFiltered) {
				final_filter = datatable_v.filter(leave => {
					let matchedInfo = eeinfo.find(employee => employee.userid == leave.userid);
					let reportstoindirect = matchedInfo.reportstoindirectini;
					let reportstodirect = matchedInfo.reportstoini;

					if (reportstoindirect == '' || leave.approvalstatusindirect == 1) {
						return statusApproval(leave.status, reportstodirect).split(' ')[0] == filtered_s;
					} else {
						return statusApproval(leave.approvalstatusindirect, reportstoindirect).split(' ')[0] == filtered_s;
					}
				});
			} else {
				final_filter = datatable_v;
			}
			return final_filter;
		}
		let sortIndex = 0;
		let sortMode = 'asc';
		if ($('#nameFilterHistory option:selected').text() != 'all') {
			sortIndex = 1;
			sortMode = 'desc';
		}
		let leavedata = filterDefault(leaves, $('#statusFilterHistory option:selected').text(), $('#nameFilterHistory option:selected').text());
		// console.table(leavedata);
		if (!$.fn.DataTable.isDataTable('#leavesdatatable')) {
			var leavehistorytbl = $('#leavesdatatable').DataTable({
				"searching": true,
				data: leavedata,
				paging: true,
				responsive: true,
				order: [[sortIndex, sortMode]],
				lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "All"]],
				columns: [
					{
						'data': 'userid',
						render: function (data) {
							let eename = '';
							eeinfo.find(eedata => {
								if (eedata.userid == data) {
									eename = eedata.eename;
								}
							});
							return eename;
						}
					},
					{ data: 'createddt', "className": "text-center" },
					{ data: 'leavetypedesc' },
					{ data: 'leavefromdt', "className": "text-center" },
					{ data: 'leavetodt', "className": "text-center" },
					{ data: 'noofdays', "className": "text-center" },
					{
						'data': 'reason',
						render: function (data) {
							return truncateStr(data, 22);
						}
					},
					{ 'data': 'approvedbyname' },
					{
						data: function (data) {
							let matchedInfo = eeinfo.find(employee => employee.userid == data.userid);
							let reportstoindirect = matchedInfo.reportstoindirectini;
							let reportstodirect = matchedInfo.reportstoini;
							let directstatus = data.status;
							let indirectstatus = data.approvalstatusindirect;

							if(directstatus == 1){
								return 'APPROVED';
							} else if(directstatus == -1) {
								return 'REJECTED';
							} else if(directstatus == -2) {
								return 'CANCELLED';
							} else if(directstatus == -3) {
								return 'RECALLED';
							} else if(directstatus == 0) {
								if(reportstoindirect != ''){ // if has indirect
									if(indirectstatus == 1){
										return 'APPROVED';
									} else if(indirectstatus == -1) {
										return 'REJECTED';
									} else if(indirectstatus == -2) {
										return 'CANCELLED';
									} else if(indirectstatus == -3) {
										return 'RECALLED';
									} else {
										return 'PENDING ' + reportstoindirect;
									}
								} else {
									return 'PENDING ' + reportstodirect;
								}
							}
							// if (data.status == 1) {
							// 	return statusApproval(data.status, reportstodirect);
							// } else if (reportstoindirect == '' || data.approvalstatusindirect == 1) {
							// 	return statusApproval(data.status, reportstodirect);
							// } else {
							// 	return statusApproval(data.approvalstatusindirect, reportstoindirect);
							// }
						}
					},
				],
				columnDefs: [
					//hide a timestamp for dates for sorting
					{
						targets: [1, 3, 4], render: function (data) {
							return '<span style="display:none;">' + toTimestamp(data) + '</span>' + moment(new Date(data)).format('ddd D MMM YYYY');
						}
					}
				],
				fnDrawCallback: function () {
					// resize filter --------------------------
					$('#leavesdatatable_length')
						.parent()
						.removeAttr('class')
						.attr('class', 'col-sm-12 col-md-2');
					// .attr('style', 'display:none;');

					$('#leavesdatatable_filter')
						.parent()
						.removeAttr('class')
						.attr('class', 'col-sm-12 col-md-10');
					// ----------------------------------------
					let dataTable = $('#leavesdatatable').dataTable();
					if ($('#statusFilterHistory').length == 0) {
						let statusSelect = '';
						statusSelect += '<span id="statusFilterHistoryDiv">';
						statusSelect += 'Status: <label style="padding-right: 2vh; width: 25vh; white-space: nowrap;">';
						statusSelect += '<select class="form-control form-control-sm" id="statusFilterHistory" name="statusFilterHistory" aria-controls="leavesdatatable">';
						statusSelect += '<option value="all">all</option>';

						// get list of status
						let distinctStatusList = $('#leavesdatatable').DataTable().columns(8).data().eq(0).sort().unique();
						let tmpname = '';
						for (let index in distinctStatusList) {
							if (!isNaN(index)) {
								tmpname = (distinctStatusList[index]).split(' ')[0];
								if(tmpname.search('err=>') < 0)
									statusSelect += '<option value="' + tmpname + '">' + tmpname + '</option>';
						// 		statusName.push((distinctStatusList[index]).split(' ')[0]);
						// 		// statusSelect += '<option value="' + statusName + '">' + statusName + '</option>';
							}
						}

						// statusName
						// 	.filter((values, index) => statusName.indexOf(values) === index)
						// 	.forEach(name => {
						// 		statusSelect += '<option value="' + name + '">' + name + '</option>';
						// 	});
						// statusSelect += '<option value="all">Approved</option>';
						// statusSelect += '<option value="all">all</option>';
						// statusSelect += '<option value="all">all</option>';
						statusSelect += '</select>';
						statusSelect += '</label>';
						statusSelect += '</span>';

						// let distinctNameList = [...new Set(eeinfo.map(rowItem => rowItem.eename))]; // Everyone, including those with no leave
						let distinctNameList = $('#leavesdatatable').DataTable().columns(0).data().eq(0).sort().unique();
						let nameSelect = '';
						if (distinctNameList.length > 1) {
							nameSelect += '<span id="nameFilterHistoryDiv">';
							nameSelect += 'Employee: <label style="padding-right: 2vh; width: 20vh; white-space: nowrap;">';
							nameSelect += '<select class="form-control form-control-sm" id="nameFilterHistory" name="nameFilterHistory" aria-controls="leavesdatatable">';
							nameSelect += '<option value="all">all</option>';

							// get list of status
							let useridTmp = null;
							let eedataTmp;
							let eeName = null;
							let eeList = new Array();
							for (let index in distinctNameList) {
								if (!isNaN(index)) {
									useridTmp = distinctNameList[index];
									eedataTmp = eeinfo.find(eedata => eedata.userid == useridTmp);
									eeName = eedataTmp.fname + ' ' + eedataTmp.lname;
									eeList.push(eeName);
									// nameSelect += '<option value="' + eeName + '">' + eeName + '</option>';
								}
							}
							eeList
								.sort()
								.forEach(eeItem => {
									nameSelect += '<option value="' + eeItem + '">' + eeItem + '</option>';
								});

							// let eeName=null;
							// for(let index in distinctNameList){
							// 	if (!isNaN(index)){
							// 		eeName = distinctNameList[index].replace(/ +(?= )/g,''); // also remove if 2 spaces
							// 		nameSelect += '<option value="' + eeName + '">' + eeName + '</option>';
							// 	}
							// }


							nameSelect += '</select>';
							nameSelect += '</label>';
							nameSelect += '</span>';
						}

						$('#leavesdatatable_filter').prepend(statusSelect);
						$('#leavesdatatable_filter').prepend(nameSelect);

						$('#statusFilterHistory,#nameFilterHistory').on('change', function () {
							let filteredStatus = $('#statusFilterHistory option:selected').text();
							let filteredName = $('#nameFilterHistory option:selected').text();
							let finalFilter = '';
							
							finalFilter = filterDefault(leaves, filteredStatus, filteredName);

							dataTable.fnClearTable();
							if (filteredName == 'all') {
								dataTable.fnSort([[0, 'asc']]);
							} else {
								dataTable.fnSort([[1, 'desc']]);
							}
							if (finalFilter.length > 0) {
								dataTable.fnAddData(finalFilter);
							}
						});

						let defaultNameName = eeinfo.filter(eedata => eedata.userid == $('#userid').val());
						$('#nameFilterHistory option[value="' + defaultNameName[0].eename.replace(/ +(?= )/g, '') + '"]').prop('selected', true);
						$('#nameFilterHistory').trigger("change");

						$('#leavesdatatable_filter label input').attr('style', 'width:25vh;');
					}
				}
			});
			$.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
				$.unblockUI();
			};
		} else {
			$('#leavesdatatable')
				.dataTable()
				.fnClearTable();
			$('#leavesdatatable')
				.dataTable()
				.fnAddData(leavedata);
			}
		// }

		$('#leavesdatatable tbody').on('click', 'tr', function () {
			thisdata = leavehistorytbl.row(this).data();
			leaveid = thisdata['leaveid'];
			$('#eeid').val(thisdata.userid);
			editLeave(leaveid);
		});

		if ($('#leavesdatatable tbody tr td').hasClass('dataTables_empty')) {
			$('#leavesdatatable tbody').css('cursor', 'default');
		} else {
			$('#leavesdatatable tbody').css('cursor', 'pointer');
		}
		$.unblockUI();
	});
}

function loadPendingLeaveRequests() {
	function statusApproval(status, abaini) {
		let return_val;
		if (!isNaN(status)) {
			switch (parseInt(status)) {
				case 0:
					return_val = 'FOR APPROVAL';
					break;
				case 1:
					return_val = 'APPROVED';
					break;
				case -1:
					return_val = 'REJECTED';
					break;
				case -2:
					return_val = 'CANCELLED';
					break;
				default:
					return_val = 'err';
					break;
			}
		} else {
			return_val = 'err';
		}
		if (return_val != 'err') {
			if (status != 0) {
				return return_val;
			} else {
				if(abaini == null) return return_val;
				return return_val + ' ' + abaini;
			}
		} else {
			return return_val + '=> status:' + status + ', abaini:' + abaini;
		}
	}

	
	const userid = $("#userid").val();

	var data = {
		userid:		userid, 
		viewtype: 	viewtype, 
		ofclist: 	ofclist, 
		canapprreject:	canApprReject,
		userofc:	$('#ofc').val()
	};
    
	qryData('leaves', 'loadPendingLeaveRequests', data, data => {
		let pendingleaves = data.pendingleaves.rows;
		$('#pendingcounter').html(data.pendingcount);
			
		function filterDefault(datatable_v, filtered_n) {
			let nameIsFiltered = filtered_n != 'all' && filtered_n != '';
			let final_filter;

			if (nameIsFiltered) {
				final_filter = datatable_v.filter(leave => {
					return leave.eename == filtered_n;
				});
			} else {
				final_filter = datatable_v;
			}
			return final_filter;
		}

		let pendingdata = filterDefault(pendingleaves,$('#nameFilterApproval option:selected').text());
		if (!$.fn.DataTable.isDataTable('#pendingleavesdatatable')) {
			var pendingleavestable = $('#pendingleavesdatatable').DataTable({
				searching: true,
				data: pendingdata,
				paging: true,
				responsive: true,
				language: {
					emptyTable: '<center>No leave approval recorded</center>'
				},
				order: [[0,'desc']],
				lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
				columns: [
					{ 'data': 'createddt', "className": "text-center" },
					{ 'data': 'eename' },
					{ 'data': 'leavetypedesc' },
					{ 'data': 'leavefromdt', "className": "text-center" },
					{ 'data': 'leavetodt', "className": "text-center" },
					{ 'data': 'noofdays', "className": "text-center" },
					{
						'data': 'reason',
						render: function (data) {
							return truncateStr(data, 22);
						}
					},
					{
						data: function (data) {
							if('eeinfo' in data){
								let eeinfo = data.eeinfo.rows[0];
								let reportstoindirect = eeinfo.indirectini;
								let reportstodirect = eeinfo.directini;

								if(((eeinfo.indirectuserid != null && eeinfo.indirectuserid != '') 
								&& data.approvalstatusindirect == 0)){
									return statusApproval(data.approvalstatusindirect, reportstoindirect);
								} else if (data.approvalstatusindirect >= 0) {
									return statusApproval(data.status, reportstodirect);
								} else {
									return '';
								}
							} else {
								return '';
							}
						}
					},
					{
						data: function (data) {
							const editLeave = "return getLeaveModal('" + data.leaveid + "');";
							const appr = "return appr('" + data.leaveid + "',1);";
							const disappr = "return appr('" + data.leaveid + "',0);";
							const cancelleave = "return cancelLeave('" + data.leaveid + "');";
							let attachmenticon = '';
							if (data.attachment != null && data.attachment != '') attachmenticon = '<i class="fas fa-paperclip"></i></i>';
							var hasaccess = $('#hasaccess').val();
							var localhr = $('#localhr').val();
							// console.log(localhr);
							var cancelbutton = '';

							if(canCancelLeaves){
								cancelbutton =  `
												<a href="#" onClick="${cancelleave}" title="Cancel" class="px-1">
													<i class="fa fa-ban text-secondary"></i>
												</a>
								`;
							}


							function actionbtns(btntype){
								switch (btntype) {
									case 1:
										return `
											<span style="display: none;">1</span>
											<a href="#" onClick="${editLeave}" title="View" class="px-1">
												<i class="fas fa-bars fa-sm text-gray-800" ></i>
											</a>
											<a href="#" onClick="${appr}" title="Approve" class="px-1">
												<i class="fas fa-check-circle text-danger"></i>
											</a>
											<a href="#" onClick="${disappr}" title="Reject" class="px-1">
												<i class="fa fa-times-circle text-secondary"></i>
											</a>
											${cancelbutton}
											${attachmenticon}
										`;
									case 2:
										return `
											<span style="display: none;">1</span>
											<a href="#" onClick="${editLeave}" title="View" class="px-1">
												<i class="fas fa-bars fa-sm text-gray-800" ></i>
											</a>
											${cancelbutton}
										`;
									default:
										return `
											<a href="#" onClick="${editLeave}" title="View" class="px-1">
												<i class="fas fa-bars fa-sm text-gray-800" ></i>
											</a>
											${cancelbutton}
											${attachmenticon}
										`;
								}
							}

							if('eeinfo' in data){	
								const eeinfo = data.eeinfo.rows[0];
								if(canApprReject){		
									return actionbtns(1);
								} else {
									if(data.status == 0){
										if(((eeinfo.indirectuserid != null && eeinfo.indirectuserid != '') 
										&& data.approvalstatusindirect == 0 && eeinfo.indirectuserid == userid)
										|| (data.approvalstatusindirect >= 0 && eeinfo.directuserid == userid)){
											return actionbtns(1);
										} else {
											return actionbtns(0);
										}
									} else {
										return actionbtns(0);
									}
								}
							} else {
								return actionbtns(0);
							}
						}
					},
				],
				columnDefs: [
					//hide a timestamp for dates for sorting
					{
						targets: [0, 3, 4], render: function (data) {
							return '<span style="display:none;">' + toTimestamp(data) + '</span>' + moment(new Date(data)).format('ddd D MMM YYYY');
						}
					}
				],
				rowId: 'id',
				fnDrawCallback: function () {
					let dataTable = $('#pendingleavesdatatable').dataTable();
					let distinctNameList = $('#pendingleavesdatatable').DataTable().columns(1).data().eq(0).sort().unique();
					// console.log(distinctNameList);
					// return false;
					let nameSelect = '';
					if ($('#nameFilterApproval').length == 0) {
						if (distinctNameList.length > 1) {
							nameSelect += '<span id="nameFilterApprovalDiv">';
							nameSelect += 'Employee: <label style="padding-right: 2vh; width: 20vh; white-space: nowrap;">';
							nameSelect += '<select class="form-control form-control-sm" id="nameFilterApproval" name="nameFilterApproval" aria-controls="leavesdatatable">';
							nameSelect += '<option value="all">all</option>';

							// get list of status
							let eeList = new Array();
							for (let index in distinctNameList) {
								if (!isNaN(index))
									eeList.push(distinctNameList[index]);
							}
							eeList
								.sort()
								.forEach(eeItem => {
									nameSelect += '<option value="' + eeItem + '">' + eeItem + '</option>';
								});

							// let eeName=null;
							// for(let index in distinctNameList){
							// 	if (!isNaN(index)){
							// 		eeName = distinctNameList[index].replace(/ +(?= )/g,''); // also remove if 2 spaces
							// 		nameSelect += '<option value="' + eeName + '">' + eeName + '</option>';
							// 	}
							// }


							nameSelect += '</select>';
							nameSelect += '</label>';
							nameSelect += '</span>';
						}
						$('#pendingleavesdatatable_filter').prepend(nameSelect);
						$('#nameFilterApproval').on('change', function () {
							let filteredName = $('#nameFilterApproval option:selected').text();
							let finalFilter = '';

							finalFilter = filterDefault(pendingleaves, filteredName);

							dataTable.fnClearTable();
							if (finalFilter.length > 0) {
								dataTable.fnAddData(finalFilter);
							}
						});
					}
				}
			});
		} else {
			$('#pendingleavesdatatable').dataTable().fnClearTable();
			if (pendingleaves.length > 0) {
				$('#pendingleavesdatatable').dataTable().fnAddData(pendingleaves);
			}
		}

		if ($('#pendingleavesdatatable tbody tr td').hasClass('dataTables_empty')) {
			$('#pendingleavesdatatable tbody').css('cursor', 'default');
		} else {
			$('#pendingleavesdatatable tbody').css('cursor', 'pointer');
		}

		$('#pendingleavesdatatable tbody').on('click', 'tr', function (evt) {
			var $cell = $(evt.target).closest('td');
			if ($cell.index() < 8) {
				thisdata = pendingleavestable.row(this).data();
				leaveid = thisdata['leaveid'];
				// console.log(leaveid);
				if(thisdata.userid == userid){
					editLeave(leaveid);
				} else {
					getLeaveModal(leaveid);
				}
			}
		});
		$.unblockUI();
	});
}

function getLeaveModal(leaveid) {
	$("#leaveid").val(leaveid);
	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			viewLeave();
		}
	});
}

function viewLeave() {
	var leaveid = $("#leaveid").val();
	var url = getAPIURL() + 'leaves.php';
	var f = "getLeave";
	var userid = $("#userid").val();
	var data = { "f": f, "leaveid": leaveid };

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			var docpaths = data['docpaths'];
			var leave = data['leave']['rows'][0];

			$("#dataeename").html(leave['eename']);
			$("#dataleavetype").html(leave['leavetypedesc']);
			$("#datareason").html(leave['reason']);
			$("#dataleavefrom").html(leave['leavefromdt']);
			$("#dataleaveto").html(leave['leavetodt']);
			$("#datanoofdays").html(leave['noofdays']);
			$("#datacreadt").html(leave['createddt']);
			$("#LeaveReqApvl").modal('show');
			$('#btnApprove').remove();
			$('#btnDispprove').remove();
			$("#btnApprove").off('click');
			$("#btnDispprove").off('click');
			$('#LeaveReqApvl .modal-body .row .col-md-5 #txtComments')
				.attr('disabled',true)
				.val('');
			if(leave.status == 0){
				if(((leave.reportstoindirectid != null && leave.reportstoindirectid != '') && leave.approvalstatusindirect == 0 && leave.reportstoindirectid == userid)
				|| (leave.approvalstatusindirect >= 0 && leave.reportstoid == userid)){
					$('#LeaveReqApvl .modal-body .row .col-md-5 #txtComments')
						.removeAttr('disabled');
					$('#LeaveReqApvl .modal-body .row .col-md-5')
						.append('<button type="button" id="btnApprove" class="btn btn-danger btn-sm">Approve</button> ');
					$('#LeaveReqApvl .modal-body .row .col-md-5')
						.append('<button type="button" id="btnDispprove" class="btn btn-secondary btn-sm">Reject</button>');
						
					$("#btnApprove").on("click", function () {
						$(this)
							.attr('disabled',true)
							.css('cursor','no-drop')
							.html('Approving...');
						leaveApprove(1);
					});

					$("#btnDispprove").on("click", function () {
						$(this)
							.attr('disabled',true)
							.css('cursor','no-drop')
							.html('Rejecting...');
						leaveApprove(0);
					});
				}
			}
			
			var html = "";
			if (docpaths.length > 0) {
				for (var i = 0; i < docpaths.length; i++) {
					var filenames = docpaths[i];
					html += filenames;
				}
			}
			if (html != '') $("#viewattachments").html(html);
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function appr(leaveid, stat) {
	$("#leaveid").val(leaveid);
	leaveApprove(stat);
}

function disappr(leaveid, stat) {
	$("#leaveid").val(leaveid);
	leaveApprove(stat);
}

function leaveApprove(stat) {
	$("#stat").val(stat);
	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			approveLeave();
		}
	});
}

function approveLeave() {
	var data = { 
		leaveid: 		$("#leaveid").val(), 
		stat: 			$("#stat").val(), 
		cmts:  			$("#txtComments").val(), 
		approver: 		$("#userid").val(),
		viewtype: 		viewtype, 
		ofclist: 		ofclist, 
		canapprreject:	canApprReject,
		userofc:		$('#ofc').val()
	};

	qryData('leaves', 'approveLeave', data, data => {
		
		$("#LeaveReqApvl").modal('hide');
		$('#pendingcounter').html(data.pendingcount);
		$("#stat").val("");
		$("#leaveid").val("");
		var pendingleaves = data['pendingleaves']['rows'];
		let allpending = pendingleaves.filter(leave => leave.status == 0 && leave.approvalstatusindirect >= 0);
		$('#pendingleavesdatatable').dataTable().fnClearTable();
		if (allpending.length > 0) {
			$('#pendingleavesdatatable').dataTable().fnAddData(allpending);
		}

		$.unblockUI();
	});
}

function addRequestLeave() {
	var url = getAPIURL() + 'leaves.php';
	var f = "addRequestLeave";
	var userid = $("#userid").val();
	var leavefrom = $("#txtDateFrom").val();
	var leaveto = $("#txtDateTo").val();
	var leavetype = $("#txtLeaveType").val();
	var leaveduration = $("#txtLeaveDuration").val();
	var leavereason = $("#txtLeaveReason").val();
	// computeNoOfDays(leavefrom,leaveto);
	var noofdays = $("#txtNoOfDays").val();
	var leavedtls = $("#txtldtls").val();
	var uploadattachement = $("#uploadattachement").val();
	var fiscalyearEle = document.getElementById("yearFilterLeaveCredit");
	if (fiscalyearEle == null) {
		var fiscalyear = new Date().getFullYear();
		fiscalyear = fiscalyear.toString();
	} else {
		fiscalyear = fiscalyearEle.value;
	}
	var data = { "f": f, "userid": userid, "leavefrom": leavefrom, "leaveto": leaveto, "leavetype": leavetype, "leaveduration": leaveduration, "leavereason": leavereason, "noofdays": noofdays, "leavedetails": leavedtls, "uploadattachement": uploadattachement, "fiscalyear": fiscalyear };

	//console.log(data);
	// return false;
	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			// return false;
			clearLeaveFields();
			if (data['errsaveleave']['err'] == 0) {
				alertDialog('Leave request has been successfully saved.');
			} else if (data['errsaveleave']['err'] == 1) {
				alertDialog('An error occured while saving leave request. Please contact IT immediately.');
				return false;
			}

			var leaves = data['leaves']['rows'];
			loadLeaveHistory();
			// 	if(leaves.length > 0){
			// 			if(!$.fn.DataTable.isDataTable('#leavesdatatable')){
			// 				var leavelist = $('#leavesdatatable').DataTable({
			// 				"dom": '<"pull-right" f><t>',
			// 				"searching": false,
			// 				data: leaves,
			// 				paging: false,
			// 				lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
			// 				columns:[
			// 					{ 'data': 'createddt', "className":"text-center" },
			// 					{ 'data': 'leavetypedesc' },
			// 					{ 'data': 'leavefromdt', "className":"text-center" },
			// 					{ 'data': 'leavetodt', "className":"text-center" },
			// 					{ 'data': 'noofdays', "className":"text-center" },
			// 					{ 'data': 'reason' },
			// 					{ 'data': 'approvedbyname' },
			// 					{ 'data': 'leavestatus' },
			// 				]
			// 			});
			// 		}else{
			// 			$('#leavesdatatable').dataTable().fnClearTable();
			// 				$('#leavesdatatable').dataTable().fnAddData(leaves);
			// 		}
			// }

			var leavecredits = data['leavecredits']['rows'];
			$('#leavecreditsdatatable').dataTable().fnClearTable();
			$('#leavecreditsdatatable').dataTable().fnAddData(leavecredits);

			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function loadAttendance() {
	var userid = $("#userid").val();
	var year = $("#txtYear").val();
	var month = $("#txtMonth").val();
	var yearmonth = year + month;
	var ofc = $("#ofc").val();
	var logfm = $("#txtLogFrom").val();
	var logto = $("#txtLogTo").val();
	var loadee = $("#loadee").val();
	var dept = $("#dept").val();
	var rank = $("#rank").val();
	var pos = $("#pos").val();
	var ee = $('#txtofc option').length == 0 ? userid : ($("#txtee").val() == null ? '' : $("#txtee").val());
	var office = $("#txtofc").val() != '' ? $("#txtofc").val() : ofclist_attendance.reduce((prev, curr) => prev += ',' + curr);
	// var user = 'ee';


	// const ranknfile = 5;
	// const isHead = rank > 0 && rank != '' && rank != null ? rank < ranknfile : false;
	const isAdmin = true;
	// const isHr = $('#dept').val() == 'DEPT0006';
	// const isGM = $('#pos').val() == 'POS0036';

	$("#forLblLogFrom").show();
	$("#forLblLogTo").show();
	if(ofclist_attendance.length > 0) {
		$("#dataofclist").show();
		$("#dataeelist").show();
	}

	// if (isAdmin) {
	// 	$("#forLblLogFrom").show();
	// 	$("#forLblLogTo").show();
	// 	$("#dataofclist").show();
	// 	$("#dataeelist").show();
	// 	user = 'admin';
	// } else if (isGM) {
	// 	$("#forLblLogFrom").show();
	// 	$("#forLblLogTo").show();
	// 	$("#dataofclist").hide();
	// 	$("#dataeelist").show();
	// 	user = 'gm';
	// } else if (isHr) {
	// 	$("#forLblLogFrom").show();
	// 	$("#forLblLogTo").show();
	// 	$("#dataofclist").hide();
	// 	$("#dataeelist").show();
	// 	user = 'hr';
	// } else if (isHead) {
	// 	$("#forLblLogFrom").show();
	// 	$("#forLblLogTo").show();
	// 	$("#dataofclist").hide();
	// 	$("#dataeelist").show();
	// 	user = 'head';
	// } else {
	// 	$("#forLblLogFrom").show();
	// 	$("#forLblLogTo").show();
	// 	$("#dataofclist").hide();
	// 	$("#dataeelist").hide();
	// 	user = 'ee';
	// }

	// var ofcselected = $("#dataofclist").css('display') == 'none' ? 0 : 1;

	var data = {
		"userid": userid, "yearmonth": yearmonth, "ofc": ofc,
		"logfm": logfm, "logto": logto, "loadee": loadee, "dept": dept,
		"rank": rank, "pos": pos, "ee": ee, "office": office,
		ofclist_attendance: ofclist_attendance, viewtype: viewtype_attendance
		// "ofcselected": ofcselected, "user": user
	};

	qryData('attendance', 'getAttendance', data, data => {
			const datatableid = '#attendancedatatable';
			const attendance = data['attendances']['rows'];
			const count_signin = attendance.filter(ee => ee.onleave == 0 && ee.loggedin != null).length;
			const count_late = attendance.filter(ee => ee.late == 1).length;
			const count_absent = attendance.filter(ee => ee.loggedin == null).length;
			const count_onleave = attendance.filter(ee => ee.onleave == 1).length;
			const count_present = parseInt(count_signin) + parseInt(count_late);
			const count_lal = attendance.filter(ee => ee.leavetype == 'AL').length;
			const count_lsl = attendance.filter(ee => ee.leavetype == 'SL').length;
			const count_lul = attendance.filter(ee => ee.leavetype == 'UL').length;

			$('#count_signin').html(count_signin);
			$('#count_late').html(count_late);
			$('#count_absent').html(count_absent);
			$('#count_onleave').html(count_onleave);
			$('#count_present').html(count_present);
			$('#count_lal').html(count_lal);
			$('#count_lsl').html(count_lsl);
			$('#count_lul').html(count_lul);
			
			if ($.fn.DataTable.isDataTable(datatableid)) {
				$(datatableid).dataTable().fnClearTable();
				if (attendance.length > 0) {
					$(datatableid).dataTable().fnAddData(attendance);
				}
			} else {
				// var attendancelist = "";
				attendancelist = $(datatableid).DataTable({
					"dom": '<"pull-right" f><t>',
					"searching": false,
					data: attendance,
					language: {
						emptyTable: '<center>No attendance record</center>'
					},
					paging: false,
					responsive: true,
					ordering: true,
					order: [[1,'desc']],
					lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
					columns: [
						{ data: 'officename', className: "text-center", title: 'Office', width: '5%' },
						{ data: 'loggeddt', className: "text-center", title: 'Date', width: '15%' },
						{ data: 'eename', title: 'Name', width: '20%' },
						{ data: 'login', className: "text-center", title: 'Time In', width: '10%' },
						{ data: 'logout', className: "text-center", title: 'Time Out', width: '10%' },
						{ data: 'remarks', title: 'Remarks', width: '40%' },
					],
					columnDefs: [
						{
							targets: 1,
								render:function(data, type, row){
									let value = '';
									if(data == null){
										const loggeddate = row.loggedno;
										const logno_year = loggeddate.substr(0,4);
										const logno_month = loggeddate.substr(4,2);
										const logno_day = loggeddate.substr(6,2);
										const dateoflogged = new Date(logno_year + '/' + logno_month + '/' + logno_day);
										const absentDate = moment(dateoflogged).format('ddd DD MMM YYYY');
										value = absentDate;
									} else {
										value = data;
									}
									return '<span style="display:none;">' + toTimestamp(value) + '</span>' + value;
								}
						},
						{
							targets: [3,4],
								render:function(data, type, row){
									const onleave = row.onleave == 1;
									const absent = row.loggedin == null;

									if(absent){
										return '--:--';
									} else if(onleave) {
										return '-';
									} else {
										return data;
									}
								}
						},
						{
							targets: 5,
								render:function(data, type, row){
									return row.loggeddt == null ? 'NO SIGN IN' : data;
								}
						},
						{ targets: 0, visible: isAdmin },
						{ targets: -1 , orderable: false}
					],
					rowCallback: function(row, data, index){
						const lateflag = parseInt(data.late);
						const rowOffset = isAdmin ? 0 : 1;

						// late legends
						switch(lateflag){
							case 1:
								$(row).find('td:eq('+ (3 - rowOffset) +')').css({'background-color':'#d9534f', 'color':'#fff'});
								break;
							case 2:
								$(row).find('td:eq('+ (3 - rowOffset) +')').css({'background-color':'#F9E79F', 'color':'#676767'});
								break;
							default:
								break;
						}
					},
					rowId: 'id',
				});
				$(datatableid + ' tbody').on('click', 'tr', function (evt) {
					const dataTable = $(datatableid).DataTable();
					if ($(evt.currentTarget.childNodes[0]).hasClass('dataTables_empty')) return false;
					const rowdata = dataTable.row(this).data();

					let leaveid = rowdata.leaveid;
					if(leaveid != null && leaveid != '')
						editLeave(rowdata.leaveid);
				});


				// $("#loaded").val(1);
			}
				
			if ($(datatableid + ' tbody tr td').hasClass('dataTables_empty')) {
				$(datatableid + ' tbody').css('cursor', 'no-drop');
			} else {
				$(datatableid + ' tbody').css('cursor', 'pointer');
			}

			// ! something to fix here
			// var ees = data['eedata']['rows'];

			// if (ees.length > 0) {
			// 	var eehtml = "";
			// 	eehtml += '<option value="" selected>All Employees</option>';
			// 	ees.forEach(ee => {
			// 		// if(ee.reportstoid == userid || ee.reportstoindirectid == userid)
			// 		eehtml += '<option value="' + ee.userid + '">' + ee.fname + ' ' + ee.lname + '</option>';
			// 	});

			// 	$("#txtee").html(eehtml);
			// 	if(user == 'ee'){
			// 		$("#txtee option[value='" + userid + "']").prop('selected', true);
			// 	}
			// 	// if (ees.length == 1) {
			// 	// 	// $("#forlblee").hide();
			// 	// 	$("#dataeelist").hide();
			// 	// }
			// 	$("#loadee").val(1);
			// 	if (ofc == office) {
			// 		if (isHead) {
			// 			if ($("#txtee").val() == "") {
			// 				$("#txtee option[value='" + userid + "']").prop('selected', true);
			// 			} else {
			// 				// console.log(ee);
			// 				$("#txtee option[value='" + ee + "']").prop('selected', true);
			// 			}
			// 		}
			// 	} else {
			// 		$("#txtee option[value='" + ee + "']").prop('selected', true);
			// 	}

			// }
			loadAttendanceChart({
				"attendance": attendance.length,
				"present": count_present,
				"signin": count_signin,
				"late": count_late,
				"onleave": count_onleave,
				"absent": count_absent
			});
			$.unblockUI();
	});
}

function loadAttendanceChart(data){
	
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	

	let charttips = true;
	let chartdata = [data.present, data.late, data.onleave, data.absent];
	let chartcolor = {'present': '#4e73df','late': '#F57C00', 'onleave': '#43A047','absent': '#c62828'};
	let chartlabels = ["TIME IN", "LATE", "LEAVE", "ABSENT"];
	$('#color_present').css('color',chartcolor.present);
	$('#color_late').css('color',chartcolor.late);
	$('#color_onleave').css('color',chartcolor.onleave);
	$('#color_absent').css('color',chartcolor.absent);

	if(data.attendance == 0){
		charttips = false;
		chartdata = [1];
		chartcolor.present = '#c1c1c1';
		chartlabels = ["NONE"]
	}

	let id = 'attendance_chart';
	let chartContainer = $('#' + id);

	// clean chart to avoid flickering bug
	if(chartContainer.hasClass('chartjs-render-monitor')){
		$('#attendance_chart_container').html('').html('<canvas id="attendance_chart"></canvas>');
	}
	chartContainer = $('#' + id);
	let myPieChart = new Chart(chartContainer, {
		type: 'pie',
		data: {
		  labels: chartlabels,
		  datasets: [{
			// data: [data.count_present, data.count_late, data.count_onleave, data.count_absent],
			data: chartdata,
			backgroundColor: [chartcolor.present, chartcolor.late, chartcolor.onleave, chartcolor.absent],
			// hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#020202a1'],
			// hoverBorderColor: "rgba(234, 236, 244, 1)"
		  }],
		},
		options: {
			maintainAspectRatio: false,
			tooltips: {
			backgroundColor: "rgb(255,255,255)",
			bodyFontColor: "#858796",
			borderColor: '#dddfeb',
			borderWidth: 1,
			xPadding: 15,
			yPadding: 15,
			caretPadding: 10,
			enabled: charttips
			},
			legend: {
			display: false,
			},
			cutoutPercentage: 20,
		},
	  });
}

function computeNoOfDays(fm, to, callback) {
	var todt = new Date(to);
	var fmdt = new Date(fm);
	var difference = fmdt > todt ? fmdt - todt : todt - fmdt;
	var diffdays = (Math.floor(difference / (1000 * 3600 * 24)));
	$("#txtNoOfDays").val(diffdays);
	if (callback != undefined) callback();
}

function getLeaveBalance() {
	var url = getAPIURL() + 'leaves.php';
	var f = "getLeaveBalance";
	var userid = $("#userid").val();
	var leavetype = $("#txtLeaveType").val();

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
			$("#leavebal").val(bal);
			$("#leavepending").val(pending);

			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function genLeaveDetails() {
	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			getLeaveDetails();
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
				wor = "";
				// leaves count
				switch (dates[i]['dayofdate']) {
					case "SAT": case "SUN":
						weekend = 'WEEKEND';
						disabled = 'disabled';
						break;
					default: break;
				}
				
				for (a = 0; a < workingdays.length; a++) {
					if (workingdays[a]['salesofficeid'] == ofc) {
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

				if (dates.length > 1 || weekend != "" || hol != "") {
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
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function editLeave(leaveid) {
	html = "";
	$("#uploadattachement").val('');
	$("#output").html(html);
	$.blockUI({
		message: $('#preloader_image'),
		fadeIn: 1000,
		onBlock: function () {
			loadDefault(function (isSuccess) {
				if (isSuccess) {
                    $("#leaveid").val(leaveid);
					getLeave();
				}
			});
		}
	});
}

function getLeave() {
	var leaveid = $("#leaveid").val();
	var url = getAPIURL() + 'leaves.php';
	var f = "getLeave";
	var userid = $("#userid").val();
	var eeid = $("#eeid").val();
	var data = { "f": f, "leaveid": leaveid };

	function reportsTo(reportstoindirect, reportsto) {
		$("#txtApprover1").val(reportstoindirect);
		if (reportstoindirect != '') {
			$("#withindirectapprv").show();
			$("#withoutindirectapprv").hide();
			$("#level1approver").show();
		} else {
			$("#level1approver").hide();
			$("#level1approver").hide();
		}

		$("#txtApprover").val(reportsto);
	}

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data); 
			// return false;
			var leave = data['leave']['rows'][0];
			
            /* RECALL LEAVE */
            if(leave.status != 1 && $('#hasaccess').val() == 1){
                $('#recall-container').hide();
                $('#btnRecallLeave').off('click');
            } else {
                $('#recall-container')
                    .html('<div class="col-12 text-center">'
                        + '  <button type="button" class="btn btn-sm btn-danger text-center" '
                        + '     title="This will undo the leave and return the credits" '
                        + '     id="btnRecallLeave" style="min-width:200px;">Recall</button>'
                        + '</div>')
                    .show();
                    $('#btnRecallLeave').on('click', function() {
						confirmDialog('This leave will be cancelled and recalled, would you like to proceed?',()=> {
							$.blockUI({
								message: $('#preloader_image'),
								fadeIn: 1000,
								onBlock: function () {
									recallApprovedLeave(leaveid)
								}
							});
						});
                    });
               
			}
			
			var reportsto = data.reportstoname;
			var reportstoindirect = data.reportstoindirectname;
			var docpaths = data['docpaths'];
			if (userid === eeid && $('#approval-tab').hasClass('active') && leave.status == 0) {
				$("#request-tab").trigger('click');
				switch (leave['status']) {
					case '1': case '-1': case '-2':
						$("#btnNewRequest").show();
						$("#btnCancelRequest").hide();
						$("#btnUpdateRequest").hide();
						$("#btnSubmit").hide();
						$("#leaveattachment").hide();
						break;
					default:
						$("#btnNewRequest").show();
						if (userid == leave.userid) {
							$("#btnCancelRequest").show();
							$("#btnUpdateRequest").show();
						} else {
							$("#btnCancelRequest").hide();
							$("#btnUpdateRequest").hide();
						}
						$("#btnSubmit").hide();
						$("#leaveattachment").show();

						break;
				}
				reportsTo(reportstoindirect, reportsto);

				$("#txtDateFrom").val(leave['leavefromdt']);
				$("#txtDateTo").val(leave['leavetodt']);
				$('#txtLeaveType').val(leave.leavetypedesc);
				$("#txtLeaveType option").filter(function () {
					return $(this).text() == leave.leavetypedesc;
				}).prop('selected', true);
				// $("#txtLeaveType option[value='" + leave['leavetype'] + "']").prop('selected', true);
				$("#txtLeaveDuration option[value='" + leave['leaveduration'] + "']").prop('selected', true);
				$("#txtNoOfDays").val(leave['noofdays']);
				$("#txtLeaveReason").val(leave['reason']);

				$("#prevdtfrom").val(leave['leavefromdt']);
				$("#prevdtto").val(leave['leavetodt']);
				// $("#prevleavetype").val(leave['leavetype']);
				$("#prevleaveduration").val(leave['leaveduration']);
				// $("#prevnoofdays").val(leave['noofdays']);
				// $("#prevleavereason").val(leave['reason']);

				getLeaveDetails();
			} else {
				const leaveHist = '#LeaveHistModal';
				$(leaveHist).find('#dataeename').html(leave.eename);
				$(leaveHist).find('#dataleavetype').html(leave.leavetypedesc);
				$(leaveHist).find('#datareason').html(leave.reason);
				$(leaveHist).find('#dataleavefrom').html(leave.leavefromdt);
				$(leaveHist).find('#dataleaveto').html(leave.leavetodt);
				$(leaveHist).find('#datanoofdays').html(leave.noofdays);
				$(leaveHist).find('#datacreadt').html(moment(new Date(leave.createddate)).format('ddd D MMM YYYY'));


				$(leaveHist).find('#txtLabelIndirect').html('');
				$(leaveHist).find('#txtCommentsIndirect').html('');
				$(leaveHist).find('#txtLabelDirect').html('');
				$(leaveHist).find('#txtCommentsDirect').html('');

				$(leaveHist).find('#txtCommentsIndirect').html('NONE');
				if (reportstoindirect != '') {
					$(leaveHist).find('#trIndirect').show();
					$(leaveHist).find('#txtLabelIndirect').html(reportstoindirect + ' comments');
					if (leave.commentsbyindirect != '' && leave.commentsbyindirect != null) {
						$(leaveHist).find('#txtCommentsIndirect').html(leave.commentsbyindirect);
					}
				} else {
					$(leaveHist).find('#trIndirect').hide();
				}
				$(leaveHist).find('#txtLabelDirect').html('');
				$(leaveHist).find('#txtLabelDirect').html(reportsto + ' comments');
				$(leaveHist).find('#txtCommentsDirect').html('NONE');
				if (leave.comments != '' && leave.comments != null) {
					$(leaveHist).find('#txtCommentsDirect').html(leave.comments);
				}

				let attachmentshmtl = '';
				if (docpaths.length > 0) {
					docpaths.forEach(path => {
						attachmentshmtl += path;
					});
				}
				if(attachmentshmtl !== '')
					$("#LeaveHistModal #viewattachments").html(attachmentshmtl);

				$(leaveHist).modal('show');
				$.unblockUI();
			}
			// $.unblockUI();         
		}
		, error: function (request, status, err) {

		}
	});
}

function recallApprovedLeave(leaveid) {
	const url = getAPIURL() + 'leaves.php';
	const f = "recallLeaveRequest";
	const userid = $("#userid").val();

	const data = { "f": f, "leaveid": leaveid, "userid": userid };
    // console.log(data);
    // return false;

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			$('#recall-container').html('');
            loadLeaveHistory();
            $.unblockUI();
		}
		, error: function (request, status, err) {
		}
	});
}

function updateLeaveRequest() {
	var url = getAPIURL() + 'leaves.php';
	var f = "updateLeaveRequest";
	var userid = $("#userid").val();
	var leaveid = $("#leaveid").val();
	var leavedtls = $("#txtldtls").val();

	var leavefrom = $("#txtDateFrom").val();
	var leaveto = $("#txtDateTo").val();
	var leavetype = $("#txtLeaveType").val();
	var leaveduration = $("#txtLeaveDuration").val();
	var leavereason = $("#txtLeaveReason").val();
	var noofdays = $("#txtNoOfDays").val();

	var prevleavefrom = $("#prevdtfrom").val();
	var prevleaveto = $("#prevdtto").val();
	var prevleavetype = $("#prevleavetype").val();
	var prevleaveduration = $("#prevleaveduration").val();
	var prevleavereason = $("#prevleavereason").val();
	var prevnoofdays = $("#prevnoofdays").val();
	var uploadattachement = $("#uploadattachement").val();

	var curdata = [leavefrom, leaveto, leaveduration];
	var prevdata = [prevleavefrom, prevleaveto, prevleaveduration];
	var fiscalyearEle = document.getElementById("yearFilterLeaveCredit");
	if (fiscalyearEle == null) {
		var fiscalyear = new Date().getFullYear();
		fiscalyear = fiscalyear.toString();
	} else {
		fiscalyear = fiscalyearEle.value;
	}
	// console.log(curdata);
	// console.log(prevdata);
	// console.log(leavedtls);
	// comapring each element of array 
	var cnt = 0;
	for (var i = 0; i < curdata.length; i++) {
		if (curdata[i] == prevdata[i]) {
			cnt++;
		}
	}
	// console.log(cnt);
	var isUpdate = "";
	if (cnt == curdata.length) {
		isUpdate = "true";
	} else {
		isUpdate = "false";
	}
	// return false;

	var data = { "f": f, "userid": userid, "leavefrom": leavefrom, "leaveto": leaveto, "leavetype": leavetype, "leaveduration": leaveduration, "leavereason": leavereason, "noofdays": noofdays, "leavedetails": leavedtls, "leaveid": leaveid, "isUpdate": isUpdate, "uploadattachement": uploadattachement, "fiscalyear": fiscalyear };

	//console.log(data);
	// return false;
	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
			// return false;
			// clearLeaveFields();
			if (data['errupdateleave']['err'] == 0) {
				alertDialog('Leave request has been successfully updated.');
			} else if (data['errupdateleave']['err'] == 1) {
				alertDialog('An error occured while updating leave request. Please contact IT immediately.');
				return false;
			}
			var leavedetails = data['leavedtls']['rows'][0];
			var leaves = data['leaves']['rows'];
			loadLeaveHistory();
			// 	if(leaves.length > 0){
			// 			if(!$.fn.DataTable.isDataTable('#leavesdatatable')){
			// 				var leavelist = $('#leavesdatatable').DataTable({
			// 				"dom": '<"pull-right" f><t>',
			// 				"searching": false,
			// 				data: leaves,
			// 				paging: false,
			// 				lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
			// 				columns:[
			// 					{ 'data': 'createddt', "className":"text-center" },
			// 					{ 'data': 'leavetypedesc' },
			// 					{ 'data': 'leavefromdt', "className":"text-center" },
			// 					{ 'data': 'leavetodt', "className":"text-center" },
			// 					{ 'data': 'noofdays', "className":"text-center" },
			// 					{ 'data': 'reason' },
			// 					{ 'data': 'approvedbyname' },
			// 					{ 'data': 'leavestatus' },
			// 				]
			// 			});
			// 		}else{
			// 			$('#leavesdatatable').dataTable().fnClearTable();
			// 				$('#leavesdatatable').dataTable().fnAddData(leaves);
			// 		}
			// }

			var leavecredits = data['leavecredits']['rows'];
			$('#leavecreditsdatatable').dataTable().fnClearTable();
			$('#leavecreditsdatatable').dataTable().fnAddData(leavecredits);

			$("#prevdtfrom").val(leavedetails['leavefromdt']);
			$("#prevdtto").val(leavedetails['leavetodt']);
			$("#prevleaveduration").val(leavedetails['leaveduration']);
			// console.log(leavedetails);
			$.unblockUI();
		}
		, error: function (request, status, err) {

		}
	});
}

function cancelLeaveRequest() {
	var leaveid = $("#leaveid").val();
	var url = getAPIURL() + 'leaves.php';
	var f = "cancelLeaveRequest";
	var userid = $("#userid").val();

	var data = { "f": f, "leaveid": leaveid };
	// console.log(data);

	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
			// console.log(data);
            clearLeaveFields();
            $("#approval-tab").trigger('click');
            $.unblockUI();
            $.blockUI({
                message: $('#preloader_image'),
                fadeIn: 1000,
                onBlock: function () {
                    loadPendingLeaveRequests();
                }
            });
		}
		, error: function (request, status, err) {

		}
	});
}

function clearLeaveFields() {
	$("#txtDateFrom").val($("#curdt").val());
	$("#txtDateTo").val($("#curdt").val());

	// $("#userid").val('');
	$("#leaveid").val('');
	var fm = $("#txtDateFrom").val();
	var to = $("#txtDateTo").val();
	computeNoOfDays(fm, to);
	var defleavetype = $("#defleavetype").val();
	html = "";
	$("#output").html(html);
	$("#txtLeaveType option[value=''").prop('selected', true);
	$('#txtLeaveDuration option').prop('selected', function () { return this.defaultSelected; });
	$("#txtLeaveReason").val('');
	$("#uploadattachement").val('');
	$("#btnCancelRequest").hide();
	$("#btnNewRequest").hide();
	$("#btnUpdateRequest").hide();
	$("#btnSubmit").show();
	$("#leaveattachment").show();
}


// function bs_input_file() {
// 	$(".input-file").before(
// 		function() {
// 			if ( ! $(this).prev().hasClass('input-ghost') ) {
// 				var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
// 				element.attr("name",$(this).attr("name"));
// 				element.change(function(){
// 					element.next(element).find('input').val((element.val()).split('\\').pop());
// 				});
// 				$(this).find("button.btn-choose").click(function(){
// 					element.click();
// 				});
// 				// $(this).find("button.btn-reset").click(function(){
// 				// 	element.val(null);
// 				// 	$(this).parents(".input-file").find('input').val('');
// 				// });
// 				$(this).find('input').css("cursor","pointer");
// 				$(this).find('input').mousedown(function() {
// 					$(this).parents('.input-file').prev().click();
// 					return false;
// 				});
// 				return element;
// 			}
// 		}
// 	);
// }

function checkPeriodIfExist(){
    var userid = $("#userid").val();
	var logfrom = $("#txtLogFrom").val();
	var logto = $("#txtLogTo").val(); 
    var ofc = $("#txtofc").val();
    var url = getAPIURL() + 'payroll.php';
    var f = "checkPeriodIfExist";
	var data = { "f":f, "userid":userid, "logfrom":logfrom, "logto":logto, "ofc":ofc };
    // console.log(url);
	$.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
            // console.log(data);
            var payrollifexist = data['payrollifexist']['rows'];
            if(payrollifexist.length > 0){
                confirmDialog("This payroll period has been generated. Would you like to view it?", function(){
                    window.location = 'payroll.php';
                });
            }
		}
		, error: function (request, status, err) {
            console.log(err);
		}
	});
	
}

function cancelLeave(leaveid){
	const data = {
		leaveid: leaveid
	}
	confirmDialog("You are about to cancel this leave request. Do you want to continue?", function(){
		blockUI(()=> {
			qryData('leaves', 'cancelLeaveRequest', data, (data) => {
				loadPendingLeaveRequests();
				$.unblockUI(); 
			});
		});
	});
}