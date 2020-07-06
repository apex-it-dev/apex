<?php
	class WorkingdayModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getWorkingdays($data){
			$res = array();
			$res['err'] = 0;
			$currentyear = $data['currentyear'];
			
			$sql = "SELECT 
						". WORKINGDAYMST . ".* 
						,DATE_FORMAT(". WORKINGDAYMST . ".`startdate`, '%a %d %b %y') AS datestart
						,DATE_FORMAT(". WORKINGDAYMST . ".`enddate`, '%a %d %b %y') AS dateend
						,a.description AS officename
					FROM ". WORKINGDAYMST . " 
					LEFT JOIN " . SALESOFFICESMST . " a
						ON a.salesofficeid = ". WORKINGDAYMST . ".office 
					WHERE YEAR(" . WORKINGDAYMST . ".workingdaydate) = '" . $currentyear . "' 
					AND " . WORKINGDAYMST . ".status = 1 
					AND " . WORKINGDAYMST . ".relationid = 0 
					OR YEAR(" . WORKINGDAYMST . ".workingdaydate) = '" . ($currentyear + 1) . "' 
					AND " . WORKINGDAYMST . ".status = 1 
					AND " . WORKINGDAYMST . ".relationid = 0";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getWorkingdays(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function updateWorkingdays($data){
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
					" . WORKINGDAYMST . " (relationid,workingdaycode,workingdaydate,office,title,startdate,enddate,description,region,createdby,createddate,modifiedby,modifieddate) 
								VALUES('$relationid','$dateToCode','$dt','$ofc','$title','$startdate','$enddate','$desc','$region','$createdby','$createddate','$userid','$modifieddate')";
				$qry = $this->cn->query($sql);
				if($relationid == 0){
					$relationid = mysqli_insert_id($this->cn);
				}
				if(!$qry){
					$res['err'] = 1;
					$res['errmsg'] =  'addWorkingdays ' . $this->cn->error;
					// exit();
				}
			}

			$columns ="" . WORKINGDAYMST . ".modifiedby = '$userid', 
					" . WORKINGDAYMST . ".modifieddate = '$modifieddate',
					" . WORKINGDAYMST . ".modifiedto = '$relationid',
					" . WORKINGDAYMST . ".status = 0";
			$sql = "UPDATE " . WORKINGDAYMST . "
						SET $columns 
						WHERE id = '$id' OR relationid = '$id'";
			$qry = $this->cn->query($sql);
			
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateEmployeeInfo()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}


		function addWorkingdays($data){
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
				$sql = "INSERT INTO " . WORKINGDAYMST . " (relationid,workingdaycode,workingdaydate,office,title,startdate,enddate,description,region,createdby,createddate) 
												   VALUES('$relationid','$dateToCode','$dt','$ofc','$title','$startdate','$enddate','$desc','$region','$createdby','$createddate')";
				$qry = $this->cn->query($sql);
				if($relationid == 0){
					$relationid = mysqli_insert_id($this->cn);
				}
				if(!$qry){
					$res['err'] = 1;
					$res['errmsg'] =  'addWorkingdays ' . $this->cn->error;
					// exit();
				}
			}
			
			return $res;
		}

		function deleteWorkingdays($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['id'];
			$userid = $data['userid'];
			$modifieddate = TODAY;



			$columns ="" . WORKINGDAYMST . ".modifiedby = '$userid', 
					" . WORKINGDAYMST . ".modifieddate = '$modifieddate',
					" . WORKINGDAYMST . ".status = 0";
			$sql = "UPDATE " . WORKINGDAYMST . "
						SET $columns 
						WHERE id = '$id' OR relationid = '$id'";
			$qry = $this->cn->query($sql);
			
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func deleteWorkingdays()! " . $this->cn->error;
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