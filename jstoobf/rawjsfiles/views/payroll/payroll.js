$(function () {
    
    $('#btnRetrieve').on('click', function (e) {
        $.blockUI({
            message: $('#preloader_image'),
            fadeIn: 1000,
            onBlock: function () {
                getAttendanceforPayroll();
            }
        });
    });

    $('#btnProceedToPayroll').on('click', function () {
        var isTblEmpty = isTableEmpty('#attendancereviewtbl');
        var isPeriodExist = checkPeriodIfExist();

        if(isTblEmpty == 0){
            alertDialog('There are no summary attendance record. Please click RETRIEVE button to get the summary attendance record.');
            return false;
        }
        
        if(isPeriodExist == 1){
            return false;
        }

        $.blockUI({
            message: $('#preloader_image'),
            fadeIn: 1000,
            onBlock: function () {
                addPayrollMaster();
            }
        });
    });

    $('#btnAddMiscellaneous').on('click', function () {
        ccyHandler('#miscAmount');
        isPeriod();
        loadPeriodNo();
        clearMiscellaneousForm();
        loadSaveAction_add();
        $('#addmiscellaneous').modal('show');
    });  
    
    $('#btnAddSalAdjustment').on('click', function () {
        ccyHandler('#saladjAmount');
        ccyHandler('#saladjDeminimisAmount');
        isPeriod();
        loadPeriodNo();
        clearSalaryAdjustmentForm();
        loadSalAdjSaveAction_add();
        $('#addsalaryadjustment').modal('show');
    });  

    $('#btnAddLoans').on('click', function () {
        window.open('loans.php');
		// checkPeriodIfExist();
		// window.location = 'payrollprocess.php';
    });
    
    

    var sPath = window.location.pathname;
    var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
    // console.log(sPage);
    switch (sPage) {
        case 'payrollprocess.php':
            loadEmployeeSalaryRecord();
            calculatePayrollQuery();
            DropdownCollections();
            loadLoanDataTable();
            loadPayrollSettings();
            isPeriod();
            break;
        case 'attendancepayrollreview.php': 
            blockUI(()=> {
                loadPayrollSettings();
                isPeriod();
            });
        case 'payrollmanagement.php':
            loadPayrollMasterList();
            break;
        default:
            break;
    }
});

function DropdownCollections(){
    loadEeList();
    loadMiscType();
    loadSalAdjType();
}
function loadEeList() {
    const modal = '#addmiscellaneous';
    $('#addmiscellaneous').find('#eename').html('<option>Loading...</option>');
    $('#addsalaryadjustment').find('#eename').html('<option>Loading...</option>');
    qryData('payroll', 'getEeList', {ofcid: ''}, data => {
        const eelist = data.eelist;
        let eenamehtml = '<option selected></option>';
        eenamehtml += eelist.map(ee => '<option value="'+ ee.userid +'">'+ ee.eename +'</option>');
        $('#addmiscellaneous').find('#eename').html(eenamehtml);
        $('#addsalaryadjustment').find('#eename').html(eenamehtml);
        eenamehtml = null; // clean
    });
}

function loadMiscType() {
    const modal = '#addmiscellaneous';
    $(modal).find('#misctype').html('<option>Loading...</option>');
    qryData('payroll', 'loadMiscellaneous', {}, data => {
        const loantypelist = data.misctypelist;
        let misctypehtml = '<option selected></option>';
        misctypehtml += loantypelist.map(type => '<option value="'+ type.ddid +'">'+ type.dddescription +'</option>');
        $(modal).find('#misctype').html(misctypehtml);
        misctypehtml = null; // clean
    });
}

function loadSalAdjType() {
    const modal = '#addsalaryadjustment';
    $(modal).find('#saladjtype').html('<option>Loading...</option>');
    qryData('payroll', 'loadSalaryAdjustmenType', {}, data => {
        const saladjtypelist = data.saladjtypelist;
        let saladjhtml = '<option selected></option>';
        saladjhtml += saladjtypelist.map(type => '<option value="'+ type.ddid +'">'+ type.dddescription +'</option>');
        $(modal).find('#saladjtype').html(saladjhtml);
        saladjhtml = null; // clean
    });
}

function loadPeriodNo(){
    const modal = '#addmiscellaneous';
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    qryData('payroll', 'getPayrollMaster', {sesid: sesid}, data => {
        const payrollmaster = data.getpayrollmaster.rows;
        const periodno = payrollmaster[0]['periodno'];
        const paydate = payrollmaster[0]['paydate'];
        if(periodno == 1){
            $('#txtPeriodNo option[value="1"]').prop('selected', true);
        }else if(periodno == 2){
            $('#txtPeriodNo option[value="2"]').prop('selected', true);
        }
        $('#addmiscellaneous').find("#txtpaydate").val(moment(new Date(paydate)).format('ddd D MMM YYYY'));
        $('#addsalaryadjustment').find("#txtpaydate").val(moment(new Date(paydate)).format('ddd D MMM YYYY'));
        // console.log(paydate);
    });
}

function calculatePayrollQuery(){
    var userid = $("#userid").val();
    var params = new window.URLSearchParams(window.location.search);
    var office = atob(params.get('office'));
    var sesid = params.get('id');
    var logfm = $("#logfm").html();
    var logto = $("#logto").html();
    var url = getAPIURL() + 'payroll.php';
    var f = "calculatePayrollQuery";
    var data = { "f":f, "userid":userid, "office":office, "logfm":logfm, "logto":logto, "sesid":sesid };
    $.ajax({
		type: 'POST',
        url: url,
        // async: false,
		data: JSON.stringify({ "data": data }),
        dataType: 'json', 
        success: function (data) {
            // console.log(data);
            payrolcalulationres = data['payrolcalulationres']['rows'];
            payrollmaster = data['payrollmaster']['rows'];
            genSSSDeductionTable(payrolcalulationres);
            genPhilHealthDeductionTable(payrolcalulationres);
            genPagIbighDeductionTable(payrolcalulationres);
            genSummaryGovDeductions(payrolcalulationres,payrollmaster);
            genWithHoldingTax(payrolcalulationres);
            loadActiveSalaryAdjusment();
            genReviewPayroll();
            genMiscellaneousPayments();

            //removeattribs
            var periodno = payrollmaster[0]['periodno'];
            if(periodno == 1){
                $('#philtab').remove();
                $('#philhealthdeduction').remove();
                
                $('#pagibigtab').remove();
                $('#pagibigdeduction').remove();
                
                $('#sssdeduction').addClass('show active');
                $('#sssdeduction-tab').addClass('active');
            }
            if(periodno == 2){
                $('#ssstab').remove();
                $('#sssdeduction').remove();
                $('#philhealthdeduction').addClass('show active');
                $('#philhealthdeduction-tab').addClass('active');
            }

            // $.unblockUI();
		}   
		, error: function (request, status, err) {
            console.log(err);
		}
    });
}

