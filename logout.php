<?php
	session_start();
	include_once('inc/global.php');
	$endTime = time() - 1800;
	$_SESSION['abaUser'] = "";
	$_SESSION['ee']['abaini'] = "";
	session_destroy();
	unset($_COOKIE['ulvl']);
	setcookie('ulvl', '', $endTime, '/');
	header("Location: " . base_URL .'login/login.php');
	exit();
?>