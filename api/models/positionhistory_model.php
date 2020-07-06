<?php
    class PositionHistory extends Database {

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

        
		public function updatePositionHistory_old($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$indexid = $data['indexid'];
			$position_n = $data['position_n'];
			$salary_n = $data['salary_n'] == "" ? "" : addslashes($data['salary_n']);
			// $effectivedate_n = $data['effectivedate_n'];

			if($data['effectivedate_n'] != ""){
                $effectivedate_n = formatDate("Y-m-d",$data['effectivedate_n']);
            }else{
                $effectivedate_n = "1900-01-01 00:00:00";
			}
			

			$columns="";
			switch($indexid){
				case "1":
						$columns = "". ABAPEOPLESMST .".position1 = '$position_n',". ABAPEOPLESMST .".salary1 = '$salary_n',". ABAPEOPLESMST .".effectivitydate1 = '$effectivedate_n'";
					break;
				case "2":
						$columns = "". ABAPEOPLESMST .".position2 = '$position_n',". ABAPEOPLESMST .".salary2 = '$salary_n',". ABAPEOPLESMST .".effectivitydate2 = '$effectivedate_n'";
					break;
				case "3":
						$columns = "". ABAPEOPLESMST .".position3 = '$position_n',". ABAPEOPLESMST .".salary3 = '$salary_n',". ABAPEOPLESMST .".effectivitydate3 = '$effectivedate_n'";
					break;
				case "4":
						$columns = "". ABAPEOPLESMST .".position4 = '$position_n',". ABAPEOPLESMST .".salary4 = '$salary_n',". ABAPEOPLESMST .".effectivitydate4 = '$effectivedate_n'";
					break;
				case "5":
						$columns = "". ABAPEOPLESMST .".position5 = '$position_n',". ABAPEOPLESMST .".salary5 = '$salary_n',". ABAPEOPLESMST .".effectivitydate5 = '$effectivedate_n'";
					break;
				default:
						$columns = "". ABAPEOPLESMST .".position = '$position_n',". ABAPEOPLESMST .".salary = '$salary_n',". ABAPEOPLESMST .".effectivedate = '$effectivedate_n'";
					break;
			}

			$sql = "UPDATE ". ABAPEOPLESMST ." SET $columns WHERE ". ABAPEOPLESMST .".userid='$userid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updatePositionHistory()! " . $this->cn->error;
				goto exitme;
			}
			// while($row = $qry->fetch_array(MYSQLI_ASSOC)){
			// 	$rows[] = $row;
			// }

			// $res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}

		public function updatePositionHistory($data) {
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$indexid = $data['indexid'];
			$position_n = $data['position_n'];
			$salary_n = $data['salary_n'] == "" ? "" : str_replace(',','',$data['salary_n']);
			$today = TODAY;
			$remarks = $data['remarks'];
			// $effectivedate_n = $data['effectivedate_n'];

			if($data['effectivedate_n'] != ""){
                $effectivedate_n = formatDate("Y-m-d",$data['effectivedate_n']);
            }else{
                $effectivedate_n = "1900-01-01 00:00:00";
			}

			if($data['enddate_n'] != ""){
                $enddate_n = formatDate("Y-m-d",$data['enddate_n']);
            }else{
                $enddate_n = "1900-01-01 00:00:00";
			}

			$sql = "UPDATE " . POSITIONHISTMST . " 
					SET " . POSITIONHISTMST . ".`position` = '$position_n' 
					   ," . POSITIONHISTMST . ".`rate` = '$salary_n'
					   ," . POSITIONHISTMST . ".`startdate` = '$effectivedate_n'
					   ," . POSITIONHISTMST . ".`enddate` = '$enddate_n'
					   ," . POSITIONHISTMST . ".`remarks` = '$remarks'
					   ," . POSITIONHISTMST . ".`modifiedby` = '$userid'
					   ," . POSITIONHISTMST . ".`modifieddate` = '$today' 
					WHERE " . POSITIONHISTMST . ".`userid` = '$eeid' 
						AND " . POSITIONHISTMST . ".`id` = $indexid 
					";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()! " . $this->cn->error;
				goto exitme;
			}
			// while($row = $qry->fetch_array(MYSQLI_ASSOC)){
			// 	$rows[] = $row;
			// }

			// $res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
		}

		public function addPositionHistory($data) {
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$position_n = $data['position_n'];
			$salary_n = $data['salary_n'] == "" ? "" : str_replace(',','',$data['salary_n']);
			$today = TODAY;
			$remarks = $data['remarks'];
			// $effectivedate_n = $data['effectivedate_n'];

			if($data['effectivedate_n'] != ""){
                $effectivedate_n = formatDate("Y-m-d",$data['effectivedate_n']);
            }else{
                $effectivedate_n = "1900-01-01 00:00:00";
			}

			if($data['enddate_n'] != ""){
                $enddate_n = formatDate("Y-m-d",$data['enddate_n']);
            }else{
                $enddate_n = "1900-01-01 00:00:00";
			}

			$sql = "INSERT INTO " . POSITIONHISTMST . " 
							(userid,position,rate,startdate,enddate,remarks,createdby,createddate)
					  VALUES('$eeid','$position_n','$salary_n','$effectivedate_n','$enddate_n','$remarks','$userid','$today')";
			
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()! " . $this->cn->error;
				goto exitme;
			}
			// while($row = $qry->fetch_array(MYSQLI_ASSOC)){
			// 	$rows[] = $row;
			// }

			// $res['rows'] = $rows;

			exitme:
			// $this->cn->close();
			return $res;
        }
        
        public function getPositionHistory($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$index = $data['indexid'];
			$sesid = $data['sesid'];

			if($index != ""){
					switch($index){
						case "1":
							$sql = "SELECT ". ABAPEOPLESMST .".position1 as positionid
										,". ABAPEOPLESMST .".salary1 AS salary
										,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivitydate1, '%a %d %b %y') AS effectdt 
										,a.description AS position 
									FROM ". ABAPEOPLESMST ." 
									LEFT JOIN ". DESIGNATIONSMST ." a 
										ON a.designationid = ". ABAPEOPLESMST .".position1 
									WHERE ". ABAPEOPLESMST .".sesid = '$sesid' ";
							break;
						case "2":
							$sql = "SELECT ". ABAPEOPLESMST .".position2 as positionid
										,". ABAPEOPLESMST .".salary2 AS salary
										,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivitydate2, '%a %d %b %y') AS effectdt 
										,a.description AS position 
									FROM ". ABAPEOPLESMST ." 
									LEFT JOIN ". DESIGNATIONSMST ." a 
										ON a.designationid = ". ABAPEOPLESMST .".position2 
									WHERE ". ABAPEOPLESMST .".sesid = '$sesid' ";
							break;
						case "3":
								$sql = "SELECT ". ABAPEOPLESMST .".position3 as positionid
											,". ABAPEOPLESMST .".salary3 AS salary
											,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivitydate3, '%a %d %b %y') AS effectdt 
											,a.description AS position 
										FROM ". ABAPEOPLESMST ." 
										LEFT JOIN ". DESIGNATIONSMST ." a 
											ON a.designationid = ". ABAPEOPLESMST .".position3 
										WHERE ". ABAPEOPLESMST .".sesid = '$sesid' ";
								break;
						case "4":
								$sql = "SELECT ". ABAPEOPLESMST .".position4 as positionid
											,". ABAPEOPLESMST .".salary4 AS salary
											,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivitydate4, '%a %d %b %y') AS effectdt 
											,a.description AS position 
										FROM ". ABAPEOPLESMST ." 
										LEFT JOIN ". DESIGNATIONSMST ." a 
											ON a.designationid = ". ABAPEOPLESMST .".position4 
										WHERE ". ABAPEOPLESMST .".sesid = '$sesid' ";
								break;
						case "5":
								$sql = "SELECT ". ABAPEOPLESMST .".position5 as positionid
											,". ABAPEOPLESMST .".salary5 AS salary
											,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivitydate5, '%a %d %b %y') AS effectdt 
											,a.description AS position 
										FROM ". ABAPEOPLESMST ." 
										LEFT JOIN ". DESIGNATIONSMST ." a 
											ON a.designationid = ". ABAPEOPLESMST .".position3 
										WHERE ". ABAPEOPLESMST .".sesid = '$sesid' ";
								break;
						default:
							$sql = "SELECT ". ABAPEOPLESMST .".position as positionid
									,". ABAPEOPLESMST .".salary AS salary
									,DATE_FORMAT(" . ABAPEOPLESMST . ".effectivedate, '%a %d %b %y') AS effectdt 
									,a.description AS position 
								FROM ". ABAPEOPLESMST ." 
								LEFT JOIN ". DESIGNATIONSMST ." a 
									ON a.designationid = ". ABAPEOPLESMST .".position 
								WHERE ". ABAPEOPLESMST .".sesid = '$sesid' ";
							break;
					}
				}else{
					
					$sql =  "SELECT ".
					"abaPosition.`description` AS desc0,
					abaPeople.`salary` as salary0,
					DATE_FORMAT(abaPeople.`effectivedate`,'%d %b %y') AS date0,
					
					abaPosition1.`description` AS desc1,
					abaPeople.`salary1`,
					DATE_FORMAT(abaPeople.`effectivitydate1`,'%d %b %y') AS date1,
					
					abaPosition2.`description` AS desc2,
					abaPeople.`salary2`,
					DATE_FORMAT(abaPeople.`effectivitydate2`,'%d %b %y') AS date2,
					
					abaPosition3.`description` AS desc3,
					abaPeople.`salary3`,
					DATE_FORMAT(abaPeople.`effectivitydate3`,'%d %b %y') AS date3,
					
					abaPosition4.`description` AS desc4,
					abaPeople.`salary4`,
					DATE_FORMAT(abaPeople.`effectivitydate4`,'%d %b %y') AS date4,
					
					abaPosition5.`description` AS desc5,
					abaPeople.`salary5`,
					DATE_FORMAT(abaPeople.`effectivitydate5`,'%d %b %y') AS date5 ".
					"FROM "	.ABAPEOPLESMST. " abaPeople
					LEFT JOIN aba_designations abaPosition
					ON abaPeople.`position` = abaPosition.`designationid`
					LEFT JOIN aba_designations abaPosition1
					ON abaPeople.`position1` = abaPosition1.`designationid`
					LEFT JOIN aba_designations abaPosition2
					ON abaPeople.`position2` = abaPosition2.`designationid`
					LEFT JOIN aba_designations abaPosition3
					ON abaPeople.`position3` = abaPosition3.`designationid`
					LEFT JOIN aba_designations abaPosition4
					ON abaPeople.`position4` = abaPosition4.`designationid`
					LEFT JOIN aba_designations abaPosition5
					ON abaPeople.`position5` = abaPosition5.`designationid` ".
					"WHERE abaPeople.sesid = '" . $sesid . "'";
				}



				
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func CompensationAndBenefits()! " . $this->cn->error;
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
		

		public function getPositionHistoryTbl($data){
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$index = $data['indexid'];
			$sesid = $data['sesid'];

			$sql = "SELECT a.* 
						  ,b.`description` AS positiondescription
						  ,DATE_FORMAT(a.`startdate`,'%a %d %b %Y') AS newstartdate 
						  ,DATE_FORMAT(a.`enddate`,'%a %d %b %Y') AS newenddate 
					FROM ". POSITIONHISTMST ." a 
					LEFT JOIN ". DESIGNATIONSMST ." b 
						ON b.`designationid` = a.`position` 
					WHERE a.`userid` = '$userid' AND a.`status` = 1 
					ORDER BY a.`startdate` DESC";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func CompensationAndBenefits()! " . $this->cn->error;
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

		
		public function getPositionList(){
			$res = array();
			$rows = array();
			$res['err'] = 0;

			$sql =  "SELECT ".
					"abaPositions.`description`
					,abaPositions.`designationid`
					,abaPositions.`departmentid`  
					FROM aba_designations abaPositions";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getPositionList()! " . $this->cn->error;
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

        public function deletePositionHistory($data) {
			$res = array();
			$rows = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$eeid = $data['eeid'];
			$indexid = $data['indexid'];
			$today = TODAY;

			$sql = "UPDATE " . POSITIONHISTMST . " 
					SET " . POSITIONHISTMST . ".`status` = '0' 
					   ," . POSITIONHISTMST . ".`modifiedby` = '$userid'
					   ," . POSITIONHISTMST . ".`modifieddate` = '$today' 
					WHERE " . POSITIONHISTMST . ".`userid` = '$eeid' 
						AND " . POSITIONHISTMST . ".`id` = $indexid 
					";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func ". __FUNCTION__ ."()! " . $this->cn->error;
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