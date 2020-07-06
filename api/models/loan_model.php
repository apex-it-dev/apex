<?php
	// include_once('auditlogs.php');
	class LoanModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		public function getEeWithLoan($data){
			$res = array();
			$ofc = $data['ofc'];
			$ofccount = count($ofc);
			$ofcfilter = '';
			if($ofccount > 0) {
				$ofcfilter = 'AND (';
				foreach ($ofc as $key => $ofcid) {
					$ofcfilter .= "b.`office` = '$ofcid'";
					if($ofccount > $key + 1) {
						$ofcfilter .= " OR ";
					}
				}
				$ofcfilter .= ')';
			}
			$res['err'] = 0;
			$sql = "SELECT a.*
						,CONCAT(b.`fname`, ' ', b.`lname`) AS eename
						,c.`dddescription` AS loantypename
						,d.`dddescription` AS paymentfrequencydesc
						,DATE_FORMAT(a.`startdate`,'%a %d %b %Y') AS newstartdate
						,(SELECT SUM(e.`amountpaid`) 
						  FROM ". LOANDTL ." e
						  WHERE e.`loanidmst` = a.`loanidmst`) AS totalpaid 
						,(loanamount - (SELECT totalpaid)) as remaining 
					FROM ".LOANMST." a
					LEFT JOIN ".ABAPEOPLESMST." b
						ON b.`userid` = a.`userid`
					LEFT JOIN ".DROPDOWNSMST." c
						ON c.`ddid` = a.`loantype` 
							AND c.`dddisplay` = 'loantype'
					LEFT JOIN ".DROPDOWNSMST." d
						ON d.`ini` = a.`paymentfrequency` 
							AND d.`dddisplay` = 'loanfrequency'
					WHERE a.`status` = 1 $ofcfilter 
					ORDER BY a.`createdby` DESC
					";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
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
		

		public function getEeWithLoanById($id){
			$res = array();
			
			$res['err'] = 0;
			$sql = "SELECT a.*
						,CONCAT(b.`fname`, ' ', b.`lname`) AS eename
						,c.`dddescription` AS loantypename
						,d.`dddescription` AS paymentfrequencydesc
						,DATE_FORMAT(a.`startdate`,'%a %d %b %Y') AS newstartdate
						,(SELECT SUM(e.`amountpaid`) 
						  FROM ". LOANDTL ." e
						  WHERE e.`loanidmst` = a.`loanidmst`) AS totalpaid 
						,(loanamount - (SELECT totalpaid)) as remaining 
					FROM ".LOANMST." a
					LEFT JOIN ".ABAPEOPLESMST." b
						ON b.`userid` = a.`userid`
					LEFT JOIN ".DROPDOWNSMST." c
						ON c.`ddid` = a.`loantype` 
							AND c.`dddisplay` = 'loantype'
					LEFT JOIN ".DROPDOWNSMST." d
						ON d.`ini` = a.`paymentfrequency` 
							AND d.`dddisplay` = 'loanfrequency'
					WHERE a.`status` = 1 AND a.`loanidmst` = '$id' 
					ORDER BY a.`createdby` DESC
					";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
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

		public function getLoanOfEE($loanidmst){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT a.* 
						,DATE_FORMAT(a.`duedate`,'%a %d %b %Y') AS newduedate
						,(CASE WHEN a.`totalpaid` > a.`amountdue` THEN 1 ELSE 0 END) AS ispaid 
					FROM " . LOANDTL . " a 
					WHERE a.`loanidmst` = '$loanidmst' AND a.`display` = 1 
					ORDER BY a.`loanid` ASC";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$row['actions'] = array("id"=>$row['id'],
										"ispaid"=>$row['ispaid'], 
										"loanid"=>$row['loanid'],
										"state"=>$row['state']
									);
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		public function payLoan($data){
			$res = array();
			$res['err'] = 0;
			
			$loanid = $data['loanid'];
			$loanidmst = $data['loanidmst'];
			$userid = $data['userid'];
			$amountdue = $data['amountdue'];
			$amountpaid = $data['amountpaid'];
			$totalpaid = $data['totalpaid'];
			$runningbalance = $data['runningbalance'];
			$createdby = $data['createdby'];
			$createddate = $data['createddate'];
			
			$amountpaid = str_replace(',','',$data['amountpaid']);

			$sql = "INSERT INTO " . LOANDTL . "
							(loanid,loanidmst,userid,runningbalance,amountdue,amountpaid,totalpaid,duedate,paiddate,display,state,createdby,createddate)
					  VALUES('$loanid','$loanidmst','$userid','$runningbalance','$amountdue','$amountpaid','','','','','','','')
					";
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

		public function payPeriod($data){
			$res = array();
			$res['err'] = 0;
			
			$loanid = $data['loanid'];
			$loanidmst = $data['loanidmst'];
			$userid = $data['userid'];
			$amountdue = $data['amountdue'];
			$amountpaid = $data['amountpaid'];
			$totalpaid = $data['totalpaid'];
			$runningbalance = $data['runningbalance'];
			$duedate = $data['duedate'];
			$paiddate = $data['paiddate'];
			$createdby = $data['createdby'];
			$createddate = $data['createddate'];
			$state = $data['state'];
			$subid = $data['subid'];
			
			$amountpaid = str_replace(',','',$data['amountpaid']);
			
			$sql = "INSERT INTO " . LOANDTL . "
							(loanid,loanidmst,userid,runningbalance,amountdue,amountpaid,totalpaid,state,duedate,paiddate,display,createdby,createddate,subid)
					  VALUES('$loanid','$loanidmst','$userid','$runningbalance','$amountdue','$amountpaid','$totalpaid','$state','$duedate','$paiddate','1','$createdby','$createddate','$subid')
					";
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

		public function loanIdExist($loanid) {
			$res = array();
			$res['checkidexist'] = '';

			$sql = "SELECT COUNT(a.`id`) AS loanidexist
					,a.`amountdue` 
					FROM " . LOANDTL . " a 
					WHERE a.`loanid` = '$loanid'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}
			$res['checkidexist'] = $qry->fetch_array(MYSQLI_ASSOC);

			exitme:
			return $res;
		}

		function updateNextId($data){
			$res = array();
			$res['err'] = 0;
			$today = TODAY;
			$userid = $data['userid'];
			$loanid = $data['nextloanid'];
			$amountdue = str_replace(',','',$data['nextloanamountdue']);

			$sql = "UPDATE " . LOANDTL . " 
					SET " . LOANDTL . ".`amountdue` = '$amountdue'
					   ," . LOANDTL . ".`modifiedby` = '$userid'
					   ," . LOANDTL . ".`modifieddate` = '$today'
					   ," . LOANDTL . ".`state` = 'current' 
					WHERE " . LOANDTL . ".`loanid` = '$loanid'";
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

		function getLoanType(){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT a.*
				FROM ". DROPDOWNSMST ." a
				WHERE a.`dddisplay` = 'loantype'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
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

		function getPaymentFrequency() {
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT a.*
				FROM ". DROPDOWNSMST ." a
				WHERE a.`dddisplay` = 'loanfrequency'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
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

		function loanExist($data) {
			$res = array();
			$res['err'] = 0;
			$res['result'] = array('loanexist'=>'');

			$userid = $data['userid'];
			$loantype = $data['loantype'];

			$sql = "SELECT COUNT(a.`id`) AS loanexist 
				FROM ". LOANMST ." a
				WHERE a.`userid` = '$userid' AND a.`loantype` = '$loantype' AND a.`loanstatus` = 0";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}

			$res['result'] = $qry->fetch_array(MYSQLI_ASSOC);

			exitme:
			return $res;
		}

		function addNewLoan($data) {
			$res = array();
			$res['err'] = 0;

			
			$userid = $data['userid'];
			$loantype = $data['loantype'];
			$paymentfreq = $data['paymentfreq'];
			$loanamount = str_replace(',','',$data['loanamount']);
			$amountdue = str_replace(',','',$data['amountdue']);
			$startdate = formatDate("Y-m-d", $data['startdate']);
			$createdby = $data['createdby'];
			$loanidmst = $data['loanidmst'];
			$today = TODAY;

			$sql = "INSERT INTO ". LOANMST ." 
							  (loanidmst,userid,loantype,loanamount,paymentfrequency,startdate,createdby,createddate,amountdue)
						VALUES('$loanidmst','$userid','$loantype','$loanamount','$paymentfreq','$startdate','$createdby','$today','$amountdue')";
						
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

		function getLoanMstTable() {
			$sql = "DESCRIBE ". LOANDTL;
			$qry = $this->cn->query($sql);
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[$row['Field']] = null;
			}
			return $rows;
		}

		function lastLoanPay($loanidmst) {
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT a.* 
					FROM ". LOANDTL ." a 
					WHERE a.`loanidmst` = '$loanidmst' AND a.`display` = 1 
					ORDER BY a.`loanid` DESC 
					LIMIT 1";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
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
		
		function getPrevPaid($loanid){
			$res = array();
			$res['err'] = 0;
			$res['result'] = '';

			$sql = "SELECT a.* 
					FROM ". LOANDTL ." a 
					WHERE a.`loanid` = '$loanid' 
					ORDER BY a.`subid` DESC";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}

			$res['result'] = $qry->fetch_array(MYSQLI_ASSOC);
			exitme:
			return $res;
		}

		function getTotalPaid($loanid){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT SUM(a.`amountpaid`) as totalpaid 
					FROM ". LOANDTL ." a 
					WHERE a.`loanid` = '$loanid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'Error on ' . __FUNCTION__ . '(): ' . $this->cn->error;
				goto exitme;
			}

			// $rows = array();
			// while($row = $qry->fetch_array(MYSQLI_ASSOC)){
			// 	$rows[] = $row;
			// }
			// $res['rows'] = $rows;

			exitme:
			return $qry->fetch_array(MYSQLI_ASSOC);
		}

		function updatePrevLoan($data){
			$res = array();
			$res['err'] = 0;
			$loanid = $data['loanid'];
			$modifiedby = $data['createdby'];
			$modifieddate = $data['createddate'];
			$subid = $data['prevsubid'];

			$sql = "UPDATE " . LOANDTL . " 
					SET " . LOANDTL . ".`display` = 0 
					   ," . LOANDTL . ".`modifiedby` = '$modifiedby'
					   ," . LOANDTL . ".`modifieddate` = '$modifieddate'
					WHERE " . LOANDTL . ".`loanid` = '$loanid' 
						AND " . LOANDTL . ".`subid` = $subid 
						AND " . LOANDTL . ".`state` = 'continue'";
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

		function closeLoan($data){
			$res = array();
			$res['err'] = 0;

			$modifiedby = $data['userid'];
			$modifieddate = TODAY;
			$loanidmst = $data['loanidmst'];

			$sql = "UPDATE " . LOANMST . " 
					 SET " . LOANMST . ".`loanstatus` = 1 
						," . LOANMST . ".`modifiedby` = '$modifiedby' 
						," . LOANMST . ".`modifieddate` = '$modifieddate' 
					 WHERE " . LOANMST . ".`loanidmst` = '$loanidmst'";
					 
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