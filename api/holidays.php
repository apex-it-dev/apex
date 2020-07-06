<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/salesoffices_model.php');
	require_once('models/holiday_model.php');
	
	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function getHolidays($data){
		$res = array();
		$val = array();
		$val['currentyear'] = $data->currentyear;

		$holidays = new HolidayModel;
		$res['holidays'] = $holidays->getHolidays($val);
 		$holidays->closeDB();
		
		$ofc = new SalesOfficesModel;
		$res['offices'] = $ofc->getSalesOffices("");
		$ofc ->closeDB(); 

		return $res;
	}

	function updateHolidays($data){
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

		$holidays = new HolidayModel;
		$res['update_holidays'] = $holidays->updateHolidays($val);
		$holidays->closeDB();
		
		return $res;
	}

	function addHolidays($data){
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

		$holidays = new HolidayModel;
		$res['add_holidays'] = $holidays->addHolidays($val);
		$holidays->closeDB();
		
		return $res;
	}

	function deleteHolidays($data){

		$res = array();
		$val = array();

		$val['id'] = $data->id;
		$val['userid'] = $data->userid;

		$holidays = new HolidayModel;
		$res['delete_holidays'] = $holidays->deleteHolidays($val);
		$holidays->closeDB();
		
		return $res;
	}

	function publishHoliday($data){
		$res = array();
		$val = array();

		$val['title'] = $data->title;
		$val['ofc'] = $data->ofc;
		$val['startdate'] = $data->startdate;
		$val['enddate'] = $data->enddate;
		$val['desc'] = $data->desc;
		$val['createdby'] = $data->createdby;
		$val['holidayid'] = $data->holidayid;

		$holidays = new HolidayModel;
		$res['publish_holiday'] = $holidays->publishHoliday($val);
		$holidays->closeDB();
		
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