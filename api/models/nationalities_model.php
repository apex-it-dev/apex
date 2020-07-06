<?php
	class NationalitiesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}
	

		public function getAllNationalities(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql = "SELECT " .NATIONALITYMST. ".nationalityid, " .NATIONALITYMST. ".description, " .NATIONALITYMST. ".status 
					FROM " .NATIONALITYMST. "
					WHERE " .NATIONALITYMST. ".status = 1";

			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllNationalities()! " . $this->cn->error;
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