<?php
	Class sendMail{

		function __construct(){}

		public function sendRequestAbbreviation($data){
			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "abacare abbreviation glossary request from " . $data['abaUser'];
			$mail->AddAddress("secchaopshk@abacare.com"); // KARIN WONG
			$mail->AddBCC("cherry.aca-ac@abacare.com"); // CHERRY MAE ACA-AC
			// $mail->AddCC("rey.castanares@abacare.com");
			$mail->IsHTML(true);

			$message = "";
			$head = "<p>Dear Ms. Karin WONG,</p>";
			$head .= "<p>We sent you a notification that <b>".$data['abaUser']."</b> requested a new ".$data['typedesc']." for your approval. Please see ".$data['typedesc']." details below.<br /> You can automatically approve/disapprove the abbreviation request using the links provided below as [Approve] [Disapprove]. Alternatively, if you wish to change the abbreviation, please login to <a href='http://www.abacare.com/abvt/admin/login.php'> abbreviation glossary contact list</a> web app. </p>";

			$summary = "";
			$summary .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
				$summary .= "<tr><th width='30%'>&nbsp;</td><td width='70%'>&nbsp;</td></tr>";
				$summary .= "<tr><td>Type </td><td> : " . $data['typedesc'] . "</td></tr>";
				$summary .= "<tr><td>Abbreviation </td><td> : " . $data['abvt'] . "</td></tr>";
				$summary .= "<tr><td>Word </td><td> : " . $data['word'] . "</td></tr>";
				$summary .= "<tr><td>CN Word </td><td> : " . $data['cnword']. "</td></tr>";
				if(!empty($data['description'])){
					$summary .= "<tr><td>Description</td><td> : " . $data['description'] . "</td></tr>";
				}
				$summary .= "<tr><td>Category</td><td> : " . $data['categorydesc'] . "</td></tr>";
				$summary .= "<tr><td>Requested by </td><td> : " . $data['abaUser'] . "</td></tr>";
				$summary .= "<tr><td>Requested Date </td><td> : " . dtFormat($data['today']) . "</td></tr>";
				$summary .= "<tr><td>Remarks </td><td> : " . $data['remarks'] . "</td></tr>";
			$summary .= "</table>";
			$summary .= "<p><a href='".base_URL."abbreviations_request_approval.php?id=".$data['id']."&approver=kwon&approve=1'>[ Approve ]</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
			$summary .= "<a href='".base_URL."abbreviations_request_approval.php?id=".$data['id']."&approver=kwon&approve=-2'>[ Disapprove ]</a></p>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		public function sendNotificationAbbreviation($data){
			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "abacare abbreviation glossary request sent";
			$mail->AddAddress($data['requestoremail']);
			$mail->IsHTML(true);

			$message = "";
			$head = "<p>Dear ".$data['requestorname'].",</p>";
			$head .= "<p>We sent you a notification that you just requested a new ".$data['typedesc']." for approval. Please see your requested ".$data['typedesc']." details below. </p>";

			$summary = "";
			$summary .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
				$summary .= "<tr><th width='30%'>&nbsp;</td><td width='70%'>&nbsp;</td></tr>";
				$summary .= "<tr><td>Type </td><td> : " . $data['typedesc'] . "</td></tr>";
				$summary .= "<tr><td>Abbreviation </td><td> : " . $data['abvt'] . "</td></tr>";
				$summary .= "<tr><td>Word </td><td> : " . $data['word'] . "</td></tr>";
				$summary .= "<tr><td>CN Word </td><td> : " . $data['cnword']. "</td></tr>";
				if(!empty($data['description'])){
					$summary .= "<tr><td>Description</td><td> : " . $data['description'] . "</td></tr>";
				}
				$summary .= "<tr><td>Category</td><td> : " . $data['categorydesc'] . "</td></tr>";
				$summary .= "<tr><td>Requested by </td><td> : " . $data['abaUser'] . "</td></tr>";
				$summary .= "<tr><td>Requested Date </td><td> : " . dtFormat($data['today']) . "</td></tr>";
				$summary .= "<tr><td>Remarks </td><td> : " . $data['remarks'] . "</td></tr>";
			$summary .= "</table>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		public function sendApprovedAbbreviation($data){
			$abvt = $data['data'][0];
			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			
			$mail->AddAddress($data['requestoremail']);
			$mail->IsHTML(true);

			$message = "";
			$head = "<p>Dear ".$data['requestorname'].",</p>";
			switch($data['status']){
				case 1:
						$msg = "We sent you a notification that your request is approved and is now available in abbreviation glossary list.";
						$type = "approved";
					break;
				default:
						$msg = "We are very sorry that your request has denied by your approver " . $abvt['approvedby'] . ".";
						$type = "disapproved";
					break;
			}
			$head .= "<p>" . $msg . " Please see below of your requested " . $abvt['typename'] . ".</p>";

			$summary = "";
			$summary .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
				$summary .= "<tr><th width='30%'>&nbsp;</td><td width='70%'>&nbsp;</td></tr>";
				$summary .= "<tr><td>Type </td><td> : " . $abvt['typename'] . "</td></tr>";
				$summary .= "<tr><td>Abbreviation </td><td> : " . $abvt['abvt'] . "</td></tr>";
				$summary .= "<tr><td>Word </td><td> : " . $abvt['word'] . "</td></tr>";
				$summary .= "<tr><td>CN Word </td><td> : " . $abvt['cnword']. "</td></tr>";
				if(!empty($abvt['description'])){
					$summary .= "<tr><td>Description</td><td> : " . $abvt['description'] . "</td></tr>";
				}
				$summary .= "<tr><td>Category</td><td> : " . $abvt['categorydesc'] . "</td></tr>";
				$summary .= "<tr><td>Requested by </td><td> : " . $abvt['createdby'] . "</td></tr>";
				$summary .= "<tr><td>Requested Date </td><td> : " . dtFormat($abvt['createddate']) . "</td></tr>";
				switch($data['status']){
					case 1:
							$summary .= "<tr><td>Approved by </td><td> : pmhe</td></tr>";
							$summary .= "<tr><td>Approved Date </td><td> : " . dtFormat(TODAY) . "</td></tr>";
						break;
					default:
							$summary .= "<tr><td>Denied by </td><td> : pmhe</td></tr>";
							$summary .= "<tr><td>Denied Date </td><td> : " . dtFormat(TODAY) . "</td></tr>";
						break;
				}
				$summary .= "<tr><td>Remarks </td><td> : " . $abvt['remarks'] . "</td></tr>";
			$summary .= "</table>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			$mail->Subject = "abacare abbreviation glossary request " . strtoupper($type);
			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		function sendRequestFolder($data){
			$res = array();
			$res['sendtoapprover'] = 1;

			$settings = new SPFolderSMTP;
			$smtp = $settings->getSMTPSetup();

			foreach($smtp as $val){
			  $approver = $val['approverini'];
		      $name = $val['approvername'];
		      $email = $val['approveremail'];
		      $server = $val['smtpserver'];
		      $auth  = $val['smtpauth'];
		      $port = $val['smtpport'];
		      $user = $val['smtpusername'];
		      $pass = $val['smtppassword'];
		      $sendas = $val['sendasemail'];
		      $subject = $val['subject'];

		      //cc_email
		      $cc = explode(';',$val['ccemail']);
      		  $cc = array_filter($cc);
      		  $countcc = count($cc);
			}

			//SEND NEW REQUEST TO APPROVER
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = $server;
			$mail->Port = $port;
			$mail->Username = $user;
			$mail->Password = $pass;
			$mail->SMTPSecure = $auth;
			$mail->SMTPAuth = true;
			$mail->isHTML(true);
			$urgent = isset($data['urgent']) ? " !URGENT " : "";
			$mail->Subject = $subject . ' - ' . $data['fldname'] . $urgent;
			$mail->From = $sendas;
			$mail->FromName = 'Abacare HelpDesk';
			$mail->Priority = isset($data['urgent']) ? 1 : 0;
			$mail->AddAddress($email, $name);
			// $mail->AddAddress('rey.castanares@abacare.com');

			for($i=0; $i<$countcc; $i++){
				$mail->AddCC($cc[$i]);
			}
			
			$mail->isHTML(true);
			
			$message = "";
			
			$head = "<p>Dear " . $name . ",</p>";
			$head .= "<p>Please see below the details of the request for SharePoint Folder creation: </p>";

			$summary ="";
			$summary = "<b>Request Summary: </b>";
			$summary .= "<table cellpadding='0' cellspacing='0' border='0' width='0' width='100%'";
			$summary .= "<tr><th width='20%'>&nbsp;</td><td width='50%'>&nbsp;</td></tr>";
				$summary .= "<tr><td>Date Requested </td><td> : " . dtFormat($data['reqdate']) . "</td></tr>";
				$summary .= "<tr><td>Requested By </td><td> : " . $data['reqby'] . "</td></tr>";
				$summary .= "<tr><td>Requester's Full Name </td><td> : " . $data['fname'] . " " . $data['lname'] . "</td></tr>";
				$summary .= "<tr><td>Office</td><td> : " . $data['office'] . "</td></tr>";

				$category = new folderCategories;
				$categories = $category->getCategories($data['cat']);

				foreach($categories as $val){
					$cat = $val['description'];
				}

				$summary .= "<tr><td>Category </td><td> : " . $cat . "</td></tr>";
				$summary .= "<tr><td>Folder Name </td><td> : " . $data['fldname'] . "</td></tr>";
				$summary .= "<tr><td>Folder Location </td><td> : " . $data['fldloc'] . "</td></tr>";
				$summary .= "<tr><td>Remarks </td><td> : " . $data['remarks'] . "</td></tr>";
				
			$summary .= "</table>";
			$summary .= "<p>To take action on this request, kindly click on the <b><i>Approved</i></b> button below to approve this request or <b><i>Disapprove</i></b> to disapprove this request. You can also view the details by clicking on <b><i>View Request Details</i></b>.</p>";

			$button ="";
			$button .= "<p><a href='". base_URL . "sp_folder_approval.php?approval=1&id=". $data['id'] . "&approve=2'>[Approve] </a>";
			$button .= " | <a href='". base_URL . "sp_folder_approval.php?approval=1&id=". $data['id'] . "&approve=3'>[Disapprove] </a>";
			$button .= " | <a href='". base_URL . "sp_folder_approval.php?view=1&id=". $data['id'] . "'> [View Request Details]</a></p>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary . $button. $foot;
			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sendtoapprover'] = 0;
				goto exitHere;
			}

			exitHere:
			return $res;

		}

		function sendNotificationFolder($data){
			$res = array();
			$res['sendtorequester'] = 1;

			$settings = new SPFolderSMTP;
			$smtp = $settings->getSMTPSetup();

			foreach($smtp as $val){
			  $approver = $val['approverini'];
		      $name = $val['approvername'];
		      $email = $val['approveremail'];
		      $server = $val['smtpserver'];
		      $auth  = $val['smtpauth'];
		      $port = $val['smtpport'];
		      $username = $val['smtpusername'];
		      $password = $val['smtppassword'];
		      $sendas = $val['sendasemail'];
		      $subject = $val['subject'];
			}

			//SEND NEW REQUEST TO APPROVER
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = $server;
			$mail->Port = $port;
			$mail->Username = $username;
			$mail->Password = $password;
			$mail->SMTPSecure = $auth;
			$mail->SMTPAuth = true;
			$mail->isHTML(true);
			$mail->Subject = 'REQUEST SENT: ' . $subject . ' - ' . $data['fldname'];
			$mail->From = $sendas;
			$mail->FromName = 'Abacare HelpDesk';

			//GET USER DETAILS
			$user = new abaPeople;
			$users = $user->getabaPeopleByIni($data['reqby']);

			foreach($users as $val){
				$fullname = $val['fname'] . ' ' . $val['lname'];
				$useremail = $val['emailaddress'];
			}

			$mail->AddAddress($useremail);
			
			$message = "";
			
			$head = "<p>Dear " . $fullname . ",</p>";

			$summary = "<p>Your request to create the folder in sharepoint has been sent. Please wait for an approval from the chairman before taking action. </p>";
			$summary .= "Folder Name : <b>" . $data['fldname'] . '</b>';
			$summary .= "<br />Folder Location : <b>" . $data['fldloc'] . '</b>';
			$summary .= "<p>If you have any questions, feel free to contact the IT Department <a href='mailto:it@abacare.com'>it@abacare.com</a>.</p>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary .  $foot;
			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sendtorequester'] = 0;
				goto exitHere;
			}

			exitHere:
			return $res;

		}

		function sendApprovedFolder($data){
			$reqby = $data['reqby'];
			$id = $data['id'];
			$res = array();
			$res['sendtohelpdesk'] = 1;
			$res['sendtorequester'] = 1;

			// GET SMTP DETAILS
			$smtp = $data['smtp'];

			foreach($smtp as $val){
			  $approver = $val['approverini'];
		      $name = $val['approvername'];
		      $email = $val['approveremail'];
		      $server = $val['smtpserver'];
		      $auth  = $val['smtpauth'];
		      $port = $val['smtpport'];
		      $username = $val['smtpusername'];
		      $password = $val['smtppassword'];
		      $sendas = $val['sendasemail'];
		      $subject = $val['subject'];

		      //cc_email
		      $cc = explode(';',$val['ccemail']);
      		  $cc = array_filter($cc);
      		  $countcc = count($cc);
			}

			//GET USER DETAILS
			$users = $data['users'];

			foreach($users as $val){
				$fullname = $val['fname'] . ' ' . $val['lname'];
				$useremail = $val['emailaddress'];
			}

			//GET REQUEST DETAILS
			$request = new SPFolders;
			$requests = $request->getRequestData($id);

			foreach($requests as $val){
				$dtrequested = $val['dtrequested'];
			  	$fldname = $val['fldname'];
			  	$fldloc = $val['fldlocation'];
			  	$remarks = $val['remarks'];
			  	$approvedby = $val['approvedby'];
			  	$approveddate = $val['approveddate'];
			  	$approvercomments = $val['approvercomments'];
			}

			//SEND NEW REQUEST TO HELPDESK			
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = $server;
			$mail->Port = $port;
			$mail->Username = $username;
			$mail->Password = $password;
			$mail->SMTPSecure = $auth;
			$mail->SMTPAuth = true;
			$mail->isHTML(true);
			$mail->Subject = 'APPROVED: ' . $subject . ' - ' . $fldname;
			$mail->From = $sendas;
			$mail->FromName = 'Abacare HelpDesk';
			$mail->AddAddress($useremail, $fullname);
			$mail->AddReplyTo($useremail, $fullname);

			for($i=0; $i<$countcc; $i++){
				$mail->AddCC($cc[$i]);
			}

			// send to helpdesk
			$mail->AddAddress("helpdesk@abacare.com");
			
			$message = "";
			
			$head = "<p>Dear " . $fullname . ",</p>";
			$head .= "<p>Your request to create the folder <b> ". $fldname . "</b> in <b>" .
						$fldloc . "</b> has been approved. </p>";

			$summary ="";
			$summary = "<b>Request Summary: </b>";
			$summary .= "<table cellpadding='0' cellspacing='0' border='0' width='0' width='100%'";
			$summary .= "<tr><th width='20%'>&nbsp;</td><td width='50%'>&nbsp;</td></tr>";
				$summary .= "<tr><td>Date Requested </td><td> : " . dtFormat($dtrequested) . "</td></tr>";
				$summary .= "<tr><td>Folder Name </td><td> : " . $fldname . "</td></tr>";
				$summary .= "<tr><td>Folder Location </td><td> : " . $fldloc . "</td></tr>";
				$summary .= "<tr><td>Remarks </td><td> : " . $remarks . "</td></tr>";
				$summary .= "<tr><td>Approved By </td><td> : " . $approvedby . "</td></tr>";
				$summary .= "<tr><td>Approved Date </td><td> : " . dtFormat($approveddate) . "</td></tr>";
				$summary .= "<tr><td>Approver's Comments </td><td> : " . $approvercomments . "</td></tr>";
			$summary .= "</table>";
			$summary .= "<p>If you have any questions, please contact the IT Department(<a href='mailto:it@abacare.com'>it@abacare.com</a>) </p>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary .  $foot;
			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sendtohelpdesk'] = 0;
				$res['sendtorequester'] = 0;
				$mail->ErrorInfo;
				goto exitHere;
			}

			exitHere:
			return $res;

		}

		function sendDisapprovedFolder($data){
			$reqby = $data['reqby'];
			$id = $data['id'];
			$res = array();
			$res['sendtohelpdesk'] = 1;
			$res['sendtorequester'] = 1;

			// GET SMTP DETAILS
			$smtp = $data['smtp'];

			foreach($smtp as $val){
			  $approver = $val['approverini'];
		      $name = $val['approvername'];
		      $email = $val['approveremail'];
		      $server = $val['smtpserver'];
		      $auth  = $val['smtpauth'];
		      $port = $val['smtpport'];
		      $username = $val['smtpusername'];
		      $password = $val['smtppassword'];
		      $sendas = $val['sendasemail'];
		      $subject = $val['subject'];
			}

			//GET USER DETAILS
			$users = $data['users'];

			foreach($users as $val){
				$fullname = $val['fname'] . ' ' . $val['lname'];
				$useremail = $val['emailaddress'];
			}

			//GET REQUEST DETAILS
			$request = new SPFolders;
			$requests = $request->getRequestData($id);

			foreach($requests as $val){
				$dtrequested = $val['dtrequested'];
			  	$fldname = $val['fldname'];
			  	$fldloc = $val['fldlocation'];
			  	$remarks = $val['remarks'];
			  	$approvedby = $val['approvedby'];
			  	$approveddate = $val['approveddate'];
			  	$approvercomments = $val['approvercomments'];
			}

			//SEND NEW REQUEST TO HELPDESK
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = $server;
			$mail->Port = $port;
			$mail->Username = $username;
			$mail->Password = $password;
			$mail->SMTPSecure = $auth;
			$mail->SMTPAuth = true;
			$mail->isHTML(true);
			$mail->Subject = 'DISAPPROVED: ' . $subject . ' - ' . $fldname;
			$mail->From = $sendas;
			$mail->FromName = 'Abacare HelpDesk';
			$mail->AddAddress($useremail, $fullname);
			// $mail->AddAddress('rey.castanares@abacare.com');

			$message = "";
			
			$head = "<p>Dear " . $fullname . ",</p>";
			$head .= "<p>Your request to create the folder <b> ". $fldname . "</b> in <b>" .
						$fldloc . "</b> was disapproved. </p>";

			$summary ="";
			$summary = "<b>Request Summary: </b>";
			$summary .= "<table cellpadding='0' cellspacing='0' border='0' width='0' width='100%'";
			$summary .= "<tr><th width='20%'>&nbsp;</td><td width='50%'>&nbsp;</td></tr>";
				$summary .= "<tr><td>Date Requested </td><td> : " . dtFormat($dtrequested) . "</td></tr>";
				$summary .= "<tr><td>Folder Name </td><td> : " . $fldname . "</td></tr>";
				$summary .= "<tr><td>Folder Location </td><td> : " . $fldloc . "</td></tr>";
				$summary .= "<tr><td>Remarks </td><td> : " . $remarks . "</td></tr>";
				$summary .= "<tr><td>Approved By </td><td> : " . $approvedby . "</td></tr>";
				$summary .= "<tr><td>Approved Date </td><td> : " . dtFormat($approveddate) . "</td></tr>";
				$summary .= "<tr><td>Approver's Comments </td><td> : " . $approvercomments . "</td></tr>";
			$summary .= "</table>";
			$summary .= "<p>If you have any questions, please contact the IT Department(<a href='mailto:it@abacare.com'>it@abacare.com</a>) </p>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary .  $foot;
			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sendtohelpdesk'] = 0;
				$res['sendtorequester'] = 0;
				$mail->ErrorInfo;
				goto exitHere;
			}

			exitHere:
			return $res;
		}

		public function sendNewPassword($data){
			$email = $data['email'];
			$newpw = strtolower($data['newpassword']);
			$eename = $data['eename'];

			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "abacare abbreviation glossary forgot password";
			$mail->AddAddress($email); // user email address
			$mail->IsHTML(true);

			$message = "";
			$head = "<p>Dear " . $eename . ",</p>";
			$head .= "<p>We sent you a notification that your password is now changed. You can now login using the password given below details.</p>";

			$summary = "";
			$summary .= "<p>New Password: " . $newpw . "</p>";

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		public function sendMkgRequest($data){
			$requestno = $data['reqno'];
			$requestor = $data['eename'];
			$requesteddate = $data['reqdt'];
			$requesttype = $data['reqtypedesc'];
			$duedate = $data['duedt'];
			$requestdtls = $data['description'];
			$remarks = $data['remarks'];

			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "New Marketing Request from " . $requestor;
			$mail->AddAddress("kevin.degamo@abacare.com"); // mkt executive
			// $mail->AddAddress("rey.castanares@abacare.com"); // mkt executive
			$mail->AddBCC("rey.castanares@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>NEW MARKETING REQUEST</h2>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Requestor</td><td width="80%">: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td width="20%">Office</td><td width="80%">: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td>Request No</td><td>: ' . $requestno . '</td></tr>';
			$summary .= '<tr><td>Requested Date</td><td>: ' . $requesteddate . '</td></tr>';
			$summary .= '<tr><td>Type</td><td>: ' . $requesttype . '</td></tr>';
			$summary .= '<tr><td>Due Date</td><td>: ' . $duedate . '</td></tr>';
			$summary .= '<tr><td>Details</td><td>: ' . $requestdtls . '</td></tr>';
			$summary .= '<tr><td>Remarks</td><td>: ' . $remarks . '</td></tr></table>';

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		public function notifyMkgRequestor($data){
			$requestno = $data['reqno'];
			$requestor = $data['eename'];
			$requestoremail = $data['emailaddress'];
			$requesteddate = $data['reqdt'];
			$requesttype = $data['reqtypedesc'];
			$duedate = $data['duedt'];
			$requestdtls = $data['description'];
			$remarks = $data['remarks'];

			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "New Marketing Request Notification (" . $requestno . ")";
			$mail->AddAddress($requestoremail,$requestor); // requestor
			$mail->AddBCC("rey.castanares@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>MARKETING REQUEST</h2>";

			$summary = "";
			$summary .= '<p>Dear ' . $requestor . ',</p>';
			$summary .= '<p>This is to notify you that your requested below details is successfully sent to the marketing for validation.</p>';
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td>Request No</td><td>: ' . $requestno . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td>Requested Date</td><td>: ' . $requesteddate . '</td></tr>';
			$summary .= '<tr><td>Type</td><td>: ' . $requesttype . '</td></tr>';
			$summary .= '<tr><td>Due Date</td><td>: ' . $duedate . '</td></tr>';
			$summary .= '<tr><td>Details</td><td>: ' . $requestdtls . '</td></tr>';
			$summary .= '<tr><td>Remarks</td><td>: ' . $remarks . '</td></tr></table>';

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		public function sendMkgRequestor($data){
			$reqno = $data['reqno'];
			$requestor = $data['eename'];
			$requestoremail = $data['emailaddress'];
			$requesteddate = $data['reqdt'];
			$requesttype = $data['reqtypedesc'];
			$duedate = $data['duedt'];
			$completiondt = $data['completiondt'];
			$requestdtls = $data['description'];
			$remarks = $data['remarks'];
			$statusdesc = $data['statusdesc'];
			$sdate = $data['sdt'];
			$cdate = $data['completeddt'];
			$logs = $data['logs'];
			$assigneename = $data['assigneename'];
			$approveddt = $data['approveddt'];

			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Marketing Request update (" . $reqno . ")";
			$mail->AddAddress($requestoremail,$requestor); // requestor
			// $mail->AddBCC("rey.castanares@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>MARKETING REQUEST STATUS UPDATE</h2>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Request No</td><td width="80%">: ' . $reqno . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td>Requested Date</td><td>: ' . $requesteddate . '</td></tr>';
			$summary .= '<tr><td>Type</td><td>: ' . $requesttype . '</td></tr>';
			$summary .= '<tr><td>Due Date</td><td>: ' . $duedate . '</td></tr>';
			$summary .= '<tr><td>Details</td><td>: ' . $requestdtls . '</td></tr>';
			$summary .= '<tr><td>Remarks</td><td>: ' . $remarks . '</td></tr>';
			$summary .= '<tr><td>Approved Date</td><td>: ' . $approveddt . '</td></tr>';
			$summary .= '<tr><td><b>Status</b></td><td>: <b>' . $statusdesc . '</b></td></tr>';
			$summary .= '<tr><td><b>Assignee</b></td><td>: <b>' . $assigneename . '</b></td></tr>';
			$summary .= '<tr><td><b>Start Date</b></td><td>: <b>' . $sdate . '</b></td></tr>';
			$summary .= '<tr><td><b>Completion Date</b></td><td>: <b>' . $completiondt . '</b></td></tr></table><br />';

			$summary .= '<table cellpadding="1" cellspacing="1" border="1" width="100%">';
			$summary .= '<tr>';
				$summary .= '<th width="10%">Status</th>';
				$summary .= '<th width="20%">Date</th>';
				$summary .= '<th width="30%">By</th>';
				$summary .= '<th width="40%">Remarks / Reason</th>';
			$summary .= '</tr>';
			for($i=0;$i<count($logs);$i++){
				$summary .= '<tr>';
					$summary .= '<td align="center">'.$logs[$i]['statusdesc'].'</td>';
					$summary .= '<td align="center">'.$logs[$i]['creadt'].'</td>';
					$summary .= '<td>'.$logs[$i]['eename'].'</td>';
					$summary .= '<td>'.$logs[$i]['reason'].'</td>';
				$summary .= '</tr>';
			}
			$summary .= '</table>';

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		public function sendMkgGMApproval($val){
			$data = $val['request'];
			$reqno = $data['reqno'];
			$requestor = $data['eename'];
			$requestoremail = $data['emailaddress'];
			$requesteddate = $data['reqdt'];
			$requesttype = $data['reqtypedesc'];
			$duedate = $data['duedt'];
			$completiondt = $data['completiondt'];
			$requestdtls = $data['description'];
			$remarks = $data['remarks'];
			$sesid = $data['sesid'];
			$salesoffice = $val['salesoffice']['rows'][0];
			$approver = $salesoffice['gmname'];
			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Marketing Request for approval (" . $reqno . ")";
			// $mail->AddAddress($salesoffice['gmemail'],$salesoffice['gmname']); // approver
			$mail->AddAddress("rey.castanares@abacare.com","reca"); // requestor
			// $mail->AddBCC("rey.castanares@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>MARKETING REQUEST FOR APPROVAL</h2>";
			$head .= "<p>Dear <b>" . $approver . "</b>,</p>";
			$head .= "<p>We sent you a marketing request notification for your approval. Please see below marketing request details.</p>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Request No</td><td width="80%">: ' . $reqno . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td>Requested Date</td><td>: ' . $requesteddate . '</td></tr>';
			$summary .= '<tr><td>Type</td><td>: ' . $requesttype . '</td></tr>';
			$summary .= '<tr><td>Due Date</td><td>: ' . $duedate . '</td></tr>';
			$summary .= '<tr><td>Details</td><td>: ' . $requestdtls . '</td></tr>';
			$summary .= '<tr><td>Remarks</td><td>: ' . $remarks . '</td></tr></table>';

			$summary .= '<p><a href="' . base_URL . '/mkgrequest_forapproval.php?sesid='. $sesid .'&appr=1" target="_blank">[ Approve ]</a> <a href="'. $sesid .'&appr=0" target="_blank">[ Disapprove ]</a></p>';

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}
			return $res;
		}

		public function sendApprovedReq($data){
			$req = $data[0];
			$gmname = $req['gmname'];
			$reqno = $req['reqno'];
			$requestor = $req['eename'];
			$requestoremail = $req['emailaddress'];
			$requesteddate = $req['reqdt'];
			$requesttype = $req['reqtypedesc'];
			$duedate = $req['duedt'];
			$approveddt = $req['approveddt'];
			$completiondt = $req['completiondt'];
			$requestdtls = $req['description'];
			$remarks = $req['remarks'];
			$sesid = $req['sesid'];
			$res = array();
			$res['sent'] = 1;
			$msg = "";

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Marketing Request (" . $reqno . ")";
			$mail->AddAddress($requestoremail,$requestor); // requestor
			// $mail->AddBCC("rey.castanares@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>MARKETING REQUEST STATUS UPDATE</h2>";
			$head .= "<p>Dear <b>" . $requestor . "</b>,</p>";
			$head .= "<p>We notify you that your request is successfully approved by your GM <b>". $gmname ."</b>. Please see below your marketing request details.</p>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Request No</td><td width="80%">: ' . $reqno . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td>Requested Date</td><td>: ' . $requesteddate . '</td></tr>';
			$summary .= '<tr><td>Type</td><td>: ' . $requesttype . '</td></tr>';
			$summary .= '<tr><td>Due Date</td><td>: ' . $duedate . '</td></tr>';
			$summary .= '<tr><td>Details</td><td>: ' . $requestdtls . '</td></tr>';
			$summary .= '<tr><td>Remarks</td><td>: ' . $remarks . '</td></tr>';
			$summary .= '<tr><td><b>Approved Date</b></td><td>: <b>' . $approveddt . '</b></td></tr></table>';

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}
			return $res;
		}

		public function sendDisapprovedReq($data){
			$req = $data[0];
			$gmname = $req['gmname'];
			$reqno = $req['reqno'];
			$requestor = $req['eename'];
			$requestoremail = $req['emailaddress'];
			$requesteddate = $req['reqdt'];
			$requesttype = $req['reqtypedesc'];
			$duedate = $req['duedt'];
			$disapproveddt = $req['disapproveddt'];
			$completiondt = $req['completiondt'];
			$requestdtls = $req['description'];
			$remarks = $req['remarks'];
			$sesid = $req['sesid'];
			$res = array();
			$res['sent'] = 1;
			$msg = "";

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Marketing Request (" . $reqno . ")";
			$mail->AddAddress($requestoremail,$requestor); // requestor
			// $mail->AddBCC("rey.castanares@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>MARKETING REQUEST STATUS UPDATE</h2>";
			$head .= "<p>Dear <b>" . $requestor . "</b>,</p>";
			$head .= "<p>We're sorry to inform you that your request was disapproved by your GM <b>". $gmname ."</b>. Please see below your marketing request details.</p>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Request No</td><td width="80%">: ' . $reqno . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: ' . $requestor . '</td></tr>';
			$summary .= '<tr><td>Requested Date</td><td>: ' . $requesteddate . '</td></tr>';
			$summary .= '<tr><td>Type</td><td>: ' . $requesttype . '</td></tr>';
			$summary .= '<tr><td>Due Date</td><td>: ' . $duedate . '</td></tr>';
			$summary .= '<tr><td>Details</td><td>: ' . $requestdtls . '</td></tr>';
			$summary .= '<tr><td>Remarks</td><td>: ' . $remarks . '</td></tr>';
			$summary .= '<tr><td><b>Approved Date</b></td><td>: <b>' . $disapproveddt . '</b></td></tr></table>';

			$foot = "";
			$foot = "<p>This is a system generated email.<br />";
			$foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}
			return $res;
		}

		public function sendLeaveRequestApproval($data){
			$res = array();
			$approvallevel = $data['approvallevel'];
			$reportstoname = $data['reportstoname'];
			$leaveid = $data['leaveid'];
			$requestor = $data['requestor'];
			$reportstoemail = $data['reportstoemail'];
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavefrom = $data['leavefrom'];
			$leaveto = $data['leaveto'];
			$reason = $data['reason'];
			$noofdays = $data['noofdays'];
			$sesid = $data['sesid'];
			$approveddate_indirect = $data['approveddate_indirect'] == "" ? null : formatDate("D d M Y",$data['approveddate_indirect']);
			// $attachment = $data['attachment'];
			$reportstoindirectname = $data['reportstoindirectname'] == "" ? null : $data['reportstoindirectname'];
			$reportstoindirectemail = $data['reportstoindirectemail'] == "" ? null : $data['reportstoindirectemail'];
			$emailto = "";
			$abaname = "";
			$cceemails = $data['ccemails'];
			$leavebalance = $data['leavebalance'];

			if($approvallevel == 1){
				$emailto = $reportstoindirectemail;
				$abaname = $reportstoindirectname;
				$notifyto = "<p>A leave request has been applied by <b>".$requestor."</b> for your approval. <br /> Please see details below.</p>";
				if($reportstoindirectemail == null || $reportstoindirectemail == ""){
					$emailto = $reportstoemail;
					$abaname = $reportstoname;
				}	
			}else if($approvallevel == 2){
				$emailto = $reportstoemail;
				$abaname = $reportstoname;
				$notifyto = "<p>A leave request applied by <b>".$requestor."</b> has been approved by <b>".$reportstoindirectname."</b> on ".$approveddate_indirect.". <br /> Please see details below.</p>";
				if($reportstoindirectemail == null || $reportstoindirectemail == ""){
					$emailto = $reportstoemail;
					$abaname = $reportstoname;
					$notifyto = "<p>A leave request has been applied by <b>".$requestor."</b> for your approval. <br /> Please see details below.</p>";
				}	

			}
			
			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Leave Request (" . $leaveid . ")";
			$mail->AddAddress($emailto,$requestor); // requestor
			$mail->AddBCC('cdm@abacare.com'); 
			$mail->IsHTML(true);
			// foreach ($cceemails as $ccemail) {
			// 	$mail->AddCC($ccemail);
			// }

			$message = "";
			$head = "<h2>LEAVE REQUEST FOR APPROVAL</h2>";
			$head .= "<p>Dear <b>" . $abaname . "</b>,</p>";
			$head .= $notifyto;

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Leave ID</td><td width="80%">: ' . $leaveid . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: '. $requestor .'</td></tr>';
			$summary .= '<tr><td>Leave Type</td><td>: '. $leavetype .'</td></tr>';
			$summary .= '<tr><td>Leave Duration</td><td>: '. $leaveduration .'</td></tr>';
			$summary .= '<tr><td>Leave From</td><td>: '. $leavefrom .'</td></tr>';
			$summary .= '<tr><td>Leave To</td><td>: '. $leaveto .'</td></tr>';
			$summary .= '<tr><td>No. of Days</b></td><td>: ' . $noofdays . '</b></td></tr>';
			$summary .= '<tr><td>Leave Balance</b></td><td>: ' . $leavebalance . ' </b></td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';
			// $summary .= '<tr><td>Attachment</b></td><td>: ' . $attachment . '</b></td></tr></table>';

			$summary .= '<p>Please respond to the request by clicking  <a href="' . base_URL . 'leave_request_approval.php?id='. $sesid .'&appr=1" target="_blank">[ Approve ]</a> &nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp; <a href="' . base_URL . 'leave_request_approval.php?id='. $sesid .'&appr=0" target="_blank">[ Disapprove ]</a></p>';


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

			// $res['approvallevel'] = 0;
			return $res;
		}

		public function sendLeaveRequestNotification($data){
			$res = array();
			$reportstoname = $data['reportstoname'];
			$leaveid = $data['leaveid'];
			$requestor = $data['requestor'];
			$reportstoemail = $data['reportstoemail'];
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavefrom = $data['leavefrom'];
			$leaveto = $data['leaveto'];
			$reason = $data['reason'];
			$noofdays = $data['noofdays'];
			$sesid = $data['sesid'];

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Leave Request (" . $leaveid . ")";
			$mail->AddAddress($reportstoemail,$requestor); // requestor
			$mail->AddBCC("cdm@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			// $head = "<h2>LEAVE REQUEST APPROVAL</h2>";
			$head = "<p>Dear <b>" . $requestor . "</b>,</p>";
			$head .= "<p>We sent you a notification that you requested for a leave approval. <br /> Please click on the link below to see your leave status. <a href='http://localhost/hris-dev/comp-ben.php'> Leave Requests </a></p>";


			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Leave ID</td><td width="80%">: ' . $leaveid . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: '. $requestor .'</td></tr>';
			$summary .= '<tr><td>Leave Type</td><td>: '. $leavetype .'</td></tr>';
			$summary .= '<tr><td>Leave Duration</td><td>: '. $leaveduration .'</td></tr>';
			$summary .= '<tr><td>Leave From</td><td>: '. $leavefrom .'</td></tr>';
			$summary .= '<tr><td>Leave To</td><td>: '. $leaveto .'</td></tr>';
			$summary .= '<tr><td>No. of Days</b></td><td>: ' . $noofdays . '</b></td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';

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

		public function sendLeaveApproveNotification($val){
			$res = array();
			$data = $val['leave'];
			// $reportstoname = $data['reportstoname'];
			$leaveid = $data['leaveid'];
			$requestor = $data['requestorname'];
			$requestoremail = $data['requestoremail'];
			$leavetype = $data['leavetypedesc'];
			$leaveduration = $data['leavedurationdesc'];
			$leavefrom = $data['leavefromdt'];
			$leaveto = $data['leavetodt'];
			$reason = $data['reason'];
			$noofdays = $data['noofdays'];
			$sesid = $data['sesid'];
			$status = $val['stat'];
			if($status > 0){
				$subj = 'APPROVED';
				$header = "<p>This is to notify you that your leave request is approved.</p>";
			}else{
				$subj = 'DISAPPROVED';
				$header = "<p>This is to notify you that your leave request was disapproved.</p>";
			}
			$cceemails = $val['ccemails'];

			// foreach ($cceemails as $ccemail) {
			// 	$res['cc'][] = $ccemail;
			// }

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "LEAVE REQUEST ". $subj ." (" . $leaveid . ")";
			// $mail->AddAddress('rey.castanares@abacare.com',$requestor); // requestor
			$mail->AddAddress($requestoremail,$requestor); // requestor
			$mail->AddBCC("cdm@abacare.com"); // it executive
			foreach ($cceemails as $ccemail) {
				$mail->AddCC($ccemail);
			}
			$mail->IsHTML(true);

			$message = "";
			// $head = "<h2>LEAVE REQUEST APPROVAL</h2>";
			$head = "<p>Dear <b>" . $requestor . "</b>,</p>";
			$head .= $header;

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Leave ID</td><td width="80%">: ' . $leaveid . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: '. $requestor .'</td></tr>';
			$summary .= '<tr><td>Leave Type</td><td>: '. $leavetype .'</td></tr>';
			$summary .= '<tr><td>Leave Duration</td><td>: '. $leaveduration .'</td></tr>';
			$summary .= '<tr><td>Leave From</td><td>: '. $leavefrom .'</td></tr>';
			$summary .= '<tr><td>Leave To</td><td>: '. $leaveto .'</td></tr>';
			$summary .= '<tr><td>No. of Days</b></td><td>: ' . $noofdays . '</b></td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';

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


		public function sendImportAttendanceNotification($val){
			$res = array();
			// $reportstoname = $data['reportstoname'];
			$emailto = $val['emailto'];
			$ccemail = $val['ccemail'] == "" ? "" : $val['ccemail'];
			$eepersonnelname = $val['eepersonnelname'];
			$sdt = formatDate("d M Y",$val['sdt']);
			$edt = formatDate("d M Y",$val['edt']);
			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "ZKTeco Log Notification Reminder";
			$mail->AddAddress($emailto,$eepersonnelname); // requestor
			$mail->AddBCC("cdm@abacare.com");
			// if(!empty($ccemail)){
			// 	$mail->AddCC($ccemail);
			// }
			$mail->IsHTML(true);

			$message = "";
			// $head = "<h2>LEAVE REQUEST APPROVAL</h2>";
			$head = "<p>Dear <b>" . $eepersonnelname . "</b>,</p>";

			$summary = "";
			$summary .= "<p>There are no generated logs from ZKTeco software from ". $sdt ." - ". $edt .". Attendance will not be uploaded to the HRIS Web app.</p>";
			$summary .= "<p>Please make sure you generate all logs from ZKTeco before 11:00:AM.</p>";

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

		public function sendLeaveRequestChangeDetailsToApprover($data){
			$res = array();
			$reportstoname = $data['reportstoname'];
			$leaveid = $data['leaveid'];
			$requestor = $data['requestor'];
			$reportstoemail = $data['reportstoemail'];
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavefrom = $data['leavefrom'];
			$leaveto = $data['leaveto'];
			$reason = $data['reason'];
			$noofdays = $data['noofdays'];
			$sesid = $data['sesid'];
			$attachment = $data['attachment'];

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Leave Request Change Details (" . $leaveid . ")";
			$mail->AddAddress($reportstoemail,$requestor); // requestor
			$mail->AddBCC("cdm@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			$head = "<h2>LEAVE REQUEST CHANGE DETAILS</h2>";
			$head .= "<p>Dear <b>" . $reportstoname . "</b>,</p>";
			$head .= "<p>This is to notify you that <b>".$requestor."</b> has changes to his/her leave request.<br /> Please see details below.</p>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Leave ID</td><td width="80%">: ' . $leaveid . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: '. $requestor .'</td></tr>';
			$summary .= '<tr><td>Leave Type</td><td>: '. $leavetype .'</td></tr>';
			$summary .= '<tr><td>Leave Duration</td><td>: '. $leaveduration .'</td></tr>';
			$summary .= '<tr><td>Leave From</td><td>: '. $leavefrom .'</td></tr>';
			$summary .= '<tr><td>Leave To</td><td>: '. $leaveto .'</td></tr>';
			$summary .= '<tr><td>No. of Days</b></td><td>: ' . $noofdays . '</b></td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';
			$summary .= '<tr><td>Attachment</b></td><td>: ' . $attachment . '</b></td></tr></table>';

			// $summary .= '<p><a href="' . base_URL . 'leave_request_approval.php?id='. $sesid .'&appr=1" target="_blank">[ Approve ]</a> &nbsp;&nbsp;&nbsp; <a href="' . base_URL . 'leave_request_approval.php?id='. $sesid .'&appr=0" target="_blank">[ Disapprove ]</a></p>';


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

		public function sendLeaveRequestChangeDetailsToRequestor($data){
			$res = array();
			$reportstoname = $data['reportstoname'];
			$leaveid = $data['leaveid'];
			$requestor = $data['requestor'];
			$reportstoemail = $data['reportstoemail'];
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavefrom = $data['leavefrom'];
			$leaveto = $data['leaveto'];
			$reason = $data['reason'];
			$noofdays = $data['noofdays'];
			$sesid = $data['sesid'];

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Leave Request (" . $leaveid . ")";
			$mail->AddAddress($reportstoemail,$requestor); // requestor
			$mail->AddBCC("cdm@abacare.com"); // it executive
			$mail->IsHTML(true);

			$message = "";
			// $head = "<h2>LEAVE REQUEST APPROVAL</h2>";
			$head = "<p>Dear <b>" . $requestor . "</b>,</p>";
			$head .= "<p>We sent you a notification that you requested for a leave approval. <br /> Please click on the link below to see your leave status. <a href='http://localhost/hris-dev/comp-ben.php'> Leave Requests </a></p>";


			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Leave ID</td><td width="80%">: ' . $leaveid . '</td></tr>';
			$summary .= '<tr><td>Requestor</td><td>: '. $requestor .'</td></tr>';
			$summary .= '<tr><td>Leave Type</td><td>: '. $leavetype .'</td></tr>';
			$summary .= '<tr><td>Leave Duration</td><td>: '. $leaveduration .'</td></tr>';
			$summary .= '<tr><td>Leave From</td><td>: '. $leavefrom .'</td></tr>';
			$summary .= '<tr><td>Leave To</td><td>: '. $leaveto .'</td></tr>';
			$summary .= '<tr><td>No. of Days</b></td><td>: ' . $noofdays . '</b></td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';

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

		public function sendTardinessNotification($data){
			$res = array();
			$requestor = trim(preg_replace('/\s+/', ' ', $data['requestor']));
			$reportstoname = $data['reportstoname'];
			$reportstoemail = $data['reportstoemail'];
			$reason = $data['reason'];
			$eta = $data['eta'];
			$cceemails = $data['ccemails'];
			$callinid = $data['callinid'];
			$createddt = $data['createddt'];
			$callintypedesc = $data['callintypedesc'];
			
			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Call In for " . $requestor . " - " . $callintypedesc . "";
			$mail->AddAddress($reportstoemail,$reportstoname); // requestor
			$mail->AddBCC('cdm@abacare.com'); 
			$mail->IsHTML(true);
			foreach ($cceemails as $ccemail) {
				$mail->AddCC($ccemail);
			}

			$message = "";
			$head = "<h2>CALL IN NOTIFICATION</h2>";
			$head .= "<p>Dear <b>" . $reportstoname . "</b>,</p>";
			$head .= "<p>Please be informed that <b>" . $requestor . "</b> had call in late today. <br>
					  Please see details below.<p>";

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			$summary .= '<tr><td width="20%">Date</td><td width="80%">: ' . $createddt . '</td></tr>';
			$summary .= '<tr><td>ETA</td><td>: '. $eta .'</td></tr>';
			$summary .= '<tr><td>Call In Type</td><td>: '. $callintypedesc .'</td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';


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

			// $res['approvallevel'] = 0;
			return $res;
		}

		public function sendCallInLeaveRequestApproval($data){
			$res = array();
			$approvallevel = $data['approvallevel'];
			$reportstoname = $data['reportstoname'];
			$leaveid = $data['leaveid'];
			$requestor = trim(preg_replace('/\s+/', ' ', $data['requestor']));
			$requestoremail = $data['requestoremail'];
			$reportstoemail = $data['reportstoemail'];
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavefrom = $data['leavefrom'];
			$leaveto = $data['leaveto'];
			$reason = $data['reason'];
			$noofdays = $data['noofdays'];
			$sesid = $data['sesid'];
			$approveddate_indirect = $data['approveddate_indirect'] == "" ? null : formatDate("D d M Y",$data['approveddate_indirect']);
			// $attachment = $data['attachment'];
			$reportstoindirectname = $data['reportstoindirectname'] == "" ? null : $data['reportstoindirectname'];
			$reportstoindirectemail = $data['reportstoindirectemail'] == "" ? null : $data['reportstoindirectemail'];
			$emailto = "";
			$abaname = "";
			$cceemails = $data['ccemails'];
			$leavebalance = $data['leavebalance'];
			$callinid = $data['callinid'];

			if($approvallevel == 1){
				$emailto = $reportstoindirectemail;
				$abaname = $reportstoindirectname;
				$notifyto = "<p>A leave request has been applied by <b>".$requestor."</b> for your approval. <br /> Please see details below.</p>";
				if($reportstoindirectemail == null || $reportstoindirectemail == ""){
					$emailto = $reportstoemail;
					$abaname = $reportstoname;
				}	
			}else if($approvallevel == 2){
				$emailto = $reportstoemail;
				$abaname = $reportstoname;
				$notifyto = "<p>A leave request applied by <b>".$requestor."</b> has been approved by <b>".$reportstoindirectname."</b> on ".$approveddate_indirect.". <br /> Please see details below.</p>";
				if($reportstoindirectemail == null || $reportstoindirectemail == ""){
					$emailto = $reportstoemail;
					$abaname = $reportstoname;
					$notifyto = "<p>A leave request has been applied by <b>".$requestor."</b> for your approval. <br /> Please see details below.</p>";
				}	

			}
			$callintypedesc = $data['callintypedesc'];
			$absenttypedesc = $data['absenttypedesc'];
			
			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "Call In for " . $requestor . " - " . $callintypedesc . "";
			$mail->AddAddress($emailto,$requestor); // requestor
			$mail->AddBCC('cdm@abacare.com'); 
			$mail->IsHTML(true);
			foreach ($cceemails as $ccemail) {
				$mail->AddCC($ccemail);
			}

			$message = "";
			$head = "<h2>CALL IN NOTIFICATION</h2>";
			$head .= "<p>Dear <b>" . $abaname . "</b>,</p>";
			$head .= $notifyto;

			$summary = "";
			$summary .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">';
			// $summary .= '<tr><td width="20%">Leave ID</td><td width="80%">: ' . $leaveid . '</td></tr>';
			$summary .= '<tr><td width="20%">Requestor</td><td width="80%">: '. $requestor .'</td></tr>';
			$summary .= '<tr><td>Call In Type</td><td>: '. $callintypedesc .'</td></tr>';
			$summary .= '<tr><td>Absent Type</td><td>: '. $absenttypedesc .'</td></tr>';
			$summary .= '<tr><td>Date</td><td>: '. $leavefrom .'</td></tr>';
			// $summary .= '<tr><td>Leave To</td><td>: '. $leaveto .'</td></tr>';
			// $summary .= '<tr><td>No. of Days</b></td><td>: ' . $noofdays . '</b></td></tr>';
			$summary .= '<tr><td>Leave Balance</b></td><td>: ' . $leavebalance . ' </b></td></tr>';
			$summary .= '<tr><td>Reason</b></td><td>: ' . $reason . '</b></td></tr></table>';
			// $summary .= '<tr><td>Attachment</b></td><td>: ' . $attachment . '</b></td></tr></table>';

			$summary .= '<p>Please respond to the request by clicking  <a href="' . base_URL . 'leave_request_approval.php?id='. $sesid .'&appr=1" target="_blank">[ Approve ]</a> &nbsp;&nbsp;&nbsp; or &nbsp;&nbsp;&nbsp; <a href="' . base_URL . 'leave_request_approval.php?id='. $sesid .'&appr=0" target="_blank">[ Disapprove ]</a></p>';


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

			// $res['approvallevel'] = 0;
			return $res;
		}
	}

?>