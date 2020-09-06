<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sign in.</title>
        <link rel="stylesheet" href="main.css" type="text/css">
    </head>
    <body>
        <?php
        $conn = new mysqli('localhost', "root", "", "socialMediaApp");
        if($conn->connect_error) die("Connection error: " . $connect_error);
        
        $unList = $psList = array();
        $username = $password = "";
        $verified = false;
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            if(fixInput($_POST["username"]!=null)){
                $username = $_POST["username"];
            }
            if(fixInput($_POST["password"]!=null)){
                $password = $_POST["password"];
            }
        }
        
        $file = fopen("config.txt.php", "r") or die("Unable to open configuration file");
        while(!feof($file)){
            $row = explode(":", fgets($file));
            $unList[] = $row[0];
            $psList[] = trim($row[1]);
        }

        for($i=0; $i<sizeof($unList); $i++){
            if($username==$unList[$i]){
                if($password==$psList[$i]){
                    $verified = true;
                    $sqlGetID = ($conn->query("SELECT id FROM userinfo WHERE username='$username'
                        and password='$password'"));
                    if($sqlGetID->num_rows>0){
                        while($row = $sqlGetID->fetch_assoc()){
                            $userId = $row["id"];
                        }
                    }else echo "Sorry, no results.";
                }
            }
        }
        fclose($file);
        
        if($verified){
            session_start();
            $_SESSION["userId"] = $userId;
            header('Location: feed.php');
            exit();
        }
        
        function fixInput($var){
            $var = trim($var);
            $var = stripslashes($var);
            $var = htmlspecialchars($var);
            return $var;
        }
        ?>
        <h1 style="text-align: center;">Sign in:</h1>
        <div id="sign-in">
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                    <input type="text" name="username"><br>
                <label for="password">Password:</label>
                    <input type="password" name="password"><br>
                <input type="submit">
            </form>
        </div>
        <div id="sign-in" style="margin-top:50px;">
            <p>Don't have an account? <a href="landingPage.php">Make one!</a></p>
        </div>
    </body>
</html>