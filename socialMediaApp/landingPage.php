<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Welcome.</title>
        <link rel="stylesheet" href="main.css" type="text/css">
    </head>
    <body>
        <?php
        $conn = new mysqli('localhost', "root", "", "socialMediaApp");
        if($conn->connect_error) die("Connection error: " . $connect_error);

        $username = $fullName = $password = $fullName = $email = "";
        $fullNameERR = $usernameERR = $passwordERR = $emailERR="";
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            if(fixInput($_POST["username"])!=null){
                $username=fixInput($_POST["username"]);
            }else $usernameERR = "Enter a username.";
            
            if(fixInput($_POST["password"])!=null){
                $password=fixInput($_POST["password"]);
            } else $passwordERR = "Passwords are required.";
            
            if(fixInput($_POST["fullName"])!=null){
                if (!preg_match("/^[a-zA-Z ]*$/",$_POST["fullName"])) {
                    $fullNameERR = "Only letters and white space allowed";
                } else {
                    $fullName = fixInput($_POST["fullName"]);
                }
            } else $fullNameERR = "Enter a name";
            
            if(fixInput($_POST["email"])!=null){
                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $emailERR = "Invalid email format";
                } else $email = fixInput($_POST["email"]);
            } else $emailERR = "Enter an email.";   
            
            if(($fullNameERR==$usernameERR)and ($usernameERR ==$passwordERR)
                    and ($passwordERR==$emailERR)){
                $sql = $conn->query("INSERT INTO userinfo(fullName, username, password, email) VALUES ('$fullName', '$username', '$password', '$email');");
                
                $sqlGetID = ($conn->query("SELECT id FROM userinfo WHERE username='$username' and password='$password'"));
                if($sqlGetID->num_rows>0){
                    while($row = $sqlGetID->fetch_assoc()){
                        $userId = $row["id"];
                        }
                }else echo "Sorry, no results.";

                echo var_dump($userId);
                $conn->close();
                session_start();
                $_SESSION["userId"] = $userId;
                
                header('Location: profile.php');
                exit();
            }
        }
        
        function fixInput($var){
            $var = trim($var);
            $var = stripslashes($var);
            $var = htmlspecialchars($var);
            return $var;
        }
        ?>
        <div id="left">
            <div id="logo">
                <h1>Welcome to Bellgram!</h1>
                <img height="300" width="300"src="images/bellarmineSeal.png" alt="bellarmineSeal.png">
            </div>
        </div>
        <div id="right">
            <div id="sign-up">
                <h3>Sign up here: <span class="error">*required fields</span></h3>
                <form action="landingPage.php" method="post">
                    <label for="fullName">Full name:</label>
                        <input type="text" name="fullName" value="<?=$fullName?>">
                        <span class="error">*<?php echo $fullNameERR;?></span><br>
                    <label for="username">Username:</label>
                        <input type="text" name="username" value="<?=$username?>">
                        <span class="error">*<?php echo $usernameERR;?></span><br>
                    <label for="password">Password:</label>
                        <input type="password" name="password" value="<?=$password?>">
                        <span class="error">*<?php echo $passwordERR;?></span><br>
                    <label for="email">Email:</label>
                        <input type="text" name="email" value="<?=$email?>">
                        <span class="error">*<?php echo $emailERR;?></span><br>
                    <input type="submit">
                </form>
            </div>
            <div id="sign-in" style="margin-top:20px;">
                <p style="text-align:center;">Have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </body>
</html>