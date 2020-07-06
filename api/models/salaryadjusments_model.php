<?php
	class SalaryAdjustmentsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function genSalaryAdjusmentID($userid){
			$today = formatDate("ym",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . PAYROLLSALARYADJUSMENTS . " WHERE DATE_FORMAT(" . PAYROLLSALARYADJUSMENTS . ".createddate,'%y%m') = '$today'";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "SADJ" . formatDate("ymd",TODAY) . "00";
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

		function getActiveSalaryAdjusments($data){
			$res = array();
			$res['err'] = 0;

			$payrollmasterid = $data['payrollmasterid'];
			
			$sql = "SELECT " . PAYROLLSALARYADJUSMENTS . ".*  
						,CONCAT(a.fname,' ',a.lname) AS eename 
						,b.`dddescription` AS misdesc
					FROM ". PAYROLLSALARYADJUSMENTS ."
					LEFT JOIN ". ABAPEOPLESMST ." a
						ON a.`userid` = " . PAYROLLSALARYADJUSMENTS . ".`userid`
					LEFT JOIN ". DROPDOWNSMST ." b
						ON b.ddid = " . PAYROLLSALARYADJUSMENTS . ".`salaryadjusmenttype`
						AND b.dddisplay = 'saladjtype'
						AND b.status = 1
					WHERE ". PAYROLLSALARYADJUSMENTS .".payrollmasterid = '$payrollmasterid'
					AND ". PAYROLLSALARYADJUSMENTS .".status = 1 ";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getOfcAssignedHR()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		function addNewSalaryAdjusment($data){
			$res = array();
			$res['err'] = 0;

			$saladjid = $data['saladjid'];
			$userid = $data['userid'];
			$curuserid = $data['curuserid'];
			$saladjtype = $data['saladjtype'];
			$saladjdesc = $data['saladjdesc'] == "" ? "" : addslashes($data['saladjdesc']);
			$saladjamount =  $data['saladjamount'] == "" ? 0 : str_replace(',','',$data['saladjamount']);
			$saladjdeminimisamount = $data['saladjdeminimisamount'] == "" ? 0 : str_replace(',','',$data['saladjdeminimisamount']);
			$periodno = $data['periodno'];
			$paydate = formatDate("Y-m-d", $data['paydate']);
			$payrollmasterid = $data['payrollmasterid'];
			$totalsalaryadjustmentamount = $saladjamount + $saladjdeminimisamount;
			$today = TODAY;

			$sql = "INSERT INTO ". PAYROLLSALARYADJUSMENTS ." 
							  (salaryadjid,userid,salaryadjusmenttype,description,basicsalaryadjustmentamount,deminimisadjamount,totalsalaryadjustmentamount,periodno,paydate,createdby,createddate,payrollmasterid)
						VALUES('$saladjid','$curuserid','$saladjtype','$saladjdesc','$saladjamount','$saladjdeminimisamount','$totalsalaryadjustmentamount','$periodno','$paydate','$userid','$today','$payrollmasterid')";
						
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

		function updateSalaryAdjustment($data){
			$res = array();
			$res['err'] = 0;

			$saladjid = $data['saladjid'];
			$userid = $data['userid'];
			$curuserid = $data['curuserid'];
			$saladjtype = $data['saladjtype'];
			$saladjdesc = $data['saladjdesc'] == "" ? "" : addslashes($data['saladjdesc']);
			$saladjamount =  $data['saladjamount'] == "" ? 0 : str_replace(',','',$data['saladjamount']);
			$saladjdeminimisamount = $data['saladjdeminimisamount'] == "" ? 0 : str_replace(',','',$data['saladjdeminimisamount']);
			$periodno = $data['periodno'];
			$paydate = formatDate("Y-m-d", $data['paydate']);
			$payrollmasterid = $data['payrollmasterid'];
			$totalsalaryadjustmentamount = $saladjamount + $saladjdeminimisamount;
			$today = TODAY;

			$sql = "UPDATE ". PAYROLLSALARYADJUSMENTS ." 
					SET ". PAYROLLSALARYADJUSMENTS .".userid = '$curuserid',
						". PAYROLLSALARYADJUSMENTS .".salaryadjusmenttype = '$saladjtype',
						". PAYROLLSALARYADJUSMENTS .".description = '$saladjdesc',
						". PAYROLLSALARYADJUSMENTS .".basicsalaryadjustmentamount = '$saladjamount',
						". PAYROLLSALARYADJUSMENTS .".deminimisadjamount = '$saladjdeminimisamount',
						". PAYROLLSALARYADJUSMENTS .".totalsalaryadjustmentamount = '$totalsalaryadjustmentamount',
						". PAYROLLSALARYADJUSMENTS .".periodno = '$periodno',
						". PAYROLLSALARYADJUSMENTS .".paydate = '$paydate',
						". PAYROLLSALARYADJUSMENTS .".modifiedby = '$userid',
						". PAYROLLSALARYADJUSMENTS .".modifieddate = '$today',
						". PAYROLLSALARYADJUSMENTS .".payrollmasterid = '$payrollmasterid'
					WHERE ". PAYROLLSALARYADJUSMENTS .".salaryadjid = '$saladjid'";
						
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

		public function closeDB(){
			$this->cn->close();
		}
	}
?>