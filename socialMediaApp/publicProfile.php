<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Your Profile</title>
        <link rel="stylesheet" href="main.css" type="text/css">
    </head>
    <body>
        <?php
        $username = fixInput($_POST["clickedProfile"]);
        
        $conn = new mysqli('localhost', "root", "", "socialMediaApp");
        if($conn->connect_error) die("Connection error: " . $connect_error);
        
        $images = array();
        $userPosts = $conn->query("SELECT * FROM imageinfo WHERE username='$username'");
        if($userPosts->num_rows>0){
            while($row = $userPosts->fetch_assoc()){
                array_push($images, $row["image"]);
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
            <h1><?=$username;?>'s Profile</h1>
            <div id="top">
                <button onclick="window.location.href ='feed.php'">Go back to feed</button>
                <?php
                for($i=0; $i<sizeof($images); $i++){
                echo "<div id='post'>";
                echo "<div id='image'><img height='300' width='300'src='$images[$i]'alt='$images[$i]'></div>";
                }
                ?>
            </div>
        </div>
    </body>
</html>