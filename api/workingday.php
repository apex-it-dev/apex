<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/salesoffices_model.php');
	require_once('models/workingday_model.php');
	
	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function getWorkingdays($data){
		$res = array();
		$val = array();
		$val['currentyear'] = $data->currentyear;

		$workingdays = new WorkingdayModel;
		$res['workingdays'] = $workingdays->getWorkingdays($val);
 		$workingdays->closeDB();
		
		$ofc = new SalesOfficesModel;
		$res['offices'] = $ofc->getSalesOffices("");
		$ofc ->closeDB(); 

		return $res;
	}

	function updateWorkingdays($data){
		$res = array();
		$val = array();

		$val['title'] = $data->title;
		$val['ofc'] = $data->ofc;
		$val['startdate'] = $data->startdate;
		$val['enddate'] = $data->enddate;
		$val['region'] = $data->region;
		$val['desc'] = $data->desc;

		$val['id'] = $data->id;
		$val['userid'] = $data->userid;
		$val['createdby'] = $data->createdby;
		$val['createddate'] = $data->createddate;
		$val['days_count'] = $data->days_count;
		
		$val['modifieddate'] = $data->modifieddate;

		$workingdays = new WorkingdayModel;
		$res['update_workingdays'] = $workingdays->updateWorkingdays($val);
		$workingdays->closeDB();
		
		return $res;
	}

	function addWorkingdays($data){
		$res = array();
		$val = array();
		$val['title'] = $data->title;
		$val['ofc'] = $data->ofc;
		$val['startdate'] = $data->startdate;
		$val['enddate'] = $data->enddate;
		$val['desc'] = $data->desc;
		$val['region'] = $data->region;
		$val['days_count'] = $data->days_count;
		$val['userid'] = $data->userid;
		$val['createddate'] = $data->createddate;

		$workingdays = new WorkingdayModel;
		$res['add_workingdays'] = $workingdays->addWorkingdays($val);
		$workingdays->closeDB();
		
		return $res;
	}

	function deleteWorkingdays($data){

		$res = array();
		$val = array();

		$val['id'] = $data->id;
		$val['userid'] = $data->userid;

		$workingdays = new WorkingdayModel;
		$res['delete_workingdays'] = $workingdays->deleteWorkingdays($val);
		$workingdays->closeDB();
		
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