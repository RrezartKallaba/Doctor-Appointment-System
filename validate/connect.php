<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

$host = "localhost";
$username = "root";
$password = "";
$dbname = "doctor_appointments";

$connect = mysqli_connect($host, $username, $password, $dbname);
mysqli_set_charset($connect, "UTF8");
date_default_timezone_set('Europe/Belgrade'); // Zona kohore per kosove
// Check connection
if (!$connect) {
    die("Connection failed! ");
}
