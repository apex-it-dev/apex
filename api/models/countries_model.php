<?php
	// include_once('auditlogs.php');
	class CountriesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		// GET DATA by ALL or ID
		public function getCountries($id=""){
			$res = array();
			$res['err'] = 0;
			$where = " WHERE 1 ";
			if(!empty($id)){
				$where .= " AND " . COUNTRIESMST . ".id = '$id' ";
			}
			$sql = "SELECT " . COUNTRIESMST . ".*
						,(CASE WHEN " . COUNTRIESMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname 
					FROM " . COUNTRIESMST . " $where 
					ORDER BY " . COUNTRIESMST . ".`description`";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'error getCountries func() '. $this->cn->error;
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

		public function closeDB(){
			$this->cn->close();
		}
	}
?>