<?php
	class LeavesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function genLeaveID($userid){
			$today = formatDate("ym",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . LEAVESMST . " WHERE DATE_FORMAT(" . LEAVESMST . ".createddate,'%y%m') = '$today'";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "L" . formatDate("ymd",TODAY) . "00";
			switch(strlen($cnt)){
				// case 4:
				// 	$newno = substr($pre, 0, -3) . $cnt; break;
				// case 3:
				// 	$newno = substr($pre, 0, -3) . $cnt; break;
				case 2:
					$newno = substr($pre, 0, -2) . $cnt ."-". $userid; break;
				default:
					$newno = substr($pre, 0, -1) . $cnt ."-". $userid; break;
			}
			// $this->cn->close();
			return $newno;
		}

		function getLeaveCredits($userid){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT " . LEAVECREDITSMST . ".* 
					,a.dddescription as leavetypedesc
					FROM " . LEAVECREDITSMST . "
					LEFT JOIN " . DROPDOWNSMST . " a
					  	ON a.ddid = " . LEAVECREDITSMST . ".leavetype
					  	AND a.dddisplay = 'leavetype' 
					WHERE " . LEAVECREDITSMST . ".userid = '$userid' ";

			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveCredits(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getAllLeaveRequestsHistory($data){
			$res = array();
			$res['err'] = 0;
			$whereIDS = "";
			foreach($data as $index=>$row){
				if($index == 0){
					$whereIDS .= "WHERE (" . LEAVESMST . ".userid = '$row' ";
				} else {
					$whereIDS .= "OR " . LEAVESMST . ".userid = '$row' ";
				}
			}

			$sql = "SELECT " . LEAVESMST . ".* 
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %Y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %Y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %Y') AS createddt 
					,b.description as leavetypedesc
					,CONCAT(c.fname,' ',c.lname) AS approvedbyname 
					,CONCAT(d.fname,' ',d.lname) AS eename 
					FROM " . LEAVESMST . " 
					LEFT JOIN ". LEAVECREDITSMST ." a 
						ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					LEFT JOIN ". BENEFITSMST ." b 
						ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					LEFT JOIN ". ABAPEOPLESMST ." c 
						ON c.userid = " . LEAVESMST . ".approvedby   
					LEFT JOIN ". ABAPEOPLESMST ." d 
						ON d.userid = " . LEAVESMST . ".userid  
					" . $whereIDS . ") AND " . LEAVESMST . ".`status` != 0 
					ORDER BY " . LEAVESMST . ".leavefrom";

					
					// ,(CASE WHEN ". LEAVESMST .".`status` = 0 THEN 'PENDING' 
		      		//   		WHEN ". LEAVESMST .".`status` = 1 THEN 'APPROVED' 
		      		//   		WHEN ". LEAVESMST .".`status` = -1 THEN 'REJECTED' 
		      		//   		WHEN ". LEAVESMST .".`status` = -2 THEN 'CANCELLED' 
		            //   		ELSE '' 
		        	// 	END) as leavestatus
		        	// ,(CASE WHEN ". LEAVESMST .".`approvalstatusindirect` = 0 THEN 'PENDING' 
		      		//   		WHEN ". LEAVESMST .".`approvalstatusindirect` = 1 THEN 'APPROVED' 
		      		//   		WHEN ". LEAVESMST .".`approvalstatusindirect` = -1 THEN 'REJECTED' 
		      		//   		WHEN ". LEAVESMST .".`approvalstatusindirect` = -2 THEN 'CANCELLED' 
		            //   		ELSE '' 
		        	// 	END) as leavestatusindirect
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error func getAllLeaveRequestsHistory(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getAllLeaveRequests($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];

			$sql = "SELECT " . LEAVESMST . ".* 
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %y') AS createddt 
					,(CASE WHEN ". LEAVESMST .".`status` = 0 THEN 'Level 2 Approval Pending' 
		      		  		WHEN ". LEAVESMST .".`status` = 1 THEN 'Approved' 
		      		  		WHEN ". LEAVESMST .".`status` = -1 THEN 'Rejected' 
		      		  		WHEN ". LEAVESMST .".`status` = -2 THEN 'Cancelled' 
		              		ELSE '' 
		        		END) as leavestatus
		        	,(CASE WHEN ". LEAVESMST .".`approvalstatusindirect` = 0 THEN 'Level 1 Approval Pending' 
		      		  		WHEN ". LEAVESMST .".`approvalstatusindirect` = 1 THEN 'Level 1 Approval Approved' 
		      		  		WHEN ". LEAVESMST .".`approvalstatusindirect` = -1 THEN 'Level 1 Approval Rejected' 
		      		  		WHEN ". LEAVESMST .".`approvalstatusindirect` = -2 THEN 'Cancelled' 
		              		ELSE '' 
		        		END) as leavestatusindirect
					,b.description as leavetypedesc
					,CONCAT(c.fname,' ',c.lname) AS approvedbyname 
					,CONCAT(d.fname,' ',d.lname) AS eename 
					FROM " . LEAVESMST . " 
					LEFT JOIN ". LEAVECREDITSMST ." a 
						ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					LEFT JOIN ". BENEFITSMST ." b 
						ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					LEFT JOIN ". ABAPEOPLESMST ." c 
						ON c.userid = " . LEAVESMST . ".approvedby   
					LEFT JOIN ". ABAPEOPLESMST ." d 
						ON d.userid = " . LEAVESMST . ".userid  
					WHERE " . LEAVESMST . ".userid = '$userid' 
					ORDER BY " . LEAVESMST . ".leavefrom";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error func getAllLeaveRequests(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getPendingLeaveRequests($data){
			$res = array();
			$res['err'] = 0;
			
			$userid = $data['userid'];
			$viewtype = $data['viewtype'];
			$ofclist = $data['ofclist'];
			$userofc = $data['userofc'];
			$ofcforwhere = '';
			$whereval = '';

			if(count($ofclist) > 0) {
				foreach($ofclist as $eachofc) {
					$ofcforwhere .= "'$eachofc',";
				}
				$ofcforwhere = substr($ofcforwhere,0,-1);
				$ofcforwhere = " AND c.office IN ($ofcforwhere) ";
			}
			$whereval = " WHERE 1 ";
			switch($viewtype) {
				case 'department':
					$whereval .= " AND (c.reportstoindirectid = '$userid' OR c.reportstoid= '$userid' OR c.userid = '$userid') $ofcforwhere ";
					break;
				case 'ofclist':
					$whereval .= $ofcforwhere;
					break;
				case 'self':
					$whereval .= "AND c.userid = '$userid' ";
					break;
				default:
					break;
			}
			
			/*
			$hasaccess = $data['hasaccess'];
			if(isset($data['officeid'])){
				$officeid = $data['officeid'];
			} else {
				$officeid = '';
			}
			if($hasaccess == 1){
				$whereval = " WHERE c.userid IS NOT NULL AND c.status = 1 AND c.`contactcategory` = 1 ";
			} else {
				if(!empty($officeid) && $officeid != ''){
					$whereval = " WHERE (c.reportstoindirectid = '$userid' OR c.reportstoid= '$userid' OR c.userid = '$userid' OR c.office = '$officeid') ";
				} else { 
					$whereval = " WHERE (c.reportstoindirectid = '$userid' OR c.reportstoid= '$userid' OR c.userid = '$userid') ";
				}
			}
			*/

			$sql = "SELECT " . LEAVESMST . ".* 
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %Y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %Y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %Y') AS createddt 
					,CONCAT(c.fname,' ',c.lname) as eename
					,b.description as leavetypedesc 
					FROM " . LEAVESMST . " 
					LEFT JOIN ". LEAVECREDITSMST ." a 
						ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					LEFT JOIN ". BENEFITSMST ." b 
						ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					LEFT JOIN ". ABAPEOPLESMST ." c 
						ON c.userid = " . LEAVESMST . ".userid 
					 $whereval AND " . LEAVESMST . ".`status` = 0 
					ORDER BY " . LEAVESMST . ".createddate DESC";
			$res['sql'] = $sql;
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

		function getLeaveRequest($leaveid){
			$res = array();
			$res['err'] = 0;
			
			$sql = "SELECT " . LEAVESMST . ".*
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %y') AS createddt 
					,(CASE WHEN ". LEAVESMST .".`status` = 0 THEN 'Pending' 
		      		  		WHEN ". LEAVESMST .".`status` = 1 THEN 'Approved' 
		      		  		WHEN ". LEAVESMST .".`status` = -1 THEN 'Rejected' 
		      		  		WHEN ". LEAVESMST .".`status` = -2 THEN 'Cancelled' 
		              		ELSE '' 
		        		END) as leavestatus
					,b.description as leavetypedesc
					,c.dddescription as leavedurationdesc 
					,CONCAT(d.fname,' ',d.lname) as eename 
					,d.reportstoid 
					,d.reportstoindirectid 
					,CONCAT(d.fname,' ',d.lname) AS requestorname 
					,d.workemail AS requestoremail
					,d.zkdeviceid
					,d.zkid
					,(a.entitleddays - a.takendays) AS leavebalance  
					FROM " . LEAVESMST . "
					LEFT JOIN ". LEAVECREDITSMST ." a 
						ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					LEFT JOIN ". BENEFITSMST ." b 
						ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					LEFT JOIN " . DROPDOWNSMST . " c 
					  	ON c.ddid = " . LEAVESMST . ".leaveduration
					  	AND c.dddisplay = 'leaveduration' 
					LEFT JOIN ". ABAPEOPLESMST ." d 
						ON d.userid = " . LEAVESMST . ".userid 
					WHERE " . LEAVESMST . ".leaveid = '$leaveid'
					LIMIT 0,1 ";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveRequest(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getReportsToName($userid){
			$res = array();
			$res['err'] = 0;
			
			$sql = "SELECT (CONCAT(a.fname,' ',a.lname)) AS fullName 
					FROM " . ABAPEOPLESMST . " a
					WHERE a.userid = '$userid' ";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func . " . __FUNCTION__ . "() ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getLeaveRequestbySesid($sesid){
			$res = array();
			$res['err'] = 0;
			
			$sql = "SELECT " . LEAVESMST . ".*
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %y') AS createddt 
					,(CASE WHEN ". LEAVESMST .".`status` = 0 THEN 'Pending' 
		      		  		WHEN ". LEAVESMST .".`status` = 1 THEN 'Approved' 
		      		  		WHEN ". LEAVESMST .".`status` = -1 THEN 'Rejected' 
		      		  		WHEN ". LEAVESMST .".`status` = -2 THEN 'Cancelled' 
		              		ELSE '' 
		        		END) as leavestatus
					,b.description as leavetypedesc
					,c.dddescription as leavedurationdesc 
					,CONCAT(d.fname,' ',d.lname) as eename 
					,d.reportstoid 
					,CONCAT(d.fname,' ',d.lname) AS requestorname 
					,d.emailaddress AS requestoremail
					,d.zkdeviceid
					,d.zkid 
					,(a.entitleddays - a.takendays) AS leavebalance 
					FROM " . LEAVESMST . "
					LEFT JOIN ". LEAVECREDITSMST ." a 
						ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					LEFT JOIN ". BENEFITSMST ." b 
						ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					LEFT JOIN " . DROPDOWNSMST . " c 
					  	ON c.ddid = " . LEAVESMST . ".leaveduration
					  	AND c.dddisplay = 'leaveduration' 
					LEFT JOIN ". ABAPEOPLESMST ." d 
						ON d.userid = " . LEAVESMST . ".createdby 
					WHERE " . LEAVESMST . ".sesid = '$sesid' 
					LIMIT 0,1 ";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveRequest(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function saveLeaveRequest($data){
			$res['err'] = 0;
			$userid = $data['userid'];
			$leaveid =$data['leaveid'];
			$sesid = genuri($leaveid);
			$approvallevel = $data['approvallevel'];
			// $attachment = $data['attachment'] == "" ? "" : $data['attachment'];
			if($data['leavefrom'] != ""){
				$leavefrom = formatDate("Y-m-d",$data['leavefrom']);
			}else{
				$leavefrom = "1900-01-01 00:00:00";
			}
			if($data['leaveto'] != ""){
				$leaveto = formatDate("Y-m-d",$data['leaveto']);
			}else{
				$leaveto = "1900-01-01 00:00:00";
			}
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavereason = $data['leavereason'] == "" ? "" : addslashes($data['leavereason']);
			$today = TODAY;

			// $dateto = strtotime($leaveto);
			// $datefrom = strtotime($leavefrom);
			// $noofdays = ceil(abs($dateto - $datefrom)/ 86400) + 1;
			$noofdays = $data['noofdays'];

			$sql = "INSERT INTO " .LEAVESMST. 
					"(leaveid, userid, leavefrom, leaveto, leavetype, leaveduration, noofdays, reason, sesid, createdby, createddate, approvallevel)
					VALUES('$leaveid', '$userid', '$leavefrom', '$leaveto', '$leavetype', '$leaveduration', '$noofdays', '$leavereason','$sesid', '$userid', '$today', '$approvallevel')";

			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveLeaveRequest()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function apprLeaveRequest($data){
			$res = array();
			$res['err'] = 0;
			$rows = array();
			$stat = $data['status'];
			$leaveid = $data['leaveid'];
			$supid = $data['reportstoid'];
			$cmts = isset($data['cmts']) ? $data['cmts'] : "";

			$today = TODAY;

			$sqlapproval = "SELECT * FROM ". LEAVESMST ." WHERE ". LEAVESMST .".leaveid = '$leaveid'  " ;
			$qry1 = $this->cn->query($sqlapproval);
			while($row = $qry1->fetch_array(MYSQLI_ASSOC)){
				$approvallevel = $row['approvallevel'];
				$rows['rows'] = $row;
			}
			$res['rows'] = $rows;
			if(!$qry1){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func apprLeaveRequest()!". $this->cn->error;
				goto exitme;
			}

			$directapproval = "". LEAVESMST .".status = $stat, 
					    	   ". LEAVESMST .".approveddate = '$today', 
					    	   ". LEAVESMST .".approvedby = '$supid', 
					    	   ". LEAVESMST .".comments = '$cmts' ";

			$indirectapproval = "". LEAVESMST .".approvalstatusindirect = $stat, 
					    		 ". LEAVESMST .".approveddate_indirect = '$today', 
					    		 ". LEAVESMST .".approvedby_indirect = '$supid', 
					    		 ". LEAVESMST .".commentsbyindirect = '$cmts',
					    		  ". LEAVESMST .".approvallevel = 2 ";

			if($approvallevel == 1){
				$columnstoupdate = $indirectapproval;
			}else if($approvallevel == 2){
				$columnstoupdate = $directapproval;
			}

			$sql = "UPDATE ". LEAVESMST ." 
				    SET  $columnstoupdate 
				    WHERE ". LEAVESMST .".leaveid = '$leaveid' ";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func apprLeaveRequest()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function deductLeaveCredits($data){
			$res = array();
			$res['err'] = 0;
			$leavetype = $data['leavetype'];
			$userid = $data['userid'];
			$noofdays = $data['noofdays'];

			$today = TODAY;

			$sql = "UPDATE ". LEAVECREDITSMST ." 
					SET ". LEAVECREDITSMST .".takendays = (". LEAVECREDITSMST .".takendays + $noofdays) 
					WHERE ". LEAVECREDITSMST .".userid = '$userid' AND ". LEAVECREDITSMST .".leavetypeid = '$leavetype'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func apprLeaveRequest()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function getleaveTypes($data){
			$res = array();
			$userid = $data['userid'];
			$fiscalyear = $data['fiscalyear'];

			$sql = "SELECT ". LEAVECREDITSMST .".*
					,". BENEFITSMST .".`description` AS leavetypedesc 
					,(CASE WHEN 
						(SELECT SUM(". LEAVESMST .".`noofdays`) FROM ". LEAVESMST ." WHERE ". LEAVESMST .".`userid` = '$userid' AND ". LEAVESMST .".`leavetype` = ". LEAVECREDITSMST .".`leavetypeid` AND ". LEAVESMST .".status = 1) IS NULL THEN 0 ELSE
						(SELECT SUM(". LEAVESMST .".`noofdays`) FROM ". LEAVESMST ." WHERE ". LEAVESMST .".`userid` = '$userid' AND ". LEAVESMST .".`leavetype` = ". LEAVECREDITSMST .".`leavetypeid` AND ". LEAVESMST .".status = 1) END) AS noofdaystaken
					,(". LEAVECREDITSMST .".entitleddays - (SELECT noofdaystaken)) AS leavebalance
					,IFNULL((SELECT SUM(". LEAVESMST .".`noofdays`) 
						FROM ". LEAVESMST ." 
						WHERE ". LEAVESMST .".leavetype = " . LEAVECREDITSMST . ".leavetypeid 
							AND ". LEAVESMST .".status = 0 
							AND ". LEAVESMST .".userid = '$userid'),0) as forapproval 
					,(CASE WHEN " . LEAVECREDITSMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname	
				FROM ". LEAVECREDITSMST ." 
				LEFT JOIN ". BENEFITSMST ." 
					ON ". BENEFITSMST .".`benefittype` = 'leave' AND ". BENEFITSMST .".`benefitini` = ". LEAVECREDITSMST .".`leavetype` 
				WHERE ". LEAVECREDITSMST .".`userid` = '$userid' AND ". LEAVECREDITSMST .".`fiscalyear` = '$fiscalyear' AND ". LEAVECREDITSMST .".`status` = 1";
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

		function getLeaveCredit($id){
			$res = array();
			$res['err'] = 0;
			// $userid = $data['userid'];
			// $leavetype = $data['leavetype'];

			$sql = "SELECT ". LEAVECREDITSMST .".id 
						,". LEAVECREDITSMST .".entitleddays
						,". LEAVECREDITSMST .".takendays
						,(". LEAVECREDITSMST .".entitleddays - ". LEAVECREDITSMST .".takendays) AS leavebalance
						,". LEAVECREDITSMST .".status
						,a.description AS leavetypedesc 
					FROM ". LEAVECREDITSMST ." 
					LEFT JOIN ". BENEFITSMST ." a 
						ON a.benefitini = ". LEAVECREDITSMST .".leavetype AND a.benefittype = 'leave' 
					WHERE ". LEAVECREDITSMST .".`id` = '$id' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveBalance(). ". $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:

			return $res;
			//
		}

		function getLeaveBalance($data){	
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$leavetype = $data['leavetype'];

			$sql = "SELECT ". LEAVECREDITSMST .".entitleddays
						,". LEAVECREDITSMST .".takendays
						,(". LEAVECREDITSMST .".entitleddays - ". LEAVECREDITSMST .".takendays) AS leavebalance
						,". LEAVECREDITSMST .".status
						,a.description AS leavetypedesc 
						,IFNULL((SELECT SUM(". LEAVESMST .".`noofdays`) 
						FROM ". LEAVESMST ." 
						WHERE ". LEAVESMST .".leavetype = " . LEAVECREDITSMST . ".leavetypeid 
							AND ". LEAVESMST .".status = 0 
							AND ". LEAVESMST .".userid = '$userid'),0) as pending 
					FROM ". LEAVECREDITSMST ." 
					LEFT JOIN ". BENEFITSMST ." a 
						ON a.benefitini = ". LEAVECREDITSMST .".leavetype AND a.benefittype = 'leave' 
					WHERE ". LEAVECREDITSMST .".`userid` = '$userid' 
						AND ". LEAVECREDITSMST .".leavetypeid = '$leavetype' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveBalance(). ". $this->cn->error;
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

		function updateLeaveBenefit($data){
			$res = array();
			$res['err'] = 0;
			// $leavetype = $data['leavetype'];
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$id = $data['id'];
			$entitled = $data['entitled'];
			$status = $data['status'];
			$fiscalyear = date('Y');

			$today = TODAY;
			//". LEAVECREDITSMST .".takendays = '$taken', 
			$sql = "UPDATE ". LEAVECREDITSMST ." 
					SET ". LEAVECREDITSMST .".entitleddays = '$entitled', ". LEAVECREDITSMST .".modifiedby = '$userid', ". LEAVECREDITSMST .".modifieddate = '$today'
						, ". LEAVECREDITSMST .".status = '$status' 
					WHERE ". LEAVECREDITSMST .".id = '$id' ";
			
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
				$res['errmsg'] = "An error occured in func updateLeaveBenefit()!". $this->cn->error;
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

		function getHolidays($data){
			$res = array();
			$res['err'] = 0;
			$fm = formatDate("Y-m-d",$data['dtfrom']); // format: YYYYMMDD
			$to = formatDate("Y-m-d",$data['dtto']); // format: YYYYMMDD
			$ofc = $data['ofc'];

			$sql = "SELECT ". HOLIDAYSMST .".holidaycode, ". HOLIDAYSMST .".title, ". HOLIDAYSMST .".startdate, ". HOLIDAYSMST .".description, ". HOLIDAYSMST .".region 
					FROM ". HOLIDAYSMST ." 
					WHERE ". HOLIDAYSMST .".holidaydate >= '$fm 00:00:00' AND ". HOLIDAYSMST .".holidaydate <= '$to 23:59:59' 
						AND ". HOLIDAYSMST .".`status` = 1 
						AND (". HOLIDAYSMST .".region = 'international' OR ". HOLIDAYSMST .".office = '$ofc' OR ". HOLIDAYSMST .".office = 'all') 
					ORDER BY ". HOLIDAYSMST .".holidaycode";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getHolidays(). ". $this->cn->error;
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
		
		function getWorkingDays($data){
			$res = array();
			$res['err'] = 0;
			$fm = formatDate("Y-m-d",$data['dtfrom']); // format: YYYYMMDD
			$to = formatDate("Y-m-d",$data['dtto']); // format: YYYYMMDD
			$ofc = $data['ofc'];

			$sql = "SELECT ". WORKINGDAYMST .".workingdaycode, ". WORKINGDAYMST .".title, ". WORKINGDAYMST .".startdate, ". WORKINGDAYMST .".description, ". WORKINGDAYMST .".region
					," . SALESOFFICESMST . ".description AS officename 
					," . SALESOFFICESMST . ".salesofficeid 
					FROM ". WORKINGDAYMST ." 
					LEFT JOIN " . SALESOFFICESMST . " 
						ON " . SALESOFFICESMST . ".salesofficeid = ". WORKINGDAYMST .".office 
					WHERE ". WORKINGDAYMST .".workingdaydate >= '$fm 00:00:00' AND ". WORKINGDAYMST .".workingdaydate <= '$to 23:59:59' 
						AND ". WORKINGDAYMST .".`status` = 1 
						AND (". WORKINGDAYMST .".region = 'international' OR ". WORKINGDAYMST .".office = '$ofc') 
					ORDER BY ". WORKINGDAYMST .".workingdaycode";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getWorkingDays(). ". $this->cn->error;
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

		function saveLeaveDetails($data){
			$res['err'] = 0;
			$leaveid = $data['leaveid'];
			$leavedt = formatDate("Y-m-d",$data['leavedate']);
			$wap = $data['wap'];
			$pts = $data['points'];
			$remarks = $data['remarks'];
			$userid = $data['userid'];
			$today = TODAY;

			$sql = "INSERT INTO " .LEAVESDTL. "(leaveid, leavedate, wap, pts, remarks, createdby, createddate)
					VALUES('$leaveid', '$leavedt', '$wap', '$pts', '$remarks', '$userid', '$today')";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveLeaveDetails()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function updateLeaveDetailsStatus($data){
			$res['err'] = 0;
			$leaveid = $data['leaveid'];
			$userid = $data['userid'];
			$today = TODAY;
			
			$sql = "UPDATE " .LEAVESDTL. "
					SET " .LEAVESDTL. ".status = 0,
						" .LEAVESDTL. ".modifiedby = '$userid', 
						" .LEAVESDTL. ".modifieddate = '$today'  
					WHERE " .LEAVESDTL. ".leaveid = '$leaveid' ";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateLeaveDetailsStatus()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function recallLeaveRequest($data){
			$res = array();
			$res['err'] = 0;
			
			$leaveid = $data['leaveid'];
			$userid = $data['userid'];
			$today = TODAY;

			$hasleavedata = $data['hasleavedata'] > 0 ? 1 : 0;

			$sql_setrecall = "UPDATE ". LEAVESMST ." 
							  SET ". LEAVESMST .".canceleddate = '$today', ". LEAVESMST .".status = -3 
							  WHERE ". LEAVESMST .".leaveid = '$leaveid' ";

			$sql_setdtls = "UPDATE ". LEAVESDTL ." 
							SET ". LEAVESDTL .".`status` = 0 
							   ," .LEAVESDTL. ".`modifiedby` = '$userid' 
							   ," .LEAVESDTL. ".`modifieddate` = '$today'  
							WHERE ". LEAVESDTL .".`leaveid` = '$leaveid'";
			
			$res['sql_setrecall'] = $sql_setrecall;
			$res['sql_setdtls'] = $sql_setdtls;


			// if($hasleavedata == 1) {
			// 	$takendays = $data['takendays'];
			// 	$leavetypeid = $data['leavetype'];

			// 	$sql_revertcredits = "UPDATE ". LEAVECREDITSMST ." 
			// 						  SET ". LEAVECREDITSMST .".`takendays` = '$takendays' 
			// 						  WHERE ". LEAVECREDITSMST .".`userid` = '$userid' 
			// 							AND ". LEAVECREDITSMST .".`leavetypeid` = '$leavetypeid'";

			// 	$res['sql_revertcredits'] = $sql_revertcredits;
			// 	$qry_revertcredits = $this->cn->query($sql_revertcredits);
			// }

			$qry_setrecall = $this->cn->query($sql_setrecall);
			$qry_setdtls = $this->cn->query($sql_setdtls);

			if(!$qry_setrecall || !$qry_setdtls){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function undoLeave($data){
			$res = array();
			$res['err'] = 0;

			$leaveid = $data['leaveid'];
			// $leaveduration = $data['leaveduration'];
			
			$sql = "UPDATE ". ATTENDANCESMST ." 
					SET ". ATTENDANCESMST .".`leaveid` = ''
						,". ATTENDANCESMST .".`onleave` = 0
						,". ATTENDANCESMST .".`wholeday` = 0
						,". ATTENDANCESMST .".`firsthalf` = 0
						,". ATTENDANCESMST .".`secondhalf` = 0 
					WHERE ". ATTENDANCESMST .".`leaveid` = '$leaveid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func undoLeave()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function getTakenDays($data) {
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$leavetype = $data['leavetype'];

			$sql = "SELECT a.`takendays` 
					FROM ". LEAVECREDITSMST ." a 
					WHERE a.`userid` = '$userid' AND a.`leavetypeid` = '$leavetype'";

			
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()!". $this->cn->error;
				goto exitme;
			}
			
			exitme:
			return $qry->fetch_array(MYSQLI_ASSOC);
		}

		function getLeaveData($data) {
			$res = array();
			$res['err'] = 0;
			$leaveid = $data['leaveid'];

			$sql = "SELECT a.* 
					FROM ". LEAVESMST ." a 
					WHERE a.`leaveid` = '$leaveid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()!". $this->cn->error;
				goto exitme;
			}

			$row = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){	
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			
			exitme:
			return $res;
		}
		
		function cancelLeaveRequest($data){
			$res = array();
			$res['err'] = 0;
			$leaveid = $data['leaveid'];
			$today = TODAY;

			$sql = "UPDATE ". LEAVESMST ." 
					SET ". LEAVESMST .".canceleddate = '$today', ". LEAVESMST .".status = -2 
					WHERE ". LEAVESMST .".leaveid = '$leaveid' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func cancelLeaveRequest()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function updateLeaveRequest($data){
			$res['err'] = 0;
			$userid = $data['userid'];
			$leaveid = $data['leaveid'];
			$attachment = $data['attachment'] == "" ? "" : $data['attachment'];
			// $sesid = genuri($leaveid);
			if($data['leavefrom'] != ""){
				$leavefrom = formatDate("Y-m-d",$data['leavefrom']);
			}else{
				$leavefrom = "1900-01-01 00:00:00";
			}
			if($data['leaveto'] != ""){
				$leaveto = formatDate("Y-m-d",$data['leaveto']);
			}else{
				$leaveto = "1900-01-01 00:00:00";
			}
			$leavetype = $data['leavetype'];
			$leaveduration = $data['leaveduration'];
			$leavereason = $data['leavereason'] == "" ? "" : addslashes($data['leavereason']);
			$today = TODAY;
			$noofdays = $data['noofdays'];

			// // $sql = "INSERT INTO " .LEAVESMST. 
			// // 		"(leaveid, userid, leavefrom, leaveto, leavetype, leaveduration, noofdays, reason , createdby, createddate)
			// // 		VALUES('$leaveid', '$userid', '$leavefrom', '$leaveto', '$leavetype', '$leaveduration', '$noofdays', '$leavereason', '$userid', '$today')";

			$sql = "UPDATE ". LEAVESMST ." 
					SET ". LEAVESMST .".leavefrom = '$leavefrom', 
						". LEAVESMST .".leaveto = '$leaveto', 
						". LEAVESMST .".leavetype = '$leavetype', 
						". LEAVESMST .".leaveduration = '$leaveduration', 
						". LEAVESMST .".reason = '$leavereason', 
						". LEAVESMST .".attachment = '$attachment', 
						". LEAVESMST .".noofdays = '$noofdays', 
						". LEAVESMST .".modifiedby = '$userid', 
						". LEAVESMST .".modifieddate = '$today'  
					WHERE ". LEAVESMST .".leaveid = '$leaveid' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateLeaveRequest()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		public function updateLeaveAttachment($data){
			$res = array();
			$res['err'] = 0;
			$leaveid = $data['leaveid'];
			$attachment = $data['attachment'] == "" ? "" : $data['attachment'];
			$today = TODAY;

			$sql = "UPDATE ". LEAVESMST ." 
					SET ". LEAVESMST .".attachment = '$attachment' 
					WHERE ". LEAVESMST .".leaveid = '$leaveid' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateLeaveAttachment()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function getAllLeaveSummary(){
			$res = array();
			$res['err'] = 0;
			// $userid = $data['userid'];

			$sql = "SELECT " . LEAVESMST . ".* 
					,DATE_FORMAT(" . LEAVESMST . ".leavefrom, '%a %d %b %y') AS leavefromdt 
					,DATE_FORMAT(" . LEAVESMST . ".leaveto, '%a %d %b %y') AS leavetodt 
					,DATE_FORMAT(" . LEAVESMST . ".createddate, '%a %d %b %y') AS createddt 
					,(CASE WHEN ". LEAVESMST .".`status` = 0 THEN 'Level 2 Approval Pending' 
		      		  		WHEN ". LEAVESMST .".`status` = 1 THEN 'Approved' 
		      		  		WHEN ". LEAVESMST .".`status` = -1 THEN 'Rejected' 
		      		  		WHEN ". LEAVESMST .".`status` = -2 THEN 'Cancelled' 
		              		ELSE '' 
		        		END) as leavestatus
		        	,(CASE WHEN ". LEAVESMST .".`approvalstatusindirect` = 0 THEN 'Level 1 Approval Pending' 
		      		  		WHEN ". LEAVESMST .".`approvalstatusindirect` = 1 THEN 'Level 1 Approval Approved' 
		      		  		WHEN ". LEAVESMST .".`approvalstatusindirect` = -1 THEN 'Level 1 Approval Rejected' 
		      		  		WHEN ". LEAVESMST .".`approvalstatusindirect` = -2 THEN 'Cancelled' 
		              		ELSE '' 
		        		END) as leavestatusindirect
					,CONCAT
					FROM " . LEAVESMST . " 
					LEFT JOIN ". ABAPEOPLESMST ." a
						ON a.userid = " . LEAVESMST . " .userid
					ORDER BY " . LEAVESMST . ".createddate";
					// ,b.description as leavetypedesc
					// ,CONCAT(c.fname,' ',c.lname) AS approvedbyname 
					// LEFT JOIN ". LEAVECREDITSMST ." a 
					// 	ON a.leavetypeid = " . LEAVESMST . ".leavetype  AND a.userid = " . LEAVESMST . ".userid 
					// LEFT JOIN ". BENEFITSMST ." b 
					// 	ON b.benefitini = a.leavetype AND b.benefittype = 'leave' 
					// LEFT JOIN ". ABAPEOPLESMST ." c 
					// 	ON c.userid = " . LEAVESMST . ".approvedby  
					// WHERE " . LEAVESMST . ".userid = '$userid' 
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveSummary(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){	
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function getLeaveDetails($leaveid){
			$res = array();
			$res['err'] = 0;
			// $userid = $data['userid'];

			$sql = "SELECT * FROM " . LEAVESDTL . " WHERE " . LEAVESDTL . ".`leaveid` = '$leaveid' ORDER BY " . LEAVESDTL . ".`leavedate` ASC";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func " . __FUNCTION__ . "()". $this->cn->error;
				// goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){	
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			// exitme:
			return $res;
		}

		function getPendingLeavesUnderUser($data){
			$res = array();
			$userid =			$data['userid'];
			$viewtype =			$data['viewtype'];
			$canapprreject = 	$data['canapprreject'];
			$ofclist = 			$data['ofclist'];

			$whereval = '';
			$ofcforwhere = '';

			if($canapprreject) {
				if(count($ofclist) > 0) {
					foreach($ofclist as $eachofc) {
						$ofcforwhere .= "'$eachofc',";
					}
					$ofcforwhere = substr($ofcforwhere,0,-1);
					$ofcforwhere = " AND b.office IN ($ofcforwhere) ";
				}
				$whereval = " AND (b.`reportstoid` = '$userid' OR b.`reportstoindirectid` = '$userid' OR b.userid = '$userid') $ofcforwhere";

				switch($viewtype) {
					case 'department':
					$whereval = " AND (b.`reportstoid` = '$userid' OR b.`reportstoindirectid` = '$userid' OR b.userid = '$userid') $ofcforwhere";
						break;
					case 'ofclist':
						$whereval = $ofcforwhere;
						break;
					default:
						break;
				}
			} else {
				$whereval = " AND (b.`reportstoid` = '$userid' OR b.`reportstoindirectid` = '$userid') ";
			}


			// WHERE (" . ABAPEOPLESMST . ".`reportstoid` = '$userid' OR " . ABAPEOPLESMST . ".`reportstoindirectid` = '$userid') 
			// 	AND " . ABAPEOPLESMST . ".`status` = 1 AND " . ABAPEOPLESMST . ".`contactcategory` = 1 AND a.`status` = 0 ";



			$sql = "SELECT COUNT(a.id) AS pending_count 
					FROM " . ABAPEOPLESMST . " b
					LEFT JOIN ". LEAVESMST ." a 
							ON a.`userid` = b.`userid` 
					WHERE a.`status` = 0 $whereval ";

			// $res['sql'] = $sql;
			// $rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func " . __FUNCTION__ . "()". $this->cn->error;
				// goto exitme;
			}
			// while($row = $qry->fetch_array(MYSQLI_ASSOC)){	
			// 	$rows[] = $row;
			// }
			$res['data'] = $qry->fetch_array(MYSQLI_ASSOC);
			// exitme:
			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>