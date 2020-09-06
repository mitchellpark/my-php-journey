<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Feed</title>
        <link rel="stylesheet" href="main.css" type="text/css">
        <style>
            img, button{
                display:block;
                margin:auto;
            }
        </style>
    </head>
    <body>
        <header>
            <img height='100' wdith='100' src='images/bellarmineSeal.png' alt='bellarmineSeal.png'>
                <button onclick="window.location.href='profile.php'">Your profile</button>
        </header>
        <div id="feed-container" style="margin-bottom:30px;">
            <h1 style="text-align:center;">Your feed</h1>
            <?php
             $conn = new mysqli('localhost', "root", "", "socialMediaApp");
            if($conn->connect_error) die("Connection error: " . $connect_error);
            
            $getImages = $conn->query("SELECT * FROM imageinfo ORDER BY timeStamp");
            $usernames = $times = $images = array();
            if($getImages->num_rows>0){
                while($row=$getImages->fetch_assoc()){
                    array_push($times, $row["timeStamp"]);
                    array_push($images, $row["image"]);
                    array_push($usernames, $row["username"]);
                }
            }
            for($i=0; $i<sizeof($times); $i++){
                echo "<div id='post'>";
                $form = "<form method='post' action='publicProfile.php'><input type='hidden' name='clickedProfile' 
                    value='$usernames[$i]'><input type='submit' value='$usernames[$i]'></form>";
                echo "<div id='username'>$form</div>";
                echo "<div id='image'><img height='300' width='300'src='$images[$i]'alt='$images[$i]'></div>";
                echo "<div id='time'>$times[$i]</div></div>";
            }
            ?>
        </div>
    </body>
</html>