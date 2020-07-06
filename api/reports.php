<?php
    // include database and object files
    require_once('../inc/global.php');
    require_once('models/database.php');
    require_once('../inc/functions.php');
    require_once('inc/class.phpmailer.php');
    require_once('models/reports_model.php');
    require_once('models/salesoffices_model.php');
    require_once('models/departments_model.php');
    require_once('models/jobpositions_model.php');
    require_once('models/dropdowns_model.php');
    require_once('models/employees_model.php');

    $result = array();
    $json = json_decode(file_get_contents("php://input"))->data;
    
    if(!empty($json)){
      $f = $json->f;
      $result = $f($json);
    //   $result = $json;
    }

    function getEmployeeReports($data) {
        $res = array();
        $val = array();

        $data->filters->officeid = array();
        $offices = new SalesOfficesModel;
        if($data->filters->office != ''){
            $getofcs = $offices->getIncOfcs($data->filters->officename);

            foreach ($getofcs['rows'] as $ofc) {
                $data->filters->officeid[] = $ofc['salesofficeid'];
            } 
            unset($getofcs);
        } else {
            foreach($data->filters->ofc_access as $ofc_item) {
                $getofcsid = $offices->getSalesOfficeByOfcId($ofc_item);
                $data->filters->officeid[] = $getofcsid['salesofficeid'];
            }
            unset($getofcs);
        }
        $offices->closeDB();
        unset($offices);

        $data->filters->hierarchy = getHierarchy($data->filters->direct);
        $val['filters'] = $data->filters;
        $draw = $data->draw;
        $start = $data->start;
        $recordlength = $data->length;
        $nextrecord = $start+$recordlength;
        
        $reportsmodel = new ReportsModel;
        $getrecord = $reportsmodel->getEmployeeReports($val);
        $reportsmodel->closeDB();
        unset($reportsmodel);

        $eerecords = $getrecord['rows'];
        unset($getrecord['rows']);
        $maxrecord = count($eerecords);
        $tobesorted = $data->columns[$data->order[0]->column]->name;
        $sortorder = $data->order[0]->dir;
        usort($eerecords, function($a, $b) use ($tobesorted, $sortorder) {
            if(strpos($tobesorted, 'date') !== FALSE){
                    return $sortorder == 'asc' ? strtotime($a[$tobesorted]) - strtotime($b[$tobesorted]) : strtotime($b[$tobesorted]) - strtotime($a[$tobesorted]);
            } else {
                    return $sortorder == 'asc' ? strcmp($a[$tobesorted], $b[$tobesorted]) : strcmp($b[$tobesorted], $a[$tobesorted]);
            }
        });
        $newarray = array_slice($eerecords, $start, $recordlength);
        
        $res = array(
            "draw"              => $draw,
            "recordsTotal"      => $maxrecord,
            "recordsFiltered"   => $maxrecord,
            "data"              => $newarray,
            // "sql"               => $getrecord['sql'],
        );
        return $res;
    }


    function getLeaveReports($data) {
        $res = array();
        $val = array();

        $data->filters->officeid = array();
        $offices = new SalesOfficesModel;
        if($data->filters->office != ''){
            $getofcs = $offices->getIncOfcs($data->filters->officename);

            foreach ($getofcs['rows'] as $ofc) {
                $data->filters->officeid[] = $ofc['salesofficeid'];
            } 
            unset($getofcs);
        } else {
            foreach($data->filters->ofc_access as $ofc_item) {
                $getofcsid = $offices->getSalesOfficeByOfcId($ofc_item);
                $data->filters->officeid[] = $getofcsid['salesofficeid'];
            }
            unset($getofcs);
        }
        $offices->closeDB();
        unset($offices);

        $data->filters->hierarchy = getHierarchy($data->filters->direct);
        $val['filters'] = $data->filters;
        $draw = $data->draw;
        $start = $data->start;
        $recordlength = $data->length;
        $nextrecord = $start+$recordlength;
        
        $reportsmodel = new ReportsModel;
        $getrecord = $reportsmodel->getLeaveReports($val);
        $abapeople = $reportsmodel->getPeople()['rows'];
        $reportsmodel->closeDB();
        unset($reportsmodel);

        $eerecords = $getrecord['rows'];
        unset($getrecord['rows']);

        foreach ($eerecords as $index => $ee) {
            $peopletmp = array("abaini" => null, "userid" => null, "fname" => null, "lname" => null);
            $approveddirect_ini     = NULL;
            $approvedindirect_ini   = NULL;
            $reportsdirect_ini      = NULL;
            $reportsindirect_ini    = NULL;

            // c
            $approveddirect = $peopletmp;
            $tmpapproveddirect = $ee['approvedby_direct_id'];
            $approveddirect = array_shift(array_filter($abapeople, function($person) use ($tmpapproveddirect) {
                return $person['userid'] == $tmpapproveddirect;
            }));
            unset($tmpapproveddirect);
            $approveddirect_ini = $approveddirect['abaini'];
            $eerecords[$index]['directini'] = $approveddirect_ini;
            $eerecords[$index]['approvedby_direct_name'] = $approveddirect['fname'] == null ? '' : $approveddirect['fname'] . ' ' .  $approveddirect['lname'];
            unset($approveddirect);

            // d
            $approvedindirect= $peopletmp;
            $tmpapprovedindirect = $ee['approvedby_indirect_id'];
            $approvedindirect = array_shift(array_filter($abapeople, function($person) use ($tmpapprovedindirect) {
                return $person['userid'] == $tmpapprovedindirect;
            }));
            unset($tmpapprovedindirect);
            $approvedindirect_ini = $approvedindirect['abaini'];
            $eerecords[$index]['indirectini'] = $approvedindirect_ini;
            $eerecords[$index]['approvedby_indirect_name'] = $approvedindirect['fname'] == null ? '' : $approvedindirect['fname'] . ' ' .  $approvedindirect['lname'];
            unset($approvedindirect);


            // l
            $reportsdirect= $peopletmp;
            $tmpreportsdirect = $ee['reportstoid'];
            $reportsdirect = array_shift(array_filter($abapeople, function($person) use ($tmpreportsdirect) {
                return $person['userid'] == $tmpreportsdirect;
            }));
            unset($tmpreportsdirect);
            $reportsdirect_ini = $reportsdirect['abaini'];
            unset($reportsdirect);

            // m
            $reportsindirect= $peopletmp;
            $tmpreportsindirect = $ee['reportstoindirectid'];
            $reportsindirect = array_shift(array_filter($abapeople, function($person) use ($tmpreportsindirect) {
                return $person['userid'] == $tmpreportsindirect;
            }));
            unset($tmpreportsindirect);
            $reportsindirect_ini = $reportsindirect['abaini'];
            unset($reportsindirect);

            
            if($ee['leavestatus_direct'] == 0 && $ee['leavestatus_indirect'] == 0){
                $eerecords[$index]['leavestatus'] = $ee['leavestatusdesc_indirect'] . ' ' . (!is_null($approvedindirect_ini) ? $approvedindirect_ini : !is_null($reportsindirect_ini) ? $reportsindirect_ini : '');
            } else if ($ee['leavestatus_direct'] == 0 && $ee['leavestatus_indirect'] == 1) {
                $eerecords[$index]['leavestatus'] = $ee['leavestatusdesc_direct'] . ' ' .   (!is_null($approveddirect_ini) ? $approveddirect_ini : !is_null($reportsdirect_ini) ? $reportsdirect_ini : '');
            } else if ($ee['leavestatus_direct'] == 0 && $ee['leavestatus_indirect'] < 0) {
                $eerecords[$index]['leavestatus'] = $ee['leavestatusdesc_indirect'];
            } else {
                $eerecords[$index]['leavestatus'] = $ee['leavestatusdesc_direct'];
            }
        }

        unset($abapeople);
        $maxrecord = count($eerecords);
        $tobesorted = $data->columns[$data->order[0]->column]->name;
        $sortorder = $data->order[0]->dir;
        usort($eerecords, function($a, $b) use ($tobesorted, $sortorder) {
            if(strpos($tobesorted, 'date') !== FALSE){
                    return $sortorder == 'asc' ? strtotime($a[$tobesorted]) - strtotime($b[$tobesorted]) : strtotime($b[$tobesorted]) - strtotime($a[$tobesorted]);
            } else {
                    return $sortorder == 'asc' ? strcmp($a[$tobesorted], $b[$tobesorted]) : strcmp($b[$tobesorted], $a[$tobesorted]);
            }
        });
        $newarray = array_slice($eerecords, $start, $recordlength);
        
        $res = array(
            "draw"              => $draw,
            "recordsTotal"      => $maxrecord,
            "recordsFiltered"   => $maxrecord,
            "data"              => $newarray,
            "sql"               => $getrecord['sql'],
            // "testarr"            =>$testarr,
            // "val"               => $val,
        );
        return $res;
    }


    function getHierarchy($directid = '') {
        $gethierarchy = returnHierarchy($directid);
        $hierarchy = array();
        foreach ($gethierarchy as $key => $userids) {
            $hierarchy[] = $userids['userid'];
        }
        return $hierarchy;
    }
    

    function returnHierarchy($directid = '') {
        $res = array();
        $val = array();

        $getee = array();
        $eemodel = new EmployeesModel;
        $getee = $eemodel->getHierarchy($directid);
        $eemodel->closeDB();
        
        $val = $getee;
        foreach ($getee as $key => $eachee) {
            $tmp = array();
            $tmp = returnHierarchy($eachee['userid']);

            if(count($tmp) > 0) {
                foreach ($tmp as $key => $value) {
                    $val[] = $value;
                }
            }
        }
        
        $res = $val;

        return $res;
    }

    function loadEmployeeFilters($data) {
        $res = array();
        $ofc_access = $data->accessitem_ofc;
        
        
        #region: office
        $office = new SalesOfficesModel;
        $getoffice = $office->getSalesOfficesMain();
        $office->closeDB();
        unset($office);

        // get only the needed data
        $filterofc = array_map(function ($eachoffice) use ($ofc_access) {
            $isexist = false;
            foreach ($ofc_access as $key => $item) {
                if($item == $eachoffice['salesofficeid']) {
                    $isexist = true;
                    break;
                }
            }
            if($isexist) {
                return array(
                    "salesofficeid" =>  $eachoffice['salesofficeid'],
                    "ofcname"       =>  $eachoffice['name'],
                    "ofcini"        =>  $eachoffice['description']
                );
            }
        }, $getoffice['rows']);
        unset($getoffice);
        $res['offices'] = array();
        foreach ($filterofc as $key => $eachofc) {
            if($eachofc != null) {
                $res['offices'][] = $eachofc;
            }
        }
        unset($filterofc);
        #endregion: office


        #region: department
        $department = new DepartmentsModel;
        $res['departments'] = $department->getAllDepartments()['rows'];
        $department->closeDB();
        unset($department);
        #endregion: department


        #region: position
        // $position = new JobPositionsModel;
        // $res['position'] = $position->getAllJobPositions();
        // $position->closeDB();
        // unset($position);
        #endregion: position


        #region: ranking
        $ranking = new DropdownsModel;
        $getranking = $ranking->getEmployeeRankings();
        $ranking->closeDB();
        unset($ranking);

        // get only the needed data
        $res['rankings'] = array_map(function ($eachranking) {
            return array(
                "id"            =>  $eachranking['ddid'],
                "description"   =>  $eachranking['dddescription'],
                "ini"           =>  $eachranking['ini']
            );
        }, $getranking);
        #endregion: ranking


        #region: gender
        $gender = new DropdownsModel;
        $getgender = $gender->getGenders();
        $gender->closeDB();
        unset($gender);

        // get only the needed data
        $res['genders'] = array_map(function ($eachgender) {
            return array(
                "id"            =>  $eachgender['ddid'],
                "description"   =>  $eachgender['dddescription'],
                "ini"           =>  $eachgender['ini']
            );
        }, $getgender);
        #endregion: gender


        #region: employee type
        $eetypes = new DropdownsModel;
        $geteetypes = $eetypes->getEmployeeCategories();
        $eetypes->closeDB();
        unset($eetypes);

        // get only the needed data
        $res['eetypes'] = array_map(function ($eacheetype) {
            return array(
                "id"            =>  $eacheetype['ddid'],
                "description"   =>  $eacheetype['dddescription'],
                "ini"           =>  $eacheetype['ini']
            );
        }, $geteetypes);
        #endregion: employee status

        
        #region: employee type
        $eestatus = new DropdownsModel;
        $geteestatus = $eestatus->getEmployeeStatus();
        $eestatus->closeDB();
        unset($eestatus);

        // get only the needed data
        $res['eestatuses'] = array_map(function ($eacheestatus) {
            return array(
                "id"            =>  $eacheestatus['ddid'],
                "description"   =>  $eacheestatus['dddescription'],
                "ini"           =>  $eacheestatus['ini']
            );
        }, $geteestatus);
        #endregion: employee status

        #region: direct
        $eemodel = new EmployeesModel;
        $getdirect = $eemodel->getManagers();
        $eemodel->closeDB();
        unset($eemodel);
        
        $eedirect = array_filter($getdirect['rows'], function($eachee) use ($ofc_access) {
            $isexist = false;
            foreach ($ofc_access as $key => $item) {
                if($item == $eachee['office']) {
                    $isexist = true;
                    break;
                }
            }
            return $isexist;
        });

        $res['eedirect'] = array();
        foreach ($eedirect as $key => $ee) {
            $res['eedirect'][] = $ee;
        }
        unset($eedirect);

        #endregion: direct

        return $res;
    }

    function loadLeaveFilters($data) {
        $res = array();
        $ofc_access = $data->accessitem_ofc;

        #region: office
        $office = new SalesOfficesModel;
        $getoffice = $office->getSalesOfficesMain();
        $office->closeDB();
        unset($office);

        // get only the needed data
        $filterofc = array_map(function ($eachoffice) use ($ofc_access) {
            $isexist = false;
            foreach ($ofc_access as $key => $item) {
                if($item == $eachoffice['salesofficeid']) {
                    $isexist = true;
                    break;
                }
            }
            if($isexist) {
                return array(
                    "salesofficeid" =>  $eachoffice['salesofficeid'],
                    "ofcname"       =>  $eachoffice['name'],
                    "ofcini"        =>  $eachoffice['description']
                );
            }
        }, $getoffice['rows']);
        unset($getoffice);
        $res['offices'] = array();
        foreach ($filterofc as $key => $eachofc) {
            if($eachofc != null) {
                $res['offices'][] = $eachofc;
            }
        }
        unset($filterofc);
        #endregion: office


        #region: department
        $department = new DepartmentsModel;
        $res['departments'] = $department->getAllDepartments()['rows'];
        $department->closeDB();
        unset($department);
        #endregion: department


        #region: position
        // $position = new JobPositionsModel;
        // $res['position'] = $position->getAllJobPositions();
        // $position->closeDB();
        // unset($position);
        #endregion: position


        #region: ranking
        $ranking = new DropdownsModel;
        $getranking = $ranking->getEmployeeRankings();
        $ranking->closeDB();
        unset($ranking);

        // get only the needed data
        $res['rankings'] = array_map(function ($eachranking) {
            return array(
                "id"            =>  $eachranking['ddid'],
                "description"   =>  $eachranking['dddescription'],
                "ini"           =>  $eachranking['ini']
            );
        }, $getranking);
        #endregion: ranking


        #region: gender
        $gender = new DropdownsModel;
        $getgender = $gender->getGenders();
        $gender->closeDB();
        unset($gender);

        // get only the needed data
        $res['genders'] = array_map(function ($eachgender) {
            return array(
                "id"            =>  $eachgender['ddid'],
                "description"   =>  $eachgender['dddescription'],
                "ini"           =>  $eachgender['ini']
            );
        }, $getgender);
        #endregion: gender


        #region: employee type
        $eetypes = new DropdownsModel;
        $geteetypes = $eetypes->getEmployeeCategories();
        $eetypes->closeDB();
        unset($eetypes);

        // get only the needed data
        $res['eetypes'] = array_map(function ($eacheetype) {
            return array(
                "id"            =>  $eacheetype['ddid'],
                "description"   =>  $eacheetype['dddescription'],
                "ini"           =>  $eacheetype['ini']
            );
        }, $geteetypes);
        #endregion: employee status

        
        #region: employee type
        $eestatus = new DropdownsModel;
        $geteestatus = $eestatus->getEmployeeStatus();
        $eestatus->closeDB();
        unset($eestatus);

        // get only the needed data
        $res['eestatuses'] = array_map(function ($eacheestatus) {
            return array(
                "id"            =>  $eacheestatus['ddid'],
                "description"   =>  $eacheestatus['dddescription'],
                "ini"           =>  $eacheestatus['ini']
            );
        }, $geteestatus);
        #endregion: employee status

        #region: direct
        $eemodel = new EmployeesModel;
        $getdirect = $eemodel->getManagers();
        $eemodel->closeDB();
        unset($eemodel);
        
        $eedirect = array_filter($getdirect['rows'], function($eachee) use ($ofc_access) {
            $isexist = false;
            foreach ($ofc_access as $key => $item) {
                if($item == $eachee['office']) {
                    $isexist = true;
                    break;
                }
            }
            return $isexist;
        });

        $res['eedirect'] = array();
        foreach ($eedirect as $key => $ee) {
            $res['eedirect'][] = $ee;
        }
        unset($eedirect);

        #endregion: direct

        #region: employees
        $employees = new EmployeesModel;
        $getemployees = $employees->getEEnUserid();
        $employees->closeDB();
        unset($employee);

        $ees = array_filter($getemployees['rows'], function($eachee) use ($ofc_access) {
            $isexist = false;
            foreach ($ofc_access as $key => $item) {
                if($item == $eachee['office'] && $eachee['status'] == 1) {
                    $isexist = true;
                    break;
                }
            }
            return $isexist;
        });

        $res['employees'] = array();
        foreach ($ees as $ee) {
            $res['employees'][] = $ee;
        }
        unset($eedirect);

        // if($ofconly == 'default') {
        //     $res['employees'] = $getemployees['rows'];
        // } else {
        //     $directperofc = array_filter($getemployees['rows'], function($ee) use ($ofconly) {
        //         return $ee['office'] == $ofconly;
        //     });
        //     foreach ($directperofc as $key => $ee) {
        //         $res['employees'][] = $ee;
        //     }
        // }
        #endregion: employees

        #region: leave type
        $leavetype = new DropdownsModel;
        $getleavetype = $leavetype->getLeaveType();
        $leavetype->closeDB();
        unset($leavetype);

        // get only the needed data
        $res['leavetype'] = array_map(function ($eachleavetype) {
            return array(
                "id"            =>  $eachleavetype['ddid'],
                "description"   =>  $eachleavetype['dddescription'],
                "ini"           =>  $eachleavetype['ini']
            );
        }, $getleavetype);
        #endregion: leave type

        #region: leave status
        $leavestatus = new DropdownsModel;
        $getleavestatus = $leavestatus->getLeaveStatus();
        $leavestatus->closeDB();
        unset($leavestatus);

        // get only the needed data
        $res['leavestatus'] = array_map(function ($eachleavestatus) {
            return array(
                "id"            =>  $eachleavestatus['ddid'],
                "description"   =>  $eachleavestatus['dddescription'],
                "ini"           =>  $eachleavestatus['ini']
            );
        }, $getleavestatus);
        #endregion: leave status
        
        return $res;
    }

    function getPosition($data) {
        $res = array();
        $department = $data->department;

        $position = new JobPositionsModel;
        if($department == ''){
            $res['positions'] = null;
        } else {
            $res['positions'] = $position->getJobTitles($department)['rows'];
        }
        $position->closeDB();
        unset($position);

        return $res;
    }

    
    function getDirect($data) {
        $res = array();
        $office = $data->office;
        $ofc_access = $data->ofc_access;

        $direct = new EmployeesModel;
        $getdirect = $direct->getManagers();
        $direct->closeDB();
        unset($direct);

        if($office != ''){
            // get office only
            $eedirect = array_filter($getdirect['rows'], function($ee) use ($office) {
                return $ee['office'] == $office;
            });
    
            // move to array
            $res['eedirect'] = array();
            foreach ($eedirect as $key => $value) {
                $res['eedirect'][] = $value;
            }
        } else {
            $eedirect = array_filter($getdirect['rows'], function($eachee) use ($ofc_access) {
                $isexist = false;
                foreach ($ofc_access as $key => $item) {
                    if($item == $eachee['office']) {
                        $isexist = true;
                        break;
                    }
                }
                return $isexist;
            });

            $res['eedirect'] = array();
            foreach ($eedirect as $key => $ee) {
                $res['eedirect'][] = $ee;
            }
            unset($eedirect);
        }
        
        return $res;
    }

    function getIndirect($data) {
        $res = array();
        $direct = $data->direct;
        $office = $data->office;

        $indirect = new EmployeesModel;
        $getindirect = $direct == '' ? $indirect->getManagers() : $indirect->getIndirect($direct);
        $indirect->closeDB();
        unset($indirect);

        if($office != ''){
            // get office only
            $eeindirect = array_filter($getindirect['rows'], function($ee) use ($office) {
                return $ee['office'] == $office;
            });
    
            // move to array
            $res['eeindirect'] = array();
            foreach ($eeindirect as $key => $value) {
                $res['eeindirect'][] = $value;
            }
        } else {
            $res['eeindirect'] = $getindirect['rows'];
        }
        
        return $res;

    }
    
    function getEmployee($data) {
        $res = array();
        $val = array();

        $val['office'] = $data->office;
        $val['ofc_access'] = $data->ofc_access;
        $val['department'] = $data->department;
        $val['position'] = $data->position;
        $val['eestatus'] = $data->eestatus;
        $val['direct'] = $data->direct;
        $val['indirect'] = $data->indirect;
        
        $employees = new EmployeesModel;
        $getemployee = $employees->getEEnUserid($val);
        $employees->closeDB();
        unset($employees);
        
        $res['employees'] = $getemployee['rows'];
        
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