<?php
	function genuri($pass){
		$npass = "";
		$salt = "abacare";
		$md5 = md5($pass . $salt);
		$npass = md5($md5);

		return $npass;
	}

	$sesid = "wala sesid";

	if(isset($_GET['id']) && !empty($_GET{'id'})){
		$id = $_GET['id'];
		$sesid = genuri($id);
	}

	echo 'sesid: ' . $sesid;
?>