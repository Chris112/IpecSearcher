<?php

error_reporting(0);


// get the parameters from URL
$doorIDInput = $_POST['doorID'];
$zoneIDInput = $_POST['zoneID'];
$descInput = $_POST['desc'];

// connect to db server
$servername = "postcodesdb.c8pgxpiq6qeo.us-west-2.rds.amazonaws.com:3306";
$username = "cjwebb90";
$password = "Darwin112";
$dbname = "postcodeDB";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//echo ($doorIDInput . "<br>");
//echo ($zoneIDInput . "<br>");
//echo ($descInput . "<br>");

$sql = "INSERT INTO Door (";

$sql .= "DoorID,";
$sql .= "ZoneID,";
$sql .= "Description";

$sql .= ") VALUES (";

$sql .= $doorIDInput . ",";
$sql .= $zoneIDInput . ",";
$sql .= "\"" . $descInput . "\")";

echo("attempted sql query:" . $sql . "<br>");

//$sql = "INSERT INTO Address (time, reportDesc) VALUES (now(), \"" . $q . "\")";
$result = mysqli_query($conn, $sql);
// mysqli_query returns false if something went wrong with the query
if ($result === FALSE) {
    echo 'Something went wrong in insertDoor.php';
} else {
    echo 'Successfully inserted door into database, thanks';
}


$conn->close();
?>