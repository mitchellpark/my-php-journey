<!DOCTYPE html>
<head lang="en">
    <head>
        <title>Some PHP fun</title>
    </head>
    <body>
        <?php 
        function addition($x = 0, $y = 10):float{
            return $x+$y;
        }
        $x=5;
        $y=10;
        echo addition($x,$y);
        
        class foo{
            function __construct(){
                $this->name = "foo";
            }
            function printName(){
                return "My name is " . $this->name;
            }
            function changeName(&$change){
                $change = "Bob";
                return "Your name is now changed.";
            }
        }
        $foo1 = new foo();
        echo "<br>" . $foo1->printName();
        
        $name = "Bradley";
        echo $foo1->changeName($name);
        echo "<br>My name was Bradley, but now it is " . $name . "<br>";
        
        $myfile = fopen("randAssign.txt", "r") or die("Unable to open file.");
        //echo fread($myfile, filesize("randAssign.txt"));
        /*while(!feof($myfile)){
            echo fgets($myfile);
        }*/
        echo "<br>";
        
        while(!feof($myfile)){
            echo fgetc($myfile);
        }
        
        fclose($myfile);
        
        $anotherFile = fopen("another.php", "w+") or die("Unable to open file");
        $txt ="THIS IS AN IMPORTANT MESSAGE!!!";
        fwrite($anotherFile, $txt);
        fclose($anotherFile);
        
        echo "<br>";
        //Some class manipulation
        class Adam{
            public $foo ="foooooo<br>";
            function __construct(){
                echo "This is adam.<br>";
            }
            public function printName(){
                echo "Adam is printed.<br>";
            }
        }
        class Bassianus extends Adam {
            function __construct(){
                parent::__construct();
                echo "This is Bassianus.<br>";
            }
            function __destruct(){
                
            }
            function printName(){
                echo "Bassianus is printed.<br>";
            }
        }
        class Saturninus extends Adam{
            //nothing 
        }
        $adam = new Adam;
        $bassianus = new Bassianus;
        $saturninus = new Saturninus;
        $adam->printName();
        echo Adam::printName();
        echo $bassianus->foo . ".";
        
        $aString = "<br>Some string testing...\nDid it work?";
        print $aString;
        print "<br>It did not...how about this:\\?, this:\', and this: \" ";
        print '$aString' . "\n". "bruh...". "$aString";
        
        
        ?>
    </body>
</head>