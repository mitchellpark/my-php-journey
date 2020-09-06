<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Your Profile</title>
        <link rel="stylesheet" href="main.css" type="text/css">
    </head>
    <body>
        <?php
        $conn = new mysqli('localhost', "root", "", "socialMediaApp");
        if($conn->connect_error) die("Connection error: " . $connect_error);
        
        session_start();
        $userId = $_SESSION["userId"];
        $getUserId = $conn->query("SELECT * FROM userinfo WHERE id=$userId");
        if($getUserId->num_rows>0){
            while($row = $getUserId->fetch_assoc()){
                $username = $row["username"];
            }
        }
        $deleteList = array();
        $getDeletes = $conn->query("SELECT * FROM imageinfo WHERE userId=$userId");
        if($getDeletes->num_rows>0){
            while($row = $getDeletes->fetch_assoc()){
                array_push($deleteList, substr($row["image"], 7));
            }
        }   
        
        $fileName = "";
        $resMessage = "";
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            if(isset($_POST["deleteFile"])){
                $sql = "DELETE FROM imageinfo WHERE ";
                $toDelete = array();

                if(!empty($_POST{"deletes"})){
                    $arrLen = count($_POST["deletes"]);
                    for($i=0; $i<$arrLen; $i++){
                        $sql .="image='images/".$_POST["deletes"][$i]."'";
                        if($i!=$arrLen-1) $sql .=" OR ";
                    }
                }
                $conn->query($sql);
            }
            
            if(isset($_POST["postFile"])){
                $target_dir = "images/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                if(isset($_POST["submit"]) && ($_FILES)["fileToUpload"]["tmp_name"]) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $resMessage = "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                   && $imageFileType != "gif" ) {
                    $resMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                if($_FILES["fileToUpload"]["size"]>500000){
                    $resMessage = "Sorry, your file is too large.";
                }
                if($uploadOk==0){
                    $resMessage = "For some reason, your file was not uploaded.";
                }else{
                    if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $fileName = "images/" . basename($_FILES["fileToUpload"]["name"]);
                        $resMessage = "The file ".$fileName. " has been uploaded.";
                        date_default_timezone_set("America/Los_Angeles");
                        $timeStamp = date("Y-m-d h:i:s A");
                        $sqlPostPhoto = $conn->query("INSERT INTO imageinfo(timeStamp, userId, image, username) VALUES ('$timeStamp', '$userId', '$fileName', '$username')");
                    }
                }
            }
        }
            //CREATE AN ARRAY WITH IMAGE COLUMN ELEMENTS FOR DELETE FUNCTION
            $imagePaths = array();
            $getImagePaths = $conn->query("SELECT * FROM imageinfo WHERE userId=$userId");
            if($getImagePaths->num_rows>0){
                while($row = $getImagePaths->fetch_assoc()){
                    array_push($imagePaths, $row["image"]); 
                }
            }
                
        function fixInput($var){
            $var = trim($var);
            $var = stripslashes($var);
            $var = htmlspecialchars($var);
            return $var;
        }
        ?>
        <div id="profile-container">
            <h1>Welcome, <?=$username;?>!</h1>
            <div id="top">
                <button onclick="window.location.href ='feed.php'">Go to feed</button>
                <div id="postOrDelete">
                    <h3>Post something:</h3>
                    <form action="profile.php" method="post" enctype="multipart/form-data">
                        Select image to upload:
                        <input type="file" name="fileToUpload">
                        <input type="submit" value="Upload Image" name="postFile">
                    </form>
                    <?php echo "<h3>$resMessage</h3>";?> 
                    <p><b>Check posts you want to delete:</b></p>
                    <form method="post" action="profile.php">
                        <?php
                        for($i=0; $i<sizeof($deleteList); $i++){
                            for($i=0; $i<sizeof($deleteList); $i++){
                                echo "<input type='checkbox' name='deletes[]' value='$deleteList[$i]'>$deleteList[$i]<br>";
                            }
                        }
                        ?>
                        <input type="submit" value="Delete" name="deleteFile">
                    </form>
                </div>
            </div>
            <div id="gallery">
                <?php
                $postedImages = array();
                $fetchPosts = $conn->query("SELECT image FROM imageinfo WHERE userId='$userId'");
                if($fetchPosts->num_rows>0){
                    while($row = $fetchPosts->fetch_assoc()){
                        array_push($postedImages, $row["image"]);
                    }
                }
                for($i=0; $i<count($postedImages); $i++){
                    echo "<img src='$postedImages[$i]' alt='$postedImages[$i]' height='300' width='300'><br>";
                }
                ?>
            </div>
        </div>
    </body>
</html>