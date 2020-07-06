<?php
	class CallInModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function genCallInID($userid){
			$today = formatDate("ym",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . CALLINMST . " WHERE DATE_FORMAT(" . CALLINMST . ".createddate,'%y%m') = '$today'";

			$qry = $this->cn->query($sql);

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "CALLIN" . formatDate("ymd",TODAY) . "00";
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

		function getAllCallIn($data){
			$res = array();
			$res['err'] = 0;

			$userid = $data['userid'];
			
			$sql = "SELECT " . CALLINMST . ".*
					,DATE_FORMAT(" . CALLINMST . ".createddate, '%a %d %b %Y') AS createddt 
					,(CASE WHEN ". CALLINMST .".`callintype` = 'trd' THEN 'Tardiness' 
		      		  		WHEN ". CALLINMST .".`callintype` = 'abs' THEN 'Absence'  
		              		ELSE '' END) as callintypedesc
		            ,(CASE WHEN ". CALLINMST .".`status` = '1' THEN 'Active' 
		      		  		WHEN ". CALLINMST .".`status` = '0' THEN 'Inactive'  
		              		ELSE '' END) as statusdesc
		            ,DATE_FORMAT(" . CALLINMST . ".eta, '%H:%i') AS eta
		            ,(SELECT " . LEAVESMST . ".`status` FROM hris_leaves WHERE " . LEAVESMST . ".leaveid = " . CALLINMST . ".`leaveid`) AS reportstostatus
  					,(SELECT " . LEAVESMST . ".`approvalstatusindirect` FROM hris_leaves WHERE " . LEAVESMST . ".leaveid = " . CALLINMST . ".`leaveid`) AS reportstoindirectstatus 
					FROM ". CALLINMST ." WHERE ". CALLINMST .".userid = '$userid' AND 
					". CALLINMST .".status = 1 ";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmgs'] = "error func getAllCallIn(). ". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:

			return $res;
		}

		function addCallIn($data){
			$res = array();
			$res['err'] = 0;

			$callinid = $data['callinid'];
			$userid = $data['userid'];
			$callintype = $data['callintype']; 
			$eta = formatDate("H:i:s", $data['eta']);
			$reason = $data['leavereason'] == "" ? "" : addslashes($data['leavereason']);
			$absenttype = $data['leavetype'];
			$today = TODAY;

			$sql = "INSERT INTO " . CALLINMST . "
						  (callinid, userid, callintype, absenttype, reason, eta, createdby, createddate)
					VALUES('$callinid', '$userid', '$callintype', '$absenttype', '$reason', '$eta', '$userid', '$today')";

			$qry = $this->cn->query($sql);
			// $res['sql'] = $sql;
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func addCallIn()!". $this->cn->error;
				goto exitme;
			}

			exitme:

			return $res;
		}

		function updateEmailNotifSent($data){
			$res = array();
			$res['err'] = 0;

			$userid = $data['userid'];
			$callinid = $data['callinid'];

			$sql = "UPDATE ". CALLINMST ." 
					SET ". CALLINMST .".emailnotifsent = 1  
					WHERE ". CALLINMST .".callinid = '$callinid' ";

			$qry = $this->cn->query($sql);
			// $res['sql'] = $sql;
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateEmailNotifSent()!". $this->cn->error;
				goto exitme;
			}

			exitme:

			return $res;
		}

		function updateLeaveIdCallin($data){
			$res = array();
			$res['err'] = 0;

			$userid = $data['userid'];
			$leaveid = $data['leaveid'];
			$callinid = $data['callinid'];

			$sql = "UPDATE ". CALLINMST ." 
					SET ". CALLINMST .".leaveid = '$leaveid'  
					WHERE ". CALLINMST .".callinid = '$callinid' ";

			$qry = $this->cn->query($sql);
			// $res['sql'] = $sql;
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateLeaveIdCallin()!". $this->cn->error;
				goto exitme;
			}

			exitme:

			return $res;

		}

		function getCallIn($data){
			$res = array();
			$res['err'] = 0;
				
			$callinid = $data['callinid'];
			$sql = "SELECT " . CALLINMST . ".*
					,DATE_FORMAT(" . CALLINMST . ".createddate, '%a %d %b %y') AS createddt 
					,(CASE WHEN ". CALLINMST .".`callintype` = 'trd' THEN 'Tardiness' 
		      		  		WHEN ". CALLINMST .".`callintype` = 'abs' THEN 'Absence'  
		              		ELSE '' END) as callintypedesc
		            ,DATE_FORMAT(" . CALLINMST . ".eta, '%H:%i') AS eta 
					FROM ". CALLINMST ." WHERE ". CALLINMST .".callinid = '$callinid' 
					AND ". CALLINMST .".status = 1";

			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmgs'] = "error func getCallIn(). ". $this->cn->error;
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