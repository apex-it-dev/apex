<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('models/database.php');
	require_once('models/employees_model.php');
	require_once('models/salesoffices_model.php');
	require_once('models/dropdowns_model.php');
	require_once('models/loan_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function getEeWithLoan($data){
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

		$eewithloans = new LoanModel;
		$res['eewithloans'] = $eewithloans->getEeWithLoan($val);
		$eewithloans ->closeDB();

		return $res;
	}

	function getLoanOfEE($data) {
		$res = array();
		$loanidmst = $data->loanidmst;
		$val = array();

		$loanofee = new LoanModel;
		$res['loanofee'] = $loanofee->getLoanOfEE($loanidmst);
		$loanofee ->closeDB();
		unset($loanofee);


		$loanee = new LoanModel;
		$loanmst = $loanee->getEeWithLoanById($loanidmst)['rows'][0];
		$emptyloandtls = $loanee->getLoanMstTable();
		$loanee->closeDB();
		unset($loanee);

		$res['loanstatus'] = $loanmst['loanstatus'];
		$res['empty'] = 0;
		if(count($res['loanofee']['rows']) == 0) {
			// construct new if empty
			$res['empty'] = 'empty';
			$tmploanid = $loanidmst . '_01';

			$duedate = getPeriod($loanmst['startdate'], $loanmst['paymentfrequency']);

			// construct from an empty array
			$emptyloandtls['id'] = 0;
			$emptyloandtls['loanid'] = $tmploanid;
			$emptyloandtls['loanidmst'] = $loanidmst;
			$emptyloandtls['userid'] = $loanmst['userid'];
			$emptyloandtls['loanamount'] = $loanmst['loanamount'];
			$emptyloandtls['runningbalance'] = $loanmst['loanamount'];
			$emptyloandtls['amountdue'] = $loanmst['amountdue'];
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
			$res['loanofee']['rows'][] = $emptyloandtls;
		} else {
			$getlastloanpay = new LoanModel;
			$lastloanpay = $getlastloanpay->lastLoanPay($loanidmst)['rows'][0];
			$getlastloanpay->closeDB();
			unset($getlastloanpay);
			$res['lastloanpay'] = $lastloanpay;


			if((float)$lastloanpay['amountdue'] > (float)$lastloanpay['totalpaid']){
				// check if previous payment is full
				$res['empty'] = 'due>paid';

			} else {
				$res['empty'] = 'fully paid';
				$getprevindex = explode('_',$lastloanpay['loanid']);
				$previdindex = (int)($getprevindex[count($getprevindex)-1]) + 1;
				unset($getprevindex); // clean

				$newrunningbalance = $lastloanpay['runningbalance'] - $lastloanpay['totalpaid'];
				if((float)$newrunningbalance > 0) {
					$newindex = $previdindex < 10 ? '0' . $previdindex : $previdindex;
					unset($previdindex); // clean
					
	
					$tmploanid = $loanidmst . '_' . $newindex;
	
					$duedate = getPeriod($lastloanpay['duedate'], $loanmst['paymentfrequency']);
	
					// construct from an empty array
					$emptyloandtls['id'] = 0;
					$emptyloandtls['loanid'] = $tmploanid;
					$emptyloandtls['loanidmst'] = $loanidmst;
					$emptyloandtls['userid'] = $loanmst['userid'];
					$emptyloandtls['loanamount'] = $loanmst['loanamount'];
					$emptyloandtls['runningbalance'] = $newrunningbalance;
					$emptyloandtls['amountdue'] = $loanmst['amountdue'] - ($loanmst['amountdue'] - $lastloanpay['amountdue']) - ($lastloanpay['totalpaid'] - $loanmst['amountdue']);
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
					$res['loanofee']['rows'][] = $emptyloandtls;
				}
			}
		}

		unset($val);
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
	#endregion

	function getOfc(){
		$res = array();

		$ofclist = new SalesOfficesModel;
		$res['ofclist'] = $ofclist->getSalesOffices();
		$ofclist->closeDB();

		return $res;
	}

	function payLoan($data){
		$res = array();
		$val = array();
		$amountdue = str_replace(',','',$data->amountdue);
		$amountpaid = str_replace(',','',$data->amountpaid);
		$runningbalance = str_replace(',','',$data->runningbalance);
		$loanid = $data->loanid;
		$duedate = formatDate('Y-m-d',$data->duedate) . ' 00:00:00';
		$paystate = $data->paystate;

		$val['userid'] = $data->userid;
		$val['loanid'] = $loanid;
		$val['loanidmst'] = $data->loanidmst;
		$val['amountdue'] = $amountdue;
		$val['amountpaid'] = $amountpaid;
		$val['runningbalance'] = $runningbalance;
		$val['duedate'] = $duedate;
		$val['paiddate'] = TODAY;
		$val['createdby'] = $data->createdby;
		$val['createddate'] = TODAY;
		$val['state'] = 'done';
		$val['subid'] = 1;
		$val['prevsubid'] = 1;
		// $val['runningbalance'] = number_format((float)$runningbalance - (float)$amountpaid, 2);


		$loanmodel = new LoanModel;
		$totalpaid = $loanmodel->getTotalPaid($loanid)['totalpaid'];
		$val['totalpaid'] = $totalpaid == '' || $totalpaid == null ? $amountpaid : $totalpaid;

		// if($amountpaid == $amountdue) { // paid exact
			
		// } else if($amountpaid > $amountdue) { // paid more than due
			// state = done
		// } else 
		if($amountpaid < $amountdue) { // paid less than due
			$val['state'] = 'continue';
		}

		if($paystate == 'continue') {
			$prevpaid = $loanmodel->getPrevPaid($loanid)['result'];
			$newamountpaid = (float)$amountpaid + (float)(str_replace(',','',$totalpaid));
			// $val['runningbalance'] = (float)$runningbalance - (float)$totalpaid;
			$val['amountdue'] = str_replace(',','',$prevpaid['amountdue']);
			$val['amountpaid'] = $amountpaid;
			$val['totalpaid'] =  $newamountpaid;
			$val['prevsubid'] = $prevpaid['subid'];
			$val['subid'] = $prevpaid['subid'] + 1;

			$res['prevpaid'] = $prevpaid;
		}
		$res['paid'] = $loanmodel->payPeriod($val);

		if($res['paid']['err'] == 0){
			if($paystate == 'continue') {
				$updateprev = $loanmodel->updatePrevLoan($val);
			}
		}
		$loanmodel->closeDB();
		unset($loanmodel);

		
		$res['val'] = $val;
		unset($val);
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

	function getLoanTypeList(){
		$res = array();
		
		$getloantype = new LoanModel;
		$res['loantypelist'] = $getloantype->getLoanType();
		$getloantype->closeDB();

		return $res;
	}

	function getPaymentFreq() {
		$res = array();
		
		$getpaymentfreq = new LoanModel;
		$res['loanpaymentfreq'] = $getpaymentfreq->getPaymentFrequency();
		$getpaymentfreq->closeDB();

		return $res;
	}


	function addNewLoan($data) {
		$res = array();
		$val = array();

		$userid = $data->userid;
		$loantype = $data->loantype;
		$paymentfreq = $data->paymentfreq;
		$loanamount = $data->loanamount;
		$amountdue = $data->amountdue;
		$startdate = $data->startdate;
		$createdby = $data->createdby;
		$loanidmst = "LOAN" . strtoupper($loantype) . "_" . $userid . "_" . formatdate('Ymd', $startdate);

		$res['loanidmst'] = $loanidmst;
		
		$val['userid'] = $userid;
		$val['loantype'] = $loantype;
		$val['paymentfreq'] = $paymentfreq;
		$val['loanamount'] = $loanamount;
		$val['amountdue'] = $amountdue;
		$val['startdate'] = $startdate;
		$val['createdby'] = $createdby;
		$val['loanidmst'] = $loanidmst;

		$checkloan = new LoanModel;
		$res['loancheckexist'] = $checkloan->loanExist($val);
		$checkloan->closeDB();
		unset($checkloan);

		$loanexist = (int)$res['loancheckexist']['result']['loanexist'];
		$res['loanexist'] = $loanexist == 1;

		// if loan not exist, add
		$res['addnewloan'] = array();
		if(!$loanexist == 1) {
			$res['test'] = '';
			$addnewloan = new LoanModel;
			$res['addnewloan'] = $addnewloan->addNewLoan($val);
			$addnewloan->closeDB();
			unset($addnewloan);
		}

		return $res;
	}
	
	function closeLoan($data){
		$res = array();
		$val = array();

		$val['userid'] = $data->userid;
		$val['loanidmst'] = $data->loanidmst;

		$loanmodel = new LoanModel;
		$res['closeloan'] = $loanmodel->closeLoan($val);
		$loanmodel->closeDB();
		unset($loanmodel);
		
		return $res;
	}


	// required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    echo json_encode($result);
?>