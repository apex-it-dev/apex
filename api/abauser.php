<?php
    // include database and object files
    require_once('../inc/global.php');
    require_once('models/database.php');
    require_once('../inc/functions.php');
    require_once('inc/class.phpmailer.php');
    require_once('models/abauser_model.php');
    require_once('models/activities_model.php');
    require_once('models/employees_model.php');
    require_once('models/salesoffices_model.php');

    $result = array();
    $json = json_decode(file_get_contents("php://input"))->data;

    if(!empty($json)){
      $f = $json->f;
      $result = $f($json);
      // $result = $json;
    }

    function logMeIn($data){
      $res = array();
      $res['err'] = 0;
      $res['errmsg'] = "";

      $uname = $data->u;
      $pword = generatePassword($data->p);

      // user login
      $users = new abaUserModel;
      $res['login'] = $users->logMeIn($uname);
      $users->closeDB();
      
      if(count($res['login']['rows']) == 0){
        $res['err'] = 1;
        $res['errmsg'] = "User not found! Please contact IT administrator.";
        goto exitme;
      }

      $row = $res['login']['rows'][0];
      $password = $row['password'];
      
      $res['password'] = $password;
      $res['pword'] = $pword;

      if($password != $pword){
        $res['err'] = 1;
        $res['errmsg'] = "Access denied! Please enter the correct password.";
        goto exitme;
      }

      $userid = $res['login']['rows'][0]['userid'];

      // save activity
      $actdata['type'] = "login";
      $actdata['details'] = 'Logged In';
      $actdata['assignedto'] = $userid;
      $actdata['abauser'] = $userid;
      $actdata['userid'] = $userid;
      $actdata['acctid'] = "";

      $acts = new ActivitiesModel;
      $acts->saveActivity($actdata);
      $acts->closeDB();

      exitme:
      return $res;
    }

    function changePassword($data){
      $res = array();
      $abaini = $data->abaini;
      $oldpw = generatePassword($data->opw);
      $res['err'] = 0;
      $res['errmsg'] = "New password successfully updated!";

      // user login
      $users = new abaUserModel;
      $res['login'] = $users->logMeIn($abaini);
      
      if(count($res['login']['rows']) == 0){
        $res['err'] = 1;
        $res['errmsg'] = $abaini . " user not found! Please contact IT administrator.";
        goto exitme;
      }

      $row = $res['login']['rows'][0];
      $userid = $res['login']['rows'][0]['userid'];
      $password = $row['password'];
      
      $res['password'] = $password;
      $res['pword'] = $oldpw;

      if($password != $oldpw){
        $res['err'] = 1;
        $res['errmsg'] = "Old/current password is incorrect! Please enter the correct current password.";
        goto exitme;
      }

      $val['abaini'] = $abaini;
      $npw = generatePassword($data->npw);
      $val['password'] = $npw;
      $res['abauser'] = $users->changePassword($val);
      $res['npw'] = $npw;
      exitme:
      $users->closeDB();
      return $res;
    }

    function forgotPassword($data){
      $res = array();
      $res['err'] = 0;
      $email = $data->email;
      $res['errmsg'] = "Your password was successfully updated and sent to email address '". $email ."' provided.";

      // user login
      $users = new abaUserModel;
      $res['chkuser'] = $users->chkUserEmailAddress($email);
      
      if(count($res['chkuser']['rows']) == 0){
        $res['err'] = 1;
        $res['errmsg'] = "'". $email ."' email address not found! Please contact IT administrator.";
        goto exitme;
      }

      $row = $res['chkuser']['rows'][0];

      $abaini = $row['abaini'];
      $val['email'] = $email;
      $val['abaini'] = $abaini;
      $val['pw'] = generateRandomString(4);
      $val['password'] = generatePassword($val['pw']);
      $res['abauser'] = $users->changePassword($val);
      $res['epw'] = $users->emailPassword($val);

      exitme:
      $users->closeDB();
      return $res;
    }

    function userLoggedActivity(){
      $res = array();
      $res['err'] = 0;

      $users = new abaUserModel;
      $res['userlogged'] = $users->userLoggedActivity();

      return $res;
    }

    function getBDActivityLog($data){
      $res = array();
      $val = array();
      $userid = $data->userid;
      $val['userid'] = $userid;
      $val['ofc'] = $data->ofc;

      $ees = new EmployeesModel;
      $res['bds'] = $ees->getBDActivityLog($val);
      $res['ees'] = $ees->getGMBDPeople($val);
      $res['ppl'] = $ees->getActiveabaPeopleWithId($userid);

      $salesofc = new SalesOfficesModel;
      $res['salesofc'] = $salesofc->getSalesOfficesOnly();

      return $res;
    }

    function getBDLogs($data){
      $res = array();
      $val = array();

      $val['bdid'] = $data->bdid;
      $val['from'] = $data->from;
      $val['to'] = $data->to;

      $ees = new EmployeesModel;
      $res['logs'] = $ees->getBDLogs($val);
      $res['ee'] = $ees->getEeByUserId($val['bdid']);

      return $res;
    }

    function sortingBDActivityLog($data){
      $res = array();
      $val = array();
      $val['sortby'] = $data->sortby;
      $val['sortin'] = $data->sortin;

      $ees = new EmployeesModel;
      $res['bds'] = $ees->sortBDActivityLog($val);

      return $res;
    }

    function filterHeaderBDActivityLog($data){
      $res = array();
      $val = array();
      $val['headerval'] = $data->headerval;
      
      $ees = new EmployeesModel;
      $res['bds'] = $ees->filterHeaderBDActivityLog($val);

      return $res;
    }

    function searchBDActivityLogs($data){
      $res = array();
      $val = array();
      $val['searchtext'] = $data->searchtext;
      $val['searchby'] = $data->searchby;
      
      $ees = new EmployeesModel;
      $res['bds'] = $ees->searchBDActivityLog($val);

      return $res;
    }

    // function changeUserPassword($data){
    //   $res = array();
    //   $val = array();

    //   $val[]
    //   return $res;
    // }
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    echo json_encode($result);
?>