function loadPayrollSettings() {
    var params = new window.URLSearchParams(window.location.search);
    var office = atob(params.get('office'));
    var url = getAPIURL() + 'payroll.php';
    var f = "loadPayrollSettings";
    var data = { "f": f, "office": office };
    // console.log(data);
    
    $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ "data": data }),
        dataType: 'json'
        , success: function (data) {
            // console.log(data);
            var payrollsettings = data['payrollsettings']['rows'][0];
            var frequency = payrollsettings['frequency'];
            var today = new Date();
            var curmonth = today.getMonth();
            var curyear = today.getFullYear();
            var curday = today.getDay();

            $("#txtLogFrom,#txtLogTo").datepicker({
                dateFormat: "D dd M y",
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:2020"
            });

            if (frequency == "SEMI-MONTHLY") {
                $("#txtperiod").change(function () {
                    var periodno = $("#txtperiod").val();
                    getPeriod(periodno);
                });
            }
            $("#frequency").html(frequency);
            
            
        }
        , error: function (request, status, err) {
            console.log(err);
        }
    });
}

function getPeriod(periodno) {
    var today = new Date();
    var curmonth = moment(today).format("M");
    var curyear = today.getFullYear();
    var curday = today.getDay();
    // console.log(periodno);
    if (periodno == 1) {
        var dateto = new Date(curyear + '-' + curmonth + '-' + '10');
        var a = moment(dateto).subtract(1, 'months');
        var datefrom = new Date(curyear + '-' + moment(a).format("M") + '-' + '26');

        $("#txtLogTo").val(moment(new Date(dateto)).format('ddd D MMM YYYY'));
        $("#txtLogFrom").val(moment(new Date(datefrom)).format('ddd D MMM YYYY'));
        
    } else if (periodno == 2) {
        var dateto = new Date(curyear + '-' + curmonth + '-' + '25');
        var datefrom = new Date(curyear + '-' + curmonth + '-' + '11');

        $("#txtLogTo").val(moment(new Date(dateto)).format('ddd D MMM YYYY'));
        $("#txtLogFrom").val(moment(new Date(datefrom)).format('ddd D MMM YYYY'));
    }
}

function isPeriod() {
    var today = new Date();
    var curmonth = moment(today).format("M");
    var curyear = today.getFullYear();
    var curday = today.getDay();

    var datefrom = new Date(curyear + '-' + curmonth + '-' + '10');

    // console.log(today +'<'+datefrom);
    if (today >= datefrom) {
        $('#txtperiod option[value="2"]').prop('selected', true);
        getPeriod(2);
    } else {
        $('#txtperiod option[value="1"]').prop('selected', true);
        getPeriod(1);
    }
}

function getAttendanceforPayroll() {
    var url = getAPIURL() + 'payroll.php';
    var f = "getAttendanceforPayroll";
    var params = new window.URLSearchParams(window.location.search);
    var office = atob(params.get('office'));
    var logfm = $("#txtLogFrom").val();
    var logto = $("#txtLogTo").val();
    var data = { "f": f, "office": office, "logfm": logfm, "logto": logto };
    // console.log(data);

    $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ "data": data }),
        dataType: 'json'
        , success: function (data) {
            // console.log(data);
            const datatableid = '#attendancereviewtbl';
            let attendancerecord = data['attendancerecord']['rows'];
            let summaryattendance = data['summaryattendance']['rows'];
            // let count_signin = attendancerecord.filter(ee => ee.onleave == 0 && ee.loggedin != null).length;
            // let count_late = attendancerecord.filter(ee => ee.late == 1).length;
            // let count_absent = attendancerecord.filter(ee => ee.loggedin == null).length;
            // let count_onleave = attendancerecord.filter(ee => ee.onleave == 1).length;
            // let count_present = parseInt(count_signin) + parseInt(count_late);
            // let count_lal = attendancerecord.filter(ee => ee.leavetype == 'AL').length;
            // let count_lsl = attendancerecord.filter(ee => ee.leavetype == 'SL').length;
            // let count_lul = attendancerecord.filter(ee => ee.leavetype == 'UL').length;
            // console.log('late ' + count_late);
            // console.log('absent ' + count_absent);
            // console.log('onleave ' + count_onleave);
            // console.log('present ' + count_present);

            if ($.fn.DataTable.isDataTable(datatableid)) {
                $(datatableid).dataTable().fnClearTable();
                if (summaryattendance.length > 0) {
                    $(datatableid).dataTable().fnAddData(summaryattendance);
                }
            } else {
                summaryattendancelist = $(datatableid).DataTable({
                    "dom": '<"pull-right" f><t>',
                    "searching": false,
                    data: summaryattendance,
                    language: {
                        emptyTable: '<center>No attendance record</center>'
                    },
                    paging: false,
                    responsive: true,
                    ordering: true,
                    order: [[0, 'asc']],
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                    columns: [
                        { data: 'eename' },
                        { data: 'workingdays' },
                        { data: 'ttlpresent' },
                        {
                            data: function (data, type, dataToSet) {
                                let ttlabsences = 0;
                                ttlabsences = parseInt(data.workingdays) - parseInt(data.ttlpresent);
                                if(data.ttlpresent > data.workingdays){
                                    ttlabsences = 0;
                                }
                                return ttlabsences;
                            }
                        }
                    ]
                });
            }
            $('#attendancereviewtbl tbody').on('click', 'tr', function () {
                thisdata = summaryattendancelist.row(this).data();
                userid = thisdata['userid'];
                eename = thisdata['eename'];
                let attendancedetails = attendancerecord.filter(ee => ee.userid == userid);
                generateAttendanceDetails(attendancedetails);
                $('#eename').html(eename);
                $('#frmattendancedetails').modal('show');
                // console.log(eename);
                return false;
            });
            $.unblockUI();

        }
        , error: function (request, status, err) {
            console.log(err);
        }
    });
}

