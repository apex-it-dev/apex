<?php
	class GetMailSettingsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getMailSettings(){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT *
					FROM ". EMAILSETTINGSMST ." a
					WHERE a.`id` = 1 ";

			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func ".__FUNCTION__."(). ". $this->cn->error;
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