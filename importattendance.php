<?php
  clearstatcache();
  include_once("inc/global.php");
  include_once("inc/functions.php");
  include_once("inc/includes.php");
  include_once("inc/sessions.php");
  include_once('api/classes/class.phpmailer.php');
  include_once("api/models/sendmail_model.php");
  // exit();
  
  
  // $alloweduser = ['loam','reca','vive','raoc']; 
  // if(!in_array($abaini,$alloweduser))
  //     echo '<script> window.location = "'. base_URL .'404"; </script>';

  if(isset($_GET['runnow'])){
    runAuto();
  } else {
    
  }

  function runAuto(){
    #region zktecodb ---------------------------------------------------------
    /* ceb */
    $zkdeviceid[] = '5';
    $serverName[] = array("180.232.109.242","222.127.1.162");
    $cnInfo[] = array("Database" => "sscceb_zkdb", "UID" => "sa", "PWD" => "Aveb2832");

    /* hk */
    $zkdeviceid[] = '1';
    $serverName[] = "202.126.222.254";
    $cnInfo[] = array("Database" => "ZKTaccess", "UID" => "ZKTaccess", "PWD" => "ZKTaccess");

    /* sha */
    $zkdeviceid[] = '2';
    $serverName[] = "180.169.115.46";
    $cnInfo[] = array("Database" => "ZKTaccess", "UID" => "ZKTaccess", "PWD" => "ZKTaccess");

    /* bei */
    $zkdeviceid[] = '3';
    $serverName[] = "60.194.13.170";
    $cnInfo[] = array("Database" => "ZKaccess", "UID" => "ZKaccess", "PWD" => "ZKaccess");

    /* sg */
    $zkdeviceid[] = '4';
    $serverName[] = "103.2.181.221";
    $cnInfo[] = array("Database" => "zkdb_abasg", "UID" => "zkabasg1", "PWD" => "bx7a@G_J");

    #endregion       ---------------------------------------------------------

    $timenow = date('Hi',strtotime('now'));
    $weekdaynumber = date('N', strtotime("+1 days"));
    $isweekend = ($weekdaynumber == 6 || $weekdaynumber == 7) ? 1 : 0;
    $logs_today = ($timenow >= 1500 && $timenow <= 2359) || $timenow < 1000 ? 1 : 0;
    $logs_yesterday = $timenow >= 1000 && $timenow < 1500 ? 1 : 0;


    $getlogfrom = '';
    if($logs_today){
      $getlogfrom = 'now';
    } else if($logs_yesterday) {
      $getlogfrom = '-1 days';
    } else {
      exit();
    }
    /* Override for debug
        To use yesterday's data a an example since it usually have both in and out,
        follow below:
          To get the in, set:  1, 0, -1 days
          To get the out, set: 0, 1, -1 days
    */
    // $j = 16;
    // while($j > 1){
    //   $i = 1;
    //   while($i >= 0){
        // $testlog = 0;//$i;
        // $getlogfrom = "-9 days";//"-$j day";
        // if($testlog == 1){
        //   $logs_today = 1;
        //   $logs_yesterday = 0;
        // } else {
        //   $logs_today = 0;
        //   $logs_yesterday = 1;
        // }
      
      $sdt = date('Y-m-d',strtotime($getlogfrom))." 00:00:00.000";
      $edt = date('Y-m-d',strtotime($getlogfrom))." 23:59:59.000";

      // if(date('N', strtotime($getlogfrom)) == 6 || date('N', strtotime($getlogfrom)) == 0 || date('N', strtotime($getlogfrom)) == 7) goto skipme;
      // echo $sdt . " -- " . $testlog . ' ' . date('N', strtotime($getlogfrom)) . ' ' ."<br>";
      // exit();

      for($a = 0;$a < count($serverName);$a++){
        $val = array();
        $val['zkdeviceid'] = $zkdeviceid[$a];
        $val['servername'] = $serverName[$a];
        $val['cninfo'] = $cnInfo[$a];
        $val['sdt'] = $sdt;
        $val['edt'] = $edt;
        $val['logs_today'] = $logs_today;
        $val['logs_yesterday'] = $logs_yesterday;
        $val['isweekend'] = $isweekend;
        // print_r($val);
        // linebr(2);
        importAttendance($val);
        // linebr(2);
      }
  //     $i--;
  //   }
  //   skipme:
  //   $j--;
  // }
  }

  function importAttendance($data){
    //variables
    $zkdeviceid = $data['zkdeviceid'];
    $serverName = $data['servername'];
    $cnInfo = $data['cninfo'];
    $sdt = $data['sdt'];
    $edt = $data['edt'];
    $logs_today = $data['logs_today'];
    $logs_yesterday = $data['logs_yesterday'];
    $loggedno = formatDate("Ymd",$sdt);
    $isweekend = $data['isweekend'];
    // echo __LINE__ . ': ' . 'loggedno: ' . $loggedno;
    // linebr(1);

    $notConnected = 0;
    #region---------------------------------------- connect to db ----------------------------------------
    $cn = new mysqli("202.155.223.165", "uabacare", "Hj7cQzaA", "aba_abvt");
    // $cn = new mysqli("localhost", "root", "5437", "aba_abvt");
    if ($cn->connect_error) {
        die("Connection failed: " . $cn->connect_error);
        goto exitme;
        // exit();
    }

    if(is_array($serverName)){
      $serverCount = count($serverName) - 1;
      foreach($serverName as $index => $server){
        $con = sqlsrv_connect($server, $cnInfo);
        if($con){
          // echo __LINE__ . ': ' . "Connection established for " . $server;
          break;
        } else {
          if($index == $serverCount){
            echo __LINE__ . ': ' . "Connection could not be established for ". $zkdeviceid . "<br>";
            $notConnected = 1;
            // goto exitme;
            // die( print_r( sqlsrv_errors(),true));
            // exit();
          }
        }
      }
    } else {
      $con = sqlsrv_connect($serverName, $cnInfo);
      if($con) {
        // echo __LINE__ . ': ' . "Connection established for " . $serverName;
      }else{
        echo __LINE__ . ': ' . "Connection could not be established for ". $zkdeviceid . "<br>";
        $notConnected = 1;
        // goto exitme;
        // die( print_r( sqlsrv_errors(),true));
        // exit();
      }
    }
    #endregion--------------------------------------------------------------------------------------------

    
    #region---------------------------- check if attendance is already logged ----------------------------
    if($logs_today) { //check only for morning sign ins
      $sql = "SELECT ". ATTENDANCEMNTRGMST .".* FROM ". ATTENDANCEMNTRGMST ." WHERE ". ATTENDANCEMNTRGMST .".zkdeviceid =  $zkdeviceid AND ". ATTENDANCEMNTRGMST .".loggedno = $loggedno 
              AND ". ATTENDANCEMNTRGMST .".status = 1";
      $qry = $cn->query($sql);
      if( $qry === false) {
          echo __LINE__ . ': ' . $cn->error;
          goto exitme;
          // exit();
      }
      $count = $qry->num_rows;
      // echo __LINE__ . ': ' . 'Exists: ' . $count;
      // linebr(1);
      // exit();

      // if exist
      if($count > 0){
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
          $nooflogs = $row['nooflogs'];
        }
        if($nooflogs == 0){
          $sql = "UPDATE ". ATTENDANCEMNTRGMST ."
                  SET ". ATTENDANCEMNTRGMST .".status = 0
                  WHERE ". ATTENDANCEMNTRGMST .".zkdeviceid =  $zkdeviceid AND ". ATTENDANCEMNTRGMST .".loggedno = $loggedno";

          $qry = $cn->query($sql);
        if( $qry === false) {echo __LINE__ . ': ' . $cn->error;goto exitme;/*exit();*/}

          $sql = "";
          $qry = "";
          $sql = "SELECT ". NOTIFIEDPERSONSMST .".*
                  ,CONCAT(
                    (CASE WHEN a.fname != '' THEN a.fname ELSE '' END),' '
                    ,(CASE WHEN a.mname != '' THEN a.mname ELSE '' END),' '
                    ,(CASE WHEN a.lname != '' THEN a.lname ELSE '' END)) AS eename   
                  FROM ". NOTIFIEDPERSONSMST ." 
                    LEFT JOIN ". ABAPEOPLESMST ." a
                      ON a.userid = ". NOTIFIEDPERSONSMST .".userid 
                  WHERE ". NOTIFIEDPERSONSMST .".zkdeviceid =  $zkdeviceid AND ". NOTIFIEDPERSONSMST .".notificationtype = 'importattendance'";

          $qry = $cn->query($sql);
          if( $qry === false) {
            echo __LINE__ . ': ' . $cn->error;
            goto exitme;
            // exit();
          }
          
          while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $email = $row['emailaddress'];
            $ccemail = $row['ccemailaddress'];
            $eepersonnelname = $row['eename'];
          }
          $emaildetails = array();
          $emaildetails['emailto'] = $email;
          $emaildetails['ccemail'] = $ccemail;
          $emaildetails['eepersonnelname'] = $eepersonnelname;
          $emaildetails['sdt'] = $sdt;
          $emaildetails['edt'] = $edt;

          // $email = new sendMail;
          // $email->sendImportAttendanceNotification($emaildetails);

          goto exitme;
          // echo __LINE__ . ': ' . 'Email sent to HR';
        }else{
          goto exitme;
        }
      }
      unset($sql);
      unset($qry);
    }
    #endregion--------------------------------------------------------------------------------------------
    
    
    $rowids = "";
    $allActiveEE = array();
    #region---------------------------------- get all active employees -----------------------------------
    $sql = "SELECT id,fname,lname,userid,zkid,startshift,endshift FROM ". ABAPEOPLESMST ." 
            WHERE " . ABAPEOPLESMST .".status = 1 
              AND ". ABAPEOPLESMST .".contactcategory = 1 
              AND ". ABAPEOPLESMST .".zkid IS NOT NULL 
              AND ". ABAPEOPLESMST .".zkdeviceid = $zkdeviceid 
              ORDER BY ".ABAPEOPLESMST.".zkid ";
    $qry = $cn->query($sql);
    unset($sql);
  
  if(!$qry) { echo __LINE__ . ': ' . $cn->error;goto exitme; /*exit();*/ }
    $rowidlist = array();
    while($row = $qry->fetch_array(MYSQLI_ASSOC)){
      $rowidlist[] = $row['zkid'];
      $allActiveEE[] = $row;
    }
    $rowidlist = array_unique($rowidlist);
    foreach($rowidlist as $id){
      $rowids .= "'" . $id . "',";
    }
    unset($rowidlist);
    unset($qry);
    #endregion--------------------------------------------------------------------------------------------

    

    $eeOnLeave = array();
    #region---------------------------- get all logs in current date: returns zkid ------------------------------
    $sql = "SELECT " . ATTENDANCESMST . ".`zkid` 
            FROM " . ATTENDANCESMST . " 
            WHERE " . ATTENDANCESMST . ".`status` = 1 
              AND " . ATTENDANCESMST . ".`zkid` IS NOT NULL 
              AND " . ATTENDANCESMST . ".`loggedno` = '$loggedno' 
              AND " . ATTENDANCESMST . ".`zkdeviceid` = $zkdeviceid";
              // " . ATTENDANCESMST . ".`onleave` = 1 
    $qry = $cn->query($sql);
    unset($sql);

  if(!$qry) { echo __LINE__ . ': ' . $cn->error; goto exitme;/*exit();*/ }
    while($row = $qry->fetch_array(MYSQLI_ASSOC)){
      $eeOnLeave[] = $row['zkid'];
    }
    unset($qry);
    // print_r($eeOnLeave);
    // linebr(1);
    #endregion--------------------------------------------------------------------------------------------
    
    #region--------------------------------- adding ee: default absent -----------------------------------
    if($logs_today && !$isweekend){
      foreach($allActiveEE as $key => $employee){ //iterate through all ee
        $zkid = $employee['zkid'];
        if(!empty($zkid) && $zkid != null && $zkid != ''){
          $userid = $employee['userid'];
          $startshift = $employee['startshift'];
          if(!in_array($zkid, $eeOnLeave)){ // don't include on leave
            $sql = "INSERT INTO ". ATTENDANCESMST ."(zkdeviceid,zkid,loggedno,startshift,userid)
                                              VALUES('$zkdeviceid','$zkid','$loggedno','$startshift','$userid')";
            // echo __LINE__ . ': ' . $sql . '<br>';
            $qry = $cn->query($sql);
          if(!$qry) { echo __LINE__ . ': ' . $cn->error; goto exitme;/*exit();*/}
            $sql = "";
            unset($qry);
          } else {
            // echo __LINE__ . ': ' . $zkid . ' is on leave <br>';
          }
        }
      }
      unset($sql);
      unset($qry);
    }
    #endregion--------------------------------------------------------------------------------------------
    unset($eeOnLeave);
    unset($allActiveEE);

    if($notConnected) goto exitme;

    // linebr(2);
    
    $rowids = substr($rowids, 0, -1);
    $num = 0;
    $qry_zkteco = null;
    #region-------------------------------------- query zkteco db ---------------------------------------
    // echo __LINE__ . ': ' . $rowids;
    // $sql = "SELECT CHECKINOUT.USERID, CHECKINOUT.CHECKTIME, CHECKINOUT.CHECKTYPE FROM CHECKINOUT WHERE (CHECKINOUT.CHECKTIME >= '$sdt' AND CHECKINOUT.CHECKTIME <= '$edt')
    //     AND CHECKINOUT.USERID IN($rowids) ORDER BY CHECKINOUT.USERID,CHECKINOUT.CHECKTIME,CHECKINOUT.CHECKTYPE ";
    $sql = "SELECT USERINFO.Badgenumber, CHECKINOUT.CHECKTIME, CHECKINOUT.CHECKTYPE 
            FROM USERINFO 
            LEFT JOIN CHECKINOUT 
              ON CHECKINOUT.USERID = USERINFO.USERID
            WHERE (CHECKINOUT.CHECKTIME >= '$sdt' AND CHECKINOUT.CHECKTIME <= '$edt') 
            AND USERINFO.Badgenumber IN($rowids) 
            ORDER BY USERINFO.Badgenumber,CHECKINOUT.CHECKTIME,CHECKINOUT.CHECKTYPE ";
  // echo $sql . '<br/>';
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $qry_zkteco = sqlsrv_query( $con, $sql , $params, $options );
    unset($sql);

    if(!$qry_zkteco) {
        die( print_r( sqlsrv_errors(), true) );
        goto exitme;
        // exit();
    }

    // get total count of retrieved data
    $num = sqlsrv_num_rows($qry_zkteco);
    #endregion--------------------------------------------------------------------------------------------

    // echo __LINE__ . ': ' . 'Count from zkteco db: ' . $num;
    // linebr(2);

    

    #region----------------------------------- Update In and Out of ee -----------------------------------
    $zkid_prev = "";
    $prevdt = "";
    $cnt=0;
    $cntperid = 0;
    $index = 0;
    if($num > 0){
      while($row = sqlsrv_fetch_array( $qry_zkteco, SQLSRV_FETCH_ASSOC)){
        $index++;
        $zkid_qry = '';
        $loggedno_qry = '';
        $loggeddt_qry = '';

        $zkid = $row['Badgenumber'];
        $loggedno =  $row['CHECKTIME']->format("Ymd");
        $loggeddt =  $row['CHECKTIME']->format("Y-m-d H:i:s");
        
        if(empty($zkid_prev)){
          $zkid_qry = $zkid;
          $loggedno_qry = $loggedno;
          $loggeddt_qry = $loggeddt;
          if($logs_yesterday)
            goto skipqry;
        } else if($zkid == $zkid_prev) {
          if($index == $num){
            if($logs_yesterday){
              $zkid_qry = $zkid_prev;
              $loggedno_qry = $loggedno_prev;
              $loggeddt_qry = $loggeddt_prev;
            } else {
              goto skipqry;
            }
          } else {
            goto skipqry;
          }
        } else if($zkid != $zkid_prev) {
          if($logs_today){
            $zkid_qry = $zkid;
            $loggedno_qry = $loggedno;
            $loggeddt_qry = $loggeddt;
          }
          if($logs_yesterday){
            $zkid_qry = $zkid_prev;
            $loggedno_qry = $loggedno_prev;
            $loggeddt_qry = $loggeddt_prev;
          }
        }
        
        if($zkid_qry == '')
          goto skipqry;
        // echo __LINE__ . ': ' . $zkid_qry . " - " . $loggeddt_qry;


        
        
        
        $eeAttendance = array();
        $sql = "SELECT zkid, onleave, firsthalf, secondhalf,startshift 
                FROM ". ATTENDANCESMST ." 
                WHERE status = 1 AND wholeday = 0 AND zkdeviceid = $zkdeviceid AND zkid = $zkid_qry AND loggedno = $loggedno_qry ";
        $qry = $cn->query($sql);
        unset($sql);

      if(!$qry) { echo __LINE__ . ': ' . $cn->error; goto exitme; /*exit();*/ }
        $eeAttendance = $qry->fetch_array(MYSQLI_ASSOC);
        $logtype = '';
        if(count($eeAttendance) > 0){

          // late flagging
          $lateflag = 0;
          if($eeAttendance['startshift'] != null && $eeAttendance['startshift'] != ''){
            $startshift = (int)$eeAttendance['startshift'];
            $loggedintime = (int)formatDate("Hi", $loggeddt);
            $lateisred = $loggedintime > $startshift + 15; 
            $lateisyellow = $loggedintime > $startshift && $loggedintime <= $startshift + 15;
            if($lateisred){
                $lateflag = 1;
            } else if($lateisyellow){
                $lateflag = 2;
            } else {
                $lateflag = 0;
            }
          }

          $insert_logtype = '';

          if($logs_today){
            if($eeAttendance['onleave'] == 0 || 
              ($eeAttendance['onleave'] == 1 && $eeAttendance['firsthalf'] == 0 && $eeAttendance['secondhalf'] == 1))
                if(!$isweekend){
                  $logtype = "loggedin = '$loggeddt_qry', late = '$lateflag' ";
                } else {
                  $insert_logtype = "loggedin";
                }
          }
          if($logs_yesterday){
            if($eeAttendance['onleave'] == 0 || 
              ($eeAttendance['onleave'] == 1 && $eeAttendance['firsthalf'] == 1 && $eeAttendance['secondhalf'] == 0))
              if(!$isweekend){
                $logtype = "loggedout = '$loggeddt_qry' ";
              } else {
                $insert_logtype = "loggedout";
              }
          }
          if(!empty($logtype)){
            if($isweekend){
              $sql = "INSERT INTO ". ATTENDANCESMST ."(zkdeviceid,zkid,loggedno,startshift,$insert_logtype)
                              VALUES('$zkdeviceid','$zkid_qry','$loggedno_qry','$startshift','$loggeddt_qry')";
            } else {
              $sql_attendance = "UPDATE ". ATTENDANCESMST .
                                " SET $logtype 
                                WHERE zkdeviceid = '$zkdeviceid' 
                                  AND zkid = '$zkid_qry' 
                                  AND loggedno = '$loggedno_qry'";
            }
            // echo __LINE__ . ': ' . $sql_attendance;
            $qry_attendance = $cn->query($sql_attendance);
          if(!$qry_attendance) {echo __LINE__ . ': ' . $cn->error;goto exitme;/*exit();*/}
            $cnt++;
          }
        }
        // print_r($eeAttendance);
        // linebr(1);
        unset($qry);


        skipqry:
        $zkid_prev = $row['Badgenumber'];
        $loggedno_prev = $row['CHECKTIME']->format("Ymd");
        $loggeddt_prev = $row['CHECKTIME']->format("Y-m-d H:i:s");
      }
      #endregion--------------------------------------------------------------------------------------------
    
    }
    
    $logno = formatDate("Ymd", $edt);
    $today = date("Y-m-d H:i:s");
    if($logs_today === 1){
      $sql3 =  "INSERT INTO ". ATTENDANCEMNTRGMST ."(zkdeviceid,startdate,enddate,nooflogs,loggedno,createdby,createddate)
                VALUES('$zkdeviceid','$sdt','$edt',$cnt,'$logno','admin','$today')";
    }
    if($logs_yesterday === 1){
      $sql3 =  "UPDATE ". ATTENDANCEMNTRGMST ." SET modifiedby = 'admin', modifieddate = '$today' 
                WHERE loggedno = '$logno' AND zkdeviceid = '$zkdeviceid'";
    }
    $qry = $cn->query($sql3);
    if( !$qry) {
      echo __LINE__ . ': ' . $cn->error;
      goto exitme;
      // exit();
    }
    // echo 'done';
    exitme:
    $cn->close();
    $cn = NULL;
    unset($cn);
  }
  

  function linebr($count){
    $i = !isset($count) ? 1 : $count;
    $returnval = '';
    while($i > 0){
      $returnval .= '<br/>';
      $i--;
    }
    echo __LINE__ . ': ' . $returnval;
  }

  
    // required headers
    header("Access-Control-Allow-Origin: *");
    // header("Content-Type: application/json; charset=UTF-8");
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo FAVICO; ?>" type="image/ico" />

   <title><?php echo TITLE; ?></title> 


    <!-- Custom fonts for this template-->
    <?php include_once('inc/bs-css.php'); ?>

  </head>

  <body id="page-top">
    
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- <div class="sidebar-primary"> -->
      <?php include_once('inc/sidebar.php');?>
    <!-- </div> -->
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          <!-- Topbar -->
          <?php include_once('inc/header.php');?>

          <!-- Begin Page Content -->
  		    <div class="container-fluid">
            <?php include_once('views/importattendance/importattendance.php')?>
  		    </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php include_once('inc/footer.php');?>
      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <?php include_once('inc/loader.php');?>

    <?php include_once('inc/scrolltotop.php');?>

    <?php include_once('inc/logoutmodal.php');?>

    <?php include_once('inc/jquery.php');?>

  

    <?php srcInit('vendor/datatables/jquery.dataTables.min.js'); ?>
    <?php srcInit('vendor/datatables/dataTables.bootstrap4.min.js'); ?>
    <?php //srcInit(VIEWS . 'profile.js'); ?>

  </body>

</html>