function generateAttendanceDetails(attendancedetails) {
    const datatableid = '#attendancedetailstbl';
    const isAdmin = $('#hasaccess').val() == 1;
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (attendancedetails.length > 0) {
            $(datatableid).dataTable().fnAddData(attendancedetails);
        }
    } else {
        attendancedetailslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: attendancedetails,
            language: {
                emptyTable: '<center>No attendance record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'desc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'loggeddt' },
                { data: 'login' },
                { data: 'logout' },
                { data: 'remarks' },
            ],
            columnDefs: [
                {
                    targets: 0, render: function (data) {
                        return '<span style="display:none;">' + toTimestamp(data) + '</span>' + data;
                    }
                },
                { targets: 0, visible: isAdmin },
                // { targets: -1 , orderable: false}
            ],
            rowCallback: function (row, data, index) {
                let lateflag = parseInt(data.late);
                let onleave = data.onleave == 1;
                let absent = data.loggedin == null;
                let rowOffset = isAdmin ? 0 : 1;

                // late legends
                switch (lateflag) {
                    case 1:
                        $(row).find('td:eq(' + (1 - rowOffset) + ')').css({ 'background-color': '#d9534f', 'color': '#fff' });
                        break;
                    case 2:
                        $(row).find('td:eq(' + (1 - rowOffset) + ')').css({ 'background-color': '#F9E79F', 'color': '#676767' });
                        break;
                    default:
                        break;
                }

                // on leave, time should be --:--
                if (onleave) {
                    $(row).find('td:eq(' + (1 - rowOffset) + ')').html('-');
                    $(row).find('td:eq(' + (2 - rowOffset) + ')').html('-');
                }
                if (absent) {
                    let loggeddate = data.loggedno;
                    let logno_year = loggeddate.substr(0, 4);
                    let logno_month = loggeddate.substr(4, 2);
                    let logno_day = loggeddate.substr(6, 2);
                    let dateoflogged = new Date(logno_year + '/' + logno_month + '/' + logno_day);
                    $(row).find('td:eq(' + (0 - rowOffset) + ')').html(moment(dateoflogged).format('ddd D MMM YYYY'));

                    $(row).find('td:eq(' + (1 - rowOffset) + ')').html('--:--');
                    $(row).find('td:eq(' + (2 - rowOffset) + ')').html('--:--');
                    // console.log(row);
                    $(row).find('td:eq(' + (3 - rowOffset) + ')').html('ABSENT');
                }
            },
            rowId: 'id',
        });
    }
}

function addPayrollMaster(){
    var url = getAPIURL() + 'payroll.php';
    var f = "addPayrollMaster";
    var params = new window.URLSearchParams(window.location.search);
    var office = atob(params.get('office'));
    var logfm = $("#txtLogFrom").val();
    var logto = $("#txtLogTo").val();
    var userid = $("#userid").val();
    var period = $("#txtperiod").val();
    var today = new Date();
    var curmonth = moment(today).format("M");
    var curyear = today.getFullYear();
    var data = { "f": f, "office": office, "logfm": logfm, "logto": logto, "userid":userid, "period":period, "curmonth":curmonth, "curyear":curyear};
    // console.log(url);

    $.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
        dataType: 'json', 
        success: function (data) {
            // console.log(data);
            var sesid = data['sesid'];
            var params = new window.URLSearchParams(window.location.search);
            var office = atob(params.get('office'));
            office = btoa(office);
            
            window.location = 'payrollprocess.php?id='+ sesid +'&office=' + office ;
		}
		, error: function (request, status, err) {
            console.log(err +', '+ request +','+ status);
		}
    });
}

function isTableEmpty(elementid){
    const datatableid = elementid;
    if (!$.fn.DataTable.isDataTable(datatableid)) {
        return 0;
    }else{
        return 1;
    }
}

function checkPeriodIfExist(){
    var userid = $("#userid").val();
	var logfrom = $("#txtLogFrom").val();
    var logto = $("#txtLogTo").val(); 
    var params = new window.URLSearchParams(window.location.search);
    var ofc = atob(params.get('office'));
    var url = getAPIURL() + 'payroll.php';
    var f = "checkPeriodIfExist";
	var data = { "f":f, "userid":userid, "logfrom":logfrom, "logto":logto, "ofc":ofc };
    var isTrue = 0;
	$.ajax({
		type: 'POST',
        url: url,
        async: false,
		data: JSON.stringify({ "data": data }),
        dataType: 'json', 
        success: function (data) {
            // console.log(data);
            var payrollifexist = data['payrollifexist']['rows'];
            if(payrollifexist.length > 0){
                isTrue =1;
                confirmDialog("This payroll period has been generated. Would you like to view it?", function(){
                    window.location = 'payroll.php';
                });
            };
		}
		, error: function (request, status, err) {
            console.log(err);
		}
    });

    return isTrue;
    
}

function loadEmployeeSalaryRecord(){
    var userid = $("#userid").val();
    var params = new window.URLSearchParams(window.location.search);
    var office = atob(params.get('office'));
    var sesid = params.get('id');
    var url = getAPIURL() + 'payroll.php';
    var f = "loadEmployeeSalaryRecord";
    var data = { "f":f, "userid":userid, "office":office, "sesid":sesid };
    // console.log(data);
    // return false;
    $.ajax({
		type: 'POST',
        url: url,
        async: false,
		data: JSON.stringify({ "data": data }),
        dataType: 'json', 
        success: function (data) {
            // console.log(data);
            var payrollsettings = data['payrollsettings']['rows'];
            var employeesalaryrecord = data['employeesalaryrecord']['rows'];
            var datatableid = '#employeesalaryviewtbl';
            var frequency = payrollsettings[0]['frequency'];
            var payrollmaster = data['getpayrollmaster']['rows'];

            let logfm = moment(payrollmaster['0']['payperiodfrom']).format('ddd D MMM YYYY');
            let logto = moment(payrollmaster['0']['payperiodto']).format('ddd D MMM YYYY');
            let paydate = moment(payrollmaster['0']['paydate']).format('ddd D MMM YYYY');
            $("#logfm").html(logfm);
            $("#logto").html(logto);
            $("#payrollperiod").val(logfm+' - '+ logto);
            $("#paydate").val(paydate);
            
            if ($.fn.DataTable.isDataTable(datatableid)) {
                $(datatableid).dataTable().fnClearTable();
                if (employeesalaryrecord.length > 0) {
                    $(datatableid).dataTable().fnAddData(employeesalaryrecord);
                }
            } else {
                employeesalaryrecordlist = $(datatableid).DataTable({
                    "dom": '<"pull-right" f><t>',
                    "searching": false,
                    data: employeesalaryrecord,
                    language: {
                        emptyTable: '<center>No employee salary record</center>'
                    },
                    paging: false,
                    responsive: true,
                    ordering: true,
                    order: [[0, 'asc']],
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                    columns: [
                        { data: 'eename' },
                        { data: 'fbasicpay'},
                        { data: 'fdeminimis'},
                        { 
                            data: function (data, type, dataToSet) {
                                let ttlsalaryrate = 0;
                                ttlsalaryrate = parseInt(data.fbasicpay) + parseInt(data.fdeminimis);
                                return ttlsalaryrate;
                            }
                        }
                    ],
                    columnDefs:[
                        {
                            targets: [1, 2, 3], render: function (data) {
                                return ccyFormat(data,2);
                            },className:'text-right'
                        }
                    ],
                    
                    fnDrawCallback:function(){
                        switch (frequency) {
                            case 'SEMI-MONTHLY':
                                $("#frequencybasicpay").html("Semi-Monthly Basic Pay");
                                $("#frequencydeminimis").html("Semi-Monthly Deminimis");
                                $("#frequencyrate").html("Semi-Monthly Rate");
                                break;
                            case 'MONTHLY':
                                $("#frequencybasicpay").html("Monthly Basic Pay");
                                $("#frequencydeminimis").html("Monthly Deminimis");
                                $("#frequencyrate").html("Monthly Rate");
                                break;
                            default:
                                break;
                        }

                        let api = this.api();
                        let ttlsalary = api.column(3).data().sum()
                        $( api.table().column(3).footer() ).html(ccyFormat(ttlsalary,2));
                        // console.log(ttlsalary);
                    }
                });
            }

		}
		, error: function (request, status, err) {
            console.log(err);
		}
    });

}

