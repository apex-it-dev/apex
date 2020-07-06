<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/attendance_model.php');
	require_once('models/employees_model.php');
	require_once('models/salesoffices_model.php');
	
	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function getAttendance($data){
		$res = array();
		$val = array();
		// $ofcselected = $data->ofcselected;
		// $user = $data->user;
		$userid = $data->userid;
		// $ofc = $ofcselected ? $data->office : $data->ofc;
		$dept = $data->dept;
		$rank = $data->rank;
		$pos = $data->pos;
		$val['userid'] = $userid;
		$val['yearmonth'] = $data->yearmonth;
		$val['logfm'] = $data->logfm;
		$val['logto'] = $data->logto;
		$val['ofc'] = $ofc;
		$val['dept'] = $dept;
		$val['pos'] = $pos;
		$val['rank'] = $rank;
		$loadee = $data->loadee;
		$val['ee'] = $data->ee;
		$val['office'] = $data->office;
		$val['viewtype'] = $data->viewtype;
		// $val['user'] = $user;

		// $val['ofcid'] = "";
		// $val['ofcname'] = "";
		// $res['salesoffice'] = array();
		
		// $val['incofcs'] = array();
		// $val['incofcsdesc'] = array();
		// if(!empty($val['office'])){
		// 	$soffices = new SalesOfficesModel;
		// 	$soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];

		// 	$res['salesoffice'] = $soffice;
		// 	$val['ofcid'] = $soffice['salesofficeid'];
		// 	$val['ofcname'] = $soffice['description'];

		// 	$incofcs = $soffice['incofcs'];
		// 	$incofsids = array();
		// 	$listofincofcs  = array();
		// 	if($incofcs != NULL || $incofcs != ''){
		// 		$listofincofcs = explode(' ',$incofcs);
		// 		foreach ($listofincofcs as $key => $incofc) {
		// 			$incofsid_tmp = $soffices->getSalesOfficeByDesc($incofc)['rows'];
		// 			if(count($incofsid_tmp) > 0){
		// 				$incofsids[] = $incofsid_tmp[0]['salesofficeid'];
		// 			}
		// 		}
		// 	}
		// 	$val['incofcs'] = $incofsids;
		// 	$val['incofcsdesc'] = $listofincofcs;
		// 	$soffices->closeDB();
		// }
		
		$attendances = new AttendanceModel;
		$attendancelist = $attendances->getAttendance($val);
		$attendances->closeDB();
		unset($attendances);
		
		$res['attendances']['error'] = $attendancelist['error'];
		$res['attendances']['rows'] = array();
		foreach ($attendancelist['rows'] as $key => $eachitem) {
			$res['attendances']['rows'][] = array(
				'userid' => $eachitem['userid'],
				'officename' => $eachitem['officename'],
				'loggeddt' => $eachitem['loggeddt'],
				'eename' => $eachitem['eename'],
				'login' => $eachitem['login'],
				'logout' => $eachitem['logout'],
				'remarks' => $eachitem['remarks'],
				'onleave' => $eachitem['onleave'],
				'leavetype' => $eachitem['leavetype'],
				'loggedno' => $eachitem['loggedno'],
				'loggedno' => $eachitem['loggedno'],
				'loggedin' => $eachitem['loggedin']
			);
		}

 		// $res['eedata'] = array();
 		// if($loadee == 0){
			//  $ees = new EmployeesModel;
	 		// if(in_array($user, $userList)){
	 			// $res['eedata'] = $ees->getAllAbaPeopleByOffice($val);
	 		// }else{
	 		// 	$res['eedata'] = $ees->getAllAbaPeopleByTL($userid);
	 		// }
	 		// $ees->closeDB();
	 	// }
		// $res['user'] = $user;
 		// $res['data'] = $data;
		return $res;
	}

	function signInAttendance($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$today = TODAY;
		$signintype = $data->signintype;

		$ees = new EmployeesModel;
		$ee = $ees->getEeByUserId($userid)['rows'][0];
		$ees->closeDB();
		$zkid = $ee['zkid'];
		$zkdeviceid = $ee['zkdeviceid'];
		$startshift = $ee['startshift'];
		$loggedno = formatDate("Ymd",$today);

		$val['zkdeviceid'] = $zkdeviceid;
		$val['zkid'] = $zkid;
		$val['userid'] = $userid;
		$val['loggedno'] = $loggedno;
		$val['loggeddate'] = $today;
		$val['loggedin'] = $today;
		$val['startshift'] = $startshift;
		$val['signintype'] = $signintype;

		$attendances = new AttendanceModel;
		$res['attendacesres'] = $attendances->checkAttendance($val);
		$attendancecount = count($res['attendacesres']['rows']);
		$res['attendance'] = "";
		$val['recordisabsent'] = 0;
		if($attendancecount > 0){
			// $attendanceisleave =  $res['attendacesres']['rows'][0]['onleave'];
			$recordisabsent = $res['attendacesres']['rows'][0]['loggedin'] == null ? 1 : 0;
			$val['recordisabsent'] = $recordisabsent;
			$val['loggedout'] = "";
			if($recordisabsent == 1){
				$res['attendance'] = $attendances->signInAttendance($val);
			} else {
				$res['attendance'] = $attendances->signOutAttendance($val);
			}
		}else{
			$val['loggedout'] = "";
			$res['attendance'] = $attendances->signInAttendance($val);
		}
		$attendances->closeDB();

		exitfn:
		return $res;
	}

	function signOutAttendance($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$today = TODAY;

		$ees = new EmployeesModel;
		$ee = $ees->getEeByUserId($userid)['rows'][0];
		$ees->closeDB();

		$val['zkdeviceid'] = $ee['zkdeviceid'];
		$val['zkid'] = $ee['zkid'];
		$val['userid'] = $userid;
		$val['loggedno'] = formatDate("Ymd",$today);
		$val['loggedout'] = $today;

		$attendances = new AttendanceModel;
		$res['attendacesres'] = $attendances->checkAttendance($val); 	
		$res['attendancecount'] = count($res['attendacesres']['rows']);
		$res['attendance'] = $attendances->signOutAttendance($val);
		$attendances->closeDB();

		return $res;
	}

	function checkAttendance($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$today = TODAY;

		$ees = new EmployeesModel;
		$ee = $ees->getEeByUserId($userid)['rows'][0];
		$ees->closeDB();

		$val['zkdeviceid'] = $ee['zkdeviceid'];
		$val['zkid'] = $ee['zkid'];
		$val['userid'] = $userid;
		$val['loggedno'] = formatDate("Ymd",$today);
		$val['loggedout'] = $today;

		$attendances = new AttendanceModel;
		$res['attendacesres'] = $attendances->checkAttendance($val); 
		$loggedoutval = "";
		$loggedinval = "";
		if(count($res['attendacesres']['rows']) > 0){
			$loggedoutval = $res['attendacesres']['rows'][0]['loggedout'];
			$loggedinval = $res['attendacesres']['rows'][0]['loggedin'];
		}
		$res['loggedout'] = $loggedoutval;	
		$res['loggedin'] = $loggedinval;	

		$attendances->closeDB();

		return $res;
	}

	function getEePerOfc($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$val['office'] = $data->office;
		$viewtype = $data->viewtype;
		$ofc = $data->office;
		$ofclist = $data->ofclist;
		$eedata = array();

		if(!empty($ofc)){
			// $soffices = new SalesOfficesModel;
			// $soffice = $soffices->getSalesOfficeByDesc($ofc)['rows'][0];
			// $soffices->closeDB();

			// $res['salesoffice'] = $soffice;
			// $val['ofcid'] = $soffice['salesofficeid'];
			// $val['ofcname'] = $soffice['description'];
			$ees = new EmployeesModel;
			$eedata = $ees->getAllAbaPeopleByOffice($val);
			$ees->closeDB();
		} else {
			$ees = new EmployeesModel;
			$eedata = $ees->getAllAbaPeople($val);
			$ees->closeDB();
		}

		// $res['thisee'] = $eedata;

		$newdata = array();
		foreach ($eedata['rows'] as $eachee) {
			foreach ($ofclist as $eachofc) {
				if($eachofc == $eachee['office']) {
					$newdata[] = $eachee;
				}
			}
		}
		// $res['aba'] = $newdata;
		$res['ofclist'] = $ofclist;

		$res['eedata']['rows'] = array();
		switch ($viewtype) {
			case 'department':
				foreach ($newdata as $key => $eachee) {
					if($eachee['reportstoid'] == $userid || $eachee['reportstoindirectid'] == $userid || $eachee['userid'] == $userid) {
						$res['eedata']['rows'][] = array('userid' => $eachee['userid'], 'eename' => $eachee['eename']);
					}
				}
				break;
			case 'ofclist':
				foreach ($newdata as $key => $eachee) {
					$res['eedata']['rows'][] = array('userid' => $eachee['userid'], 'eename' => $eachee['eename']);
				}
				break;
			default: break;
		}


		return $res;
	}
	function getAttendanceByDate($data){
		$res = array();
		$val = array();

		$datein = $data->datefilter;


		$val['datein'] = $datein;

		$attendances = new AttendanceModel;
		$res['attendance'] = $attendances->getAttendanceByDate($val);
		$attendances->closeDB();
		
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