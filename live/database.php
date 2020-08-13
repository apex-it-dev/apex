<?php
	class Database{
		private $cn = "";
		function connect(){
			$cn = new mysqli("162.241.24.185", "apexhris_apexuser", "0FMYYLPP3T", "apexhris_apex");
			//$cn = new mysqli("202.155.223.165", "uabacare", "Hj7cQzaA", "aba_abvt_dev");
			// $cn = new mysqli("localhost", "root", "5437", "aba_abvt", 3308);
			$cn->set_charset("utf8");

			if ($cn->connect_error) {
			    die("Connection failed: " . $cn->connect_error);
			    exit();
			} 

			return $cn;
		}

		function closeDB(){
			mysqli_close($cn);
			unset($cn);
			$cn = null;
		}
	}
?>