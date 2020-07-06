<?php
	class AttendanceModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getAttendance($data){
			$res = array();
			$res['error'] = 0;
			$yrmo = $data['yearmonth'];
			$userid = $data['userid'];
			// $ofcname = $data['ofcname'];
			// $ofcid = $data['ofcid'];
			// $incofcs = $data['incofcs'];
			// $incofcsdesc = $data['incofcsdesc'];
			$logfm = formatDate("Ymd",$data['logfm']);
			$logto = formatDate("Ymd",$data['logto']);
			$dept = $data['dept'];
			$pos = $data['pos'];
			$rank = $data['rank'];
			$ee = $data['ee'];
			// $office = $data['office'];
			$user = "";
			$emptyDate = '1900-01-01 00:00:00';
			$userlevel = $data['user'];
			$office = '';
			$viewtype = $data['viewtype'];

			$officelist = explode(',',$data['office']);
			if(count($officelist) > 0 && $data['office'] != '') {
				$office .= " AND a.office IN (";
				foreach($officelist as $eachofc) {
					$office .= "'$eachofc',";
				}
				$office = substr($office, 0, -1);
				$office .= ") ";
			}
			

			// $office = "";
			// if(!empty($ofcid)){
			// 	$office = " AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office = '$ofcid'";
			// 	if(count($incofcs) > 0){
			// 		foreach ($incofcs as $key => $eachincofcs) {
			// 			if($eachincofcs != $ofcid)
			// 				$office .= " OR a.salesoffice = '$eachincofcs' OR a.office = '$eachincofcs'";
			// 		}
			// 	}
			// 	if(count($incofcsdesc) > 0){
			// 		foreach ($incofcsdesc as $key => $eachincofcsdesc) {
			// 			if($eachincofcsdesc != $ofcname)
			// 				$office .= " OR a.webhr_station = '$eachincofcsdesc'";
			// 		}
			// 	}
			// 	$office .= ")";
			// }

			if($ee != ""){
				$user = " AND a.userid = '$ee' ";
			} else if($viewtype == 'department') {
				$user = " AND (a.reportstoid = '$userid' OR a.reportstoindirectid = '$userid' OR a.userid = '$userid' ) ";
			}
			// } else {
			// 	if($userlevel == 'head'){
			// 		$user = " AND (a.reportstoid = '$userid' OR a.reportstoindirectid = '$userid') ";
			// 	}


			$sql = "SELECT ". ATTENDANCESMST .".*
					,date_format(". ATTENDANCESMST .".`loggedin`,'%a %d %b %y') AS loggeddt
					,(CASE WHEN ". ATTENDANCESMST .".`loggedin` = '$emptyDate' THEN '--:--' ELSE date_format(". ATTENDANCESMST .".`loggedin`,'%H:%i') END) AS login
					,(CASE WHEN ". ATTENDANCESMST .".`loggedout` = '$emptyDate' THEN '--:--' ELSE date_format(". ATTENDANCESMST .".`loggedout`,'%H:%i') END) AS logout
					,CONCAT(a.fname,' ',a.lname) AS eename 
					,b.`description` as officename 
					,LEFT(c.`leavetype`, LENGTH(c.`leavetype`)-2) AS leavetype 
				FROM ". ATTENDANCESMST ." 
				RIGHT JOIN ". ABAPEOPLESMST	 ." a
					ON a.`userid` = ". ATTENDANCESMST .".`userid`  
						AND a.`status` = 1  
						AND a.`contactcategory` = 1 
				LEFT JOIN ". SALESOFFICESMST ." b 
					ON (b.`salesofficeid` = a.salesoffice AND (a.`office` = '' OR a.`office` IS NULL)) 
						OR (b.`salesofficeid` = a.`office` AND (a.`salesoffice` = '' OR a.`salesoffice` IS NULL)) 
						OR (b.`salesofficeid` = a.`office` AND (a.`salesoffice` <> '' AND a.`salesoffice` IS NOT NULL)) 
				LEFT JOIN ". LEAVESMST ." c 
					ON c.`leaveid` = ". ATTENDANCESMST .".`leaveid` 
						AND c.`status` = 1 
				WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
						AND ". ATTENDANCESMST .".`status` = 1 $office $user 
				ORDER BY ". ATTENDANCESMST .".loggedin DESC ";
			// }else{
			// 	if($ee != ""){
			// 		$user = " AND (a.userid = '$ee') ";
			// 	}else{
			// 		$user = " AND (a.userid = '$userid') ";
			// 	}
			// 	$sql = "SELECT ". ATTENDANCESMST .".*
			// 			,date_format(". ATTENDANCESMST .".`loggedin`,'%a %d %b %y') AS loggeddt
			// 			,date_format(". ATTENDANCESMST .".`loggedin`,'%H:%i') AS login
			// 			,date_format(". ATTENDANCESMST .".`loggedout`,'%H:%i') AS logout 
			// 			,CONCAT(a.fname,' ',a.lname) AS eename 
			// 		FROM ". ATTENDANCESMST ." 
			// 		RIGHT JOIN ". ABAPEOPLESMST	 ." a
			// 			ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
			// 				AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
			// 		WHERE (SUBSTRING(". ATTENDANCESMST .".`loggedno`,1,LENGTH(". ATTENDANCESMST .".`loggedno`)-2) = '$yrmo') 
			// 			$user 
			// 		ORDER BY ". ATTENDANCESMST .".loggedin DESC ";
			// }
			// }else if($dept == "DEPT0006" || $rank == 1 || $rank == 2){
			// 	$sql = "SELECT ". ATTENDANCESMST .".*
			// 			,date_format(". ATTENDANCESMST .".`loggedin`,'%a %d %b %y') AS loggeddt
			// 			,date_format(". ATTENDANCESMST .".`loggedin`,'%H:%i') AS login
			// 			,date_format(". ATTENDANCESMST .".`loggedout`,'%H:%i') AS logout 
			// 			,CONCAT(a.fname,' ',a.lname) AS eename 
			// 		FROM ". ATTENDANCESMST ." 
			// 		RIGHT JOIN ". ABAPEOPLESMST	 ." a
			// 			ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
			// 				AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
			// 				AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid') 
			// 		WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
			// 		ORDER BY ". ATTENDANCESMST .".loggedin DESC ";
			// }else{
			// 	$sql = "SELECT ". ATTENDANCESMST .".*
			// 			,date_format(". ATTENDANCESMST .".`loggedin`,'%a %d %b %y') AS loggeddt
			// 			,date_format(". ATTENDANCESMST .".`loggedin`,'%H:%i') AS login
			// 			,date_format(". ATTENDANCESMST .".`loggedout`,'%H:%i') AS logout 
			// 			,CONCAT(a.fname,' ',a.lname) AS eename 
			// 		FROM ". ATTENDANCESMST ." 
			// 		RIGHT JOIN ". ABAPEOPLESMST	 ." a
			// 			ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
			// 				AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
			// 		WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
			// 		ORDER BY ". ATTENDANCESMST .".loggedin DESC ";
			// }
				// $sql = "SELECT ". ATTENDANCESMST .".*
				// 		,date_format(". ATTENDANCESMST .".`loggedin`,'%a %d %b %y') AS loggeddt
				// 		,date_format(". ATTENDANCESMST .".`loggedin`,'%H:%i') AS login
				// 		,date_format(". ATTENDANCESMST .".`loggedout`,'%H:%i') AS logout 
				// 		,CONCAT(a.fname,' ',a.lname) AS eename 
				// 	FROM ". ATTENDANCESMST ." 
				// 	RIGHT JOIN ". ABAPEOPLESMST	 ." a
				// 		ON a.`zkid` = ". ATTENDANCESMST .".`zkid` AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
				// 	WHERE a.`userid` = '$userid' 
				// 		AND SUBSTRING(". ATTENDANCESMST .".`loggedno`,1,LENGTH(". ATTENDANCESMST .".`loggedno`)-2) = '$yrmo' 
				// 	ORDER BY ". ATTENDANCESMST .".loggedin DESC ";
			
			$res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getAttendance(). ". $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rem = "";
				if($row['holiday'] > 0){
					$rem = 'HOLIDAY';
				}
				if($row['onleave'] > 0){
					$rem = 'ON LEAVE - ';
					if($row['wholeday'] > 0){
						$rem .= 'WHOLEDAY';
					}
					if($row['firsthalf'] > 0){
						$rem .= 'FIRST HALF';
					}
					if($row['secondhalf'] > 0){
						$rem .= 'SECOND HALF';
					}
					if(!empty($row['leavetype']) && $row['leavetype'] != null){
						$rem .= ' (' .$row['leavetype']. ')';
					}
				}
				$row['remarks'] = $rem;
				$rows[] = $row;
			}
			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function saveAttendanceLog($data){
			$res = array();
			$res['error'] = 0;
			$leaveid = $data['leaveid'];
			$zkdeviceid = $data['zkdeviceid'];
			$zkid = $data['zkid'];
			$wd = $data['wholeday'];
			$am = $data['firsthalf'];
			$pm = $data['secondhalf'];
			$loggedno = $data['loggedno'];
			// $emptyDate = '1900-01-01 00:00:00';
			$loggedin = $data['loggedin'];
			$loggedout = $data['loggedout'];
			$attendance_exist = $data['attendance_exist'];
			$userid = $data['userid'];
			
			if($attendance_exist == 1){
				$sql = "UPDATE ". ATTENDANCESMST ." SET loggedin = '$loggedin', loggedout = '$loggedout', onleave = 1, wholeday = '$wd', firsthalf = '$am', secondhalf = '$pm', leaveid = '$leaveid' 
						WHERE userid = '$userid' AND loggedno = '$loggedno' ";
			} else {
				$sql = "INSERT INTO ". ATTENDANCESMST ."(zkdeviceid,zkid,loggedno,loggedin,loggedout,onleave,wholeday,firsthalf,secondhalf,leaveid,userid) 
						VALUES('$zkdeviceid','$zkid','$loggedno','$loggedin','$loggedout',1,'$wd','$am','$pm','$leaveid','$userid')";
			}
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func saveAttendanceLog(). ". $this->cn->error;
				goto exitme;
			}
			
			exitme:
			return $res;
		}

		function signInAttendance($data){
			$res = array();
			$zkdeviceid = $data['zkdeviceid'];
			$zkid = $data['zkid'];
			$userid = $data['userid'];
			$loggedno = $data['loggedno'];
			$loggedin = $data['loggedin'];
			$loggeddate = $data['loggeddate'];
			$startshift = $data['startshift'];
			$signintype = $data['signintype'];
			$hasrecordbutabsent = $data['recordisabsent'];
			$lateflag = 0;
			if($startshift != null && $startshift != ''){
				$loggedintime = (int)formatDate("Hi", $loggedin);
				$lateisred = $loggedintime > (int)$startshift + 15;
				$lateisyellow = $loggedintime > (int)$startshift && $loggedintime <= (int)$startshift + 15;
				if($lateisred){
					$lateflag = 1;
				} else if($lateisyellow){
					$lateflag = 2;
				} else {
					$lateflag = 0;
				}	
			}
			if($data['loggedout'] != ""){
				$loggedout = formatDate("Y-m-d H:i:s",$data['loggedout']);
			}else{
				$loggedout = "1900-01-01 00:00:00";
			}

			if($hasrecordbutabsent == 1){
				$sql = "UPDATE ". ATTENDANCESMST ." SET loggedin = '$loggedin', late = '$lateflag', loggeddate = '$loggeddate' 
						WHERE userid = '$userid' AND loggedno = '$loggedno' ";
				//(zkdeviceid = '$zkdeviceid' AND zkid = '$zkid')
			} else {
				$sql = "INSERT INTO ". ATTENDANCESMST ."(zkdeviceid,zkid,loggedno,loggedin,loggeddate,loggedout,late,userid,startshift,deviceused) 
						VALUES('$zkdeviceid','$zkid','$loggedno','$loggedin','$loggeddate','$loggedout','$lateflag','$userid','$startshift','$signintype')";
			}
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func signInAttendance(). ". $this->cn->error;
				goto exitme;
			}
			
			exitme:
			return $res;
		}

		function signOutAttendance($data){
			$res = array();
			$zkdeviceid = $data['zkdeviceid'];
			$zkid = $data['zkid'];
			$userid = $data['userid'];
			$loggedno = $data['loggedno'];
			if($data['loggedout'] != ""){
				$loggedout = formatDate("Y-m-d H:i:s",$data['loggedout']);
			}else{
				$loggedout = "1900-01-01 00:00:00";
			}
			$sql = "UPDATE ". ATTENDANCESMST ." 
					SET ". ATTENDANCESMST .".loggedout = '$loggedout' 
					WHERE userid = '$userid' AND loggedno = '$loggedno' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func signOutAttendance(). ". $this->cn->error;
				goto exitme;
			}
			
			exitme:
			return $res;
		}

		function checkAttendance($data){
			$res = array();
			$zkdeviceid = $data['zkdeviceid'];
			$zkid = $data['zkid'];
			$userid = $data['userid'];
			$loggedno = $data['loggedno'];

			$sql = "SELECT * FROM " . ATTENDANCESMST . " 
					WHERE " . ATTENDANCESMST . ".userid = '$userid' 
						AND " . ATTENDANCESMST . ".loggedno = '$loggedno' 
						AND " . ATTENDANCESMST . ".status = 1 ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func checkAttendance(). ". $this->cn->error;
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

		function getAttendanceRecord($data){
			$res = array();
			$res['err'] = 0;

			$emptyDate = '1900-01-01 00:00:00';
			$ofcname = $data['ofcname'];
			$ofcid = $data['ofcid'];
			$logfm = formatDate("Ymd",$data['logfm']);
			$logto = formatDate("Ymd",$data['logto']);

			$sql = "SELECT ". ATTENDANCESMST .".*
					,date_format(". ATTENDANCESMST .".`loggedin`,'%a %d %b %y') AS loggeddt
					,(CASE WHEN ". ATTENDANCESMST .".`loggedin` = '$emptyDate' THEN '--:--' ELSE date_format(". ATTENDANCESMST .".`loggedin`,'%H:%i') END) AS login
					,(CASE WHEN ". ATTENDANCESMST .".`loggedout` = '$emptyDate' THEN '--:--' ELSE date_format(". ATTENDANCESMST .".`loggedout`,'%H:%i') END) AS logout
					,CONCAT(a.fname,' ',a.lname) AS eename 
					,b.`description` as officename 
					,LEFT(c.`leavetype`, LENGTH(c.`leavetype`)-2) AS leavetype 
				FROM ". ATTENDANCESMST ." 
				RIGHT JOIN ". ABAPEOPLESMST	 ." a
					ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
						AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
						AND a.`status` = 1 
				LEFT JOIN ". SALESOFFICESMST ." b
					ON b.`salesofficeid` = a.salesoffice 
						OR b.`salesofficeid` = a.`office` 
				LEFT JOIN ". LEAVESMST ." c 
					ON c.`leaveid` = ". ATTENDANCESMST .".`leaveid` 
						AND c.`status` = 1 
				WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
						AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office = '$ofcid')
				ORDER BY ". ATTENDANCESMST .".userid DESC ";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getAttendanceRecord(). ". $this->cn->error;
				goto exitme;
			}

			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rem = "";
				if($row['holiday'] > 0){
					$rem = 'HOLIDAY';
				}
				if($row['onleave'] > 0){
					$rem = 'ON LEAVE - ';
					if($row['wholeday'] > 0){
						$rem .= 'WHOLEDAY';
					}
					if($row['firsthalf'] > 0){
						$rem .= 'FIRST HALF';
					}
					if($row['secondhalf'] > 0){
						$rem .= 'SECOND HALF';
					}
					if(!empty($row['leavetype']) && $row['leavetype'] != null){
						$rem .= ' (' .$row['leavetype']. ')';
					}
				}
				$row['remarks'] = $rem;
				$rows[] = $row;
			}
			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		function getAttendanceSummary($data){
			$res = array();
			$res['err'] = 0;

			$emptyDate = '1900-01-01 00:00:00';
			$ofcname = $data['ofcname'];
			$ofcid = $data['ofcid'];
			$logfm = formatDate("Ymd",$data['logfm']);
			$logto = formatDate("Ymd",$data['logto']);
			$lateinterval = 15;

			$sql = "SELECT ". ATTENDANCESMST .".`userid`
					,CONCAT(a.fname,' ',a.lname) AS eename 
					,COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END) AS ttlpresent
					,d.basicpay/22 AS dailywage
					,any_value(
						CASE WHEN
						(SELECT dailywage) * ((`TOTAL_WEEKDAYS`('$logfm','$logto')) - (COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END))) < 0 THEN 0
						ELSE
						(SELECT dailywage) * ((`TOTAL_WEEKDAYS`('$logfm','$logto')) - (COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END))) END
				)  AS absence
					,(CASE WHEN e.lates IS NULL OR e.lates = 0 THEN 0 ELSE e.lates END) AS lates
					FROM ". ATTENDANCESMST ." 
					RIGHT JOIN aba_people a
						ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
							AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
							AND a.status = 1
					LEFT JOIN aba_sales_offices b
						ON b.`salesofficeid` = a.salesoffice 
							OR b.`salesofficeid` = a.`office` 
					LEFT JOIN hris_leaves c 
						ON c.`leaveid` = ". ATTENDANCESMST .".`leaveid` 
							AND c.`status` = 1 
					LEFT JOIN aba_people_salary d
						ON d.`userid` = a.`userid` 
					LEFT JOIN
						(SELECT `". ATTENDANCESMST ."`.`userid`
							,any_value((CASE WHEN 
								(((d.`basicpay`/22)/8)/60) * (CASE WHEN `". ATTENDANCESMST ."`.`loggedout` IS NULL OR DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s') = '00:00:00' THEN TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) 
								WHEN  TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) < '00:00:00' THEN '00:00:00'
								ELSE TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE))
								END )/60 < 0 THEN 0 ELSE
								(((d.`basicpay`/22)/8)/60) * (CASE WHEN `". ATTENDANCESMST ."`.`loggedout` IS NULL OR DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s') = '00:00:00' THEN TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) 
								WHEN  TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) < '00:00:00' THEN '00:00:00'
								ELSE TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE))
								END )/60 END
							)) AS lates
							FROM `". ATTENDANCESMST ."`
							RIGHT JOIN aba_people a
								ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
									AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
									AND a.status = 1
							LEFT JOIN aba_people_salary d
										ON d.`userid` = a.`userid` 
								WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
								AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office = '$ofcid') 
								AND `". ATTENDANCESMST ."`.`late` = 1 
								GROUP BY `". ATTENDANCESMST ."`.`userid`) AS e
						ON e.userid = `". ATTENDANCESMST ."`.`userid`
				WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
				AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office = '$ofcid') 
				GROUP BY ". ATTENDANCESMST .".`userid`, eename, d.basicpay, e.lates";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func getAttendanceSummary(). ". $this->cn->error;
				goto exitme;
			}

			$rows = array();
			$counter = 0;
			$lfms = formatDate("Y-m-d",$data['logfm']);
			$ltos = formatDate("Y-m-d,",$data['logto']);
			$lfm = strtotime($lfms);
			$lto = strtotime($ltos);
			
			while($lfm <= $lto){
				$counter += date("N", $lfm) < 6 ? 1 : 0;
				$lfm = strtotime("+1 day", $lfm);
			}
			$workingdays['workingdays'] = $counter;
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = array_merge($row, $workingdays);
			}
			
			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		function getAttendanceByDate($data){
			$res = array();
			$datein = formatDate("Ymd",$data['datein']);
			$res['err'] = 0;

			$sql = "SELECT date_format(". ATTENDANCESMST .".`loggedin`,'%H%i') as datein 
					FROM ". ATTENDANCESMST ." 
					WHERE ". ATTENDANCESMST .".`loggedno` = '$datein' 
					  AND ". ATTENDANCESMST .".`status` = 1 
					  AND ". ATTENDANCESMST .".`onleave` = 0";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func " . __FUNCTION__ . "()" . $this->cn->error;
				goto exitme;
			}
			$rows = array();
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
				// $rows[] = $row;
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