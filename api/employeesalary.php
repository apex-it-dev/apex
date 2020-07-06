<?php
	require_once('../inc/global.php');
	require_once('../inc/functions.php');
	require_once('classes/class.phpmailer.php');
	require_once('models/database.php');
	require_once('models/dropdowns_model.php');
	require_once('models/employees_model.php');
	require_once('models/sendmail_model.php');
	require_once('models/salesoffices_model.php');
	require_once('models/payroll_model.php');
	require_once('models/employeesalary_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function loadEmployeeList($data){
		$res = array();
		$val = array();
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