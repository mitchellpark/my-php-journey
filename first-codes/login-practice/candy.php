<!DOCTPYE html>
<?php
$cookie_name="name";
$cookie_value="John Doe";
setcookie($cookie_name, $cookie_value, time() + (86400*30), "/");
?>
<html>
    <head>
        <title>PROTECTED PAGE</title>
        <style>
            body{
                text-align: center;
                background-color: cornflowerblue;
            }
        </style>
    </head>
    <body>
        <h1>Congratulations on making it through!</h1>
        <?php
        if(!isset($_COOKIE[$cookie_name])) {
            echo "Cookie '" . $cookie_name . "' is not set!<br>";
        } else {
            echo "Cookie '" . $cookie_name . "' is set!<br>";
            echo "Value is: " . $_COOKIE[$cookie_name];
        }
        ?>
    </body>
</html>