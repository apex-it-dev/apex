<?php
	class MiscellaneousModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function genMiscID($userid){
			$today = formatDate("ym",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . PAYROLLMISCELLANEOUS . " WHERE DATE_FORMAT(" . PAYROLLMISCELLANEOUS . ".createddate,'%y%m') = '$today'";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "MISC" . formatDate("ymd",TODAY) . "00";
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

		function addNewMiscellaneous($data){
			$res = array();
			$res['err'] = 0;

			$miscid = $data['miscid'];
			$userid = $data['userid'];
			$curuserid = $data['curuserid'];
			$misctype = $data['misctype'];
			$miscdesc = $data['miscdesc'] == "" ? "" : addslashes($data['miscdesc']);
			$miscamount = str_replace(',','',$data['miscamount']);
			$periodno = $data['periodno'];
			$paydate = formatDate("Y-m-d", $data['paydate']);
			$payrollmasterid = $data['payrollmasterid'];
			$today = TODAY;

			$sql = "INSERT INTO ". PAYROLLMISCELLANEOUS ." 
							  (miscid,userid,misctype,description,miscamount,periodno,paydate,createdby,createddate,payrollmasterid)
						VALUES('$miscid','$curuserid','$misctype','$miscdesc','$miscamount','$periodno','$paydate','$userid','$today','$payrollmasterid')";
						
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function updateMiscellaneous($data){
			$res = array();
			$res['err'] = 0;

			$miscid = $data['miscid'];
			$userid = $data['userid'];
			$curuserid = $data['curuserid'];
			$misctype = $data['misctype'];
			$miscdesc = $data['miscdesc'] == "" ? "" : addslashes($data['miscdesc']);
			$miscamount = str_replace(',','',$data['miscamount']);
			$periodno = $data['periodno'];
			$paydate = formatDate("Y-m-d", $data['paydate']);
			$payrollmasterid = $data['payrollmasterid'];
			$today = TODAY;

			
			$sql = "UPDATE ". PAYROLLMISCELLANEOUS ." 
					SET ". PAYROLLMISCELLANEOUS .".userid = '$curuserid',
						". PAYROLLMISCELLANEOUS .".misctype = '$misctype',
						". PAYROLLMISCELLANEOUS .".description = '$miscdesc',
						". PAYROLLMISCELLANEOUS .".miscamount = '$miscamount',
						". PAYROLLMISCELLANEOUS .".periodno = '$periodno',
						". PAYROLLMISCELLANEOUS .".paydate = '$paydate',
						". PAYROLLMISCELLANEOUS .".modifiedby = '$userid',
						". PAYROLLMISCELLANEOUS .".modifieddate = '$today'
					WHERE ". PAYROLLMISCELLANEOUS .".miscid = '$miscid' ";
						
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function getActiveMiscellaneous($data){
			$res = array();
			$res['err'] = 0;

			$payrollmasterid = $data['payrollmasterid'];
			
			$sql = "SELECT " . PAYROLLMISCELLANEOUS . ".*  
						,CONCAT(a.fname,' ',a.lname) AS eename 
						,b.`dddescription` AS misdesc
					FROM ". PAYROLLMISCELLANEOUS ."
					LEFT JOIN ". ABAPEOPLESMST ." a
						ON a.`userid` = " . PAYROLLMISCELLANEOUS . ".`userid`
					LEFT JOIN ". DROPDOWNSMST ." b
						ON b.ddid = " . PAYROLLMISCELLANEOUS . ".`misctype`
						AND b.dddisplay = 'misctype'
						AND b.status = 1
					WHERE ". PAYROLLMISCELLANEOUS .".payrollmasterid = '$payrollmasterid'
					AND ". PAYROLLMISCELLANEOUS .".status = 1 ";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getOfcAssignedHR()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$miscamount = array('miscamountfloatval' => (float) $row['miscamount']);
				$rows[] = array_merge($row, $miscamount);
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