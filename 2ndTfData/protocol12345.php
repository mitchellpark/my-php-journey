        <?php
        /*
            If file starts with 101 or 105, this class is declared.
        */
        class Protocol{
            function orderBy($filePath, $order){
                //TO DO
            }
        }

        class protocol1or5 extends Protocol{
            
            //Creates a table, returns false if new table need not be created.
            function createDB($eventName){
                global $conn;
                
                $sql = "CREATE TABLE `tfdata`.`protocol1or5` ( `id` INT(5) NOT NULL AUTO_INCREMENT ,`eventID` VARCHAR(10),`place` VARCHAR(2) NULL , `fullName` VARCHAR(50) NULL ,  `school` VARCHAR(30) NULL ,  `finalTime` VARCHAR(12) NULL, `timeDiff` VARCHAR(10) NULL, PRIMARY KEY  (`id`)) ENGINE = InnoDB;";
                if($conn->query($sql)==false){
                    $createNew = false;
                }
            }
            
            //Inserts desired data into the table
            function initializeDB($eventName, $dividedIntoRows){
                global $conn;
                
                //Concatenation of event number and section number to make an eventID
                $eventID = substr($eventName,0,3).substr($eventName,6,2);
                
                //Loops through the .lif file, row by row and inserts into database.
                for($i=1; $i<(count($dividedIntoRows)-1); $i++){
                    if($rows = explode(",", $dividedIntoRows[$i])){

                        $fullName = "$rows[3],$rows[4]";
                        $insert = "INSERT INTO protocol1or5(eventID, place, fullName, school, finalTime, timeDiff) VALUES('$eventID', '$rows[0]',  '$fullName', '$rows[5]', '$rows[6]', '$rows[8]')";
                        $conn->query($insert);
                        
                        //Deletes timeDiff from whoever is first place, because there is no time to catch up for him.
                        $delete = "UPDATE protocol1or5 SET timeDiff=null WHERE place=1";
                        $conn->query($delete);
                    }
                }
            }
            
            /*
             * Returns true the event's eventID already exists and the event should not be inserted
             */
            function isInitialized($eventName){
                global $conn;
                $eventID = substr($eventName,0,3).substr($eventName,6,2);
                $sqlResult = $conn->query("SELECT * FROM protocol1or5 WHERE eventID='$eventID'");
                return $sqlResult->num_rows>0;
            }
            
            //Takes in a specific event number and section, and returns an html table in string format.
            function displayTable($eventNum,$section){
                global $conn;
                
                $eventID = $eventNum.$section;
                $columns = array("place", "fullName", "school", "finalTime", "timeDiff");
                $table = "<table><th>place</th><th>fullName</th><th>school</th><th>finalTime</th><th>Time Difference</th>";
                
                //Fetches appropriate data, given the eventID, and insert into array sqlResult.
                $sql = "SELECT * FROM protocol1or5 WHERE eventID = '$eventID'";
                $sqlResult = $conn->query($sql);
                
                //Inserts into table format.
                if($sqlResult->num_rows>0){
                    while($row = $sqlResult->fetch_assoc()){
                        $table.="<tr>";
                        for($i=0; $i<sizeof($columns); $i++){
                            $table.="<td>".$row[$columns[$i]]."</td>";
                        }
                        $table.="</tr>";
                    }
                }
                $table .="</table>";
                return $table;
            }
        }
        
        /*
         * Refer to comments above because it's basically identical to protocol1or5
         */
        class protocol234 extends Protocol{
            
            function createDB($eventName){
                global $conn;
                $createNew = true;

                $sql = "CREATE TABLE `tfdata`.`protocol234` ( `id` INT(5) NOT NULL AUTO_INCREMENT , `eventID` VARCHAR(10), `place` VARCHAR(4) NULL , `school` VARCHAR(30) NULL , `abbrev` VARCHAR(8) NULL, `finalTime` VARCHAR(12) NULL, `timeDiff` VARCHAR(10) NULL, PRIMARY KEY  (`id`)) ENGINE = InnoDB;";
                if($conn->query($sql)==false){
                    $createNew = false;
                }
                return $createNew;
            }
            
            function initializeDB($eventName, $dividedIntoRows){
                global $conn;
                $eventID = substr($eventName,0,3).substr($eventName,6,2);
                
                for($i=1; $i<(count($dividedIntoRows)-1); $i++){
                    if($rows = explode(",", $dividedIntoRows[$i])){
                        $insert = "INSERT INTO protocol234(eventID, place, school, abbrev, finalTime, timeDiff) VALUES('$eventID', '$rows[0]', '$rows[3]', '$rows[5]', '$rows[6]',     '$rows[8]')";
                        $conn->query($insert);
                        //Deletes timeDiff from whoever is first place, because there is no time to catch up for him.
                        $delete = "UPDATE protocol234 SET timeDiff=null WHERE place=1";
                        $conn->query($delete);
                    }
                }
            }
            
            function isInitialized($eventName){
                global $conn;
                $eventID = substr($eventName,0,3).substr($eventName,6,2);
                $sqlResult = $conn->query("SELECT * FROM protocol234 WHERE eventID='$eventID'");
                return $sqlResult->num_rows>0;
            }
            
            function displayTable($eventNum, $section){
                global $conn;
                $arr = array("place", "school", "abbrev", "finalTime", "timeDiff");
                $table = "<table><th>Place</th><th>School</th><th>Abbreviation</th><th>Final Time</th><th>Time Difference</>";
                $eventID = $eventNum.$section;
                $sql = "SELECT * FROM protocol234 WHERE eventID = '$eventID'";
                $sqlResult = $conn->query($sql);
                if($sqlResult->num_rows>0){
                    while($row = $sqlResult->fetch_assoc()){
                        $table.="<tr>";
                        for($i=0; $i<sizeof($arr); $i++){
                            $table.="<td>".$row[$arr[$i]]."</td>";
                        }
                        $table.="</tr>";
                    }
                }
                $table .="</table>";
                return $table;
            }
        }
?>