// function updatePayrollDetails(category){
//     // const datatablelist = $(datatableid).DataTable();
//     // datarows = datatablelist.rows().data().toArray();
//     // let dataarray = [];
//     // $.each(datarows, function(key, value) {
//     //     dataarray.push(value);
//     // });
//     var params = new window.URLSearchParams(window.location.search);
//     var logfm = $("#logfm").html();
//     var logto = $("#logto").html();
//     var sesid = params.get('id');
//     var office = atob(params.get('office'));
//     var userid = $("#userid").val();
//     var url = getAPIURL() + 'payroll.php';
//     var f = "updatePayrollDetails";
//     var data = { "f":f, "userid":userid, "category":category, "office":office, "sesid":sesid, "logfm":logfm, "logto":logto };
//     // console.log(data);
//     // return false;
//     $.ajax({
// 		type: 'POST',
//         url: url,
//         // async: false,
// 		data: JSON.stringify({ "data": data }),
//         dataType: 'json', 
//         success: function (data) {
//                     console.log(data);
            
// 		}
// 		, error: function (request, status, err) {
//             console.log(err);
// 		}
//     });

// }

function genSSSDeductionTable(govedeductionsdata){
    const datatableid = '#sssdeductionviewtbl';
    // console.log(govedeductionsdata);
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (govedeductionsdata.length > 0) {
            $(datatableid).dataTable().fnAddData(govedeductionsdata);
        }
    } else {
        sssdeductionslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: govedeductionsdata,
            language: {
                emptyTable: '<center>No sss record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'asc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'eename' },
                { data: 'basicpay' },
                { data: 'ssscontriee' },
                { data: 'ssscontrier' },
                { 
                    data: function (data, type, dataToSet) {
                        let ttlssscontribution = 0;
                        ttlssscontribution = parseInt(data.ssscontriee) + parseInt(data.ssscontrier);
                        return ttlssscontribution;
                    }
                },
            ],
            columnDefs:[
                {
                    targets: [1, 2, 3, 4], render: function (data) {
                        return ccyFormat(data,2);
                    },className:'text-right'
                }
            ],
                    
            fnDrawCallback:function(){

                let api = this.api();
                let ttlssscontribution = api.column(4).data().sum()
                $( api.table().column(4).footer() ).html(ccyFormat(ttlssscontribution,2));
                // console.log(ttlsalary);
            },

            rowId: 'id',
        });
    }
}

function genPhilHealthDeductionTable(govedeductionsdata){
    const datatableid = '#phildeductionviewtbl';
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (govedeductionsdata.length > 0) {
            $(datatableid).dataTable().fnAddData(govedeductionsdata);
        }
    } else {
        phildeductionslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: govedeductionsdata,
            language: {
                emptyTable: '<center>No philhealth record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'asc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'eename' },
                { data: 'basicpay' },
                { data: 'philee' },
                { data: 'philee' },
                { 
                    data: function (data, type, dataToSet) {
                        let ttlphilcontribution = 0;
                        ttlphilcontribution = parseInt(data.philee) + parseInt(data.philee);
                        return ttlphilcontribution;
                    }
                },
            ],
            columnDefs:[
                {
                    targets: [1, 2, 3, 4], render: function (data) {
                        return ccyFormat(data,2);
                    },className:'text-right'
                }
            ],
                    
            fnDrawCallback:function(){

                let api = this.api();
                let ttlphilcontribution = api.column(4).data().sum()
                $( api.table().column(4).footer() ).html(ccyFormat(ttlphilcontribution,2));
                // console.log(ttlsalary);
            },

            rowId: 'id',
        });
    }
}

function genPagIbighDeductionTable(govedeductionsdata){
    const datatableid = '#pagibigdeductionviewtbl';
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (govedeductionsdata.length > 0) {
            $(datatableid).dataTable().fnAddData(govedeductionsdata);
        }
    } else {
        pagibigdeductionslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: govedeductionsdata,
            language: {
                emptyTable: '<center>No pagibig record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'asc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'eename' },
                { data: 'basicpay' },
                { data: 'pagibigee' },
                { data: 'pagibiger' },
                { 
                    data: function (data, type, dataToSet) {
                        let ttlpagibigcontribution = 0;
                        ttlpagibigcontribution = parseInt(data.pagibigee) + parseInt(data.pagibiger);
                        return ttlpagibigcontribution;
                    }
                },
            ],
            columnDefs:[
                {
                    targets: [1, 2, 3, 4], render: function (data) {
                        return ccyFormat(data,2);
                    },className:'text-right'
                }
            ],
                    
            fnDrawCallback:function(){
                let api = this.api();
                let ttlpagibigcontribution = api.column(4).data().sum()
                $( api.table().column(4).footer() ).html(ccyFormat(ttlpagibigcontribution,2));
                // console.log(ttlsalary);
            },

            rowId: 'id',
        });
    }
}

