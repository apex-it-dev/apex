<?php
	class EmployeeSalaryModel extends Database{

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

		function loadEmployeeSalaryRecord($data){
			$res = array();
			$res['err'] = 0;

			$userid = $data['userid'];
			$ofcid = $data['ofcid'];
			$frequency = $data['frequency'];

			$sql = " SELECT " . EMPLOYEESALARY . ".* 
						,CONCAT(a.fname,' ',a.lname) AS eename 
					FROM " . EMPLOYEESALARY . "
					LEFT JOIN ". ABAPEOPLESMST	 ." a
						ON a.userid = " . EMPLOYEESALARY . ".userid 
					WHERE " . EMPLOYEESALARY . ".office = '$ofcid' 
					AND " . EMPLOYEESALARY . ".status = 1";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func addPayrollMaster()!". $this->cn->error;
				goto exitme;
			}

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				switch ($frequency) {
					case 'SEMI-MONTHLY':
						$basicpay = intval($row['basicpay']) / 2;
						$deminimis = intval($row['deminimis']) / 2;
						break;
					case 'MONTHLY':
						$basicpay = intval($row['basicpay']);
						$deminimis = intval($row['deminimis']);
						break;
					default:
					
						break;
				}
				$row['fbasicpay'] = $basicpay;
				$row['fdeminimis'] = $deminimis;
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