
<?php

	// Bootstrap core JavaScript
	srcInit('vendor/jquery/jquery.min.js');
	srcInit('vendor/bootstrap/js/bootstrap.bundle.min.js');

	// Core plugin JavaScript
	srcInit('vendor/jquery-easing/jquery.easing.min.js');

	// Custom scripts for all pages
	srcInit('js/sb-admin-2.min.js');
	// srcInit('vendor/jsencrypt/jsencrypt.min.js');
	srcInit('js/config.js');
	srcInit('js/functions.js');

	srcInit('js/jquery-ui.js');
	srcInit('js/jquery.blockUI.js');

	srcInit(VIEWS . 'sidebar.js');
	srcInit('login/changepassword.js');
	include_once('login/changepassword.php');
	
?>

