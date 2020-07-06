<?php
	class PayrollModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function genPayrollMstID($data){
			$ofcid = $data['ofcid'];
			$today = formatDate("ym",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . PAYROLLMST . " WHERE DATE_FORMAT(" . PAYROLLMST . ".createddate,'%y%m') = '$today'";

			$qry = $this->cn->query($sql);

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "PMSTID-" . formatDate("ymd",TODAY) . "00";
			switch(strlen($cnt)){
				// case 4:
				// 	$newno = substr($pre, 0, -3) . $cnt; break;
				// case 3:
				// 	$newno = substr($pre, 0, -3) . $cnt; break;
				case 2:
					$newno = substr($pre, 0, -2) . $cnt ."-". $ofcid; break;
				default:
					$newno = substr($pre, 0, -1) . $cnt ."-". $ofcid; break;
			}
			// $this->cn->close();
			return $newno;
		}
		function genPayrollDtlID($data){
			// $ofcid = $data['ofcid'];
			$paydate = $data['paydate'];
			$userid =$data['curuserid'];
			$today = formatDate("ym",TODAY);
			$newnno = "";
			$sql = "SELECT COUNT(id) + 1 as idcnt FROM " . PAYROLLDTLS . " WHERE DATE_FORMAT(" . PAYROLLDTLS . ".createddate,'%y%m') = '$today'";

			$qry = $this->cn->query($sql);

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = $row['idcnt'];
			}

			$pre = "PDTLSID-" . formatDate('ymd', $paydate) . "00";
			switch(strlen($cnt)){
				// case 4:
				// 	$newno = substr($pre, 0, -3) . $cnt; break;
				// case 3:
				// 	$newno = substr($pre, 0, -3) . $cnt; break;
				case 2:
					$newno = substr($pre, 0, -2) . $cnt ."-". $userid; break;
				default:
					$newno = substr($pre, 0, -1) . $cnt ."-". $userid; break;
			}
			// $this->cn->close();
			return $newno;
		}

		function checkPeriodIfExist($data){
			$res = array();
			$res['err'] = 0;

			$userid = $data['userid'];
			$ofcid = $data['ofcid'];
			$periodfrom = formatDate("Y-m-d H:i:s", $data['logfm']);
			$periodto = formatDate("Y-m-d H:i:s", $data['logto']);

			// $sql = "SELECT " . PAYROLLMST . ".*
			// 		,";
			$sql = "SELECT * FROM " . PAYROLLMST . "
					WHERE (" . PAYROLLMST . ".`payperiodfrom` BETWEEN '$periodfrom' AND '$periodto')
					AND (" . PAYROLLMST . ".`payperiodto` BETWEEN '$periodfrom' AND '$periodto')
					AND (" . PAYROLLMST . ".`office` = '$ofcid' ) ";

			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmgs'] = "error func checkPeriodIfExist(). ". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		function loadPayrollSettings($data){
			$res = array();
			$res['err'] = 0;

			$ofcid = $data['ofcid'];
			$sql = "SELECT * FROM " . PAYROLLSETTINGS . "
					WHERE " . PAYROLLSETTINGS . ".office = '$ofcid' 
					AND " . PAYROLLSETTINGS . ".status = 1 ";

			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmgs'] = "error func checkPeriodIfExist(). ". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:

			return $res;
		}
		
		function addPayrollMaster($data){
			$res = array();
			$res['err'] = 0;

			$userid = $data['userid'];
			$ofcid = $data['ofcid'];
			$periodfrom = formatDate("Y-m-d H:i:s", $data['logfm']);
			$periodto = formatDate("Y-m-d H:i:s", $data['logto']);
			$paydate = formatDate("Y-m-d H:i:s", $data['paydate']);
			$payrollmstid = $data['payrollmstid'];
			$today = TODAY;
			$sesid = $data['sesid'];
			$frequency = $data['frequency'];
			$period = $data['period'];

			$sql = "INSERT INTO " .PAYROLLMST. "
					(payrollmstid, payperiodfrom, payperiodto, paydate, office, createdby, createddate, sesid, frequency, periodno)
					VALUES('$payrollmstid', '$periodfrom', '$periodto', '$paydate', '$ofcid', '$userid', '$today','$sesid','$frequency', '$period')";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func addPayrollMaster()!". $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		function addPayrollDetails($val,$data){
			$res = array();
			$res['err'] = 0;
			$genpayrolldetailval = array();

			$userid = $val['userid'];
			$ofcid = $val['ofcid'];
			$paydate = formatDate("Y-m-d H:i:s", $val['paydate']);
			$payrollmstid = $val['payrollmstid'];
			$today = TODAY;

			$genpayrolldetailval['paydate'] = $paydate;

			$countsummary = count($data['rows']);
			// $payrolldtlidres = array();
			if($countsummary > 0){
				for ($i=0; $i < $countsummary; $i++) { 
					$sql = "";
					$abauserid = $data['rows'][$i]['userid'];
					$genpayrolldetailval['curuserid'] = $abauserid;
					$payrolldtlid = $this->genPayrollDtlID($genpayrolldetailval);
					$absences = $data['rows'][$i]['absence'] == null ? 0 : $data['rows'][$i]['absence'];
					$late = $data['rows'][$i]['lates'] == null ? 0 : $data['rows'][$i]['lates'];
					$sql = "INSERT INTO " . PAYROLLDTLS . " 
							(payrolldtlid,payrollmstid,userid,absences,lates,createdby,createddate)
							VALUES('$payrolldtlid','$payrollmstid','$abauserid','$absences','$late','$userid','$today')";
					$qry = $this->cn->query($sql);
					if(!$qry){
						$res['err'] = 1;
						$res['errmsg'] = "An error occured in func addPayrollMaster()!". $this->cn->error;
						goto exitme;
					}
				}
			}
			exitme:
			return $res;
		}

		function updatePayrollDetails($val,$data){
			$res = array();
			$result = array();
			$res['err'] = 0;
			
			$userid = $val['userid'];
			$payrollmstid = $val['payrollmstid'];
			$category = $val['category'];
			$countdarray = count($data['rows']);
			$frequency = $val['frequency'];
			$period = $val['periodno'];
			$today = TODAY;
			// $sql = array();
			$res['countarray'] = $countdarray;
			if($countdarray > 0){
				switch ($category) {
					case 'grosssalary':
						for ($i=0; $i < $countdarray; $i++) { 
							$sql = "";
							$curuserid = $data['rows'][$i]['userid'];
							$fbasicpay = $data['rows'][$i]['fbasicpay'] == null ? 0 : $data['rows'][$i]['fbasicpay'];
							$fdeminimis = $data['rows'][$i]['fdeminimis'] == null ? 0 : $data['rows'][$i]['fdeminimis'];
		
							$sql = "UPDATE " . PAYROLLDTLS . " 
									SET " . PAYROLLDTLS . ".basicpay = '$fbasicpay',
										" . PAYROLLDTLS . ".deminimis = '$fdeminimis',
										" . PAYROLLDTLS . ".modifiedby = '$userid',
										" . PAYROLLDTLS . ".modifieddate = '$today'
									WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
									AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmstid' ";
		
							$qry = $this->cn->query($sql);
							if(!$qry){
								$res['err'] = 1;
								$res['errmsg'] = "An error occured in func updatePayrollDetails()!". $this->cn->error;
								goto exitme;
							}
						}
						break;
					case 'salaryadjustment':
						for ($i=0; $i < $countdarray; $i++) { 
							$sql = "";
							$curuserid = $data['rows'][$i]['userid'];
							$salaryadjusmentamount = $data['rows'][$i]['basicsalaryadjustmentamount'] == null ? 0 : $data['rows'][$i]['basicsalaryadjustmentamount'];
							$deminimisadjusmentamount = $data['rows'][$i]['deminimisadjamount'] == null ? 0 : $data['rows'][$i]['deminimisadjamount'];

							$sql = "UPDATE " . PAYROLLDTLS . " 
									SET " . PAYROLLDTLS . ".salaryadjusmentamount = '$salaryadjusmentamount',
										" . PAYROLLDTLS . ".deminimisadjustmentamount = '$deminimisadjusmentamount',
										" . PAYROLLDTLS . ".modifiedby = '$userid',
										" . PAYROLLDTLS . ".modifieddate = '$today'
									WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
									AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmstid' ";

							$qry = $this->cn->query($sql);
							if(!$qry){
								$res['err'] = 1;
								$res['errmsg'] = "An error occured in func updatePayrollDetails()!". $this->cn->error;
								goto exitme;
							}
						}
						break;
					case 'govdeductions':
						for ($i=0; $i < $countdarray; $i++) { 
							$sql = "";
							$curuserid = $data['rows'][$i]['userid'];
							if($period == 1){
								$ssscontriee = number_format($data['rows'][$i]['ssscontriee'],2);
								$updatefield = " " . PAYROLLDTLS . ".ssscontributionamount = '$ssscontriee', ";
							}elseif($period == 2){
								$pagibigee = number_format($data['rows'][$i]['pagibigee'],2);
								$philee = number_format($data['rows'][$i]['philee'],2);
								$updatefield = " " . PAYROLLDTLS . ".philhealthcontributionamount = '$philee',
												 " . PAYROLLDTLS . ".pagibigcontributionamount = '$pagibigee', ";
							}
							
							$sql = "UPDATE " . PAYROLLDTLS . " 
									SET $updatefield 
										" . PAYROLLDTLS . ".modifiedby = '$userid',
										" . PAYROLLDTLS . ".modifieddate = '$today'
									WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
									AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmstid' ";
		
							$qry = $this->cn->query($sql);
							if(!$qry){
								$res['err'] = 1;
								$res['errmsg'] = "An error occured in func updatePayrollDetails()!". $this->cn->error;
								goto exitme;
							}
						}
						break;
					case 'taxableincome':
						for ($i=0; $i < $countdarray; $i++) { 
							$sql = "";
							$curuserid = $data['rows'][$i]['userid'];
							$basicpayaftertax = $data['rows'][$i]['basicpayaftertax'] == null ? 0 : $data['rows'][$i]['basicpayaftertax'];
							$totaltaxdue = $data['rows'][$i]['ttltaxdue'] == null ? 0 : $data['rows'][$i]['ttltaxdue'];

							$sql = "UPDATE " . PAYROLLDTLS . " 
									SET " . PAYROLLDTLS . ".basicpayaftertax = '$basicpayaftertax',
										" . PAYROLLDTLS . ".totaltaxdue = '$totaltaxdue',
										" . PAYROLLDTLS . ".modifiedby = '$userid',
										" . PAYROLLDTLS . ".modifieddate = '$today'
									WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
									AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmstid' ";

							$qry = $this->cn->query($sql);
							if(!$qry){
								$res['err'] = 1;
								$res['errmsg'] = "An error occured in func updatePayrollDetails()!". $this->cn->error;
								goto exitme;
							}	
						}
					break;
					case 'miscellaneous':
						for ($i=0; $i < $countdarray; $i++) { 
							$sql = "";
							$curuserid = $data['rows'][$i]['userid'];
							$miscellaneousamount = $data['rows'][$i]['ttlmiscamount'] == null ? 0 : $data['rows'][$i]['ttlmiscamount'];

							$sql = "UPDATE " . PAYROLLDTLS . " 
									SET " . PAYROLLDTLS . ".miscellaneousamount = '$miscellaneousamount',
										" . PAYROLLDTLS . ".modifiedby = '$userid',
										" . PAYROLLDTLS . ".modifieddate = '$today'
									WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
									AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmstid' ";

							$qry = $this->cn->query($sql);
							if(!$qry){
								$res['err'] = 1;
								$res['errmsg'] = "An error occured in func updatePayrollDetails()!". $this->cn->error;
								goto exitme;
							}	
						}
					break;
					default:
						break;
				}
				
			}
			// $res['sql'] = $sql;
			exitme:
			return $res;
		}

		function getMasterPayroll($sesid){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT * FROM " .PAYROLLMST. "
					WHERE " .PAYROLLMST. ".sesid = '$sesid'";

			// $res['sql'] = $sql;
			$rows = array();
			$qry = $this->cn->query($sql);

			if(!$qry){
				$res['err'] = 1;
				$res['errmgs'] = "error func getMasterPayroll(). ". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:

			return $res;
			
		}

		function calculatePayrollQuery($data){
			$res = array();
			$res['err'] = 0;

			$emptyDate = '1900-01-01 00:00:00';
			$ofcname = $data['ofcname'];
			$ofcid = $data['ofcid'];
			$logfm = formatDate("Ymd",$data['logfm']);
			$logto = formatDate("Ymd",$data['logto']);
			$lateinterval = 15;
			$frequency = $data['frequency'];
			$periodno = $data['periodno'];
			$paydate = formatDate("Y-m-d H:i:s", $data['paydate']);
			$payrollmasterid = $data['payrollmasterid'];

			switch ($frequency) {
				case 'SEMI-MONTHLY':
					$basicpay = '(summary.basicpay/2)';
					$divisor = 2;
					break;
				case 'MONTHLY':
					$basicpay = '(summary.basicpay)';
					$divisor = 1;
					break;
				default:
					break;
			}
			
			switch ($periodno) {
				case 1:
					$governmentdeductions = 'summary.ssscontriee';
					break;
				case 2:
					$governmentdeductions = '(summary.pagibigee + summary.philee)';
					break;
				default:
					break;
			}
			
			// - summary.absence - summary.lates
			$sql = "SELECT 	summary.*
						   	,summary.basicpay
							,summary.deminimis
							,summary.basicpay/2 AS semibasic
							,summary.deminimis/2 AS semibasicdeminimis
							,summary.basicsalaryadjustmentamount
							,summary.deminimisadjamount
							,summary.ttlmiscamount
							,(summary.basicpay/$divisor) - (summary.absence + summary.lates) AS grosssalary
						   	,((SELECT grosssalary) + summary.totalsalaryadjustmentamount) - $governmentdeductions  AS taxableincome 
						   	,(SELECT " .  PAYROLLWITHHOLDINGTAX . ".`prescribedminimumtaxdue` FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
										BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
										AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') 
									AS prescibedmintaxdue
							,(SELECT ((taxableincome - " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom`) *  " .  PAYROLLWITHHOLDINGTAX . ".`excessoverlowerlimitpercentage`)  AS bbb
										FROM " .  PAYROLLWITHHOLDINGTAX . " WHERE taxableincome 
										BETWEEN " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangefrom` AND " .  PAYROLLWITHHOLDINGTAX . ".`compensationrangeto`
										AND " .  PAYROLLWITHHOLDINGTAX . ".`frequency` = '$frequency') 
									AS excessoverlimitamount
							,(SELECT prescibedmintaxdue) + (SELECT excessoverlimitamount)
									AS ttltaxdue
							,(SELECT taxableincome) - (SELECT ttltaxdue) AS basicpayaftertax
							,((SELECT basicpayaftertax) - (summary.ttlmiscamount)) + summary.deminimis AS nettakehomepay
					FROM (
							SELECT ". ATTENDANCESMST .".`userid`
								,CONCAT(a.fname,' ',a.lname) AS eename 
								,any_value(d.basicpay) as basicpay
								,any_value(d.deminimis) as deminimis
								,any_value(d.basicpay)/22 AS dailywage
								,COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END) AS ttlpresent
								,any_value(
										CASE WHEN
											d.basicpay/22 * ((`TOTAL_WEEKDAYS`('$logfm','$logto')) - (COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END))) < 0 THEN 0
										ELSE
											d.basicpay/22 * ((`TOTAL_WEEKDAYS`('$logfm','$logto')) - (COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 AND `". ATTENDANCESMST ."`.`weekend` <> 1 THEN ". ATTENDANCESMST .".`loggedno` END))) END
								)  AS absence
								,any_value((CASE WHEN e.lates IS NULL OR e.lates = 0 THEN 0 ELSE e.lates END)) AS lates
								,any_value((SELECT " .  PAYROLLSSSCONTRIBUTIONS . ".`amountcontributionee` FROM  " .  PAYROLLSSSCONTRIBUTIONS . " WHERE  d.basicpay 
									BETWEEN " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangefrom` AND " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangeto`)) AS ssscontriee
								,any_value((SELECT " .  PAYROLLSSSCONTRIBUTIONS . ".`amountcontributioner` FROM  " .  PAYROLLSSSCONTRIBUTIONS . " WHERE  d.basicpay 
									BETWEEN " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangefrom` AND " .  PAYROLLSSSCONTRIBUTIONS . ".`compensationrangeto`)) AS ssscontrier
								,any_value((SELECT " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`eeshare` FROM " .  PAYROLLPAGIBIGCONTRIBUTIONS . " WHERE " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`status` = 1)) AS pagibigee
								,any_value((SELECT " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`ershare` FROM " .  PAYROLLPAGIBIGCONTRIBUTIONS . " WHERE " .  PAYROLLPAGIBIGCONTRIBUTIONS . ".`status` = 1)) AS pagibiger
								,any_value((SELECT 
									(CASE 
										WHEN " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`premiumrate` * (d.`basicpay` + d.deminimis) > " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`monthlypremmax` THEN " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`monthlypremmax` / 2
										ELSE  (" .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`premiumrate` * (d.`basicpay` + d.deminimis))/$divisor 
									END) AS philcontri 
									FROM " .  PAYROLLPHILHELATHCONTRIBUTIONS . " WHERE  " .  PAYROLLPHILHELATHCONTRIBUTIONS . ".`status` = 1)) 
								AS philee
								,any_value(f.salaryadjusmenttype) as salaryadjusmenttype
								,any_value(f.description) as description
								,any_value((CASE WHEN f.basicsalaryadjustmentamount IS NULL THEN 0
									ELSE f.basicsalaryadjustmentamount END)) AS basicsalaryadjustmentamount
								,any_value((CASE WHEN f.deminimisadjamount IS NULL THEN 0
									ELSE f.deminimisadjamount END)) AS deminimisadjamount
								,any_value((CASE WHEN f.totalsalaryadjustmentamount IS NULL THEN 0
									ELSE f.totalsalaryadjustmentamount END)) AS totalsalaryadjustmentamount
								,any_value((CASE WHEN g.ttlmiscamount IS NULL THEN 0
									ELSE g.ttlmiscamount END)) AS ttlmiscamount
								FROM ". ATTENDANCESMST ." 
								RIGHT JOIN ". ABAPEOPLESMST ." a
									ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
										AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
										AND a.status = 1
								LEFT JOIN aba_sales_offices b
									ON b.`salesofficeid` = a.salesoffice 
										OR b.`salesofficeid` = a.`office` 
								LEFT JOIN hris_leaves c 
									ON c.`leaveid` = ". ATTENDANCESMST .".`leaveid` 
										AND c.`status` = 1 
								LEFT JOIN ". PAYROLLEESALARY ." d
									ON d.`userid` = a.`userid` 
								LEFT JOIN
									(SELECT `". ATTENDANCESMST ."`.`userid`
										,any_value((CASE WHEN 
											(((d.`basicpay`/22)/8)/60) * (CASE WHEN `". ATTENDANCESMST ."`.`loggedout` IS NULL OR DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s') = '00:00:00' THEN TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`". ATTENDANCESMST ."`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) 
											WHEN  TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`". ATTENDANCESMST ."`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) < '00:00:00' THEN '00:00:00'
											ELSE TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`". ATTENDANCESMST ."`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE))
											END )/60 < 0 THEN 0 ELSE
											(((d.`basicpay`/22)/8)/60) * (CASE WHEN `". ATTENDANCESMST ."`.`loggedout` IS NULL OR DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s') = '00:00:00' THEN TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`". ATTENDANCESMST ."`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) 
											WHEN  TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`". ATTENDANCESMST ."`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE)) < '00:00:00' THEN '00:00:00'
											ELSE TIMEDIFF(DATE_FORMAT(`". ATTENDANCESMST ."`.`loggedin`, '%H:%i:%s'),DATE_ADD(CONVERT(CONCAT(`". ATTENDANCESMST ."`.`startshift`,'00'),TIME),INTERVAL $lateinterval MINUTE))
											END )/60 END
										)) AS lates
										FROM `". ATTENDANCESMST ."`
										RIGHT JOIN ". ABAPEOPLESMST ." a
											ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
												AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
												AND a.status = 1
										LEFT JOIN ". PAYROLLEESALARY ." d
													ON d.`userid` = a.`userid` 
											WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
											AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office = '$ofcid') 
											AND `". ATTENDANCESMST ."`.`late` = 1 
											GROUP BY `". ATTENDANCESMST ."`.`userid`) AS e
									ON e.userid = `". ATTENDANCESMST ."`.`userid`
								LEFT JOIN ". PAYROLLSALARYADJUSMENTS ." f
									ON f.userid = `". ATTENDANCESMST ."`.`userid`
									AND f.paydate = '$paydate'
									AND f.status = 1
								LEFT JOIN 
									(SELECT ". ATTENDANCESMST .".`userid`
										,(CASE WHEN SUM(d.`miscamount`) IS NULL THEN 0 
													ELSE SUM(d.`miscamount`) END )  AS ttlmiscamount
										FROM ". ATTENDANCESMST ."
										RIGHT JOIN ". ABAPEOPLESMST ." a
											ON a.`zkid` = hris_attendance.`zkid` 
												AND a.`zkdeviceid` = hris_attendance.`zkdeviceid` 
												AND a.status = 1
										LEFT JOIN ". PAYROLLMISCELLANEOUS ." d
											ON d.`userid` = a.`userid` 
										WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
										AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office = '$ofcid') 
										AND `". ATTENDANCESMST ."`.`late` = 1 
										GROUP BY `". ATTENDANCESMST ."`.`userid`) AS g
									ON g.userid = ". ATTENDANCESMST .".`userid`
							WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
							AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = '$ofcname' OR a.salesoffice = '$ofcid' OR a.office ='$ofcid') 
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

		function updateLoanPaymentsToPayroll($data){
			$res = array();
			$res['err'] = 0;
			
			$loanpayments = $data['loanpayments'];
			$payrollmasterid = $data['payrollmasterid'];
			$userid = $data['userid'];
			$today = TODAY;
			$countloanpayments = count($loanpayments);
			$amountcol = array_column($loanpayments,'amountdue');

			
			if($countloanpayments > 0){
				for ($i=0; $i < $countloanpayments; $i++) { 
					$curuserid = $loanpayments[$i]->userid;
					$amountdue = $loanpayments[$i]->amountdue;
					$loantype = $loanpayments[$i]->loantype;
	
					switch ($loantype) {
						case 'SSS':
							$loan = PAYROLLDTLS .".sssloandeduction = '$amountdue'";
							break;
						case 'Pag-ibig':
							$loan = PAYROLLDTLS .".pagibigloandeduction = '$amountdue'";
							break;
						case 'Company':
							$loan = PAYROLLDTLS .".companyloan = '$amountdue'";
							break;
						default:
					}
	
					$sql = "UPDATE " . PAYROLLDTLS . " 
							SET $loan,
								" . PAYROLLDTLS . ".modifiedby = '$userid',
								" . PAYROLLDTLS . ".modifieddate = '$today'
							WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
							AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmasterid' ";
	
					$qry = $this->cn->query($sql);
					if(!$qry){
						$res['err'] = 1;
						$res['errmsg'] = "An error occured in func updateLoanPaymentsToPayroll()!". $this->cn->error;
						goto exitme;
					}	
				}
			}
			
			
			exitme:
			return $res;
		}

		function updateMiscellaneousToPayroll($data){
			$res = array();
			$res['err'] = 0;
			
			$miscpayments = $data['miscpayments'];
			$payrollmasterid = $data['payrollmasterid'];
			$userid = $data['userid'];
			$today = TODAY;
			$countmiscpayments = count($miscpayments);
			$res['ress'] = $miscpayments;
			// $sql=array();
			
			if($countmiscpayments > 0){
				for ($i=0; $i < $countmiscpayments; $i++) { 
					$curuserid = $miscpayments[$i]->userid;
					$miscamount = $miscpayments[$i]->miscamountfloatval;
					$res['amkount'][] = $miscamount;
	
					$sql = "UPDATE " . PAYROLLDTLS . " 
							SET " . PAYROLLDTLS . ".miscellaneousamount = '$miscamount',
								" . PAYROLLDTLS . ".modifiedby = '$userid',
								" . PAYROLLDTLS . ".modifieddate = '$today'
							WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
							AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmasterid' ";
	
					$qry = $this->cn->query($sql);
					if(!$qry){
						$res['err'] = 1;
						$res['errmsg'] = "An error occured in func updateLoanPaymentsToPayroll()!". $this->cn->error;
						goto exitme;
					}	
				}
			}
			
			// $res['sql'] = $sql;
			exitme:
			return $res;
		}

		function getAllCurrentPayrollDetails($data){
			$res = array();
			$res['err'] = 0;

			$payrollmasterid = $data['payrollmasterid'];
			$frequency = $data['frequency'];
			switch ($frequency) {
				case 'SEMI-MONTHLY':
					$basicpay = '(summary.basicpay/2)';
					$divisor = 2;
					break;
				case 'MONTHLY':
					$basicpay = '(summary.basicpay)';
					$divisor = 1;
					break;
				default:
					break;
			}

			$sql = "SELECT a.*,
						CONCAT(b.fname,' ',b.lname) AS eename, 
						a.`basicpayaftertax` + (c.deminimis)/$divisor AS netpayable
					FROM " .PAYROLLDTLS. " a
					LEFT JOIN " . ABAPEOPLESMST . " b
						ON a.`userid` = b.`userid`
					LEFT JOIN " . EMPLOYEESALARY . " c
						ON c.userid = b.`userid`
					WHERE a.`payrollmstid` = '$payrollmasterid'";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();

			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getAllCurrentPayrollDetails()! " . $this->cn->error;
				goto exitme;
			}
			
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		function loadPayrollMasterList($data){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT * FROM " . PAYROLLMST . "";

			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmge'] = "error func loadPayrollMasterList(). ". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}
			
			$res['rows'] = $rows;

			exitme:
			return $res;
		}
		
		function updateCalculatedNetPayableToPayroll($data){
			$res = array();
			$res['err'] = 0;
			
			$payrollsummarylist = $data['payrollsummarylist'];
			$payrollmasterid = $data['payrollmasterid'];
			$userid = $data['userid'];
			$today = TODAY;
			$countpayrollsummarylist = count($payrollsummarylist);
			// $res['res'] = $payrollsummarylist;
			// $sql=array();
			
			if($countpayrollsummarylist > 0){
				for ($i=0; $i < $countpayrollsummarylist; $i++) { 
					$curuserid = $payrollsummarylist[$i]->userid;
					$netpayable = $payrollsummarylist[$i]->netpayable;
					// $res['amkount'][] = $netpayable;
	
					$sql = "UPDATE " . PAYROLLDTLS . " 
							SET " . PAYROLLDTLS . ".nettakehomepay = '$netpayable',
								" . PAYROLLDTLS . ".modifiedby = '$userid',
								" . PAYROLLDTLS . ".modifieddate = '$today'
							WHERE " . PAYROLLDTLS . ".userid = '$curuserid' 
							AND " . PAYROLLDTLS . ".payrollmstid = '$payrollmasterid' ";
	
					$qry = $this->cn->query($sql);
					if(!$qry){
						$res['err'] = 1;
						$res['errmsg'] = "An error occured in func updateCalculatedNetPayableToPayroll()!". $this->cn->error;
						goto exitme;
					}	
				}
			}
			
			// $res['sql'] = $sql;
			exitme:
			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>