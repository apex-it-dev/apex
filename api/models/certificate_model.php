<?php
	class CertificateModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		function getCertificates($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];

			$sql = "SELECT " . CERTIFICATIONMST . ".* 
					FROM " . CERTIFICATIONMST . " 
					WHERE " . CERTIFICATIONMST . ".userid = '$userid' AND " . CERTIFICATIONMST . ".status = 1";

			// $res['sql'] = $sql;
			// $res['function'] = __FUNCTION__;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] = 'ERROR on ' . __FUNCTION__ . ':' . $this->cn->error;
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

		function addCertificates($data){
			$res = array();
			$res['err'] = 0;
			$userid = $data['userid'];
			$certificatename = $data['certificatename'];
			$issuingorganization = $data['certificateorganization'];
			$issuedmonth = $data['certificateissuemonth'];
			$issuedyear = $data['certificateissueyear'];
			$expirationmonth = $data['certificateexpirymonth'];
			$expirationyear = $data['certificateexpiryyear'];
			$noExpiry = $data['certificatenoexpiry'];
			$createdby = $data['addedby'];
			$createddate = TODAY;
			
			if($noExpiry == 1){
				$sql = "INSERT INTO " 
				. CERTIFICATIONMST . " (userid,certificationname,issuingorganization,issuedmonth,issuedyear,noExpiry,createdby,createddate) 
								VALUES('$userid','$certificatename','$issuingorganization','$issuedmonth','$issuedyear','$noExpiry','$createdby','$createddate')";
			} else {
				$sql = "INSERT INTO " 
				. CERTIFICATIONMST . " (userid,certificationname,issuingorganization,issuedmonth,issuedyear,expirationmonth,expirationyear,noExpiry,createdby,createddate) 
								VALUES('$userid','$certificatename','$issuingorganization','$issuedmonth','$issuedyear','$expirationmonth','$expirationyear','$noExpiry','$createdby','$createddate')";
			}

			// $res['sql'] = $sql;
			// $res['function'] = __FUNCTION__;
			$qry = $this->cn->query($sql);
			$res['insertedid'] = mysqli_insert_id($this->cn);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] =  ' ' . __FUNCTION__ . $this->cn->error;
				// exit();
			}
			return $res;
		}

		function addAttachment($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['insertto'];
			$filename = $data['attachment'];
			

			$columns = "" . CERTIFICATIONMST . ".attachments = '$filename'";
			$sql = "UPDATE " . CERTIFICATIONMST . " 
					SET  $columns WHERE " . CERTIFICATIONMST . ".id = '$id' " ;

			// $res['sql'] = $sql;
			// $res['function'] = __FUNCTION__;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] =  ' ' . __FUNCTION__ . $this->cn->error;
				// exit();
			}
			return $res;
		}

		function updateAttachment($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['insertto'];
			$filename = $data['attachment'];
			

			$columns = "" . CERTIFICATIONMST . ".attachments = '$filename'";
			$sql = "UPDATE " . CERTIFICATIONMST . " 
					SET  $columns WHERE " . CERTIFICATIONMST . ".id = '$id' " ;

			// $res['sql'] = $sql;
			// $res['function'] = __FUNCTION__;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] =  ' ' . __FUNCTION__ . $this->cn->error;
				// exit();
			}
			return $res;
		}

		function updateCertificates($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['id'];
			$userid = $data['userid'];
			$certificatename = $data['certificatename'];
			$issuingorganization = $data['certificateorganization'];
			$issuedmonth = $data['certificateissuemonth'];
			$issuedyear = $data['certificateissueyear'];
			$expirationmonth = $data['certificateexpirymonth'];
			$expirationyear = $data['certificateexpiryyear'];
			$noExpiry = $data['certificatenoexpiry'];
			$modifiedby = $data['modifiedby'];
			$modifieddate = TODAY;

			if($noExpiry == 1){
				$columns = "" . CERTIFICATIONMST . ".certificationname = '$certificatename',
							" . CERTIFICATIONMST . ".issuingorganization = '$issuingorganization',
							" . CERTIFICATIONMST . ".issuedmonth = '$issuedmonth',
							" . CERTIFICATIONMST . ".issuedyear = '$issuedyear',
							" . CERTIFICATIONMST . ".expirationmonth = NULL,
							" . CERTIFICATIONMST . ".expirationyear = NULL,
							" . CERTIFICATIONMST . ".noExpiry = '$noExpiry',
							" . CERTIFICATIONMST . ".modifiedby = '$modifiedby',
							" . CERTIFICATIONMST . ".modifieddate = '$modifieddate'
							";
			} else {
				$columns = "" . CERTIFICATIONMST . ".certificationname = '$certificatename',
							" . CERTIFICATIONMST . ".issuingorganization = '$issuingorganization',
							" . CERTIFICATIONMST . ".issuedmonth = '$issuedmonth',
							" . CERTIFICATIONMST . ".issuedyear = '$issuedyear',
							" . CERTIFICATIONMST . ".expirationmonth = '$expirationmonth',
							" . CERTIFICATIONMST . ".expirationyear = '$expirationyear',
							" . CERTIFICATIONMST . ".noExpiry = '$noExpiry',
							" . CERTIFICATIONMST . ".modifiedby = '$modifiedby',
							" . CERTIFICATIONMST . ".modifieddate = '$modifieddate'
							";
			}
			$sql = "UPDATE " . CERTIFICATIONMST . " 
					SET  $columns WHERE " . CERTIFICATIONMST . ".id = '$id' " ;

			// $res['sql'] = $sql;
			// $res['function'] = __FUNCTION__;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] =  ' ' . __FUNCTION__ . $this->cn->error;
				// exit();
			}
			return $res;
		}


		function deleteCertificates($data){
			$res = array();
			$res['err'] = 0;
			$id = $data['id'];
			$modifiedby = $data['userid'];
			$modifieddate = TODAY;

			$columns = "" . CERTIFICATIONMST . ".status = 0,
						" . CERTIFICATIONMST . ".modifiedby = '$modifiedby',
						" . CERTIFICATIONMST . ".modifieddate = '$modifieddate'
						";
			
			$sql = "UPDATE " . CERTIFICATIONMST . " 
					SET  $columns WHERE " . CERTIFICATIONMST . ".id = '$id' " ;

			// $res['sql'] = $sql;
			// $res['function'] = __FUNCTION__;
			$qry = $this->cn->query($sql);
			if(!$qry){
				$res['err'] = 1;
				$res['errmsg'] =  ' ' . __FUNCTION__ . $this->cn->error;
				// exit();
			}
			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>