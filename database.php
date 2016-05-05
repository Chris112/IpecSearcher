<?php

function createDBConnection() {
    $servername = "postcodesdb.c8pgxpiq6qeo.us-west-2.rds.amazonaws.com:3306";
    $username = "cjwebb90";
    $password = "Darwin112";
    $dbname = "postcodeDB";

// Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        return $conn;
    }
}

// Check to see if suburb has a shopping center associated with it, if so, return the door number.
function doesShoppingCenterExist($suburb) {
    $conn = createDBConnection();
    $query = 'SELECT `DoorID` FROM `ShoppingCenter` WHERE Suburb="' . $suburb . '"';
    $result = mysqli_query($conn, $query);
    $scDoorID = -1;

    if (mysqli_num_rows($result) == 0) {
        // No shopping center for target suburb
        $scDoorID = 0;
    } else {
        $row = mysqli_fetch_assoc($result);
        $scDoorID = $row["DoorID"];
    }
    mysqli_free_result($result);
    return $scDoorID;
}

function displayShoppingCenter($suburb) {
    $conn = createDBConnection();
    $query = 'SELECT * FROM `ShoppingCenter` WHERE Suburb="' . $suburb . '"';
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {

        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            displayMap($row["DoorID"]);
            echo '<td class="text-center"><b>Shopping Center</b></td>';
            echo '<td class="text-center">' . $row["Name"] . '</td>';
            echo '</tr>';
            echo '<td class="text-center"><b>Suburb</b></td>';
            echo '<td class="text-center">' . $row["Suburb"] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td class="text-center"><b>Address</b></td>';
            echo '<td class="text-center">' . $row["StreetNumber"] . " " . $row["StreetName"] . '</td>';
            echo '</tr>';
            echo '<td class="text-center"><b>Postcode</b></td>';
            echo '<td class="text-center">' . $row["Postcode"] . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td class="text-center"><b>Door</b></td>';
            echo '<td class="text-center">' . $row["DoorID"] . '</td>';
            echo '</tr>';

            // Get supervisor information
            $query2 = 'SELECT * FROM `Door` WHERE DoorID="' . $row["DoorID"] . '"';
            $result2 = mysqli_query($conn, $query2);
            $row2 = mysqli_fetch_assoc($result2);
            $zoneID = $row2["ZoneID"];
            displayZoneSupervisorInfo($zoneID);




            echo '</tbody>';
            echo '</table>';
        }
        mysqli_free_result($result);
    } else {
        echo "0 results";
    }

    $conn->close();
}

function displayZoneSupervisorInfo($zoneID) {

    $conn = createDBConnection();
    $query = 'SELECT `SupervisorID` FROM `Zone` WHERE ZoneID="' . $zoneID . '"';
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $supervisorID = $row["SupervisorID"];

    $query2 = 'SELECT * FROM `Staff` WHERE StaffID="' . $supervisorID . '"';
    $result2 = mysqli_query($conn, $query2);
    $row2 = mysqli_fetch_assoc($result2);

    echo '<tr>';
    echo '<td class="text-center"><b>Zone</b></td>';
    echo '<td class="text-center">' . $zoneID . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td class="text-center"><b>Supervisor</b></td>';
    echo '<td class="text-center">' . $row2["Name"] . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td class="text-center"><b>Contact</b></td>';
    echo '<td class="text-center">' . $row2["ContactNumber"] . '</td>';
    echo '</tr>';
}

function possibleDestinationCount($suburb) {
    $conn = createDBConnection();
    $query = 'SELECT DISTINCT DoorID FROM `Address` WHERE Suburb="' . $suburb . '"';
    $result = mysqli_query($conn, $query);
    //$destinationCount = mysqli_num_rows($result);
    $destinationCount = 0;
    while ($row = mysqli_fetch_array($result)) {
        $destinationCount++;
    }


    mysqli_free_result($result);
    return $destinationCount;
}

// Display details about a suburbs door, do not print the SC passed in param 2
function displaySuburb($suburb, $scDoorID) {
    $conn = createDBConnection();
    $query = 'SELECT DISTINCT DoorID FROM `Address` WHERE Suburb="Success"';
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        if ($row["DoorID"] != $scDoorID) {
            displayMap($row["DoorID"]);
            echo '<td class="text-center"><b>Suburb</b></td>';
            echo '<td class="text-center">' . $suburb . '</td>';
            echo '</tr>';
            echo '<td class="text-center"><b>Postcode</b></td>';

            $query3 = "SELECT postcode FROM postcode_db WHERE suburb=\"" . $suburb . "\"";
            $result3 = mysqli_query($conn, $query3);
            $row3 = mysqli_fetch_assoc($result3);
            $postcode = $row3["postcode"];

            echo '<td class="text-center">' . $postcode . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td class="text-center"><b>Door</b></td>';
            echo '<td class="text-center">' . $row["DoorID"] . '</td>';
            echo '</tr>';

            // Get supervisor information
            $query2 = 'SELECT * FROM `Door` WHERE DoorID="' . $row["DoorID"] . '"';
            $result2 = mysqli_query($conn, $query2);
            $row2 = mysqli_fetch_assoc($result2);
            $zoneID = $row2["ZoneID"];
            displayZoneSupervisorInfo($zoneID);




            echo '</tbody>';
            echo '</table>';
        }
    }
}

function displayMap($doorID) {
    //$query3 = "SELECT postcode FROM postcode_db WHERE suburb=\"" . $suburb . "\"";
    //$result3 = mysqli_query(createDBConnection(), $query3);
    //$row3 = mysqli_fetch_assoc($result3);
    //$postcode = $row3["postcode"];
    echo '<img class="img-responsive" src="maps/' . $doorID . '.jpg" align="middle" style="margin-left: auto; margin-right: auto;" alt="Map to Destination">';
    echo '<br>';
}

// Check to see if suburb has a shopping center associated with it, if so, return the door number.
function insertAddress() {

}
?>