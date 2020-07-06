<?php
	class DropdownsModel extends Database{

		private $db = "";
		private $cn = "";

		function __construct(){
			$this->db = new Database();
			$this->cn = $this->db->connect();
		}

		// GET DATA by ALL or ID
		public function getDropDowns($val,$id=""){
			$where = " WHERE " . DROPDOWNSMST . ".dddisplay = '$val'";
			if(!empty($id)){
				$where .= " AND " . DROPDOWNSMST . ".ddid = '$id'";
			}
			$sql = "SELECT " . DROPDOWNSMST . ".*,(CASE WHEN " . DROPDOWNSMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname FROM " . DROPDOWNSMST . $where . " ORDER BY " . DROPDOWNSMST . ".`dddescription`, " . DROPDOWNSMST . ".`ddid`";

			// echo $sql . "<br />";
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		// GET DATA by ALL or ID
		public function getDropDownsOrderBy($val,$id="",$orderby){
			$where = " WHERE " . DROPDOWNSMST . ".dddisplay = '$val'";
			if(!empty($id)){
				$where .= " AND " . DROPDOWNSMST . ".ddid = '$id'";
			}
			if(!empty($orderby)){
				$orderedby = " ORDER BY " . DROPDOWNSMST . "." . $orderby;
			}
			$sql = "SELECT " . DROPDOWNSMST . ".*,(CASE WHEN " . DROPDOWNSMST . ".`status` = 1 THEN 'active' ELSE 'inactive' END) AS statusname FROM " . DROPDOWNSMST . $where . $orderedby;

			// echo $sql . "<br />";
			$rows = array();
			$qry = $this->cn->query($sql);
			if(!$qry){
				echo $this->cn->error;
			}
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			// $this->cn->close();
			return $rows;
		}

		public function getSalutations($id=""){
			$result = "";
			$result = $this->getDropDowns('eesalutation',$id);
			return $result;
		}

		public function getGenders($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('gender',$id,'sort');
			return $result;
		}

		public function getEmployeeStatus($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('eestatus',$id,'sort');
			return $result;
		}

		public function getEmployeeTypes($id=""){
			$result = "";
			$result =  $this->getDropDowns('eetype',$id);
			return $result;
		}

		public function getEmployeeCategories($id=""){
			$result = "";
			$result =  $this->getDropDowns('eecategory',$id);
			return $result;
		}

		public function getEmployeeSalutations($id=""){
			$result = "";
			$result =  $this->getDropDowns('eesalutation',$id);
			return $result;
		}
		public function getBloodGroups($id=""){
			$result = "";
			$result =  $this->getDropDowns('bloodgroup',$id);
			return $result;
		}
		public function getMaritalStatuses($id=""){
			$result = "";
			$result =  $this->getDropDowns('maritalstatus',$id);
			return $result;
		}
		public function getUserAccessLvls($id=""){
			$result = "";
			$result =  $this->getDropDowns('accesslvl',$id);
			return $result;
		}
		public function getAbvtGlossaryType($id=""){
			$result = "";
			$result =  $this->getDropDowns('abvtglossary',$id);
			return $result;	
		}
		public function getContactCategories($id=""){
			$result = "";
			$result =  $this->getDropDowns('contactcategory',$id);
			return $result;	
		}
		public function getClientTypes($id=""){
			$result = "";
			$result =  $this->getDropDowns('accounttypes',$id);
			return $result;		
		}
		public function getClientStatuses($id=""){
			$result = "";
			$result =  $this->getDropDowns('clientstatuses',$id);
			return $result;			
		}
		public function getClientContactTypes($id=""){
			$result = "";
			$result =  $this->getDropDowns('clientcontacttypes',$id);
			return $result;			
		}
		public function getClientAddressTypes($id=""){
			$result = "";
			$result =  $this->getDropDowns('clientaddresstypes',$id);
			return $result;			
		}
		public function getRequestTypes($id=""){
			$result = "";
			$result =  $this->getDropDownsOrderBy('mkgrequesttypes',$id,"dddescription");
			return $result;			
		}
		public function getRequestStatuses($id=""){
			$result = "";
			$result =  $this->getDropDowns('mkgreqstatuses',$id);
			return $result;			
		}
		public function getReqPriorityTypes($id=""){
			$result = "";
			$result =  $this->getDropDowns('mkgreqpriorities',$id);
			return $result;			
		}
		public function getSupplierProductTypes($id=""){
			$result = "";
			$result = $this->getDropDowns('supproducttypes',$id);
			return $result;
		}
		public function getCDMTaskTypes($id=""){
			$result = "";
			$result = $this->getDropDowns('cdmtasktypes',$id);
			return $result;	
		}
		public function getTDlCategories($id=""){
			$result = "";
			$result = $this->getDropDowns('tdlcategory',$id);
			return $result;	
		}
		public function getTDlStatusPercent($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('tdlstatus',$id,'sort');
			return $result;	
		}
		public function getCltProstTitles($id=""){
			$result = "";
			$result = $this->getDropDowns('cltprosttitles',$id);
			return $result;	
		}
      	public function getTDLTaskTypes($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('tdltasktype',$id,'sort');
			return $result;	
		}
		public function getCDMresultexpected($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('cdmresultexpected',$id,'sort');
			return $result;	
		}

		public function getAffinities($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('cdmaffinity',$id,'sort');
			return $result;	
		}

		public function getUsefulLinkCategories($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('tdlusefullinkcats',$id,'dddescription');
			return $result;	
		}

		public function getProductTypes($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('cdmproducttypes',$id,'dddescription');
			return $result;	
		}
		public function getEmployeeRankings($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('eerankings',$id,'sort');
			return $result;	
		}
		public function getLeaveType($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('leavetype',$id,'sort');
			return $result;	
		}
		public function getLeaveStatus($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('leavestatus',$id,'sort');
			return $result;	
		}
		public function getLeaveDuration($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('leaveduration',$id,'sort');
			return $result;	
		}
		public function getMiscellaneous($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('misctype',$id,'sort');
			return $result;	
		}
		public function getSalaryAdjustmentType($id=""){
			$result = "";
			$result = $this->getDropDownsOrderBy('saladjtype',$id,'sort');
			return $result;	
		}

		public function getDVCities(){
			$result = array();
			$result[] = array("cityid" => "CT001", "cityname" => "Beijing");
			$result[] = array("cityid" => "CT002", "cityname" => "China");
			$result[] = array("cityid" => "CT003", "cityname" => "Denmark");
			$result[] = array("cityid" => "CT004", "cityname" => "Dublin");
			$result[] = array("cityid" => "CT005", "cityname" => "France");
			$result[] = array("cityid" => "CT018", "cityname" => "Foshan");
			$result[] = array("cityid" => "CT006", "cityname" => "Guangzhou");
			$result[] = array("cityid" => "CT007", "cityname" => "Hong Kong");
			$result[] = array("cityid" => "CT008", "cityname" => "London");
			$result[] = array("cityid" => "CT009", "cityname" => "Malaysia");
			$result[] = array("cityid" => "CT010", "cityname" => "Paris");
			$result[] = array("cityid" => "CT011", "cityname" => "Philippines");
			$result[] = array("cityid" => "CT012", "cityname" => "Shanghai");
			$result[] = array("cityid" => "CT013", "cityname" => "Shenzhen");
			$result[] = array("cityid" => "CT014", "cityname" => "Singapore");
			$result[] = array("cityid" => "CT015", "cityname" => "Switzerland");
			$result[] = array("cityid" => "CT016", "cityname" => "United Kingdom");
			$result[] = array("cityid" => "CT017", "cityname" => "United States");

			return $result;
		}
		public function getDVCountries(){
			$result = array();
			$result[] = array("countryid" => "CTRY001", "countryname" => "China");
			$result[] = array("countryid" => "CTRY002", "countryname" => "Denmark");
			$result[] = array("countryid" => "CTRY003", "countryname" => "France");
			$result[] = array("countryid" => "CTRY004", "countryname" => "Hong Kong");
			$result[] = array("countryid" => "CTRY005", "countryname" => "Malaysia");
			$result[] = array("countryid" => "CTRY006", "countryname" => "Philippines");
			$result[] = array("countryid" => "CTRY007", "countryname" => "Singapore");
			$result[] = array("countryid" => "CTRY008", "countryname" => "Switzerland");
			$result[] = array("countryid" => "CTRY009", "countryname" => "United Kingdom");
			$result[] = array("countryid" => "CTRY010", "countryname" => "United States");

			return $result;
		}

		public function getNationalitiesList(){
			$res = array();
			$rows = array();
			$sql = "SELECT ". NATIONALITYMST .".*
					FROM ". NATIONALITYMST ." WHERE 1 ";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			return $res;
		}

		public function getEthnicitiesList(){
			$res = array();
			$rows = array();
			$sql = "SELECT ". ETHNICITYMST .".*
					FROM ". ETHNICITYMST ." WHERE 1 ";
			$qry = $this->cn->query($sql);
			while($row = $qry->fetch_array(MYSQLI_ASSOC)){
				$rows[] = $row;
			}

			$res['rows'] = $rows;

			return $res;
		}

		public function closeDB(){
			$this->cn->close();
		}
	}
?>