<?php
	class JobPositionsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		public function getAllJobPositions(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql = "SELECT " .DESIGNATIONSMST. ".designationid, " .DESIGNATIONSMST. ".description, " .DESIGNATIONSMST. ".status 
					FROM " .DESIGNATIONSMST. "
					WHERE " .DESIGNATIONSMST. ".status = 1";

			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllJobPositions()! " . $this->cn->error;
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

		public function getJobTitles($deptid){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql = "SELECT " .DESIGNATIONSMST. ".designationid, " .DESIGNATIONSMST. ".description, " .DESIGNATIONSMST. ".status 
					FROM " .DESIGNATIONSMST. "
					WHERE " .DESIGNATIONSMST. ".status = 1 AND " .DESIGNATIONSMST. ".departmentid = '$deptid' 
					ORDER BY " .DESIGNATIONSMST. ".`description` ASC ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getJobTitles()! " . $this->cn->error;
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

		public function closeDB(){
			$this->cn->close();
		}
	}
?>