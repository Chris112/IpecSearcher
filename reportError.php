<?php

error_reporting(0);

// get the q parameter from URL
$q = $_POST["errorDescription"];


$hint = "";

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

       $sql = "INSERT INTO reportError (time, reportDesc) VALUES (now(), \"" . $q . "\")";
    $result = mysqli_query($conn, $sql);


// mysqli_query returns false if something went wrong with the query
if ($result === FALSE) {
    echo 'Something went wrong in reportError.php';
} else {
    echo 'Successfully inserted report into database, thanks';
}


$conn->close();
?>