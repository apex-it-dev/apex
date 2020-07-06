<?php
	class CalendarEventsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getCalendarEvents(){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT " . CALENDAREVENTSMST . ".* 
					,DATE_FORMAT(" . CALENDAREVENTSMST . ".startdate, '%Y-%m-%d') AS startdt
					,DATE_FORMAT(" . CALENDAREVENTSMST . ".enddate, '%Y-%m-%d') AS enddt
					FROM " . CALENDAREVENTSMST . "";

			$rows = array();
			$event_array = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getLeaveCredits(). ". $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
				$event_array[] = array(
					'title' => $row['title'],
					'start' => $row['startdt'],
					'end' => $row['enddt'],
				);
			}
			$res['rows'] = $rows;
			$res['events'] = $event_array;
			exitme:
			
			// echo json_encode($event_array);
			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>