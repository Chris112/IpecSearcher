<?php

error_reporting(0);

// get the q parameter from URL
$q = $_REQUEST["q"];
$rid = $_REQUEST["rid"];

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


$sql = "SELECT postcode FROM postcode_db WHERE suburb=\"" . $q . "\"";



//$result = mysqli_query($conn, $sql);
// Perform a query, check for error
if (!mysqli_query($conn, $sql)) {
    //echo("Error description: " . mysqli_error($con));
} else {
    $result = mysqli_query($conn, $sql);
}

// mysqli_query returns false if something went wrong with the query
if ($result === FALSE) {
    
} else {

    if (mysqli_num_rows($result) > 0) {
        $hint = "";
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            //echo "postcode: " . $row["postcode"] . " - Suburb: " . $row["suburb"] . " " . $row["state"] . "<br>";
            $currCode = $row['postcode'];
            if (
                    $currCode >= 2000 && $currCode <= 2999 || // NSW
                    $currCode >= 3000 && $currCode <= 3999 || // VIC
                    $currCode >= 4000 && $currCode <= 4999 || // QLD
                    $currCode >= 5000 && $currCode <= 5799 || // SA
                    $currCode >= 6000 && $currCode <= 6797 || // WA
                    $currCode >= 7000 && $currCode <= 7799 || // TAS
                    $currCode >= 800 && $currCode <= 899  // NT
            ) {
                if ($hint === "") {
                    $hint = $row['postcode'];
                } else {
                    $hint .= ", " . $row['postcode'];
                }
            }
        }
    } else {
        $hint = "No results";
    }
}

echo $hint . $rid;
$conn->close();
?>