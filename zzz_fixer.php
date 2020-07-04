<?php

    
    echo '<script> window.location = "https://abacare.com/aces/404"; </script>';

    $dbConn = array("host"=>"202.155.223.165","user"=>"uabacare","pass"=>"Hj7cQzaA","dbname"=>"aba_abvt"); 



    #region update userid and zkid base on leaveid
    // // for($i = 1; $i <= 5; $i++){
    //     $i = 4;
    //     $leaveids = getAttendanceLogWithLeave($dbConn,$i);
    //     // echo var_dump($leaveids);
    //     foreach ($leaveids as $key => $leaveid) {
    //         $leaveidofee = $leaveid['leaveid'];
    //         $eeids = getEEids($dbConn,$i,$leaveidofee);
    //         $countee = (int)count($eeids);
    //         if($countee > 0) {
    //             updateEEid($dbConn, $i, $leaveidofee, $eeids[0]);
    //             // echo var_dump($eeids[0]);
    //         }
    //     }
    // // }

    function updateEEid($dbConn, $zkdeviceid, $leaveid, $eeid){
        $zkid = $eeid['zkid'];
        $userid = $eeid['userid'];
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {die("Connection failed: " . $cn->connect_error);exit();}

        
        $sql = "UPDATE hris_attendance SET userid = '$userid', zkid = '$zkid' WHERE zkdeviceid = '$zkdeviceid' AND leaveid = '$leaveid'";
        $qry = $cn->query($sql);
        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }

        $cn->close();
    }
    function getAttendanceLogWithLeave($dbConn,$zkdeviceid){
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {die("Connection failed: " . $cn->connect_error);exit();}

        $sql = "SELECT DISTINCT a.`leaveid`
                FROM hris_attendance a
                WHERE a.`leaveid` IS NOT NULL AND a.`leaveid` <> '' AND a.`zkdeviceid` = $zkdeviceid";
        $qry = $cn->query($sql);

        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }
        $rows = array();
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }
        
        $cn->close();
        return $rows;
    }
    function getEEids($dbConn,$zkdeviceid,$leaveid){
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {die("Connection failed: " . $cn->connect_error);exit();}

        $sql = "SELECT b.`zkid`, b.`userid` 
                FROM hris_leaves a 
                LEFT JOIN aba_people b 
                    ON b.`userid` = a.`userid` AND b.`zkdeviceid` = $zkdeviceid 
                WHERE a.`leaveid` = '$leaveid'";
        $qry = $cn->query($sql);

        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }
        $rows = array();
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }
        
        $cn->close();
        return $rows;
    }
    #endregion


    #region add userid in attendance
    // for($i = 1; $i <= 5; $i++){
    //     $abapeople = getAbaPeople($dbConn,$i);
    //     foreach ($abapeople as $key => $person) {
    //         writeInAttendance($dbConn, $i, $person);
    //     }
    // }

    function getAbaPeople($dbConn,$zkdeviceid){
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {die("Connection failed: " . $cn->connect_error);exit();}

        $sql = "SELECT a.`zkid`,a.`userid` FROM aba_people a WHERE a.`zkdeviceid` = '$zkdeviceid' and a.`status` = 1 and a.`contactcategory` = 1";
        $qry = $cn->query($sql);
        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }
        $rows = array();
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }

        $cn->close();
        return $rows;
    }

    function writeInAttendance($dbConn,$zkdeviceid,$person){
        $zkid = $person['zkid'];
        $userid = $person['userid'];
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {
            die("Connection failed: " . $cn->connect_error);
            exit();
        }

        $sql = "UPDATE hris_attendance SET userid = '$userid' WHERE zkdeviceid = '$zkdeviceid' and zkid = '$zkid'";
        $qry = $cn->query($sql);
        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }

        $cn->close();
    }
    #endregion
?>