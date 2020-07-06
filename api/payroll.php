<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/dropdowns_model.php');
	require_once('models/employees_model.php');
	require_once('models/attendance_model.php');
	require_once('models/sendmail_model.php');
	require_once('models/salesoffices_model.php');
	require_once('models/notifiedpersons_model.php');
	require_once('models/callin_model.php');
	require_once('models/payroll_model.php');
	require_once('models/employeesalary_model.php');
	require_once('models/governmentdeductions_model.php');
	require_once('models/birwithholdingtax_model.php');
	require_once('models/miscellaneous_model.php');
	require_once('models/salaryadjusments_model.php');
	require_once('models/loan_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function checkPeriodIfExist($data){
		$res = array();
		$val = array();
		
		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['logfm'] = $data->logfrom;
		$val['logto'] = $data->logto;
		$ofc = $data->ofc;

		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();

			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}

		$payrollifexist = new PayrollModel;
		$res['payrollifexist'] = $payrollifexist->checkPeriodIfExist($val);
 		$payrollifexist->closeDB();

		return $res;
	}

	function getEeList($data){
		$res = array();
		$val = array();

		$ofcid = $data->ofcid;
		$val['ofcid'] = $ofcid;
		$val['ofcname'] = '';

		if(!empty($ofcid)){
			$getofc = new SalesOfficesModel;
			$ofc = $getofc->getSalesOfficeByOfcId($ofcid);
			$getofc->closeDB();
			if(count($ofc) > 0) $val['ofcname'] = $ofc['rows'][0]['description'];
		}

		$getee = new EmployeesModel;
		$eelist = $getee->getAllAbaPeopleByOffice($val);
		$getee->closeDB();

		// return only name and userid
		foreach ($eelist['rows'] as $key => $ee) {
			$res['eelist'][$key]['userid'] = str_replace('  ', ' ', $ee['userid']);
			$res['eelist'][$key]['eename'] = str_replace('  ', ' ', $ee['eename']);
		}

		return $res;
	}

	function loadMiscellaneous(){
		$res = array();
		$id = "";

		$dd = new DropdownsModel;
		$res['misctypelist'] = $dd->getMiscellaneous($id);
		$dd->closeDB();

		return $res;
	}

	function loadSalaryAdjustmenType(){
		$res = array();
		$id = "";

		$dd = new DropdownsModel;
		$res['saladjtypelist'] = $dd->getSalaryAdjustmentType($id);
		$dd->closeDB();

		return $res;
	}

	function getPayrollMaster($data){
		$res = array();
		$val = array(); 
		$sesid = $data->sesid;

		$payrollmodel = new PayrollModel;
		$res['getpayrollmaster'] = $payrollmodel->getMasterPayroll($sesid);
		$payrollmodel->closeDB();

		return $res;
	}

	function loadPayrollSettings($data){
		$res = array();
		$val = array();

		$ofc = $data->office;
		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			

			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}
		$payrollmodel = new PayrollModel;
		$res['payrollsettings'] = $payrollmodel->loadPayrollSettings($val);

		//close db cn
		$soffices->closeDB();
 		$payrollmodel->closeDB();

		 return $res;
	}

	function getAttendanceforPayroll($data){
		$res = array();
		$val = array();

		$val['logfm'] = $data->logfm;
		$val['logto'] = $data->logto;
		$ofc = $data->office;

		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];

			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}

		$attendancemodel = new AttendanceModel;
		$res['summaryattendance'] = $attendancemodel->getAttendanceSummary($val);
		$res['attendancerecord'] = $attendancemodel->getAttendanceRecord($val);

		//close db cn
		$soffices->closeDB();
 		$attendancemodel->closeDB();

		return $res;
	}

	function addPayrollMaster($data){
		$res = array();
		$val = array();
		$payrollprocessstatus = array();
		$curyear = $data->curyear;
		$curmonth = $data->curmonth;
		$ofc = $data->office;
		$userid = $data->userid;
		$period = $data->period;
		$val['logfm'] = $data->logfm;
		$val['logto'] = $data->logto;
		$val['userid'] = $userid;
		$val['period'] = $period;

		//get salesoffice
		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();

			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}

		//get attendance record and summary
		$attendancemodel = new AttendanceModel;
		$summaryattendance = $attendancemodel->getAttendanceSummary($val);
		$res['attendancerecord'] = $attendancemodel->getAttendanceRecord($val);

		//add payroll master and payroll details
		$payrollmodel = new PayrollModel;
		$payrollsettings = $payrollmodel->loadPayrollSettings($val);
		switch($period){
			case 1:
				$ifsemifirstpayday = $payrollsettings['rows'][0]['ifsemifirstpayday'];
				$val['paydate'] = strftime("%F", strtotime($curyear."-".$curmonth."-".$ifsemifirstpayday));
				break;
			case 2:
				$ifsemisecpayday = $payrollsettings['rows'][0]['ifsemisecpayday'];
				$val['paydate'] = strftime("%F", strtotime($curyear."-".$curmonth."-".$ifsemisecpayday));
				break;
		}
		
		$payrollmstid = $payrollmodel->genPayrollMstID($val);
		$sesid = genuri($payrollmstid);
		$val['payrollmstid'] = $payrollmstid;
		$val['sesid'] = $sesid;
		$val['frequency'] = $payrollsettings['rows'][0]['frequency'];
		$payrollprocessstatus['ifaddpayrollmsterr'] = $payrollmodel->addPayrollMaster($val);
		$payrollprocessstatus['ifaddpayrolldtlserr'] = $payrollmodel->addPayrollDetails($val,$summaryattendance);
		$res['payrollprocessstatus'] = $payrollprocessstatus;

		$res['sesid'] = $sesid;
		//close db cn
		$attendancemodel->closeDB();
		$payrollmodel->closeDB();

		return $res;
	}

	function loadEmployeeSalaryRecord($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$ofc = $data->office;
		$val['userid'] = $userid;
		$sesid = $data->sesid;
		//get salesoffice
		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();

			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}

		$payrollmodel = new PayrollModel;
		$res['getpayrollmaster'] = $payrollmodel->getMasterPayroll($sesid);
		$res['payrollsettings'] = $payrollmodel->loadPayrollSettings($val);
		$val['frequency'] = $res['payrollsettings']['rows'][0]['frequency'];
 		$payrollmodel->closeDB();

		$employeesalarymodel = new EmployeeSalaryModel;
		$res['employeesalaryrecord'] = $employeesalarymodel->loadEmployeeSalaryRecord($val);
		$employeesalarymodel->closeDB();
		 
		return $res;
	}

	function updatePayrollDetails($data){
		$res = array();
		$val = array();
		$dataarray = array();

		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['category'] = $data->category;
		$ofc = $data->office;
		$sesid = $data->sesid;
		$val['logfm'] = $data->logfm;
		$val['logto'] = $data->logto;

		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();
			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}

		$payrollmodel = new PayrollModel;
		
		$payrollsettings = $payrollmodel->loadPayrollSettings($val);
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmstid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$val['periodno'] = $getpayrollmaster['rows'][0]['periodno'];
		$val['frequency'] = $payrollsettings['rows'][0]['frequency'];
		$val['paydate'] = $getpayrollmaster['rows'][0]['paydate'];
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];

		$employeesalarymodel = new EmployeeSalaryModel;
		$governmentdeductionsmodel = new GovernmentDedcutionsModel;
		$salaryadjmodel = new SalaryAdjustmentsModel;
		$miscellaneousmodel = new MiscellaneousModel;
		
		

		switch ($val['category']) {
			case 'grosssalary':
				$dataarray = $employeesalarymodel->loadEmployeeSalaryRecord($val);
				break;
			case 'salaryadjustment':
				$dataarray = $payrollmodel->calculatePayrollQuery($val);
				break;
			case 'govdeductions':
				$dataarray = $governmentdeductionsmodel->loadGovernmentDeductions($val);
				break;
			case 'taxableincome':
				$dataarray = $payrollmodel->calculatePayrollQuery($val);
				break;
			case 'miscellaneous':
				$dataarray = $payrollmodel->calculatePayrollQuery($val);
				break;
			default:
			$dataarray = [];
				break;
		}
		
		$res['ifupdtpayrolldtlserr'] = $payrollmodel->updatePayrollDetails($val,$dataarray);

		$employeesalarymodel->closeDB();
		$governmentdeductionsmodel->closeDB();
		$miscellaneousmodel->closeDB();
		$salaryadjmodel->closeDB();
		$payrollmodel->closeDB();
		
		return $res;
	}


	function calculatePayrollQuery($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['logfm'] = $data->logfm;
		$val['logto'] = $data->logto;
		$ofc = $data->office;
		$sesid = $data->sesid;

		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();
			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}
		
		$payrollmodel = new PayrollModel;
		$payrollsettings = $payrollmodel->loadPayrollSettings($val);
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['periodno'] = $getpayrollmaster['rows'][0]['periodno'];// $val['periodno'] = 2;
		$val['frequency'] = $payrollsettings['rows'][0]['frequency'];
		$val['paydate'] = $getpayrollmaster['rows'][0]['paydate'];
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$res['payrolcalulationres'] = $payrollmodel->calculatePayrollQuery($val);
		$res['payrollmaster'] = $getpayrollmaster;
		$payrollmodel->closeDB();

		return $res;
	}

	function addNewMiscellaneous($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$misctype = $data->misctype;
		$miscdesc = $data->miscdesc;
		$miscamount = $data->miscamount;
		$periodno = $data->periodno;
		$paydate = $data->paydate;
		$curuserid = $data->curuserid;
		$sesid = $data->sesid;

		$val['userid'] = $userid;
		$val['misctype'] = $misctype;
		$val['miscdesc'] = $miscdesc;
		$val['miscamount'] = $miscamount;
		$val['periodno'] = $periodno;
		$val['paydate'] = $paydate;
		$val['curuserid'] = $curuserid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$payrollmodel->closeDB();

		$miscellaneousmodel = new MiscellaneousModel;
		$val['miscid'] = $miscellaneousmodel->genMiscID($curuserid);
		$res['ifaddmiscerr'] = $miscellaneousmodel->addNewMiscellaneous($val);
		$miscellaneousmodel->closeDB();

		return $res;
	}

	function updateMiscellaneous($data){
		$res = array();
		$val = array();

		$miscid = $data->miscid;
		$userid = $data->userid;
		$misctype = $data->misctype;
		$miscdesc = $data->miscdesc;
		$miscamount = $data->miscamount;
		$periodno = $data->periodno;
		$paydate = $data->paydate;
		$curuserid = $data->curuserid;
		$sesid = $data->sesid;

		$val['miscid'] = $miscid;
		$val['userid'] = $userid;
		$val['misctype'] = $misctype;
		$val['miscdesc'] = $miscdesc;
		$val['miscamount'] = $miscamount;
		$val['periodno'] = $periodno;
		$val['paydate'] = $paydate;
		$val['curuserid'] = $curuserid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$payrollmodel->closeDB();

		$miscellaneousmodel = new MiscellaneousModel;
		$res['ifupdtmiscerr'] = $miscellaneousmodel->updateMiscellaneous($val);
		$miscellaneousmodel->closeDB();

		return $res;
	}

	function getActiveMiscellaneous($data){
		$res = array();
		$val = array();

		$sesid = $data->sesid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$payrollmodel->closeDB();

		$miscellaneousmodel = new MiscellaneousModel;
		$res['miscellaneouslist'] = $miscellaneousmodel->getActiveMiscellaneous($val);
		$miscellaneousmodel->closeDB();

		return $res;
	}

	function getActiveSalaryAdjusments($data){
		$res = array();
		$val = array();

		$sesid = $data->sesid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$payrollmodel->closeDB();

		$salaryadjmodel = new SalaryAdjustmentsModel;
		$res['salaryadlist'] = $salaryadjmodel->getActiveSalaryAdjusments($val);
		$salaryadjmodel->closeDB();

		return $res;
	}

	function addNewSalaryAdjusment($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$saladjtype = $data->saladjtype;
		$saladjdesc = $data->saladjdesc;
		$saladjamount = $data->saladjamount;
		$saladjdeminimisamount = $data->saladjdeminimisamount;
		$periodno = $data->periodno;
		$paydate = $data->paydate;
		$curuserid = $data->curuserid;
		$sesid = $data->sesid;

		$val['userid'] = $userid;
		$val['saladjtype'] = $saladjtype;
		$val['saladjdesc'] = $saladjdesc;
		$val['saladjamount'] = $saladjamount;
		$val['saladjdeminimisamount'] = $saladjdeminimisamount;
		$val['periodno'] = $periodno;
		$val['paydate'] = $paydate;
		$val['curuserid'] = $curuserid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$payrollmodel->closeDB();

		$saladjmodel = new SalaryAdjustmentsModel;
		$val['saladjid'] = $saladjmodel->genSalaryAdjusmentID($curuserid);
		$res['ifaddsaladjcerr'] = $saladjmodel->addNewSalaryAdjusment($val);
		$saladjmodel->closeDB();

		return $res;
	}

	function updateSalaryAdjustment($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$saladjtype = $data->saladjtype;
		$saladjdesc = $data->saladjdesc;
		$saladjamount = $data->saladjamount;
		$saladjdeminimisamount = $data->saladjdeminimisamount;
		$periodno = $data->periodno;
		$paydate = $data->paydate;
		$curuserid = $data->curuserid;
		$sesid = $data->sesid;
		$saladjid = $data->saladjid;

		$val['userid'] = $userid;
		$val['saladjtype'] = $saladjtype;
		$val['saladjdesc'] = $saladjdesc;
		$val['saladjamount'] = $saladjamount;
		$val['saladjdeminimisamount'] = $saladjdeminimisamount;
		$val['periodno'] = $periodno;
		$val['paydate'] = $paydate;
		$val['curuserid'] = $curuserid;
		$val['saladjid'] = $saladjid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$payrollmodel->closeDB();

		$saladjmodel = new SalaryAdjustmentsModel;
		$res['ifaddsaladjcerr'] = $saladjmodel->updateSalaryAdjustment($val);
		$saladjmodel->closeDB();

		return $res;
	}

	function loadEELoans($data){
		$res = array();
		$val = array();

		$ofc = $data->ofc;
		$incofsids = array();
		// get id of inlcuded ofcs
		if($ofc != ''){
			$listofincofcs = array();
			$soffices = new SalesOfficesModel;
			$ofcs = $soffices->getSalesOfficeByOfcId($ofc);
			$listofincofcs = explode(' ',$ofcs['incofcs']);
			foreach ($listofincofcs as $key => $incofc) {
				$incofsid_tmp = $soffices->getSalesOfficeByDesc($incofc)['rows'];
				if(count($incofsid_tmp) > 0){
					$incofsids[] = $incofsid_tmp[0]['salesofficeid'];
				}
			}
			$soffices->closeDB();
		}
		$val['ofc'] = $incofsids;
		unset($incofsids);

		$loanmodel = new LoanModel;
		$res['eewithloans'] = $loanmodel->getEeWithLoan($val);
		$loancount = count($res['eewithloans']['rows']);
		$loanmst = $res['eewithloans']['rows'];
		$rowcount = 0;
		if($loancount>0){
			for ($i=0; $i < $loancount; $i++) { 
				$loanidmst = $loanmst[$i]['loanidmst'];
				$res['loanofee'] = $loanmodel->getLoanOfEE($loanidmst);
				if(count($res['loanofee']['rows']) == 0) {
					// construct new if empty
					$res['empty'] = 'empty';
					$rowcount++;
					$tmploanid = $loanidmst . '_0'.$rowcount;
		
					$duedate = getPeriod($loanmst[$i]['startdate'], $loanmst[$i]['paymentfrequency']);
		
					// construct from an empty array
					$emptyloandtls['id'] = $i;
					$emptyloandtls['loanid'] = $tmploanid;
					$emptyloandtls['loanidmst'] = $loanidmst;
					$emptyloandtls['userid'] = $loanmst[$i]['userid'];
					$emptyloandtls['eename'] = $loanmst[$i]['eename'];
					$emptyloandtls['loantype'] = $loanmst[$i]['loantypename'];
					$emptyloandtls['loanamount'] = $loanmst[$i]['loanamount'];
					$emptyloandtls['runningbalance'] = $loanmst[$i]['loanamount'];
					$emptyloandtls['amountdue'] = (float)$loanmst[$i]['amountdue'];
					$emptyloandtls['amountpaid'] = 0;
					$emptyloandtls['newduedate'] = formatDate('D d M Y',$duedate);
					$emptyloandtls['state'] = "current";
					$emptyloandtls['status'] = 1;
					$emptyloandtls['ispaid'] = 0;
					$emptyloandtls['actions'] = array(
						'id'=>0,
						'ispaid'=>0,
						'loanid'=>$tmploanid,
						'state'=>"current"
					);
					$res['pendingloan']['rows'][] = $emptyloandtls;
				}
				else {
					$lastloanpay = $loanmodel->lastLoanPay($loanidmst);
					$lastloanpaydtls = $lastloanpay['rows'][0];
					$res['lastloanpay'][] = $lastloanpay['rows'][0];

					if((float)$lastloanpaydtls['amountdue'] > (float)$lastloanpaydtls['totalpaid']){
						// check if previous payment is full
						$res['empty'] = 'due>paid';
					} 				
					else {
						$res['empty'] = 'fully paid';
						$getprevindex = explode('_',$lastloanpaydtls['loanid']);
						$previdindex = (int)($getprevindex[count($getprevindex)-1]) + 1;
						// unset($getprevindex); // clean
		
						$newrunningbalance = $lastloanpaydtls['runningbalance'] - $lastloanpaydtls['totalpaid'];
						if((float)$newrunningbalance > 0) {
							$newindex = $previdindex < 10 ? '0' . $previdindex : $previdindex;
						 	// unset($previdindex); // clean
			
							$tmploanid = $loanidmst . '_' . $newindex;
			
							$duedate = getPeriod($lastloanpaydtls['duedate'], $loanmst[$i]['paymentfrequency']);
			
							// construct from an empty array
							$emptyloandtls['id'] = $i;
							$emptyloandtls['loanid'] = $tmploanid;
							$emptyloandtls['loanidmst'] = $loanidmst;
							$emptyloandtls['userid'] = $loanmst[$i]['userid'];
							$emptyloandtls['eename'] = $loanmst[$i]['eename'];
							$emptyloandtls['loantype'] = $loanmst[$i]['loantypename'];
							$emptyloandtls['loanamount'] = $loanmst[$i]['loanamount'];
							$emptyloandtls['runningbalance'] = $newrunningbalance;
							$emptyloandtls['amountdue'] = (float) $loanmst[$i]['amountdue'] - ($loanmst[$i]['amountdue'] - $lastloanpaydtls['amountdue']) - ($lastloanpaydtls['totalpaid'] - $loanmst[$i]['amountdue']);
							$emptyloandtls['amountpaid'] = 0;
							$emptyloandtls['newduedate'] = formatDate('D d M Y',$duedate);
							$emptyloandtls['state'] = "current";
							$emptyloandtls['status'] = 1;
							$emptyloandtls['ispaid'] = '0';
							$emptyloandtls['actions'] = array(
								'id'=>0,
								'ispaid'=>0,
								'loanid'=>$tmploanid,
								'state'=>"current"
							);
							$res['pendingloan']['rows'][] = $emptyloandtls;
						}
					}
				}
			}
		}
		
		$loanmodel ->closeDB();

		return $res;
	}

	#region local functions
	function getPeriod($date, $freq = ''){
		$duedate = '';
		switch ($freq) {
			case 'm':
				$tmpdate = $date;
				$duedate = $tmpdate;
				if(formatDate('d', $tmpdate) == date("t", strtotime($tmpdate))) {
					$duedate = date("Y-m-", strtotime($tmpdate . ' +1 day')) . '15';
				// } else if(formatDate('d', $tmpdate) >= 15){
				// 	$duedate = date("Y-m-t", strtotime($tmpdate));
				} else {
					$duedate = date("Y-m-d", strtotime($tmpdate . '+1 month'));
				}
				$duedate .= ' 00:00:00';
				break;
			case 'sm':
				$tmpdate = $date;
				$duedate = $tmpdate;
				if(formatDate('d', $tmpdate) == date("t", strtotime($tmpdate))) {
					$duedate = date("Y-m-", strtotime($tmpdate . ' +1 day')) . '15';
				} else if(formatDate('d', $tmpdate) >= 15){
					$duedate = date("Y-m-t", strtotime($tmpdate));
				} else {
					$duedate = date("Y-m-", strtotime($tmpdate)) . '15';
				}
				$duedate .= ' 00:00:00';
				break;
			default:
				# code...
				break;
		}

		return $duedate;
	}

	function updateLoanPaymentsToPayroll($data){
		$res = array();
		$val = array();
		$loanpayments = array();
		
		$loanpayments = $data->loanpayments;
		$sesid = $data->sesid;
		$userid = $data->userid;
		
		$val['loanpayments'] = $loanpayments;
		$val['userid'] = $userid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$res['updateloanpayments'] = $payrollmodel->updateLoanPaymentsToPayroll($val);
		$payrollmodel ->closeDB();

		// $res['loanpayments'] = $loanpayments;
		return $res;
	}

	function updateMiscellaneousToPayroll($data){
		$res = array();
		$val = array();
		$miscpayments = array();

		$sesid = $data->sesid;
		$userid = $data->userid;
		$miscpayments = $data->miscpayments;

		$val['miscpayments'] = $miscpayments;
		$val['userid'] = $userid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$res['updatemiscpayments'] = $payrollmodel->updateMiscellaneousToPayroll($val);
		$payrollmodel ->closeDB();

		return $res;
	}

	function getAllCurrentPayrollDetails($data){
		$res = array();
		$val = array();
		$sesid = $data->sesid;
		$ofc = $data->office;
		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();
			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}
		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$payrollsettings = $payrollmodel->loadPayrollSettings($val);
		$val['frequency'] = $payrollsettings['rows'][0]['frequency'];
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$res['allcurrentpayrolldetails'] = $payrollmodel->getAllCurrentPayrollDetails($val);
		$payrollmodel ->closeDB();
		$res['val'] = $val;
		$res['payrollmaster'] = $getpayrollmaster;
		return $res;
	}

	function loadPayrollMasterList($data){
		$res = array();
		$val = array();

		$ofc = $data->office;
		$incofsids = array();
		if($ofc != ''){
			$listofincofcs = array();
			$soffices = new SalesOfficesModel;
			$ofcs = $soffices->getSalesOfficeByOfcId($ofc);
			$listofincofcs = explode(' ',$ofcs['incofcs']);
			foreach ($listofincofcs as $key => $incofc) {
				$incofsid_tmp = $soffices->getSalesOfficeByDesc($incofc)['rows'];
				if(count($incofsid_tmp) > 0){
					$incofsids[] = $incofsid_tmp[0]['salesofficeid'];
				}
			}
			$soffices->closeDB();
		}
		$val['ofc'] = $incofsids;
		unset($incofsids);

		// $payrollmodel = new PayrollModel;
		// $res['allpayrollmasterlist'] = $payrollmodel->loadPayrollMasterList($val);
		// $payrollmodel ->closeDB();

		return $val;
	}

	function payrollCheckpoint($data){
		$res = array();
		$val = array();
		$res['isPassed'] = 0;
		$res['errmsg'] = '';

		$userid = $data->userid;
		$sesid = $data->sesid;
		$ofc = $data->office;
		if(!empty($ofc)){
			$soffices = new SalesOfficesModel;
			$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			$soffices->closeDB();
			// $res['salesoffice'] = $soffice;
			$val['ofcid'] = $soffice['salesofficeid'];
			$val['ofcname'] = $soffice['description'];
		}
		
		$payrollmodel = new PayrollModel;
		$payrollsettings = $payrollmodel->loadPayrollSettings($val);
		$payrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$payrollmodel ->closeDB();
        
        //people assigned to review, validate and approve
        $reviewer1 = $payrollsettings['rows'][0]['reviewer1'];
        $reviewer2 = $payrollsettings['rows'][0]['reviewer2'];
        $validator1 = $payrollsettings['rows'][0]['validator1'];
        $validator2 = $payrollsettings['rows'][0]['validator2'];
        $approver = $payrollsettings['rows'][0]['approver'];

        //validation process
        if($reviewer1 != $userid){
			
		} 


		$res['payrollsettings'] = $payrollsettings;
		$res['payrollmaster'] = $payrollmaster;

		return $res;
	}

	function getOfc(){
		$res = array();

		$ofclist = new SalesOfficesModel;
		$res['ofclist'] = $ofclist->getSalesOffices();
		$ofclist->closeDB();

		return $res;
	}

	function updateCalculatedNetPayableToPayroll($data){
		$res = array();
		$val = array();

		$sesid = $data->sesid;
		$userid = $data->userid;

		$payrollsummarylist = $data->payrollsummarylist;

		$val['payrollsummarylist'] = $payrollsummarylist;
		$val['userid'] = $userid;

		$payrollmodel = new PayrollModel;
		$getpayrollmaster = $payrollmodel->getMasterPayroll($sesid);
		$val['payrollmasterid'] = $getpayrollmaster['rows'][0]['payrollmstid'];
		$res['updatenetpayable'] = $payrollmodel->updateCalculatedNetPayableToPayroll($val);
		$payrollmodel ->closeDB();

		return $res;
	}
	#endregion

	// required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    echo json_encode($result);
?>