<?php
	// include_once('auditlogs.php');
	class EmployeesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}
		function setInsertId($id){
			$this->id = $id;
		}
		public function getInsertId(){
			return $this->id;
		}
		
		public function genUserID($joineddate){	
			$sql = "SELECT * 
					FROM " . ABAPEOPLESMST . " 
					WHERE " . ABAPEOPLESMST . ".status = 1 
						AND " . ABAPEOPLESMST . ".contactcategory = 1 
						AND " . ABAPEOPLESMST . ".abaini NOT IN('pmhe','miji','puca','chda','jasw','joch','jazh','keha')
					ORDER BY " . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".lname ";
			$qry = $this->cn->query($sql);
			$cnt = 9;

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt++;
			}
			$pre = "A" . $joineddate . '-' . "00000";
			// $pre = "00000";
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
		// SAVE ABBREVIATION
		public function saveabaPeople($data){
			$eeid = !empty($data['eeid']) ? $data['eeid'] : "";
			$sal = !empty($data['salutation']) ? $data['salutation'] : "";
			$abaini = strtolower($data['abaini']);
			$fname = ucfirst($data['fname']);
			$mname = !empty($data['mname']) ? ucfirst($data['mname']) : "";
			$lname = strtoupper($data['lname']);
			$cnname = !empty($data['cnname']) ? $data['cnname'] : "";
			$email = !empty($data['email']) ? strtolower($data['email']) : "";
			$joindt = !empty($data['joineddate']) ? formatDate("Y-m-d",$data['joineddate']) : "";
			$gender = !empty($data['gender']) ? $data['gender'] : "";
			$bdate = !empty($data['birthdate']) ? formatDate("Y-m-d",$data['birthdate']) : "";
			$mobno = !empty($data['mobileno']) ? $data['mobileno'] : "";
			$degree = !empty($data['degree']) ? $data['degree'] : "";
			$desig = !empty($data['designation']) ? $data['designation'] : "";
			$dept = !empty($data['department']) ? $data['department'] : "";
			$salesofc = !empty($data['salesoffice']) ? $data['salesoffice'] : "";
			$eetype = !empty($data['eetype']) ? $data['eetype'] : "";
			$eecat = !empty($data['eecategory']) ? $data['eecategory'] : "";
			$bloodtype = !empty($data['bloodtype']) ? $data['bloodtype'] : "";
			$nat = !empty($data['nationality']) ? $data['nationality'] : "";
			$rel = !empty($data['religion']) ? $data['religion'] : "";
			$marital = !empty($data['maritalstatus']) ? $data['maritalstatus'] : "";
			$country = !empty($data['country']) ? $data['country'] : "";

			$emerconperson = !empty($data['emerconperson']) ? $data['emerconperson'] : null;
			$emerconaddress = !empty($data['emerconaddress']) ? $data['emerconaddress'] : null;
			$emerconrel = !empty($data['emerconrelationship']) ? $data['emerconrelationship'] : null;
			$emerconno = !empty($data['emerconno']) ? $data['emerconno'] : null;
			
			$abaUser = $data['abaUser'];
			$today = $data['today'];
			
			$sql = "INSERT INTO " . ABAPEOPLESMST . " (eeid,salutation,abaini,fname,mname,lname,cnname,email,birthdate,mobileno,joineddate,webhr_eecategory,webhr_designation,webhr_company,webhr_station,webhr_department,webhr_gender,,createdby,createddate)
					VALUES('$abaUser','$today')";

			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			$id = $this->cn->insert_id;
			$this->setInsertId($id);

			$this->cn->close();

			// AUDIT LOGS
			$adtlogs = array();
			$adtlogs['method'] = "CREATE";
			$adtlogs['pname'] = "abapeople";
			$adtlogs['dtl1'] = $sql;
			$adtlogs['dtl2'] = "";
			$adtlogs['tbl'] = ABAPEOPLESMST;
			$adtlogs['rid'] = $id;
			$adtlogs['abaUser'] = $abaUser;
			$adtlogs['today'] = $today;

			$adt = new AuditLogs;
			$adt->saveAuditLog($adtlogs);
		}

		// // UPDATE ABBREVIATION
		// public function updateAbvt($data){
		// 	$id = $data['id'];
		// 	$abvt = $data['txtAbvt'];
		// 	$word = $data['txtName'];
		// 	$cnword = $data['txtCNName'];
		// 	$desc = addslashes($data['txtDesc']);
		// 	$cat = $data['txtCat'];
		// 	$stat = $data['txtStat'];
		// 	$abaUser = $data['abaUser'];
		// 	$today = $data['today'];
			
		// 	$sql = "UPDATE " . ABBREVIATIONSMST . " SET abvt = '$abvt', word = '$word', cnword = '$cnword', description = '$desc', category = '$cat', status = '$stat', modifiedby = '$abaUser', modifieddate = '$today' WHERE id = '$id'";
			
		// 	$qry = $this->cn->query($sql);
		// 	if(!$qry){
		// 		echo $this->cn->error;
		// 		exit();
		// 	}

		// 	$this->cn->close();

		// 	// AUDIT LOGS
		// 	$adtlogs = array();
		// 	$adtlogs['method'] = "UPDATE";
		// 	$adtlogs['pname'] = "abbreviation";
		// 	$adtlogs['dtl1'] = $sql;
		// 	$adtlogs['dtl2'] = "";
		// 	$adtlogs['tbl'] = ABBREVIATIONSMST;
		// 	$adtlogs['rid'] = $id;
		// 	$adtlogs['abaUser'] = $abaUser;
		// 	$adtlogs['today'] = $today;

		// 	$adt = new AuditLogs;
		// 	$adt->saveAuditLog($adtlogs);
		// }

		// GET DATA by ALL or ID
		public function getabaPeople($id=""){
			$where = "WHERE 1 ";
			if(!empty($id)){
				$where .= " AND " . ABAPEOPLESMST . ".id = '$id'";
			}
			$sql = "SELECT z.* FROM ((SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. $where . " AND " . ABAPEOPLESMST . ".status = 1"
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`)";

			$sql .= " UNION ALL (SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. $where . " AND " . ABAPEOPLESMST . ".status = 0"
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`)) AS z ORDER BY z.fname";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function getActiveabaPeople($id=""){
			$res = array();
			$res['err'] = 0;
			$where = "";
			$sql = "SELECT " . ABAPEOPLESMST . ".*
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " WHERE " . ABAPEOPLESMST . ".status = 1 AND " . ABAPEOPLESMST . ".contactcategory = 1"
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getActiveabaPeople(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		public function getActiveabaPeopleWithId($id){
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
			$sql = "SELECT " . ABAPEOPLESMST . ".*
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						,(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		   ELSE '' 
		        		  END) as eesdesignation 
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".designationid = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".salesofficeid = " . ABAPEOPLESMST . ".office 
					 WHERE " . ABAPEOPLESMST . ".userid = '$id' ";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		public function getActiveReportingToPeopleWithId($listee){
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
			$whereval = "WHERE ";
			foreach($listee as $index=>$employee){
				if(isset($employee['reportstoid']) || isset($employee['reportstoindirectid'])) {
					if($index > 0){
						$whereval .= "OR ";
					}
				}
				if(isset($employee['reportstoid']) && isset($employee['reportstoindirectid'])) {
					$id = $employee['reportstoid'];
					$id1 = $employee['reportstoindirectid'];
					$whereval .= ABAPEOPLESMST . ".userid = '$id' OR " . ABAPEOPLESMST . ".userid = '$id1' ";
				} else if(isset($employee['reportstoid'])){
					$id = $employee['reportstoid'];
					$whereval .= ABAPEOPLESMST . ".userid = '$id' ";
				} else if(isset($employee['reportstoindirectid'])){
					$id = $employee['reportstoindirectid'];
					$whereval .= ABAPEOPLESMST . ".userid = '$id' ";
				}
			}

			$sql = "SELECT " . ABAPEOPLESMST . ".*
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						,(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		   ELSE '' 
		        		  END) as eesdesignation 
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".id = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".id = " . ABAPEOPLESMST . ".salesoffice 
					  " . $whereval;
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		public function getAllActiveabaPeopleWithId($data){
			$id = $data['userid'];
			$viewtype = $data['viewtype'];
			$ofclist = $data['ofclist'];
			// $userofc = $data['userofc'];
			$ofcforwhere = '';
			$whereVal = "";

			if(count($ofclist) > 0) {
				foreach($ofclist as $eachofc) {
					$ofcforwhere .= "'$eachofc',";
				}
				$ofcforwhere = substr($ofcforwhere,0,-1);
				$ofcforwhere = " AND " . ABAPEOPLESMST . ".office IN ($ofcforwhere) ";
			}

			switch($viewtype) {
				case 'department':
					$whereVal = " AND (" . ABAPEOPLESMST . ".userid = '$id' 
									OR " . ABAPEOPLESMST . ".reportstoid = '$id' 
									OR " . ABAPEOPLESMST . ".reportstoindirectid = '$id') $ofcforwhere ";
					break;
				case 'ofclist':
					$whereVal = $ofcforwhere;
					break;
				case 'self':
					$whereVal = " AND " . ABAPEOPLESMST . ".userid = '$id' ";
					break;
				default:
					break;
			}

			/*
			if(!$hasaccess && $ofconly == 'default'){ // head of department
				$whereVal = "AND (" . ABAPEOPLESMST . ".userid = '$id' 
								OR " . ABAPEOPLESMST . ".reportstoid = '$id' 
								OR " . ABAPEOPLESMST . ".reportstoindirectid = '$id') ";
			} else if (!$hasaccess && $ofconly != 'default') { // if hr, with access to all under his ofc
				if(isset($data['officeid'])){
					$ofc1 = $data["officeid1"];
					$whereVal = "AND (" . ABAPEOPLESMST . ".office = '$ofc' OR " . ABAPEOPLESMST . ".office = '$ofc1') ";
				} else {
					$whereVal = "AND " . ABAPEOPLESMST . ".office = '$ofc' ";
				}
			} else if (!$hasaccess) { // self, for employee
				$whereVal = "AND " . ABAPEOPLESMST . ".userid = '$id' ";
			}
			*/


			$sql = "SELECT " . ABAPEOPLESMST . ".*
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						,(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		   WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		   ELSE '' 
		        		  END) as eesdesignation 
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".id = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".id = " . ABAPEOPLESMST . ".salesoffice 
					 WHERE " . ABAPEOPLESMST . ".status = 1 AND " . ABAPEOPLESMST . ".contactcategory = 1 " . $whereVal . " ORDER BY " . ABAPEOPLESMST . ".abaini ASC ";
			$res['sql'] = $sql;

			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}

			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			
			// $this->cn->close();
			return $res;
		}

		// returns null
		public function getAllActiveabaPeopleWithId1($data){
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
			$hasaccess = $data['hasaccess'] == 0 || $data['hasaccess'] == '' ? FALSE : TRUE;
			$id = $data['userid'];
			$ofconly = $data['ofconly'];
			$ofc = $data['officeid']; //[0]['salesofficeid']
			$whereVal = "";

			if(!$hasaccess && $ofconly == 'default'){ // head of department
				$whereVal = "AND (a.userid = '$id' 
								OR a.reportstoid = '$id' 
								OR a.reportstoindirectid = '$id') ";
			} else if (!$hasaccess && $ofconly != 'default') { // if hr, with access to all under his ofc
				if(isset($data['officeid'])){
					$ofc1 = $data["officeid1"];
					$whereVal = "AND (a.office = '$ofc' OR a.office = '$ofc1') ";
				} else {
					$whereVal = "AND a.office = '$ofc' ";
				}
			} else if (!$hasaccess) { // self, for employee
				$whereVal = "AND a.userid = '$id' ";
			}
			// otherwise, return everything

			// if(($hasaccess == 0 || $hasaccess == '') && $ofconly == 'default'){ // no access
			// 	$whereVal = "AND (" . ABAPEOPLESMST . ".userid = '$id' 
			// 					OR " . ABAPEOPLESMST . ".reportstoid = '$id' 
			// 					OR " . ABAPEOPLESMST . ".reportstoindirectid = '$id') ";
			// } else if($ofconly != 'default' && $ofconly != '' && $ofc != ''){
			// 	$whereVal = "AND " . ABAPEOPLESMST . ".office = '$ofc' ";
			// } else {
			// 	$whereVal = "AND " . ABAPEOPLESMST . ".userid = '$id' ";
			// }

			$sql = "SELECT a.*
						,CONCAT(
							(CASE WHEN a.fname != '' THEN a.fname ELSE '' END),' '
							,(CASE WHEN a.mname != '' THEN a.mname ELSE '' END),' '
							,(CASE WHEN a.lname != '' THEN a.lname ELSE '' END)) as eename
						,(CASE WHEN a.`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						,(CASE WHEN a.`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		   WHEN a.`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		   WHEN a.`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		   WHEN a.`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		   ELSE '' 
		        		  END) as eesdesignation 
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(
							(CASE WHEN b.fname != '' THEN b.fname ELSE '' END),' '
							,(CASE WHEN b.mname != '' THEN b.mname ELSE ' ' END)
							,(CASE WHEN b.lname != '' THEN b.lname ELSE '' END)) AS reportsto
						,CONCAT(
							(CASE WHEN c.fname != '' THEN c.fname ELSE '' END),' '
							,(CASE WHEN c.mname != '' THEN c.mname ELSE ' ' END)
							,(CASE WHEN c.lname != '' THEN c.lname ELSE '' END)) AS reportstoindirect
						,b.`abaini` AS reportstoini
						,c.`abaini` AS reportstoindirectini
					FROM " . ABAPEOPLESMST. 
					" a LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".id = a.designation 
					  LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".id = a.salesoffice 
					  LEFT JOIN " . ABAPEOPLESMST . " b ON b.userid = a.reportstoid 
					  LEFT JOIN " . ABAPEOPLESMST . " c ON c.userid = a.reportstoindirectid 
					 WHERE a.status = 1 AND a.contactcategory = 1 " . $whereVal . " ORDER BY a.abaini ASC ";

			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			
			// $this->cn->close();
			return $rows;
		}

		
		
		public function getOfcID($ofcname){


			$sql = "SELECT " . SALESOFFICESMST . ".salesofficeid FROM " . SALESOFFICESMST . " WHERE " . SALESOFFICESMST . ".description = '$ofcname' ";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		public function getGMBDPeople($data){
			$userid = $data['userid'];
			$ofc = $data['ofc'];
			$and = "";
			if(!empty($ofc)){
				$and = " AND ". ABAPEOPLESMST	 .".webhr_station = '$ofc'";
				//for cn filter
				if($userid == 'A700101-00006'){
					$and = " AND " . ABAPEOPLESMST . ".webhr_station IN ('abasha', 'runsha', 'ababei', 'runbei') ";
				}
			}
			$sql = "SELECT ". ABAPEOPLESMST	 .". * ,
	  				(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
	      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
	              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
	              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDD' 
	              		ELSE '' 
	        		END) as eesdesignation 
					FROM
	  				". ABAPEOPLESMST." 
					WHERE ". ABAPEOPLESMST	 .".status = 1 $and
					HAVING eesdesignation <> '' 
					ORDER BY ". ABAPEOPLESMST	 .".abaini ASC";

			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		// SEARCH DATA
		public function searchAbacarian($val){
			$res = array();
			$data = strtolower($val['txtData']);
			$res['err'] = 0;

			// SQL abaini
			$sql = "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE " . ABAPEOPLESMST . ".abaini = '$data' AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE " . ABAPEOPLESMST . ".abaini LIKE '$data%' AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE " . ABAPEOPLESMST . ".abaini LIKE '%$data%' AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$sql .= " UNION ";

			// SQL NAME
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (" . ABAPEOPLESMST . ".fname = '$data' OR " . ABAPEOPLESMST . ".mname = '$data' OR " . ABAPEOPLESMST . ".lname = '$data') AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (" . ABAPEOPLESMST . ".fname LIKE '$data%' OR " . ABAPEOPLESMST . ".mname LIKE '$data%' OR " . ABAPEOPLESMST . ".lname LIKE '$data%') AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (" . ABAPEOPLESMST . ".fname LIKE '%$data%' OR " . ABAPEOPLESMST . ".mname LIKE '%$data%' OR " . ABAPEOPLESMST . ".lname LIKE '%$data%') AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".lname) LIKE '%$data%' OR CONCAT(" . ABAPEOPLESMST . ".lname,' '," . ABAPEOPLESMST . ".fname) LIKE '%$data%') AND " . ABAPEOPLESMST . ".contactcategory = 1 ";
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func searchAbacarian()! " . $this->cn->error;
				goto exitme;
			}
			
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			// $qry = $this->cn->multi_query($sql);
			// if(!$qry){
			// 	echo $this->cn->error;
			// 	exit();
			// }
			
			// $rows = array();
			// if($res = $this->cn->store_result()){
			// 	while($row = $res->fetch_assoc()){
			// 		$rows[] = $row;
			// 	}
			// }

			// $res['sql'] = $sql;
			$res['rows'] = $rows;
			exitme:

			return $res;
		}

		function chkDuplicateAbvt($val){

			$sql = "SELECT " . ABBREVIATIONSMST .".*," . CATEGORIESMST . ".name as categoryname FROM " . ABBREVIATIONSMST
				. " LEFT JOIN " . CATEGORIESMST . " ON " . CATEGORIESMST . ".`id` = " . ABBREVIATIONSMST . ".`category` "
				. " WHERE " . ABBREVIATIONSMST . ".abvt = '$val'";

			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}

			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		function chkDuplicateAbvtName($val){

			$sql = "SELECT " . ABBREVIATIONSMST .".*," . CATEGORIESMST . ".name as categoryname FROM " . ABBREVIATIONSMST
				. " LEFT JOIN " . CATEGORIESMST . " ON " . CATEGORIESMST . ".`id` = " . ABBREVIATIONSMST . ".`category` "
				. " WHERE " . ABBREVIATIONSMST . ".word = '$val'";

			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}

			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		function chkDuplicateAbvtName1($a,$n){
			$sql = "";
			$sql = "SELECT " . ABBREVIATIONSMST .".*," . CATEGORIESMST . ".name as categoryname FROM " . ABBREVIATIONSMST
				. " LEFT JOIN " . CATEGORIESMST . " ON " . CATEGORIESMST . ".`id` = " . ABBREVIATIONSMST . ".`category` "
				. " WHERE " . ABBREVIATIONSMST . ".abvt = '$a'";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABBREVIATIONSMST .".*," . CATEGORIESMST . ".name as categoryname FROM " . ABBREVIATIONSMST
				. " LEFT JOIN " . CATEGORIESMST . " ON " . CATEGORIESMST . ".`id` = " . ABBREVIATIONSMST . ".`category` "
				. " WHERE " . ABBREVIATIONSMST . ".word = '$n'";

			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}

			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function saveImportedabaPeople($data){
			$sql2 = null;
			$abaini = isset($data['abaini']) ? strtolower($data['abaini']) : null;
			$eetype = isset($data['eetype']) ? strtolower($data['eetype']) : null;
			$eecat = isset($data['eecategory']) ? strtolower($data['eecategory']) : null;
			$designation = isset($data['designation']) ? strtolower($data['designation']) : null;
			$company = isset($data['company']) ? strtolower($data['company']) : null;
			$station = isset($data['station']) ? strtolower($data['station']) : null;
			$department = isset($data['department']) ? strtolower($data['department']) : null;
			$fname = isset($data['fname']) ? ucfirst($data['fname']) : null;
			$lname = isset($data['lname']) ? strtoupper($data['lname']) : null;
			$mname = isset($data['mname']) ? strtoupper($data['mname']) : null;
			$eename = $fname . " " . $lname;
			$email = isset($data['email']) ? strtolower($data['email']) : null;
			$address = isset($data['address']) ? addslashes($data['address']) : null;
			$city = isset($data['city']) ? $data['city'] : null;
			$state = isset($data['state']) ? $data['state'] : null;
			$zipcode = isset($data['zipcode']) ? $data['zipcode'] : null;
			$country = isset($data['country']) ? $data['country'] : null;
			$presentaddr = isset($data['presentaddr']) ? addslashes($data['presentaddr']) : null;
			$presentcity = isset($data['presentcity']) ? $data['presentcity'] : null;
			$presentstate = isset($data['presentstate']) ? $data['presentstate'] : null;
			$presentzipcode = isset($data['presentzipcode']) ? $data['presentzipcode'] : null;
			$presentcountry = isset($data['presentcountry']) ? $data['presentcountry'] : null;
			$homephoneno = isset($data['homephoneno']) ? $data['homephoneno'] : null;
			$officephoneno = isset($data['officephoneno']) ? $data['officephoneno'] : null;
			$telext = isset($data['telext']) ? $data['telext'] : null;
			$mobileno = isset($data['mobileno']) ? $data['mobileno'] : null;
			$gender = isset($data['gender']) ? $data['gender'] : null;
			$birthdate = isset($data['birthdate']) ? formatDate("Y-m-d",$data['birthdate']) : null;
			$joineddate = isset($data['joineddate']) ? formatDate("Y-m-d",$data['joineddate']) : null;
			$passportno = isset($data['passportno']) ? $data['passportno'] : null;
			$passportexpiry = isset($data['passportexpiry']) ? formatDate("Y-m-d",$data['passportexpiry']) : null;
			$drivinglicenseno = isset($data['drivinglicenseno']) ? $data['drivinglicenseno'] : null;
			$drivinglicenseexpiry = isset($data['drivinglicenseexpiry']) ? formatDate("Y-m-d",$data['drivinglicenseexpiry']) : null;
			$nationality = isset($data['nationality']) ? $data['nationality'] : null;
			$bloodgroup = isset($data['bloodgroup']) ? $data['bloodgroup'] : null;
			$govtidsocsec = isset($data['govtidsocsec']) ? $data['govtidsocsec'] : null;
			$reportsto = isset($data['reportsto']) ? $data['reportsto'] : null;
			$reportstoindirect = isset($data['reportstoindirect']) ? $data['reportstoindirect'] : null;
			$emerconperson = !empty($data['emerconperson']) ? $data['emerconperson'] : null;
			$emerconaddress = !empty($data['emerconaddress']) ? $data['emerconaddress'] : null;
			$emerconrelationship = !empty($data['emerconrelationship']) ? $data['emerconrelationship'] : null;
			$emerconno = !empty($data['emerconno']) ? $data['emerconno'] : null;
			$cnname = isset($data['cnname']) ? strtolower($data['cnname']) : null;
			$eestatus = isset($data['eestatus']) ? strtolower($data['eestatus']) : null;
			$eestat = strtolower($eestatus) == "active" ? 2 : 3;
			$stat = strtolower($eestatus) == "active" ? 1 : 0;
			$skype = isset($data['skype']) ? strtolower($data['skype']) : null;
			$wechat = isset($data['wechat']) ? strtolower($data['wechat']) : null;
			
			$ctccat = $data['contactcategory'];
			$abaUser = $data['abaUser'];
			$today = $data['today'];
			
			$sql = "INSERT INTO " . ABAPEOPLESMST . " (abaini,webhr_eetype,webhr_eecategory,webhr_designation,webhr_company,webhr_station,webhr_department,fname,lname,emailaddress,address,webhr_city,webhr_state,webhr_zipcode,webhr_country,webhr_status,presentaddress,presentcity,presentstate,presentzipcode,presentcountry,homephoneno,officephoneno,telext,mobileno,webhr_gender,birthdate,joineddate,passportno,passportexpiry,drivinglicenseno,drivinglicenseexpiry,webhr_nationality,webhr_bloodgroup,govtidsocsec,reportsto,reportstoindirect,emercontactperson,emercontactrelation,emercontactno,cnname,contactcategory,skype,wechat,status,createdby,createddate)
					VALUES('$abaini','$eetype','$eecat','$designation','$company','$station','$department','$fname','$lname','$email','$address','$city','$state','$zipcode','$country','$eestatus','$presentaddr','$presentcity','$presentstate','$presentzipcode','$presentcountry','$homephoneno','$officephoneno','$telext','$mobileno','$gender','$birthdate','$joineddate','$passportno','$passportexpiry','$drivinglicenseno','$drivinglicenseexpiry','$nationality','$bloodgroup','$govtidsocsec','$reportsto','$reportstoindirect','$emerconperson','$emerconrelationship','$emerconno','$cnname','$ctccat','$skype','$wechat','$stat','$abaUser','$today')";
			// echo $sql . '<br />';
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo 'error saving abacarian $abaini. ' . $this->cn->error;
				exit();
			}
			$id = $this->cn->insert_id;
			$this->setInsertId($id);

			if(!empty($abaini) || $abaini != ""){
				$sql2 = "INSERT INTO " . ABBREVIATIONSMST . " (type,abvt,word,cnword,description,refid,category,createdby,createddate) 
						VALUES('1','$abaini','$eename','$cnname','$designation','$id','$eestat','$abaUser','$today')";
				// echo $sql2 . '<br />';
				$qry2 = $this->cn->query($sql2);
				if(!$qry2){
					echo 'error saving abvt. ' . $this->cn->error;
					// exit();
				}
				
				$pass = generatePassword("1234");
				$sql3 = "INSERT INTO " . USERSMST . " (username,password,abaini,fname,lname,mname,accesslevel,createdby,createddate) 
						VALUES('$abaini','$pass','$abaini','$fname','$lname','$mname','3','$abaUser','$today')";
				// echo $sql2 . '<br />';
				$qry3 = $this->cn->query($sql3);
				if(!$qry3){
					echo 'error saving user. ' . $this->cn->error;
					// exit();
				}
			}

			$this->cn->close();

			// AUDIT LOGS
			$adtlogs = array();
			$adtlogs['method'] = "CREATE";
			$adtlogs['pname'] = "abapeople";
			$adtlogs['dtl1'] = $sql;
			$adtlogs['dtl2'] = $sql2;
			$adtlogs['tbl'] = ABAPEOPLESMST;
			$adtlogs['rid'] = $id;
			$adtlogs['abaUser'] = $abaUser;
			$adtlogs['today'] = $today;

			$adt = new AuditLogs;
			$adt->saveAuditLog($adtlogs);
		}

		public function abacarianExist($data){
			$abaini = $data['abaini'];
			$fname = $data['fname'];
			$lname = $data['lname'];

			$sql = "SELECT * FROM " . ABAPEOPLESMST
					. " WHERE " . ABAPEOPLESMST . ".abaini = '$abaini'
						AND " . ABAPEOPLESMST . ".fname = '$fname'
						AND " . ABAPEOPLESMST . ".lname = '$lname'";
			// echo $sql . '<br />';
			$rows = array();
			$qry = $this->cn->query($sql);
			$row = $qry->fetch_array(MYSQLI_ASSOC);
			$result['cnt'] = $qry->num_rows;
			$result['id'] = $row['id'];
			if(!$qry){
				echo 'error function abacarianExist(). ' . $this->cn->error;
				exit();
			}
			// echo $result['cnt']  . ' ' . $abaini . '<br />';
			$this->cn->close();
			return $result;
		}

		public function updateImportedabaPeople($data){
			$sql2 = null;
			$id  = isset($data['id']) ? $data['id'] : null;
			$abaini = isset($data['abaini']) ? strtolower($data['abaini']) : null;
			$eetype = isset($data['eetype']) ? strtolower($data['eetype']) : null;
			$eecat = isset($data['eecategory']) ? strtolower($data['eecategory']) : null;
			$designation = isset($data['designation']) ? strtolower($data['designation']) : null;
			$company = isset($data['company']) ? strtolower($data['company']) : null;
			$station = isset($data['station']) ? strtolower($data['station']) : null;
			$department = isset($data['department']) ? strtolower($data['department']) : null;
			$fname = isset($data['fname']) ? ucfirst($data['fname']) : null;
			$lname = isset($data['lname']) ? strtoupper($data['lname']) : null;
			$eename = $fname . " " . $lname;
			$email = isset($data['email']) ? strtolower($data['email']) : null;
			$address = isset($data['address']) ? addslashes($data['address']) : null;
			$city = isset($data['city']) ? $data['city'] : null;
			$state = isset($data['state']) ? $data['state'] : null;
			$zipcode = isset($data['zipcode']) ? $data['zipcode'] : null;
			$country = isset($data['country']) ? $data['country'] : null;
			$presentaddr = isset($data['presentaddr']) ? addslashes($data['presentaddr']) : null;
			$presentcity = isset($data['presentcity']) ? $data['presentcity'] : null;
			$presentstate = isset($data['presentstate']) ? $data['presentstate'] : null;
			$presentzipcode = isset($data['presentzipcode']) ? $data['presentzipcode'] : null;
			$presentcountry = isset($data['presentcountry']) ? $data['presentcountry'] : null;
			$homephoneno = isset($data['homephoneno']) ? $data['homephoneno'] : null;
			$officephoneno = isset($data['officephoneno']) ? $data['officephoneno'] : null;
			$telext = isset($data['telext']) ? $data['telext'] : null;
			$mobileno = isset($data['mobileno']) ? $data['mobileno'] : null;
			$gender = isset($data['gender']) ? $data['gender'] : null;
			$birthdate = isset($data['birthdate']) ? formatDate("Y-m-d",$data['birthdate']) : null;
			$joineddate = isset($data['joineddate']) ? formatDate("Y-m-d",$data['joineddate']) : null;
			$passportno = isset($data['passportno']) ? $data['passportno'] : null;
			$passportexpiry = isset($data['passportexpiry']) ? formatDate("Y-m-d",$data['passportexpiry']) : null;
			$drivinglicenseno = isset($data['drivinglicenseno']) ? $data['drivinglicenseno'] : null;
			$drivinglicenseexpiry = isset($data['drivinglicenseexpiry']) ? formatDate("Y-m-d",$data['drivinglicenseexpiry']) : null;
			$nationality = isset($data['nationality']) ? $data['nationality'] : null;
			$bloodgroup = isset($data['bloodgroup']) ? $data['bloodgroup'] : null;
			$govtidsocsec = isset($data['govtidsocsec']) ? $data['govtidsocsec'] : null;
			$reportsto = isset($data['reportsto']) ? $data['reportsto'] : null;
			$reportstoindirect = isset($data['reportstoindirect']) ? $data['reportstoindirect'] : null;
			$emerconperson = !empty($data['emerconperson']) ? addslashes($data['emerconperson']) : null;
			$emerconaddress = !empty($data['emerconaddress']) ? addslashes($data['emerconaddress']) : null;
			$emerconrelationship = !empty($data['emerconrelationship']) ? $data['emerconrelationship'] : null;
			$emerconno = !empty($data['emerconno']) ? $data['emerconno'] : null;
			$cnname = isset($data['cnname']) ? strtolower($data['cnname']) : null;
			$eestatus = isset($data['eestatus']) ? strtolower($data['eestatus']) : null;
			$eestat = strtolower($eestatus) == "active" ? 2 : 3;
			$stat = strtolower($eestatus) == "active" ? 1 : 0;
			$skype = isset($data['skype']) ? strtolower($data['skype']) : null;
			$wechat = isset($data['wechat']) ? strtolower($data['wechat']) : null;
			
			$abaUser = $data['abaUser'];
			$today = $data['today'];

			$sql = "UPDATE " . ABAPEOPLESMST . " SET webhr_eetype ='$eetype', webhr_eecategory = '$eecat', webhr_designation = '$designation'
					, webhr_company = '$company', webhr_station = '$station', webhr_department = '$department', emailaddress = '$email', address = '$address'
					, webhr_city = '$city', webhr_state = '$state', webhr_zipcode = '$zipcode', webhr_country = '$country', webhr_status = '$eestatus'
					, presentaddress = '$presentaddr', presentcity = '$presentcity', presentstate = '$presentstate', presentzipcode = '$presentzipcode'
					, presentcountry = '$presentcountry', homephoneno = '$homephoneno', officephoneno = '$officephoneno', telext = '$telext'
					, mobileno = '$mobileno', webhr_gender = '$gender', birthdate = '$birthdate', joineddate = '$joineddate', passportno = '$passportno'
					, passportexpiry = '$passportexpiry', drivinglicenseno = '$drivinglicenseno', drivinglicenseexpiry = '$drivinglicenseexpiry'
					, webhr_nationality = '$nationality' , webhr_bloodgroup = '$bloodgroup', govtidsocsec = '$govtidsocsec', reportsto = '$reportsto'
					, reportstoindirect = '$reportstoindirect', emercontactperson = '$emerconperson', emercontactrelation = '$emerconrelationship'
					, emercontactno = '$emerconno', cnname = '$cnname', skype = '$skype', wechat = '$wechat', modifiedby = '$abaUser', modifieddate = '$today' 
					, status = '$stat' 
				WHERE " . ABAPEOPLESMST . ".id = '$id'";
			// echo $sql . '<br />';
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo 'error updating abacarian $abaini. ' . $this->cn->error;
				// exit();
			}

			if(!empty($abaini) || $abaini != ""){
				$sql2 = "UPDATE " . ABBREVIATIONSMST
					. " SET abvt = '$abaini', word = '$eename', cnword = '$cnname', description = '$designation', category = '$eestat', modifiedby = '$abaUser'
						, modifieddate = '$today'
					WHERE " . ABBREVIATIONSMST . ".refid = '$id'
						AND " . ABBREVIATIONSMST . ".type = 1";

				$qry2 = $this->cn->query($sql2);
				if(!$qry2){
					echo 'error updating abvt $abaini. ' . $this->cn->error;
					exit();
				}
			}

			$this->cn->close();

			// AUDIT LOGS
			$adtlogs = array();
			$adtlogs['method'] = "UPDATE";
			$adtlogs['pname'] = "abapeople";
			$adtlogs['dtl1'] = $sql;
			$adtlogs['dtl2'] = $sql2;
			$adtlogs['tbl'] = ABAPEOPLESMST;
			$adtlogs['rid'] = $id;
			$adtlogs['abaUser'] = $abaUser;
			$adtlogs['today'] = $today;

			$adt = new AuditLogs;
			$adt->saveAuditLog($adtlogs);
		}
		public function getabaPeopleSorted($ofc){
			$where = "WHERE 1 ";
			if(strtoupper($ofc) != "ALL"){
				$where .= " AND " . ABAPEOPLESMST . ".webhr_station = '$ofc'";
			}
			
			$sql = "SELECT z.* FROM (";
			$sql .= "(SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. $where . " AND " . ABAPEOPLESMST . ".status = 1"
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`)";

			$sql .= " UNION ALL (SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. $where . " AND " . ABAPEOPLESMST . ".status = 0"
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`)) AS z ORDER BY z.fname";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function getabaPeopleByIni($ini){
			$where = "";
			$where .= " WHERE " . ABAPEOPLESMST . ".abaini = '$ini'";
			$sql = "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,a.dddescription as title 
					FROM " . ABAPEOPLESMST 
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` " 
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`salesofficeid` = " . ABAPEOPLESMST . ".`office` " 
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' " 
					. $where . " AND " . ABAPEOPLESMST . ".status = 1" 
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`";

			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function getReqby($id=""){	
			$where = "";
			if(!empty($id)){
				$where = " AND " . ABAPEOPLESMST . ".id = '$id'";	
			}

			$sql = "SELECT *, (CASE WHEN " . ABAPEOPLESMST . " . `status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname FROM " . ABAPEOPLESMST . " WHERE " . ABAPEOPLESMST . ".contactcategory = 1 " . $where . " ORDER BY " . ABAPEOPLESMST . " . `abaini`";

			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function saveImportedContact($data){
			$sql2 = null;
			$abaini = isset($data['abaini']) ? strtolower($data['abaini']) : null;
			// $eetype = isset($data['eetype']) ? strtolower($data['eetype']) : null;
			// $eecat = isset($data['eecategory']) ? strtolower($data['eecategory']) : null;
			$designation = isset($data['designation']) ? strtolower($data['designation']) : null;
			// $company = isset($data['company']) ? strtolower($data['company']) : null;
			$station = isset($data['station']) ? strtolower($data['station']) : null;
			// $department = isset($data['department']) ? strtolower($data['department']) : null;
			$fname = isset($data['fname']) ? ucfirst($data['fname']) : null;
			$lname = isset($data['lname']) ? strtoupper($data['lname']) : null;
			$mname = isset($data['mname']) ? strtoupper($data['mname']) : null;
			// $eename = $fname . " " . $lname;
			$email = isset($data['email']) ? strtolower($data['email']) : null;
			// $address = isset($data['address']) ? addslashes($data['address']) : null;
			// $city = isset($data['city']) ? $data['city'] : null;
			// $state = isset($data['state']) ? $data['state'] : null;
			// $zipcode = isset($data['zipcode']) ? $data['zipcode'] : null;
			// $country = isset($data['country']) ? $data['country'] : null;
			// $presentaddr = isset($data['presentaddr']) ? addslashes($data['presentaddr']) : null;
			// $presentcity = isset($data['presentcity']) ? $data['presentcity'] : null;
			// $presentstate = isset($data['presentstate']) ? $data['presentstate'] : null;
			// $presentzipcode = isset($data['presentzipcode']) ? $data['presentzipcode'] : null;
			// $presentcountry = isset($data['presentcountry']) ? $data['presentcountry'] : null;
			// $homephoneno = isset($data['homephoneno']) ? $data['homephoneno'] : null;
			$officephoneno = isset($data['officephoneno']) ? $data['officephoneno'] : null;
			// $mobileno = isset($data['mobileno']) ? $data['mobileno'] : null;
			// $gender = isset($data['gender']) ? $data['gender'] : null;
			// $birthdate = isset($data['birthdate']) ? formatDate("Y-m-d",$data['birthdate']) : null;
			// $joineddate = isset($data['joineddate']) ? formatDate("Y-m-d",$data['joineddate']) : null;
			// $passportno = isset($data['passportno']) ? $data['passportno'] : null;
			// $passportexpiry = isset($data['passportexpiry']) ? formatDate("Y-m-d",$data['passportexpiry']) : null;
			// $drivinglicenseno = isset($data['drivinglicenseno']) ? $data['drivinglicenseno'] : null;
			// $drivinglicenseexpiry = isset($data['drivinglicenseexpiry']) ? formatDate("Y-m-d",$data['drivinglicenseexpiry']) : null;
			// $nationality = isset($data['nationality']) ? $data['nationality'] : null;
			// $bloodgroup = isset($data['bloodgroup']) ? $data['bloodgroup'] : null;
			// $govtidsocsec = isset($data['govtidsocsec']) ? $data['govtidsocsec'] : null;
			// $reportsto = isset($data['reportsto']) ? $data['reportsto'] : null;
			// $reportstoindirect = isset($data['reportstoindirect']) ? $data['reportstoindirect'] : null;
			// $emerconperson = !empty($data['emerconperson']) ? $data['emerconperson'] : null;
			// $emerconaddress = !empty($data['emerconaddress']) ? $data['emerconaddress'] : null;
			// $emerconrelationship = !empty($data['emerconrelationship']) ? $data['emerconrelationship'] : null;
			// $emerconno = !empty($data['emerconno']) ? $data['emerconno'] : null;
			$cnname = isset($data['cnname']) ? strtolower($data['cnname']) : null;
			// $eestatus = isset($data['eestatus']) ? strtolower($data['eestatus']) : null;
			// $eestat = $eestatus == "active" ? 2 : 3;
			// $stat = $eestatus != "active" ? 0 : 1;
			// $skype = isset($data['skype']) ? strtolower($data['skype']) : null;
			// $wechat = isset($data['wechat']) ? strtolower($data['wechat']) : null;
			
			$ctccat = $data['contactcategory'];
			$abaUser = $data['abaUser'];
			$today = $data['today'];
			
			$sql = "INSERT INTO " . ABAPEOPLESMST . " (abaini,webhr_eetype,webhr_eecategory,webhr_designation,webhr_company,webhr_station,webhr_department,fname,lname,emailaddress,address,webhr_city,webhr_state,webhr_zipcode,webhr_country,webhr_status,presentaddress,presentcity,presentstate,presentzipcode,presentcountry,homephoneno,officephoneno,mobileno,webhr_gender,birthdate,joineddate,passportno,passportexpiry,drivinglicenseno,drivinglicenseexpiry,webhr_nationality,webhr_bloodgroup,govtidsocsec,reportsto,reportstoindirect,emercontactperson,emercontactrelation,emercontactno,cnname,contactcategory,skype,wechat,status,createdby,createddate)
					VALUES('$abaini','$eetype','$eecat','$designation','$company','$station','$department','$fname','$lname','$email','$address','$city','$state','$zipcode','$country','$eestatus','$presentaddr','$presentcity','$presentstate','$presentzipcode','$presentcountry','$homephoneno','$officephoneno','$mobileno','$gender','$birthdate','$joineddate','$passportno','$passportexpiry','$drivinglicenseno','$drivinglicenseexpiry','$nationality','$bloodgroup','$govtidsocsec','$reportsto','$reportstoindirect','$emerconperson','$emerconrelationship','$emerconno','$cnname','$ctccat','$skype','$wechat','$stat','$abaUser','$today')";
			// echo $sql . '<br />';
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo 'error saving abacarian $abaini. ' . $this->cn->error;
				exit();
			}
			$id = $this->cn->insert_id;
			$this->setInsertId($id);

			if(!empty($abaini) || $abaini != ""){
				$sql2 = "INSERT INTO " . ABBREVIATIONSMST . " (type,abvt,word,cnword,description,refid,category,createdby,createddate) 
						VALUES('1','$abaini','$eename','$cnname','$designation','$id','$eestat','$abaUser','$today')";
				// echo $sql2 . '<br />';
				$qry2 = $this->cn->query($sql2);
				if(!$qry2){
					echo 'error saving abvt. ' . $this->cn->error;
					// exit();
				}
				
				$pass = generatePassword("1234");
				$sql3 = "INSERT INTO " . USERSMST . " (username,password,abaini,fname,lname,mname,accesslevel,createdby,createddate) 
						VALUES('$abaini','$pass','$abaini','$fname','$lname','$mname','3','$abaUser','$today')";
				// echo $sql2 . '<br />';
				$qry3 = $this->cn->query($sql3);
				if(!$qry3){
					echo 'error saving user. ' . $this->cn->error;
					// exit();
				}
			}

			$this->cn->close();

			// AUDIT LOGS
			$adtlogs = array();
			$adtlogs['method'] = "CREATE";
			$adtlogs['pname'] = "abapeople";
			$adtlogs['dtl1'] = $sql;
			$adtlogs['dtl2'] = $sql2;
			$adtlogs['tbl'] = ABAPEOPLESMST;
			$adtlogs['rid'] = $id;
			$adtlogs['abaUser'] = $abaUser;
			$adtlogs['today'] = $today;

			$adt = new AuditLogs;
			$adt->saveAuditLog($adtlogs);
		}

		// SEARCH USER FOR CLIENT ACCESS
		public function searchUserClientAccess($data){
			$res = array();
			$sql = "";

			// SQL abaini
			$sql = "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE " . ABAPEOPLESMST . ".abaini = '$data'
						AND " . ABAPEOPLESMST . ".status = 1";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE " . ABAPEOPLESMST . ".abaini LIKE '$data%' 
						AND " . ABAPEOPLESMST . ".status = 1";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE " . ABAPEOPLESMST . ".abaini LIKE '%$data%' 
						AND " . ABAPEOPLESMST . ".status = 1";
			$sql .= " UNION ";

			// SQL NAME
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (" . ABAPEOPLESMST . ".fname = '$data' OR " . ABAPEOPLESMST . ".mname = '$data' OR " . ABAPEOPLESMST . ".lname = '$data') 
						AND " . ABAPEOPLESMST . ".status = 1";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (" . ABAPEOPLESMST . ".fname LIKE '$data%' OR " . ABAPEOPLESMST . ".mname LIKE '$data%' OR " . ABAPEOPLESMST . ".lname LIKE '$data%') 
						AND " . ABAPEOPLESMST . ".status = 1";
			$sql .= " UNION ";
			$sql .= "SELECT " . ABAPEOPLESMST . ".*,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
						,CONCAT(" . ABAPEOPLESMST . ".fname,' '," . ABAPEOPLESMST . ".mname,' '," . ABAPEOPLESMST . ".lname) as eename
						,a.dddescription as title
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " LEFT JOIN " . DROPDOWNSMST . " a ON a.ddid = " . ABAPEOPLESMST . ".`salutation` AND a.dddisplay = 'eesalutation' "
					. " WHERE (" . ABAPEOPLESMST . ".fname LIKE '%$data%' OR " . ABAPEOPLESMST . ".mname LIKE '%$data%' OR " . ABAPEOPLESMST . ".lname LIKE '%$data%') 
						AND " . ABAPEOPLESMST . ".status = 1";

			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			
			$cnt = 0;
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$eeid = $row['id'];
				$abaini = $row['abaini'];
				$rows[$cnt] = $row;

				$sql1 = "SELECT a.*
							,b.`name` as salesofficename 
						FROM " . CLIENTUSERACCESS . " a
						LEFT JOIN " . SALESOFFICESMST . " b
							ON b.salesofficeid = a.salesofficeno
						WHERE a.eeid = '$eeid' 
							AND a.abaini = '$abaini' ";
				$qry1 = $this->cn->query($sql1);
				$cnt1 = $qry1->num_rows;
				$cnt2 = 0;
				$offices = "";

				while($row1 = $qry1->fetch_array(MYSQLI_ASSOC)){
					// $office[] = $row1;
					$cnt2++;
					$offices .= $row1['salesofficename'];
					if($cnt2 <> $cnt1){
						$offices .= ",";
					}
				}

				$rows[$cnt]['offices'] = $offices;
				$cnt++;
			}

			$res['rows'] = $rows;
			$res['cnt'] = $cnt;
			$this->cn->close();
			return $res;
		}

		public function getClientUserOfficeAccess($abaini){
			$res = array();
			$sql = "SELECT a.*
						,b.name as salesofficename 
					FROM " . CLIENTUSERACCESS . " a
					LEFT JOIN " . SALESOFFICESMST . " b
						ON b.salesofficeid = a.salesofficeno
					WHERE a.abaini = '$abaini' ";
			$qry = $this->cn->query($sql);
			$res['cnt'] = $qry->num_rows;

			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			$this->cn->close();
			return $res;
		}

		public function updateClientUserAccess($data){
			$ofcs = $data['ofcs'];
			$ofc = explode(",",$ofcs);
			$eeid = $data['eeid'];
			$abaini = $data['abaini'];
			$strOfc = "";

			if(!empty($ofcs)){
				for($i=0;$i<count($ofc);$i++){
					$ofcno = $ofc[$i];
					if(!empty($ofcno)){
						$sql = "SELECT a.* FROM " . CLIENTUSERACCESS . " a 
								WHERE a.eeid = '$eeid'
									AND a.abaini = '$abaini'
									AND a.salesofficeno = '$ofcno'";
						$qry = $this->cn->query($sql);
						$num = $qry->num_rows;
						$strOfc .= "'" . $ofcno . "',";

						if($num == 0){
							$ins = "INSERT INTO " . CLIENTUSERACCESS . "(eeid,abaini,salesofficeno) VALUES('$eeid','$abaini','$ofcno')";
							$qry = $this->cn->query($ins);
						}
					}
				}

				$strOfc = rtrim($strOfc,",");

				$del = "DELETE FROM " . CLIENTUSERACCESS . "
						WHERE " . CLIENTUSERACCESS . ".eeid = '$eeid'
							AND " . CLIENTUSERACCESS . ".abaini = '$abaini'
							AND " . CLIENTUSERACCESS . ".salesofficeno NOT IN($strOfc)";
				$qry = $this->cn->query($del);
			}else{
				$del = "DELETE FROM " . CLIENTUSERACCESS . "
						WHERE " . CLIENTUSERACCESS . ".eeid = '$eeid'
							AND " . CLIENTUSERACCESS . ".abaini = '$abaini'";
				$qry = $this->cn->query($del);
			}

			$this->cn->close();
			return $ofcs;
		}

		public function getNotBingoPlayers($id=""){
			$where = "";
			$sql = "SELECT " . ABAPEOPLESMST . ".*
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						," . DESIGNATIONSMST . ".description as designationname
						," . SALESOFFICESMST . ".description as salesofficename
					FROM " . ABAPEOPLESMST
					. " LEFT JOIN " . DESIGNATIONSMST . " ON " . DESIGNATIONSMST . ".`id` = " . ABAPEOPLESMST . ".`designation` "
					. " LEFT JOIN " . SALESOFFICESMST . " ON " . SALESOFFICESMST . ".`id` = " . ABAPEOPLESMST . ".`salesoffice` "
					. " WHERE " . ABAPEOPLESMST . ".status = 1 AND " . ABAPEOPLESMST . ".contactcategory = 1 AND " . ABAPEOPLESMST . ".webhr_station IN('sscceb','opsceb')
					AND " . ABAPEOPLESMST . ".abaini NOT IN (SELECT " . BINGOPLAYERS . ".abaini FROM " . BINGOPLAYERS . ")"
					. " ORDER BY " . ABAPEOPLESMST . ".`abaini`";;
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function getBDees($acctid){
			$res = array();
			$res['err'] = 0;
			$sql = "SELECT ". ABAPEOPLESMST	 .". * ,
	  				(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
	      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
	              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
	              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
	              		ELSE '' 
	        		END) as eesdesignation 
					FROM 
	  				". ABAPEOPLESMST." 
					WHERE ". ABAPEOPLESMST .".status = 1 AND ". ABAPEOPLESMST .".`userid` NOT IN (SELECT ". CDMACCOUNTSACCESS .".`userid` FROM ". CDMACCOUNTSACCESS ." WHERE ". CDMACCOUNTSACCESS .".`acctid` = '$acctid') 
					HAVING eesdesignation <> '' ORDER BY ". ABAPEOPLESMST.".abaini";
			
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'error func getBDees(). '. $this->cn->error;
				goto exitme;
			// 	exit();
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:

			return $res;
		}

		public function getCSees($abaini=""){
			$where = "";
			if(!empty($abaini)){
				$where = "AND " . ABAPEOPLESMST . ".abaini = '$abaini' ";
			}
			$sql = "SELECT ". ABAPEOPLESMST .".*
						,CONCAT(
							(CASE WHEN ". ABAPEOPLESMST .".fname != '' THEN ". ABAPEOPLESMST .".fname ELSE '' END),' '
							,(CASE WHEN ". ABAPEOPLESMST .".mname != '' THEN ". ABAPEOPLESMST .".mname ELSE '' END),' '
							,(CASE WHEN ". ABAPEOPLESMST .".lname != '' THEN ". ABAPEOPLESMST .".lname ELSE '' END)) AS eename
						,(CASE WHEN ". ABAPEOPLESMST .".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname
						,". SALESOFFICESMST .".description AS salesofficename
						,". CMFCSREPORTSMST .".iframe 
					FROM ". ABAPEOPLESMST ." LEFT JOIN ". SALESOFFICESMST ." ON ". SALESOFFICESMST .".`id` = ". ABAPEOPLESMST .".`salesoffice` 
					LEFT JOIN ". CMFCSREPORTSMST ." ON ". CMFCSREPORTSMST .".`abaini` = ". ABAPEOPLESMST .".`abaini` 
					WHERE ". ABAPEOPLESMST .".status = 1 
							AND ". ABAPEOPLESMST .".contactcategory = 1 
							AND ". ABAPEOPLESMST .".webhr_designation 
								IN('client service director','client servicing manager'
									,'account executive - corporate','account executive - individual'
									,'account supervisor - corporate','account supervisor - individual'
									,'client servicing manager - corporate','client servicing manager - individual'
									,'client servicing assistant manager - corporate','client servicing assistant manager - individual') 
					ORDER BY ". ABAPEOPLESMST .".`webhr_station`,". ABAPEOPLESMST .".`abaini`";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function getabacSnrMgt(){
			$sql = "SELECT ". ABAPEOPLESMST .".* 
					FROM ". ABAPEOPLESMST ." 
					WHERE ". ABAPEOPLESMST .".`webhr_designation` IN('general manager beijing'
										,'general manager hong kong'
										,'general manager singapore'
										,'general manager for china'
										,'general manager sscceb'
										,'chief operating officer'
										,'chairman'
										,'ceo') 
						AND ". ABAPEOPLESMST .".`status` = 1 
						AND ". ABAPEOPLESMST .".`emailaddress` != '' 		
						AND ". ABAPEOPLESMST .".`abaini` NOT IN(SELECT ". MKGREQUESTSNRMGT .".`abaini` FROM ". MKGREQUESTSNRMGT .")";
			
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
				exit();
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$this->cn->close();
			return $rows;
		}

		public function getEeByUserId($userid){
			$res = array();
			$res['err'] = 0;
			$sql = "SELECT ". ABAPEOPLESMST .".* 
					FROM ". ABAPEOPLESMST ." 
					WHERE ". ABAPEOPLESMST .".`status` = 1 
						AND ". ABAPEOPLESMST .".`workemail` != '' 		
						AND ". ABAPEOPLESMST .".`userid` = '$userid'";
			
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "error func getEeByUserId(). ". $this->cn->error;
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

		function getBDActivityLog($data){
			$res = array();
			$userid = $data['userid'];
			$ofc = $data['ofc'];

			if($ofc == 'runbei' || $ofc == 'ababei'){
				$and = " AND ". ABAPEOPLESMST .".`webhr_station` in ('runbei', 'ababei') ";
			}elseif($ofc == 'runsha' || $ofc == 'abasha') {
				$and = " AND ". ABAPEOPLESMST .".`webhr_station` in ('runsha', 'abasha') ";
			}else{
				$and = " AND ". ABAPEOPLESMST .".`webhr_station` = '$ofc' ";
			}

			if(!empty($ofc)){
				$where = " WHERE ". ABAPEOPLESMST .".`status` = 1 
					    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
					    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
										'business development executive',
										'business development executive eb',
										'business development manager',
										'business development manager',
										'general manager beijing',
										'general manager for china',
										'general manager hong kong',
										'general manager singapore') 
						$and ";

				if($userid == 'A700101-00006'){
					$where = " WHERE ". ABAPEOPLESMST .".`status` = 1 
					    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
					    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
										'business development executive',
										'business development executive eb',
										'business development manager',
										'business development manager',
										'general manager beijing',
										'general manager for china',
										'general manager hong kong',
										'general manager singapore') 
						AND ". ABAPEOPLESMST .".`webhr_station` IN ('abasha', 'runsha', 'ababei', 'runbei') ";
				}elseif ($userid == 'A190101-99999' || $userid == 'A970623-00002' || $userid == 'A161215-00089' || $userid == 'A170810-00000') {
					$where = " WHERE ". ABAPEOPLESMST .".`status` = 1 
					    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
					    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
										'business development executive',
										'business development executive eb',
										'business development manager',
										'business development manager',
										'general manager beijing',
										'general manager for china',
										'general manager hong kong',
										'general manager singapore') ";
				}
			}
			$sql = "SELECT ". ABAPEOPLESMST .".userid,
						". ABAPEOPLESMST .".`fname`,
						". ABAPEOPLESMST .".`mname`,
						". ABAPEOPLESMST .".`lname`,
						". ABAPEOPLESMST .".`cnname`,
						". ABAPEOPLESMST .".`abaini`,
						". ABAPEOPLESMST .".`emailaddress`,
						". ABAPEOPLESMST .".`webhr_designation`,
						". ABAPEOPLESMST .".`webhr_station`,
						(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		ELSE '' 
		        		END) as eedesignationini,
						(SELECT CONCAT(DATE_FORMAT(". CDMACTIVITIES .".`createddate`,'%a %d %b %y'),' - ',". CDMACTIVITIES .".`actdetails`) FROM ". CDMACTIVITIES ." WHERE ". CDMACTIVITIES .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMACTIVITIES .".`acttype` != 'login' ORDER BY ". CDMACTIVITIES .".`createddate` DESC LIMIT 1) AS lastactivity,
						(SELECT COUNT(id) FROM ". CDMTASKS ." WHERE ". CDMTASKS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMTASKS .".`status` = 0) AS cnttasks,
						(SELECT COUNT(id) FROM ". CDMOPPS ." WHERE ". CDMOPPS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMOPPS .".`oppsstatus` NOT IN('SP')) AS cntopps
					FROM ". ABAPEOPLESMST ."
					$where 
					ORDER BY ". ABAPEOPLESMST .".webhr_station, ". ABAPEOPLESMST .".webhr_designation, ". ABAPEOPLESMST .".lname";
			$qry = $this->cn->query($sql);
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			return $res;
		}

		function getBDLogs($data){
			$res = array();
			$bdid = $data['bdid'];
			$from = $data['from'] . " 00:00:00";
			$to = $data['to'] . " 23:59:59";
			$sql = "SELECT ". CDMACTIVITIES .".*,
						DATE_FORMAT(" . CDMACTIVITIES . ".createddate,'%a %d %b %y') AS createddt 
					FROM ". CDMACTIVITIES ." 
					WHERE ". CDMACTIVITIES .".`createdby` = '$bdid' 
						AND ". CDMACTIVITIES .".`acttype` != 'login' 
						AND (". CDMACTIVITIES .".`createddate` >= '$from' AND ". CDMACTIVITIES .".`createddate` <= '$to') 
					ORDER BY ". CDMACTIVITIES .".`createddate` DESC";
			$qry = $this->cn->query($sql);
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			return $res;
		}

		function sortBDActivityLog($data){
			$res = array();
			$sortby = $data['sortby'];
			$sortin = $data['sortin'];

			switch($sortby){
				case 'bdname':
					$fieldname = "". ABAPEOPLESMST .".`fname` $sortin";
					break;
				case 'designation':
					$fieldname = "eedesignationini $sortin";
					break;
				case 'ofc':
					$fieldname = "". ABAPEOPLESMST .".`webhr_station` $sortin";
					break;
				default:
					$fieldname = "". ABAPEOPLESMST .".webhr_station, ". ABAPEOPLESMST .".webhr_designation, ". ABAPEOPLESMST .".lname";
					break;
			}

			$sql = "SELECT ". ABAPEOPLESMST .".userid,
						". ABAPEOPLESMST .".`fname`,
						". ABAPEOPLESMST .".`mname`,
						". ABAPEOPLESMST .".`lname`,
						". ABAPEOPLESMST .".`cnname`,
						". ABAPEOPLESMST .".`abaini`,
						". ABAPEOPLESMST .".`emailaddress`,
						". ABAPEOPLESMST .".`webhr_designation`,
						". ABAPEOPLESMST .".`webhr_station`,
						(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		ELSE '' 
		        		END) as eedesignationini,
						(SELECT CONCAT(DATE_FORMAT(". CDMACTIVITIES .".`createddate`,'%a %d %b %y'),' - ',". CDMACTIVITIES .".`actdetails`) FROM ". CDMACTIVITIES ." WHERE ". CDMACTIVITIES .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMACTIVITIES .".`acttype` != 'login' ORDER BY ". CDMACTIVITIES .".`createddate` DESC LIMIT 1) AS lastactivity,
						(SELECT COUNT(id) FROM ". CDMTASKS ." WHERE ". CDMTASKS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMTASKS .".`status` = 0) AS cnttasks,
						(SELECT COUNT(id) FROM ". CDMOPPS ." WHERE ". CDMOPPS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMOPPS .".`oppsstatus` NOT IN('SP')) AS cntopps
					FROM ". ABAPEOPLESMST ."
					WHERE ". ABAPEOPLESMST .".`status` = 1 
					    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
					    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
										'business development executive',
										'business development executive eb',
										'business development manager',
										'business development manager',
										'general manager beijing',
										'general manager for china',
										'general manager hong kong',
										'general manager singapore') 
					ORDER BY $fieldname ";
			$qry = $this->cn->query($sql);
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			return $res;
		}

		function filterHeaderBDActivityLog($data){
			$res = array();
			$headerval = $data['headerval'];

			switch($headerval){
				case 'ttlbd':
					$where = "WHERE ". ABAPEOPLESMST .".`status` = 1 
						    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
						    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
											'business development executive',
											'business development executive eb',
											'business development manager',
											'business development manager',
											'general manager beijing',
											'general manager for china',
											'general manager hong kong',
											'general manager singapore') 
							ORDER BY ". ABAPEOPLESMST .".webhr_station, ". ABAPEOPLESMST .".webhr_designation, ". ABAPEOPLESMST .".lname";
					break;
				default:
					$where = "WHERE ". ABAPEOPLESMST .".`status` = 1 
						    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
						    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
											'business development executive',
											'business development executive eb',
											'business development manager',
											'business development manager',
											'general manager beijing',
											'general manager for china',
											'general manager hong kong',
											'general manager singapore') 
							ORDER BY ". ABAPEOPLESMST .".webhr_station, ". ABAPEOPLESMST .".webhr_designation, ". ABAPEOPLESMST .".lname";
					break;
			}

			$sql = "SELECT ". ABAPEOPLESMST .".userid,
						". ABAPEOPLESMST .".`fname`,
						". ABAPEOPLESMST .".`mname`,
						". ABAPEOPLESMST .".`lname`,
						". ABAPEOPLESMST .".`cnname`,
						". ABAPEOPLESMST .".`abaini`,
						". ABAPEOPLESMST .".`emailaddress`,
						". ABAPEOPLESMST .".`webhr_designation`,
						". ABAPEOPLESMST .".`webhr_station`,
						(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
		      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
		              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
		              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
		              		ELSE '' 
		        		END) as eedesignationini,
						(SELECT CONCAT(DATE_FORMAT(". CDMACTIVITIES .".`createddate`,'%a %d %b %y'),' - ',". CDMACTIVITIES .".`actdetails`) FROM ". CDMACTIVITIES ." WHERE ". CDMACTIVITIES .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMACTIVITIES .".`acttype` != 'login' ORDER BY ". CDMACTIVITIES .".`createddate` DESC LIMIT 1) AS lastactivity,
						(SELECT COUNT(id) FROM ". CDMTASKS ." WHERE ". CDMTASKS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMTASKS .".`status` = 0) AS cnttasks,
						(SELECT COUNT(id) FROM ". CDMOPPS ." WHERE ". CDMOPPS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMOPPS .".`oppsstatus` NOT IN('SP')) AS cntopps
					FROM ". ABAPEOPLESMST ."
					$where ";
			$qry = $this->cn->query($sql);
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			return $res;
		}

		function searchBDActivityLog($data){
			$res = array();
			$searchtext = addslashes($data['searchtext']);
			$searchby = $data['searchby'];

			switch($searchby){
				case 'bdname';
					$and = " AND CONCAT(". ABAPEOPLESMST .".`fname`,' ',". ABAPEOPLESMST .".`lname`) LIKE '%$searchtext%' ";
					break;
				case 'abahk';
					$and = " AND ". ABAPEOPLESMST .".`webhr_station` = '$searchby' ";
					goto here;
					break;
				case 'abasg';
					$and = " AND ". ABAPEOPLESMST .".`webhr_station` = '$searchby' ";
					goto here;
					break;
				case 'runsha';
					$and = " AND ". ABAPEOPLESMST .".`webhr_station` IN ('$searchby','abasha') ";
					goto here;
					break;
				case 'runbei';
					$and = " AND ". ABAPEOPLESMST .".`webhr_station` IN ('$searchby','ababei') ";
					goto here;
					break;
				default:
					goto all;
					break;

			}
			if(!empty($searchtext)){
				here:
				$sql = "SELECT ". ABAPEOPLESMST .".userid,
							". ABAPEOPLESMST .".`fname`,
							". ABAPEOPLESMST .".`mname`,
							". ABAPEOPLESMST .".`lname`,
							". ABAPEOPLESMST .".`cnname`,
							". ABAPEOPLESMST .".`abaini`,
							". ABAPEOPLESMST .".`emailaddress`,
							". ABAPEOPLESMST .".`webhr_designation`,
							". ABAPEOPLESMST .".`webhr_station`,
							(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
			              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
			              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
			              		ELSE '' 
			        		END) as eedesignationini,
							(SELECT CONCAT(DATE_FORMAT(". CDMACTIVITIES .".`createddate`,'%a %d %b %y'),' - ',". CDMACTIVITIES .".`actdetails`) FROM ". CDMACTIVITIES ." WHERE ". CDMACTIVITIES .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMACTIVITIES .".`acttype` != 'login' ORDER BY ". CDMACTIVITIES .".`createddate` DESC LIMIT 1) AS lastactivity,
							(SELECT COUNT(id) FROM ". CDMTASKS ." WHERE ". CDMTASKS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMTASKS .".`status` = 0) AS cnttasks,
							(SELECT COUNT(id) FROM ". CDMOPPS ." WHERE ". CDMOPPS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMOPPS .".`oppsstatus` NOT IN('SP')) AS cntopps
						FROM ". ABAPEOPLESMST ."
						WHERE ". ABAPEOPLESMST .".`status` = 1 
						    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
						    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
											'business development executive',
											'business development executive eb',
											'business development manager',
											'business development manager',
											'general manager beijing',
											'general manager for china',
											'general manager hong kong',
											'general manager singapore')
							$and 
							ORDER BY ". ABAPEOPLESMST .".fname ASC ";
				}else{
					all:
					$sql = "SELECT ". ABAPEOPLESMST .".userid,
							". ABAPEOPLESMST .".`fname`,
							". ABAPEOPLESMST .".`mname`,
							". ABAPEOPLESMST .".`lname`,
							". ABAPEOPLESMST .".`cnname`,
							". ABAPEOPLESMST .".`abaini`,
							". ABAPEOPLESMST .".`emailaddress`,
							". ABAPEOPLESMST .".`webhr_designation`,
							". ABAPEOPLESMST .".`webhr_station`,
							(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%general manager%' THEN 'GM' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development manager%' THEN 'BDM' 
			              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development exe%' THEN 'BDE' 
			              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%business development director%' THEN 'BDD' 
			              		ELSE '' 
			        		END) as eedesignationini,
							(SELECT CONCAT(DATE_FORMAT(". CDMACTIVITIES .".`createddate`,'%a %d %b %y'),' - ',". CDMACTIVITIES .".`actdetails`) FROM ". CDMACTIVITIES ." WHERE ". CDMACTIVITIES .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMACTIVITIES .".`acttype` != 'login' ORDER BY ". CDMACTIVITIES .".`createddate` DESC LIMIT 1) AS lastactivity,
							(SELECT COUNT(id) FROM ". CDMTASKS ." WHERE ". CDMTASKS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMTASKS .".`status` = 0) AS cnttasks,
							(SELECT COUNT(id) FROM ". CDMOPPS ." WHERE ". CDMOPPS .".`userid` = ". ABAPEOPLESMST .".`userid` AND ". CDMOPPS .".`oppsstatus` NOT IN('SP')) AS cntopps
						FROM ". ABAPEOPLESMST ."
						WHERE ". ABAPEOPLESMST .".`status` = 1 
						    AND ". ABAPEOPLESMST .".`contactcategory` = 1 
						    AND ". ABAPEOPLESMST .".`webhr_designation` IN ('business development director',
											'business development executive',
											'business development executive eb',
											'business development manager',
											'business development manager',
											'general manager beijing',
											'general manager for china',
											'general manager hong kong',
											'general manager singapore')
						ORDER BY ". ABAPEOPLESMST .".webhr_station, ". ABAPEOPLESMST .".webhr_designation, ". ABAPEOPLESMST .".lname";
				}
			$qry = $this->cn->query($sql);
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			return $res;
		}
		public function getAllAbaPeople(){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
			$contactsinfo = "" . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno, 
							 " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							 " . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							 " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 

			$personaldata = "" . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".nationality, " . ABAPEOPLESMST . ".maritalstatus, 
							 " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender
							 ";

			$employeedata = "" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							 " . ABAPEOPLESMST . ".department," . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							 " . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid";

			$sql = "SELECT " . ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig," . ABAPEOPLESMST . ".abaini, $personaldata, $contactsinfo, $employeedata
						,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %Y') AS birthdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %Y') AS joindt
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname						
						,a.description as designationnamedesc
						,b.description as officename
						,c.description as nationalitydesc
						,d.dddescription as eetypedesc
						,e.dddescription as eecategorydesc
						,f.dddescription as maritalstat
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " a
						ON a.designationid = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " b
					  	ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
					  LEFT JOIN " . NATIONALITYMST . " c
					  	ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
					  LEFT JOIN " . DROPDOWNSMST . " d
					  	ON d.ddid = " . ABAPEOPLESMST . ".eetype
					  	AND d.dddisplay = 'eetype' 
					  LEFT JOIN " . DROPDOWNSMST . " e
					  	ON e.ddid = " . ABAPEOPLESMST . ".eecategory
					  	AND e.dddisplay = 'eecategory' 
					  LEFT JOIN " . DROPDOWNSMST . " f
					  	ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
					  	AND f.dddisplay = 'maritalstatus' 
					  WHERE " . ABAPEOPLESMST . ".status = 1 AND " . ABAPEOPLESMST . ".contactcategory = 1
					  ORDER BY " . ABAPEOPLESMST . ".abaini " ;
			
			$qry = $this->cn->query($sql);
			// $res['sql'] = $sql;
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllAbaPeople()! " . $this->cn->error;
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

		public function getAllAbaPeopleWithStatus($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$statFilter = $data['statFilter'];
			$ofc = $data['ofc'];
			// $userid = $data['userid'];
			// $showAll = $data['showAll'];

			$statWhere = '';
			$ofcWhere = '';

			if($statFilter != 2){
				$statWhere =  "AND " . ABAPEOPLESMST . ".`status` = '$statFilter' ";
			}
			// if($showAll != 1){
			// 	if(isset($data['aba']) && isset($data['ssc'])) {
			// 		$ofcaba = $data['aba'];
			// 		$ofcssc = $data['ssc'];
			// 		$ofcWhere = "AND (b.`description` = '$ofcaba' OR b.`description` = '$ofcssc') ";
			// 	} else {
			// 		$ofcWhere = "AND b.`description` = '$ofc' ";
			// 	}
			// }
			

			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
			$contactsinfo = "" . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno, 
							 " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							 " . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							 " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 

			$personaldata = "" . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".nationality, " . ABAPEOPLESMST . ".maritalstatus, 
							 " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender
							 ";

			$employeedata = "" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							 " . ABAPEOPLESMST . ".department," . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							 " . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid";
			
			$sql = "SELECT " . ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig," . ABAPEOPLESMST . ".abaini, $personaldata, $contactsinfo, $employeedata
						,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %Y') AS birthdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %Y') AS joindt
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname						
						,a.description as designationnamedesc
						,b.description as officename
						,c.description as nationalitydesc
						,d.dddescription as eetypedesc
						,e.dddescription as eecategorydesc
						,f.dddescription as maritalstat
						,g.description as departmentdesc
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " a
						ON a.designationid = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " b
					  	ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
					  LEFT JOIN " . NATIONALITYMST . " c
					  	ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
					  LEFT JOIN " . DROPDOWNSMST . " d
					  	ON d.ddid = " . ABAPEOPLESMST . ".eetype
					  	AND d.dddisplay = 'eetype' 
					  LEFT JOIN " . DROPDOWNSMST . " e
					  	ON e.ddid = " . ABAPEOPLESMST . ".eecategory
					  	AND e.dddisplay = 'eecategory' 
					  LEFT JOIN " . DROPDOWNSMST . " f
					  	ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
					  	AND f.dddisplay = 'maritalstatus' 
					  LEFT JOIN " . DEPARTMENTSMST . " g
					  	ON g.departmentid = " . ABAPEOPLESMST . ".department
					  WHERE " . ABAPEOPLESMST . ".contactcategory = 1 AND " . ABAPEOPLESMST . ".`status` != '-1' " . $statWhere . " " . $ofcWhere . " 
					  ORDER BY " . ABAPEOPLESMST . ".abaini " ;
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllAbaPeople()! " . $this->cn->error;
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

		public function getEmployeeData($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$id = $data['userid'];
			$sesid = $data['sesid'];
			$action = $data['action'];
			$profilegroup = $data['profilegroup'];

			$where = " WHERE " . ABAPEOPLESMST . ".userid = '$id' AND " . ABAPEOPLESMST . ".contactcategory = 1"; // AND " . ABAPEOPLESMST . ".status = 1
			if($action == 'editprofile' || $action == 'viewprofile' || $action == 'viewbygm'){
				$where = " WHERE " . ABAPEOPLESMST . ".sesid = '$sesid' AND " . ABAPEOPLESMST . ".contactcategory = 1"; // AND " . ABAPEOPLESMST . ".status = 1
			}

			switch ($profilegroup) {
				case 'contactinfo':
					$columns = "," . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno,
							" . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							" . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							" . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 
					break;
				case 'personalinfo':
					$columns = "," . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".cnname, " . ABAPEOPLESMST . ".nationality, 
							" . ABAPEOPLESMST . ".maritalstatus, " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender,
							" . ABAPEOPLESMST . ".passportno, " . ABAPEOPLESMST . ".passportissueddate, " . ABAPEOPLESMST . ".passportexpiry, " . ABAPEOPLESMST . ".passportissuancecountry,
							" . ABAPEOPLESMST . ".govtidsocsec, " . ABAPEOPLESMST . ".salutation";
					break;
				case 'employeedata':
					$columns = "," . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							" . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							" . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno,
							" . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid, " . ABAPEOPLESMST . ".probationperiodmonth, 
							" . ABAPEOPLESMST . ".terminationperiodnoticemonth, " . ABAPEOPLESMST . ".workingvisabyabacare, " . ABAPEOPLESMST . ".workingvisaexpirationdate,g.description AS deptdesc,
							h.dddescription AS postgradedesc," . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".probationcompletiondate, " . ABAPEOPLESMST . ".probationcompletiondate, 
							" . ABAPEOPLESMST . ".lastworkingday, " . ABAPEOPLESMST . ".effectivedate, 
							" . ABAPEOPLESMST . ".companynameof1stcontractsigned, " . ABAPEOPLESMST . ".dateofmostrecentcontracteffective, " . ABAPEOPLESMST . ".actualplaceofcurrentwork, 
							" . ABAPEOPLESMST . ".workingtypeofvisa, " . ABAPEOPLESMST . ".startshift, " . ABAPEOPLESMST . ".endshift";
					break;
				case 'contactlist':
					$columns = "," . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".cnname," . ABAPEOPLESMST . ".nationality, 
							" . ABAPEOPLESMST . ".maritalstatus, " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender," . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, 
							" . ABAPEOPLESMST . ".homephoneno, " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							" . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							" . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation,
							CONCAT(j.fname,' ',j.lname,' ',j.abaini) AS reportstoname, CONCAT(n.fname,' ',n.lname,' ',n.abaini) AS reportstoindirectname, " . ABAPEOPLESMST . ".officephoneno, " . ABAPEOPLESMST . ".telext";
					break;
				case 'profile':
					$columns = "," . ABAPEOPLESMST . ".cnname," . ABAPEOPLESMST . ".nationality, 
							" . ABAPEOPLESMST . ".maritalstatus, " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender," . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, 
							" . ABAPEOPLESMST . ".homephoneno, " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							" . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							" . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation,
							" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							" . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							" . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno,
							" . ABAPEOPLESMST . ".workskype, " . ABAPEOPLESMST . ".workingvisabyabacare," . ABAPEOPLESMST . ".workingvisaexpirationdate," . ABAPEOPLESMST . ".probationperiodmonth,
							" . ABAPEOPLESMST . ".terminationperiodnoticemonth,g.description AS deptdesc,h.dddescription AS postgradedesc,i.description AS countrydesc, 
							" . ABAPEOPLESMST . ".effectivedate, " . ABAPEOPLESMST . ".monthlygrosssalaryinlocalcurrencyshownincontract, " . ABAPEOPLESMST . ".monthlyemployerscontributionmpfmfpsss, 
							" . ABAPEOPLESMST . ".monthlyaplusmedicalinsuranceinhkd, " . ABAPEOPLESMST . ".monthlymedicalinsuranceinlocal, " . ABAPEOPLESMST . ".monthlybusinessexpensesallowanceincontractinlocal, 
							" . ABAPEOPLESMST . ".companynameof1stcontractsigned, " . ABAPEOPLESMST . ".dateofmostrecentcontracteffective, " . ABAPEOPLESMST . ".workingtypeofvisa";
					break;
				case 'compensationandbenefits':
					$columns = "," . ABAPEOPLESMST . ".monthlygrosssalaryinlocalcurrencyshownincontract, " . ABAPEOPLESMST . ".monthlyemployerscontributionmpfmfpsss, 
							" . ABAPEOPLESMST . ".monthlyaplusmedicalinsuranceinhkd, " . ABAPEOPLESMST . ".monthlymedicalinsuranceinlocal, " . ABAPEOPLESMST . ".monthlybusinessexpensesallowanceincontractinlocal";
					break;

				case 'accountsettings':
					$columns = "," . ABAPEOPLESMST . ".zkid, " . ABAPEOPLESMST . ".zkdeviceid";
					break;
				default:
					break;
			}
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";

			$sql = "SELECT " . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".abaini, ". ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig $columns 
						,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %Y') AS birthdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %Y') AS joindt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".passportissueddate, '%a %d %b %Y') AS passportissueddt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".passportexpiry, '%a %d %b %Y') AS passportexpirydt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".probationcompletiondate, '%a %d %b %Y') AS probationcompletiondate
						,DATE_FORMAT(" . ABAPEOPLESMST . ".lastworkingday, '%a %d %b %Y') AS lastworkingday
						,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivedate, '%a %d %b %Y') AS effectivedate
						,DATE_FORMAT(" . ABAPEOPLESMST . ".dateofmostrecentcontracteffective, '%a %d %b %Y') AS dateofmostrecentcontracteffective
						,DATE_FORMAT(" . ABAPEOPLESMST . ".probationenddate, '%a %d %b %Y') AS probationenddt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".regularizationdate, '%a %d %b %Y') AS regularizationdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".workingstartofvisa, '%a %d %b %Y') AS workingstartofvisadt
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname	
						,(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%human resources manager%' THEN 'ADMIN' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%human resources supervisor%' THEN 'ADMIN' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%projects supervisor%' THEN 'ADMIN' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%it%' THEN 'ADMIN' 
			              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%human resources executive' THEN 'HRE' 
			              		ELSE '' 
			        		END) as eedesignationini
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname						
						,a.description AS designationnamedesc
						,b.description AS officename
						,c.description AS nationalitydesc
						,d.dddescription AS eetypedesc
						,e.dddescription AS eecategorydesc
						,f.dddescription AS maritalstat
						,". ABAPEOPLESMST .".govtidsocsec
						,". ABAPEOPLESMST .".passportno
						,k.countryid AS passportissuancecountry
						,k.description AS passportissuancecountrydesc
						,l.ddid AS salutationid
						,l.dddescription AS salutation
						,m.countryid AS actualplaceofcurrentworkid
						,m.description AS actualplaceofcurrentwork
						,". ABAPEOPLESMST .".startshift
						,". ABAPEOPLESMST .".endshift
						,DATE_FORMAT(" . ABAPEOPLESMST . ".probationperiodmonth, '%a %d %b %Y') AS probationperiod
						,DATE_FORMAT(" . ABAPEOPLESMST . ".terminationperiodnoticemonth, '%a %d %b %Y') AS terminationperiod
						,DATE_FORMAT(" . ABAPEOPLESMST . ".workingvisaexpirationdate, '%a %d %b %Y') AS visaexpireddate
						," . ABAPEOPLESMST . ".department 
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " a
						ON a.designationid = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " b
					  	ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
					  LEFT JOIN " . NATIONALITYMST . " c
					  	ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
					  LEFT JOIN " . DROPDOWNSMST . " d
					  	ON d.ddid = " . ABAPEOPLESMST . ".eetype
					  	AND d.dddisplay = 'eetype' 
					  LEFT JOIN " . DROPDOWNSMST . " e
					  	ON e.ddid = " . ABAPEOPLESMST . ".eecategory
					  	AND e.dddisplay = 'eecategory' 
					  LEFT JOIN " . DROPDOWNSMST . " f
					  	ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
					  	AND f.dddisplay = 'maritalstatus' 
					  LEFT JOIN ". DEPARTMENTSMST ." g
					  	ON g.departmentid = " . ABAPEOPLESMST . ".department 
					  LEFT JOIN ". DROPDOWNSMST ." h 
					  	ON h.ddid = ". ABAPEOPLESMST .".positiongrade AND h.dddisplay = 'eerankings' 
					  LEFT JOIN ". COUNTRIESMST ." i 
					  	ON i.countryid = ". ABAPEOPLESMST .".presentcountry 
					  LEFT JOIN " . ABAPEOPLESMST . " j 
						ON j.userid = " . ABAPEOPLESMST . ".reportstoid AND j.status = 1 
					  LEFT JOIN ". COUNTRIESMST ." k 
						ON k.countryid = ". ABAPEOPLESMST .".passportissuancecountry 
					  LEFT JOIN ". DROPDOWNSMST ." l 
						ON l.ddid = ". ABAPEOPLESMST .".salutation AND  l.dddisplay = 'eesalutation'
					  LEFT JOIN ". COUNTRIESMST ." m 
					  	ON m.countryid = ". ABAPEOPLESMST .".actualplaceofcurrentwork 
					  LEFT JOIN " . ABAPEOPLESMST . " n 
						ON n.userid = " . ABAPEOPLESMST . ".reportstoindirectid AND n.status = 1 
					$where ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getEmployeeData()! " . $this->cn->error;
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

		public function getEmployeeData_org($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$id = $data['userid'];
			$sesid = $data['sesid'];
			$action = $data['action'];
			$profilegroup = $data['profilegroup'];

			$where = " WHERE " . ABAPEOPLESMST . ".userid = '$id' AND " . ABAPEOPLESMST . ".contactcategory = 1 AND " . ABAPEOPLESMST . ".status = 1"; // AND " . ABAPEOPLESMST . ".status = 1
			if($action == 'editprofile' || $action == 'viewprofile'){
				$where = " WHERE " . ABAPEOPLESMST . ".sesid = '$sesid' AND " . ABAPEOPLESMST . ".contactcategory = 1"; // AND " . ABAPEOPLESMST . ".status = 1
			}

			switch ($profilegroup) {
				case 'contactinfo':
					$columns = "," . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno,
							" . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							" . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							" . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 
					break;
				case 'personalinfo':
					$columns = "," . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".cnname, " . ABAPEOPLESMST . ".nationality, 
							" . ABAPEOPLESMST . ".maritalstatus, " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender,
							" . ABAPEOPLESMST . ".passportno, " . ABAPEOPLESMST . ".passportissueddate, " . ABAPEOPLESMST . ".passportexpiry, " . ABAPEOPLESMST . ".passportissuancecountry,
							" . ABAPEOPLESMST . ".govtidsocsec, " . ABAPEOPLESMST . ".salutation";
					break;
				case 'employeedata':
					$columns = "," . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							" . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							" . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno,
							" . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid, " . ABAPEOPLESMST . ".probationperiodmonth, 
							" . ABAPEOPLESMST . ".terminationperiodnoticemonth, " . ABAPEOPLESMST . ".workingvisabyabacare, " . ABAPEOPLESMST . ".workingvisaexpirationdate,g.description AS deptdesc,
							h.dddescription AS postgradedesc," . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".probationcompletiondate, " . ABAPEOPLESMST . ".probationcompletiondate, 
							" . ABAPEOPLESMST . ".lastworkingday, " . ABAPEOPLESMST . ".effectivedate, 
							" . ABAPEOPLESMST . ".companynameof1stcontractsigned, " . ABAPEOPLESMST . ".dateofmostrecentcontracteffective, " . ABAPEOPLESMST . ".actualplaceofcurrentwork, 
							" . ABAPEOPLESMST . ".workingtypeofvisa, " . ABAPEOPLESMST . ".startshift, " . ABAPEOPLESMST . ".endshift";
					break;
				case 'contactlist':
					$columns = "," . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".cnname," . ABAPEOPLESMST . ".nationality, 
							" . ABAPEOPLESMST . ".maritalstatus, " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender," . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, 
							" . ABAPEOPLESMST . ".homephoneno, " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							" . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							" . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation,
							CONCAT(j.fname,' ',j.lname,' ',j.abaini) AS reportstoname, CONCAT(n.fname,' ',n.lname,' ',n.abaini) AS reportstoindirectname, " . ABAPEOPLESMST . ".officephoneno, " . ABAPEOPLESMST . ".telext, " . ABAPEOPLESMST . ".workemail";
					break;
				case 'profile':
					$columns = "," . ABAPEOPLESMST . ".cnname," . ABAPEOPLESMST . ".nationality, 
							" . ABAPEOPLESMST . ".maritalstatus, " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender," . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, 
							" . ABAPEOPLESMST . ".homephoneno, " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							" . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							" . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation,
							" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							" . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							" . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno,
							" . ABAPEOPLESMST . ".workskype, " . ABAPEOPLESMST . ".workingvisabyabacare," . ABAPEOPLESMST . ".workingvisaexpirationdate," . ABAPEOPLESMST . ".probationperiodmonth,
							" . ABAPEOPLESMST . ".terminationperiodnoticemonth,g.description AS deptdesc,h.dddescription AS postgradedesc,i.description AS countrydesc, 
							" . ABAPEOPLESMST . ".effectivedate, " . ABAPEOPLESMST . ".monthlygrosssalaryinlocalcurrencyshownincontract, " . ABAPEOPLESMST . ".monthlyemployerscontributionmpfmfpsss, 
							" . ABAPEOPLESMST . ".monthlyaplusmedicalinsuranceinhkd, " . ABAPEOPLESMST . ".monthlymedicalinsuranceinlocal, " . ABAPEOPLESMST . ".monthlybusinessexpensesallowanceincontractinlocal, 
							" . ABAPEOPLESMST . ".companynameof1stcontractsigned, " . ABAPEOPLESMST . ".dateofmostrecentcontracteffective, " . ABAPEOPLESMST . ".workingtypeofvisa";
					break;
				case 'compensationandbenefits':
					$columns = "," . ABAPEOPLESMST . ".monthlygrosssalaryinlocalcurrencyshownincontract, " . ABAPEOPLESMST . ".monthlyemployerscontributionmpfmfpsss, 
							" . ABAPEOPLESMST . ".monthlyaplusmedicalinsuranceinhkd, " . ABAPEOPLESMST . ".monthlymedicalinsuranceinlocal, " . ABAPEOPLESMST . ".monthlybusinessexpensesallowanceincontractinlocal";
					break;

				case 'accountsettings':
					$columns = "," . ABAPEOPLESMST . ".zkid, " . ABAPEOPLESMST . ".zkdeviceid";
					break;
				default:
					break;
			}
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";

			$sql = "SELECT " . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".abaini, ". ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig $columns 
						,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %Y') AS birthdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %Y') AS joindt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".passportissueddate, '%a %d %b %Y') AS passportissueddt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".passportexpiry, '%a %d %b %Y') AS passportexpirydt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".probationcompletiondate, '%a %d %b %Y') AS probationcompletiondate
						,DATE_FORMAT(" . ABAPEOPLESMST . ".lastworkingday, '%a %d %b %Y') AS lastworkingday
						,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivedate, '%a %d %b %Y') AS effectivedate
						,DATE_FORMAT(" . ABAPEOPLESMST . ".dateofmostrecentcontracteffective, '%a %d %b %Y') AS dateofmostrecentcontracteffective
						,DATE_FORMAT(" . ABAPEOPLESMST . ".probationenddate, '%a %d %b %Y') AS probationenddt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".regularizationdate, '%a %d %b %Y') AS regularizationdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".workingstartofvisa, '%a %d %b %Y') AS workingstartofvisadt
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname	
						,(CASE WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%human resources manager%' THEN 'ADMIN' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%human resources supervisor%' THEN 'ADMIN' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%projects supervisor%' THEN 'ADMIN' 
			      		  		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%it%' THEN 'ADMIN' 
			              		WHEN ". ABAPEOPLESMST .".`webhr_designation` LIKE '%human resources executive' THEN 'HRE' 
			              		ELSE '' 
			        		END) as eedesignationini
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname						
						,a.description AS designationnamedesc
						,b.description AS officename
						,c.description AS nationalitydesc
						,d.dddescription AS eetypedesc
						,e.dddescription AS eecategorydesc
						,f.dddescription AS maritalstat
						,". ABAPEOPLESMST .".govtidsocsec
						,". ABAPEOPLESMST .".passportno
						,k.countryid AS passportissuancecountry
						,k.description AS passportissuancecountrydesc
						,l.ddid AS salutationid
						,l.dddescription AS salutation
						,m.countryid AS actualplaceofcurrentworkid
						,m.description AS actualplaceofcurrentwork
						,". ABAPEOPLESMST .".startshift
						,". ABAPEOPLESMST .".endshift
						,DATE_FORMAT(" . ABAPEOPLESMST . ".probationperiodmonth, '%a %d %b %Y') AS probationperiod
						,DATE_FORMAT(" . ABAPEOPLESMST . ".terminationperiodnoticemonth, '%a %d %b %Y') AS terminationperiod
						,DATE_FORMAT(" . ABAPEOPLESMST . ".workingvisaexpirationdate, '%a %d %b %Y') AS visaexpireddate
						," . ABAPEOPLESMST . ".department 
					FROM " . ABAPEOPLESMST. 
					" LEFT JOIN " . DESIGNATIONSMST . " a
						ON a.designationid = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " b
					  	ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
					  LEFT JOIN " . NATIONALITYMST . " c
					  	ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
					  LEFT JOIN " . DROPDOWNSMST . " d
					  	ON d.ddid = " . ABAPEOPLESMST . ".eetype
					  	AND d.dddisplay = 'eetype' 
					  LEFT JOIN " . DROPDOWNSMST . " e
					  	ON e.ddid = " . ABAPEOPLESMST . ".eecategory
					  	AND e.dddisplay = 'eecategory' 
					  LEFT JOIN " . DROPDOWNSMST . " f
					  	ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
					  	AND f.dddisplay = 'maritalstatus' 
					  LEFT JOIN ". DEPARTMENTSMST ." g
					  	ON g.departmentid = " . ABAPEOPLESMST . ".department 
					  LEFT JOIN ". DROPDOWNSMST ." h 
					  	ON h.ddid = ". ABAPEOPLESMST .".positiongrade AND h.dddisplay = 'eerankings' 
					  LEFT JOIN ". COUNTRIESMST ." i 
					  	ON i.countryid = ". ABAPEOPLESMST .".presentcountry 
					  LEFT JOIN " . ABAPEOPLESMST . " j 
						ON j.userid = " . ABAPEOPLESMST . ".reportstoid AND j.status = 1 
					  LEFT JOIN ". COUNTRIESMST ." k 
						ON k.countryid = ". ABAPEOPLESMST .".passportissuancecountry 
					  LEFT JOIN ". DROPDOWNSMST ." l 
						ON l.ddid = ". ABAPEOPLESMST .".salutation AND  l.dddisplay = 'eesalutation'
					  LEFT JOIN ". COUNTRIESMST ." m 
					  	ON m.countryid = ". ABAPEOPLESMST .".actualplaceofcurrentwork 
					  LEFT JOIN " . ABAPEOPLESMST . " n 
						ON n.userid = " . ABAPEOPLESMST . ".reportstoindirectid AND n.status = 1 
					$where ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getEmployeeData()! " . $this->cn->error;
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

		function updateEmployeeInfo($data){
			$res['err'] = 0;
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$profilegroup = $data['profilegroup'];
			$today = TODAY;
			$salutationid = $data['salutationid'] == "" ? 0 : addslashes($data['salutationid']);
			$abaini = $data['abaini'] == "" ? "" : addslashes($data['abaini']);
			$lastname = $data['lastname'] == "" ? "" : addslashes($data['lastname']);
			$firstname = $data['firstname'] == "" ? "" : addslashes($data['firstname']);
			$cnname = $data['cnname'] == "" ? "" : addslashes($data['cnname']);
			$nationality = $data['nationality'] == "" ? "" : addslashes($data['nationality']);
			$maritalstatus = $data['maritalstatus'] == "" ? "" : addslashes($data['maritalstatus']);
			if($data['birthdate'] != ""){
				$birthdate = formatDate("Y-m-d",$data['birthdate']);
			}else{
				$birthdate = "1900-01-01 00:00:00";
			}
			$gender = $data['gender'] == "" ? "" : addslashes($data['gender']);
			$govtidsocsec = $data['govtidsocsec'] == "" ? "" : addslashes($data['govtidsocsec']);
			$passportno = $data['passportno'] == "" ? "" : addslashes($data['passportno']);
			// $passportissueddate = $data['passportissueddate'] == "" ? "" : addslashes($data['passportissueddate']);
			// $passportexpiry = $data['passportexpiry'] == "" ? "" : addslashes($data['passportexpiry']);
			$passportissuancecountry = $data['passportissuancecountry'] == "" ? "" : addslashes($data['passportissuancecountry']);
			if($data['passportissueddate'] != ""){
				$passportissueddate = formatDate("Y-m-d",$data['passportissueddate']);
			}else{
				$passportissueddate = "1900-01-01 00:00:00";
			}
			if($data['passportexpiry'] != ""){
				$passportexpiry = formatDate("Y-m-d",$data['passportexpiry']);
			}else{
				$passportexpiry = "1900-01-01 00:00:00";
			}

			$eadd = $data['eadd'] == "" ? "" : addslashes($data['eadd']);
			$mobphone = $data['mobphone'] == "" ? "" : addslashes($data['mobphone']);
			$homephone = $data['homephone'] == "" ? "" : addslashes($data['homephone']);
			$wechat = $data['wechat'] == "" ? "" : addslashes($data['wechat']);
			$skype = $data['skype'] == "" ? "" : addslashes($data['skype']);
			$whatsapp = $data['whatsapp'] == "" ? "" : addslashes($data['whatsapp']);
			$linkedin = $data['linkedin'] == "" ? "" : addslashes($data['linkedin']);
			$presentcity = $data['presentcity'] == "" ? "" : addslashes($data['presentcity']);
			$presentstate = $data['presentstate'] == "" ? "" : addslashes($data['presentstate']);
			$presentcountry = $data['presentcountry'] == "" ? "" : addslashes($data['presentcountry']);
			$presentzipcode = $data['presentzipcode'] == "" ? "" : addslashes($data['presentzipcode']);
			$presentaddr = $data['presentaddr'] == "" ? "" : addslashes($data['presentaddr']);
			$emercontactperson = $data['emercontactperson'] == "" ? "" : addslashes($data['emercontactperson']);
			$emercontactno = $data['emercontactno'] == "" ? "" : addslashes($data['emercontactno']);
			$emercontactrelation = $data['emercontactrelation'] == "" ? "" : addslashes($data['emercontactrelation']);

			if($data['joineddate'] != ""){
				$joineddate = formatDate("Y-m-d",$data['joineddate']);
			}else{
				$joineddate = "1900-01-01 00:00:00";
			}
			$ofc = $data['ofc'] == "" ? "" : addslashes($data['ofc']);
			$position = $data['position'] == "" ? "" : addslashes($data['position']);
			$dept = $data['dept'] == "" ? "" : addslashes($data['dept']);
			$eecat = $data['eecat'] == "" ? "" : addslashes($data['eecat']);
			$emp_status = $data['eestatus'];
			$posgrade = $data['posgrade'] == "" ? "" : addslashes($data['posgrade']);
			$reportsto = $data['reportsto'] == "" ? "" : addslashes($data['reportsto']);
			$reportstoindirect = $data['reportstoindirect'] == "" ? "" : addslashes($data['reportstoindirect']);
			$reportstotext = $data['reportstotext'] == "" ? "" : addslashes($data['reportstotext']);
			$reportstoindirecttext = $data['reportstoindirecttext'] == "" ? "" : addslashes($data['reportstoindirecttext']);
			$workeadd = $data['workeadd'] == "" ? "" : addslashes($data['workeadd']);
			$ofcno = $data['ofcno'] == "" ? "" : addslashes($data['ofcno']);
			$workskype = $data['workskype'] == "" ? "" : addslashes($data['workskype']);
			$startshift = $data['startshift'] == "" ? "" : addslashes($data['startshift']);
			$endshift = $data['endshift'] == "" ? "" : addslashes($data['endshift']);

			
			// $probationcompletiondate = $data['probationcompletiondate'] == "" ? "" : addslashes($data['probationcompletiondate']);
			// $lastworkingday = $data['lastworkingday'] == "" ? "" : addslashes($data['lastworkingday']);
			// $effectivedate = $data['effectivedate'] == "" ? "" : addslashes($data['effectivedate']);
			$monthlygrosssalaryinlocalcurrencyshownincontract = $data['monthlygrosssalaryinlocalcurrencyshownincontract'] == '' ? 0 : addslashes($data['monthlygrosssalaryinlocalcurrencyshownincontract']);
			$monthlyemployerscontributionmpfmfpsss = $data['monthlyemployerscontributionmpfmfpsss'] == "" ? 0 : addslashes($data['monthlyemployerscontributionmpfmfpsss']);
			$monthlyaplusmedicalinsuranceinhkd = $data['monthlyaplusmedicalinsuranceinhkd'] == "" ? 0 : addslashes($data['monthlyaplusmedicalinsuranceinhkd']);
			$monthlymedicalinsuranceinlocal = $data['monthlymedicalinsuranceinlocal'] == "" ? 0 : addslashes($data['monthlymedicalinsuranceinlocal']);
			$monthlybusinessexpensesallowanceincontractinlocal = $data['monthlybusinessexpensesallowanceincontractinlocal'] == "" ? 0 : addslashes($data['monthlybusinessexpensesallowanceincontractinlocal']);
			$companynameof1stcontractsigned = $data['companynameof1stcontractsigned'] == "" ? "" : addslashes($data['companynameof1stcontractsigned']);
			// $dateofmostrecentcontracteffective = $data['dateofmostrecentcontracteffective'] == "" ? "" : addslashes($data['dateofmostrecentcontracteffective']);
			$actualplaceofcurrentwork = $data['actualplaceofcurrentwork'] == "" ? "" : addslashes($data['actualplaceofcurrentwork']);
			if($data['probationcompletiondate'] != ""){
				$probationcompletiondate = formatDate("Y-m-d",$data['probationcompletiondate']);
			}else{
				$probationcompletiondate = "1900-01-01 00:00:00";
			}

			if($data['lastworkingday'] != ""){
				$lastworkingday = formatDate("Y-m-d",$data['lastworkingday']);
			}else{
				$lastworkingday = "1900-01-01 00:00:00";
			}

			if($data['effectivedate'] != ""){
				$effectivedate = formatDate("Y-m-d",$data['effectivedate']);
			}else{
				$effectivedate = "1900-01-01 00:00:00";
			}

			if($data['dateofmostrecentcontracteffective'] != ""){
				$dateofmostrecentcontracteffective = formatDate("Y-m-d",$data['dateofmostrecentcontracteffective']);
			}else{
				$dateofmostrecentcontracteffective = "1900-01-01 00:00:00";
			}

			$zkteco_office = $data['zkteco_office'] == "" ? 0 : addslashes($data['zkteco_office']);
			$zkteco_id = $data['zkteco_id'] == "" ? 0 : addslashes($data['zkteco_id']);

			if($data['probationperiod'] != ""){
				$probationperiod = formatDate("Y-m-d",$data['probationperiod']);
			}else{
				$probationperiod = "1900-01-01 00:00:00";
			}
			if($data['terminationperiod'] != ""){
				$terminationperiod = formatDate("Y-m-d",$data['terminationperiod']);
			}else{
				$terminationperiod = "1900-01-01 00:00:00";
			}
			$visaprocessedbyaba = $data['visaprocessedbyaba'] == "" ? "" : addslashes($data['visaprocessedbyaba']);
			if($data['visaexpireddate'] != ""){
				$visaexpireddate = formatDate("Y-m-d",$data['visaexpireddate']);
			}else{
				$visaexpireddate = "1900-01-01 00:00:00";
			}

			if($data['probationenddate'] != ""){
				$probationenddate = formatDate("Y-m-d",$data['probationenddate']);
			}else{
				$probationenddate = "1900-01-01 00:00:00";
			}

			if($data['regularizationdate'] != ""){
				$regularizationdate = formatDate("Y-m-d",$data['regularizationdate']);
			}else{
				$regularizationdate = "1900-01-01 00:00:00";
			}

			$typeofvisa = $data['typeofvisa'] == "" ? "" : addslashes($data['typeofvisa']);

			if($data['startofvisa'] != ""){
				$startofvisa = formatDate("Y-m-d",$data['startofvisa']);
			}else{
				$startofvisa = "1900-01-01 00:00:00";
			}

			if($data['startcontractdate'] != ""){
				$startcontractdate = formatDate("Y-m-d",$data['startcontractdate']);
			}else{
				$startcontractdate = "1900-01-01 00:00:00";
			}

			if($data['endcontractdate'] != ""){
				$endcontractdate = formatDate("Y-m-d",$data['endcontractdate']);
			}else{
				$endcontractdate = "1900-01-01 00:00:00";
			}

			switch ($profilegroup) {
				case 'personalinfo':
					$columns = "" . ABAPEOPLESMST . ".salutation = '$salutationid',
							 " . ABAPEOPLESMST . ".lname = '$lastname',
							 " . ABAPEOPLESMST . ".fname = '$firstname',
							 " . ABAPEOPLESMST . ".cnname = '$cnname',
							 " . ABAPEOPLESMST . ".nationality = '$nationality',
							 " . ABAPEOPLESMST . ".maritalstatus = '$maritalstatus',
							 " . ABAPEOPLESMST . ".birthdate = '$birthdate',
							 " . ABAPEOPLESMST . ".gender = '$gender',
							 " . ABAPEOPLESMST . ".govtidsocsec = '$govtidsocsec',
							 " . ABAPEOPLESMST . ".passportno = '$passportno',
							 " . ABAPEOPLESMST . ".passportissueddate = '$passportissueddate',
							 " . ABAPEOPLESMST . ".passportexpiry = '$passportexpiry',
							 " . ABAPEOPLESMST . ".passportissuancecountry = '$passportissuancecountry',
							 " . ABAPEOPLESMST . ".abaini = '$abaini', ";
					break;
				case 'contactinfo':
					$columns = "" . ABAPEOPLESMST . ".emailaddress = '$eadd', 
							 " . ABAPEOPLESMST . ".mobileno = '$mobphone', 
							 " . ABAPEOPLESMST . ".homephoneno = '$homephone', 
							 " . ABAPEOPLESMST . ".wechat = '$wechat', 
							 " . ABAPEOPLESMST . ".skype = '$skype', 
							 " . ABAPEOPLESMST . ".whatsapp = '$whatsapp', 
							 " . ABAPEOPLESMST . ".linkedin = '$linkedin', 
							 " . ABAPEOPLESMST . ".presentaddress = '$presentaddr', 
							 " . ABAPEOPLESMST . ".presentcity = '$presentcity', 
							 " . ABAPEOPLESMST . ".presentstate = '$presentstate',  
							 " . ABAPEOPLESMST . ".presentzipcode = '$presentzipcode',  
							 " . ABAPEOPLESMST . ".presentcountry = '$presentcountry', 
							 " . ABAPEOPLESMST . ".emercontactperson = '$emercontactperson',  
							 " . ABAPEOPLESMST . ".emercontactno = '$emercontactno', 
							 " . ABAPEOPLESMST . ".emercontactrelation = '$emercontactrelation', ";
					break;
				case 'employeedata':
					$columns ="" . ABAPEOPLESMST . ".joineddate = '$joineddate', 
						 " . ABAPEOPLESMST . ".office = '$ofc', 
						 " . ABAPEOPLESMST . ".designation = '$position', 
						 " . ABAPEOPLESMST . ".department = '$dept', 
						 " . ABAPEOPLESMST . ".eecategory = '$eecat', 
						 " . ABAPEOPLESMST . ".status = '$emp_status', 
						 " . ABAPEOPLESMST . ".positiongrade = '$posgrade', 
						 " . ABAPEOPLESMST . ".reportstoid = '$reportsto', 
						 " . ABAPEOPLESMST . ".reportstoindirectid = '$reportstoindirect', 
						 " . ABAPEOPLESMST . ".reportsto = '$reportstotext', 
						 " . ABAPEOPLESMST . ".reportstoindirect = '$reportstoindirecttext', 
						 " . ABAPEOPLESMST . ".workemail = '$workeadd', 
						 " . ABAPEOPLESMST . ".officephoneno = '$ofcno', 
						 " . ABAPEOPLESMST . ".workskype = '$workskype',
						 " . ABAPEOPLESMST . ".probationperiodmonth = '$probationperiod',
						 " . ABAPEOPLESMST . ".terminationperiodnoticemonth = '$terminationperiod',
						 " . ABAPEOPLESMST . ".workingvisabyabacare = '$visaprocessedbyaba',
						 " . ABAPEOPLESMST . ".workingvisaexpirationdate = '$visaexpireddate',
						 " . ABAPEOPLESMST . ".probationcompletiondate = '$probationcompletiondate',
						 " . ABAPEOPLESMST . ".lastworkingday = '$lastworkingday',
						 " . ABAPEOPLESMST . ".effectivedate = '$effectivedate',
						 " . ABAPEOPLESMST . ".companynameof1stcontractsigned = '$companynameof1stcontractsigned',
						 " . ABAPEOPLESMST . ".dateofmostrecentcontracteffective = '$dateofmostrecentcontracteffective',
						 " . ABAPEOPLESMST . ".actualplaceofcurrentwork = '$actualplaceofcurrentwork', 
						 " . ABAPEOPLESMST . ".probationenddate = '$probationenddate', 
						 " . ABAPEOPLESMST . ".regularizationdate = '$regularizationdate', 
						 " . ABAPEOPLESMST . ".workingtypeofvisa = '$typeofvisa', 
						 " . ABAPEOPLESMST . ".workingstartofvisa = '$startofvisa', 
						 " . ABAPEOPLESMST . ".contractstartdate = '$startcontractdate', 
						 " . ABAPEOPLESMST . ".contractenddate = '$endcontractdate', 
						 " . ABAPEOPLESMST . ".startshift = '$startshift', 
						 " . ABAPEOPLESMST . ".endshift = '$endshift', ";
					break;
				case "compensationandbenefits":
					$columns ="" . ABAPEOPLESMST . ".monthlygrosssalaryinlocalcurrencyshownincontract = '$monthlygrosssalaryinlocalcurrencyshownincontract',
						 " . ABAPEOPLESMST . ".monthlyemployerscontributionmpfmfpsss = '$monthlyemployerscontributionmpfmfpsss',
						 " . ABAPEOPLESMST . ".monthlyaplusmedicalinsuranceinhkd = '$monthlyaplusmedicalinsuranceinhkd',
						 " . ABAPEOPLESMST . ".monthlymedicalinsuranceinlocal = '$monthlymedicalinsuranceinlocal',
						 " . ABAPEOPLESMST . ".monthlybusinessexpensesallowanceincontractinlocal = '$monthlybusinessexpensesallowanceincontractinlocal', ";
					break;
				case 'accountsettings':
					$columns ="" . ABAPEOPLESMST . ".zkid = '$zkteco_id', 
							" . ABAPEOPLESMST . ".zkdeviceid = '$zkteco_office',";
					break;
				default:
					break;
			}
			
			$sql = "UPDATE " . ABAPEOPLESMST . " 
					SET  $columns  
					" . ABAPEOPLESMST . ".modifiedby = '$userid' ,
					" . ABAPEOPLESMST . ".modifieddate = '$today' 
					WHERE " . ABAPEOPLESMST . ".userid = '$eeid' " ;
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateEmployeeInfo()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}


		function updateAvatarfile($data){
			$res['err'] = 0;
			$userid = $data['userid'];
			$filename = $data['filename'];
			$today = TODAY;
			$sql = "UPDATE " . ABAPEOPLESMST . " 
					SET  " . ABAPEOPLESMST . ".avatarorig = '$filename',
						 " . ABAPEOPLESMST . " .modifiedby = '$userid' ,
						 " . ABAPEOPLESMST . " .modifieddate = '$today' 
					 WHERE " . ABAPEOPLESMST . ".userid = '$userid' " ;
						 
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateAvatarfile()! " . $this->cn->error;
				goto exitme;
			}
			exitme:
			return $res;
		}

		public function getAllAbaPeopleOrgChart(){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			// $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
			$contactsinfo = "" . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno, 
							 " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,	
							 " . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
							 " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 

			$personaldata = "" . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".nationality, " . ABAPEOPLESMST . ".maritalstatus, 
							 " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender
							 ";

			$employeedata = "" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
							 " . ABAPEOPLESMST . ".department," . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
							 " . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid";

			$sql = "SELECT " . ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig," . ABAPEOPLESMST . ".abaini, $personaldata, $contactsinfo, $employeedata
						,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %y') AS birthdt
						,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %y') AS joindt
						,CONCAT(
							(CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
							,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
						,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname						
						,a.description as designationnamedesc
						,b.description as officename
						,c.description as nationalitydesc
						,d.dddescription as eetypedesc
						,e.dddescription as eecategorydesc
						,f.dddescription as maritalstat
						,g.abaini as reportstoini
					FROM " . ABAPEOPLESMST . 
					" LEFT JOIN " . DESIGNATIONSMST . " a
						ON a.designationid = " . ABAPEOPLESMST . ".designation 
					  LEFT JOIN " . SALESOFFICESMST . " b
					  	ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
					  LEFT JOIN " . NATIONALITYMST . " c
					  	ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
					  LEFT JOIN " . DROPDOWNSMST . " d
					  	ON d.ddid = " . ABAPEOPLESMST . ".eetype
					  	AND d.dddisplay = 'eetype' 
					  LEFT JOIN " . DROPDOWNSMST . " e
					  	ON e.ddid = " . ABAPEOPLESMST . ".eecategory
					  	AND e.dddisplay = 'eecategory' 
					  LEFT JOIN " . DROPDOWNSMST . " f
					  	ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
					  	AND f.dddisplay = 'maritalstatus' 
					  LEFT JOIN " . ABAPEOPLESMST . " g 
					  	ON g.userid = " . ABAPEOPLESMST . ".reportstoid AND " . ABAPEOPLESMST . ".reportstoid IS NOT NULL  
					  WHERE " . ABAPEOPLESMST . ".status = 1 AND " . ABAPEOPLESMST . ".contactcategory = 1 AND " . ABAPEOPLESMST . ".reportstoid IS NOT NULL AND " . ABAPEOPLESMST . ".eecategory != 1 
					  ORDER BY " . ABAPEOPLESMST . ".reportstoid " ;
			
			$qry = $this->cn->query($sql);
			// $res['sql'] = $sql;
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllAbaPeople()! " . $this->cn->error;
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

		function saveNewEmployee($data){
			$res['err'] = 0;
			$res['newuserid'] = "";
			$userid = $data['userid'];
			$contactcategory = 1;
			$salutation = $data['salutation'] == "" ? "" : addslashes($data['salutation']);
			$lastname = $data['lastname'] == "" ? "" : addslashes(strtoupper($data['lastname']));
			$firstname = $data['firstname'] == "" ? "" : addslashes(ucfirst($data['firstname']));
			$eename = $firstname . " " . $lastname;
			$cnname = $data['cnname'] == "" ? "" : addslashes($data['cnname']);
			$nationality = $data['nationality'] == "" ? "" : addslashes($data['nationality']);
			$maritalstatus = $data['maritalstatus'] == "" ? "" : addslashes($data['maritalstatus']);
			if($data['birthdate'] != ""){
				$birthdate = formatDate("Y-m-d",$data['birthdate']);
			}else{
				$birthdate = "1900-01-01 00:00:00";
			}
			$gender = $data['gender'] == "" ? "" : addslashes($data['gender']);
			$abaini = $data['abaini'] == "" ? "" : addslashes($data['abaini']); 
			$govtid = $data['govtid'] == "" ? "" : addslashes($data['govtid']);
			$passportno = $data['passportno'] == "" ? "" : addslashes($data['passportno']);
			if($data['issueddate'] != ""){
				$issueddate = formatDate("Y-m-d",$data['issueddate']);
			}else{
				$issueddate = "1900-01-01 00:00:00";
			}
			if($data['passportexpdate'] != ""){
				$passportexpdate = formatDate("Y-m-d",$data['passportexpdate']);
			}else{
				$passportexpdate = "1900-01-01 00:00:00";
			}
			$passissuedcountry = $data['passissuedcountry'] == "" ? "" : addslashes($data['passissuedcountry']);

			$eadd = $data['eadd'] == "" ? "" : addslashes($data['eadd']);
			$mobphone = $data['mobphone'] == "" ? "" : addslashes($data['mobphone']);
			$homephone = $data['homephone'] == "" ? "" : addslashes($data['homephone']);
			$wechat = $data['wechat'] == "" ? "" : addslashes($data['wechat']);
			$skype = $data['skype'] == "" ? "" : addslashes($data['skype']);
			$whatsapp = $data['whatsapp'] == "" ? "" : addslashes($data['whatsapp']);
			$linkedin = $data['linkedin'] == "" ? "" : addslashes($data['linkedin']);
			$presentcity = $data['presentcity'] == "" ? "" : addslashes($data['presentcity']);
			$presentstate = $data['presentstate'] == "" ? "" : addslashes($data['presentstate']);
			$presentcountry = $data['presentcountry'] == "" ? "" : addslashes($data['presentcountry']);
			$presentzipcode = $data['presentzipcode'] == "" ? "" : addslashes($data['presentzipcode']);
			$presentaddr = $data['presentaddr'] == "" ? "" : addslashes($data['presentaddr']);
			$emercontactperson = $data['emercontactperson'] == "" ? "" : addslashes($data['emercontactperson']);
			$emercontactno = $data['emercontactno'] == "" ? "" : addslashes($data['emercontactno']);
			$emercontactrelation = $data['emercontactrelation'] == "" ? "" : addslashes($data['emercontactrelation']);
			$startshift = $data['startshift'] == "" ? "" : addslashes($data['startshift']);
			$endshift = $data['endshift'] == "" ? "" : addslashes($data['endshift']);

			if($data['joineddate'] != ""){
				$joineddate = formatDate("Y-m-d",$data['joineddate']);
			}else{
				$joineddate = "1900-01-01 00:00:00";
			}
			$ofc = $data['ofc'] == "" ? "" : addslashes($data['ofc']);
			$position = $data['position'] == "" ? "" : addslashes($data['position']);
			$dept = $data['dept'] == "" ? "" : addslashes($data['dept']);
			$eecat = $data['eecat'] == "" ? "" : addslashes($data['eecat']);
			$posgrade = $data['posgrade'] == "" ? "" : addslashes($data['posgrade']);
			$reportsto = $data['reportsto'] == "" ? "" : addslashes($data['reportsto']);
			$reportstoindirect = $data['reportstoindirect'] == "" ? "" : addslashes($data['reportstoindirect']);
			$reportstotext = $data['reportstotext'] == "" ? "" : addslashes($data['reportstotext']);
			$reportstoindirecttext = $data['reportstoindirecttext'] == "" ? "" : addslashes($data['reportstoindirecttext']);
			$workeadd = $data['workeadd'] == "" ? "" : addslashes($data['workeadd']);
			$ofcno = $data['ofcno'] == "" ? "" : addslashes($data['ofcno']);
			$workskype = $data['workskype'] == "" ? "" : addslashes($data['workskype']);
			// $zkteco_office = $data['zkteco_office'] == "" ? "" : addslashes($data['zkteco_office']);
			// $zkteco_id = $data['zkteco_id'] == "" ? "" : addslashes($data['zkteco_id']);

			if($data['probationperiod'] != ""){
				$probationperiod = formatDate("Y-m-d",$data['probationperiod']);
			}else{
				$probationperiod = "1900-01-01 00:00:00";
			}
			if($data['terminationperiod'] != ""){
				$terminationperiod = formatDate("Y-m-d",$data['terminationperiod']);
			}else{
				$terminationperiod = "1900-01-01 00:00:00";
			}
			$visaprocessedbyaba = $data['visaprocessedbyaba'] == "" ? "" : addslashes($data['visaprocessedbyaba']);
			if($data['visaexpireddate'] != ""){
				$visaexpireddate = formatDate("Y-m-d",$data['visaexpireddate']);
			}else{
				$visaexpireddate = "1900-01-01 00:00:00";
			}
			if($data['probcompletedate'] != ""){
				$probcompletedate = formatDate("Y-m-d",$data['probcompletedate']);
			}else{
				$probcompletedate = "1900-01-01 00:00:00";
			}
			if($data['lastworkingdate'] != ""){
				$lastworkingdate = formatDate("Y-m-d",$data['lastworkingdate']);
			}else{
				$lastworkingdate = "1900-01-01 00:00:00";
			}
			if($data['effectivedate'] != ""){
				$effectivedate = formatDate("Y-m-d",$data['effectivedate']);
			}else{
				$effectivedate = "1900-01-01 00:00:00";
			}

			$companynamefirstctrcsigned = $data['companynamefirstctrcsigned'] == "" ? "" : addslashes($data['companynamefirstctrcsigned']);

			if($data['recenteffectivedatectrc'] != ""){
				$recenteffectivedatectrc = formatDate("Y-m-d",$data['recenteffectivedatectrc']);
			}else{
				$recenteffectivedatectrc = "1900-01-01 00:00:00";
			}
			$curplaceofwork = $data['curplaceofwork'] == "" ? "" : addslashes($data['curplaceofwork']);

			//new added fields
			if($data['regularizationdate'] != ""){
				$regularizationdate = formatDate("Y-m-d",$data['regularizationdate']);
			}else{
				$regularizationdate = "1900-01-01 00:00:00";
			}

			$typeofvisa = $data['typeofvisa'] == "" ? "" : addslashes($data['typeofvisa']);

			if($data['startofvisa'] != ""){
				$startofvisa = formatDate("Y-m-d",$data['startofvisa']);
			}else{
				$startofvisa = "1900-01-01 00:00:00";
			}

			if($data['probationenddate'] != ""){
				$probationenddate = formatDate("Y-m-d",$data['probationenddate']);
			}else{
				$probationenddate = "1900-01-01 00:00:00";
			}

			if($data['startcontractdate'] != ""){
				$startcontractdate = formatDate("Y-m-d",$data['startcontractdate']);
			}else{
				$startcontractdate = "1900-01-01 00:00:00";
			}

			if($data['endcontractdate'] != ""){
				$endcontractdate = formatDate("Y-m-d",$data['endcontractdate']);
			}else{
				$endcontractdate = "1900-01-01 00:00:00";
			}

			$today = TODAY;
			$jdt = date("ymd",strtotime($joineddate));
			$newuserid = $this->genUserID($jdt);
			$sesid = genuri($newuserid);

			$sql = "INSERT INTO " . ABAPEOPLESMST. "
						(userid, contactcategory, salutation, fname, lname, cnname, nationality, maritalstatus, birthdate, gender, abaini, govtidsocsec, passportno, passportissueddate, passportexpiry, passportissuancecountry,
						 emailaddress, mobileno, homephoneno, wechat, skype, whatsapp, linkedin, presentcity, presentstate, presentcountry, presentzipcode, presentaddress, emercontactperson, emercontactno, emercontactrelation,
						 joineddate, office, designation, department, eecategory, positiongrade, reportstoid, reportstoindirectid, reportsto, reportstoindirect, workemail, officephoneno, workskype, 
						 probationperiodmonth, terminationperiodnoticemonth, workingvisabyabacare,  workingvisaexpirationdate, probationcompletiondate, lastworkingday, effectivedate,
						 companynameof1stcontractsigned, dateofmostrecentcontracteffective, actualplaceofcurrentwork,
						 regularizationdate, workingtypeofvisa, workingstartofvisa, probationenddate, contractstartdate, contractenddate,
						 sesid, createdby, createddate, startshift, endshift)
					VALUES 
						('$newuserid', '$contactcategory', '$salutation', '$firstname', '$lastname', '$cnname', '$nationality', '$maritalstatus', '$birthdate', '$gender', '$abaini', '$govtid', '$passportno', '$issueddate','$passportexpdate', '$passissuedcountry',
						 '$eadd', '$mobphone', '$homephone', '$wechat', '$skype', '$whatsapp', '$linkedin', '$presentcity', '$presentstate', '$presentcountry', '$presentzipcode', '$presentaddr','$emercontactperson', '$emercontactno', '$emercontactrelation',
						 '$joineddate', '$ofc', '$position', '$dept', '$eecat', '$posgrade', '$reportsto', '$reportstoindirect', '$reportstotext','$reportstoindirecttext','$workeadd','$ofcno','$workskype',
						 '$probationperiod', '$terminationperiod', '$visaprocessedbyaba', '$visaexpireddate', '$probcompletedate', '$lastworkingdate', '$effectivedate', 
						 '$companynamefirstctrcsigned', '$recenteffectivedatectrc', '$curplaceofwork',
						 '$regularizationdate', '$typeofvisa', '$startofvisa', '$probationenddate', '$startcontractdate', '$endcontractdate',
						 '$sesid', '$userid', '$today', '$startshift', '$endshift')";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveNewEmployee()! " . $this->cn->error;
				goto exitme;
			}else{
				$res['newuserid'] = $newuserid;
			}

			$id = $this->cn->insert_id;
			$this->setInsertId($id);

			if(!empty($abaini) || $abaini != ""){
				$sql2 = "INSERT INTO " . ABBREVIATIONSMST . " (type,abvt,word,cnword,description,refid,category,createdby,createddate) 
						VALUES('1','$abaini','$eename','$cnname','$position','$id','2','$userid','$today')";
				// echo $sql2 . '<br />';
				$qry2 = $this->cn->query($sql2);
				if(!$qry2){
					$res['err'] = 1;
					$res['errmsg'] = "An error occured in func saving abvt! " . $this->cn->error;
					exit();
				}
				
				$pass = generatePassword("1234");
				$sql3 = "INSERT INTO " . ABAUSER . " (userid,username,password,abaini,fname,lname,accesslevel,createdby,createddate) 
						VALUES('$newuserid','$abaini','$pass','$abaini','$firstname','$lastname','3','$userid','$today')";
				// echo $sql2 . '<br />';
				$qry3 = $this->cn->query($sql3);
				if(!$qry3){
					$res['err'] = 1;
					$res['errmsg'] = "An error occured in func saving user! " . $this->cn->error;
					exit();
				}
			}

			exitme:
			return $res;
		}

		public function updateAccountSettings($data){
			$res['err'] = 0;
			$userid = $data['userid'];
			$zkteco_office = $data['zkteco_office'] == "" ? "" : addslashes($data['zkteco_office']);
			$zkteco_id = $data['zkteco_id'] == "" ? "(NULL)" : addslashes($data['zkteco_id']);
			$today=TODAY;
			$monnthlygrosssalinlocalcurshowninctrc = $data['monnthlygrosssalinlocalcurshowninctrc'] == "" ? 0 : addslashes($data['monnthlygrosssalinlocalcurshowninctrc']);
			$monthlempcontri = $data['monthlempcontri'] == "" ? 0 : addslashes($data['monthlempcontri']);
			$monthaplusmedinhkd = $data['monthaplusmedinhkd'] == "" ? 0 : addslashes($data['monthaplusmedinhkd']);
			$monthlymedinsinlocal = $data['monthlymedinsinlocal'] == "" ? 0 : addslashes($data['monthlymedinsinlocal']);
			$monthlybusinessexpensesallowan = $data['monthlybusinessexpensesallowan'] == "" ? 0 : addslashes($data['monthlybusinessexpensesallowan']);

			$columns ="" . ABAPEOPLESMST . ".zkid = '$zkteco_id', 
					   " . ABAPEOPLESMST . ".zkdeviceid = '$zkteco_office',
					   " . ABAPEOPLESMST . ".monthlygrosssalaryinlocalcurrencyshownincontract = '$monnthlygrosssalinlocalcurshowninctrc',
					   " . ABAPEOPLESMST . ".monthlyemployerscontributionmpfmfpsss = '$monthlempcontri',
					   " . ABAPEOPLESMST . ".monthlyaplusmedicalinsuranceinhkd = '$monthaplusmedinhkd',
					   " . ABAPEOPLESMST . ".monthlymedicalinsuranceinlocal = '$monthlymedinsinlocal',
					   " . ABAPEOPLESMST . ".monthlybusinessexpensesallowanceincontractinlocal = '$monthlybusinessexpensesallowan',
					   " . ABAPEOPLESMST . " .modifiedby = '$userid',
					   " . ABAPEOPLESMST . " .modifieddate = '$today'";
			$sql = "UPDATE " . ABAPEOPLESMST . " 
								SET  $columns WHERE " . ABAPEOPLESMST . ".userid = '$userid' " ;
						// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
						
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateEmployeeInfo()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		public function getAllAbaPeopleByOffice($data){
			$ofcwhere = '';

			$officelist = explode(',',$data['office']);
			if(count($officelist) > 0) {
				$ofcwhere .= " AND " . ABAPEOPLESMST . ".office IN (";
				foreach($officelist as $eachofc) {
					$ofcwhere .= "'$eachofc',";
				}
				$ofcwhere = substr($ofcwhere, 0, -1);
				$ofcwhere .= ") ";
			}
            // $ofcid = $data['ofcid'];
			// $ofcname = $data['ofcname'];
			// $ofcwhere = "";
			// if($ofcname != '' && $ofcid != ''){
			// 	$ofcwhere = "AND (" . ABAPEOPLESMST . ".webhr_station = '$ofcname' OR " . ABAPEOPLESMST . ".office = '$ofcid')";
			// }
            $res = array();
            $rows = array();
            $res['err'] = 0;
            // $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
            $contactsinfo = "" . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno, 
                             " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,    
                             " . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
                             " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 

            $personaldata = "" . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".nationality, " . ABAPEOPLESMST . ".maritalstatus, 
                             " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender
                             ";

            $employeedata = "" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation,
            				 " . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
                             " . ABAPEOPLESMST . ".department," . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
                             " . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid";

            $sql = "SELECT " . ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig," . ABAPEOPLESMST . ".abaini, $personaldata, $contactsinfo, $employeedata
                        ,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %Y') AS birthdt
                        ,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %Y') AS joindt
                        ,CONCAT(
                            (CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
                            ,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
                            ,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
                        ,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname                        
                        ,a.description as designationnamedesc
                        ,b.description as officename
                        ,c.description as nationalitydesc
                        ,d.dddescription as eetypedesc
                        ,e.dddescription as eecategorydesc
                        ,f.dddescription as maritalstat
                    FROM " . ABAPEOPLESMST. 
                    " LEFT JOIN " . DESIGNATIONSMST . " a
                        ON a.designationid = " . ABAPEOPLESMST . ".designation 
                      LEFT JOIN " . SALESOFFICESMST . " b
                          ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
                      LEFT JOIN " . NATIONALITYMST . " c
                          ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
                      LEFT JOIN " . DROPDOWNSMST . " d
                          ON d.ddid = " . ABAPEOPLESMST . ".eetype
                          AND d.dddisplay = 'eetype' 
                      LEFT JOIN " . DROPDOWNSMST . " e
                          ON e.ddid = " . ABAPEOPLESMST . ".eecategory
                          AND e.dddisplay = 'eecategory' 
                      LEFT JOIN " . DROPDOWNSMST . " f
                          ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
                          AND f.dddisplay = 'maritalstatus' 
                      WHERE " . ABAPEOPLESMST . ".status = 1 
                          AND " . ABAPEOPLESMST . ".contactcategory = 1 
						  $ofcwhere
                      ORDER BY " . ABAPEOPLESMST . ".abaini " ;
            // $res['sql'] = $sql;
            $qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func getAllAbaPeople()! " . $this->cn->error;
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

        public function getAllAbaPeopleByTL($userid){
            $res = array();
            $res['err'] = 0;
            // $where .= " AND " . ABAPEOPLESMST . ".userid = '$id'";
            $contactsinfo = "" . ABAPEOPLESMST . ".emailaddress, " . ABAPEOPLESMST . ".mobileno, " . ABAPEOPLESMST . ".homephoneno, 
                             " . ABAPEOPLESMST . ".wechat, " . ABAPEOPLESMST . ".skype, " . ABAPEOPLESMST . ".whatsapp, " . ABAPEOPLESMST . ".linkedin,    
                             " . ABAPEOPLESMST . ".presentaddress, " . ABAPEOPLESMST . ".presentcity, " . ABAPEOPLESMST . ".presentstate, " . ABAPEOPLESMST . ".presentzipcode, 
                             " . ABAPEOPLESMST . ".presentcountry, " . ABAPEOPLESMST . ".emercontactperson, " . ABAPEOPLESMST . ".emercontactno, " . ABAPEOPLESMST . ".emercontactrelation"; 

            $personaldata = "" . ABAPEOPLESMST . ".fname, " . ABAPEOPLESMST . ".mname, " . ABAPEOPLESMST . ".lname, " . ABAPEOPLESMST . ".nationality, " . ABAPEOPLESMST . ".maritalstatus, 
                             " . ABAPEOPLESMST . ".birthdate, " . ABAPEOPLESMST . ".gender
                             ";

            $employeedata = "" . ABAPEOPLESMST . ".joineddate," . ABAPEOPLESMST . ".office, " . ABAPEOPLESMST . ".webhr_designation," . ABAPEOPLESMST . ".designation, " . ABAPEOPLESMST . ".departmentname, 
                             " . ABAPEOPLESMST . ".department," . ABAPEOPLESMST . ".eetype, " . ABAPEOPLESMST . ".eecategory," . ABAPEOPLESMST . ".reportsto," . ABAPEOPLESMST . ".webhr_station,
                             " . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".reportstoindirect," . ABAPEOPLESMST . ".positiongrade, " . ABAPEOPLESMST . ".workemail," . ABAPEOPLESMST . ".officephoneno," . ABAPEOPLESMST . ".workskype," . ABAPEOPLESMST . ".reportstoid," . ABAPEOPLESMST . ".reportstoindirectid";

            $sql = "SELECT " . ABAPEOPLESMST . ".userid, " . ABAPEOPLESMST . ".sesid, " . ABAPEOPLESMST . ".avatarorig," . ABAPEOPLESMST . ".abaini, $personaldata, $contactsinfo, $employeedata
                        ,DATE_FORMAT(" . ABAPEOPLESMST . ".birthdate, '%a %d %b %Y') AS birthdt
                        ,DATE_FORMAT(" . ABAPEOPLESMST . ".joineddate, '%a %d %b %Y') AS joindt
                        ,CONCAT(
                            (CASE WHEN " . ABAPEOPLESMST . ".fname != '' THEN " . ABAPEOPLESMST . ".fname ELSE '' END),' '
                            ,(CASE WHEN " . ABAPEOPLESMST . ".mname != '' THEN " . ABAPEOPLESMST . ".mname ELSE '' END),' '
                            ,(CASE WHEN " . ABAPEOPLESMST . ".lname != '' THEN " . ABAPEOPLESMST . ".lname ELSE '' END)) as eename
                        ,(CASE WHEN " . ABAPEOPLESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname                        
                        ,a.description as designationnamedesc
                        ,b.description as officename
                        ,c.description as nationalitydesc
                        ,d.dddescription as eetypedesc
                        ,e.dddescription as eecategorydesc
                        ,f.dddescription as maritalstat
                    FROM " . ABAPEOPLESMST. 
                    " LEFT JOIN " . DESIGNATIONSMST . " a
                        ON a.designationid = " . ABAPEOPLESMST . ".designation 
                      LEFT JOIN " . SALESOFFICESMST . " b
                          ON b.salesofficeid = " . ABAPEOPLESMST . ".office 
                      LEFT JOIN " . NATIONALITYMST . " c
                          ON c.nationalityid = " . ABAPEOPLESMST . ".nationality 
                      LEFT JOIN " . DROPDOWNSMST . " d
                          ON d.ddid = " . ABAPEOPLESMST . ".eetype
                          AND d.dddisplay = 'eetype' 
                      LEFT JOIN " . DROPDOWNSMST . " e
                          ON e.ddid = " . ABAPEOPLESMST . ".eecategory
                          AND e.dddisplay = 'eecategory' 
                      LEFT JOIN " . DROPDOWNSMST . " f
                          ON f.ddid = " . ABAPEOPLESMST . ".maritalstatus
                          AND f.dddisplay = 'maritalstatus' 
                      WHERE " . ABAPEOPLESMST . ".status = 1 
                          AND " . ABAPEOPLESMST . ".contactcategory = 1 
                          AND (" . ABAPEOPLESMST . ".reportstoid = '$userid' OR " . ABAPEOPLESMST . ".userid = '$userid') 
                      ORDER BY " . ABAPEOPLESMST . ".abaini " ;
            
            $qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func getAllAbaPeopleByTL()! " . $this->cn->error;
                goto exitme;
            }
            $rows = array();
            while($row = $qry->fetch_array(MYSQLI_ASSOC)){
                $rows[] = $row;
            }

            $res['rows'] = $rows;

            exitme:
            // $this->cn->close();
            return $res;
		}
		
		function getEeHead($userid){

			$sql = "SELECT 
				 b.`abaini` AS directini
				,b.`userid` AS directuserid
				,c.`abaini` AS indirectini 
				,c.`userid` AS indirectuserid 
				FROM " . ABAPEOPLESMST . " a 
				LEFT JOIN " . ABAPEOPLESMST . " b 
				ON b.`userid` = a.`reportstoid` 
				LEFT JOIN " . ABAPEOPLESMST . " c 
				ON c.`userid` = a.`reportstoindirectid` 
				WHERE a.`userid` = '$userid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()!" . $this->cn->error;
                goto exitme;
            }
            $rows = array();
            while($row = $qry->fetch_array(MYSQLI_ASSOC)){
                $rows[] = $row;
            }

			$res['rows'] = $rows;

            exitme:
            // $this->cn->close();
            return $res;
		}

		function checkini($abaini){
			$res = array();
			$sql = "SELECT COUNT(1) AS isExist 
					FROM " . ABAPEOPLESMST . " 
					WHERE " . ABAPEOPLESMST . ".`abaini` = '$abaini' AND " . ABAPEOPLESMST . ".`status` = 1 
					LIMIT 1";
					
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()!" . $this->cn->error;
            }
			return $qry->fetch_array(MYSQLI_ASSOC);
		}

		function getManagers(){
			$sql = "SELECT a.`userid`
						,CONCAT(a.`fname`, ' ', a.`lname`) AS fullname
						,a.`abaini`
						,a.`office` 
					FROM " . ABAPEOPLESMST . " a
					WHERE a.`contactcategory` = 1 AND a.`positiongrade` < 5 AND a.`status` = 1 
					ORDER BY (SELECT fullname) ASC";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()!" . $this->cn->error;
                goto exitme;
            }
            $rows = array();
            while($row = $qry->fetch_array(MYSQLI_ASSOC)){
                $rows[] = $row;
            }

			$res['rows'] = $rows;

            exitme:
            // $this->cn->close();
            return $res;
		}

		function getIndirect($direct) {
			$sql = "SELECT DISTINCT b.`userid`
						,CONCAT(b.`fname`, ' ', b.`lname`) AS fullname
						,b.`abaini`
						,a.`office` 
					FROM " . ABAPEOPLESMST . " a
					RIGHT JOIN " . ABAPEOPLESMST . " b
						ON b.`userid` = a.`reportstoindirectid`
					WHERE a.`status` = 1 AND a.`contactcategory` = 1 AND a.`reportstoid` = '$direct' 
					ORDER BY (SELECT fullname) ASC";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()!" . $this->cn->error;
                goto exitme;
            }
            $rows = array();
            while($row = $qry->fetch_array(MYSQLI_ASSOC)){
                $rows[] = $row;
            }

			$res['rows'] = $rows;

            exitme:
            // $this->cn->close();
            return $res;
		}

		function getHierarchy($directid){
			$sql = "SELECT a.`userid`, a.`abaini` 
					FROM " . ABAPEOPLESMST . " a
					WHERE a.`status` > -1 AND a.`contactcategory` = 1 AND a.`reportstoid` = '$directid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
            if(!$qry){
                // $res['err'] = 1;
                // $res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()!" . $this->cn->error;
                goto exitme;
			}
			
            $rows = array();
            while($row = $qry->fetch_array(MYSQLI_ASSOC)){
                $rows[]['userid'] = $row['userid'];
            }

			$res = $rows;

            exitme:
            // $this->cn->close();
            return $res;
		}

		function getEEnUserid($data = '') {
			$office = '';
			$department = '';
			$position = '';
			$eestatus = '';
			$direct = '';
			$indirect = '';
			if($data != ''){
				// $office = $data['ofc_access'];
				$department = $data['department'];
				$position = $data['position'];
				$eestatus = $data['eestatus'];
				$direct = $data['direct'];
				$indirect = $data['indirect'];
			}

			$where = '';
			
			// if($office != '') 		$where .= " AND a.office = '$office'";

			// if(count($office) > 0) {
			// 	$where .= " AND (";
			// 	foreach ($office as $key => $officeid) {
			// 		$where .= " a.`office` = '$officeid'";
			// 		if($key < count($filters->officeid)-1) $where .= " OR";
			// 	}
			// 	$where .= ")";
			// }

			if($department != '') 	$where .= " AND a.department = '$department'";
			if($position != '') 	$where .= " AND a.designation = '$position'";
			if($eestatus != '') 	$where .= " AND a.status = $eestatus";
			if($direct != '') 		$where .= " AND a.reportstoid = '$direct'";
			if($indirect != '') 	$where .= " AND a.reportstoindirectid = '$indirect'";

			$sql = "SELECT a.userid
						  ,CONCAT(a.fname,' ',a.lname) AS fullname
						  ,a.office
						  ,a.department
						  ,a.designation
						  ,a.status
						  ,a.reportstoid
						  ,a.reportstoindirectid 
					FROM " . ABAPEOPLESMST . " a 
					WHERE a.contactcategory = 1 AND a.status > -1 $where 
					ORDER BY (SELECT fullname) ASC";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
            if(!$qry){
                $res['err'] = 1;
                $res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()!" . $this->cn->error;
                goto exitme;
            }
            $rows = array();
            while($row = $qry->fetch_array(MYSQLI_ASSOC)){
                $rows[] = $row;
            }

			$res['rows'] = $rows;

            exitme:
            // $this->cn->close();
            return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>