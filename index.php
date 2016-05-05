<?php
include 'database.php';
$servername = "postcodesdb.c8pgxpiq6qeo.us-west-2.rds.amazonaws.com:3306";
$username = "cjwebb90";
$password = "Darwin112";
$dbname = "postcodeDB";

abstract class AbstractAddress {

// property declaration
    public $AddressID;
    public $DoorID;
    public $UnitNumber;
    public $StreetNumber;
    public $StreetName;
    public $Suburb;
    public $Postcode;

//method declaration
    public function printAddress() {
        $text = "AddressID: " . $this->AddressID . ", DoorID: " . $this->DoorID . ", Address: ";
        if (isset($this->$UnitNumber)) {
            $text += $this->$UnitNumber . "/" . $this->$StreetNumber . " " . $this->$StreetName . ", " . $this->$Suburb . ", " . $this->$Postcode;
        } else {
            $text += $this->$StreetNumber . " " . $this->$StreetName . ", " . $this->$Suburb . ", " . $this->$Postcode;
        }
        return $text;
    }

}

// Is just an abstact class implemented at the moment
class PrivateAddress extends AbstractAddress {

    public function printAddress() {
        return parent::printAddress();
    }

}

class BusinessAddress extends AbstractAddress {

    public function printAddress() {
        return parent::printAddress();
    }

}

class ShoppingCenter extends AbstractAddress {

    public $ShoppingCenterName;

    public function printAddress() {
        return parent::printAddress();
    }

}

// A shop within a shopping center. Belongs to a single Shopping center
class ShoppingCenterShop extends AbstractAddress {

    public $ShopNumber;
    public $ShoppingCenter;

    public function printAddress() {
        return parent::printAddress();
    }

}
?>

