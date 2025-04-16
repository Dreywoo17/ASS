<?php
session_start(); 
session_unset();
session_destroy();

header("Location: /itproject/Login/login.php");
exit();

?>
