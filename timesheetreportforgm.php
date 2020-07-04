<?php
    clearstatcache();
    include_once("api/models/database.php");
    include_once("inc/global.php");
    include_once("inc/functions.php");
    include_once('api/classes/class.phpmailer.php');
    include_once("api/models/sendmail_model.php");

    if(empty($_GET['runnow'])){
        echo '<script> window.location = "'. base_URL .'404"; </script>';
        exit();
    }

    $weekNow = date('N', strtotime("now"));
    $isFriday = $weekNow == 5 ? 1 : 0;
    $isWeekend = $weekNow == 6 || $weekNow == 7 ? 1 : 0;
    if($isWeekend) {
        echo 'Its weekend, why?';
        exit();
    }
    $dbConn = array("host"=>"202.155.223.165","user"=>"uabacare","pass"=>"Hj7cQzaA","dbname"=>"aba_abvt");
    // $dbConn = array("host"=>"localhost","user"=>"root","pass"=>"5437","dbname"=>"aba_abvt");
    $gmList = getGmList($dbConn);
    // echo var_dump($gmList);
    foreach ($gmList as $theGM) {
        // echo var_dump(array("theGM"=>$theGM,"wholeweek"=>$isFriday));
        // if($theGM['zkdeviceid'] != 5) goto skiptest; //limiter // test
        $hrList = getHrList($theGM['zkdeviceid'],$dbConn);
        $hrEmails = array();
        foreach($hrList as $anHR){
            $hrEmails[] = $anHR['workemail'];
        }
        generateReport(array("theGM"=>$theGM,"wholeweek"=>$isFriday,"hrEmails"=>$hrEmails,"dbConn"=>$dbConn));
        skiptest: echo '';
    }




    #region get list of gm
    function getGmList($dbConn){
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {
            die("Connection failed: " . $cn->connect_error);
            exit();
        }

        $sql = "SELECT b.`fname`, b.`lname`, b.`workemail`, a.`zkdeviceid`, a.`office`, c.`description` AS ofcname 
                FROM hris_notified_persons a
                LEFT JOIN aba_people b
                    ON b.`userid` = a.`userid` AND b.`status` = 1 AND b.`contactcategory` = 1 
                LEFT JOIN aba_sales_offices c 
                    ON c.`salesofficeid` = a.`office` 
                WHERE a.`status` = 1 AND a.`notificationtype` = 'timesheetreportgm' 
                ORDER BY a.`zkdeviceid`";

        $qry = $cn->query($sql);
        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }
        $rows = array();
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }
        $cn->close();
        $cn = NULL;
        unset($cn);
        return $rows;
    }
    #endregion

    #region get list of hr under gm
    function getHrList($zkdeviceid,$dbConn){
        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {
            die("Connection failed: " . $cn->connect_error);
            exit();
        }

        $sql = "SELECT b.`fname`, b.`lname`,b.`workemail`,a.`zkdeviceid`
                FROM ". NOTIFIEDPERSONSMST ." a
                LEFT JOIN ". ABAPEOPLESMST ." b
                    ON b.`userid` = a.`userid` AND b.`status` = 1 AND b.`contactcategory` = 1
                WHERE a.`status` = 1 AND a.`notificationtype` = 'timesheetreporthr' AND a.`zkdeviceid` = $zkdeviceid";

        $qry = $cn->query($sql);
        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }
        $rows = array();
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }
        $cn->close();
        $cn = NULL;
        unset($cn);
        return $rows;
    }
    #endregion


    function generateReport($data){
        $wholeweek = $data['wholeweek'];
        $theGM = $data['theGM'];
        $zkdeviceid = $theGM['zkdeviceid'];
        $gmFullName = $theGM['fname'] . ' ' . $theGM['lname'];
        $gmEmail = $theGM['workemail'];
        $hrEmails = $data['hrEmails'];
        $dbConn = $data['dbConn'];
        $ofc = $theGM['office'];
        $ofcname = $theGM['ofcname'];
        
        $stamp_today = strtotime('now');
        // $stamp_today = strtotime('-3 days'); // test
        $date_today = date('D M. d, yy',$stamp_today);
        $loggedno_today = date('Ymd',$stamp_today);
        
        // $wholeweek = 1; // test

        if($wholeweek){
            $stamp_monday = strtotime('monday this week');
            // $stamp_monday = strtotime('monday last week');   // test
            $date_monday = date('D M. d, yy',$stamp_monday);
            $loggedno_monday = date('Ymd',$stamp_monday);
        }


        $cn = new mysqli($dbConn['host'], $dbConn['user'], $dbConn['pass'], $dbConn['dbname']);
        if ($cn->connect_error) {
            die("Connection failed: " . $cn->connect_error);
            exit();
        }

        $whereQry = " a.`loggedno` = $loggedno_today";
        if($wholeweek) $whereQry = " a.`loggedno` >= $loggedno_monday AND a.`loggedno` <= $loggedno_today ";
        
        #region
        $sql = "SELECT b.`abaini`, b.`fname`,b.`lname`, 
                    LEFT(c.`leavetype`,LENGTH(c.`leavetype`)-2) AS leavetype, DATE_FORMAT(a.`loggedno`, '%w') AS wknum, DATE_FORMAT(a.`loggedno`, '%a') AS wkname, a.* 
                FROM " . ATTENDANCESMST . " a 
                LEFT JOIN " . ABAPEOPLESMST . " b 
                    ON b.`zkid` = a.`zkid` AND b.`zkdeviceid` = a.`zkdeviceid` AND b.`status` = 1 AND b.`contactcategory` = 1 
                LEFT JOIN hris_leaves c 
                    ON c.`leaveid` = a.`leaveid` AND c.`status` = 1 
                WHERE a.`zkdeviceid` = $zkdeviceid AND a.`status` = 1 AND b.`status` = 1 AND b.`contactcategory` = 1 
                    AND $whereQry 
                ORDER BY wknum, b.`abaini` ASC";
        $qry = $cn->query($sql);
        unset($sql);
        if(!$qry) { echo __LINE__ . ': ' . $cn->error; exit(); }
        $eeattendancelist = array();
        while($row = $qry->fetch_array(MYSQLI_ASSOC)){
            $rem = "";
            if(is_null($row['loggedin'])){
                $rem = 'NO SIGN IN';
            }
            if(!$wholeweek){
                if($row['late'] == 1){
                    $rem = 'LATE';
                }
            }
            if($row['holiday'] > 0){
                $rem = 'HOLIDAY';
            }
            if($row['onleave'] > 0){
                if(!$wholeweek){
                    $rem = 'ON LEAVE - ';
                    if($row['wholeday'] > 0){
                        $rem .= 'WHOLEDAY';
                    }
                    if($row['firsthalf'] > 0){
                        $rem .= 'FIRST HALF';
                    }
                    if($row['secondhalf'] > 0){
                        $rem .= 'SECOND HALF';
                    }
                }
                if(!empty($row['leavetype']) && $row['leavetype'] != null){
                    if($wholeweek){
                        $rem .= $row['leavetype'];
                    } else {
                        $rem .= " (".$row['leavetype']. ")";
                    }
                }
            }
            $row['remarks'] = $rem;
            $eeattendancelist[] = $row;
        }
        #endregion

        #region
        $sql_ee = "SELECT a.`abaini`, a.`fname`, a.`lname`, a.`zkdeviceid`, a.`zkid`,a.`userid` 
                   FROM " . ABAPEOPLESMST . " a 
                   WHERE a.`status` = 1 AND a.`contactcategory` = 1 
                    AND (a.`zkid` IS NOT NULL OR a.`zkid` != '') 
                    AND a.`zkdeviceid` = $zkdeviceid 
                   ORDER BY a.`fname`
                  ";

        $qry_ee = $cn->query($sql_ee);
        unset($sql_ee);
        if(!$qry_ee) { echo __LINE__ . ': ' . $cn->error; exit(); }

        $active_ee = array();
        while($row = $qry_ee->fetch_array(MYSQLI_ASSOC)){
            $active_ee[] = $row;
        }
        // echo var_dump($active_ee);
        
        $sql_attendance = "SELECT 
                            LEFT(b.`leavetype`,LENGTH(b.`leavetype`)-2) AS leavetype 
                            ,DATE_FORMAT(a.`loggedno`, '%w') AS wknum
                            ,DATE_FORMAT(a.`loggedno`, '%a') AS wkname
                            ,a.*
                            FROM " . ATTENDANCESMST . " a 
                            LEFT JOIN hris_leaves b
                                ON b.`leaveid` = a.`leaveid` AND b.`status` = 1 
                            WHERE a.`status` = 1  
                                AND a.`zkdeviceid` = $zkdeviceid AND $whereQry 
                            ORDER BY LENGTH(a.`zkid`),a.`zkid`, wknum ASC
                          ";
        $qry_attendance = $cn->query($sql_attendance);
        unset($sql_attendance);
        if(!$qry_attendance) { echo __LINE__ . ': ' . $cn->error; exit(); }

        $rows_attendance = array();
        while($row = $qry_attendance->fetch_array(MYSQLI_ASSOC)){
            $rows_attendance[] = $row;
        }
        // echo $whereQry . '<br>';
        // echo var_dump($rows_attendance);
        unset($qry_ee);
        unset($qry_attendance);
        
        $eeattendancelist = array();
        if($wholeweek){
            $tmpee = array("active_ee"=>$active_ee,"wholeweek"=>$wholeweek,"loggedno_monday"=>$loggedno_monday,"loggedno_today"=>$loggedno_today);
        } else {
            $tmpee = array("active_ee"=>$active_ee,"wholeweek"=>$wholeweek,"loggedno_today"=>$loggedno_today);
        }
        $eeattendancelist = generateBlankTable($tmpee);
        unset($active_ee);

        

        
        foreach($eeattendancelist as $key => $this_ee){
            foreach($rows_attendance as $attendance_rec){
                if($this_ee['zkid'] == $attendance_rec['zkid'] && $this_ee['loggedno'] == $attendance_rec['loggedno']){
                    $eeattendancelist[$key]['loggedin'] = $attendance_rec['loggedin'];
                    $eeattendancelist[$key]['loggedout'] = $attendance_rec['loggedout'];
                    $eeattendancelist[$key]['holiday'] = $attendance_rec['holiday'];
                    $eeattendancelist[$key]['onleave'] = $attendance_rec['onleave'];
                    $eeattendancelist[$key]['leavetype'] = $attendance_rec['leavetype'];
                    $eeattendancelist[$key]['wholeday'] = $attendance_rec['wholeday'];
                    $eeattendancelist[$key]['firsthalf'] = $attendance_rec['firsthalf'];
                    $eeattendancelist[$key]['secondhalf'] = $attendance_rec['secondhalf'];
                    $eeattendancelist[$key]['leaveid'] = $attendance_rec['leaveid'];
                    $eeattendancelist[$key]['status'] = $attendance_rec['status'];
                    $eeattendancelist[$key]['late'] = $attendance_rec['late'];
                    $eeattendancelist[$key]['startshift'] = $attendance_rec['startshift'];
                }
            }
        }
        // echo var_dump($eeattendancelist);
        // exit();

        foreach ($eeattendancelist as $key => $this_ee) {
            $loggedno_tmp = $this_ee['loggedno'];
            $sql_holiday = "SELECT COUNT(id) AS holidaycount 
                            FROM ". HOLIDAYSMST ." a 
                            WHERE a.`holidaycode` = $loggedno_tmp AND a.`office` = '$ofc' AND a.`status` = 1";

            $qry_holiday = $cn->query($sql_holiday);
            unset($sql_holiday);
            if(!$qry_holiday) { echo __LINE__ . ': ' . $cn->error; exit(); }
            $holidaycnt = $qry_holiday->fetch_array(MYSQLI_ASSOC)['holidaycount'];
            if((int)$holidaycnt > 0) $eeattendancelist[$key]['holiday'] = 1;
            unset($qry_holiday);
        }

        foreach($eeattendancelist as $key => $this_ee){
            $rem = "";
            if(is_null($this_ee['loggedin'])){
                $rem = 'NO SIGN IN';
            }
            if(!$wholeweek){
                if($this_ee['late'] == 1){
                    $rem = 'LATE';
                }
            }
            if($this_ee['holiday'] > 0){
                $rem = 'HOLIDAY';
            }
            if($this_ee['onleave'] > 0){
                if(!$wholeweek){
                    $rem = 'ON LEAVE - ';
                    if($this_ee['wholeday'] > 0){
                        $rem .= 'WHOLEDAY';
                    }
                    if($this_ee['firsthalf'] > 0){
                        $rem .= 'FIRST HALF';
                    }
                    if($this_ee['secondhalf'] > 0){
                        $rem .= 'SECOND HALF';
                    }
                }
                if(!empty($this_ee['leavetype']) && $this_ee['leavetype'] != null){
                    if($wholeweek){
                        $rem .= $this_ee['leavetype'];
                    } else {
                        $rem .= " (" .$this_ee['leavetype'] .")";
                    }
                }
            }
            $eeattendancelist[$key]['remarks'] = $rem;
        }
        // echo var_dump($eeattendancelist);
        // exit();
        unset($rows_attendance);
        #endregion

        
        
        // unset($qry);
        // echo count($eeattendancelist) . '<br>';
        // $cnt_present = 0;
        // $cnt_absent = 0;
        // $cnt_late = 0;
        // $cnt_leave = 0;
        $tableBody = "";
        $previd = '';
        $maxlist = count($eeattendancelist);
        $colorAlternate = 0;
        foreach ($eeattendancelist as $key => $eeattendance) {
            if($wholeweek){
                $tdcolor = '';
                if($previd != $eeattendance['userid']){
                    $tableBody .= $colorAlternate % 2 ? '<tr style="color:black;">' : '<tr style="background-color:#e6e6e6;color:black;">';
                    $colorAlternate++;
                    $tableBody .= '<td>' . $eeattendance['fname'] . ' ' . $eeattendance['lname'] . '</td>';
                }
                if($eeattendance['onleave'] == 1){
                    $tdcolor = 'style="background-color:#43a047;color:black;"';
                } else if($eeattendance['late'] != 0){
                    if($eeattendance['late'] == 1){
                        $tdcolor = 'style="background-color:#f57c00;color:black;"';
                    } else if($eeattendance['late'] == 2){
                        $tdcolor = 'style="background-color:#f9e76e;color:black;"';
                    }
                } else if(is_null($eeattendance['loggedin']) && $eeattendance['holiday'] == 0){
                    $tdcolor = 'style="background-color:#c62828; color:white;"';
                }

                // remarks for weekly
                $remarks = $eeattendance['remarks'];
                if($remarks != '' && $remarks != null){
                    if($eeattendance['firsthalf'] > 0){
                        $tableBody .= '<td align="center" '. $tdcolor .'>'. $eeattendance['leavetype'] . ' am / ' .formatDate("H:i",$eeattendance['loggedin']).'</td>';
                    } else if($eeattendance['secondhalf'] > 0){
                        $tableBody .= '<td align="center" '. $tdcolor .'>' .formatDate("H:i",$eeattendance['loggedin']). ' / '. $eeattendance['leavetype'] . ' pm'.'</td>';
                    } else { // wholeday or no sign in
                        $tableBody .= '<td align="center" '. $tdcolor .'>'. $remarks .'</td>';
                    }
                    // $tableBody .= '<td>'. $eeattendance['remarks'] .'</td>';
                } else {
                    $tableBody .= '<td align="center" '. $tdcolor .'>'.formatDate("H:i",$eeattendance['loggedin']).'</td>';
                }
                
                if((int)$key+1 < $maxlist){
                    if($eeattendancelist[$key+1]['userid'] != $eeattendance['userid']){
                        #region placeholder
                        if($eeattendance['wknum'] < 5){
                            for($i = $eeattendance['wknum']; $i < 5; $i++) {
                                $tableBody .= '<td></td>';
                            }
                        }
                        #endregion
                        $tableBody .= '</tr>';
                    }
                } else if ($key+1 == $maxlist) {
                    $tableBody .= '</tr>';
                }
                $previd = $eeattendance['userid'];
            } else {
                if($eeattendance['onleave'] == 1){
                    $tableBody .= '<tr style="background-color:#43a047;color:black;">';
                } else if($eeattendance['late'] != 0){
                    if($eeattendance['late'] == 1){
                        $tableBody .= '<tr style="background-color:#f57c00;color:black;">';
                    } else if($eeattendance['late'] == 2){
                        $tableBody .= '<tr style="background-color:#f9e76e;color:black;">';
                    }
                } else if(is_null($eeattendance['loggedin']) && $eeattendance['holiday'] == 0){
                    $tableBody .= '<tr style="background-color:#c62828; color:white;">';
                } else if($key % 2){
                    $tableBody .= '<tr style="color:black;">';
                } else {
                    $tableBody .= '<tr style="background-color:#e6e6e6;color:black;">';
                }
                if($wholeweek) $tableBody .= '<td align="center">' .$eeattendance['wkname'] . '</td>' ;
                $tableBody .= '<td>' . $eeattendance['fname'] . ' ' . $eeattendance['lname'] . '</td>';
                $tableBody .= '<td align="center">'.formatDate("H:i",$eeattendance['loggedin']).'</td>';
                $tableBody .= '<td>'. $eeattendance['remarks'] .'</td>';
                $tableBody .= '</tr>';}
        }
        // $cnt_list = array("present" =>$cnt_present, "absent"=>$cnt_absent, "late"=>$cnt_late, "leave"=>$cnt_leave);

        $counterBody = "";
        if($wholeweek){
            $summaryList = array("PRESENT","NO SIGN IN","LATE","LEAVE");
            foreach ($summaryList as $summaryindex => $summary) {
                $counterBody .= ($summaryindex % 2) ? '<tr style="color:black;">' : '<tr style="background-color:#e6e6e6;color:black;">';
                $counterBody .= '<td><b>'. $summary .'</b></td>';
                // loop each day
                $i = loggednoToDate($loggedno_monday);
                while($i <= loggednoToDate($loggedno_today)){
                    $cnt_present = 0;
                    $cnt_absent = 0;
                    $cnt_late = 0;
                    $cnt_leave = 0;
                    foreach($eeattendancelist as $eeattendance) {
                        if($eeattendance['loggedno'] == date('Ymd', strtotime($i))){
                            switch ($summaryindex) {
                                case 0:
                                    if($eeattendance['onleave'] == 1){
                                        if($eeattendance['wholeday'] = 0){
                                            if (!is_null($eeattendance['loggedin'])){
                                                $cnt_present += 1;
                                            }
                                        }
                                    } else if (!is_null($eeattendance['loggedin'])) {
                                        $cnt_present += 1;
                                    }
                                    break;

                                case 1:
                                    if (is_null($eeattendance['loggedin'])) {
                                        $cnt_absent += 1;
                                    }
                                    break;

                                case 2:
                                    if ($eeattendance['late'] == 1) {
                                        $cnt_late += 1;
                                    }
                                    break;

                                case 3:
                                    if($eeattendance['onleave'] == 1){
                                        $cnt_leave += 1;
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }
                    }
                    switch ($summaryindex) {
                        case 0:
                            $counterBody .= '<td align="center">'. $cnt_present .'</td>';
                            break;

                        case 1:
                            $counterBody .= '<td align="center">'. $cnt_absent .'</td>';
                            break;

                        case 2:
                            $counterBody .= '<td align="center">'. $cnt_late .'</td>';
                            break;

                        case 3:
                            $counterBody .= '<td align="center">'. $cnt_leave .'</td>';
                            break;

                        default:
                            break;
                    }
                    $i = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($i)));
                }
                $counterBody .= '</tr>';
            }
        } else {
            // if($wholeweek){
            //     $i = $loggedno_monday;
            // } else {
                $i = $loggedno_today;
            // }
            // while($i <= $loggedno_today){
                $cnt_present = 0;
                $cnt_absent = 0;
                $cnt_late = 0;
                $cnt_leave = 0;
                foreach($eeattendancelist as $eeattendance) {
                    if($eeattendance['loggedno'] == $i){
                        if($eeattendance['onleave'] == 1){
                            $cnt_leave += 1;
                            if($eeattendance['wholeday'] = 0){
                                if (!is_null($eeattendance['loggedin'])){
                                    $cnt_present += 1;
                                }
                            }
                        } else if (!is_null($eeattendance['loggedin'])) {
                            $cnt_present += 1;
                        }
                        if (is_null($eeattendance['loggedin'])) {
                            $cnt_absent += 1;
                        }
                        if ($eeattendance['late'] == 1) {
                            $cnt_late += 1;
                        }
                    }
                }
                if(($loggedno_today - $i) % 2){
                    $counterBody .= "<tr>";
                } else {
                    $counterBody .= '<tr style="background-color:#e6e6e6;">';
                }
                $counterBody .= '
                                        <td>'.date('D d M yy',strtotime('-' . ($loggedno_today - $i) . ' days',$stamp_today)).'</td>
                                        <td align="center">'.$cnt_present.'</td>
                                        <td align="center">'.$cnt_absent.'</td>
                                        <td align="center">'.$cnt_late.'</td>
                                        <td align="center">'.$cnt_leave.'</td>
                                    </tr>
                                ';
                $i++;
            // }
        }
        $daterange = array("today"=>$stamp_today);
        if($wholeweek) $daterange['monday'] = $stamp_monday;
        // goto exitme;    // test
        sendMailToGm(
            array("counterbody"=>$counterBody, "tbody"=>$tableBody, 
                "loggeddate"=>$daterange, "wholeweek"=>$wholeweek,
                "gmName"=>$gmFullName, "gmEmail"=>$gmEmail, "hrEmails"=>$hrEmails,
                "ofc"=>$ofc, "ofcname"=>$ofcname)
        );

        // echo "present: $cnt_present <br>absent: $cnt_absent <br>late: $cnt_late <br>leave: $cnt_leave";   
        exitme:
        $cn->close();
        $cn = NULL;
        unset($cn);
    }

    
    function sendMailToGm($data) {
        $wholeweek = $data['wholeweek'];
        // $counter = $data['counter'];
        $tbody = $data['tbody'];
        $stamp_today = $data['loggeddate']['today'];
        $stamp_monday = '';
        $loggeddate = '';
        if($wholeweek){
            $stamp_monday = $data['loggeddate']['monday'];
            $loggedmonday = date('D d M',$stamp_monday);
            $loggeddate .= $loggedmonday . ' to ';
        }
        $loggedtoday = date('D d M yy',$stamp_today);
        $loggeddate .= $loggedtoday;


        $gmEmail = $data['gmEmail'];
        // $gmEmail = 'vivencia.velasco@abacare.com'; // test
        $gmName = $data['gmName'];
        $hrEmails = $data['hrEmails'];
        $counterbody = $data['counterbody'];
        $ofc = $data['ofc'];

        $ofcname = strtoupper($data['ofcname']);
        $subject = '';
        if($wholeweek){
            $subject = "$loggeddate Weekly Timesheet Report $ofcname";
        } else {
            $subject = "$loggeddate timesheet report";
        }

        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail = mailTemplate($mail);
        $mail->FromName = "abacare International Limited";
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        if($tbody != ""){
            if($ofc == 'SO0003'){ // temp
                $mail->AddAddress('katherine.abella@abacare.com');
            } else {
                $mail->AddAddress($gmEmail,$gmName); // requestor
                foreach ($hrEmails as $hrEmail) {
                    $mail->AddCC($hrEmail);
                }
                // $mail->AddBCC('ralph.ocdol@abacare.com');
                $mail->AddBCC('katherine.abella@abacare.com');
            }
            $mail->AddBCC('cdm@abacare.com'); 
        } else {
            goto exitme;
        }

        if($wholeweek){
            $attendanceWhen = "this week";
        } else {
            $attendanceWhen = "today";
        }

        $head = '<h2 style="font-family: Calibri;">TIMESHEET REPORT</h2>';
        $head .= '<p style="font-family: Calibri;">Dear <b>'. $gmName .'</b>,</p>';
        $head .= '<span style="font-family: Calibri;">Below are the details of attendance for ' . $attendanceWhen . ', ' . $loggeddate . '.</span>';

        $summary = "";
        
        if($wholeweek){
            $instance = 0;
            $maxweekcnt = 5;
            $counterHead = "";
            $date_tmptoday = date('D d M yy',$stamp_today);
            // if($wholeweek){
            $date_tmp = date('D d M yy',$stamp_monday);
            $stamp_tmp = $stamp_monday;
            // $i = 0;
            while($stamp_tmp < $stamp_today){
                $counterHead .= '<th width="12%">'. $date_tmp .'</th>';
                // $i++;
                $instance++;
                $date_tmp = date('D d M yy',strtotime("+$instance days",$stamp_monday));
                $stamp_tmp = strtotime("+$instance days",$stamp_monday);

            }
            #region placeholder
            if($instance < $maxweekcnt){
                for($i = $instance; $i < $maxweekcnt; $i++){
                    $date_tmp = date('D d M yy',strtotime("+$i days",$stamp_monday));
                    $counterHead .= '<th width="12%">'. $date_tmp .'</th>';
                }
            }
            $summary .= '
                            <br>
                            <br>
                            <span style="padding-right:1em">A.</span> Summary <br>
                            <table cellpadding="1" cellspacing="3" border="0" width="80%" style="font-family: Calibri;">
                                <tr style="background-color:#2f3037; color: #fff;">
                                    <th width="20%" align="left">DETAILS</th>
                                    '.$counterHead.'
                                </tr>'.
                                $counterbody
    
                            .'</table>
                        ';
        } else {
            $summary .= '
                            <table cellpadding="1" cellspacing="3" border="0" width="80%" style="font-family: Calibri;">
                                <tr style="background-color:#2f3037; color: #fff;">
                                    <th width="20%" align="left">DATE</th>
                                    <th width="15%">PRESENT</th>
                                    <th width="15%">NO SIGN IN</th>
                                    <th width="15%">LATE</th>
                                    <th width="15%">LEAVE</th>
                                </tr>'.
                                $counterbody
    
                            .'</table>
                        ';
        }

        $summary .= '<br>';
        
        if($wholeweek){ // whole week, weekly report
            $instance = 0;
            $maxweekcnt = 5;
            $dateHeader = '';
            $date_tmptoday = date('D d M yy',$stamp_today);
            // if($wholeweek){
            $date_tmp = date('D d M yy',$stamp_monday);
            $stamp_tmp = $stamp_monday;
            // $i = 0;
            while($stamp_tmp < $stamp_today){
                $dateHeader .= '<th width="12%">'. $date_tmp .'</th>';
                // $i++;
                $instance++;
                $date_tmp = date('D d M yy',strtotime("+$instance days",$stamp_monday));
                $stamp_tmp = strtotime("+$instance days",$stamp_monday);

            }
            #region placeholder
            if($instance < $maxweekcnt){
                for($i = $instance; $i < $maxweekcnt; $i++){
                    $date_tmp = date('D d M yy',strtotime("+$i days",$stamp_monday));
                    $dateHeader .= '<th width="12%">'. $date_tmp .'</th>';
                }
            }
                #endregion
            // } else {
            //     $dateHeader .= '<th width="65%">'. $date_tmptoday .'</th>';
            //     $instance++;
            // }

            $labels = '';
            $tfoot = '';
            for($i = 0; $i < $maxweekcnt; $i++){
                $tfoot .= '<td></td>';
            }

            $summary .= '
                            <span style="padding-right:1em">B.</span> Details <br>
                            <table cellpadding="1" cellspacing="3" border="0" width="80%" style="font-family: Calibri;">
                                <tr style="background-color:#2f3037; color: #fff;">
                                    <th width="20%" align="left">EMPLOYEE NAME</th>
                                    '.$dateHeader.'
                                </tr>
                                '.
                                $tbody
                            .'  <tr style="background-color:#2f3037;">
                                    <td></td>
                                    '.$tfoot.'
                                </tr>
                            </table>
                        ';
        } else {    // daily report
            $summary .= '
                            <table cellpadding="1" cellspacing="3" border="0" width="80%" style="font-family: Calibri;">
                                <tr style="background-color:#2f3037; color: #fff;">
                                <th width="20%" align="left"> EMPLOYEE NAME</th>
                                <th width="15%">TIME IN</th>
                                <th width="45%" align="left"> REMARKS</th>
                            </tr>'.
                            $tbody
                        .'  <tr style="background-color:#2f3037;">
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    ';
        }


        $foot = "";
        $foot .= '<br /><p style="font-family: Calibri;">This is a system generated email.<br />';
        $foot .= "PLEASE DO NOT REPLY TO THIS MESSAGE.</p>";


        #region testing
        // $foot .= "<br/><br/>TEST AREA<br/>";
        // $foot .= "GM Email: " . $data['gmEmail'] . "<br/>";
        // foreach ($hrEmails as $index => $hrEmail) {
        //     $foot .= "HR" . $index . " Email: " . $hrEmail . "<br/>";
        // }
        #endregion
        // echo $head . $summary . $foot;
        // echo '<br><br>';
        $mail->Body = $head . $summary . $foot;
        if(!$mail->Send()){echo __LINE__ . " Failed to send email for " . $gmEmail ."<br>";} // SEND EMAIL
        // echo var_dump($mail);
        exitme: echo '';
    }

    function generateBlankTable($data){
        $active_ee = $data['active_ee'];
        $wholeweek = $data['wholeweek'];
        if($wholeweek) $loggedno_monday = $data['loggedno_monday'];
        $loggedno_today = $data['loggedno_today'];


        $emptyTableAttendance = array();
        $dwVal = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        foreach($active_ee as $this_ee){
            if($wholeweek){
                $i = loggednoToDate($loggedno_monday);
            } else {
                $i = loggednoToDate($loggedno_today);
            }
            $j = 1;
            while($i <= loggednoToDate($loggedno_today)){
                $blankAttendance = array("leavetype"=>''
                                        ,"wknum"=>(int)$j
                                        ,"wkname"=>$dwVal[$j]
                                        ,"loggedno"=>(int)date('Ymd', strtotime($i))
                                        ,"loggedin"=>NULL
                                        ,"loggedout"=>NULL
                                        ,"holiday"=>0
                                        ,"onleave"=>0
                                        ,"wholeday"=>0
                                        ,"firsthalf"=>0
                                        ,"secondhalf"=>0
                                        ,"leaveid"=>NULL
                                        ,"status"=>1
                                        ,"late"=>0
                                        ,"startshift"=>NULL
                                        ,"remarks"=>''
                                        );
                $emptyTableAttendance[] = array_merge($this_ee,$blankAttendance);
                $i = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($i)));
                $j++;
            }
        }
        return $emptyTableAttendance;
    }

    function loggednoToDate($loggedno){
        $logYear = substr($loggedno,0,4);
        $logMonth = substr($loggedno,4,2);
        $logDay = substr($loggedno,6,2);

        $convertDate = date('Y-m-d', strtotime("$logMonth/$logDay/$logYear")) . ' 00:00:00';

        return $convertDate;
    }
    // // required headers
    // header("Access-Control-Allow-Origin: *");
    // // header("Content-Type: application/json; charset=UTF-8");
    // header("Expires: 0");
    // header("Cache-Control: no-store, no-cache, must-revalidate");
    // header("Cache-Control: post-check=0, pre-check=0", false);
    // header("Pragma: no-cache");
?>