<?php
    session_start();
    if(!empty($_SESSION["email"]))
    {
        $_SESSION = array();
    }
    header("Location: https://goosekbd.com/index.php");
    exit();
?>
