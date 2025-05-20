<?php
session_start();
?>
<?php
include "connect.php"; // Ensure $conn is defined as the mysqli connection object in connect.php
$username = $_POST["username"];
$passwort = md5($_POST["passlog"]);

// echo "POSTDATEN: ".$username." -> ".$passwort."<br>";



$ergebnis = mysqli_query($conn, "SELECT username, passwort, id, abt FROM adlogin WHERE username = '$username'");
// $db_user= mysql_result($ergebnis, 0, "username") ;
// $db_pass= mysql_result($ergebnis, 0, "passwort") ;

$row = mysqli_fetch_row($ergebnis);
// echo "USER: ".$row[0]."<br>"; // 42
// echo "PASS: ".$row[1]."<br>";; // Der Wert von email

$db_user= $row[0];
$db_pass=$row[1];
$db_id=$row[2];
$db_abt=$row[3];
// echo $abfrage."<br>";
// echo "Anmeldu -> PASS: ".$passwort."<br>";



if($db_pass == $passwort)
    {
    $_SESSION["username"] = $username;
	$_SESSION["user_id_log"] = $db_id;
	$_SESSION["user_abt_log"] = $db_abt;
    // echo "Login erfolgreich. <br> <a href=\"start.php\">Geschützer Bereich</a>";
	//echo "Ergebnis -> PASS: ".$db_id." / ".$_SESSION['userid'];
	header("Location: start.php");
	exit;
	}
else
    {
    echo "<label><div align='center'><big>Benutzername bzw. Passwort waren falsch... <a href=\"start.php\">Startseite</a></big></div></label><br>";
	session_start();
	session_unset();
	session_destroy();
	$_SESSION = array();
    }
?> 