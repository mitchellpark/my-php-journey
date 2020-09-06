<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        
        $stmt->close();
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ss", $param_username, $param_password);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: socialLogin.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        $stmt->close();
    }
    
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="socialStyle.css">
    <link href='https://fonts.googleapis.com/css?family=Adamina' rel='stylesheet'>

    
</head>
<body>
    <div class="wrapper">

        <center><h1>Sign Up</h1></center>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <p>Already have an account? <a href="socialLogin.php">Login!</a></p>
            <div class="user-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" id = "box" value="<?php echo $username; ?>" placeholder = "Enter a username">
                <?php echo $username_err; ?>
            </div>    
            <div class="pass-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" id = "box" value="<?php echo $password; ?>" placeholder = "Enter a password">
               <?php echo $password_err; ?>
            </div>
            <div class="pass-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id = "box" value="<?php echo $confirm_password; ?>" placeholder = "Retype the password">
                <?php echo $confirm_password_err; ?>
            </div>
            <input type="submit" class="btn1" value="Submit">
            
        </form>
    </div>    
</body>
</html>









<!--
<!DOCTYPE html>
<html>
    <head>
         <link href='https://fonts.googleapis.com/css?family=Adamina' rel='stylesheet'>

        <title> Login </title>
        <style>
            * {
                cursor: no-drop;
            }
            body {
                font-family: 'Adamina';font-size: 22px;
                background-color: #91C1DB;
                cursor: no-drop;
            }
            h1 {
                color: #DE89BE;
                font-size: 100px;
                margin: auto;
                text-align:center;
                margin-top: auto;
                margin-bottom: 80px;
            }
            .label {
                text-align:right;
            }
            fieldset {
                text-align:left;
            }
            legend {
                text-align:right;
            }
            li {
                list-style:none;
            }
            fieldset {
                width:60%;
                height: 200%;
            }
            .totalForm{
                text-align:center;
            }
            .title {
                text-align:center;
                padding-right: 20px;
            }
            form {
                font-size: 70px;
                text-align: center;
                margin-top: 20px;
                margin-bottom: 35px;
            }
            .username {
                color: #DE89BE;
                border-style: outset;
                background-color: rgb(132, 189, 219);
                margin-bottom: 20px;
                height:60px;
                width: 500px;
                font-family: 'Adamina';
                font-size:24pt;
            }
            .pass {
                color: #DE89BE;
                border-style: outset;
                background-color: rgb(132, 189, 219);
                margin:auto;
                height:60px;
                width: 500px;
                font-family: 'Adamina';
                font-size:24pt;
            }
            ::placeholder {
                color: #40434E;
                font-family: 'Adamina';
                font-size: 25px;
            }
            .login {
                background-color: #FDCFF3;
                border: 2px solid #FDCFF3;
                border-radius: 7px;
                cursor: crosshair;
                font-size: 20px;
            }
            .submit {
                margin-top: 20px;
                font-size: 50px;
                background-color: #FDCFF3;
                border: 2px solid #FDCFF3;
                border-radius: 7px;
                cursor: crosshair;
            }
        
        </style>

    </head>

    <body>
        <h1>SIGNUP</h1>
        <a href = "socialLogin.php" ><center><button class = "login" > Already have an account? Login!</button></center> </a>
        <div id = "signUpField">
        <form action = "createAccount.php">
            <center><input type="text" class ="username" placeholder = "Username" required> <br> </center>
            <center><input type="password" class ="pass" placeholder = "Password" required> <br> </center>
            <center><input type="submit" class ="submit" value = "Sign Up" required> </center>
        </form>
        </div>


    </body>

</html>
        -->
