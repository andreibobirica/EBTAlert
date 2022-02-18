<?php
    include_once "Database.php";
    include_once "mailSender.php";
    $db = new Database();


    $query="SELECT * FROM alert";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($alert = $result->fetch_assoc()){
            if(checkAlertValability($db,$alert)){
                if(verifyAlert($db,$alert)){
                    //set alert success
                    $query = "UPDATE alert SET success = 1 WHERE id='$alert[id]'";
                    $db->query($query);
                    //GET Info account
                    $accountAlert = array();
                    $query = "SELECT * FROM account INNER JOIN user ON user.account = account.id WHERE account.id = '$alert[account]'";
                    $result = $db->query($query);
                    if ($result->num_rows == 1) {
                        $accountAlert = $result->fetch_assoc();                    
                    }
                    //Send Email
                    //Sending email Confirm
                    $htmlEmailConfirmation = "
                    <h3>Good Morning $accountAlert[name] $accountAlert[surname],<br/> We found new Results for your alert, check it out...</h3>
                    <small>Here are the detail:</small>
                    <p>".$alert["check-in"]."<small> to </small> ".$alert["check-out"]."</p>
                    <hr/><ul>
                    $alert[location]
                    </ul><hr/>
                    <h2><a href='https://$_SERVER[SERVER_NAME]/index.php?viewAlert=$alert[id]'>Click To see your results</a><h2/>
                    <small>to check detail login in our site...</small>
                    <br/>
                    <p>Thank you for your time... EBT Site</p>
                    ";
                    sendEmail($accountAlert["email"],"Your Alert find new Results",$htmlEmailConfirmation);
                }
            }
        }
    }

    function verifyAlert($db,$alert){
        $filter = array();
        $room = array();
        $hotels = array();
        $roomPeople = array();
        //filter
        $query="SELECT * FROM alert_filter WHERE alert='$alert[id]'";
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $filter = $result->fetch_assoc();    
        }
        //room
        $query="SELECT * FROM alert_room WHERE alert='$alert[id]'";
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $room = $result->fetch_assoc();    
        }
        //hotels
        $query="SELECT * FROM alert_hotel WHERE alert='$alert[id]'";
        $result = $db->query($query);
        if ($result->num_rows > 0) {
            while($hotel= $result->fetch_assoc()){
                array_push($hotels,$hotel);
            }
        }
        //roomspeople
        $query="SELECT * FROM alert_roompeople WHERE alert='$alert[id]'";
        $result = $db->query($query);
        if ($result->num_rows > 0) {
            while($hotel= $result->fetch_assoc()){
                array_push($roomPeople,$hotel);
            }
        }
        /*
        print_r($filter);
        echo "<br/>";
        print_r($room);
        echo "<br/>";
        print_r($hotels);
        echo "<br/>";
        print_r($roomPeople);
        echo "<br/>";
        print_r($alert);
        echo "<br/>";*/
        //WRITE DATA IN $_POST
        $_POST["alertRequest"] = true;
        //Saving search propertis
        $_POST["search"]["location"] = $alert["location"];
        $_POST["search"]["checkin"]= $alert["check-in"];
        $_POST["search"]["checkout"]= $alert["check-out"];
        $_POST["search"]["nRooms"] = $room["nRooms"];
        $_POST["search"]["totPeop"] = $room["totpeop"];
        //Coord
        $_POST["search"]["lat"] = null;
        $_POST["search"]["lng"] = null;
        //RoomPerson
        $_POST["search"]["roomsPerson"] = array();
        foreach($roomPeople as $rpeop){
            array_push($_POST["search"]["roomsPerson"],$rpeop["people"]);
        }
        //filter
        $_POST["filter"] = "minPInput=$filter[priceMin]&maxPInput=$filter[priceMax]";
        if($filter["rest"]==1)
        $_POST["filter"] .= "&checkRest=on";
        if($filter["park"]==1)
        $_POST["filter"] .= "&checkPark=on";
        if($filter["wifi"]==1)
        $_POST["filter"] .= "&checkWifi=on";
        if($filter["break"]==1)
        $_POST["filter"] .= "&checkBreak=on";
        if($filter["privpark"]==1)
        $_POST["filter"] .= "&checkPrivPark=on";
        if($filter["pets"]==1)
        $_POST["filter"] .= "&checkPets=on";
        if($filter["disab"]==1)
        $_POST["filter"] .= "&checkDisab=on";
        if($filter["ac"]==1)
        $_POST["filter"] .= "&checkAc=on";
        if($filter["privbath"]==1)
        $_POST["filter"] .= "&checkPrivBath=on";
        if($filter["tv"]==1)
        $_POST["filter"] .= "&checkTv=on";
        if($filter["minibar"]==1)
        $_POST["filter"] .= "&checkMiniBar=on";
        if($filter["safe"]==1)
        $_POST["filter"] .= "&checkSafe=on";
        if($filter["tecaffe"]==1)
        $_POST["filter"] .= "&checkTeCaffe=on";
        if($filter["phone"]==1)
        $_POST["filter"] .= "&checkPhone=on";

        //hotels
        $_POST["hotelFilter"] = array();
        foreach($hotels as $hotel){
            array_push($_POST["hotelFilter"],$hotel["hotel"]);
        }
        
        

        //Control data
        include_once "../comp/search/getResults.php";

        return (sizeof($optimalResults) > 0);
    }


    function checkAlertValability($db,$alert){
        if($alert["success"]==1){
            return false;
        }
        $checkInDate= strtotime($alert["check-in"]);
        if($checkInDate < time()+86400){
            $query = "DELETE FROM `alert` WHERE `alert`.`id` = $alert[id]";
            $result = $db->query($query);
            return false;
        }
        return true;
    }
?>