function genSummaryGovDeductions(govedeductionsdata,payrollmaster){
    const datatableid = '#summarygovdeductionviewtbl';
    var periodno = payrollmaster[0]['periodno'];
    var targetcol = 0;
    var visibleoption = 0;
    if(periodno == 1){
        targetcol = [2, 3];
        visibleoption = 0;
    }else if(periodno == 2){
        targetcol = 1;
        visibleoption = 0;
    }
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (govedeductionsdata.length > 0) {
            $(datatableid).dataTable().fnAddData(govedeductionsdata);
        }
    } else {
        summarygovdeductionslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: govedeductionsdata,
            language: {
                emptyTable: '<center>No record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'asc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'eename' },
                { data: 'ssscontriee' },
                { data: 'pagibigee' },
                { data: 'philee' },
                { 
                    data: function (data, type, dataToSet) {
                        
                        let ttlgovdeductions = 0;
                        if(periodno == 1){
                            ttlgovdeductions = parseInt(data.ssscontriee);
                        }else if(periodno == 2){
                            ttlgovdeductions = parseInt(data.pagibigee) + parseInt(data.philee);
                        }
                        return ttlgovdeductions;
                    }
                },
            ],
            columnDefs:[
                {
                    targets: [1, 2, 3, 4], render: function (data) {
                        return ccyFormat(data,2);
                    },className:'text-right'
                },
                { targets: targetcol, visible: visibleoption }
            ],
                    
            fnDrawCallback:function(){
                let api = this.api();
                let ttlgovdeductions = api.column(4).data().sum()
                $( api.table().column(4).footer() ).html(ccyFormat(ttlgovdeductions,2));
                // console.log(ttlsalary);
            },

            rowId: 'id',
        });
    }
}

function genWithHoldingTax(withholdingtaxesdata){
    // console.log(withholdingtaxesdata);
    const datatableid = '#withholdingtaxviewtbl';
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (withholdingtaxesdata.length > 0) {
            $(datatableid).dataTable().fnAddData(withholdingtaxesdata);
        }
    } else {
        summarygovdeductionslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: withholdingtaxesdata,
            language: {
                emptyTable: '<center>No record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'asc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'eename' },
                { data: 'basicpayaftertax' },
                { data: 'prescibedmintaxdue' },
                { data: 'excessoverlimitamount' },
                { data: 'ttltaxdue' },
                { data: 'basicpayaftertax' },
            ],
            columnDefs:[
                {
                    targets: [1, 2, 3, 4, 5], render: function (data) {
                        return ccyFormat(data,2);
                    },className:'text-right'
                }
            ],
                    
            fnDrawCallback:function(){
                let api = this.api();
                let ttlbasicpay = api.column(5).data().sum()
                $( api.table().column(5).footer() ).html(ccyFormat(ttlbasicpay,2));
                // console.log(ttlsalary);
            },

            rowId: 'id',
        });
    }
}

function loadActiveSalaryAdjusment(){
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    const data = {
        sesid:sesid
    }
    qryData('payroll', 'getActiveSalaryAdjusments', data, data => {
        const salaryadlist = data.salaryadlist.rows;
        genSalaryAdjustments(salaryadlist);
    });
}

function genSalaryAdjustments(salaryadjusmentdata){
    const datatableid = '#salaryadjusmentsviewtbl';
    if ($.fn.DataTable.isDataTable(datatableid)) {
        $(datatableid).dataTable().fnClearTable();
        if (salaryadjusmentdata.length > 0) {
            $(datatableid).dataTable().fnAddData(salaryadjusmentdata);
        }
    } else {
        summarygovdeductionslist = $(datatableid).DataTable({
            "dom": '<"pull-right" f><t>',
            "searching": false,
            data: salaryadjusmentdata,
            language: {
                emptyTable: '<center>No record</center>'
            },
            paging: false,
            responsive: true,
            ordering: true,
            order: [[0, 'asc']],
            lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
            columns: [
                { data: 'eename' },
                { data: 'salaryadjusmenttype' },
                { data: 'description' },
                { data: 'basicsalaryadjustmentamount' },
                { data: 'deminimisadjamount' },
                { data: 'totalsalaryadjustmentamount' },
            ],
            columnDefs:[
                {
                    targets: [3, 4, 5], render: function (data) {
                        return ccyFormat(data,2);
                    },className:'text-right'
                }
            ],
                    
            fnDrawCallback:function(){
                let api = this.api();
                let ttlsalaryadjusment = api.column(5).data().sum()
                $( api.table().column(5).footer() ).html(ccyFormat(ttlsalaryadjusment,2));
                // console.log(ttlsalary);
            },

            rowId: 'id',
        });

        $(datatableid + ' tbody').on('click', 'tr', function (evt) {
            const dataTable = $(datatableid).DataTable();
            if ($(evt.currentTarget.childNodes[0]).hasClass('dataTables_empty')) return false;
            const rowdata = dataTable.row(this).data();

            let salaryadjid = rowdata.salaryadjid;
            if(salaryadjid != null && salaryadjid != '')
                loadSalAdjSaveAction_update();
                loadSalaryAdjustmentForm(rowdata);
        });
    }

    if ($(datatableid + ' tbody tr td').hasClass('dataTables_empty')) {
        $(datatableid + ' tbody').css('cursor', 'no-drop');
    } else {
        $(datatableid + ' tbody').css('cursor', 'pointer');
    }
    
}

function addNewMiscellaneous() {
    const modal = '#addmiscellaneous';
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    const data = {
        curuserid: $(modal).find('#eename option:selected').val(),
        misctype: $(modal).find('#misctype option:selected').val(),
        miscdesc: $(modal).find('#miscdesc').val(),
        miscamount: $(modal).find('#miscAmount').val(),
        periodno: $(modal).find('#txtPeriodNo option:selected').val(),
        paydate: $(modal).find('#txtpaydate').val(),
        userid: $('#userid').val(),
        sesid:sesid
    }
    blockUI(()=> {
        qryData('payroll', 'addNewMiscellaneous', data, (data) => {
            genMiscellaneousPayments();
            $(modal).modal('hide');
            $.unblockUI(); 
        });
    });
}

function updateMiscellaneous() {
    const modal = '#addmiscellaneous';
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    const data = {
        miscid: $(modal).find('#miscid').val(),
        curuserid: $(modal).find('#eename option:selected').val(),
        misctype: $(modal).find('#misctype option:selected').val(),
        miscdesc: $(modal).find('#miscdesc').val(),
        miscamount: $(modal).find('#miscAmount').val(),
        periodno: $(modal).find('#txtPeriodNo option:selected').val(),
        paydate: $(modal).find('#txtpaydate').val(),
        userid: $('#userid').val(),
        sesid:sesid
    }
    blockUI(()=> {
        qryData('payroll', 'updateMiscellaneous', data, (data) => {
            genMiscellaneousPayments();
            $(modal).modal('hide');
            $.unblockUI(); 
        });
    });
}

