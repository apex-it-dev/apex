<?php
    require_once(__DIR__ . '/../../api/RSAUtils/function.php');
    $getkey = new RSAUtilsAba;
    $key = $getkey->runRSA(MethodEnum::getPub);
    unset($getkey);
    echo $key;
?>