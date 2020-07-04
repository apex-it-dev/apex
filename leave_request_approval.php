<?php
  session_start();
    include_once('inc/global.php');
    include_once('inc/functions.php');
    include_once('api/classes/class.phpmailer.php');
    include_once('api/models/database.php');
    include_once('api/models/leaves_model.php');
    include_once('api/models/sendmail_model.php');
    include_once('api/models/employees_model.php');

    if(isset($_GET['id']) && isset($_GET['appr'])){
      if(!empty($_GET['id'])){
        $id = $_GET['id'];
          $appr = 0;
          if($_GET['appr'] > 0){
            $appr = $_GET['appr'];
          }
          $leaves = new LeavesModel;
          $leave = $leaves->getLeaveRequestbySesid($id);
          // print_r($leave);
          // exit();
          $request = $leave['rows'];
          $status = $request[0]['status'];
          $approvallevel =$request[0]['approvallevel'];
          if(count($request) > 0){
            $leaveid = $request[0]['leaveid'];
            $leavetype = $request[0]['leavetypedesc'];
            if($status == 0){
                $val['leave'] = $request[0];
                switch($appr){
                  case 1:
                      $val['stat'] = $appr;
                      $val['status'] = 1;
                      $val['leaveid'] = $leaveid;
                      $val['reportstoid'] = $request[0]['reportstoid'];
                      $leaves->apprLeaveRequest($val);
                      echo '<script type="text/javascript">alert("Leave request is successfully approved.");</script>';
                    break;
                  default:
                      $val['stat'] = $appr;
                      $val['status'] = -1;
                      $val['leaveid'] = $leaveid;
                      $val['reportstoid'] = $request[0]['reportstoid'];
                      $leaves->apprLeaveRequest($val);
                      echo '<script type="text/javascript">alert("Leave request is disapproved");</script>';
                    break;
                }
                 $newleave = $leaves->getLeaveRequestbySesid($id);
                 $status = $newleave['rows'][0]['status'];
                 $val['leavetypedesc'] = $leavetype;
                 if($status == 1){
                    $email = new sendMail;
                    $email->sendLeaveApproveNotification($val);
                 }else if($status == 0){
                    $ees = new EmployeesModel;
                    $eedata['eedata'] = $ees->getActiveabaPeopleWithId($request[0]['userid']);
                    $reportstoid = $eedata['eedata'][0]['reportstoid'];
                    $eedata['reportstodata'] = $ees->getActiveabaPeopleWithId($reportstoid);
                    $reportstoindirectid = $eedata['eedata'][0]['reportstoindirectid'];
                    $eedata['reportstoindirectdata'] = $ees->getActiveabaPeopleWithId($reportstoindirectid);
                    $ees->closeDB();

                    $emaildetails['approvallevel'] = $newleave['rows'][0]['approvallevel'];
                    $emaildetails['reportstoname'] = $eedata['reportstodata'][0]['eename'];
                    $emaildetails['reportstoemail'] = $eedata['reportstodata'][0]['workemail'];
                    $emaildetails['reportstoindirectname'] = "";
                    $emaildetails['reportstoindirectemail'] = "";
                    if(count($eedata['reportstoindirectdata']) > 0 ){
                      $emaildetails['reportstoindirectname'] = $eedata['reportstoindirectdata'][0]['eename'];
                      $emaildetails['reportstoindirectemail'] = $eedata['reportstoindirectdata'][0]['workemail'];
                    }
                    
                    // $emaildetails['reportstoemail'] = 'vivencia.velasco@abacare.com';
                    // $emaildetails['reportstoindirectemail'] = 'vivencia.velasco@abacare.com';

                    $emaildetails['requestor'] = $eedata['eedata'][0]['eename'];
                    $emaildetails['leaveid'] = $newleave['rows'][0]['leaveid'];
                    $emaildetails['leavetype'] = $newleave['rows'][0]['leavetypedesc'];
                    $emaildetails['leaveduration'] = $newleave['rows'][0]['leavedurationdesc'];
                    $emaildetails['reason'] = $newleave['rows'][0]['reason'];
                    $emaildetails['leavefrom'] = $newleave['rows'][0]['leavefromdt'];
                    $emaildetails['leaveto'] = $newleave['rows'][0]['leavetodt'];
                    $emaildetails['noofdays'] = $newleave['rows'][0]['noofdays'];
                    $emaildetails['leavestatus'] = $newleave['rows'][0]['leavestatus'];
                    $emaildetails['sesid'] = $newleave['rows'][0]['approveddate_indirect'];
                    $emaildetails['approveddate_indirect'] = $newleave['rows'][0]['sesid'];
                    // $emaildetails['attachment'] = $filenamepathstring;

                    $res['emaildetailsss'] = $emaildetails;
                    $email = new sendMail;
                    $res['errsent'] = $email->sendLeaveRequestApproval($emaildetails);
                 }
                
            }else{
              echo '<script type="text/javascript">alert("Leave request is already been taken action by approver.");</script>';
            }
          }else{
            echo '<script type="text/javascript">alert("Invalid URL! Please contact the web administrator.");</script>';
          }
    }else{
      echo '<script type="text/javascript">alert("Invalid URL! Please contact the web administrator.");</script>';
    }
  }else{
    echo '<script type="text/javascript">alert("Invalid URL! Please contact the web administrator.");</script>';
  }
  
  $leaves->closeDB();
  echo '<script type="text/javascript">window.location="'. base_URL .'";</script>';
  exit();
?>