function loadSaveAction_add(){
    const modal = '#addmiscellaneous';
    $(modal).find('#saveBtn')
        .off('click')
        .on('click', function(e) {
            if($(modal).find('#eename option:selected').val() === ''){alertDialog('Please select a name');return;}
            if($(modal).find('#misctype option:selected').val() === ''){alertDialog('Please select a miscellaneous type');return;}
            if($(modal).find('#miscdesc').val() === ''){alertDialog('Please enter description');return;}
            if($(modal).find('#miscAmount').val() == '' ){alertDialog('Please enter the amount');return;}
            if($(modal).find('#txtpaydate').val() === ''){alertDialog('Please select a date');return;}
            
            // proceed saving
            addNewMiscellaneous();
        });
}

function loadSaveAction_update(){
    const modal = '#addmiscellaneous';
    $(modal).find('#saveBtn')
        .off('click')
        .on('click', function(e) {
            if($(modal).find('#eename option:selected').val() === ''){alertDialog('Please select a name');return;}
            if($(modal).find('#misctype option:selected').val() === ''){alertDialog('Please select a miscellaneous type');return;}
            if($(modal).find('#miscdesc').val() === ''){alertDialog('Please enter description');return;}
            if($(modal).find('#miscAmount').val() == '' ){alertDialog('Please enter the amount');return;}
            if($(modal).find('#txtpaydate').val() === ''){alertDialog('Please select a date');return;}
            
            // proceed saving
            updateMiscellaneous();
        });
}

function genMiscellaneousPayments(){
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    const data = {
        sesid:sesid
    }
    qryData('payroll', 'getActiveMiscellaneous', data, data => {
        const miscellaneouslist = data.miscellaneouslist.rows;
        const datatableid = '#miscviewtbl';
        // console.log(miscellaneouslist);
        
        if ($.fn.DataTable.isDataTable(datatableid)) {
            $(datatableid).dataTable().fnClearTable();
            if (miscellaneouslist.length > 0) {
                $(datatableid).dataTable().fnAddData(miscellaneouslist);
            }
        } else {
            summarygovdeductionslist = $(datatableid).DataTable({
                "dom": '<"pull-right" f><t>',
                "searching": false,
                data: miscellaneouslist,
                language: {
                    emptyTable: '<center>No record</center>'
                },
                paging: false,
                responsive: true,
                ordering: true,
                order: [[0, 'asc']],
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                columns: [
                    { data: 'eename' },
                    { data: 'misdesc' },
                    { data: 'description' },
                    { data: 'miscamount' }
                ],
                columnDefs:[
                    {
                        targets: [3], render: function (data) {
                            return ccyFormat(data,2);
                        },className:'text-right'
                        
                    }
                ],
                        
                fnDrawCallback:function(){
                    let api = this.api();
                    let ttlmiscamount = api.column(3).data().sum()
                    $( api.table().column(3).footer() ).html(ccyFormat(ttlmiscamount,2));
                    // console.log(ttlsalary);
                },

                rowId: 'id',
            });

            $(datatableid + ' tbody').on('click', 'tr', function (evt) {
                const dataTable = $(datatableid).DataTable();
                if ($(evt.currentTarget.childNodes[0]).hasClass('dataTables_empty')) return false;
                const rowdata = dataTable.row(this).data();

                let miscid = rowdata.miscid;
                if(miscid != null && miscid != '')
                    loadSaveAction_update();
                    loadMiscellaneousForm(rowdata);
            });
        }

        if ($(datatableid + ' tbody tr td').hasClass('dataTables_empty')) {
            $(datatableid + ' tbody').css('cursor', 'no-drop');
        } else {
            $(datatableid + ' tbody').css('cursor', 'pointer');
        }
    });
}

function loadMiscellaneousForm(data){
    const modal = '#addmiscellaneous';
    $(modal).find('#miscmodaltitle').html('Update Miscellaneous');
    $(modal).find('#miscid').val(data.miscid);
    $(modal).find('#eename').val(data.userid);
    $(modal).find('#misctype').val(data.misctype);
    $(modal).find('#miscdesc').val(data.description);
    $(modal).find('#miscAmount').val(ccyFormat(data.miscamount));
    $(modal).find('#txtPeriodNo').val(data.periodno);
    $(modal).find('#txtpaydate').val(moment(new Date(data.paydate)).format('ddd D MMM YYYY'));
    $(modal).modal('show');
}

function clearMiscellaneousForm(){
    const modal = '#addmiscellaneous';
    $(modal).find('#miscmodaltitle').html('Add Miscellaneous');
    $(modal).find('#miscid').val('');
    $(modal).find('#eename').val('');
    $(modal).find('#misctype').val('');
    $(modal).find('#miscdesc').val('');
    $(modal).find('#miscAmount').val('');
    $(modal).find('#txtPeriodNo').val('');
    $(modal).find('#txtpaydate').val('');
}

function addNewSalaryAdjusment() {
    const modal = '#addsalaryadjustment';
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    const data = {
        curuserid: $(modal).find('#eename option:selected').val(),
        saladjtype: $(modal).find('#saladjtype option:selected').val(),
        saladjdesc: $(modal).find('#saladjdesc').val(),
        saladjamount: $(modal).find('#saladjAmount').val(),
        saladjdeminimisamount: $(modal).find('#saladjDeminimisAmount').val(),
        periodno: $(modal).find('#txtPeriodNo option:selected').val(),
        paydate: $(modal).find('#txtpaydate').val(),
        userid: $('#userid').val(),
        sesid:sesid
    }
    blockUI(()=> {
        qryData('payroll', 'addNewSalaryAdjusment', data, (data) => {
            loadActiveSalaryAdjusment();
            $(modal).modal('hide');
            $.unblockUI(); 
        });
    });
}

function updateSalaryAdjustment() {
    const modal = '#addsalaryadjustment';
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    const data = {
        saladjid: $(modal).find('#saladjid').val(),
        curuserid: $(modal).find('#eename option:selected').val(),
        saladjtype: $(modal).find('#saladjtype option:selected').val(),
        saladjdesc: $(modal).find('#saladjdesc').val(),
        saladjamount: $(modal).find('#saladjAmount').val(),
        saladjdeminimisamount: $(modal).find('#saladjDeminimisAmount').val(),
        periodno: $(modal).find('#txtPeriodNo option:selected').val(),
        paydate: $(modal).find('#txtpaydate').val(),
        userid: $('#userid').val(),
        sesid:sesid
    }
    blockUI(()=> {
        qryData('payroll', 'updateSalaryAdjustment', data, (data) => {
            console.log(data);
            loadActiveSalaryAdjusment();
            $(modal).modal('hide');
            $.unblockUI(); 
        });
    });
}

