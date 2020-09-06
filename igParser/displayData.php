<!DOCTYPE html>
<html>
    <head>
        <title>SCRAPER</title>
        <style>
            #image{
                display:block;
                margin:auto;
                width:50%;
            }
        </style>
    </head>
    <body>
        <?php
        echo "<br>".filesize("instagram.html");
        $getIG = file_get_contents("http://instagram.com/bellarminebells", false);
        $file = fopen("parser.php", "w");
        fwrite($file, $getIG);
        fclose($file);
        $res = file_put_contents('in.html', $getIG);
        require_once'parser.php';
        $dom = file_get_html('instagram.html', false);
        $images = array();
        
        if(!empty($dom)) {
            
            foreach($dom->find('.eXle2') as $caption){
                array_push($images, $caption);
            }
            
            $filePaths = array();
            foreach($dom->find('.NCYx-') as $img){
                $filePaths[] = $img->src;
            }
        }
        for($i=0; $i<count($images); $i++){
            echo "<div id='image'><img src='$filePaths[$i]' alt='Sorry...Couldn't display'></div>";
            echo $images[$i];
        }
        ?>
    </body>
</html>