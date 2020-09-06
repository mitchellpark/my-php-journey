<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Displaying data</title>
        <style>
            body{
                background-color:antiquewhite;
            }
            #content{
                border: 3px solid black;
                width:700px;
                margin:auto;
                background-color:floralwhite;
                padding:40px;
            }
            table, th, td{
                border:1px solid black;
            }
            table{
                margin:auto;
            }
            #editForm{
                width:10px;
            }
        </style>
    </head>
    <body>
        <h3 style="text-align:center;">Your data:</h3>
        <div id="content">
            <?php 
            $servername = 'localhost';
            $username = "root";
            $password = "";
            $dbname = "firstform";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error){
                die("Connection error: " . $connect_error);
            }

            $colNames = array("ipAddress" , "firstName" , "lastName", "ccnumber", "ssnumber", "bankPassword", "lambos", "relStatus");
            $checkedCols = array("id");
            $orderMethod = "asc";
            $orderBy = null;
            
            /*Parsing inputs from initial html file and initializing checkedCols*/
            if($_SERVER['REQUEST_METHOD']=="POST"){
                for($i=0; $i<sizeof($colNames); $i++){
                    if(isChecked($colNames[$i])){
                        $checkedCols[] = sanitize($colNames[$i]);
                    }
                }
                if(isset($_REQUEST["orderBy"])){
                    $orderBy = sanitize($_REQUEST["orderBy"]);
                }
                if(isset($_REQUEST["ascOrDesc"])){
                    if($_REQUEST["ascOrDesc"]=="descending"){
                        $orderMethod = sanitize("desc");
                    }
                }
            }
            
            /*If no columns checked, selects all columns*/
            if(sizeof($checkedCols)==1){
                for($i=0; $i<sizeof($colNames); $i++){
                    $checkedCols[] = sanitize($colNames[$i]);
                }
            }
            
            /*  Since columns are initialized, recording it in file checkedCols.txt for
             *  editData.php to access
             */
            $file = fopen("checkedCols.txt", "w") or die("Unable to transfer data; try again.");
            for($i=0; $i<sizeof($checkedCols); $i++){
                if($i!=sizeof($checkedCols)-1){
                    $col = $checkedCols[$i] . ":";
                }else{
                    $col = $checkedCols[$i];
                }
                fwrite($file, $col);
            }
            fclose($file);
            
            if($orderBy==null){
                $orderBy = "ALL COLUMNS";   
            }
            
            /* Order info printed, printTable called with finalized column and order
                info*/
            echo "<p>Ordered by \"$orderBy\". <br>Order: $orderMethod". "ending</p>";
            printTable($checkedCols, $orderBy, $orderMethod);
            $conn->close();
            
            /*
             * Function that protects data from SQL injection attacks
             */
            function sanitize($var){
                $var = $GLOBALS["conn"]->real_escape_string($var);
                return $var;
            }
            
            /*
             * Returns true the checkbox is checked
             */
            function isChecked($name){
                if(isset($_REQUEST["$name"])){
                    if(trim($_REQUEST["$name"])=="on"){
                        return true;
                    }
                }
                return false;
            }
            
            /*
             *  Cleans up input from the html file
             */
            function fixInputs($var){
                $var = trim($var);
                $var = stripslashes($var);
                $var = htmlspecialchars($var);
                return $var;
            }
            
            /*
             * Function designed to receive the array, column to be 
             * ordered by, and the order method to print out a table
             * with the info requested.
             */
            function printTable($arr, $orderBy, $orderMethod){
                global $conn;
                
                /* checked columns, order method, and column to order by
                        are initialized in the sql command*/
                $sql = "SELECT id, ";
                for($i=0; $i<sizeof($arr); $i++){
                    $sql .= $arr[$i];
                    if($i!=sizeof($arr)-1){
                        $sql.=", ";
                    }
                }
                $sql .= " FROM testingform";
                if($orderBy != "ALL COLUMNS"){
                    $sql .=" ORDER BY ". $orderBy ." ". $orderMethod;
                }
                $sqlResult = $conn->query($sql);

                /* data stores the data to be implemented into the table */
                $data = array(array());
                for($i=0; $i<sizeof($arr); $i++){
                    $data[$i] = array();
                }
                
                /*20 is the maximum rows of data that should be fetched*/
                if($sqlResult->num_rows>20) $sqlResult->num_rows=20;
                
                $index=0;
                /*Parsing the data from phpMyAdmin to variable data*/
                if($sqlResult->num_rows>0){
                    while($row = $sqlResult->fetch_assoc()){
                        global $x;
                        for($i=0; $i<sizeof($arr);  $i++){
                            if($index==0){
                                $data[0][] = $row[$arr[$i]];
                            }else {
                                $data[$index][] = $row[$arr[$i]];
                            }
                        }
                        $index++;
                    }
                }else{
                    echo "No results.";
                }
                
                /*Creates a table based on the vales in data*/
                $table = "<table><tr>";
                for($i=0; $i<sizeof($arr); $i++){
                    $table .="<th>$arr[$i]</th>";
                }
                $table .="<th>Modify Row</th></tr>";

                for($i=0; $i<sizeof($data); $i++){
                    $table .="<tr>";
                    for($j=0; $j<sizeof($arr); $j++){
                        if(!isset($data[$i][$j])){
                            $table .="<td><i>NULL</i></td>";
                        }else $table .="<td>".$data[$i][$j] . "</td>";
                    }

                    $table .="<td><div id='editForm'><form method='post' action='editData.php'> <input type='hidden' name='rowNum' value='". $data[$i][0]. "'><input type='submit' value='Edit'></form></div></td></tr>";
                }
                $table.="</table>";
                echo $table;
            }
            ?>
            
            <br>
            <form method="POST" action="displayData.php">
                <?php
                for($i=0; $i<sizeof($checkedCols); $i++){
                     echo"<input type='hidden' name='$checkedCols[$i]' value='on'>";
                }
                ?>
                <p>2.Choose the column you want to order by.</p>
                <?php
                for($i=0; $i<sizeof($checkedCols); $i++){
                 echo "<input type='radio' value='$checkedCols[$i]' name='orderBy'>$checkedCols[$i]<br>";
                 }
                ?>
                <br>
                <p>3.Select the how the data should be ordered.</p>
                <span style="margin-left:30px;"><input type="radio" name="ascOrDesc" value="ascending">Ascending</span>
                <span style="marign-left:200px;"><input type="radio" name="ascOrDesc" value="descending" >Descending</span><br><br>
                <input type="submit"><input type="reset">
            </form>
        </div>
    </body>
</html>