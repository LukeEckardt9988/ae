<?php
$username = $_POST["username"];
$passwort = md5($_POST["password"]);
session_start();
session_unset();
session_destroy();
$_SESSION = array();
header("Location: start.php");
exit;
?>
