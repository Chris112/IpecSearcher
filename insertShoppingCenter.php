<?php

error_reporting(0);


// get the parameters from URL
$scName = $_POST['scName'];
$streetNumber = $_POST['streetNumber'];
$streetName = $_POST['streetName'];
$suburb = $_POST['suburb'];
$postcode = $_POST['postcode'];
$doorID = $_POST['doorID'];

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

$sql = "INSERT INTO ShoppingCenter (";


if ($scName != '') {
    $sql .= "Name,";
}
if ($streetNumber != '') {
    $sql .= "StreetNumber,";
}
if ($streetName != '') {
    $sql .= "StreetName,";
}
if ($suburb != '') {
    $sql .= "Suburb,";
}
if ($postcode != '') {
    $sql .= "Postcode,";
}
if ($doorID != '') {
    $sql .= "DoorID";
}


$sql .= ") VALUES (";

if ($scName != '') {
    $sql .= "\"" . $scName . "\",";
}
if ($streetNumber != '') {
    $sql .= $streetNumber . ",";
}
if ($streetName != '') {
    $sql .= "\"" . $streetName . "\",";
}
if ($suburb != '') {
    $sql .= "\"" . $suburb . "\",";
}
if ($postcode != '') {
    $sql .= $postcode . ",";
}
if ($doorID != '') {
    $sql .= $doorID . ")";
}

echo("attempted sql query:" . $sql . "<br>");

//$sql = "INSERT INTO Address (time, reportDesc) VALUES (now(), \"" . $q . "\")";
$result = mysqli_query($conn, $sql);
// mysqli_query returns false if something went wrong with the query
if ($result === FALSE) {
    echo 'Something went wrong in insertShoppingCenter.php';
} else {
    echo 'Successfully inserted ShoppingCenter into database, thanks';
}


$conn->close();
?>