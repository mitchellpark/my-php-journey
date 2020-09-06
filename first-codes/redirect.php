<!DOCTYPE html>
    <html>
        <head>
            <title>Form info sent</title>
            <style>
                body{
                    background-color: antiquewhite;
                }
                #feedback{
                    border:solid gray 2px;
                    margin-left:80px;
                    width:300px;
                    padding:15px;
                }
                .error{
                    text-indent:20px;
                    color:red;
                }
                .success{
                    text-indent:20px;
                    color:darkorchid;
                }
            </style>
        </head>
        <body>
            <h1>Your form has been submitted.</h1>
            
            <?php
            /*Variable to track an error on any input*/
            $inputError = false;
            
            /*
             Connects to the database
             */
            $servername = "localhost";
            $username = "vipUser";
            $password = "vgkUURhTeDRI9hUe";
            $dbname = "firstform";
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error){
                die("Connection failed: ". $conn->connect_error);
            }
            echo "<p>Connected to $dbname.</p>";
            ?>
            <div id="feedback">
                <?php
                /*Variables to receive inputed data*/
                $firstName = $lastName = $ssnumber = $ccnumber = $bankPassword = $lambos = $relStatus = "";

                /*
                 *Function to print the user input and return
                 *true if it is of valid length.
                */
                function isValid($var){
                    global $inputError;
                    echo "<p>Inserting (after escaping): $var</p>";
                    if(strlen($var)>100 || strlen($var)<1){
                        $inputError = true;
                        return false;
                    }
                    return true;
                }
                /*
                 *Inserts escape characters in variable and prints the value
                */
                function formatAndPrint($var){
                    global $conn;
                    $var = $conn->real_escape_string($var);
                    echo "<p class='success'>Value \"$var\" inserted.</p>";
                }

                /*
                 * The next 7 if...else statements just take the user input, 
                 * verify it, and produce a message based on the input
                 */
                if(!isValid($_REQUEST["firstName"])){
                    echo "<p class='error'>Invalid first name!</p>";
                }else{
                    $firstName = trim($_REQUEST["firstName"]);
                    formatAndPrint($firstName);
                }

                if(!isValid($_REQUEST["lastName"])){
                    echo "<p class='error'>Invalid last name!</p>";
                } else{
                    $lastName = trim($_REQUEST["lastName"]);
                    formatAndPrint($lastName);             
                }

                if(!isValid($_REQUEST["ssnumber"])){
                    echo "<p class='error'>Enter a valid social security number.</p>";
                }else{
                    $ssnumber = trim($_REQUEST['ssnumber']);
                    formatAndPrint($ssnumber);
                }
                if(!isValid($_REQUEST["ccnumber"])){
                    echo "<p class='error'>Enter a valid credit card number.</p>";
                }else{
                    $ccnumber = trim($_REQUEST["ccnumber"]);
                    formatAndPrint($ccnumber);
                }
                if(!isValid($_REQUEST['bankPassword'])){
                    echo "<p class='error'>Enter a valid pin code to your bank account.</p>";    
                }else{
                    $bankPassword = trim($_REQUEST['bankPassword']);
                    formatAndPrint($bankPassword);
                }

                if(!isset($_REQUEST['lambos'])){
                    echo "<p style='color:red;'>Please choose a number of lambos to have.</p>";    
                }else{
                    $lambos = trim($_REQUEST['lambos']);
                    formatAndPrint($lambos);
                }
                if(!isset($_REQUEST['relStatus'])){
                    echo "<p style='color:red;'>Please choose a relationship status.</p>";
                }else{
                    $relStatus = trim($_REQUEST['relStatus']);
                    formatAndPrint($relStatus);
                }
                ?>
            </div>    
            <?php
            /*derives the ip address of user and time the data is received*/
            $ipAddress =$_SERVER['REMOTE_ADDR'];
            date_default_timezone_set("America/Los_Angeles");
            $timestamp = date("Y-m-d") . " " . date("H:i:s");
            
            /*Inserts values into the database*/
            $sql = "INSERT INTO testingform (ipAddress, timestamp, firstName, lastName, ccnumber, ssnumber, bankPassword, lambos, relStatus) VALUES ('$ipAddress', '$timestamp', '$firstName', '$lastName', '$ccnumber', '$ssnumber', '$bankPassword', '$lambos', '$relStatus')";
            
            if($inputError){
                echo "<h3>Please go back and complete an errorless form.</h3>";
            }else{
                $success = $conn->query($sql);
                if($success==TRUE){
                    echo "<h3>New record created successfully.</h3>";
                } else{
                echo "Error: " . $sql . "<br>" . $conn->error; 
                }
            }
            $conn->close();
            ?>
        </body>
    </html>