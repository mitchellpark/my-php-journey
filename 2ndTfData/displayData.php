<!DOCTPYE html>
<html>
    <head>
        <title>Displaying Data...</title>
        <style>
            body{
                background-color: skyblue;
            }
            div{
                font-size: 20px;
                background-color:aliceblue;
                margin:auto;
                text-align: center;
            }
            #content{
                padding:30px;
                border-radius: 10px;
                border: 3px solid black;
                width: 60%;
                margin:auto;
                margin-top:5%;
                margin-bottom:5%;
            }
            #table{
                display:block;
                width:80%;
                padding:0px;
                border:1px solid black;
                margin-bottom: 10px;
            }
            #header{
                background-color: cornsilk;
                border:1px solid black;
                margin:0;
            }
            #eventName{
                font-size: 30px;
            }
            table, th, td{
                margin:auto;
                padding:3px;
                border:1px solid black;
                border-collapse:collapse;
                background-color:white;
            }
            th{
                background-color:darkgrey;
            }
        </style>
    </head>
    <body>
        <?php
        require 'protocol12345.php';
        
        //Receiving metadata about the specific event
        $eventID = unserialize(base64_decode($_POST["eventID"]));
        $sections = unserialize(base64_decode($_POST["sectionsArr"]));
        $eventName = unserialize(base64_decode($_POST["eventName"]));
        
        //Forming a connection with the database
        $servername = '127.0.0.1';
        $username = "root";
        $password = "";
        $dbname = "tfData";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if($conn->connect_error){
            die("Connection error: " . $connect_error);
        }
        
        //Creates Protocol classes to later access the displayTable() function
        if($eventID=="101" || $eventID=="105"){
            $event = new protocol1or5;
        }else $event = new protocol234;
        ?>
        
        <div id="content">
            <div id="eventName"><b><?=$eventName?></b></div>
            <p style="font-size:14px">*Note that 1st place doesn't have a time difference, because he/she has no one to catch up to.</p>
            
            <?php
            //Per section, sends a call to displayTable, sending specific section numbers.
            for($i=0; $i<count($sections); $i++){
                $sectionNum = $i+1;
                echo "<div id='table'><div id='header'>Section $sectionNum</div><p>".$event->displayTable($eventID, $sections[$i])."</div>";
            }
            ?>
        </div>
    </body>
</html>