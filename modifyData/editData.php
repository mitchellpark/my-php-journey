<!DOCTPYE html>
<html lang="en">
    <head>
        <title>Editing data...</title>
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
            #info{
                margin:auto;
                width:500px;
                margin-bottom:40px;
            }
        </style>
    </head>
    <body>
        <div id="content">
            <?php
            /* Establishing a connection with the database*/
            $servername = 'localhost';
            $username = "root";
            $password = "";
            $dbname = "firstform";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) die("Connection error: " . $connect_error);
    
            
            /* Initializing checkedCols from retrieving the user-chosen columns
             * from displaydata.php
             */
            $file = fopen("checkedCols.txt", "r") or die("Unable to transfer data. Try again.");
            while(!feof($file)){
                $checkedCols = explode(":", fgets($file));
            }
            fclose($file);

            $rowNum = $instructions= $infoMessage= $update = "";
            $noUpdates = true;
            $newData = array();
            
            /*Receiving data from displayData.php*/
            if($_SERVER['REQUEST_METHOD']=="POST"){
                if(fixInput($_POST['rowNum'])!=null){
                    $rowNum = $_POST["rowNum"];
                }
                $infoMessage = "Edit the row you have chosen.";
                $instructions = "Note that 'ssnumber' and 'ccnumber' only accept numerical values. Value will default to 0 if another value is entered. Also, the 'id' value may not be changed.";
            }
            
            /*Receiving data from this page, after making edits*/
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                $instructions = "";
                $infoMessage = "Your new row:";
                if(null!=fixInput($_GET['rowNum'])){
                    $rowNum = $_GET["rowNum"];
                }
                
                /* Constructing the "update" sql command */
                $update = "UPDATE testingform SET ";
                for($i=1; $i<sizeof($checkedCols); $i++){
                    if(null!=fixInput($_GET["$checkedCols[$i]"])){
                        $noUpdates = false;
                        $update .=$checkedCols[$i]."='".$_GET["$checkedCols[$i]"]."', ";
                    }
                }
                $update = substr($update, 0, strlen($update)-2);
                $update .=" WHERE id=".$rowNum; 
            }
            
            /* Cleans up user input*/
            function fixInput($var){
                $var = trim($var);
                $var = stripslashes($var);
                $var = htmlspecialchars($var);
                return $var;
            }
            
            if(!$noUpdates){
                $updateResult = $conn->query($update);
            }elseif($update!=null){
                $infoMessage = "No edits made.";
            }
            
            /* Constructing sql command to display the row*/
            $sql = "SELECT ";
            for($i=0; $i<sizeof($checkedCols); $i++){
                if($i!=sizeof($checkedCols)-1){
                    $sql .= "$checkedCols[$i]" . ", ";
                } else{
                    $sql .="$checkedCols[$i]";
                }
            }
            $sql .= " FROM testingform WHERE id=$rowNum";
            $result = $conn->query($sql);
            $data = array();
            
            /*Initializing $data, which holds all values of the row*/
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()){
                    for($i=0; $i<sizeof($checkedCols); $i++){
                        array_push($data, $row[$checkedCols[$i]]);
                    }
                }
            }
            ?>
            
            <div id="info">
                <h3 style="text-align:center;"><?=$infoMessage;?></h3>
                <p style="text-align:center;"><?=$instructions;?></p>
            </div>
            
            <?php
            /*Creates a table that displays the row*/
            $table="<table><tr>";
            foreach($checkedCols as $i){
                $table .="<th>$i</th>";
            }
            $table.="</tr><tr>";
            for($j=0; $j<sizeof($checkedCols); $j++){
                if(!isset($data[$j])){
                    $table .="<td>NULL</td>";
                }else $table .="<td>".$data[$j]."</td>";
            }
            $table.="</tr>";
            $table.="</table>";
            echo $table;
            ?>
            <form method="get" action="editData.php">
                <input type="hidden" name="rowNum" value="<?php echo $rowNum;?>">
                <br>
                <table>
                    <?php
                    /*Creates a table that receives user input*/
                    $inputTable="";
                    for($i=0; $i<sizeof($checkedCols); $i++){
                        $inputTable .="<th>$checkedCols[$i]</th>";
                    }
                    $inputTable .="<tr><td></td>";
                    for($i=1; $i<sizeof($checkedCols); $i++){
                        $inputTable.="<td><input type='text' name='$checkedCols[$i]' size='7'></td>";
                    }
                    echo $inputTable;
                    ?>
                </table>
                <br>
                <span style='margin-left:325px;'><input type="submit" value="Submit"></span>
            </form>
            <br>
            
            <!--Button to go to the homepage, sets all columns to the "chosen" state-->
            <form method="post" action="displayData.php">
                <input type="hidden" name="firstName" value="on">
                <input type="hidden" name="lastName" value="on">
                <input type="hidden" name="ssnumber" value="on">
                <input type="hidden" name="ccnumber" value="on">
                <input type="hidden" name="bankPassword" value="on">
                <input type="hidden" name="lambos" value="on">
                <input type="hidden" name="relStatus" value="on">
                <input type="hidden" name="ipAddress" value="on">
                <input type="submit" value="Go to Homepage">
            </form>
        </div>
    </body>
</html>
    
    