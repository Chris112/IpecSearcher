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

$sql = "";
switch (strlen($q)) {
    case 1:
      //  $sql = "SELECT * from postcode_db WHERE postcode LIKE '" . $q . "___'";
      //  break;
    case 2:
     //   $sql = "SELECT * from postcode_db WHERE postcode LIKE '" . $q . "__'";
      //  break;
    case 3:
      //  $sql = "SELECT * from postcode_db WHERE postcode LIKE '" . $q . "_'";
        //break;
        $hint = "";
        break;
    case 4:
        $sql = "SELECT * from postcode_db WHERE postcode=" . $q;
        $hint = "No results found";
        break;
    default:
        echo "no suggestions";
}


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
            if ($hint === "") {
                $hint = $row['suburb'];
            } else {
                $hint .= ", " . $row['suburb'];
            }
        }
    } 
}

echo $hint . $rid;
$conn->close();
?>