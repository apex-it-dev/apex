<?php
	class RecruitmentsModel extends Database2{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database2();
			$this->cn = $this->db->connect();
		}

		public function genApplicantID($now){
			$res = array();
			$cnt = 1;
			$today = formatDate("Ymd",$now);
			$sql = "SELECT COUNT(id) AS cnt 
					FROM ". APPLICANTSMST ." 
					WHERE DATE_FORMAT(". APPLICANTSMST .".createddate, '%Y%m%d') = '$today'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);

			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$cnt = ($row['cnt'] + 1);
			}
			$pre = "APP". $today ."00";
			// $pre = "00000";
			switch(strlen($cnt)){
				case 2:
					$newno = substr($pre, 0, -2) . $cnt; break;
				default:
					$newno = substr($pre, 0, -1) . $cnt; break;
			}
			
			return $newno;
		}

		public function saveApplicant($data){
			$res = array();
			$res['err'] = 0;
			$fname = $data['fname'];
			$mname = $data['mname'];
			$lname = strtoupper($data['lname']);
			$eaddr = $data['eaddress'];
			$ctcno = $data['contactno'];
			$age = $data['agebracket'];
			$gender = $data['gender'];
			$careerid = $data['careerid'];

			$today = TODAY;
			// $appid = '00001';
			$appid = $this->genApplicantID($today);
			$filename = $appid .'_'. $data['filename'];
			$uri = genuri($appid);
			$filesize = $data['filesize'];
			$filetype = $data['filetype'];

			$filename2 = "";
			if(!empty($data['filename2'])){
				$filename2 = $appid .'_'. $data['filename2'];
			}
			$filesize2 = $data['filesize2'];
			$filetype2 = $data['filetype2'];
			// $uri = 'zfghm';

			$sql = "INSERT INTO ". APPLICANTSMST ."(applicantid,firstname,middlename,lastname,emailaddress,contactno,gender,agebracket,createddate,resume,filesize,filetype,sesid
						,coverletter,filesize2,filetype2,position) 
					VALUES('$appid','$fname','$mname','$lname','$eaddr','$ctcno','$gender','$age','$today','$filename','$filesize','$filetype','$uri'
						,'$filename2','$filesize2','$filetype2','$careerid')";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveApplicant()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			$res['appid'] = $appid;
			return $res;
		}

		public function getApplicants($data){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". APPLICANTSMST .".* 
						,(CASE WHEN ". APPLICANTSMST .".agebracket = '1820' THEN '18-20' 
							WHEN ". APPLICANTSMST .".agebracket = '2125' THEN '21-25' 
							WHEN ". APPLICANTSMST .".agebracket = '2630' THEN '26-30' 
							WHEN ". APPLICANTSMST .".agebracket = '3135' THEN '31-35' 
							WHEN ". APPLICANTSMST .".agebracket = '3640' THEN '36-40' 
							WHEN ". APPLICANTSMST .".agebracket = '4145' THEN '41-45' 
							WHEN ". APPLICANTSMST .".agebracket = '4650' THEN '46-50' 
							WHEN ". APPLICANTSMST .".agebracket = '5155' THEN '51-55' 
							WHEN ". APPLICANTSMST .".agebracket = '5660' THEN '56-60' 
							ELSE '' END) AS agedesc
						,(CASE WHEN ". APPLICANTSMST .".gender = 'm' THEN 'Male' 
							ELSE 'Female' END) AS genderdesc 
					FROM ". APPLICANTSMST ." 
					WHERE ". APPLICANTSMST .".status NOT IN (1,-1) ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getApplicants()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:

			return $res;
		}

		public function getApplicantById($id){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". APPLICANTSMST .".* 
						,(CASE WHEN ". APPLICANTSMST .".agebracket = '1820' THEN '18-20' 
							WHEN ". APPLICANTSMST .".agebracket = '2125' THEN '21-25' 
							WHEN ". APPLICANTSMST .".agebracket = '2630' THEN '26-30' 
							WHEN ". APPLICANTSMST .".agebracket = '3135' THEN '31-35' 
							WHEN ". APPLICANTSMST .".agebracket = '3640' THEN '36-40' 
							WHEN ". APPLICANTSMST .".agebracket = '4145' THEN '41-45' 
							WHEN ". APPLICANTSMST .".agebracket = '4650' THEN '46-50' 
							WHEN ". APPLICANTSMST .".agebracket = '5155' THEN '51-55' 
							WHEN ". APPLICANTSMST .".agebracket = '5660' THEN '56-60' 
							ELSE '' END) AS agedesc
						,(CASE WHEN ". APPLICANTSMST .".gender = 'm' THEN 'Male' 
							ELSE 'Female' END) AS genderdesc
						,(CASE WHEN ". APPLICANTSMST .".status = 0 THEN 'New' 
							WHEN ". APPLICANTSMST .".status = 2 THEN 'Consideration' 
							WHEN ". APPLICANTSMST .".status = 3 THEN 'Interview' 
							WHEN ". APPLICANTSMST .".status = 4 THEN 'Final Interview' 
							WHEN ". APPLICANTSMST .".status = 5 THEN 'Hire' 
							ELSE '' END) AS statusdesc 
					FROM ". APPLICANTSMST ." 
					WHERE ". APPLICANTSMST .".applicantid = '$id' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getApplicant()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:			

			return $res;
		}

		public function getApplicantBySesid($sesid){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". APPLICANTSMST .".* 
						,(CASE WHEN ". APPLICANTSMST .".agebracket = '1820' THEN '18-20' 
							WHEN ". APPLICANTSMST .".agebracket = '2125' THEN '21-25' 
							WHEN ". APPLICANTSMST .".agebracket = '2630' THEN '26-30' 
							WHEN ". APPLICANTSMST .".agebracket = '3135' THEN '31-35' 
							WHEN ". APPLICANTSMST .".agebracket = '3640' THEN '36-40' 
							WHEN ". APPLICANTSMST .".agebracket = '4145' THEN '41-45' 
							WHEN ". APPLICANTSMST .".agebracket = '4650' THEN '46-50' 
							WHEN ". APPLICANTSMST .".agebracket = '5155' THEN '51-55' 
							WHEN ". APPLICANTSMST .".agebracket = '5660' THEN '56-60' 
							ELSE '' END) AS agedesc
						,(CASE WHEN ". APPLICANTSMST .".gender = 'm' THEN 'Male' 
							ELSE 'Female' END) AS genderdesc
						,(CASE WHEN ". APPLICANTSMST .".status = 0 THEN 'New' 
							WHEN ". APPLICANTSMST .".status = 2 THEN 'Consideration' 
							WHEN ". APPLICANTSMST .".status = 3 THEN 'Interview' 
							WHEN ". APPLICANTSMST .".status = 4 THEN 'Final Interview' 
							WHEN ". APPLICANTSMST .".status = 5 THEN 'Hire' 
							ELSE '' END) AS statusdesc 
					FROM ". APPLICANTSMST ." 
					WHERE ". APPLICANTSMST .".sesid = '$sesid' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getApplicant()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:			

			return $res;
		}

		public function saveInterviewer($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$appid = $data['applicantid'];
			$int = $data['interviewer'];
			$intid = $appid .'_'. $int;
			$inttype = $data['interviewtype'];
			$intname = $data['name'];
			$intjt = $data['jobtitle'];
			$intofc = $data['office'];
			$intdate = formatDate("Y-m-d ", $data['interviewdate']) . date("h:i:s");
			$inttime = $data['interviewtime'];
			$today = TODAY;

			$sql = "INSERT INTO ". INTERVIEWERSMST ."(applicantid,interviewid,interviewtype,interviewer,interviewername,interviewerjobtitle,intervieweroffice,interviewdate,interviewtime,createdby,createddate) 
					VALUES('$appid','$intid','$inttype','$int','$intname','$intjt','$intofc','$intdate','$inttime','$userid','$today')";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveInterviewer()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		public function updateInterviewer($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$int = $data['interviewer'];
			$intid = $data['interviewid'];
			$inttype = $data['interviewtype'];
			$intname = $data['name'];
			$intjt = $data['jobtitle'];
			$intofc = $data['office'];
			$intdate = formatDate("Y-m-d ", $data['interviewdate']) . date("h:i:s");
			$inttime = $data['interviewtime'];
			$rem = $data['remarks'];
			$stat = $data['status'];
			$today = TODAY;

			$sql = "UPDATE ". INTERVIEWERSMST ." 
					SET ". INTERVIEWERSMST .".interviewtype = '$inttype', ". INTERVIEWERSMST .".interviewer = '$int', ". INTERVIEWERSMST .".interviewername = '$intname', 
						". INTERVIEWERSMST .".interviewerjobtitle = '$intjt', ". INTERVIEWERSMST .".intervieweroffice = '$intofc', ". INTERVIEWERSMST .".interviewdate = '$intdate', 
						". INTERVIEWERSMST .".interviewtime = '$inttime', ". INTERVIEWERSMST .".modifiedby = '$userid', ". INTERVIEWERSMST .".modifieddate = '$today', 
						". INTERVIEWERSMST .".remarks = '$rem', ". INTERVIEWERSMST .".status = '$stat' 
					WHERE ". INTERVIEWERSMST .".interviewid = '$intid' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateInterviewer()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		public function getNewQuestionId($data){
			$userid = $data['userid'];
			$appid = $data['applicantid'];

			$sql = "SELECT ". QUESTIONSMST .".questionid 
					FROM ". QUESTIONSMST ." 
					WHERE ". QUESTIONSMST .".applicantid = '$appid' 
						AND ". QUESTIONSMST .".interviewer = '$userid' 
					ORDER BY ". QUESTIONSMST .".createddate DESC 
					LIMIT 0,1";
			// $res['sql'] = $sql; 
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$qid = $row['questionid'];
			}
			$res['qid'] = $qid;
			return $res;
		}

		public function saveQuestion($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$appid = $data['applicantid'];
			$questionid = $data['questionid'];
			$question = $data['question'];
			$intname = $data['name'];
			$today = TODAY;

			$sql = "INSERT INTO ". QUESTIONSMST ."(applicantid,interviewer,interviewername,questionid,question,createddate) 
					VALUES('$appid','$userid','$intname','$questionid','$question','$today')";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func saveQuestion()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		public function updateQuestion($data){
			$res = array();
			$res['err'] = 0;
			$questionid = $data['questionid'];
			$question = $data['question'];
			$today = TODAY;

			$sql = "UPDATE ". QUESTIONSMST ." 
					SET question = '$question', modifieddate = '$today' 
					WHERE ". QUESTIONSMST .".questionid = '$questionid'";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateQuestion()! " . $this->cn->error;
				goto exitme;
			}

			exitme:
			return $res;
		}

		public function getInterviewers($appid=false){
			$res = array();
			$res['err'] = 0;
			$where = "1";
			if(!empty($appid)){
				$where .= " AND ". INTERVIEWERSMST .".applicantid = '$appid'";
			}

			$sql = "SELECT ". INTERVIEWERSMST .".*
						,DATE_FORMAT(" . INTERVIEWERSMST . ".interviewdate,'%a %d %b %y') AS interviewdt
						,(CASE WHEN ". INTERVIEWERSMST .".interviewtype = 'oo' THEN 'ONE-ON-ONE' 
							WHEN ". INTERVIEWERSMST .".interviewtype = 'ac' THEN 'AUDIO CALL' 
							WHEN ". INTERVIEWERSMST .".interviewtype = 'vc' THEN 'VIDEO CALL' 
							ELSE '' END) AS interviewtypedesc
						,(CASE WHEN ". INTERVIEWERSMST .".status = 0 THEN 'ON GOING' 
							WHEN ". INTERVIEWERSMST .".status = -2 THEN 'REMOVED' 
							ELSE 'DONE' END) AS statusdesc 
					FROM ". INTERVIEWERSMST ." 
					LEFT JOIN ". APPLICANTSMST ." 
						ON ". APPLICANTSMST .".applicantid = ". INTERVIEWERSMST .".applicantid 
					WHERE $where AND ". APPLICANTSMST .".status != 1 ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getInterviewers()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		public function getInterviewer($id){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". INTERVIEWERSMST .".*
						,DATE_FORMAT(" . INTERVIEWERSMST . ".interviewdate,'%a %d %b %y') AS interviewdt
						,(CASE WHEN ". INTERVIEWERSMST .".interviewtype = 'oo' THEN 'ONE-ON-ONE' 
							WHEN ". INTERVIEWERSMST .".interviewtype = 'ac' THEN 'AUDIO CALL' 
							WHEN ". INTERVIEWERSMST .".interviewtype = 'vc' THEN 'VIDEO CALL' 
							ELSE '' END) AS interviewtypedesc
						,(CASE WHEN ". INTERVIEWERSMST .".status = 0 THEN 'ON GOING' 
							WHEN ". INTERVIEWERSMST .".status = -2 THEN 'REMOVED' 
							ELSE 'DONE' END) AS statusdesc 
					FROM ". INTERVIEWERSMST ." 
					WHERE ". INTERVIEWERSMST .".interviewid = '$id' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getInterviewer()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		public function getQuestions($appid){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". QUESTIONSMST .".* 
					FROM ". QUESTIONSMST ." 
					WHERE ". QUESTIONSMST .".applicantid = '$appid' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getQuestions()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		public function getQuestion($id){
			$res = array();
			$res['err'] = 0;

			$sql = "SELECT ". QUESTIONSMST .".* 
					FROM ". QUESTIONSMST ." 
					WHERE ". QUESTIONSMST .".questionid = '$id' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func getQuestion()! " . $this->cn->error;
				goto exitme;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;
			exitme:
			return $res;
		}

		public function updateAppStatus($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['id'];
			$status = $data['status'];
			$userid = $data['userid'];
			$today = TODAY;

			$sql = "UPDATE ". APPLICANTSMST ." 
					SET ". APPLICANTSMST .".status = '$status', ". APPLICANTSMST .".modifiedby = '$userid', ". APPLICANTSMST .".modifieddate = '$today' 
					WHERE ". APPLICANTSMST .".applicantid = '$id' ";
			// $res['sql'] = $sql;
			$qry = $this->cn->query($sql);
			$rows = array();
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = "An error occured in func updateAppStatus()! " . $this->cn->error;
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