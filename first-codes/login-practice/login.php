<!DOCTYPE html>
<html>
    <head>
        <title>Post-Login</title>
        <style>
            header, article{
                width:500px;
                margin:auto;
            }
            header{
                text-align:center;
                margin-top:150px;
            }
            article{
                background-color:floralwhite;
                padding:20px;
                border:1px solid black;
            }        
        </style>
    </head>
    <body style="background-color:antiquewhite;">
        
        <?php
        $verified = false;
        $username = "";
        $password = "";
        
        if($_SERVER['REQUEST_METHOD']=="POST"){
            if(isset($_REQUEST["username"])){
                $username = fixAndSanitize($_REQUEST["username"]);
            }
            if(isset($_REQUEST["password"])){
                $password = fixAndSanitize($_REQUEST["password"]);
            }
        }        
        function fixAndSanitize($var){
            $var = trim($var);
            $var = stripslashes($var);
            $var = htmlspecialchars($var);
            return $var;
        }
        
        $unList = array();
        $psList = array();
        $file = fopen("users.txt", "r") or die("Unable to open file");
        while(!feof($file)){
            $row = explode(":", fgets($file));
            $unList[] = $row[0];
            $psList[] = trim($row[1]);
        }
        for($i=0; $i<sizeof($unList); $i++){
            if($unList[$i] ==$username){
                if($psList[$i]==$password){
                    $verified = true;
                }
            }
        }
        fclose($file);
        if($verified){
            header('Location: http://localhost/webapp/login/candy.php');
            exit();
        }
        ?>
        <header><h4>Try again: </h4></header>
        <article>
            <form method="POST" action="login.php">
                <p>Username: <input type="text" name="username"></p>
                <p>Password: <input type="password" name="password"></p>
                <input type="submit">
            </form>
        </article>
    </body>
</html>