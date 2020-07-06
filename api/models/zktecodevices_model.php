<?php
	class ZKTecoDevicesModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		public function getAllZKTecoDevices(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql = "SELECT * FROM " .ZKTECODEVICESMST. " WHERE " .ZKTECODEVICESMST. ".status = 1";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllZKTecoDevices()! " . $this->cn->error;
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