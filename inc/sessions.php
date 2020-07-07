<?php
	// session_destroy();
	session_start();
	if(isset($_SESSION['ee']['abaini'])){
        // print_r($_SESSION);
        // exit();
		$abaini = $_SESSION['ee']['abaini'];
		$abaemail = $_SESSION['ee']['abaemail'];
		$userid = $_SESSION['ee']['userid'];
		$eename = $_SESSION['ee']['name'];
		$eejt = $_SESSION['ee']['jobtitle'];
		$ofc = $_SESSION['ee']['ofc'];
		$ofcname = $_SESSION['ee']['ofcname'];
		$rank = $_SESSION['ee']['rank'];
		$dept = $_SESSION['ee']['dept'];
		$pos = $_SESSION['ee']['pos'];
		
		$avatartmp = $_SESSION['ee']['avatar'];
		$avatarpath = $_SESSION['ee']['avatarpath'];

		$useraccess = isset($_SESSION['useraccess']['aces']) ? $_SESSION['useraccess']['aces'] : null;
		$useraccesshermes = isset($_SESSION['useraccess']['hermes']) ? $_SESSION['useraccess']['hermes'] : null;
		$useraccessportal = isset($_SESSION['useraccess']['otapps']) ? $_SESSION['useraccess']['otapps'] : null;
		

		// echo '<pre>';
		// print_r($_SESSION['useraccess']);
		// echo '</pre>';
		// exit();
		
		// if($_SESSION['hasPWChanged'] == 0){
		// 	echo '<script type="text/javascript">alert("Its your first time to logged in to this website and you are required to changed your password immediately!");</script>';
		// 	echo '<script type="text/javascript">window.location="'. CHANGEPASSWORD .'"</script>';
		// 	exit();
		// }
	}
	
	if(!isset($_SESSION['ee']['abaini'])){
		$headerprotocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://";
		$urlsub = explode('/', $_SERVER['PHP_SELF']);
		$domain = $urlsub[1];
		unset($urlsub[1]);
		$prevurl = base64_encode($headerprotocol.$_SERVER['HTTP_HOST'].'/'.$domain.implode('/',$urlsub));
		header("Location: ".base_URL."login/login.php");
		exit();
	}
?>