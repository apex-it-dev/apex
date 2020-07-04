<?php
    include_once("../inc/includes.php");
    include_once("../inc/sessions.php");

    if(isset($_POST['key'])) {
        $key = $_POST['key'];
        echo (json_encode($useraccess[$key]));
    }
    // print_r($useraccess);


?>