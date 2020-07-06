<?php
	class GovernmentDedcutionsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function loadGovernmentDeductions($data){
			$res = array();
			$res['err'] = 0;

			$emptyDate = '1900-01-01 00:00:00';
			$ofcname = $data['ofcname'];
			$ofcid = $data['ofcid'];
			$logfm = formatDate("Ymd",$data['logfm']);
			$logto = formatDate("Ymd",$data['logto']);

			$sql = "SELECT ". ATTENDANCESMST .".`userid`
							,CONCAT(a.fname,' ',a.lname) AS eename 
							,COUNT(DISTINCT CASE WHEN ". ATTENDANCESMST .".`status` = 1 THEN ". ATTENDANCESMST .".`loggedno` END) AS totalpresent
							,d.basicpay
							,d.`deminimis`
							,(SELECT " . PAYROLLSSSCONTRIBUTIONS . ".`amountcontributionee` FROM  " . PAYROLLSSSCONTRIBUTIONS . " WHERE  d.basicpay 
								BETWEEN " . PAYROLLSSSCONTRIBUTIONS . ".`compensationrangefrom` AND " . PAYROLLSSSCONTRIBUTIONS . ".`compensationrangeto`) AS ssscontriee
							,(SELECT " . PAYROLLSSSCONTRIBUTIONS . ".`amountcontributioner` FROM  " . PAYROLLSSSCONTRIBUTIONS . " WHERE  d.basicpay 
								BETWEEN " . PAYROLLSSSCONTRIBUTIONS . ".`compensationrangefrom` AND " . PAYROLLSSSCONTRIBUTIONS . ".`compensationrangeto`) AS ssscontrier
							,(SELECT " . PAYROLLPAGIBIGCONTRIBUTIONS. ".`eeshare` FROM " . PAYROLLPAGIBIGCONTRIBUTIONS. " WHERE " . PAYROLLPAGIBIGCONTRIBUTIONS. ".`status` = 1) AS pagibigee
							,(SELECT " . PAYROLLPAGIBIGCONTRIBUTIONS. ".`ershare` FROM " . PAYROLLPAGIBIGCONTRIBUTIONS. " WHERE " . PAYROLLPAGIBIGCONTRIBUTIONS. ".`status` = 1) AS pagibiger
							,(SELECT 
								(CASE 
									WHEN `" . PAYROLLPHILHELATHCONTRIBUTIONS. "`.`premiumrate` * (d.`basicpay` + d.deminimis) > `" . PAYROLLPHILHELATHCONTRIBUTIONS. "`.`monthlypremmax` THEN (`" . PAYROLLPHILHELATHCONTRIBUTIONS. "`.`monthlypremmax`)/2
									ELSE  (`" . PAYROLLPHILHELATHCONTRIBUTIONS. "`.`premiumrate` * (d.`basicpay` + d.deminimis))/2 END)
									AS philcontri 
							FROM `" . PAYROLLPHILHELATHCONTRIBUTIONS. "` WHERE  `" . PAYROLLPHILHELATHCONTRIBUTIONS. "`.`status` = 1) AS philee
							FROM ". ATTENDANCESMST ." 
							RIGHT JOIN ". ABAPEOPLESMST ." a
								ON a.`zkid` = ". ATTENDANCESMST .".`zkid` 
									AND a.`zkdeviceid` = ". ATTENDANCESMST .".`zkdeviceid` 
									AND a.status = 1
							LEFT JOIN ". SALESOFFICESMST ." b
								ON b.`salesofficeid` = a.salesoffice 
									OR b.`salesofficeid` = a.`office` 
							LEFT JOIN ". LEAVESMST ." c 
								ON c.`leaveid` = ". ATTENDANCESMST .".`leaveid` 
									AND c.`status` = 1 
							LEFT JOIN " . EMPLOYEESALARY . " d
							on d.`userid` = a.`userid` 
			WHERE (". ATTENDANCESMST .".`loggedno` >= '$logfm' AND ". ATTENDANCESMST .".`loggedno` <= '$logto') 
			AND ". ATTENDANCESMST .".`status` = 1 AND (a.webhr_station = 'sscceb' OR a.salesoffice = 'SO0007' OR a.office = 'SO0007') 
			GROUP BY ". ATTENDANCESMST .".`userid`, eename,d.basicpay,d.`deminimis`";

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