function loadSalAdjSaveAction_add(){
    const modal = '#addsalaryadjustment';
    $(modal).find('#saveBtn')
        .off('click')
        .on('click', function(e) {
            if($(modal).find('#eename option:selected').val() === ''){alertDialog('Please select a name');return;}
            if($(modal).find('#saladjtype option:selected').val() === ''){alertDialog('Please select a miscellaneous type');return;}
            if($(modal).find('#saladjdesc').val() === ''){alertDialog('Please enter description');return;}
            if($(modal).find('#saladjAmount').val() == '' ){alertDialog('Please enter the amount');return;}
            if($(modal).find('#saladjDeminimisAmount').val() == '' ){alertDialog('Please enter the amount');return;}
            if($(modal).find('#txtpaydate').val() === ''){alertDialog('Please select a date');return;}
            // proceed saving
            addNewSalaryAdjusment();
        });
}

function loadSalAdjSaveAction_update(){
    const modal = '#addsalaryadjustment';
    $(modal).find('#saveBtn')
        .off('click')
        .on('click', function(e) {
            if($(modal).find('#eename option:selected').val() === ''){alertDialog('Please select a name');return;}
            if($(modal).find('#saladjtype option:selected').val() === ''){alertDialog('Please select a miscellaneous type');return;}
            if($(modal).find('#saladjdesc').val() === ''){alertDialog('Please enter description');return;}
            if($(modal).find('#saladjAmount').val() == '' ){alertDialog('Please enter the amount');return;}
            if($(modal).find('#saladjDeminimisAmount').val() == '' ){alertDialog('Please enter the amount');return;}
            if($(modal).find('#txtpaydate').val() === ''){alertDialog('Please select a date');return;}
            // proceed saving
            updateSalaryAdjustment();
        });
}

function loadSalaryAdjustmentForm(data){
    const modal = '#addsalaryadjustment';
    $(modal).find('#saladmodaltitle').html('Update Salary Adjustment');
    $(modal).find('#saladjid').val(data.salaryadjid);
    $(modal).find('#eename').val(data.userid);
    $(modal).find('#saladjtype').val(data.salaryadjusmenttype);
    $(modal).find('#saladjdesc').val(data.description);
    $(modal).find('#saladjAmount').val(ccyFormat(data.basicsalaryadjustmentamount));
    $(modal).find('#saladjDeminimisAmount').val(ccyFormat(data.deminimisadjamount));
    $(modal).find('#txtPeriodNo').val(data.periodno);
    $(modal).find('#txtpaydate').val(moment(new Date(data.paydate)).format('ddd D MMM YYYY'));
    $(modal).modal('show');
}

function clearSalaryAdjustmentForm(){
    const modal = '#addsalaryadjustment';
    $(modal).find('#saladmodaltitle').html('Add Salary Adjustment');
    $(modal).find('#saladjid').val('');
    $(modal).find('#eename').val('');
    $(modal).find('#saladjtype').val('');
    $(modal).find('#saladjdesc').val('');
    $(modal).find('#saladjAmount').val(ccyFormat(''));
    $(modal).find('#saladjDeminimisAmount').val(ccyFormat(''));
    $(modal).find('#txtPeriodNo').val('');
    $(modal).find('#txtpaydate').val('');
}

function loadLoanDataTable(apiParam = {}) {
    if($.isEmptyObject(apiParam)){
        apiParam = {
            ofc: '',
        }
    }
    qryData('payroll', 'loadEELoans', apiParam, data => {
        const eewithloans = data.eewithloans.rows;
        let pendingloans = 0;
        if(eewithloans.length>0){
            pendingloans  = data.pendingloan.rows;
        }else{
            pendingloans  = [];
        }
       
        const datatableid = '#loansviewtbl';
        // console.log(eewithloans);
        
        if ($.fn.DataTable.isDataTable(datatableid)) {
            $(datatableid).dataTable().fnClearTable();
            if (eewithloans.length > 0) {
                $(datatableid).dataTable().fnAddData(pendingloans);
            }
        } else {
            loanpaymentslist = $(datatableid).DataTable({
                "dom": '<"pull-right" f><t>',
                "searching": false,
                data: pendingloans,
                language: {
                    emptyTable: '<center>No record</center>'
                },
                paging: false,
                responsive: true,
                ordering: true,
                order: [[0, 'asc']],
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                columns: [
                    { data: 'eename' },
                    { data: 'loantype' },
                    { data: 'newduedate' },
                    { data: 'runningbalance' },
                    { data: 'amountdue' }
                ],
                columnDefs:[
                    {
                        targets: [3, 4], render: function (data) {
                            return ccyFormat(data,2);
                        },className:'text-right'
                        
                    }
                ],
                        
                fnDrawCallback:function(){
                    let api = this.api();
                    let ttlloanamount = api.column(4).data().sum()
                    $( api.table().column(4).footer() ).html(ccyFormat(ttlloanamount,2));
                    // console.log(ttlsalary);
                },

                rowId: 'id',
            });
        }

        if ($(datatableid + ' tbody tr td').hasClass('dataTables_empty')) {
            $(datatableid + ' tbody').css('cursor', 'no-drop');
        } else {
            $(datatableid + ' tbody').css('cursor', 'pointer');
        }

    });
}

