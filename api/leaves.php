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

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function loadDefault($data){
		$res = array();
		$val = array();
		$id = '';
		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['fiscalyear'] = $data->fiscalyear;

		$leaves = new LeavesModel;
		$res['leavecredits'] = $leaves->getleaveTypes($val);
		// $res['pendingcount'] = $leaves->getPendingLeavesUnderUser($userid)['pending_count'];
 		$leaves->closeDB();

 		$dd = new DropdownsModel;
		$res['leaveduration'] = $dd->getLeaveDuration($id);
		$dd->closeDB();

		$ees = new EmployeesModel;
		$res['eeinfo'] = $ees->getActiveabaPeopleWithId($userid);
		$res['reportsto'] = $ees->getActiveabaPeopleWithId($res['eeinfo'][0]['reportstoid']);
		$res['reportstoindirect'] = $ees->getActiveabaPeopleWithId($res['eeinfo'][0]['reportstoindirectid']);
		// if($userid == 'A170810-00000' || $userid == 'A161215-00089'){
 	// 		// $ees = new EmployeesModel;
		// 	$res['eedata'] = $ees->getAllAbaPeople();
 	// 	}
 		$ees->closeDB();

 		$soffices = new SalesOfficesModel;
		$res['offices'] = $soffices->getSalesOfficesMain();
		$soffices->closeDB();

		return $res;
	}

	function loadLeaveHistory($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$val['userid'] = $data->userid;
		$val['viewtype'] = $data->viewtype;
		$val['ofclist'] = $data->ofclist;
		
		/*
		! some changes here
		$ofc = new SalesOfficesModel;
		$ofconly = $ofc->getSalesOfficeByOfcId($ofcid)['description'];

		if(strpos($ofconly, 'hk')){
			$val['ofconly'] = 'abahk';
			$val['ofconly1'] = 'sschk';
		} else {
			$val['ofconly'] = $ofconly == null ? $ofcid : $ofconly;
		}
		$val['officeid'] = '';

		$reportsto = '';
		$reportstoindirect = '';

		$val['ofc'] = $ofc->getSalesOfficeId($val['ofconly']);
		if(count($val['ofc']) > 0){ $val['officeid'] = $val['ofc']['salesofficeid']; }
		unset($val['ofc']); // remove variable

		if(isset($val['ofconly1'])){
			$val['ofc1'] = $ofc->getSalesOfficeId($val['ofconly1']);
			if(count($val['ofc1']) > 0){ $val['officeid1'] = $val['ofc1']['salesofficeid']; }
			unset($val['ofc1']); // remove variable
		}
		$ofc->closeDB();
		*/


		$ees = new EmployeesModel;
		$eeinfo = $ees->getAllActiveabaPeopleWithId($val);
		$res['eeinfo'] = $eeinfo['rows'];;
		// $res['eesql'] = $eeinfo['sql'];
		
		$reportsto = $ees->getActiveReportingToPeopleWithId($res['eeinfo']);
		foreach($res['eeinfo'] as $index=>$employee){
			$res['eeinfo'][$index]['reportsto'] = '';
			$res['eeinfo'][$index]['reportstoini'] = '';
			$res['eeinfo'][$index]['reportstoindirect'] = '';
			$res['eeinfo'][$index]['reportstoindirectini'] = '';
			foreach($reportsto as $reportsindex=>$reportsee){
				$match = 0;
				if($employee['reportstoid'] == $reportsee['userid']){
					$res['eeinfo'][$index]['reportsto'] = $reportsee['eename'];
					$res['eeinfo'][$index]['reportstoini'] = $reportsee['abaini'];
					$match += 1;
				}
				if($employee['reportstoindirectid'] == $reportsee['userid']){
					$res['eeinfo'][$index]['reportstoindirect'] = $reportsee['eename'];
					$res['eeinfo'][$index]['reportstoindirectini'] = $reportsee['abaini'];
					$match += 1;
				}
				if($match >=2){
					break;
				}
			}
		}

		$ees->closeDB();

		$userids = array();
		// $userids[] = $userid;
		foreach($res['eeinfo'] as $row){
			$userids[] = $row['userid'];
		}
		// $res['alluserids'] = $userids;
		$val['userids'] = array_unique($userids);
		// $res['userids'] = $val['userids'];


		$leaves = new LeavesModel;
		$res['leaves'] = $leaves->getAllLeaveRequestsHistory($val['userids']);
		$leaves->closeDB();
		 
		return $res;
	}

	function loadPendingLeaveRequests($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['viewtype'] = $data->viewtype;
		$val['ofclist'] = $data->ofclist;
		$val['canapprreject'] = $data->canapprreject;
		$val['userofc'] = $data->userofc;

		/*
		if(strpos($data->ofconly, 'hk')){
			$ofcname = 'abahk';
		} else {
			$ofcname = $data->ofconly;
		}
		if(!empty($ofcname) && $ofcname != '' && $ofcname != 'default'){
			$soffices = new SalesOfficesModel;
			$salesoffice = $soffices->getSalesOfficeByDesc($ofcname);
			$soffices->closeDB();
			$val['officeid'] = $salesoffice['rows'][0]['salesofficeid'];
			unset($salesoffice);
		}
		*/


		$leaves = new LeavesModel;
		$res['pendingleaves'] = $leaves->getPendingLeaveRequests($val);
		// $res['pendingcount'] = $leaves->getPendingLeavesUnderUser($val)['pending_count'];
 		$leaves->closeDB();

		$eehead = new EmployeesModel;
		foreach($res['pendingleaves']['rows'] as $index=>$employee){
			$res['pendingleaves']['rows'][$index]['eeinfo'] = $eehead->getEeHead($employee['userid']);
		}
		$eehead->closeDB();

		return $res;
	}

	function getPendingCount($data) {
		$val = array();
		$val['userid'] = 			$data->userid;
		$val['viewtype'] =			$data->viewtype;
		$val['canapprreject'] = 	$data->canapprreject;
		$val['ofclist'] = 			$data->ofclist;
		
		$leaves = new LeavesModel;
		$pendingcount = $leaves->getPendingLeavesUnderUser($val)['data']['pending_count'];
		$leaves->closeDB();

		return $pendingcount;
	}

	function getLeave($data){
		$res = array();
		$val = array();
		$filenames = array();
		$leaveid = $data->leaveid;

		$leaves = new LeavesModel;
		$res['leave'] = $leaves->getLeaveRequest($leaveid);

		$res['reportstoname'] = '';
		$res['reportstoindirectname'] = '';

		$tmpid = '';
		$tmpid = $res['leave']['rows'][0]['reportstoid'];
		if($tmpid != ''){
			$val['fullname'] = $leaves->getReportsToName($tmpid);
			if($val['fullname']['err'] == 0){
				$res['reportstoname'] = $val['fullname']['rows'][0]['fullName'];
			}
		}

		$tmpid = '';
		$tmpid = $res['leave']['rows'][0]['reportstoindirectid'];
		if($tmpid != ''){
			$val['fullname'] = $leaves->getReportsToName($tmpid);
			if($val['fullname']['err'] == 0){
				$res['reportstoindirectname'] = $val['fullname']['rows'][0]['fullName'];
			}
		}
		unset($val['fullname']);

		$attachment = $res['leave']['rows'][0]['attachment'];
		$userid = $res['leave']['rows'][0]['userid'];
		$res['$attachment']=$attachment;

		if(!empty($attachment)){
			$res['filenames'] = explode("||",$attachment);
			$filecount = count($res['filenames']);
			for($i=0;$i<$filecount;$i++){
				$filename = $res['filenames'][$i];
				$filepath = base_URL."upload/leave_attachment_files/$userid/$filename" . "?" . time(date("Y-m-d H:i:s"));
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ){
					$res['docpaths'][] = '<div class="form-group col-lg-4"><a href="'.$filepath.'" target="'.$filepath.'"><img src="'.$filepath.'" style="width: 100px;"></a></div>'; 
				}else if($ext == 'docx' || $ext == 'doc'){
					$res['docpaths'][] = '<div class="form-group col-lg-4"><a href="'.$filepath.'" target="'.$filepath.'"><i class="fas fa-file-word" style="font-size:48px;"></i></a></div>'; 
				}else if($ext == 'pdf'){
					$res['docpaths'][] = '<div class="form-group col-lg-4"><a href="'.$filepath.'" target="'.$filepath.'"><i class="fas fa-file-pdf style="font-size:48px;"></i></a></div>'; 
				}else{
					$res['docpaths'][] = '<div class="form-group col-lg-4"><a href="'.$filepath.'" target="'.$filepath.'"><i class="fas fa-paperclip style="font-size:48px;"></i></a></div>'; 
				}
				$res['docpaths'][] = $_SERVER['DOCUMENT_ROOT']."/hris-dev/upload/leave_attachment_files/$userid/$filename";
			}
		}else{
			$res['docpaths'][]=[];
		}
		
		// $userid = $res['leave']['rows'][0]['userid'];
		// $countfiles = count($filenames);
		// for($i=0;$i<=$countfiles;$i++){
			// $filename = $filenames[$i];
			// $res['docpath'] = $_SERVER['DOCUMENT_ROOT']."/hris-dev/upload/tmp/".$userid."/".$filename;
		// }
		// $docpath
 		// $leaves->closeDB();

		return $res;
	}

	function approveLeave($data){
		$res = array();
		$val = array();
		$ccemails = array();
		$userid = $data->approver;
		$leaveid = $data->leaveid;

		$val['leaveid'] = $leaveid;
		$val['status'] = $data->stat == 0 ? -1 : $data->stat;
		$val['cmts'] = $data->cmts;
		$val['reportstoid'] = $userid;
		$val['userid'] = $userid;
		$val['stat'] = $data->stat;

		$val['viewtype'] = $data->viewtype;
		$val['ofclist'] = $data->ofclist;
		$val['canapprreject'] = $data->canapprreject;
		$val['userofc'] = $data->userofc;

		$leaves = new LeavesModel;
		$res['leave'] = $leaves->apprLeaveRequest($val);
		// $res['pendingcount'] = $leaves->getPendingLeavesUnderUser($userid)['pending_count'];
		$request = $leaves->getLeaveRequest($leaveid);
		$leave = $request['rows'][0];
		$val['leave'] = $leave;
		$res['pendingleaves'] = $leaves->getPendingLeaveRequests($val);
		$approvallevel = $leave['approvallevel'];
		$status = $leave['status'];

		$ees = new EmployeesModel;
		$eedata['eedata'] = $ees->getActiveabaPeopleWithId($request['rows'][0]['userid']);
		foreach($res['pendingleaves']['rows'] as $index=>$employee){
			$res['pendingleaves']['rows'][$index]['eeinfo'] = $ees->getEeHead($employee['userid']);
		}
		$reportstoid = $eedata['eedata'][0]['reportstoid'];
		$eedata['reportstodata'] = $ees->getActiveabaPeopleWithId($reportstoid);
		$reportstoindirectid = $eedata['eedata'][0]['reportstoindirectid'];
		$eedata['reportstoindirectdata'] = $ees->getActiveabaPeopleWithId($reportstoindirectid);
		$ofc = $eedata['eedata'][0]['office'];
		$ees->closeDB();
		
		if($status == 1){
			if($data->stat > 0){
				$res['leavetodeduct'] = $leaves->deductLeaveCredits($val['leave']);
				$leavedetails = $leaves->getLeaveDetails($leaveid)['rows'];
				// $res['leavedetails'] = $leavedetails;

				// for($i=0;$i<count($leavedetails);$i++){
				foreach($leavedetails as $i=>$theleave){
					$logs = array();
					$logs['leaveid'] = $leaveid;
					$logs['zkdeviceid'] = $leave['zkdeviceid'];
					$logs['zkid'] = $leave['zkid'];
					$logs['userid'] = $theleave['createdby'];
					$loggedno = $theleave['leavedate'];
					$logs['loggedno'] = formatDate("Ymd", $loggedno);
					$logs['loggedin'] = formatDate("Y-m-d", $loggedno) . " 00:00:00";
					$logs['loggedout'] = formatDate("Y-m-d", $loggedno) . " 00:00:00";
					$wap = $theleave['wap'];
					$logs['wholeday'] = $wap == 'w' ? 1 : 0;
					$logs['firsthalf'] = $wap == 'a' ? 1 : 0;
					$logs['secondhalf'] = $wap == 'p' ? 1 : 0;

					$attendances = new AttendanceModel;
					$check_attendance = array();
					$check_attendance = $attendances->checkAttendance($logs)['rows'];
					$logs['attendance_exist'] = count($check_attendance) > 0 ? 1 : 0;
			 		$res['attendance'][] = $attendances->saveAttendanceLog($logs);
			 		$attendances->closeDB();
			 		// $res['logs'][] = $logs;
			 	}
			}
			$notiftype = "leaveapproved";
			$notifdata=array();
			$notifdata['ofc'] = $ofc;
			$notifdata['notiftype'] = $notiftype;
 			$notifiedpersons = new NotifiedPersonsModel;
			$ccemailres = $notifiedpersons->getLeaveApprovedNotifPersons($notifdata);
			$cccount = count($ccemailres['rows']);
			if($cccount>0){
				for ($i=0; $i<$cccount ; $i++) { 
					$ccemails[] = $ccemailres['rows'][$i]['ccemailaddress'];
				}
			}
			$val['ccemails'] = $ccemails;
	 		$email = new sendMail;
			$res['errsent'] = $email->sendLeaveApproveNotification($val);
			

		}else if($status == 0){
			
			$res['leavedtls'] = $leaves->getLeaveRequest($leaveid);
			$res['leaves'] = $leaves->getAllLeaveRequests($val);
			$emaildetails['approvallevel'] = $res['leavedtls']['rows'][0]['approvallevel'];
			$emaildetails['reportstoname'] = $eedata['reportstodata'][0]['eename'];
			$emaildetails['reportstoemail'] = $eedata['reportstodata'][0]['workemail'];
			$emaildetails['reportstoindirectname'] = "";
			$emaildetails['reportstoindirectemail'] = "";
			if(count($eedata['reportstoindirectdata']) > 0 ){
				$emaildetails['reportstoindirectname'] = $eedata['reportstoindirectdata'][0]['eename'];
				$emaildetails['reportstoindirectemail'] = $eedata['reportstoindirectdata'][0]['workemail'];
			}
			// $emaildetails['reportstoemail'] = 'vivencia.velasco@abacare.com';
			// $emaildetails['reportstoindirectemail'] = 'vivencia.velasco@abacare.com';

			$emaildetails['requestor'] = $eedata['eedata'][0]['eename'];
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
			$emaildetails['ofc'] = $ofc;
			$emaildetails['leavebalance'] = $res['leavedtls']['rows'][0]['leavebalance'];
			// // $emaildetails['attachment'] = $filenamepathstring;
			// $emaildetails = "";
			// $res['emaildetailsss'] = $emaildetails;
			// $emaildetails['ccemail'] = "cdm@abacare.com";
			$notiftype = "leaveapproved";
			$notifdata=array();
			$notifdata['ofc'] = $ofc;
			$notifdata['notiftype'] = $notiftype;
 			$notifiedpersons = new NotifiedPersonsModel;
			$ccemailres = $notifiedpersons->getLeaveApprovedNotifPersons($notifdata);
			$cccount = count($ccemailres['rows']);
			if($cccount>0){
				for ($i=0; $i<$cccount ; $i++) { 
					$ccemails[] = $ccemailres['rows'][$i]['ccemailaddress'];
				}
			}
			$emaildetails['ccemails'] = $ccemails;
			$res['emaildetailsss'] = $emaildetails;
			// $email = new sendMail;
  			// $res['errsent'] = $email->sendLeaveRequestApproval($emaildetails);
			// $res['eeeee'] = $eedata['eedata'];

		}
		
		$leaves->closeDB();
		return $res;
	}

	function addRequestLeave($data){
		$res = array();
		$val = array();
		$emaildetails =  array();
		$leavetypedet = array();
		$res['errsentnotif'] = 0;
		$res['errsaveleave'] = 0;
		$reason = $data->leavereason;
		$fiscalyear = $data->fiscalyear;
		$leavedtls = explode("||", $data->leavedetails);
		$uploadattachement = $data->uploadattachement;
		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['leavefrom'] = $data->leavefrom;
		$val['leaveto'] = $data->leaveto;
		$val['leavetype'] = $data->leavetype;
		$val['leaveduration'] = $data->leaveduration;
		$val['leavereason'] = $reason;
		$val['noofdays'] = $data->noofdays;

		$ees = new EmployeesModel;
		$eedata['eedata'] = $ees->getActiveabaPeopleWithId($userid);
		$reportstoid = $eedata['eedata'][0]['reportstoid'];
		$eedata['reportstodata'] = $ees->getActiveabaPeopleWithId($reportstoid);
		$reportstoindirectid = $eedata['eedata'][0]['reportstoindirectid'];
		$eedata['reportstoindirectdata'] = $ees->getActiveabaPeopleWithId($reportstoindirectid);
		$ofc = $eedata['eedata'][0]['office'];

		if($eedata['reportstoindirectdata'] == null || $eedata['reportstoindirectdata'] == ""){
			$val['approvallevel'] = 2;
		}else{
			$val['approvallevel'] = 1;
		}

		$leaves = new LeavesModel;
		$leaveid = $leaves->genLeaveID($userid);
		$val['leaveid'] = $leaveid;
		$res['errsaveleave'] = $leaves->saveLeaveRequest($val);
		if($res['errsaveleave']['err'] == 0){
		// $res['errsaveleave'] = $leaves->saveLeaveRequest($val);
			if(!empty($uploadattachement)){
				$docpath = "../upload/tmp/".$userid."*.*";
				foreach (glob($docpath) as $filepath) {
			   	 $res['docs'][] = $filepath;
				 $res['filelist'][] = basename($filepath);  
				}

				$filecount = count($res['docs']);
				$eefilepathfolder = "../upload/leave_attachment_files/".$userid;
				if (!file_exists($eefilepathfolder)) {
				    mkdir($eefilepathfolder, 0777);
				}
				
				$arrayfilenames = array();
				for($i=0;$i<$filecount;$i++){
					$filepath = $res['docs'][$i];
					$filename = $res['filelist'][$i];
					if(rename($filepath, $eefilepathfolder."/".$filename)){
						$res['success'] = 'true';
					}else{
						$res['success'] = 'false';
					}
				}
				$filenamestring = '';
				if(count($res['filelist']) != 0){
					$filenamestring = implode("||", $res['filelist']);
				}
				$val['attachment'] = $filenamestring;
				$leaves->updateLeaveAttachment($val);
			}
			
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

			
			$emaildetails['approvallevel'] = $res['leavedtls']['rows'][0]['approvallevel'];
			$emaildetails['reportstoname'] = $eedata['reportstodata'][0]['eename'];
			$emaildetails['reportstoemail'] = $eedata['reportstodata'][0]['workemail'];
			$emaildetails['reportstoindirectname'] = "";
			$emaildetails['reportstoindirectemail'] = "";
			if(count($eedata['reportstoindirectdata']) > 0 ){
				$emaildetails['reportstoindirectname'] = $eedata['reportstoindirectdata'][0]['eename'];
				$emaildetails['reportstoindirectemail'] = $eedata['reportstoindirectdata'][0]['workemail'];
			}
			
			// $emaildetails['reportstoemail'] = 'vivencia.velasco@abacare.com';
			// $emaildetails['reportstoindirectemail'] = 'vivencia.velasco@abacare.com';

			$emaildetails['requestor'] = $eedata['eedata'][0]['eename'];
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
			if($cccount>0){
				for ($i=0; $i<$cccount ; $i++) { 
					$ccemails[] = $ccemailres['rows'][$i]['ccemailaddress'];
				}
			}
			$emaildetails['ccemails'] = $ccemails;
			$res['emaildetailsss'] = $emaildetails;
			$email = new sendMail;
  			$res['errsent'] = $email->sendLeaveRequestApproval($emaildetails);
  		}
  		
  		$leavetypedet['userid'] = $userid;
  		$leavetypedet['fiscalyear'] = $fiscalyear;
  		// $res['leavetypedet'] = $leavetypedet;
		$res['leavecredits'] = $leaves->getleaveTypes($leavetypedet);
  		$leaves->closeDB();
  		$ees->closeDB();
		return $res;
	}

	function getLeaveCredit($data){
		$res = array();
		$val = array();
		// $val['userid'] = $data->userid;
		$val['leaveid'] = $data->leaveid;

		$leaves = new LeavesModel;
		$res['leavebal'] = $leaves->getLeaveCredit($val['leaveid']);
 		$leaves->closeDB();

		return $res;
	}

	function getLeaveBalance($data){
		$res = array();
		$val = array();
		$val['userid'] = $data->userid;
		$val['leavetype'] = $data->leavetype;

		$leaves = new LeavesModel;
		$res['leavebal'] = $leaves->getLeaveBalance($val);
 		$leaves->closeDB();

		return $res;
	}

	function updateLeaveBenefit($data){
		$res = array();
		$val = array();
		
		$val['userid'] = $data->userid;
		$val['eeid'] = $data->eeid;
		$val['id'] = $data->id;
		$val['entitled'] = $data->entitled;
		// $val['taken'] = $data->taken;
		$val['status'] = $data->status;

		$leaves = new LeavesModel;
		$res['leavebal'] = $leaves->updateLeaveBenefit($val);
 		$leaves->closeDB();

		return $res;
	}

	function genLeaveDetails($data){
		$res = array();
		$dtfrom = strtotime(formatDate("Y-m-d",$data->dtfrom));
		$dtto = strtotime(formatDate("Y-m-d",$data->dtto));
		$fm = formatDate("Y-m-d H:i:s",$data->dtfrom);
		$noofdays = ((($dtto - $dtfrom)/60/60/24) + 1);
		$ofc = $data->ofc;

		$res['dtfrom'] = $dtfrom;
		$res['dtto'] = $dtto;
		$res['noofdays'] = $noofdays;
		$res['dates'] = array();
		for($i=0;$i<$noofdays;$i++){
			$strdays = "";
			if($i>0){
				$strdays = "+$i days";
			}
			$dt = date('Y-m-d H:i:s', strtotime($fm.$strdays));
			$res['dates'][] = array("dt"=>$dt,
									"dtymd"=>formatDate("Ymd",$dt),
									"formatted"=>formatDate("D d M Y",$dt),
									"dayofdate"=>strtoupper(formatDate("D",$dt)));
		}

		$salesoffices = new SalesOfficesModel;
		$salesoffice = $salesoffices->getSalesOfficeByOfcId($ofc);
		$salesoffices->closeDB();

		$hol['dtfrom'] = $data->dtfrom;
		$hol['dtto'] = $data->dtto;
		$hol['ofc'] = $salesoffice['salesofficeid'];
		$leaves = new LeavesModel;
		$res['holidays'] = $leaves->getHolidays($hol);
		$res['workingdays'] = $leaves->getWorkingDays($hol);
		$leaves->closeDB();

		return $res;
	}

	function recallLeaveRequest($data){
		$res = array();
		$val = array();

		$val['leaveid'] = $data->leaveid;
		$val['userid'] = $data->userid;

		$leaves = new LeavesModel;
		$getleavedata = $leaves->getLeaveData($val);
		
		$val['hasleavedata'] = count($getleavedata) > 0 ? 1 : 0;
		$val['leaveuserid'] = '';
		$val['leavetype'] = '';
		$val['takendays'] = '';
		if($val['hasleavedata'] == 1) {
			$leavedata = $getleavedata['rows'][0];
			$val['leaveuserid'] = $leavedata['userid'];
			$noofdays = $leavedata['noofdays'];
			$val['leavetype'] = $leavedata['leavetype'];

			$val['leaveduration'] = $leavedata['leaveduration'];
			$res['attendance'] = $leaves->undoLeave($val);

			$val['takendays'] = (string)((float)$leaves->getTakenDays($val)['takendays'] - (float)$noofdays);
		}
		$res['leave'] = $leaves->recallLeaveRequest($val);

		$res['val'] = $val;
		$leaves->closeDB();
		return $res;
	}

	function cancelLeaveRequest($data){
		$res = array();
		$val = array();
		
		$val['leaveid'] = $data->leaveid;

		$leaves = new LeavesModel;
		$res['leave'] = $leaves->cancelLeaveRequest($val);
 		$leaves->closeDB();

		return $res;
	}
	
	function updateLeaveRequest($data){
		$res = array();
		$val = array();
		$leavetypedet = array();
		$emaildetails =  array();
		$res['errsentnotif'] = 0;
		$res['errupdateleave'] = 0;
		$reason = $data->leavereason;
		$leavedtls = explode("||", $data->leavedetails);
		$leaveid = $data->leaveid;
		$isUpdate = $data->isUpdate;
		$userid = $data->userid;
		$val['userid'] = $userid;
		$val['leavefrom'] = $data->leavefrom;
		$val['leaveto'] = $data->leaveto;
		$val['leavetype'] = $data->leavetype;
		$val['leaveduration'] = $data->leaveduration;
		$val['leavereason'] = $reason;
		$val['noofdays'] = $data->noofdays;
		$val['leaveid'] = $leaveid;
		$uploadattachement = $data->uploadattachement;
		$fiscalyear = $data->fiscalyear;

		$ees = new EmployeesModel;
		$eedata['eedata'] = $ees->getActiveabaPeopleWithId($userid);
		$reportstoid = $eedata['eedata'][0]['reportstoid'];
		$eedata['reportstodata'] = $ees->getActiveabaPeopleWithId($reportstoid);
		$reportstoindirectid = $eedata['eedata'][0]['reportstoindirectid'];
		$eedata['reportstoindirectdata'] = $ees->getActiveabaPeopleWithId($reportstoindirectid);
		
		$leaves = new LeavesModel;
		$val['attachment'] = "";
		if(!empty($uploadattachement)){
			$docpath = $_SERVER['DOCUMENT_ROOT']."/aces/upload/tmp/".$userid."*.*";
			foreach (glob($docpath) as $filepath) {
				$res['docs'][] = $filepath;
				$res['filelist'][] = basename($filepath);
			}
			$eefilepathfolder = $_SERVER['DOCUMENT_ROOT']."/aces/upload/leave_attachment_files/".$userid;
			if (!file_exists($eefilepathfolder)) {
				mkdir($eefilepathfolder, 0777);
			}
			$filecount = count($res['docs']);
			$arrayfilenames = array();
			for($i=0;$i<$filecount;$i++){
				$filepath = $res['docs'][$i];
				$filename = $res['filelist'][$i];
				if(rename($filepath, $eefilepathfolder."/".$filename)){
					$res['success'] = 'true';
				}else{
					$res['success'] = 'false';
				}
			}
			$filenamestring = '';
			if(count($res['filelist']) != 0){
				$filenamestring = implode("||", $res['filelist']);
			}
			$val['attachment'] = $filenamestring;
		}
		$res['errupdateleave'] = $leaves->updateLeaveRequest($val);
		if($res['errupdateleave']['err'] == 0){
			if($isUpdate == 'false'){
				$dtls['userid'] = $userid;
				$dtls['leaveid'] = $leaveid;
				$res['errupdateleavedtls'] =  $leaves->updateLeaveDetailsStatus($dtls);
				for($i=0;$i<count($leavedtls);$i++){
					$ldtls = explode("::",$leavedtls[$i]);
					$dtls['leavedate'] = $ldtls[0]; // leave date
					$dtls['wap'] = $ldtls[1]; // w - wholeday, a - am, p - pm
					$dtls['points'] = $ldtls[2]; // points 1 or 0.5
					$dtls['remarks'] = $ldtls[3]; // remarks
					$res['leavedetails'][] = $leaves->saveLeaveDetails($dtls);
				}
			}
			$res['leavedtls'] = $leaves->getLeaveRequest($leaveid);
			$res['leaves'] = $leaves->getAllLeaveRequests($val);

			$emaildetails['approvallevel'] = $res['leavedtls']['rows'][0]['approvallevel'];
			$emaildetails['reportstoname'] = $eedata['reportstodata'][0]['eename'];
			$emaildetails['reportstoemail'] = $eedata['reportstodata'][0]['workemail'];
			$emaildetails['reportstoindirectname'] = "";
			$emaildetails['reportstoindirectemail'] = "";
			if(count($eedata['reportstoindirectdata']) > 0 ){
				$emaildetails['reportstoindirectname'] = $eedata['reportstoindirectdata'][0]['eename'];
				$emaildetails['reportstoindirectemail'] = $eedata['reportstoindirectdata'][0]['workemail'];
			}
			
			// $emaildetails['reportstoemail'] = 'vivencia.velasco@abacare.com';
			// $emaildetails['reportstoindirectemail'] = 'vivencia.velasco@abacare.com';

			$emaildetails['requestor'] = $eedata['eedata'][0]['eename'];
			$emaildetails['leaveid'] = $leaveid;
			$emaildetails['leavetype'] = $res['leavedtls']['rows'][0]['leavetypedesc'];
			$emaildetails['leaveduration'] = $res['leavedtls']['rows'][0]['leavedurationdesc'];
			$emaildetails['reason'] = $reason;
			$emaildetails['leavefrom'] = $res['leavedtls']['rows'][0]['leavefromdt'];
			$emaildetails['leaveto'] = $res['leavedtls']['rows'][0]['leavetodt'];
			$emaildetails['noofdays'] = $res['leavedtls']['rows'][0]['noofdays'];
			$emaildetails['leavestatus'] = $res['leavedtls']['rows'][0]['leavestatus'];
			$emaildetails['sesid'] = $res['leavedtls']['rows'][0]['sesid'];
			$emaildetails['approveddate_indirect'] = "";

			$email = new sendMail;
  			$res['errsent'] = $email->sendLeaveRequestChangeDetailsToApprover($emaildetails);
  			$res['errsent1'] = $email->sendLeaveRequestApproval($emaildetails);
  		
		}
		
		$leavetypedet['userid'] = $userid;
  		$leavetypedet['fiscalyear'] = $fiscalyear;
  		$res['leavecredits'] = $leaves->getleaveTypes($leavetypedet);

  		$leaves->closeDB();
  		$ees->closeDB();
  		// $res['test'] = $uploadattachement;
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