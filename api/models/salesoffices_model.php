<?php
	// include_once('auditlogs.php');
	class SalesOfficesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		// // SAVE CATEGORY
		// public function saveCat($data){
		// 	$name = $data['txtName'];
		// 	$desc = $data['txtDesc'];
		// 	$abaUser = $data['abaUser'];
		// 	$today = $data['today'];
			
		// 	$sql = "INSERT INTO " . DESIGNATIONS . " (description,createdby,createddate) VALUES('$desc','$abaUser','$today')";

		// 	$qry = $this->cn->query($sql);
		// 	if(!$qry){
		// 		echo $this->cn->error;
		// 	}
		// 	$id = $this->cn->insert_id;

		// 	$this->cn->close();

		// 	// AUDIT LOGS
		// 	$adtlogs = array();
		// 	$adtlogs['method'] = "CREATE";
		// 	$adtlogs['pname'] = "designations";
		// 	$adtlogs['dtl1'] = $sql;
		// 	$adtlogs['dtl2'] = "";
		// 	$adtlogs['tbl'] = DESIGNATIONS;
		// 	$adtlogs['rid'] = $id;
		// 	$adtlogs['abaUser'] = $abaUser;
		// 	$adtlogs['today'] = $today;

		// 	$adt = new AuditLogs;
		// 	$adt->saveAuditLog($adtlogs);
		// }

		// // UPDATE CATEGORY
		// public function updateCat($data){
		// 	$id = $data['id'];
		// 	$name = $data['txtName'];
		// 	$desc = $data['txtDesc'];
		// 	$stat = $data['txtStat'];
		// 	$abaUser = $data['abaUser'];
		// 	$today = $data['today'];
			
		// 	$sql = "UPDATE " . CATEGORIESMST . " SET name = '$name', description = '$desc', status = '$stat', modifiedby = '$abaUser', modifieddate = '$today' WHERE id = '$id'";

		// 	$qry = $this->cn->query($sql);
		// 	if(!$qry){
		// 		echo $this->cn->error;
		// 	}

		// 	$this->cn->close();

		// 	// AUDIT LOGS
		// 	$adtlogs = array();
		// 	$adtlogs['method'] = "UPDATE";
		// 	$adtlogs['pname'] = "categories";
		// 	$adtlogs['dtl1'] = $sql;
		// 	$adtlogs['dtl2'] = "";
		// 	$adtlogs['tbl'] = CATEGORIESMST;
		// 	$adtlogs['rid'] = $id;
		// 	$adtlogs['abaUser'] = $abaUser;
		// 	$adtlogs['today'] = $today;

		// 	$adt = new AuditLogs;
		// 	$adt->saveAuditLog($adtlogs);
		// }

		// GET DATA by ALL or ID
		public function getSalesOffices($id=""){
			$where = " WHERE 1 AND " . SALESOFFICESMST . ".showmain = 1";
			if(!empty($id)){
				$where .= " AND " . SALESOFFICESMST . ".id = '$id'";
			}
			$sql = "SELECT " . SALESOFFICESMST . ".*,(CASE WHEN " . SALESOFFICESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname FROM " . SALESOFFICESMST . $where . " ORDER BY " . SALESOFFICESMST . ".`description`";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		// GET DATA by sales office ID
		public function getSalesOfficesGM($soid=""){
			$res = array();
			$where = "";
			$res['err'] = 0;
			if(!empty($soid)){
				$where .= " AND " . SALESOFFICESMST . ".salesofficeid = '$soid' ";
			}
			$sql = "SELECT " . SALESOFFICESMST . ".* 
						, CONCAT(" . ABAPEOPLESMST . ".`fname`,' '," . ABAPEOPLESMST . ".`lname`,' '," . ABAPEOPLESMST . ".`cnname`) AS gmname 
						, " . ABAPEOPLESMST . ".`emailaddress` AS gmemail 
					FROM " . SALESOFFICESMST . " 
					LEFT JOIN " . ABAPEOPLESMST . " 
						ON " . ABAPEOPLESMST . ".abaini = " . SALESOFFICESMST . ".assignedgm 
							AND " . ABAPEOPLESMST . ".status = 1 
							AND " . SALESOFFICESMST . ".status = 1 
					WHERE " . SALESOFFICESMST . ".showmain = 1 $where
					ORDER BY " . SALESOFFICESMST . ".`sort`";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getSalesOfficesGM()! " . $this->cn->error;
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

		// GET DATA by sales office ID
		public function getSalesOfficesGMbyDesc($desc=""){
			$res = array();
			$where = "";
			$res['err'] = 0;
			if(!empty($desc)){
				if($desc == "runsha"){
					$desc = "abasha";
				}
				if($desc == "runbei"){
					$desc = "ababei";
				}
				$where .= " AND " . SALESOFFICESMST . ".incofcs LIKE '%$desc%' ";
			}
			$sql = "SELECT " . SALESOFFICESMST . ".* 
						, CONCAT(" . ABAPEOPLESMST . ".`fname`,' '," . ABAPEOPLESMST . ".`lname`,' '," . ABAPEOPLESMST . ".`cnname`) AS gmname 
						, " . ABAPEOPLESMST . ".`emailaddress` AS gmemail 
					FROM " . SALESOFFICESMST . " 
					LEFT JOIN " . ABAPEOPLESMST . " 
						ON " . ABAPEOPLESMST . ".abaini = " . SALESOFFICESMST . ".assignedgm 
							AND " . ABAPEOPLESMST . ".status = 1 
							AND " . SALESOFFICESMST . ".status = 1 
					WHERE " . SALESOFFICESMST . ".showmain = 1 $where
					ORDER BY " . SALESOFFICESMST . ".`sort`";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getSalesOfficesGMbyDesc()! " . $this->cn->error;
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

		public function updateSalesOfficeGM($data){
			$salesofficeid = $data['salesofficeid'];
			$assignedgm = $data['assignedgm'];
			$abauser = $data['abauser'];
			$today = TODAY;
			$res = array();
			$res['err'] = 0;

			$sql = "UPDATE " . SALESOFFICESMST . " 
					SET " . SALESOFFICESMST . ".assignedgm = '$assignedgm', 
						" . SALESOFFICESMST . ".modifiedby = '$abauser', 
						" . SALESOFFICESMST . ".modifieddate = '$today' 
					WHERE " . SALESOFFICESMST . ".salesofficeid = '$salesofficeid' ";
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateSalesOfficeGM()! " . $this->cn->error;
				goto exitme;
			}
			exitme:
			// $this->cn->close();
			return $res;
		}
		
		public function getSalesOfficesOnly(){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$sql = "SELECT " . SALESOFFICESMST . ".*
					FROM " . SALESOFFICESMST . " WHERE " . SALESOFFICESMST . ".showonsup = 1 AND " . SALESOFFICESMST . ".status = 1
					ORDER BY " . SALESOFFICESMST . ".`description`";
			
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getSalesOfficesOnly()! " . $this->cn->error;
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
		
		public function getSalesOfficeByDesc($salesofc){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			//$salesofc = "sscceb";
			
			$sql = "SELECT " . SALESOFFICESMST . ".*
					FROM " . SALESOFFICESMST . " WHERE " . SALESOFFICESMST . ".description = '$salesofc' ";
			
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getSalesOfficeByDesc()! " . $this->cn->error;
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

		public function getSalesOfficeId($salesofc){
			$res = array();
			// $rows = array();
			$res['err'] = 0;
			//$salesofc = "sscceb";
			$sql = "SELECT " . SALESOFFICESMST . ".salesofficeid
					FROM " . SALESOFFICESMST . " WHERE " . SALESOFFICESMST . ".description = '$salesofc' ";
			
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getSalesOfficeId()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows = $row;
			}
			$res['rows'] = $rows;
			exitme:
			// $this->cn->close();
			return $rows;
		}

		public function getSalesOfficeByOfcId($id){
			$res = array();
			// $rows = array();
			$res['err'] = 0;
			//$salesofc = "sscceb";
			$sql = "SELECT " . SALESOFFICESMST . ".* 
					FROM " . SALESOFFICESMST . " WHERE " . SALESOFFICESMST . ".salesofficeid = '$id' ";
			
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func " .__FUNCTION__."()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows = $row;
			}
			$res['rows'] = $rows;
			exitme:
			// $this->cn->close();
			return $rows;
		}

		public function getSalesOfficesMain(){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$sql = "SELECT " . SALESOFFICESMST . ".* 
					FROM " . SALESOFFICESMST . " WHERE " . SALESOFFICESMST . ".showmain = 1 AND " . SALESOFFICESMST . ".status = 1 
					ORDER BY " . SALESOFFICESMST . ".`sort`";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getSalesOfficesMain()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		public function getOfcAssignedHR($ofc){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". SALESOFFICESMST .".`salesofficeid`
						,". SALESOFFICESMST .".`description`
						,". SALESOFFICESMST .".`assignedhr`
						,". ABAPEOPLESMST .".`emailaddress`
						,CONCAT(". ABAPEOPLESMST .".`fname`,' ',". ABAPEOPLESMST .".`lname`) AS eename
					FROM ". SALESOFFICESMST ."
					LEFT JOIN ". ABAPEOPLESMST ."
						ON ". ABAPEOPLESMST .".`abaini` = ". SALESOFFICESMST .".`assignedhr` 
						AND ". ABAPEOPLESMST .".`status` = 1 
						AND ". ABAPEOPLESMST .".`contactcategory` = 1
					WHERE ". SALESOFFICESMST .".salesofficeid = '$ofc' ";
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

		function getIncOfcs($ofc) {
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT *
					FROM ". SALESOFFICESMST ." a
					WHERE a.`incofcs` LIKE '%$ofc%' AND a.`status` = 1 ";
					
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ".__FUNCTION__."()! " . $this->cn->error;
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