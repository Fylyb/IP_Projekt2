<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
$sname = "example";
$uname = "example";
$password = "example";

$db_name = "example";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if(!$conn) {
    echo "Connection failed!";
}
