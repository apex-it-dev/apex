<?php
	// include_once('auditlogs.php');
	class ActivitiesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function genNewNo(){
			$today = formatDate("ymd",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . CDMACTIVITIES . " WHERE DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%y%m%d') = '$today'";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "A" . formatDate("ymd",TODAY) . "00000";
			switch(strlen($cnt)){
				case 5:
					$newno = substr($pre, 0, -5) . $cnt; break;
				case 4:
					$newno = substr($pre, 0, -4) . $cnt; break;
				case 3:
					$newno = substr($pre, 0, -3) . $cnt; break;
				case 2:
					$newno = substr($pre, 0, -2) . $cnt; break;
				default:
					$newno = substr($pre, 0, -1) . $cnt; break;
			}

			return $newno;
		}

		public function saveActivity($data){
			$res = array();
			$res['err'] = 0;
			$newno = "";
			$sesid = "";
			// $newno = $this->genNewNo();
			// $sesid = genuri($newno);

			$type = $data['type'];
			$details = stripslashes($data['details']);
			$assignedto = $data['assignedto'];
			$acctid = $data['acctid'];
			$abauser = $data['abauser'];
			$userid = $data['userid'];
			$today = TODAY;

			$sql = "INSERT INTO " . CDMACTIVITIES . " (acttype,actdetails,acctid,assignedto,createdby,createddate,sesid,userid) 
					VALUES('$type','$details','$acctid','$userid','$userid','$today','$sesid','$userid') ";

			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveActivity()!"  . $this->cn->error;
				goto exitme;
			}
			exitme:
			return $res;
		}

		public function getActivities($acctid){
			$res = array();
			$rows=array();
			$sql = "SELECT " . CDMACTIVITIES . ".*
						,DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%a %d %b %y') AS createddt
						,DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%H:%i') AS createdtm
						,DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%y%m%d') AS creadt
						,a.abaini
					FROM " . CDMACTIVITIES . " 
					LEFT JOIN ". ABAPEOPLESMST ." a
						ON a.userid = " . CDMACTIVITIES . ".createdby AND a.status = 1
					WHERE " . CDMACTIVITIES . ".acctid = '$acctid' 
					ORDER BY " . CDMACTIVITIES . ".createddate DESC";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			return $res;
		}

		public function getActivitiesDashBoard($userid){
			$res = array();
			$rows=array();
			$sql = "SELECT " . CDMACTIVITIES . ".*
						,DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%a %d %b %y') AS createddt
						,DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%H:%i') AS createdtm
						,DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%y%m%d') AS creadt
                        ,a.abaini 
					FROM " . CDMACTIVITIES . " 
                    LEFT JOIN ". ABAPEOPLESMST ." a
						ON a.userid = " . CDMACTIVITIES . ".createdby AND a.status = 1 
					WHERE " . CDMACTIVITIES . ".userid = '$userid' 
					  AND " . CDMACTIVITIES . ".acttype <> 'login'
					  AND " . CDMACTIVITIES . ".createddate >= NOW() + INTERVAL -7 DAY 
					  AND " . CDMACTIVITIES . ".createddate <  NOW() + INTERVAL  0 DAY
					ORDER BY " . CDMACTIVITIES . ".createddate DESC";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>