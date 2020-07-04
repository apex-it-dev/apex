<?php
	include_once("inc/global.php");
  	include_once("inc/functions.php");
    // exit();
    $sesini = $_SESSION['abaini'];
    $alloweduser = ['raoc']; // 'loam','reca','vive',
    if(!in_array($sesini,$alloweduser))
        echo '<script> window.location = "'. base_URL .'404"; </script>';

    $res = array();
	$cn = new mysqli("202.155.223.165", "uabacare", "Hj7cQzaA", "aba_abvt");
    if ($cn->connect_error) {
        die("Connection failed: " . $cn->connect_error);
        exit();
    }

    $sql_leavedetails = "SELECT a.*, b.`zkid`, b.`zkdeviceid` FROM " . LEAVESDTL . " a
                        LEFT JOIN " . ABAPEOPLESMST . " b 
                            ON b.`userid` = a.`createdby` 
                        LEFT JOIN hris_leaves c 
                            ON c.leaveid = a.leaveid 
                        WHERE a.`status` = 1 AND b.`zkid` IS NOT NULL AND b.`zkdeviceid` = 5 c.`status` = 1 
                        ORDER BY a.`leavedate` ASC";
    echo $sql_leavedetails;
    exit();
    $qry_leavedetails = $cn->query($sql_leavedetails);
    if( $qry_leavedetails === false) {
        echo $cn->error;
        exit();
    }

    $rows_leavedetail = array();
    while($row = $qry_leavedetails->fetch_array(MYSQLI_ASSOC)){
    	$rows_leavedetail[] = $row;
    }
    $leavedetails = $rows_leavedetail;

    // echo count($leavedetails);
    // exit();

    foreach($leavedetails as $dtlsindex => $details){
        $leaveid = $details['leaveid'];
        $zkdeviceid = $details['zkdeviceid'] == null ? '' : $details['zkdeviceid'];
        $zkid = $details['zkid'] == null ? '' : $details['zkid'];
        $startshift = $details['startshift'];
        $loggeddate = $details['leavedate'];
        $loggedno = formatDate("Ymd", $loggeddate);
        $loggedin = formatDate("Y-m-d", $loggeddate) . " 00:00:00";
        $loggedout = formatDate("Y-m-d", $loggeddate) . " 00:00:00";
        $wap = $details['wap'];
        $wd = $wap == 'w' ? 1 : 0;
        $am = $wap == 'a' ? 1 : 0;
        $pm = $wap == 'p' ? 1 : 0;
        if(($zkdeviceid != '' || $zkdeviceid != null) && ($zkid != '' || $zkid != null)){
            $sql_attendance = "INSERT INTO ". ATTENDANCESMST ."(zkdeviceid,zkid,loggedno,loggedin,loggedout,onleave,wholeday,firsthalf,secondhalf,leaveid) 
                        VALUES('$zkdeviceid','$zkid','$loggedno','$loggedin','$loggedout',1,'$wd','$am','$pm','$leaveid')";
            $qry_attendance = $cn->query($sql_attendance);
        }
    }
    exit();

    // print_r($res['logs']);
?>