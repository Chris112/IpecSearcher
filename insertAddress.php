<?php

error_reporting(0);


// get the parameters from URL
$shopNameInput = $_POST['shopName'];
$doorIDInput = $_POST['doorID'];
$suburbInput = $_POST['suburbName'];
$postcodeInput = $_POST['postcode'];
$unitNumberInput = $_POST['unitNumber'];
$streetNumberInput = $_POST['streetNumber'];
$streetNameInput = $_POST['streetName'];
$shopNumberInput = $_POST['shopNumber'];
$notesInput = $_POST['notes'];

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

echo ("Shopname: '" . $shopNameInput . "'<br>");
echo ("DoorID: '" . $doorIDInput . "'<br>");
echo ("Suburb: '" . $suburbInput . "'<br>");
echo ("Postcode: '" . $postcodeInput . "'<br>");
echo ("UnitNumber: '" . $unitNumberInput . "'<br>");
echo ("Streetnumber: '" . $streetNumberInput . "'<br>");
echo ("Street name: '" . $streetNameInput . "'<br>");
echo ("Shop number: '" . $shopNumberInput . "'<br>");
echo ("Description: '" . $notesInput . "'<br>");

$sql = "INSERT INTO Address (";



if ($shopNameInput != '') {
    $sql .= "ShopName,";
}
$sql .= "DoorID,";
if ($unitNumberInput != '') {
    $sql .= "UnitNumber,";
}
//if ($streetNumberInput != null || $streetNumberInput != "" || $streetNumberInput != " ") {
if ($streetNumberInput != '') {
    $sql .= "StreetNumber,";
}
if ($streetNameInput != '') {
//if ($streetNameInput != null || $streetNameInput != "" || $streetNameInput != " ") {
    $sql .= "StreetName,";
}
$sql .= "Suburb,";
//if ($shopNumberInput != null || $shopNumberInput != "" || $shopNumberInput != " ") {
if ($shopNumberInput != '') {
    $sql .= "ShopNumber,";
}
$sql .= "Postcode";
if ($notesInput != '') {
//if ($notesInput != null || $notesInput != "" || $notesInput != " ") {
    $sql .= ",Notes)";
} else {
    $sql .= ")";
}

$sql .= " VALUES (";

if ($shopNameInput != '') {
//if ($shopNameInput != null || $shopNameInput != "" || $shopNameInput != " ") {
    $sql .= "\"" . $shopNameInput . "\",";
}
$sql .= $doorIDInput . ",";
//if ($unitNumberInput != null || $unitNumberInput != "" || $unitNumberInput != " ") {
if ($unitNumberInput != '') {
    $sql .= "\"" . $unitNumberInput . "\",";
}
//if ($streetNumberInput != null || $streetNumberInput != "" || $streetNumberInput != " ") {
if ($streetNumberInput != '') {
    $sql .= $streetNumberInput . ",";
}
//if ($streetNameInput != null || $streetNameInput != "" || $streetNameInput != " ") {
if ($streetNameInput != '') {
    $sql .= "\"" . $streetNameInput . "\",";
}
$sql .= "\"" . $suburbInput . "\",";
//if ($shopNumberInput != null || $shopNumberInput != "" || $shopNumberInput != " ") {
if ($shopNumberInput != '') {
    $sql .= "\"" . $shopNumberInput . "\",";
}
$sql .= $postcodeInput;
//if ($notesInput != null || $notesInput != "" || $notesInput != " ") {
if ($notesInput != '') {
    $sql .= ",\"" . $notesInput . "\")";
} else {
    $sql .= ")";
}

echo("attempted sql query:" . $sql . "<br>");

//$sql = "INSERT INTO Address (time, reportDesc) VALUES (now(), \"" . $q . "\")";
$result = mysqli_query($conn, $sql);
// mysqli_query returns false if something went wrong with the query
if ($result === FALSE) {
    echo 'Something went wrong in insertAddress.php';
} else {
    echo 'Successfully inserted address into database, thanks';
}


$conn->close();
?>