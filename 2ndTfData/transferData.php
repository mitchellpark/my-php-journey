<html>
    <head>
        <title>Secret Backend Shenanigans</title>
        <style>
            body{
                background-color: black;
                color:green;
                font-family: monospace;
            }
            #content{
                font-size:20px;
                text-align:center;
            }
        </style>
    </head>
    <body>
        <div id="content">
            (Some intense PHP and SQL going on here)
            <p>To update, add .lif file to folder "/tfData."</p>
            <p>Click to <button onclick="window.location.reload()">Refresh</button>.</p>
        </div>
        
        <?php
        /*
            Creating a connection with the database
        */
        require 'protocol12345.php';
        $servername = 'localhost';
        $username = "root";
        $password = "";
        $dbname = "tfData";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if($conn->connect_error){
            die("Connection error: " . $connect_error);
        }
        
        /*
            Scanning file directory dataFiles.txt to search for new .lif files.
        */
        $dataFiles = array();
        foreach(glob("*.lif") as $file){
            array_push($dataFiles, $file);
        }
        
        //fopen for writing event data into eventInfo.txt
        $writeFile = fopen("eventInfo.txt", "w");
        
        /*
            Loops through $dataFiles (array of .lif files) and checks name to send
            it to a customized createDB() and initializeDB(). If it starts with a
            101 or 105, class 'protocol1or5' is made, if it starts with 102, 103, or 104,
            an instance of 'protocol234' is declared.
        */
        for($i=0; $i<count($dataFiles); $i++){
            //Each file made into an array of rows
            $dividedIntoRows = explode("\n", file_get_contents($dataFiles[$i]));
            
            //Writing down event names to evenInfo.txt
            writeDownEvents($dividedIntoRows[0], $dataFiles[$i]);
            
            //Checks if it is 1,2,3,4,or 5, the declares the appropriate class
            $section = substr($dataFiles[$i], 2, 1);
            switch($section){
                case 1:
                    $p = new protocol1or5;
                    
                    //If a table needs to be created, creates it
                    $p->createDB($dataFiles[$i]);
                    
                    //If data is not already initialized, initializes it.
                    if($p->isInitialized($dataFiles[$i])==false){
                        $p->initializeDB($dataFiles[$i], $dividedIntoRows);   
                    }
                    break;
                case 2:
                    $p = new protocol234;
                    $p->createDB($dataFiles[$i]);
                    if($p->isInitialized($dataFiles[$i])==false){
                        $p->initializeDB($dataFiles[$i], $dividedIntoRows);
                    }
                    break;
                case 3:
                    $p = new protocol234;
                    $p->createDB($dataFiles[$i]);
                    if($p->isInitialized($dataFiles[$i])==false){
                        $p->initializeDB($dataFiles[$i], $dividedIntoRows);
                    }
                    break;
                case 4:
                    $p = new protocol234;
                    $p->createDB($dataFiles[$i]);
                    if($p->isInitialized($dataFiles[$i])==false){
                        $p->initializeDB($dataFiles[$i], $dividedIntoRows);
                    }
                    break;
                case 5:
                    $p = new protocol1or5;
                    $p->createDB($dataFiles[$i]);
                    if($p->isInitialized($dataFiles[$i])==false){
                        $p->initializeDB($dataFiles[$i], $dividedIntoRows);
                    }
                    break;
                default: echo "<h2>Something went wrong. Maybe try again?</h2>";
                    break;
            }
        }
        fclose($writeFile);
                                   
        /*
            Writes down info about each event on eventInfo.txt, where tfData.php can
            reference later.
        */
        function writeDownEvents($arrOfRow, $dataFile){
            global $writeFile;
            $row = explode(",", $arrOfRow);
            $sectionNum = substr($dataFile,6,2).",";
            $metaData = "$row[0],$row[3],$row[9],$sectionNum\n";
            fwrite($writeFile, $metaData);
        }
        ?>
    </body>
</html>