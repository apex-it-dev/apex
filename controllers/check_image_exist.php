<?php
    $imgpath = explode('?' ,$_POST['img'])[0];
    echo file_exists("../$imgpath") == 1 ? TRUE : FALSE;
?>