<html>
    <head>
        <title>IPEC Searcher</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="js/libs/twitter-bootstrap/css/bootstrap.css" type="text/css"/>
        <link href="theme.css" rel="stylesheet" type="text/css">

    </head>
    <body>

        <div class="jumbotron container">

            <!-- Heading with logo -->
            <center>
                <img align="middle" id="tollLogo" src="http://i.imgur.com/e1t9h3c.jpg" class="img-responsive" alt="logo">
            </center>
            <br>


            <div class="container">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs form-tabs" role="tablist">
                    <li class="active"><a href="#destination" role="tab" data-toggle="tab">Find Destination</a></li>
                    <li><a href="#suburbSearch" role="tab" data-toggle="tab" id="suburbTab">Find Suburb</a></li>
                    <li><a href="#postcodeSearch" role="tab" data-toggle="tab">Find Postcode</a></li>
                    <li><a href="#showMap" role="tab" data-toggle="tab"> Current map </a></li>
                    <li><a href="#reportErrorTab" role="tab" data-toggle="tab"> Report </a></li>
                    <li><a href="#insertAddress" role="tab" data-toggle="tab">New Address</a></li>
                    <li><a href="#addDoor" role="tab" data-toggle="tab">New Door</a></li>
                    <li><a href="#insertShoppingCenter" role="tab" data-toggle="tab">New SC</a></li>
                </ul>


                <!-- Tab panes -->
                <div class="tab-content">

                    <!-- Find destination tab content -->
                    <div class="tab-pane fade active in" id="destination">
                        <!-- Find Destination content -->
                        <h2>Enter suburb to find destination</h2>
                        <form role="form" method="post">
                            <div class="form-group form-inline">
                                <label class="control-label"><strong>Suburb</strong></label>
                                <input type="text" value="defaultValue" name="destinationSearchTxtBox" >
                            </div>
                            <label class="radio-inline"><input type="radio" name="optradio">Private Address</label>
                            <label class="radio-inline"><input type="radio" name="optradio">Shopping Center</label>
                            <br>
                            <button type="submit" name="Submit" class="btn btn-default">Submit</button>
                        </form>




                        <?php
                        if (isset($_POST['Submit'])) {
                            $userInput = $_POST['destinationSearchTxtBox'];
                            if ($userInput !== "") {

                                /* Understand and interprete input here */
                                // for now just use success shopping center and private
                                $userInput = "Success";





                                /* Find number of possibilities or doors with target suburb */
                                // for now just use success sc and private so 2
                                $destinationCount = possibleDestinationCount("Success");





                                /* Display all possibilities to user */
                                $scChecked = false;
                                $scDoorID = 0;
                                for ($i = 0; $i < $destinationCount; $i++) {


                                    //<!--2 columns, left map right details about destination -->
                                    echo '<div class = "container" style="width: 100%; margin: auto;"">';

                                    //<!--Details column -->
                                    echo '<div  style = "border:1px solid black; width: 100%; margin: auto; " >';
                                    echo '<h2>Details</h2>';

                                    echo '<div class = "table-responsive">';
                                    echo '<table class = "table" align="center" style="width: 90%; margin: auto;">';
                                    echo '<tbody>';
                                    echo '<tr>';


                                    // How do i find the doors that success goes to?
                                    // find number of possible doors
                                    //      SELECT DoorID FROM db WHERE suburb=userinput
                                    // now i know there are 2 doors success goes to
                                    // now i need to display info and map for 2 doors
                                    // check if there is a shopping center for target suburb at one of the doors AND at the suburb from user input
                                    if (!$scChecked) {
                                        $scDoorID = doesShoppingCenterExist("Success");
                                        if ($scDoorID != 0) {
                                            displayShoppingCenter("Success");
                                        }
                                        $scChecked = true;
                                    } else {
                                        displaySuburb("Success", $scDoorID);
                                    }

                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    $scChecked = true;
                                }


                                // display door info about user input for privates
                            } else {
                                print ("Invalid input, please enter valid input.");
                            }
                        }
                        $_POST['Submit'] = null;
                        ?>


                    </div>





                    <!-- Find Suburb tab content -->
                    <div class="tab-pane fade" id="suburbSearch">
                        <h2>Enter postcode to find the suburb</h2>Enter postcode below:
                        <form>
                            <input type="hidden" id="lastUsedRID" name="lastUsedRID" value="0">
                            <input class="form-control" type="text" placeholder="eg: 6000" onkeyup="showHintSuburb(this.value)">
                            Suburb: <span id="suburbHint"></span>
                        </form>

                    </div>


                    <!-- Find Postcode tab content -->
                    <div class="tab-pane fade" id="postcodeSearch">
                        <h2>Enter suburb to find postcode</h2>Enter suburb below:
                        <form>
                            <input class="form-control" type="text" placeholder="eg: Mount Lawley"  onkeyup="showHintPostcode(this.value)">
                        </form>
                        Postcode: <span id="postcodeHint"></span>
                    </div>


                    <!-- Show map tab content -->
                    <div class="tab-pane fade" id="showMap">
                        <h2>Current map</h2>
                        <!--<div class="col-xs-8">-->
                        <img src="http://i.imgur.com/hciAGVf.jpg" style="width: 50%; display: block; margin-left: auto; margin-right: auto;" class="img-responsive" alt="warehouse map cleaned"/>
                    </div>



                    <!-- Report Error content -->
                    <div class="tab-pane fade" id="reportErrorTab">
                        <h2>Report Error</h2>
                        Please include as much detail as you can

                        <form action="reportError.php" method="post">
                            <p>Description of error: <input type="text" name="errorDescription" /></p>
                            <input type="submit" name="submit" value="Submit" />
                        </form>
                    </div>





                    <!-- Add address content -->
                    <div class="tab-pane fade" id="insertAddress" >
                        <h2>Add address to database</h2>
                        <form role="form" method="post" action="insertAddress.php"> <!-- todo: make form call insertAddress() somehow and get values from fields -->
                            <div class="col-md-4">
                                Door ID: <input class="form-control" name="doorID" type="text" placeholder="Door #">
                                Suburb: <input class="form-control" name="suburbName" type="text" placeholder="Suburb">
                                Postcode: <input class="form-control" name="postcode" type="text" placeholder="eg: 6000">
                            </div>
                            <div class="col-md-4" >
                                Unit Number: <input class="form-control" name="unitNumber" type="text" placeholder="Unit #">
                                Street Number: <input class="form-control" name="streetNumber" type="text" placeholder="Street #">
                                Street Name: <input class="form-control" name="streetName" type="text" placeholder="Street Name">
                            </div>
                            <div class="col-md-4" >
                                Shop name: <input class="form-control" name="shopName" type="text" placeholder="eg: Target">
                                Shop Number: <input class="form-control" name="shopNumber" type="text" placeholder="Shopping centers">
                            </div>
                            <div style="width: 50%; margin: auto;">
                                <input class="form-control" name="notes" type="text" placeholder="notes">
                                <input type="submit" name="newAddressSubmit" value="Submit" />
                            </div>

                        </form>
                    </div>

                    <!-- Add Door content -->
                    <div class="tab-pane fade" id="addDoor">
                        <h2>Add door to database</h2>
                        <form role="form" method="post" action="insertDoor.php"> 
                            <div style="width: 30%; margin: auto;">
                                Door ID: <input class="form-control" name="doorID" type="text" placeholder="Door number">
                                Zone ID: <input class="form-control" name="zoneID" type="text" placeholder="for now always just use 1">
                                Description: <input class="form-control" name="desc" type="text" placeholder="Description of what belongs at door">
                                <input type="submit" name="newDoorSubmit" value="Submit" />
                            </div>
                        </form>
                    </div>



                    <!-- Insert Shopping Center content -->
                    <div class="tab-pane fade" id="insertShoppingCenter">
                        <h2>Add shopping center to database</h2>
                        <form role="form" method="post" action="insertShoppingCenter.php"> 
                            <div style="width: 30%; margin: auto;">
                                Name: <input class="form-control" name="scName" type="text" placeholder="Shopping center name here">
                                Street Number: <input class="form-control" name="streetNumber" type="text" placeholder="Street number here">
                                Street Name: <input class="form-control" name="streetName" type="text" placeholder="Street name here">
                                Suburb: <input class="form-control" name="suburb" type="text" placeholder="Suburb here">
                                Postcode: <input class="form-control" name="postcode" type="text" placeholder="Postcode here">
                                Door ID: <input class="form-control" name="doorID" type="text" placeholder="Door number">
                                <input type="submit" name="newSCSubmit" value="Submit" />
                            </div>
                        </form>
                    </div>


                    <script>




                        $rid = 1000;
                        $lastUsedRID = -1;
                        function showHintSuburb(str) {
                            if (str.length == 0) {
                                document.getElementById("suburbHint").innerHTML = "";
                                return;
                            } else {
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function () {
                                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                                        // get new RID
                                        var OriginalString = xmlhttp.responseText;
                                        var StrippedString = OriginalString.replace(/(<([^>]+)>)/ig, "");
                                        var currRID = StrippedString.substr(StrippedString.length - 4, StrippedString.length);

                                        // if new RID is greater than previous RID, update element otherwise do nothing
                                        if (currRID > $("#lastUsedRID").val()) {
                                            document.getElementById("suburbHint").innerHTML = xmlhttp.responseText.substr(0, xmlhttp.responseText.length - 4);
                                            $("#lastUsedRID").val(currRID);
                                        }
                                    }
                                };
                                xmlhttp.open("GET", "suggestSuburb.php?q=" + str + "&rid=" + ++$rid, true);
                                xmlhttp.send();
                            }
                        }


                        function showHintPostcode(str) {
                            if (str.length == 0) {
                                document.getElementById("postcodeHint").innerHTML = "";
                                return;
                            } else {
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function () {
                                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                        // get new RID
                                        var OriginalString = xmlhttp.responseText;
                                        var StrippedString = OriginalString.replace(/(<([^>]+)>)/ig, "");
                                        var currRID = StrippedString.substr(StrippedString.length - 4, StrippedString.length);

                                        // if new RID is greater than previous RID, update element otherwise do nothing
                                        console.log(currRID + " vs " + $("#lastUsedRID").val());
                                        if (currRID > $("#lastUsedRID").val()) {

                                            //if (currRID > $rid) {
                                            document.getElementById("postcodeHint").innerHTML = xmlhttp.responseText.substr(0, xmlhttp.responseText.length - 4);
                                            //$lastUsedRID = currRID;
                                            $("#lastUsedRID").val(currRID);
                                        }
                                    }
                                };
                                xmlhttp.open("GET", "suggestPostcode.php?q=" + str + "&rid=" + ++$rid, true);
                                xmlhttp.send();
                            }
                        }


                    </script>




                </div> <!-- End of tab content  -->







                <!--<script> src = "js/libs/jquery/jquery.js" ></script>
                <script> src = "js/libs/twitter-bootstrap/js/bootstrap.js" ></script>
                <script> src = "js/libs/twitter-bootstrap/js/bootstrap.tab.js" ></script>-->

            </div> <!-- /container -->
        </div> <!-- /jumbotron -->




        <footer class="footer">
            <p>&copy; 2015 Company, Inc.</p>
        </footer>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </body>
</html>
