<!DOCTPYE html>
<html>
    <head>
        <title>The Directory</title>
        <style>
            body{
                background-color: skyblue;
            }
            div{
                font-size: 20px;
                background-color:aliceblue;
                margin:auto;
            }
            #content{
                text-align: center;
                padding:30px;
                border-radius: 10px;
                border: 3px solid black;
                width: 75%;
                margin:auto;
                margin-top:5%;
                margin-bottom:5%;
            }
            #rows{
                padding:40px;
                width:70%;
            }
            #individual-events, #team-events{
                background-color: white;
                display:inline-block;
                border:5px solid black; 
                width:35%;
                padding:30px;
            }
            #title{
                text-align:center;
                text-shadow:cyan -2px 2px;
                font-size:40px;
            }
        </style>
    </head>
    <body>
        <div id="content">
            <div id="rows">
                <?php
                require 'protocol12345.php';
                
                //Will be an array of Event objects.
                $events = array();
                
                /* 
                 * Represents all data for one event, including title, sections, etc.
                 */
                class Event{
                    public $title="";
                    public $eventID = "";
                    public $distance = "";
                    public $sections = array();
                    
                    function setTitle($t){
                        $this->title = $t;
                    }
                    function setEventID($id){
                        $this->eventID = $id;
                    }
                    function setDistance($d){
                        $this->distance = $d;
                    }
                    function addSection($sect){
                        array_push($this->sections, $sect);
                    }
                }
                
                $file = fopen("eventInfo.txt", "r");
                /* 
                 * Kind of a confusing loop, but it basically loops through rows in the
                 * eventInfo.txt and transfer that info to Event class form. 
                 * $notHere - If it tallies to the array's length, indicates
                 */
                while(!feof($file)){
                    $notHere = 0;
                    $row = explode(",", fgets($file));
                    
                    //If eventInfo.txt isn't initialized, exit the loop.
                    if(count($row)<=1)break;
                    
                    //Increments $notHere if the current event's eventNum does not appear already 
                    //in the array.
                    foreach($events as $e){
                        $eventNum = substr($e->eventID,0,3);
                        if($eventNum!=$row[0]){
                            $notHere++;
                        }
                    }
                    /*
                     * If $notHere tallies to the array's length, it indicates that
                     * 'events' does not have any events with the current event's eventNum 
                     * (i.e.101,102,103). We don't want duplicates of those, so we create a new Event.
                     * If we have it, we still want to add additional sections, which is done by the 
                     * else statement.
                     */
                    if($notHere==count($events)){
                        $varName = "event".$row[0];
                        $$varName = new Event;
                        $$varName->setEventID($row[0]);
                        $$varName->addSection($row[3]);
                        $$varName->setTitle($row[1]);
                        $$varName->setDistance($row[2]);
                        array_push($events, $$varName);
                    }else{
                        $varName = "event".$row[0];
                        $$varName->addSection($row[3]);
                    }
                } 
                fclose($file);
                
                //Two distinct arrays to separate the events according to protocol
                $events15 = array();
                $events234 = array();
                for($i=0; $i<count($events); $i++){
                    if(($events[$i]->eventID == 101 )|| ($events[$i]->eventID==105)){
                       array_push($events15, $events[$i]);
                    }else {
                        array_push($events234, $events[$i]);
                    }
                }
             
                ?>
                
                <!--Section to list individual events, and provide a portal to displayData.php-->
                <div id="individual-events" style="margin-right:20px;">
                    <p id="title"><b>Individual Events</b></p>
                    <?php
                    //Sends necessary info of an event to displayData.php through a form, using hidden input types.
                    for($i=0; $i<count($events15); $i++){
                        if($i>0) echo "<hr>";
                        
                        $eventID = base64_encode(serialize($events15[$i]->eventID));
                        $sectionsArr = base64_encode(serialize($events15[$i]->sections));
                        $eventName = base64_encode(serialize($events15[$i]->title));
                        echo $events15[$i]->title;
                        echo "<p><form method='post' action='displayData.php'>
                            <input type='hidden' name='eventID' value='$eventID'>
                            <input type='hidden' name='sectionsArr' value='$sectionsArr'>
                            <input type='hidden' name='eventName' value='$eventName'>
                            <input value='View Results' type='submit'></form></p>";
                        }
                    ?>
                </div>
                
                <!--Section to list team events, and provide a portal to displayData.php-->
                <div id="team-events" style="margin-left:20px;">
                    <p id="title"><b>Team Events</b></p>
                    <?php
                    //Sends necessary info of an event to displayData.php through a form, using hidden input types.
                    for($i=0; $i<count($events234); $i++){
                        if($i>0) echo "<hr>";
                        
                        $eventID = base64_encode(serialize($events234[$i]->eventID));
                        $sectionsArr = base64_encode(serialize($events234[$i]->sections));
                        $eventName = base64_encode(serialize($events234[$i]->title));
                        echo $events234[$i]->title;
                        echo "<p><form method='post' action='displayData.php'>
                            <input type='hidden' name='eventID' value='$eventID'>
                            <input type='hidden' name='sectionsArr' value='$sectionsArr'>
                            <input type='hidden' name='eventName' value='$eventName'>
                            <input value='View Results' type='submit'></form></p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>