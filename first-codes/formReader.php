<!DOCTYPE html>
<html lang="en">
    <head>
        <title>The application</title>
        <style>
            body{
                background-color:aliceblue;
            }
            #container{
                background-color: antiquewhite;
                width:700px;
                margin: auto;
                margin-top:80px;
                border: 5px solid black;
                border-radius:30px;
            }
            header, article{
                margin:30px;
            }
            #paragraph{
                text-align:center;
                line-height:40px;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <?php
            $servername = "localhost";
            $username = "vipUser";
            $password = "pXjEV0fzVoGUGjsd";
            $dbName = "firstform";

            $conn = new mysqli($servername, $username, $password, $dbName);
            
            $firstName = $lastName = $ssnumber = $ccnumber = $bankPassword = $lambos = 
            $eliteness  = $relStatus = $comments= "";
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            
            date_default_timezone_set("America/Los_Angeles");
            $timestamp = date("Y-m-d") . " ". date("H:i:s"); 
            
            if($_SERVER['REQUEST_METHOD']=="POST"){
                global $conn;
                    $firstName = test_input($_REQUEST['firstName']);
                    $lastName = test_input($_REQUEST['lastName']);
                    $ssnumber = test_input($_REQUEST['ssnumber']);
                    $ccnumber = test_input($_RESQUEST['ccnumber']);
                    $bankPassword = test_input($_REQUEST['bankPassword']);
                    $lambos = test_input($_REQUEST['lambos']);
                    $relStatus = test_input($_REQUEST['relStatus']);
                    $comments = test_input($_REQUEST['comments']);
                }

            function test_input($var){
                $var = trim($var);
                $var = stripslashes($var);
                $var = htmlspecialchars($var);
                $var = $GLOBALS['conn']->real_escape_string($var);
                return $var;
            }

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql =  "INSERT INTO testingform (ipAddress, timestamp, firstName, lastName, ssnumber, ccnumber, bankPassword, lambos, relStatus) VALUES ('$ipAddress', '$timestamp' , '$firstName', '$lastName', '$ssnumber', '$ccnumber', '$bankPassword', '$lambos', '$relStatus')";

            if($conn->query($sql)==false){
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
            ?>
            <header><h1>Assuming you filled everything out:</h1></header>
            <article>
                <p id="paragraph">There once was a man named <?php echo $firstName;?>, who came from the family of <?php echo $lastName;?>s.     <br>In occurred to him that he was <?php echo $relStatus; ?>, so he decided to go to a club.
                    <br>Billy the bartender asked him how many cars he had, and he answered, "Only <?php echo $lambos;?> lambos."
                    <br>Impressed, Billy decides steal <?php echo $firstName;?>'s phone and announce all his personal info.
                    <br>Who wants a free social security number? Maybe you can use "<?php echo $ssnumber; ?>", but there's so many to choose!
                    <br>Who wants to make a brand new purchase? "<?php echo $ccnumber; ?>" is the number to use.
                    <br>And everyone, don't forget the password; the password is <?php echo $bankPassword; ?>!
                    <br>Flustered, <?php echo $firstName;?> yelled out,"<?php echo $comments;?>!!!"
                    <br>The end.
                </p>
            </article>
        </div>
    </body>
</html>