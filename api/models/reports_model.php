<?php
	class ReportsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
        }
        
        public function getEmployeeReports($data) {
            $res = array();
			$rows = array();
			$res['err'] = 0;

			$today = TODAY;
			$filters = $data['filters'];

			$where_val = "";
			
			
			$get_age = "(CASE WHEN a.`birthdate` = '1900-01-01 00:00:00' THEN '0' ELSE FLOOR(DATEDIFF('$today',a.`birthdate`) / 365.25) END)";
			
			// office
			if(count($filters->officeid) > 0) {
				$where_val .= " AND (";
				foreach ($filters->officeid as $key => $officeid) {
					$where_val .= " a.`office` = '$officeid'";
					if($key < count($filters->officeid)-1) $where_val .= " OR";
				}
				$where_val .= ")";
			}
			if(!empty($filters->department)) 	$where_val .= " AND a.`department` = '$filters->department' ";
			if(!empty($filters->position)) 		$where_val .= " AND a.`designation` = '$filters->position' ";
			if(!empty($filters->gender)) 		$where_val .= " AND a.`gender` = '$filters->gender' ";
			if(!empty($filters->ranking)) 		$where_val .= " AND a.`positiongrade` = '$filters->ranking' ";
			if(!empty($filters->eetype)) 		$where_val .= " AND a.`eecategory` = '$filters->eetype' ";
			if(!empty($filters->direct)) { 		
				$where_val .= " AND a.`reportstoid` = '$filters->direct' ";
				if(count($filters->hierarchy) > 0) {
					$where_val .= " AND (";
					foreach ($filters->hierarchy as $key => $eachuser) {
						$where_val .= " a.userid = '$eachuser'";
						if($key < count($filters->hierarchy)-1) $where_val .= " OR";
					}
					$where_val .= ")";
				}
			}
			if(!empty($filters->indirect)) 		$where_val .= " AND a.`reportstoindirectid` = '$filters->indirect' ";
			if($filters->eestatus != '') 		$where_val .= " AND a.`status` = '$filters->eestatus' ";
			if($filters->joineddate->enabled) {
				$joinedfrom = formatDate("Y-m-d", $filters->joineddate->from);
				$joinedto = formatDate("Y-m-d", $filters->joineddate->to);
				$where_val .= " AND a.`joineddate` >= '$joinedfrom' AND a.`joineddate` <= '$joinedto' ";
			}
			if($filters->agerange->enabled) {
				$agefrom = $filters->agerange->from;
				$ageto = $filters->agerange->to;
				if($agefrom < 20) $where_val .= " AND $get_age < $agefrom AND $get_age > 0 ";
				if($ageto > 60) $where_val .= " AND $get_age > $ageto ";
				if($agefrom >= 20 && $ageto <= 60) $where_val .= " AND $get_age >= $agefrom AND $get_age <= $ageto ";
			}
			if($filters->probationenddate->enabled) {
				$probaendfrom = formatDate("Y-m-d", $filters->probationenddate->from);
				$probaendto = formatDate("Y-m-d", $filters->probationenddate->to);
				$where_val .= " AND a.`probationenddate` >= '$probaendfrom' AND a.`probationenddate` <= '$probaendto' ";
			}
			if($filters->enddate->enabled) {
				$probaendfrom = formatDate("Y-m-d", $filters->enddate->from);
				$probaendto = formatDate("Y-m-d", $filters->enddate->to);
				$where_val .= " AND a.`lastworkingday` >= '$probaendfrom' AND a.`lastworkingday` <= '$probaendto' ";
			}

			// a.* is to be removed once everything is sorted out
            $sql = "SELECT 
						b.description AS newposition
						,c.description AS newdepartment
						,d.description AS newoffice
						,e.dddescription AS newranking
						,f.dddescription AS newemployeetype
						,g.dddescription AS newgender
						,h.dddescription AS newstatus
						,(CASE WHEN $get_age = 0 THEN '' ELSE $get_age END) AS age
						,DATE_FORMAT(a.birthdate, '%a %d %b %Y') as newbirthdate 
						,CONCAT(a.fname, ' ',a.lname) AS eename
						,a.fname
						,a.mname
						,a.lname
						,a.abaini
						,a.emailaddress
						,a.userid
						,DATE_FORMAT(a.joineddate, '%a %d %b %Y') AS newjoineddate 
						,CONCAT(i.fname, ' ', i.lname) AS directname
						,CONCAT(j.fname, ' ', j.lname) AS indirectname
						,DATE_FORMAT(a.probationenddate, '%a %d %b %Y') as newprobationenddate 
						,DATE_FORMAT(a.lastworkingday, '%a %d %b %Y') as newlastworkingdate
						,a.cnname
						,a.presentaddress as presentstreet
						,a.presentcity
						,a.presentstate
						,a.presentzipcode
						,k.description AS presentcountry
						,a.personalemail
						,a.mobileno
						,a.homephoneno
						,a.wechat
						,a.skype
						,a.whatsapp
						,a.linkedin
						,a.emercontactperson
						,a.emercontactno
						,a.emercontactrelation
						,a.workemail
						,a.officephoneno
						,a.workskype
						,l.description AS nationality
						,m.dddescription AS maritalstatus 
					FROM ". ABAPEOPLESMST ." a
					LEFT JOIN ". DESIGNATIONSMST ." b
						ON b.designationid = a.designation
					LEFT JOIN ". DEPARTMENTSMST ." c
						ON c.departmentid = a.department
					LEFT JOIN ". SALESOFFICESMST ." d
						ON d.salesofficeid = a.office
					LEFT JOIN ". DROPDOWNSMST ." e
						ON e.dddisplay = 'eerankings'
						AND e.ddid = a.positiongrade
					LEFT JOIN ". DROPDOWNSMST ." f
						ON f.dddisplay = 'eecategory'
						 AND f.ddid = a.eecategory
					LEFT JOIN ". DROPDOWNSMST ." g
						ON g.dddisplay = 'gender'
						 AND g.ddid = a.gender
					LEFT JOIN ". DROPDOWNSMST ." h
						ON h.dddisplay = 'eestatus'
						 AND h.ddid = a.status
					LEFT JOIN ". ABAPEOPLESMST ." i
						ON i.userid = a.reportstoid
					LEFT JOIN ". ABAPEOPLESMST ." j
						ON j.userid = a.reportstoindirectid
					LEFT JOIN ". COUNTRIESMST ." k
						ON k.`countryid` = a.`presentcountry`
					LEFT JOIN ". NATIONALITYMST ." l
						ON l.nationalityid = a.nationality
					LEFT JOIN ". DROPDOWNSMST ." m
						ON m.dddisplay = 'maritalstatus'
						AND m.ddid = a.maritalstatus
					WHERE a.contactcategory = 1 
						AND a.status > -1 
							$where_val";

			$res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			return $res;
		}
		
		public function getLeaveReports($data) {
            $res = array();
			$rows = array();
			$res['err'] = 0;

			$today = TODAY;
			$filters = $data['filters'];

			$where_val = "";

			// office
			if(count($filters->officeid) > 0) {
				$where_val .= " AND (";
				foreach ($filters->officeid as $key => $officeid) {
					$where_val .= " b.`office` = '$officeid'";
					if($key < count($filters->officeid)-1) $where_val .= " OR";
				}
				$where_val .= ")";
			}
			if(!empty($filters->department)) 	$where_val .= " AND b.department = '$filters->department' ";
			if(!empty($filters->position)) 		$where_val .= " AND b.designation = '$filters->position' ";
			// if(!empty($filters->direct)) { 		
			// 	$where_val .= " AND b.`reportstoid` = '$filters->direct' ";
			// 	if(count($filters->hierarchy) > 0) {
			// 		$where_val .= " AND (";
			// 		foreach ($filters->hierarchy as $key => $eachuser) {
			// 			$where_val .= " b.userid = '$eachuser'";
			// 			if($key < count($filters->hierarchy)-1) $where_val .= " OR";
			// 		}
			// 		$where_val .= ")";
			// 	}
			// }
			// if(!empty($filters->indirect)) 		$where_val .= " AND b.reportstoindirectid = '$filters->indirect' ";

			
			if($filters->leavefrom->enabled) {
				$joinedfrom_from = formatDate("Y-m-d", $filters->leavefrom->from);
				$leavefrom_to = formatDate("Y-m-d", $filters->leavefrom->to);
				$where_val .= " AND a.`leavefrom` >= '$joinedfrom_from' AND a.`leavefrom` <= '$leavefrom_to' ";
			}
			
			if($filters->leaveto->enabled) {
				$leaveto_from = formatDate("Y-m-d", $filters->leaveto->from);
				$leaveto_to = formatDate("Y-m-d", $filters->leaveto->to);
				$where_val .= " AND a.`leaveto` >= '$leaveto_from' AND a.`leaveto` <= '$leaveto_to' ";
			}

			if($filters->eestatus != '') 		$where_val .= " AND b.status = '$filters->eestatus' ";
			if($filters->employee != '')		$where_val .= " AND b.userid = '$filters->employee' ";
			if($filters->leavestatus != '') {
				if($filters->leavestatus == -1) {
					$where_val .= " AND (a.status = $filters->leavestatus OR a.approvalstatusindirect = $filters->leavestatus) ";
				} else {
					$where_val .= " AND a.status = $filters->leavestatus ";
				}
			}
			if($filters->leavetype != '') 		$where_val .= " AND LEFT(a.leavetype, LENGTH(a.leavetype)-2) = '$filters->leavetype' ";

			$sql = "SELECT   CONCAT(b.fname, ' ', b.lname) AS eename
							,b.fname
							,b.mname
							,b.lname
							,b.abaini
							,b.reportstoid
							,b.reportstoindirectid
							,h.description AS newoffice
							,f.description AS newdepartment
							,g.description AS newposition
							,a.leaveid
							,a.userid
							,a.reason
							,i.dddescription AS newstatus
							,LEFT(a.leavetype, LENGTH(a.leavetype)-2) AS leavetypeid
							,e.description AS leavetypedescription
							,DATE_FORMAT(a.leavefrom, '%a %d %b %Y') AS newleavefromdate
							,DATE_FORMAT(a.leaveto, '%a %d %b %Y') AS newleavetodate
							,a.noofdays
							,a.reason
							,a.attachment
							,(CASE 
								WHEN a.status = 0 AND b.reportstoid != '' THEN b.reportstoid 
								WHEN a.status != 0 AND a.approvedby != '' THEN a.approvedby
								ELSE NULL 
							END) AS approvedby_direct_id
							,a.approveddate AS approveddate_direct
							,(CASE WHEN a.comments != '' THEN a.comments ELSE NULL END) AS commentsby_direct
							,(CASE 
								WHEN a.approvalstatusindirect = 0 AND b.reportstoindirectid != '' THEN b.reportstoindirectid 
								WHEN a.approvalstatusindirect != 0 AND a.approvedby_indirect != '' THEN a.approvedby_indirect
								ELSE NULL 
							END) AS approvedby_indirect_id
							,a.approveddate_indirect
							,(CASE WHEN a.commentsbyindirect != '' THEN a.commentsbyindirect ELSE NULL END) AS commentsby_indirect
							,a.status AS leavestatus_direct
							,a.approvalstatusindirect AS leavestatus_indirect
							,j.dddescription AS leavestatusdesc_direct
							,k.dddescription AS leavestatusdesc_indirect 
					FROM ". LEAVESMST ." a 
					LEFT JOIN ". ABAPEOPLESMST ." b 
						ON b.status > -1 AND b.contactcategory = 1
						 AND b.userid = a.userid 
					LEFT JOIN ". BENEFITSMST ." e 
						ON e.benefittype = 'leave' 
						AND e.benefitini = LEFT(a.leavetype, LENGTH(a.leavetype)-2) 
					LEFT JOIN ". DEPARTMENTSMST ." f 
						ON f.departmentid = b.department 
					LEFT JOIN ". DESIGNATIONSMST ." g 
						ON g.designationid = b.designation 
					LEFT JOIN ". SALESOFFICESMST ." h
						ON h.salesofficeid = b.office
					LEFT JOIN ". DROPDOWNSMST ." i
						ON i.dddisplay = 'eestatus'
						 AND i.ddid = b.status
					LEFT JOIN ". DROPDOWNSMST ." j
						ON j.`dddisplay` = 'leavestatus'
						AND j.`ddid` = a.`status`
					LEFT JOIN ". DROPDOWNSMST ." k
						ON k.`dddisplay` = 'leavestatus'
						AND k.`ddid` = a.`approvalstatusindirect`
					WHERE b.status > -1 AND b.contactcategory = 1  
						$where_val";

			$res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			exitme:
			return $res;
		}

		public function getPeople() {
            $res = array();
			$rows = array();
			$res['err'] = 0;

			$sql = "SELECT a.`abaini`, a.`userid`, a.`fname`, a.`lname`
					FROM aba_people a
					WHERE a.`status` > -1 AND a.`contactcategory` = 1";
			
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func " . __FUNCTION__ . "()! " . $this->cn->error;
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