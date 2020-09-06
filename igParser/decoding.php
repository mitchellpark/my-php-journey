<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Decoding Announcements</title>
        <style>
            body{
                background-color:antiquewhite;
                margin:0px 200px;
                font-family: serif;
            }
            article{
                background-color: white;
                border:3px solid black;
                border-radius: 10px;
                margin-bottom:100px;
            }
            hr, p.name, p.description{
                margin:30px;
            }
            .name{
                color:brown;
                font-size:34px;
                font-weight: bold;
                margin:10px;
            }
            .description{
                font-size: 18px;
                margin:10px;
            }

        </style>
    </head>
    
    <body>
        <div id="container">
            <img src="https://upload.wikimedia.org/wikipedia/en/f/fc/BCP-Crest.png" 
                 width="150" height="150" alt="Bellarmine logo" style="margin-top:30px;">
            <h1>Announcements</h1>
            <article>
            <?php
                $page=file_get_contents('http://10.88.13.207/webapps/jsonRaw.php');
                $decoded=json_decode($page, true);
                foreach($decoded as $a){
                    $name = $a['title'];
                    $description = $a['body'];
                    echo "<p class='name'>$name</p>";
                    echo "<p class='description'>$description</p><hr>";
                }
            ?>
            </article>
        </div>
    </body>
</html>