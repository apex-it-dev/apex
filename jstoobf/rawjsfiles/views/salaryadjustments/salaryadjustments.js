$(function () {
    
    $("#saladj-datefrom,#saladj-dateto").datepicker({
		dateFormat: "D d M yy",
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2020"
    });
    
});

function loadSalaryAdjusments(){
    var url = getAPIURL() + 'salaryadjustments.php';
	var f = "getAttendance";
    var userid = $("#userid").val();
    var me = '';
}

function genSalaryAdjusments(){

}

function computeNoOfDays(fm, to, callback) {
	var todt = new Date(to);
	var fmdt = new Date(fm);
	var difference = fmdt > todt ? fmdt - todt : todt - fmdt;
	var diffdays = (Math.floor(difference / (1000 * 3600 * 24)));
	$("#txtNoOfDays").val(diffdays);
	if (callback != undefined) callback();
}
