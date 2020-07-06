<?php
	class HolidayModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getHolidays($data){
			$res = array();
			$res['err'] = 0;
			$currentyear = $data['currentyear'];
			
			$sql = "SELECT 
						". HOLIDAYSMST . ".* 
						,DATE_FORMAT(". HOLIDAYSMST . ".`startdate`, '%a %d %b %y') AS datestart
						,DATE_FORMAT(". HOLIDAYSMST . ".`enddate`, '%a %d %b %y') AS dateend
						,a.description AS officename
					FROM ". HOLIDAYSMST . " 
					LEFT JOIN " . SALESOFFICESMST . " a
						ON a.salesofficeid = ". HOLIDAYSMST . ".office 
					WHERE YEAR(" . HOLIDAYSMST . ".holidaydate) = '" . $currentyear . "' 
					AND " . HOLIDAYSMST . ".status = 1 
					AND " . HOLIDAYSMST . ".relationid = 0 
					OR YEAR(" . HOLIDAYSMST . ".holidaydate) = '" . ($currentyear + 1) . "' 
					AND " . HOLIDAYSMST . ".status = 1 
					AND " . HOLIDAYSMST . ".relationid = 0";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getHolidays(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function updateHolidays($data){
			$res = array();
			$res['err'] = 0;

			$title = $data['title'] == "" ? "" : addslashes($data['title']);
			$ofc = $data['ofc'];

			if($data['startdate'] != ""){
				$startdate = formatDate("Y-m-d",$data['startdate']);
			}else{
				$startdate = "1900-01-01 00:00:00";
			}

			if($data['enddate'] != ""){
				$enddate = formatDate("Y-m-d",$data['enddate']);
			}else{
				$enddate = "1900-01-01 00:00:00";
			}

			$region = $data['region'];
			$desc = $data['desc'] == "" ? "" : addslashes($data['desc']);

			$id = $data['id'];
			$userid = $data['userid'];
			$createdby = $data['createdby'];

			if($data['createddate'] != ""){
				$createddate = formatDate("Y-m-d",$data['createddate']);
			}else{
				$createddate = "1900-01-01 00:00:00";
			}

			$days_count = $data['days_count'];
			
			if($data['modifieddate'] != ""){
				$modifieddate = formatDate("Y-m-d",$data['modifieddate']);
			}else{
				$modifieddate = "1900-01-01 00:00:00";
			}




			//Insertion
			$dates=array();
			for($i=0;$i<$days_count+1;$i++) {
				$NewDate=date('Y-m-d', strtotime($startdate."+".$i." days"));
				$dates[]=$NewDate;
			}
			$res['arrDt'] = $dates;

			$relationid = 0;
			foreach($dates as $dt){
				$dateToCode = formatDate('Ymd',$dt);
				$sql = "INSERT INTO 
					" . HOLIDAYSMST . " (relationid,holidaycode,holidaydate,office,title,startdate,enddate,description,region,createdby,createddate,modifiedby,modifieddate) 
								VALUES('$relationid','$dateToCode','$dt','$ofc','$title','$startdate','$enddate','$desc','$region','$createdby','$createddate','$userid','$modifieddate')";
				$qry = $this->cn->query($sql);
				if($relationid == 0){
					$relationid = mysqli_insert_id($this->cn);
				}
				if(!$qry){
					$res['err'] = 1;
					$res['errmsg'] =  'addHolidays ' . $this->cn->error;
					// exit();
				}
			}

			$columns ="" . HOLIDAYSMST . ".modifiedby = '$userid', 
					" . HOLIDAYSMST . ".modifieddate = '$modifieddate',
					" . HOLIDAYSMST . ".modifiedto = '$relationid',
					" . HOLIDAYSMST . ".status = 0";
			$sql = "UPDATE " . HOLIDAYSMST . "
						SET $columns 
						WHERE id = '$id' OR relationid = '$id'";
			$qry = $this->cn->query($sql);
			// $res['sql'] = $sql;
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateEmployeeInfo()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}


		function addHolidays($data){
			$res = array();
			$res['err'] = 0;
			$title = $data['title'] == "" ? "" : addslashes($data['title']);
			$ofc = $data['ofc'];
			$desc = $data['desc'] == "" ? "" : addslashes($data['desc']);
			$region = $data['region'];
			$days_count = (int)$data['days_count'];
			$createdby = $data['userid'];

			if($data['startdate'] != ""){
				$startdate = formatDate("Y-m-d",$data['startdate']);
			}else{
				$startdate = "1900-01-01 00:00:00";
			}

			if($data['enddate'] != ""){
				$enddate = formatDate("Y-m-d",$data['enddate']);
			}else{
				$enddate = "1900-01-01 00:00:00";
			}

			if($data['createddate'] != ""){
				$createddate = formatDate("Y-m-d",$data['createddate']);
			}else{
				$createddate = "1900-01-01 00:00:00";
			}

			// $today = date('Y-m-d H:i:s',date('Y-m-d'));  //formatDate("Y-m-d",$data['leavefrom']);
			
			$dates=array($startdate);
			for($i=1;$i<$days_count+1;$i++) {
				$NewDate=date('Y-m-d', strtotime($startdate."+".$i." days"));
				$dates[]=$NewDate;
			}
			
			$relationid = 0;
			foreach($dates as $dt){
				$dateToCode = formatDate('Ymd',$dt);
				$sql = "INSERT INTO " . HOLIDAYSMST . " (relationid,holidaycode,holidaydate,office,title,startdate,enddate,description,region,createdby,createddate) 
												   VALUES('$relationid','$dateToCode','$dt','$ofc','$title','$startdate','$enddate','$desc','$region','$createdby','$createddate')";
				$qry = $this->cn->query($sql);
				if($relationid == 0){
					$relationid = mysqli_insert_id($this->cn);
				}
				if(!$qry){
					$res['err'] = 1;
					$res['errmsg'] =  'addHolidays ' . $this->cn->error;
					// exit();
				}
			}
			
			return $res;
		}

		function deleteHolidays($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['id'];
			$userid = $data['userid'];
			$modifieddate = TODAY;



			$columns ="" . HOLIDAYSMST . ".modifiedby = '$userid', 
					" . HOLIDAYSMST . ".modifieddate = '$modifieddate',
					" . HOLIDAYSMST . ".status = 0";
			$sql = "UPDATE " . HOLIDAYSMST . "
						SET $columns 
						WHERE id = '$id' OR relationid = '$id'";
			$qry = $this->cn->query($sql);
			
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func deleteHolidays()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}


		function publishHoliday($data){

			
			// title: title,
			// ofc: ofc,
			// startdate: startdate,
			// enddate: enddate,
			// desc: desc,
			// createdby: createdby,
			// createddate: createddate

			$res = array();
			$res['err_announcement_insert'] = 0;
			$res['err_holiday_published'] = 0;
			$title = $data['title'] == "" ? "" : addslashes($data['title']);
			$ofc = $data['ofc'];
			$desc = $data['desc'] == "" ? "" : addslashes($data['desc']);
			$createdby = $data['createdby'];
			$holidayid = $data['holidayid'];

			if($data['startdate'] != ""){
				$startdate = formatDate("Y-m-d",$data['startdate']);
			}else{
				$startdate = "1900-01-01 00:00:00";
			}

			if($data['enddate'] != ""){
				$enddate = formatDate("Y-m-d",$data['enddate']);
			}else{
				$enddate = "1900-01-01 00:00:00";
			}

			$createddate = TODAY;

			
			
			$dateToCode = formatDate('Ymd',$createddate);
			$sql = "INSERT INTO " . ANNOUNCEMENTMST . " (announcementcode,announcementdate,office,title,startdate,enddate,description,createdby,createddate,announcementtype) 
												VALUES('$dateToCode','$startdate','$ofc','$title','$startdate','$enddate','$desc','$createdby','$createddate','holiday')";
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err_announcement_insert'] = 1;
				$res['errmsg_announcement_insert'] =  __FUNCTION__ . '(): ' . $this->cn->error;
				// exit();
			}

			$columns ="" . HOLIDAYSMST . ".modifiedby = '$createdby', 
					" . HOLIDAYSMST . ".modifieddate = '$createddate',
					" . HOLIDAYSMST . ".published = 1";
			$sql = "UPDATE " . HOLIDAYSMST . "
						SET $columns 
						WHERE id = '$holidayid' OR relationid = '$holidayid'";
			$qry = $this->cn->query($sql);
			
			if(!$qry){
				$res['err_holiday_published'] = 1;
				$res['errmsg_holiday_published'] =  __FUNCTION__ . '(): ' . $this->cn->error;
			}
			
			// exitme:
			return $res;
		}


		public function closeDB(){
			$this->cn->close();
		}
	}
?>