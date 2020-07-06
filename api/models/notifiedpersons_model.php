<?php
	class NotifiedPersonsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getLeaveApprovedNotifPersons($data){
			$res = array();
			$res['err'] = 0;
			$ofc = $data['ofc'];
			$notiftype = $data['notiftype'];
			$sql = "SELECT ". NOTIFIEDPERSONSMST .".*
		                ,CONCAT(
		                  (CASE WHEN a.fname != '' THEN a.fname ELSE '' END),' '
		                  ,(CASE WHEN a.mname != '' THEN a.mname ELSE '' END),' '
		                  ,(CASE WHEN a.lname != '' THEN a.lname ELSE '' END)) AS eename   
		                FROM ". NOTIFIEDPERSONSMST ." 
		                  LEFT JOIN ". ABAPEOPLESMST ." a
		                    ON a.userid = ". NOTIFIEDPERSONSMST .".userid 
		                WHERE ". NOTIFIEDPERSONSMST .".office = '$ofc' 
		                  AND ". NOTIFIEDPERSONSMST .".notificationtype = '$notiftype'
		                  AND ". NOTIFIEDPERSONSMST .".status = 1 ";
			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getNotifiedPersons(). ". $this->cn->error;
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