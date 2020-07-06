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
	require_once('models/certificate_model.php');
	require_once('models/abauser_model.php');
	require_once('models/positionhistory_model.php');

	$result = array();
	$json = json_decode(file_get_contents("php://input"))->data;

	if(!empty($json)){
	  $f = $json->f;
	  $result = $f($json);
	  // $result = $json;
	}

	function loadEmployeeProfile($data){
		$res = array();
		$val = array();
		$userid = $data->userid;
		$profilegroup = $data->profilegroup;
		$id = "";

		$val['userid'] = $userid;
		$val['profilegroup'] = $profilegroup;
		$val['sesid'] = $data->sesid;
		$val['action'] = $data->action;

		$ees = new EmployeesModel;
		$res['eedata'] = $ees->getEmployeeData($val);
		$ees->closeDB();

		$deptid = $res['eedata']['rows'][0]['department'];
		$jobpos = new JobPositionsModel;
		$res['jobpositions'] = $jobpos->getJobTitles($deptid);
		$jobpos->closeDB(); 

		$country = new CountriesModel;
		$res['countries'] = $country->getCountries();
		$country->closeDB();
		
		$avatarimg = $res['eedata']['rows'][0]['avatarorig'];
		$avatarorig = strpos($avatarimg,"?") > 0 ? explode("?",$avatarimg)[0] : $avatarimg;
		$avatar = '../img/ees/'. $avatarorig;

		if($avatarorig == NULL){
			$res['avatar'] = IMAGES .'ees/default.svg';
		}else{
			if(file_exists($avatar)){
				$res['avatar'] = IMAGES .'ees/'. $res['eedata']['rows'][0]['avatarorig'];
			}else{
				$res['avatar'] = IMAGES .'ees/default.svg';
			}
		}
		return $res;
	}

	function updateEmployeeInfo($data){
		$res = array();
		$val = array();

		$userid = $data->userid;
		$profilegroup = $data->profilegroup;
		$val['userid'] = $userid;
		$val['eeid'] = $data->eeid;
		$val['profilegroup'] = $profilegroup;

		// personal
		$val['salutationid'] = $data->salutationid;
		$val['lastname'] = $data->lastname;
		$val['firstname'] = $data->firstname;
		$val['cnname'] = $data->cnname;
		$val['nationality'] = $data->nationality;
		$val['maritalstatus'] = $data->maritalstatus;
		$val['birthdate'] = $data->birthdate;
		$val['gender'] = $data->gender;
		$val['abaini'] = $data->abaini;
		$val['govtidsocsec'] = $data->govtidsocsec;
		$val['passportno'] = $data->passportno;
		$val['passportissueddate'] = $data->passportissueddate;
		$val['passportexpiry'] = $data->passportexpiry;
		$val['passportissuancecountry'] = $data->passportissuancecountry;

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
		$val['eestatus'] = $data->eestatus;
		$val['startshift'] = $data->shiftfrom;
		$val['endshift'] = $data->shiftto;

		$val['probationcompletiondate'] = $data->probationcompletiondate;
		$val['lastworkingday'] = $data->lastworkingday;
		$val['effectivedate'] = $data->effectivedate;
		$val['monthlygrosssalaryinlocalcurrencyshownincontract'] = $data->monthlygrosssalaryinlocalcurrencyshownincontract;
		$val['monthlyemployerscontributionmpfmfpsss'] = $data->monthlyemployerscontributionmpfmfpsss;
		$val['monthlyaplusmedicalinsuranceinhkd'] = $data->monthlyaplusmedicalinsuranceinhkd;
		$val['monthlymedicalinsuranceinlocal'] = $data->monthlymedicalinsuranceinlocal;
		$val['monthlybusinessexpensesallowanceincontractinlocal'] = $data->monthlybusinessexpensesallowanceincontractinlocal;
		$val['companynameof1stcontractsigned'] = $data->companynameof1stcontractsigned;
		$val['dateofmostrecentcontracteffective'] = $data->dateofmostrecentcontracteffective;
		$val['actualplaceofcurrentwork'] = $data->actualplaceofcurrentwork;

		// account settings
		$val['zkteco_office']= $data->zkteco_office;
		$val['zkteco_id'] = $data->zkteco_id;

		// compensation and benefits
		// $val['leavebenefitsyear'] = $data->leavebenefitsyear;
		// $val['benefitlistdatatable'] = $data->benefitlistdatatable;


		$val['probationperiod'] = $data->probationperiod;
		$val['terminationperiod'] = $data->terminationperiod;
		$val['visaprocessedbyaba'] = $data->visaprocessedbyaba;
		$val['visaexpireddate'] = $data->visaexpireddate;

		$val['probationenddate'] = $data->probationenddate;
		$val['regularizationdate'] = $data->regularizationdate;
		$val['typeofvisa'] = $data->typeofvisa; 
		$val['startofvisa'] = $data->startofvisa;
	    $val['startcontractdate'] = $data->startcontractdate;
	    $val['endcontractdate'] = $data->endcontractdate;

		$ees = new EmployeesModel;
		$res['contactupdated'] = $ees->updateEmployeeInfo($val);
		$res['profilegroup'] = $profilegroup;
		$ees->closeDB(); 

		$user = new abaUserModel;
		$res['updatestatus'] = $user->userStatus($val);
		$user->closeDB();
		unset($user);

		// $leaveben = new CompensationAndBenefits;
		// $res['leaveben'] = $leaveben->updateLeaveBenefits($val,$userid);
		// $leaveben ->closeDB();

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

				//abappl

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
			case 'compensationandbenefits';
				//department
				$dept = new DepartmentsModel;
				$res['departments'] = $dept->getAllDepartments();
				$dept ->closeDB(); 
				break;
			default:
				break;
		}
		$dd->closeDB();
		
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

	function loadCompensationBenefits($data){
		$res = array();
		$userid = $data->userid;
		$indexid = $data->indexid;
		$sesid = $data->sesid;
		
		// $leaveben = new LeavesModel;
		$val['userid'] = $userid;
		$val['indexid'] = $indexid;
		$val['sesid'] = $sesid;
		// $leaveben->closeDB();
		
		$compensationbenefits = new CompensationAndBenefits;
		$res['leave_credits'] = $compensationbenefits->getleaveTypes($sesid);
		$res['leave_benefits'] = $compensationbenefits->getLeaveTypeBenefits();
		$res['leave_pending'] = $compensationbenefits->getPendingLeaveRequests($val);
		$res['hmo_benefits'] = $compensationbenefits->getHmoBenefits();
		$compensationbenefits->closeDB();

		$positionhistory = new PositionHistory;
		$res['position_history'] = $positionhistory->getPositionHistoryTbl($val);
		$res['position_list'] = $positionhistory->getPositionList();
		$positionhistory->closeDB();
		unset($positionhistory);

		$res['val'] = $val;

		return $res;
	}


	function updatePositionHistory($data){
		$res = array();
		$val = array();

		$val['userid'] =  $data->userid;
		$val['eeid'] =  $data->eeid;
		$val['indexid'] = $data->indexid;
		$val['position_n'] = $data->position_n;
		$val['salary_n'] = $data->salary_n;
		$val['effectivedate_n'] = $data->effectivedate_n;
		$val['enddate_n'] = $data->enddate_n;
		$val['remarks'] = $data->remarks;

		$positionhistory = new PositionHistory;
		$res['pos_history'] = $positionhistory->updatePositionHistory($val);
		$positionhistory->closeDB();

		return $res;
	}

	function deletePositionHistory($data) {
		$res = array();
		$val = array();

		$val['userid'] =  $data->userid;
		$val['eeid'] =  $data->eeid;
		$val['indexid'] = $data->indexid;

		$positionhistory = new PositionHistory;
		$res['delete_history'] = $positionhistory->deletePositionHistory($val);
		$positionhistory->closeDB();
		unset($positionhistory);

		return $res;
	}

	function addPositionHistory($data) {
		$res = array();
		$val = array();

		$val['userid'] =  $data->userid;
		$val['eeid'] =  $data->eeid;
		$val['position_n'] = $data->position_n;
		$val['salary_n'] = $data->salary_n;
		$val['effectivedate_n'] = $data->effectivedate_n;
		$val['enddate_n'] = $data->enddate_n;
		$val['remarks'] = $data->remarks;

		$positionhistory = new PositionHistory;
		$res['pos_history'] = $positionhistory->addPositionHistory($val);
		$positionhistory->closeDB();

		return $res;
	}

	function checkini($data){
		// $res = array();
		$abaini = $data->inival;

		$checkini = new EmployeesModel;
		$isExist = $checkini->checkini($abaini);
		$checkini->closeDB();

		return $isExist;
	}

	function getCertificates($data){
		$res = array();
		$val = array();
		$val['userid'] = $data->eeid;

		$certificates = new CertificateModel;
		$res['certificates'] = $certificates->getCertificates($val);
		$certificates->closeDB();
		
		return $res;
	}

	function addCertificates($data){
		$res = array();
		$val = array();
		$val['userid'] = $data->eeid;
		$val['certificatename'] = $data->certificatename;
		$val['certificateorganization'] = $data->certificateorganization;
		$val['certificateissuemonth'] = $data->certificateissuemonth;
		$val['certificateissueyear'] = $data->certificateissueyear;
		$val['certificateexpirymonth'] = $data->certificateexpirymonth;
		$val['certificateexpiryyear'] = $data->certificateexpiryyear;
		$val['certificatenoexpiry'] = $data->certificatenoexpiry;
		$val['addedby'] = $data->addedby;

		$certificates = new CertificateModel;
		$res['certificates'] = $certificates->addCertificates($val);
		
		// If saving the certificate succeeded, proceed with uploading
		if($res['certificates']['err'] == 0){

			$filelist = array();
			$idfolder = $data->addedby;
			$tmppathfiles =  $_SERVER['DOCUMENT_ROOT'] . '/aces/upload/tmp/' . $idfolder. '/' . $idfolder . '*.*';
			$fileInPath = glob($tmppathfiles);
			if(!empty($fileInPath)){
				foreach ($fileInPath as $filepath) {
					$filelist['docs'][] = $filepath;
					$filelist['filelist'][] = basename($filepath);
				}

				$finalpath =  $_SERVER['DOCUMENT_ROOT'] . '/aces/upload/certification_attachment_files/' . $data->eeid;
				if(!file_exists($finalpath)){
					mkdir($finalpath, 0777);
				}
				
				$filepath = $filelist['docs'][0];
				$filename = $filelist['filelist'][0];
				if(rename($filepath, $finalpath .'/'. $filename)){
					$res['uploaded'] = true;
				} else {
					$res['uploaded'] = false;
				}
				if($res['uploaded']){
					$val['attachment'] = $filename;
					$val['insertto'] = $res['certificates']['insertedid'];
					$res['fileupload'] = $certificates->addAttachment($val);
				}
			}
		}
		$certificates->closeDB();
		return $res;
	}

	function updateCertificates($data){
		$res = array();
		$val = array();
		$val['id'] = $data->id;
		$val['userid'] = $data->eeid;
		$val['certificatename'] = $data->certificatename;
		$val['certificateorganization'] = $data->certificateorganization;
		$val['certificateissuemonth'] = $data->certificateissuemonth;
		$val['certificateissueyear'] = $data->certificateissueyear;
		$val['certificateexpirymonth'] = $data->certificateexpirymonth;
		$val['certificateexpiryyear'] = $data->certificateexpiryyear;
		$val['certificatenoexpiry'] = $data->certificatenoexpiry;
		$val['modifiedby'] = $data->modifiedby;
		// $val['attachmentname'] = $data->attachmentname;

		$certificates = new CertificateModel;
		$res['certificates'] = $certificates->updateCertificates($val);
		
		// If saving the certificate succeeded, proceed with uploading
		if($res['certificates']['err'] == 0){

			$filelist = array();
			$idfolder = $data->modifiedby;
			$tmppathfiles =  $_SERVER['DOCUMENT_ROOT'] . '/aces/upload/tmp/' . $idfolder. '/' . $idfolder . '*.*';
			$fileInPath = glob($tmppathfiles);
			if(!empty($fileInPath)){
				foreach ($fileInPath as $filepath) {
					$filelist['docs'][] = $filepath;
					$filelist['filelist'][] = basename($filepath);
				}

				$finalpath =  $_SERVER['DOCUMENT_ROOT'] . '/aces/upload/certification_attachment_files/' . $data->eeid;
				if(!file_exists($finalpath)){
					mkdir($finalpath, 0777);
				}
				
				$filepath = $filelist['docs'][0];
				$fileNewAttachment = explode('.', $filelist['filelist'][0]);
				$fileNewName = $fileNewAttachment[0];
				$fileNewType = $fileNewAttachment[count($fileNewAttachment)-1];

				$fileCurrentAttachment = explode('.',$data->attachmentname);
				$fileCurrentName = $fileCurrentAttachment[0];
				$fileCurrentType = $fileCurrentAttachment[count($fileCurrentAttachment)-1];

				$filename = $fileCurrentName . '.' . $fileNewType;
				unlink($finalpath .'/'. $data->attachmentname);
				if(rename($filepath, $finalpath .'/'. $filename)){
					$res['uploaded'] = true;
				} else {
					$res['uploaded'] = false;
				}
				if($res['uploaded']){
					$val['attachment'] = $filename;
					$val['insertto'] = $data->id;
					$res['fileupload'] = $certificates->updateAttachment($val);
				}
			}
		}
		$certificates->closeDB();
		
		return $res;
	}
	
	function deleteCertificates($data){
		$res = array();
		$val = array();
		$val['id'] = $data->id;
		$val['userid'] = $data->eeid;

		$certificates = new CertificateModel;
		$res['certificates'] = $certificates->deleteCertificates($val);
		$certificates->closeDB();
		
		return $res;
	}

	function getLeaveCreditProfile($data){
		$res = array();
		$val = array();
		$val['userid'] = $data->userid;
		$val['creditini'] = $data->creditini;
		$val['fiscalyr'] = $data->fiscalyr;
		

		$leaves = new CompensationAndBenefits;
		$res['leavebal'] = $leaves->getLeaveCreditsProfile($val);

		$res['isExist'] = 1;
		if(count($res['leavebal']['rows']) == 0){
			$res['isExist'] = 0;
			$res['leavebal'] = $leaves->getBenefitsProfile($val);
			$res['leavebal']['userid'] = $val['userid'];
			$res['leavebal']['fiscalyr'] = $val['fiscalyr'];
		}

		$leaves->closeDB();

		return $res;
	}

	function updateLeaveBenefitProfile($data){
		$res = array();
		$val = array();
		
		$val['userid'] = $data->userid;
		$val['eeid'] = $data->eeid;
		$val['leavetypeid'] = $data->leavetypeid;
		$val['entitled'] = $data->entitled;
		// $val['taken'] = $data->taken;
		$val['status'] = $data->status;
		$val['fiscalyr'] = $data->fiscalyr;
		

		$leaves = new CompensationAndBenefits;
		$res['leavebal'] = $leaves->updateLeaveBenefitProfile($val);
 		$leaves->closeDB();

		return $res;
	}

	function saveLeaveBenefitProfile($data){
		$res = array();
		$val = array();
		
		$val['userid'] = $data->userid;
		$val['eeid'] = $data->eeid;
		$val['leavetypeid'] = $data->leavetypeid;
		$val['entitled'] = $data->entitled;
		// $val['taken'] = $data->taken;
		$val['status'] = $data->status;
		$val['fiscalyr'] = $data->fiscalyr;
		$val['creditini'] = $data->creditini;
		

		$leaves = new CompensationAndBenefits;
		$res['leavebal'] = $leaves->saveLeaveBenefitProfile($val);
 		$leaves->closeDB();

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