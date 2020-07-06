<?php
	class CompensationAndBenefits extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		public function getHmoBenefits(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql =  "SELECT ".
						"hmoBenefits.description AS hmo_benefits_description, ".
						"hmoBenefits.coverageamount AS hmo_benefits_coverageamount ".
					"FROM "	.BENEFITSMST. " hmoBenefits ".
					"WHERE hmoBenefits.benefittype = 'hmo'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func CompensationAndBenefits()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}



		public function updateLeaveBenefits(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			//hris_leave_credits.userid and .fiscalyear
			//left join hris_benefits, hris_leave_credits.leavetype vs hris_benefits.benefitini

			$sql =  "UPDATE ".
						"hmoBenefits.description AS hmo_benefits_description, ".
						"hmoBenefits.coverageamount AS hmo_benefits_coverageamount ".
					"FROM "	.LEAVECREDITSMST. " hmoBenefits ".
					"WHERE hmoBenefits.benefittype = 'hmo'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func CompensationAndBenefits()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}


		public function getLeaveBenefits($eeid){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$fiscalyear = date('Y');
			$today = TODAY;
			$sql =  "SELECT * FROM " . BENEFITSMST .
					" WHERE " . BENEFITSMST .".benefittype = 'leave'" ;
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getLeaveBenefits()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$benefitini = $row['benefitini'];
				$entitleddays = $row['reg_credit'];
				$leavetypeid = strtoupper($benefitini) . substr($fiscalyear,2);

				$sqlinscr = "INSERT INTO ". LEAVECREDITSMST ." (userid,leavetypeid,leavetype,fiscalyear,entitleddays,createdby,createddate) 
	    					VALUES('$eeid','$leavetypeid','$benefitini','$fiscalyear','$entitleddays','admin','$today')";
	    		$this->cn->query($sqlinscr);
	    		// $rows[] = $sqlinscr;
			}

			//get leave saved leaves
			$sqlleavecr = "SELECT " . LEAVECREDITSMST . ".* 
                           ,a.description as leavedesc
                           ,(CASE WHEN " . LEAVECREDITSMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname    
                           FROM " . LEAVECREDITSMST . " 
                           LEFT JOIN " . BENEFITSMST ." a
                            ON a.benefitini = " . LEAVECREDITSMST . ".leavetype 
                           WHERE " . LEAVECREDITSMST . ".userid = '$eeid' AND ". LEAVECREDITSMST .".fiscalyear = '$fiscalyear' ";
			$qry = $this->cn->query($sqlleavecr);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getLeaveBenefits()! " . $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}
		public function getEEHMOBenefits($eeid){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$fiscalyear = date('Y');
			$today = TODAY;

			$sql =  "SELECT * FROM " . BENEFITSMST .
					" WHERE " . BENEFITSMST .".benefittype = 'hmo'" ;
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getEEHMOBenefits()! " . $this->cn->error;
				goto exitme;
			}
			// while($row = $qry->fetch_array(MYSQLI_ASSOC)){
			// 	$benefitini = $row['benefitini'];
			// 	$entitleddays = $row['reg_credit'];
			// 	$leavetypeid = strtoupper($benefitini) . substr($fiscalyear,2);

			// 	$sqlinscr = "INSERT INTO ". LEAVECREDITSMST ." (userid,leavetypeid,leavetype,fiscalyear,entitleddays,createdby,createddate) 
	  //   					VALUES('$eeid','$leavetypeid','$benefitini','$fiscalyear','$entitleddays','admin','$today')";
	  //   		$this->cn->query($sqlinscr);
	  //   		// $rows[] = $sqlinscr;
			// }

			// //get leave saved leaves
			// $sqlleavecr = "SELECT " . LEAVECREDITSMST . ".* 
			// 			   ,a.description as leavedesc
			// 			   FROM " . LEAVECREDITSMST . " 
			// 			   LEFT JOIN " . BENEFITSMST ." a
			// 			   	ON a.benefitini = " . LEAVECREDITSMST . ".leavetype 
			// 			   WHERE " . LEAVECREDITSMST . ".userid = '$eeid' AND ". LEAVECREDITSMST .".fiscalyear = '$fiscalyear' ";
			// $qry = $this->cn->query($sqlleavecr);
			// if(!$qry){
			// 	$res['err'] = 1;
			// 	$res['errmsg'] = "An error occured in func getLeaveBenefits()! " . $this->cn->error;
			// 	goto exitme;
			// }

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}

		public function getLeaveTypeBenefits(){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$sql =  "SELECT * FROM " . BENEFITSMST .
					" WHERE " . BENEFITSMST .".benefittype = 'leave'" ;
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getLeaveBenefits()! " . $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}

		function getleaveTypes($sesid){
			$res = array();
			// $userid = $data['userid'];

			$sql = "SELECT ". LEAVECREDITSMST .".*
					,a.`description` AS leavetypedesc 
					,(". LEAVECREDITSMST .".entitleddays - ". LEAVECREDITSMST .".takendays) AS leavebalance
					,(SELECT COUNT(id) 
						FROM ". LEAVESMST ." 
						WHERE ". LEAVESMST .".leavetype = " . LEAVECREDITSMST . ".leavetypeid 
							AND ". LEAVESMST .".status = 0 
							AND ". LEAVESMST .".userid = b.`userid`) as forapproval 
					,(CASE WHEN " . LEAVECREDITSMST . ".`status` = 1 THEN 'enabled' ELSE 'disabled' END) AS statusname	
				FROM ". LEAVECREDITSMST ." 
				LEFT JOIN ". BENEFITSMST ." a 
					ON a.`benefittype` = 'leave' AND a.`benefitini` = ". LEAVECREDITSMST .".`leavetype` 
				LEFT JOIN " . ABAPEOPLESMST . " b 
					ON b.`sesid` = '$sesid' 
				WHERE ". LEAVECREDITSMST .".`userid` = b.`userid` ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getleaveTypes(). ". $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getLeaveCreditsProfile($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$creditini = $data['creditini'];
			$fiscalyr = (int)$data['fiscalyr'];
			// $leavetype = $data['leavetype'];

			$sql = "SELECT ". LEAVECREDITSMST .".*
						,(". LEAVECREDITSMST .".entitleddays - ". LEAVECREDITSMST .".takendays) AS leavebalance
						,". LEAVECREDITSMST .".status
						,a.description AS leavetypedesc 
					FROM ". LEAVECREDITSMST ." 
					LEFT JOIN ". BENEFITSMST ." a 
						ON a.benefitini = ". LEAVECREDITSMST .".leavetype AND a.benefittype = 'leave' 
					WHERE ". LEAVECREDITSMST .".`userid` = '$userid' AND 
						  ". LEAVECREDITSMST .".`leavetype` = '$creditini' AND 
						  ". LEAVECREDITSMST .".`fiscalyear` = '$fiscalyr' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func " . __FUNCTION__ . ": " . $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:

			return $res;
		}

		function getBenefitsProfile($data){
			$res = array();
			$res['err'] = 0;
			$creditini = $data['creditini'];
			// $leavetype = $data['leavetype'];

			$sql = "SELECT ". BENEFITSMST .".* 
					FROM ". BENEFITSMST ." 
					WHERE ". BENEFITSMST .".`benefittype` = 'leave' AND 
						  ". BENEFITSMST .".`benefitini` = '$creditini' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func " . __FUNCTION__ . ": " . $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:

			return $res;
		}
		
		function updateLeaveBenefitProfile($data){
			$res = array();
			$res['err'] = 0;
			// $leavetype = $data['leavetype'];
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$leavetypeid = $data['leavetypeid'];
			$entitled = $data['entitled'];
			$status = $data['status'];
			$fiscalyear = $data['fiscalyr'];

			$today = TODAY;
			//". LEAVECREDITSMST .".takendays = '$taken', 
			$sql = "UPDATE ". LEAVECREDITSMST ." 
					SET ". LEAVECREDITSMST .".entitleddays = '$entitled', ". LEAVECREDITSMST .".modifiedby = '$userid', ". LEAVECREDITSMST .".modifieddate = '$today'
						, ". LEAVECREDITSMST .".status = '$status' 
					WHERE ". LEAVECREDITSMST .".leavetypeid = '$leavetypeid' AND ". LEAVECREDITSMST .".`userid` = '$eeid' ";
			
			$this->cn->query($sql);

			$sqlleavecr = "SELECT " . LEAVECREDITSMST . ".* 
						   ,a.description as leavedesc
						   FROM " . LEAVECREDITSMST . " 
						   LEFT JOIN " . BENEFITSMST ." a
						   	ON a.benefitini = " . LEAVECREDITSMST . ".leavetype 
						   WHERE " . LEAVECREDITSMST . ".userid = '$eeid' AND ". LEAVECREDITSMST .".fiscalyear = '$fiscalyear' ";
			$qry = $this->cn->query($sqlleavecr);

			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateLeaveBenefitProfile()!". $this->cn->error;
				goto exitme;
			}

			// $res['sql'] = $sql;
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:	
			return $res;
		}

		function saveLeaveBenefitProfile($data){
			$res = array();
			$res['err'] = 0;
			// $leavetype = $data['leavetype'];
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$leavetypeid = $data['leavetypeid'];
			$entitled = $data['entitled'];
			$status = $data['status'];
			$fiscalyr = $data['fiscalyr'];
			$creditini = $data['creditini'];

			$today = TODAY;
			//". LEAVECREDITSMST .".takendays = '$taken', 
			$sql = "INSERT INTO ". LEAVECREDITSMST ." (userid,leavetypeid,leavetype,fiscalyear,entitleddays,createdby,createddate) 
												VALUES('$eeid','$leavetypeid','$creditini','$fiscalyr','$entitled','$userid','$today')";

			// $res['sql'] = $sql;

			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveLeaveBenefitProfile()!". $this->cn->error;
				goto exitme;
			}

			exitme:	
			return $res;
		}

		
		
		function getPendingLeaveRequests($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$sql = "SELECT " . LEAVESMST . ".* 
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %y') AS createddt 
					FROM " . LEAVESMST . " 
					LEFT JOIN ". LEAVECREDITSMST ." a 
						ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					LEFT JOIN ". BENEFITSMST ." b 
						ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					WHERE " . LEAVESMST . ".`status` = 0 AND " . LEAVESMST . ".userid = '$userid' 
					ORDER BY " . LEAVESMST . ".createddate DESC";
			// $res['sql'] = $sql;
 			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getPendingLeaveRequests(). ". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){				
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>