function genReviewPayroll(){
    var params = new window.URLSearchParams(window.location.search);
    var sesid = params.get('id');
    var office = atob(params.get('office'));
    const data = {
        sesid:sesid,
        office:office
    }
    qryData('payroll', 'getAllCurrentPayrollDetails', data, data => {
        const datatableid = '#reviewpayrollviewtbl';
        const summarydata = data['allcurrentpayrolldetails']['rows'];
        let payrollmaster = data['payrollmaster']['rows'];
        let periodno = payrollmaster[0]['periodno'];
        let targetcol = 0;
        let visibleoption = 0;
    
        if(periodno == 1){
            targetcol = [4, 5];
            visibleoption = 0;
        }else if(periodno == 2){
            targetcol = 3;
            visibleoption = 0;
        }
        // console.log(targetcol +','+ visibleoption +' '+ periodno);
        // console.log(summarydata);
        // return false;
        if ($.fn.DataTable.isDataTable(datatableid)) {
            $(datatableid).dataTable().fnClearTable();
            if (summarydata.length > 0) {
                $(datatableid).dataTable().fnAddData(summarydata);
            }
        } else {
            summarygovdeductionslist = $(datatableid).DataTable({
                "dom": '<"pull-right" f><t>',
                "searching": false,
                data: summarydata,
                language: {
                    emptyTable: '<center>No record</center>'
                },
                paging: false,
                responsive: true,
                ordering: true,
                order: [[0, 'asc']],
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                columns: [
                    { data: 'eename' },
                    // {
                    //     data: function (data, type, dataToSet) {
                    //         let ttlgrossalary = 0;
                    //         ttlgrossalary = parseInt(data.basicpay) + parseInt(data.deminimis);
                    //         return ttlgrossalary;
                    //     }
                    // },
                    { data: 'basicpay'},
                    { data: 'deminimis'},
                    { 
                        data: function (data, type, dataToSet) {
                            let ttlSalaryAdjustment = 0;
                            ttlSalaryAdjustment = parseInt(data.salaryadjusmentamount) + parseInt(data.deminimisadjustmentamount);
                            return ttlSalaryAdjustment;
                        }
                    },
                    { data: 'ssscontributionamount' },
                    { data: 'pagibigcontributionamount' },
                    { data: 'philhealthcontributionamount' },
                    { data: 'lates' },
                    { data: 'absence' },
                    { data: 'totaltaxdue' },
                    { 
                        data: function (data, type, dataToSet) {
                        let ttlloans = 0;
                        ttlloans = parseInt(data.sssloandeduction) + parseInt(data.pagibigloandeduction) + parseInt(data.companyloan)
                        return ttlloans ;
                        }
                    },
                    { data: 'miscellaneousamount' },
                    { data: 'netpayable' }
                ],
                columnDefs:[
                    {
                        targets: [1,2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], render: function (data) {
                            return ccyFormat(data,2);
                        },className:'text-right'
                        
                    },
                    { targets: targetcol, visible: visibleoption }
                ],
                        
                fnDrawCallback:function(){
                    let api = this.api();
                    let ttlnet = api.column(12).data().sum()
                    $( api.table().column(12).footer() ).html(ccyFormat(ttlnet,2));
                    // console.log(ttlsalary);
                },

                rowId: 'id',
            });
        }
    }, true);
}

function loadPayrollMasterList(apiParam={}){
    if($.isEmptyObject(apiParam)){
        apiParam = {
            office: '',
        }
    }
    // console.log(apiParam);
    // return false;
    blockUI(()=> {
        loadOfcList();
        qryData('payroll', 'loadPayrollMasterList', apiParam, (data) => {
            const datatableid = '#payrollhistorytblview';
            let payrollmasterlist = data['allpayrollmasterlist']['rows'];
            
            // console.log(payrollmasterlist);
            if ($.fn.DataTable.isDataTable(datatableid)) {
                $(datatableid).dataTable().fnClearTable();
                if (payrollmasterlist.length > 0) {
                    $(datatableid).dataTable().fnAddData(payrollmasterlist);
                }
            } else {
                payrollmasterlist = $(datatableid).DataTable({
                    "dom": '<"pull-right" f><t>',
                    "searching": false,
                    data: payrollmasterlist,
                    language: {
                        emptyTable: '<center>No record</center>'
                    },
                    paging: false,
                    responsive: true,
                    ordering: true,
                    order: [[0, 'asc']],
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                    columns: [
                        { 
                            data: function (data, type, dataToSet) {
                                let payperiodfrom = moment(new Date(data.payperiodfrom)).format('ddd D MMM YYYY');
                                let payperiodto = moment(new Date(data.payperiodto)).format('ddd D MMM YYYY');
                                const payperiod = payperiodfrom + ' to ' + payperiodto;
                                return payperiod;
                            },
                            name: 'payperiod', title:'Pay Period', width:'30%'
                        },
                        // { data : 'payperiodfrom', name: 'payperiodfrom', title: 'Payroll Period', width:"30%" },
                        { data : 'paydate', name: 'paydate', title: 'Pay Date', width:'30%' },
                        { data : 'payrollmstid', name: 'description', title: 'Description', width:'30%' },
                        { data : 'reviewedby1', name: 'status', title: 'Status', width:'10%' }
                    ],
                    columnDefs: [
                        {
                            targets: [1], render: function (data) {
								return '<span style="display:none;">' + toTimestamp(data) + '</span>' + moment(new Date(data)).format('ddd D MMM YYYY');
							}
                        },
                    ],
                    rowId: 'id',
                });
            }
            
        }, true);
        $.unblockUI(); 
    });
}

function loadOfcList(){
    qryData('payroll', 'getOfc', {}, data => {
        const ofclist = data.ofclist;
        let ofchtml = '<option value="" selected>All offices</option>';
        ofclist.forEach(ofc => {
            ofchtml += '<option value="'+ ofc.salesofficeid +'">' + ofc.description + '</option>';
        });
        $('#officeList')
            .html(ofchtml)
            .off('change')
            .on('change',function(){
                const ofc = $(this).val();
                blockUI(()=> {
                    loadPayrollMasterList({ofc: ofc});
                    $.unblockUI();
                });
            });
    });
}

function payrollCheckpoint(){
    let params = new window.URLSearchParams(window.location.search);
    let ofc = atob(params.get('office'));
    let sesid = params.get('id');
    var errmsg = '';
    var isPassed = 1;
    const data = {
        userid: $('#userid').val(),
        sesid:sesid,
        office: ofc
    }
    qryData('payroll', 'payrollCheckpoint', data, (data) => {
        let userid = $("#userid").val();
        let payrollmaster = data['payrollmaster']['rows'][0];
        let payrollsettings = data['payrollsettings']['rows'][0];
        

        //people assigned to review, validate and approve
        let reviewer1 = payrollsettings['reviewer1'];
        let reviewer2 = payrollsettings['reviewer2'];
        let validator1 = payrollsettings['validator1'];
        let validator2 = payrollsettings['validator2'];
        let approver = payrollsettings['approver'];

        //validation process
        if(reviewer1 != userid){

        } 
        isPassed = 0;
        errmsg = "Mali ka";
        return {isPassed, errmsg};
    },true);

    
}

function updatePayrollMaster(){

    const data = {
        // userid: $('#userid').val(),
        // sesid:sesid
    }
    qryData('payroll', 'payrollCheckpoint', data, (data) => {
    }, true);
}

