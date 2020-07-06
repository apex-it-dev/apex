<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('models/database.php');
	require_once('models/employees_model.php');
	require_once('models/jobpositions_model.php');
	require_once('models/salesoffices_model.php');
	require_once('models/departments_model.php');
	require_once('models/dropdowns_model.php');
	require_once('models/nationalities_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function loadEmployeeData($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$profilegroup = $data->profilegroup;
		$val['userid'] = $userid;
		$val['profilegroup'] = $profilegroup;
		$val['sesid'] = $data->sesid;
		$val['action'] = $data->action;

		$ees = new EmployeesModel;
		$res['eedata'] = $ees->getEmployeeData_org($val); 
		$abappl = $ees->getAllAbaPeople();
		$ees ->closeDB(); 
		unset($ees);

		$res['abappl']['rows'] = array();
		$res['abappl']['err'] = $abappl['err'];
		foreach ($abappl['rows'] as $key => $ee) {
			$res['abappl']['rows'][] = array(
				'userid'				=>	$ee['userid'],
				'abaini'				=>	$ee['abaini'],
				'eename'				=>	$ee['eename'],
				'designationnamedesc'	=>	$ee['designationnamedesc'],
				'officename'			=>	$ee['officename']
			);
		}

		return $res;
	}

	function getOrgChart($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$profilegroup = $data->profilegroup;
		$val['userid'] = $userid;
		$val['profilegroup'] = $profilegroup;

		$ees = new EmployeesModel;
		$orgchart = $ees->getAllAbaPeopleOrgChart();
		$ees ->closeDB(); 

		

		$protocol = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://';
		$url = $protocol . $_SERVER["SERVER_NAME"] . '/' . explode('/', $_SERVER['PHP_SELF'])[1];
		
		$orgdata = $orgchart['rows'];
		$imgsrc = 'img/ees';
		$active_ees = array_map(function($each_ee){ return $each_ee['abaini']; }, $orgdata);
		$hasunassigned = false;
		foreach ($orgdata as $key => $org) {
			$desig = $org['designation'] == "" || $org['designation'] == null ? null : $org['designationnamedesc'];
			$avatar = "$url/$imgsrc/default.svg";
			if(!($org['avatarorig'] == "" || $org['avatarorig'] == null)) {
				$imgpath = explode('?' , $org['avatarorig'])[0];
				if(file_exists("../$imgsrc/$imgpath")) $avatar = "$url/$imgsrc/$imgpath";
			}
			if($org['reportstoini'] == null){
				$res['orgchartdata'][] = array(	
												"id" 		=> $org['abaini'],
												"ini" 		=> $org['abaini'],
												"name" 		=> $org['fname'] .' '. $org['lname'],
												"title" 	=> $desig,
												"img" 		=> $avatar,
												"office" 	=> $org['officename'],);
			} else {
				if(!$hasunassigned) $hasunassigned = !in_array($org['reportstoini'],$active_ees,true);
				$res['orgchartdata'][] = array(	
												"id" 		=> $org['abaini'],
												"pid" 		=> !in_array($org['reportstoini'],$active_ees,true) ? 'unassigned' : $org['reportstoini'],
												"ini" 		=> $org['abaini'],
												"name" 		=> $org['fname'] .' '. $org['lname'],
												"title" 	=> $desig,
												"img" 		=> $avatar,
												"office" 	=> $org['officename'],);
			}
		}
		
		if($hasunassigned) $res['orgchartdata'][] = array(	
			"id" 		=> 'unassigned',
			"pid" 		=> 'unassigned',
			"ini" 		=> 'unassigned',
			"tags" 		=> ['na'],
			"name" 		=> 'unassigned',
			"title" 	=> 'unassigned',
			"img" 		=> "$imgsrc/default.svg",
			"office" 	=> 'unassigned');
		return $res;
	}

	function searchEmployee($data){
		$res = array();
		$search = $data->search;
		$val['txtData'] = $search;

		$ees = new EmployeesModel;
		$res['abappl'] = $ees->searchAbacarian($val); 
		$ees ->closeDB(); 

		return $res;
	}

	function getEmployeeInfo($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$profilegroup = $data->profilegroup;
		$val['userid'] = $userid;
		$val['profilegroup'] = $profilegroup;
		$val['sesid'] = $data->sesid;
		$val['action'] = $data->action;

		$ees = new EmployeesModel;
		$res['eedata'] = $ees->getEmployeeData($val); 
		// $res['reportstoname'] = $ees->getActiveabaPeopleWithId($res['eedata'][0]['reportstoid']);
		$ees ->closeDB(); 

		return $res;
	}
	
	// required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    echo json_encode($result);
?>