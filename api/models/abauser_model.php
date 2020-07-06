<?php
	// include_once('auditlogs.php');
	class abaUserModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function logMeIn($uname){
			$res = array();
			$res['err'] = 0;
    		$sql = "SELECT ". ABAPEOPLESMST .".* 
                        ,". ABAUSER .".`password` 
                        ,a.description AS designationname 
                        ,b.description AS officename 
                        ,". ABAUSER .".accesslevel 
                    FROM ". ABAPEOPLESMST ." 
                    LEFT JOIN ". ABAUSER ." 
                        ON ". ABAUSER .".`abaini` = ". ABAPEOPLESMST .".`abaini`  AND ". ABAUSER .".`status` = 1 
                    LEFT JOIN ". DESIGNATIONSMST ." a 
                        ON a.designationid = ". ABAPEOPLESMST .".designation 
                    LEFT JOIN ". SALESOFFICESMST ." b 
                        ON b.salesofficeid = ". ABAPEOPLESMST .".office 
                    WHERE ". ABAPEOPLESMST .".`abaini` = '$uname' 
                        AND ". ABAPEOPLESMST .".`status` = 1 AND ". ABAPEOPLESMST .".`contactcategory` IN(1,3) ";
            // $res['sql'] = $sql;
            $rows = array();
			$qry = $this->cn->query($sql);
			
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error in func logMeIn().". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			exitme:
			$res['rows'] = $rows;
			return $res;
		}

		function changePassword($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$abaini = $data['abaini'];
			$pw = $data['password'];
			$today = TODAY;

			$sql = "UPDATE ". ABAUSER ." 
					SET ". ABAUSER .".password = '$pw' 
					WHERE ". ABAUSER .".username = '$abaini' AND ". ABAUSER .".status = 1 ";
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error in changePassword func(). " . $this->cn->error;
			}

			return $res;
		}

		function userLoggedActivity(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql = "SELECT ". ABAPEOPLESMST .".`fname`,
					      ". ABAPEOPLESMST .".`lname`,
					      ". ABAPEOPLESMST .".`mname`,
					      ". ABAPEOPLESMST .".`webhr_designation`,
					      DATE_FORMAT((SELECT ". CDMACTIVITIES .".`createddate`  
					      FROM ". CDMACTIVITIES ." 
					      WHERE ". CDMACTIVITIES .".`userid` = ". ABAPEOPLESMST .".`userid` 
					      ORDER BY ". CDMACTIVITIES .".`createddate` DESC LIMIT 0,1),'%a %d %b %y %H %I %p') AS last_logged 
					FROM ". ABAPEOPLESMST ." 
					WHERE ". ABAPEOPLESMST .".`webhr_designation` IN('business development director','business development executive','business development manager',
										'general manager beijing','general manager for china','general manager hong kong','general manager singapore')
					     AND ". ABAPEOPLESMST .".`status` = 1 AND ". ABAPEOPLESMST .".`contactcategory` = 1";
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error in userLoggedActivity func(). " . $this->cn->error;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			
			return $res;
		}

		function chkUserEmailAddress($email){
			$res = array();
			$res['err'] = 0;
			$rows = array();

			$sql = "SELECT * FROM ". ABAPEOPLESMST ." WHERE ". ABAPEOPLESMST .".workemail = '$email' AND ". ABAPEOPLESMST .".status = 1 ";
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error in chkUserEmailAddress func(). " . $this->cn->error;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			
			return $res;
		}

		function emailPassword($data){
			$pw = $data['pw'];
			$email = $data['email'];
			$abaini = $data['abaini'];

			$res = array();
			$res['sent'] = 1;

			// SEND TO abacare ADMIN
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail = mailTemplate($mail);
			$mail->FromName = "abacare International Limited";
			$mail->Subject = "abacare Aces Webapp Forgot Password";
			$mail->AddAddress($email); // user email address
			$mail->IsHTML(true);

			$message = "";
			$head = "<p>Dear Sir/Madam,</p>";
			$head .= "<p>You may now log-in to the Aces webapp using the information below:</p>";

			$summary = "";
			$summary .= "<p>Username: " . $abaini . "<br />";
			$summary .= "New Password: " . $pw . "</p>";
			$summary .= "<p>Should you encounter any problems accessing your account, please contact the IT administrator or email us at helpdesk@abacare.com for assistance.
				<br />Thank you for using the abacare Aces webapp.</p>";

			$foot = "";
			$foot = "<p>This is a system-generated e-mail.<br />";
			$foot .= "PLEASE DO NOT REPLY ON THIS MESSAGE.</p>";

			$message = $head . $summary . $foot;

			$mail->Body = $message;

			if(!$mail->Send()){
				$res['sent'] = 0;
			}

			return $res;
		}

		function checkPassword($uname, $password){
			$res = array();
			$rows = array();
			$res['result'] = 0;
			$sql = "SELECT ". ABAPEOPLESMST .".* 
						,". ABAUSER .".`password` 
					FROM ". ABAPEOPLESMST ." 
					LEFT JOIN ". ABAUSER ." 
						ON ". ABAUSER .".`abaini` = ". ABAPEOPLESMST .".`abaini` 
					WHERE ". ABAPEOPLESMST .".`abaini` = '$uname' 
						AND ". ABAPEOPLESMST .".`status` = 1 AND ". ABAPEOPLESMST .".`contactcategory` IN(1,3) ";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			if(count($rows) == 0){
				$res['result'] = 1;
				goto end;
			}

			// CHECK IF PASSWORD IS MATCHED
			if($password != $rows[0]['password']){
				$res['result'] = 2;
				goto end;
			}

			if($rows[0]['status'] == 0){
				$res['result'] = 3;
				goto end;
			}
			// $res['stat'] = $rows[0]['status'];
			end:
			return $res;
		}

		function userStatus($data) {
			
			$userid = $data['eeid'];
			$modifiedby = $data['userid'];
			$today = TODAY;
			$status = $data['eestatus'];

			$sql = "UPDATE " . ABAUSER . " 
					SET " . ABAUSER . ".status = '$status' ,
						" . ABAUSER . ".modifiedby = '$userid' ,
						" . ABAUSER . ".modifieddate = '$today' 
					WHERE " . ABAUSER . ".userid = '$userid'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function getUser($userid){
			$res = array();
			$res['err'] = 0;

    		$sql = "SELECT ". ABAPEOPLESMST .".*
                        ,". ABAUSER .".`password`
                        ,a.description AS designationname
                        ,b.description AS officename 
                        ,c.dddescription AS rankdesc 
                        ,d.description AS deptdesc 
                        ,". ABAUSER .".accesslevel 
                    FROM ". ABAPEOPLESMST ." 
                    LEFT JOIN ". ABAUSER ." 
                        ON ". ABAUSER .".`abaini` = ". ABAPEOPLESMST .".`abaini`  AND ". ABAUSER .".`status` = 1 
                    LEFT JOIN ". DESIGNATIONSMST ." a 
                        ON a.designationid = ". ABAPEOPLESMST .".designation 
                    LEFT JOIN ". SALESOFFICESMST ." b 
                        ON b.salesofficeid = ". ABAPEOPLESMST .".office 
                    LEFT JOIN ". DROPDOWNSMST ." c 
                    	ON c.ddid = ". ABAPEOPLESMST .".positiongrade AND c.dddisplay = 'eerankings' 
                    LEFT JOIN ". DEPARTMENTSMST ." d 
                    	ON d.departmentid = ". ABAPEOPLESMST .".department 
                    WHERE ". ABAPEOPLESMST .".`userid` = '$userid' 
                        AND ". ABAPEOPLESMST .".`status` = 1 AND ". ABAPEOPLESMST .".`contactcategory` IN(1,3) ";
            // $res['sql'] = $sql;
            $rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error func ".__FUNCTION__."().". $this->cn->error;
				goto exitme;
			}
			
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			exitme:
			$res['rows'] = $rows;
			return $res;
		}

		function getUserAccess($userid){
			$res = array();
			$res['err'] = 0;

    		$sql = "SELECT ". MENUUSERACCESS .".menuid
    					,". MENUUSERACCESS .".module
    					,". MENUUSERACCESS .".userid
    					,". MENUUSERACCESS .".accessname
    					,". MENUUSERACCESS .".status
    					,a.foreignkey 
                    FROM ". MENUUSERACCESS ." 
                    LEFT JOIN ". MENUACCESS ." a
                        ON a.`menuid` = ". MENUUSERACCESS .".`menuid` 
                        	AND a.`module` = ". MENUUSERACCESS .".`module` 
                        	AND a.`accessname` = ". MENUUSERACCESS .".`accessname` 
					WHERE ". MENUUSERACCESS .".`userid` = '$userid' ";
            // $res['sql'] = $sql;
            $rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error func ".__FUNCTION__."().". $this->cn->error;
				goto exitme;
			}
			
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			exitme:
			$res['rows'] = $rows;
			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>