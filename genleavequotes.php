<?php
	// include_once("inc/global.php");
 //  	include_once("inc/functions.php");

	// $cn = new mysqli("202.155.223.165", "uabacare", "Hj7cQzaA", "aba_abvt_dev");
 //    if ($cn->connect_error) {
 //        die("Connection failed: " . $cn->connect_error);
 //        exit();
 //    }

 //    $sqlees = "SELECT * FROM ". ABAPEOPLESMST ." WHERE " . ABAPEOPLESMST .".status = 1 AND ". ABAPEOPLESMST .".contactcategory = 1 ORDER BY ".ABAPEOPLESMST.".abaini ";
 //    $qryees = $cn->query($sqlees);

 //    if( $qryees === false) {
 //        echo $cn->error;
 //        exit();
 //    }

 //    $sqlben = "SELECT * FROM ". BENEFITSMST ." WHERE " . BENEFITSMST .".benefittype = 'leave' AND " . BENEFITSMST .".default = 1 ORDER BY ".BENEFITSMST.".sortno ";
 //    $qryben = $cn->query($sqlben);

 //    if( $qryben === false) {
 //        echo $cn->error;
 //        exit();
 //    }
    
 //    while($row = $qryben->fetch_array(MYSQLI_ASSOC)){
 //    	$rowsben[] = $row;
 //    }

 //    $cnt = 1;
 //    $fiscalyear = date('Y');
 //    while($row = $qryees->fetch_array(MYSQLI_ASSOC)){
 //    	$userid = $row['userid'];
 //    	$sqlleavecr = "SELECT * FROM ". LEAVECREDITSMST ." WHERE ". LEAVECREDITSMST .".userid = '$userid' AND ". LEAVECREDITSMST .".fiscalyear = '$fiscalyear' ";
 //    	// echo $sqlleavecr;
 //    	$qryleavecr = $cn->query($sqlleavecr);

 //    	if( $qryleavecr === false) {
	//         echo $cn->error .'<br />';
	//         exit();
	//     }

	//     $numleavecr = $qryleavecr->num_rows;
	//     // echo $numleavecr .' '. $sqlleavecr;
	//     if($numleavecr == 0){
	//     	// while($row1 = $qryben->fetch_array(MYSQLI_ASSOC)){
	//     	for($i=0;$i<count($rowsben);$i++){
	//     		$benefitini = $rowsben[$i]['benefitini'];
	//     		$regcr = $rowsben[$i]['reg_credit'];
	//     		$leavetypeid = strtoupper($benefitini) . substr($fiscalyear,2);

	//     		$sqlinscr = "INSERT INTO ". LEAVECREDITSMST ." (userid,leavetypeid,leavetype,fiscalyear,entitleddays,createdby,createddate) 
	//     					VALUES('$userid','$leavetypeid','$benefitini','$fiscalyear','$regcr','admin','$today')";
	//     		$cn->query($sqlinscr);
	//     		// echo $sqlinscr .'<br />';
	//     	}
	//     }
	//     // echo '<br />';
	//     $cnt++;
 //    }
 //    echo $cnt . '<br />DONE';
 //    exitme:
 //    $cn = null;
 //    unset($cn);
?>