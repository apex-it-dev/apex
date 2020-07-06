<?php
	class BIRWithholdingTaxModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function loadWitholdingTax($data){
			$res = array();
			$res['err'] = 0;

			$emptyDate = '1900-01-01 00:00:00';
			$ofcname = $data['ofcname'];
			$ofcid = $data['ofcid'];
			$logfm = formatDate("Ymd",$data['logfm']);
			$logto = formatDate("Ymd",$data['logto']);
			$lateinterval = 15;
			$frequency = $data['frequency'];

			if($frequency == 'SEMI-MONTHLY'){
				$basicpay = '(summary.basicpay/2)';
				$divisor = 2;
			}elseif($frequency == 'MONTHLY'){
				$basicpay = '(summary.basicpay)';
				$divisor = 1;
			}
			// $period = $data['period'];

			$sql = "SELECT 	summary.*
						   	,summary.basicpay, summary.basicpay/2 AS semi
						   	,(summary.basicpay/2) - summary.ssscontriee - summary.absence - summary.lates AS taxableincome 
						   	,(SELECT " .  PAYROLLWITHHOLDINGTAX . ".`prescribedminimumtaxdue` FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
									BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
									AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') AS prescibedmintaxdue
							,(SELECT ((taxableincome - " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom`) *  " .  PAYROLLWITHHOLDINGTAX . ".`excessoverlowerlimitpercentage`)  AS bbb
									FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
									BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
									AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') AS excessoverlimitamount
							,(SELECT " .  PAYROLLWITHHOLDINGTAX . ".`prescribedminimumtaxdue` FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
									BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
									AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') + (SELECT (taxableincome - " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom`) *  " .  PAYROLLWITHHOLDINGTAX . ".`excessoverlowerlimitpercentage`  AS bbb
									FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
									BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
									AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') AS ttltaxdue
							,(($basicpay - summary.ssscontriee - summary.absence - summary.lates)) -  ((SELECT " .  PAYROLLWITHHOLDINGTAX . ".`prescribedminimumtaxdue` FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
									BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
									AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') + (SELECT (taxableincome - " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom`) *  " .  PAYROLLWITHHOLDINGTAX . ".`excessoverlowerlimitpercentage`  AS bbb
									FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
									BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
									AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency')) AS basicpayaftertax
					FROM (
							SELECT ". ATTENDANCESMST .".`userid`
								,CONCAT(a.fname,' ',a.lname) AS eename 
								,d.basicpay
								,d.`deminimis`
								,COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END) AS ttlabsence
								,d.basicpay/22 * ((`TOTAL_WEEKDAYS`('$logfm','$logto')) - (COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END)))  AS absence
								,(CASE WHEN e.lates IS NULL OR e.lates = 0 THEN 0 ELSE e.lates END) AS lates
								,(SELECT " .  PAYROLLSSSCONTRIBUTIONS . ".`amountcontributionee` FROM  " .  PAYROLLSSSCONTRIBUTIONS . " WHERE  d.basicpay 
									BETWEEN " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangefrom` AND " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangeto`) AS ssscontriee
								,(SELECT " .  PAYROLLSSSCONTRIBUTIONS . ".`amountcontributioner` FROM  " .  PAYROLLSSSCONTRIBUTIONS . " WHERE  d.basicpay 
									BETWEEN " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangefrom` AND " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangeto`) AS ssscontrier
								,(SELECT " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`eeshare` FROM " .  PAYROLLPAGIBIGCONTRIBUTIONS . " WHERE " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`status` = 1) AS pagibigee
								,(SELECT " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`ershare` FROM " .  PAYROLLPAGIBIGCONTRIBUTIONS . " WHERE " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`status` = 1) AS pagibiger
								,(SELECT 
									(CASE 
										WHEN " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`premiumrate` * d.`basicpay` > " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`monthlypremmax` THEN " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`monthlypremmax` / 2
										ELSE  (" .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`premiumrate` * d.`basicpay`)/$divisor 
									END) AS philcontri 
									FROM " .  PAYROLLPHILHELATHCONTRIBUTIONS . " WHERE  " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`status` = 1) 
								AS philee
								
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
										
										,(CASE WHEN 
											(((d.`basicpay`/22)/8)/60) * (CASE WHEN `". ATTENDANCESMST ."`.`loggedout` IS NULL OR DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s') = '00:00:00' THEN TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) 
											WHEN  TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) < '00:00:00' THEN '00:00:00'
											ELSE TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE))
											END )/60 < 0 THEN 0 ELSE
											(((d.`basicpay`/22)/8)/60) * (CASE WHEN `". ATTENDANCESMST ."`.`loggedout` IS NULL OR DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s') = '00:00:00' THEN TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) 
											WHEN  TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) < '00:00:00' THEN '00:00:00'
											ELSE TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`hris_attendance`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE))
											END )/60 END
										) AS lates
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
							GROUP BY ". ATTENDANCESMST .".`userid`, eename) 
					AS summary";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func loadGovernmentDeductions(). ". $this->cn->error;
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

		public function closeDB(){
			$this->cn->close();
		}
	}
?>