<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/database2.php');
	require_once('models/recruitments_model.php');
	require_once('models/careers_model.php');
	require_once('models/employees_model.php');
	require_once('models/salesoffices_model.php');
	require_once('models/sendmail_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function saveApplicant($data){
		$res = array();
		$val = array();

		$val['fname'] = $data->fname;
		$val['mname'] = $data->mname;
		$val['lname'] = $data->lname;
		$val['eaddress'] = $data->eaddress;
		$val['contactno'] = $data->contactno;
		$val['agebracket'] = $data->agebracket;
		$val['gender'] = $data->gender;
		$val['filename'] = $data->filename;
		$val['filesize'] = $data->filesize;
		$val['filetype'] = $data->filetype;
		$val['filename2'] = $data->filename2;
		$val['filesize2'] = $data->filesize2;
		$val['filetype2'] = $data->filetype2;
		$val['careerid'] = $data->careerid;
		$ofcid = $data->ofcid;

		$ofcs = new SalesOfficesModel;
		$hr = $ofcs->getOfcAssignedHR($ofcid)['rows'][0];
		$res['hr'] = $hr;
		$ofcs->closeDB();

		$careers = new CareersModel;
		$career = $careers->getHiringPosition($val['careerid'])['rows'][0];
		$careers->closeDB();
		$res['career'] = $career;

		$recruits = new RecruitmentsModel;
		$res['recruits'] = $recruits->saveApplicant($val);
		$recruits->closeDB();

		// SEND TO abacare HR
		$mail = new PHPMailer;
		$mail->IsSMTP();
        $mail = mailTemplate($mail);
		$mail->FromName = "abacare International Limited";
		$mail->Subject = "NEW APPLICANT FOR ". $career['title'] ." (". $hr['description'] .")";
		$mail->AddAddress('lou.amaya@abacare.com',"loam"); // office HR
		$mail->AddCC('rey.castanares@abacare.com',"reca"); // it
		$mail->AddCC('vivencia.velasco@abacare.com',"vive"); // it
		$mail->IsHTML(true);

		$message = "";
		$head = "<p>Dear <b>" . $hr['eename'] . "</b>,";
		$head .= "<br /><br />A new inquiry to apply for the said position. Please see below details applicant info. To view full applicant info, go to aces recruitment module.</p>";

		$summary = "";
		$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
		$summary .= '<tr><td width="20%">First Name</td><td width="80%">: ' . $val['fname'] . '</td></tr>';
		$summary .= '<tr><td>Last Name</td><td>: '. $val['lname'] .'</td></tr>';
		$summary .= '<tr><td>Middle Name</td><td>: '. $val['mname'] .'</td></tr>';
		$summary .= '<tr><td>Email Address</td><td>: '. $val['eaddress'] .'</td></tr>';
		$summary .= '<tr><td>Contact No</td><td>: '. $val['contactno'] .'</td></tr>';

		switch($val['agebracket']){
			case "1820": $age = "18-20"; break;
			case "2125": $age = "21-25"; break;
			case "2630": $age = "26-30"; break;
			case "3135": $age = "31-35"; break;
			case "3640": $age = "36-40"; break;
			case "4145": $age = "41-45"; break;
			case "4650": $age = "46-50"; break;
			case "5155": $age = "51-55"; break;
			case "5660": $age = "56-60"; break;
			default: $age = ""; break;
		}

		$summary .= '<tr><td>Age</td><td>: '. $age .'</td></tr>';
		$gender = $val['gender'] == "m" ? "Male" : "Female";
		$summary .= '<tr><td>Gender</b></td><td>: ' . $gender . '</b></td></tr></table>';

		$foot = "";
		$foot = "<p>This is a system generated email.<br />";
		$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

		$message = $head . $summary . $foot;

		$mail->Body = $message;

		if(!$mail->Send()){
			$res['sent'] = 0;
		}else{
			$res['sent'] = 1;
		}

		// SEND TO applicant
		$mail = new PHPMailer;
		$mail->IsSMTP();
        $mail = mailTemplate($mail);
		$mail->FromName = "abacare International Limited";
		$mail->Subject = "APPLICATION FOR POSITION OF ". $career['title'] ." (". $hr['description'] .")";
		// $mail->AddAddress($val['eaddress'],$val['fname'] ." ". $val['lname']); // office HR
		$mail->AddAddress('lou.amaya@abacare.com',"reca"); // it
		$mail->AddCC('rey.castanares@abacare.com',"reca"); // it
		$mail->AddCC('vivencia.velasco@abacare.com',"vive"); // it
		$mail->IsHTML(true);

		$head = "";
		$head = "<p>Dear <b>". $val['fname'] ." ". $val['lname'] ." ". $val['eaddress'] ."</b>,";
		$head .= "<br /><br />Thank you for applying online for a position of <b>". $career['title'] ."</b>. Your application has been sent to the recruitment and is being reviewed. We will send you feedback after 48 hours.
			<br /><br />Yours sincerely,
			<br />abacare International Limited</p>";

		$summary = "";

		$foot = "";
		$foot = "<p>This is a system generated email.<br />";
		$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

		$message = "";
		$message = $head . $summary . $foot;

		$mail->Body = $message;

		if(!$mail->Send()){
			$res['sent'] = 0;
		}else{
			$res['sent'] = 1;
		}

		return $res;
	}

	function loadDefault($data){
		$res = array();

		$recruits = new RecruitmentsModel;
		$res['apps'] = $recruits->getApplicants($data);
		$res['interviewers'] = $recruits->getInterviewers();
		$recruits->closeDB();

		$careers = new CareersModel;
		$res['careers'] = $careers->getHiringPositions();
		$careers->closeDB();

		return $res;
	}

	function loadApplicant($data){
		$res = array();
		$sesid = $data->id;

		$recruits = new RecruitmentsModel;
		$res['app'] = $recruits->getApplicantBySesid($sesid);
		$appid = $res['app']['rows'][0]['applicantid'];
		$res['interviewers'] = $recruits->getInterviewers($appid);
		$res['questions'] = $recruits->getQuestions($appid);
		$recruits->closeDB();

		$ees = new EmployeesModel;
		$res['ees'] = $ees->getActiveabaPeople();
		$ees->closeDB();

		return $res;
	}

	function getInterviewer($data){
		$res = array();
		$id = $data->interviewid;

		$recruits = new RecruitmentsModel;
		$res['interviewer'] = $recruits->getInterviewer($id);
		$recruits->closeDB();

		return $res;
	}

	function saveInterviewer($data){
		$res = array();
		$val['userid'] = $data->userid;
		$appid = $data->id;
		$val['applicantid'] = $appid;
		$interviewer = $data->interviewer;
		$val['interviewer'] = $interviewer;
		$val['interviewtype'] = $data->interviewtype;
		$val['interviewdate'] = $data->interviewdate;
		$val['interviewtime'] = $data->interviewtime;
		
		$ees = new EmployeesModel;
		$ee = $ees->getActiveabaPeopleWithId($interviewer)[0];
		$ees->closeDB();

		$res['ee'] = $ee;
		$val['name'] = $ee['fname'] .' '. $ee['lname'];
		$val['jobtitle'] = $ee['designationname'];
		$val['office'] = $ee['salesofficename'];
		$val['email'] = $ee['workemail'];

		$recruits = new RecruitmentsModel;
		$res['app'] = $recruits->saveInterviewer($val);
		$res['interviewers'] = $recruits->getInterviewers($appid);
		$app = $recruits->getApplicantById($appid)['rows'][0];
		$res['applicant'] = $app;
		$recruits->closeDB();

		$careers = new CareersModel;
		$career = $careers->getHiringPosition($app['position'])['rows'][0];
		// $res['career'] = $career;
		$careers->closeDB();

		// SEND TO aba interviewer
		$mail = new PHPMailer;
		$mail->IsSMTP();
        $mail = mailTemplate($mail);
		$mail->FromName = "abacare International Limited";
		$mail->Subject = "APPLICANT INTERVIEW FOR ". $career['title'];
		$mail->AddAddress('lou.amaya@abacare.com',"loam"); // interviewer
		$mail->AddCC('rey.castanares@abacare.com',"reca"); // it
		$mail->AddCC('vivencia.velasco@abacare.com',"vive"); // it
		$mail->IsHTML(true);

		$message = "";
		$head = "<p>Dear <b>" . $val['name'] . "</b>,";
		$head .= "<br /><br />You have been selected to interview below applicant.</p>";

		$summary = "";
		$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
		$summary .= '<tr><td width="20%">First Name</td><td width="80%">: ' . $app['firstname'] . '</td></tr>';
		$summary .= '<tr><td>Last Name</td><td>: '. $app['lastname'] .'</td></tr>';
		$summary .= '<tr><td>Middle Name</td><td>: '. $app['middlename'] .'</td></tr>';
		$summary .= '<tr><td>Email Address</td><td>: '. $app['emailaddress'] .'</td></tr>';
		$summary .= '<tr><td>Contact No</td><td>: '. $app['contactno'] .'</td></tr>';

		switch($app['agebracket']){
			case "1820": $age = "18-20"; break;
			case "2125": $age = "21-25"; break;
			case "2630": $age = "26-30"; break;
			case "3135": $age = "31-35"; break;
			case "3640": $age = "36-40"; break;
			case "4145": $age = "41-45"; break;
			case "4650": $age = "46-50"; break;
			case "5155": $age = "51-55"; break;
			case "5660": $age = "56-60"; break;
			default: $age = ""; break;
		}

		$summary .= '<tr><td>Age</td><td>: '. $age .'</td></tr>';
		$gender = $app['gender'] == "m" ? "Male" : "Female";
		$summary .= '<tr><td>Gender</b></td><td>: ' . $gender . '</b></td></tr>';

		$inttype = "";
		switch($val['interviewtype']){
			case "ac": $inttype = "Audio Call"; break;
			case "vc": $inttype = "Video Call"; break;
			default: $inttype = "One-on-One"; break;
		}
		$summary .= '<tr><td>Interview Type</b></td><td>: ' . $inttype . '</b></td></tr>';
		$summary .= '<tr><td>Interview Date</b></td><td>: ' . formatDate("D d M y", $val['interviewdate']) . '</b></td></tr>';
		$summary .= '<tr><td>Interview Time</b></td><td>: ' . $val['interviewtime'] . '</b></td></tr>';
		$summary .= '</table>';

		$foot = "";
		$foot = "<p>This is a system generated email.<br />";
		$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

		$message = $head . $summary . $foot;

		$mail->Body = $message;

		if(!$mail->Send()){
			$res['sent'] = 0;
		}else{
			$res['sent'] = 1;
		}

		return $res;
	}

	function updateInterviewer($data){
		$res = array();
		$val['userid'] = $data->userid;
		$appid = $data->appid;
		$val['interviewid'] = $data->id;
		$interviewer = $data->interviewer;
		$val['interviewer'] = $interviewer;
		$val['interviewtype'] = $data->interviewtype;
		$val['interviewdate'] = $data->interviewdate;
		$val['interviewtime'] = $data->interviewtime;
		$val['remarks'] = $data->remarks;
		$val['status'] = $data->status;
		
		$ees = new EmployeesModel;
		$ee = $ees->getActiveabaPeopleWithId($interviewer)[0];
		$ees->closeDB();

		$res['ee'] = $ee;
		$val['name'] = $ee['fname'] .' '. $ee['lname'];
		$val['jobtitle'] = $ee['designationname'];
		$val['office'] = $ee['salesofficename'];

		$recruits = new RecruitmentsModel;
		$res['app'] = $recruits->updateInterviewer($val);
		$res['interviewers'] = $recruits->getInterviewers($appid);
		$recruits->closeDB();

		return $res;
	}

	function saveQuestion($data){
		$res = array();
		$userid = $data->userid;
		$val['userid'] = $userid;
		$appid = $data->id;
		$val['applicantid'] = $appid;
		$val['question'] = $data->question;
		
		$ees = new EmployeesModel;
		$ee = $ees->getActiveabaPeopleWithId($userid)[0];
		$ees->closeDB();

		$res['ee'] = $ee;
		$val['name'] = $ee['fname'] .' '. $ee['lname'];

		$recruits = new RecruitmentsModel;
		$questid = $recruits->getNewQuestionId($val);

		$qid = explode("_",$questid['qid']);
		$questionid = ($qid[0] + 1) .'_'. $appid .'_'. $userid;
		$val['questionid'] = $questionid;

		$res['app'] = $recruits->saveQuestion($val);
		$res['questions'] = $recruits->getQuestions($appid);
		$recruits->closeDB();

		return $res;
	}

	function updateQuestion($data){
		$res = array();
		$val['questionid'] = $data->questionid;
		$val['question'] = $data->question;
		$appid = $data->id;

		$recruits = new RecruitmentsModel;
		$res['app'] = $recruits->updateQuestion($val);
		$res['questions'] = $recruits->getQuestions($appid);
		$recruits->closeDB();

		return $res;
	}

	function getQuestion($data){
		$res = array();
		$id = $data->questionid;

		$recruits = new RecruitmentsModel;
		$res['question'] = $recruits->getQuestion($id);
		$recruits->closeDB();

		return $res;
	}

	function updateAppStatus($data){
		$res = array();
		$appid = $data->id;
		$val['id'] = $appid;
		$status = $data->status;
		$val['status'] = $status;
		$val['userid'] = $data->userid;

		$recruits = new RecruitmentsModel;
		$res['app'] = $recruits->updateAppStatus($val);
		$app = $recruits->getApplicantById($appid)['rows'][0];
		$recruits->closeDB();

		if($status == -1){
			$careers = new CareersModel;
			$career = $careers->getHiringPosition($app['position'])['rows'][0];
			$careers->closeDB();

			$ofcs = new SalesOfficesModel;
			$ofc = $ofcs->getOfcAssignedHR($career['office'])['rows'][0];
			$ofcs->closeDB();

			// SEND TO applicant
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "APPLICATION FOR POSITION ". $career['title'] ." (". $ofc['description'] .")";
			$mail->AddAddress('lou.amaya@abacare.com',"loam"); // interviewer
			$mail->AddCC('rey.castanares@abacare.com',"reca"); // it
			$mail->AddCC('vivencia.velasco@abacare.com',"vive"); // it
			$mail->IsHTML(true);

			$head = "";
			$head = "<p>Dear <b>". $app['firstname'] ." ". $app['lastname'] ."</b>,";
			$head .= "<br /><br />Thank you for applying online for a position of <b>". $career['title'] ."</b>. We regret to inform you that upon our review, we are unable to approve your application at this time.
				<br /><br />Yours sincerely,
				<br />abacare International Limited</p>";

			$summary = "";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = "";
			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}else{
				$res['sent'] = 1;
			}
		}

		return $res;
	}

	function updateBulkAppStatus($data){
		$res = array();
		$status = $data->status;
		$userid = $data->userid;
		$ids = $data->ids;

		$recruits = new RecruitmentsModel;
		
		for($i=0;$i<COUNT($ids);$i++){
			$val = array();
			$appid = $ids[$i];
			$val['id'] = $appid;
			$val['status'] = $status;
			$val['userid'] = $userid;
			$res['app'][] = $recruits->updateAppStatus($val);
			$app = $recruits->getApplicantById($appid)['rows'][0];

			if($status == -1){
				$careers = new CareersModel;
				$career = $careers->getHiringPosition($app['position'])['rows'][0];
				$careers->closeDB();

				$ofcs = new SalesOfficesModel;
				$ofc = $ofcs->getOfcAssignedHR($career['office'])['rows'][0];
				$ofcs->closeDB();

				// SEND TO applicant
				$mail = new PHPMailer;
				$mail->IsSMTP();
				$mail = mailTemplate($mail);
				$mail->FromName = "abacare International Limited";
				$mail->Subject = "APPLICATION FOR POSITION ". $career['title'] ." (". $ofc['description'] .")";
				$mail->AddAddress('lou.amaya@abacare.com',"loam"); // interviewer
				$mail->AddCC('rey.castanares@abacare.com',"reca"); // it
				$mail->AddCC('vivencia.velasco@abacare.com',"vive"); // it
				$mail->IsHTML(true);

				$head = "";
				$head = "<p>Dear <b>". $app['firstname'] ." ". $app['lastname'] ."</b>,";
				$head .= "<br /><br />Thank you for applying online for a position of <b>". $career['title'] ."</b>. We regret to inform you that upon our review, we are unable to approve your application at this time.
					<br /><br />Yours sincerely,
					<br />abacare International Limited</p>";
					
				$summary = "";

				$foot = "";
				$foot = "<p>This is a system generated email.<br />";
				$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

				$message = "";
				$message = $head . $summary . $foot;

				$mail->Body = $message;

				if(!$mail->Send()){
					$res['sent'] = 0;
				}else{
					$res['sent'] = 1;
				}
			}
		}
		
		$recruits->closeDB();

		$res['data'] = $data;

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