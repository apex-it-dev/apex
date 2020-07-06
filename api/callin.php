<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/leaves_model.php');
	require_once('models/dropdowns_model.php');
	require_once('models/employees_model.php');
	require_once('models/attendance_model.php');
	require_once('models/sendmail_model.php');
	require_once('models/salesoffices_model.php');
	require_once('models/notifiedpersons_model.php');
	require_once('models/callin_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function loadDataCallin($data){
		$res = array();
		$val = array();
		$id = '';
		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['fiscalyear'] = formatDate('Y',TODAY);

		$callins = new CallInModel;
		$res['callins'] = $callins->getAllCallIn($val);
 		$callins->closeDB();

 		$dd = new DropdownsModel;
		$res['leaveduration'] = $dd->getLeaveDuration($id);
		$dd->closeDB();

		$leaves = new LeavesModel;
		$res['leavecredits'] = $leaves->getleaveTypes($val);
 		$leaves->closeDB();

		$ees = new EmployeesModel;
		$res['eeinfo'] = $ees->getActiveabaPeopleWithId($userid);
		$res['reportsto'] = $ees->getActiveabaPeopleWithId($res['eeinfo'][0]['reportstoid']);
		$res['reportstoindirect'] = $ees->getActiveabaPeopleWithId($res['eeinfo'][0]['reportstoindirectid']);
		// if($reportstoindirect != []){
		// 	$res['reportstoindirect'] = $reportstoindirect;
		// }
		return $res;
	}

	function addCallIn($data){
		$res = array();
		$val = array();
		$emaildetails =  array();
		$leavetypedet = array();
		$res['errsentnotif'] = 0;
		$res['errsaveleave'] = 0;

		$userid = $data->userid;
		$callintype = $data->callintype;
		$today = TODAY;

		$val['userid'] = $userid;
		$val['callintype'] = $callintype;
		$val['eta'] = $data->eta;
		$val['leavereason'] = $data->callinreason;
		$val['leavetype'] = $data->leavetype;
		$val['leaveduration'] = $data->leaveduration;
		$val['noofdays'] = $data->noofdays;
		$val['fiscalyear'] = $data->fiscalyear;
		$leavedtls = explode("||", $data->leavedtls);
		$val['leavefrom'] = $data->leavefrom;
		$val['leaveto'] = $data->leaveto;
		$absenttypedesc = $data->absenttypedesc;

		$callins = new CallInModel;
		$val['callinid'] = $callins->genCallInID($userid);
		$res['addcallin'] = $callins->addCallIn($val);
 		$res['getcallin'] = $callins->getCallIn($val);

 		$ees = new EmployeesModel;
		$eedata['eedata'] = $ees->getActiveabaPeopleWithId($userid);
		$reportstoid = $eedata['eedata'][0]['reportstoid'];
		$eedata['reportstodata'] = $ees->getActiveabaPeopleWithId($reportstoid);
		$reportstoindirectid = $eedata['eedata'][0]['reportstoindirectid'];
		$eedata['reportstoindirectdata'] = $ees->getActiveabaPeopleWithId($reportstoindirectid);
		$ofc = $eedata['eedata'][0]['office'];
		$myworkemail = $eedata['eedata'][0]['workemail'];

		if($eedata['reportstoindirectdata'] == null || $eedata['reportstoindirectdata'] == ""){
			$val['approvallevel'] = 2;
		}else{
			$val['approvallevel'] = 1;
		}

 		if($callintype == 'abs'){
 			$leaves = new LeavesModel;
			$leaveid = $leaves->genLeaveID($userid);
			$val['leaveid'] = $leaveid;
			$res['errsaveleave'] = $leaves->saveLeaveRequest($val);
			$res['errupdateleaveid'] = $callins->updateLeaveIdCallin($val);

			if($res['errsaveleave']['err'] == 0){
				for($i=0;$i<count($leavedtls);$i++){
					$ldtls = explode("::",$leavedtls[$i]);
					$dtls['userid'] = $userid;
					$dtls['leaveid'] = $leaveid;
					$dtls['leavedate'] = $ldtls[0]; // leave date
					$dtls['wap'] = $ldtls[1]; // w - wholeday, a - am, p - pm
					$dtls['points'] = $ldtls[2]; // points 1 or 0.5
					$dtls['remarks'] = $ldtls[3]; // remarks

					$res['leavedetails'][] = $leaves->saveLeaveDetails($dtls);
				}
				$res['leavedtls'] = $leaves->getLeaveRequest($leaveid);
				$res['leaves'] = $leaves->getAllLeaveRequests($val);

				$emaildetails['requestor'] = $eedata['eedata'][0]['eename'];
				$emaildetails['requestoremail'] = $myworkemail;
				$emaildetails['reportstoname'] = $eedata['reportstodata'][0]['eename'];
				$emaildetails['reportstoemail'] = $eedata['reportstodata'][0]['workemail'];
				// $emaildetails['reportstoemail'] = 'vivencia.velasco@abacare.com';
				$emaildetails['reportstoindirectname'] = "";
				$emaildetails['reportstoindirectemail'] = "";
				if(count($eedata['reportstoindirectdata']) > 0 ){
					$emaildetails['reportstoindirectname'] = $eedata['reportstoindirectdata'][0]['eename'];
					// $emaildetails['reportstoindirectemail'] = 'vivencia.velasco@abacare.com';
					$emaildetails['reportstoindirectemail'] = $eedata['reportstoindirectdata'][0]['workemail'];
				}

				$emaildetails['callinid'] = $res['getcallin']['rows'][0]['callinid'];
				$emaildetails['approvallevel'] = $res['leavedtls']['rows'][0]['approvallevel'];
				$emaildetails['leaveid'] = $leaveid;
				$emaildetails['leavetype'] = $res['leavedtls']['rows'][0]['leavetypedesc'];
				$emaildetails['leaveduration'] = $res['leavedtls']['rows'][0]['leavedurationdesc'];
				$emaildetails['reason'] = $res['leavedtls']['rows'][0]['reason'];
				$emaildetails['leavefrom'] = $res['leavedtls']['rows'][0]['leavefromdt'];
				$emaildetails['leaveto'] = $res['leavedtls']['rows'][0]['leavetodt'];
				$emaildetails['noofdays'] = $res['leavedtls']['rows'][0]['noofdays'];
				$emaildetails['leavestatus'] = $res['leavedtls']['rows'][0]['leavestatus'];
				$emaildetails['sesid'] = $res['leavedtls']['rows'][0]['sesid'];
				$emaildetails['approveddate_indirect'] = $res['leavedtls']['rows'][0]['approveddate_indirect'];
				$emaildetails['leavebalance'] = $res['leavedtls']['rows'][0]['leavebalance'];

				// $emaildetails['attachment'] = $filenamepathstring;
				$notiftype = "leaveapproved";
				$notifdata=array();
				$notifdata['ofc'] = $ofc;
				$notifdata['notiftype'] = $notiftype;
	 			$notifiedpersons = new NotifiedPersonsModel;
				$ccemailres = $notifiedpersons->getLeaveApprovedNotifPersons($notifdata);
				$cccount = count($ccemailres['rows']);
				$ccemails[] = $myworkemail;
				if(count($eedata['reportstoindirectdata']) > 0 ){
					// $ccemails[] = 'vivencia.velasco@abacare.com';
					$ccemails[] = $eedata['reportstoindirectdata'][0]['workemail']; 
				}
				if($cccount>0){
					for ($i=0; $i<$cccount ; $i++) { 
						$ccemails[] = $ccemailres['rows'][$i]['ccemailaddress'];
					}
				}
				$emaildetails['ccemails'] = $ccemails;
				$emaildetails['callintypedesc'] = 'Absences';
				$emaildetails['absenttypedesc'] = $absenttypedesc;
				$res['emaildetailsres'] = $emaildetails;
				$email = new sendMail;
	  			$res['errsent'] = $email->sendCallInLeaveRequestApproval($emaildetails);

	  			if($res['errsent']['sent'] == 1){
	  				$res['errupdateemailnotif'] = $callins->updateEmailNotifSent($val);
	  			}
			}
 		}else if($callintype == 'trd'){
 			$emaildetailstardi = array();
 			
 			$emaildetailstardi['callinid'] = $res['getcallin']['rows'][0]['callinid'];
			$emaildetailstardi['reason'] = $res['getcallin']['rows'][0]['reason'];
			$emaildetailstardi['eta'] = $res['getcallin']['rows'][0]['eta'];
			$emaildetailstardi['createddt'] = $res['getcallin']['rows'][0]['createddt'];

 			$emaildetailstardi['reportstoname'] = $eedata['reportstodata'][0]['eename'];
			$emaildetailstardi['reportstoemail'] = $eedata['reportstodata'][0]['workemail'];
			$emaildetailstardi['reportstoindirectname'] = "";
			$emaildetailstardi['reportstoindirectemail'] = "";
			$emaildetailstardi['requestor'] = $eedata['eedata'][0]['eename'];

			$notiftype = "callin";
			$notifdata=array();
			$notifdata['ofc'] = $ofc;
			$notifdata['notiftype'] = $notiftype;
 			$notifiedpersons = new NotifiedPersonsModel;
			$ccemailres = $notifiedpersons->getLeaveApprovedNotifPersons($notifdata);
			$cccount = count($ccemailres['rows']);
			$ccemails[] = $myworkemail;
			if(count($eedata['reportstoindirectdata']) > 0 ){
				// $ccemails[] = 'vivencia.velasco@abacare.com';
				$ccemails[] = $eedata['reportstoindirectdata'][0]['workemail'];
			}
			if($cccount>0){
				for ($i=0; $i<$cccount ; $i++) { 
					$ccemails[] = $ccemailres['rows'][$i]['ccemailaddress'];
				}
			}
			$emaildetailstardi['ccemails'] = $ccemails;
			$emaildetailstardi['callintypedesc'] = 'Tardiness';
			$res['emaildetailstardi'] = $emaildetailstardi;
			$email = new sendMail;
	  		$res['errsent'] = $email->sendTardinessNotification($emaildetailstardi);
			if($res['errsent']['sent'] == 1){
				$res['errupdateemailnotif'] = $callins->updateEmailNotifSent($val);
			}
 		}	
 		$callins->closeDB();
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