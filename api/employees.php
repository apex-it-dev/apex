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
	require_once('models/countries_model.php');
	require_once('models/zktecodevices_model.php');
	require_once('models/compensationnbenefits_model.php');
	require_once('models/leaves_model.php');
	

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
		$val['statFilter'] = $data->statFilter;
		$val['ofc'] = $data->ofc;
		if(strpos($data->ofc,'hk') !== false){
			$val['aba'] = 'abahk';
			$val['ssc'] = 'sschk';
		}
		// $val['userid'] = $data->userid;
		// $val['showAll'] = $data->showAll;

		$ees = new EmployeesModel;
		$eedata = $ees->getAllAbaPeopleWithStatus($val);
		$ees ->closeDB();
		unset($ees);

		$res['eedata']['err'] = $eedata['err'];
		$res['eedata']['rows'] = array();

		foreach ($eedata['rows'] as $eachee) {
			$res['eedata']['rows'][] = array(
				'userid' => $eachee['userid'],
				'designationnamedesc' => $eachee['designationnamedesc'],
				'departmentdesc' => $eachee['departmentdesc'],
				'officename' => $eachee['officename'],
				'joindt' => $eachee['joindt'],
				'statusname' => $eachee['statusname'],
				'office' => $eachee['office'],
				'sesid' => $eachee['sesid'],
				'statusname' => $eachee['statusname'],
				'eename' => $eachee['eename'],
			);
		}


		$ofc = new SalesOfficesModel;
		$offices = $ofc->getSalesOffices();
		$ofc ->closeDB(); 

		foreach ($offices as $key => $office) {
			$res['offices'][] = array(
				'id' 	=> $office['salesofficeid'],
				'ini'	=> $office['description'],
				'name'	=> $office['name']
			);
		}

		return $res;
	}

	function loadDropdown($data){
		$res = array();
		$id = "";
		$profilegroup = $data->profilegroup;

		$dd = new DropdownsModel;
		switch ($profilegroup) {
			case 'personalinfo':
				//nationalities
				$natl = new NationalitiesModel;
				$res['nationalities'] = $natl->getAllNationalities();
				$natl ->closeDB();
				 
				//marital status
				$res['maritalstatus'] = $dd->getMaritalStatuses($id);
				$res['salutations'] = $dd->getSalutations($id);

				break;
			case 'employeedata':
				//office
				$ofc = new SalesOfficesModel;
				$res['offices'] = $ofc->getSalesOffices($id);
				$ofc ->closeDB(); 

				//department
				$dept = new DepartmentsModel;
				$res['departments'] = $dept->getAllDepartments();
				$dept ->closeDB(); 

				$res['eecat'] = $dd->getEmployeeCategories($id);
				$res['eeranks'] = $dd->getEmployeeRankings($id);

				break;
			case 'contactlist':
				
				break;
			case 'profile':
				
				break;

			case 'accountsettings':
				$zktecooffice = new ZKTecoDevicesModel;
				$res['zktecooffices'] = $zktecooffice->getAllZKTecoDevices();
				$zktecooffice->closeDB();
				break;
			default:
				break;
		}
		$dd->closeDB();
		
		//countries
		$countries = new CountriesModel;
		$res['countries'] = $countries->getCountries($id);
		$countries->closeDB();

		$ees = new EmployeesModel;
		$res['eedata'] = $ees->getAllAbaPeople();
		$ees ->closeDB();
		

		return $res;
	}

	function loadJobTitles($data){
		$res = array();
		$deptid = $data->deptid;

		$jobpos = new JobPositionsModel;
		$res['jobpositions'] = $jobpos->getJobTitles($deptid);
		$jobpos->closeDB();

		return $res;
	}


	function saveNewEmployeeInfo($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$val['userid'] = $userid;
		// personal
		$val['salutation'] = $data->salutation;
		$val['lastname'] = $data->lastname;
		$val['firstname'] = $data->firstname;
		$val['cnname'] = $data->cnname;
		$val['nationality'] = $data->nationality;
		$val['maritalstatus'] = $data->maritalstatus;
		$val['birthdate'] = $data->birthdate;
		$val['gender'] = $data->gender;
		$val['abaini'] = $data->abaini;
		$val['govtid'] = $data->govtid;
		$val['passportno'] = $data->passportno;
		$val['issueddate'] = $data->issueddate;
		$val['passportexpdate'] = $data->passportexpdate;
		$val['passissuedcountry'] = $data->passissuedcountry;

		// contact
		$val['eadd'] = $data->eadd;
		$val['mobphone'] = $data->mobphone;
		$val['homephone'] = $data->homephone;
		$val['wechat'] = $data->wechat;
		$val['skype'] = $data->skype;
		$val['whatsapp'] = $data->whatsapp;
		$val['linkedin'] = $data->linkedin;
		$val['presentcity'] = $data->presentcity;
		$val['presentstate'] = $data->presentstate;
		$val['presentcountry'] = $data->presentcountry;
		$val['presentzipcode'] = $data->presentzipcode;
		$val['presentaddr'] = $data->presentaddr;
		$val['emercontactperson'] = $data->emercontactperson;
		$val['emercontactno'] = $data->emercontactno;
		$val['emercontactrelation'] = $data->emercontactrelation;

		// employee
		$val['joineddate'] = $data->joineddate;
		$val['ofc'] = $data->ofc;
		$val['position'] = $data->positions;
		$val['dept'] = $data->department;
		$val['eecat'] = $data->eecat;
		$val['posgrade'] = $data->posgrade;
		$val['reportsto'] = $data->reportsto;
		$val['reportstoindirect'] = $data->reportstoindirect;
		$val['reportstotext'] = $data->reportstotext;
		$val['reportstoindirecttext'] = $data->reportstoindirecttext;
		$val['workeadd'] = $data->workeadd;
		$val['ofcno'] = $data->ofcno;
		$val['workskype'] = $data->workskype;

		// // account settings
		// $val['zkteco_office']= $data->zkteco_office;
		// $val['zkteco_id'] = $data->zkteco_id;

		//other info
		$val['probationperiod'] = $data->probationperiod;
		$val['terminationperiod'] = $data->terminationperiod;
		$val['visaprocessedbyaba'] = $data->visaprocessedbyaba;
		$val['visaexpireddate'] = $data->visaexpireddate;
		$val['probcompletedate'] = $data->probcompletedate;
		$val['lastworkingdate'] = $data->lastworkingdate;
		$val['effectivedate'] = $data->effectivedate;
		
		$val['companynamefirstctrcsigned'] = $data->companynamefirstctrcsigned;
		$val['recenteffectivedatectrc'] = $data->recenteffectivedatectrc;
		$val['curplaceofwork'] = $data->curplaceofwork;

		// //newly added fields
		$val['regularizationdate'] = $data->regularizationdate;
		$val['typeofvisa'] = $data->typeofvisa; 
		$val['startofvisa'] = $data->startofvisa;
		$val['probationenddate'] = $data->probationenddate;
	    $val['startcontractdate'] = $data->startcontractdate;
	    $val['endcontractdate'] = $data->endcontractdate;
		$val['startshift'] = $data->shiftfrom;
		$val['endshift'] = $data->shiftto;
		
		$ees = new EmployeesModel;
		$res['savenew'] = $ees->saveNewEmployee($val);
		// // $res['profilegroup'] = $profilegroup;
		$ees ->closeDB(); 

		return $res;
	}

	function loadCompensationBenefits($data){
		$res = array();
		$userid = $data->userid;
		$indexid = $data->indexid;
		
		$leaveben = new LeavesModel;
		$res['leave_benefits'] = $leaveben->getleaveTypes($userid);
		$val['userid'] = $userid;
		$val['indexid'] = $indexid;
		$leaveben->closeDB();
		
		$compensationbenefits = new CompensationAndBenefits;
		$res['hmo_benefits'] = $compensationbenefits->getHmoBenefits();
		$res['position_history'] = $compensationbenefits->getPositionHistory($val);
		$res['position_list'] = $compensationbenefits->getPositionList();
		$compensationbenefits->closeDB();

		$res['val'] = $val;

		return $res;
	}

	function updateAccountSettings($data){
		$res = array();
		$val = array();
		$profilegroup = 'accountsettings';
		$val['profilegroup'] = $profilegroup;
		$val['userid'] = $data->eeid;
		$val['zkteco_office']= $data->zkteco_office;
		$val['zkteco_id'] = $data->zkteco_id;
		$val['monnthlygrosssalinlocalcurshowninctrc'] = $data->monnthlygrosssalinlocalcurshowninctrc;
		$val['monthlempcontri'] = $data->monthlempcontri;
		$val['monthaplusmedinhkd'] = $data->monthaplusmedinhkd;
		$val['monthlymedinsinlocal'] = $data->monthlymedinsinlocal;
		$val['monthlybusinessexpensesallowan'] = $data->monthlybusinessexpensesallowan;

		$ees = new EmployeesModel;
		$res['updateacctsettings'] = $ees->updateAccountSettings($val);
		$ees->closeDB();

		// $ees = new EmployeesModel;
		// $res['contactupdated'] = $ees->updateEmployeeInfo($val);
		// $ees ->closeDB(); 

		return $res;
	}

	function generateLeaveQuota($data){
		$res = array();
		$eeid = $data->eeid;
		$compensationbenefits = new CompensationAndBenefits;
		$res['leavequotas'] = $compensationbenefits->getLeaveBenefits($eeid);
		$compensationbenefits->closeDB();

		return $res;
	}

	function getLeaveBenefit($data){
		$res = array();
		$val = array();
		$id = $data->id;

		$leaves = new LeavesModel;
		$res['leavebal'] = $leaves->getLeaveCredit($id);
 		$leaves->closeDB();

		return $res;
	}

	function updateLeaveBenefit($data){
		$res = array();
		$val = array();
		$val['userid'] = $data->userid;
		$val['eeid'] = $data->eeid;
		$val['id'] = $data->id;
		$val['entitled'] = $data->entitled;
		$val['status'] = $data->status;

		$leaves = new LeavesModel;
		$res['leavequotas'] = $leaves->updateLeaveBenefit($val);
 		$leaves->closeDB();

		return $res;
	}

	function generateHMOBenefits($data){
		$res = array();
		$eeid = $data->eeid;
		$compensationbenefits = new CompensationAndBenefits;
		$res['hmobenefits'] = $compensationbenefits->getEEHMOBenefits($eeid);
		$compensationbenefits->closeDB();

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