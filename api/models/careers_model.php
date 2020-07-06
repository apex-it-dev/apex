<?php
	// include_once('auditlogs.php');
	class CareersModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		public function getHiringPositions(){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". ABACAREERSMST .".* 
					FROM ". ABACAREERSMST ." 
					WHERE ". ABACAREERSMST .".status = 1 ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getHiringPositions()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:

			return $res;
		}

		public function getHiringPosition($id){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". ABACAREERSMST .".* 
					FROM ". ABACAREERSMST ." 
					WHERE ". ABACAREERSMST .".status = 1 AND ". ABACAREERSMST .".careerid = '$id' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getHiringPosition()! " . $this->cn->error;
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