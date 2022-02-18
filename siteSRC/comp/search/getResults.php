<?php
//Database only if is caled by view result , not by alert
if(!isset($_POST["alertRequest"])){
    include_once "../../core/Database.php";
    $db = new Database();
}


/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [Km] (same as earthRadius)
 */
function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return ($angle * $earthRadius)/1000;
}


//Saving search propertis
$search['location'] = $_POST["search"]["location"];
$search['checkin']  = $_POST["search"]["checkin"];
$search['checkout']  = $_POST["search"]["checkout"];
$search['nRooms']  = $_POST["search"]["nRooms"];
$search['totPeop'] = $_POST["search"]["totPeop"];
//Coord
$search['lat']  = $_POST["search"]["lat"];
$search['lng']  = $_POST["search"]["lng"];
//RoomPerson
$search['roomsPerson'] = $_POST["search"]["roomsPerson"];
sort($search['roomsPerson']);



/*
//DEBUGING DATA
//Saving search propertis
$search['location'] = "Egitto";
$search['checkin']  = "2020/02/24";
$search['checkout']  = "2020/02/26";
$search['roomsPerson']  = array(1,1,1,1,3,3,5);
$search['nRooms']  = 5;
$search['totPeop'] = 19;
//Coord
$search['lat']  = "11";
$search['lng']  = "10";
*/
//Distance MAX (KM) for search with coords
$distMax = 100;


//Results Variable with all hotels by location and name and coordinates
//Contains ID
$hotelsAll = array();
//Results Variable with hotels disponible by availability between check-in and check-out
//Containes ID
$hotelsAvail = array();
//Results Variable with hotels disponible by availability between check-in and check-out
//Containes ID
$roomsAvail = array();

//Result variable with hotels disponible by availability between check-in and check-out
//Contains all the data
$arrayHotelRoomAvail = array();
//Array that contains the rooms disponibily in that period
$disponibility = array();
//Array With Optimal Results That Contains All the data To Display and to pass to javacript script
$optimalResults = array();


//Finding Hotels in coordinates by searching
if($search['lat'] != null && $search['lng'] != null){
    $query = "SELECT * FROM location INNER JOIN hotel ON hotel.location = location.id ";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $dist = vincentyGreatCircleDistance((float)$search["lat"],(float)$search["lng"],(float)$row["lati"],(float)$row["longi"]);
            if($dist < $distMax){
                array_push($hotelsAll,$row["id"]);
            }
        }
    }
}

//Findind Results in hotel by hotel name
//Geting Info of the hotel
foreach(explode(" ",$search['location']) as $locationQuery){
    $query = "SELECT hotel.id FROM `hotel` WHERE name LIKE '%$locationQuery%'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            array_push($hotelsAll,$row["id"]);
        }
    }

    //Finding Hotels in location
    $query = "SELECT hotel.id FROM `location` INNER JOIN hotel ON hotel.location = location.id WHERE location.state LIKE '%$locationQuery%' OR location.mun LIKE '%$locationQuery%' OR location.street LIKE '%$locationQuery%'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            array_push($hotelsAll,$row["id"]);
        }
    }
}

//DELETE DUPLICATES Hotels Search
$hotelsAll = array_unique($hotelsAll);
// $Hotels are the entire hotels list  by location and name, not for availability

//Eliminates The indexes wrong
$a = array();
foreach($hotelsAll as $hotel){
    array_push($a,$hotel);
}
$hotelsAll =$a;

//Continue Seraching only if there is some hotel in this area, or name, or location
if(sizeof($hotelsAll)>0){
        //Geting Hotel Avability by dates 
        //Preparing Query
        $query = "SELECT * FROM hotel INNER JOIN room_type ON room_type.hotel = hotel.id 
        INNER JOIN price_date ON price_date.room = room_type.id 
        WHERE date <= '$search[checkout]' AND date >= '$search[checkin]' 
        AND price_date.disp-price_date.book >= 1";
        foreach($hotelsAll as $index => $hotel){
            if( $index == 0 && $index == sizeof($hotelsAll)-1){
                $query .= " AND hotel.id = '$hotel'";
            }elseif($index == 0){
                $query .= " AND ( hotel.id = '$hotel' OR ";
            }elseif($index == sizeof($hotelsAll)-1){
                $query .= "hotel.id = '$hotel')";
            }else{
            $query .= "hotel.id = '$hotel' OR ";
            
            }
        }
        //Query prepared
        $result = $db->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                //print("<br/>");print_r($row);print("<br/>");
                array_push($hotelsAvail,$row["hotel"]);
                array_push($roomsAvail,$row["id"]);
                array_push($arrayHotelRoomAvail,$row);
            }
        }
        //DELETE DUPLICATES Hotels Search by beetween check in and check out
        $hotelsAvail = array_unique($hotelsAvail);
        //Delete Duplicates Rooms Search by beetween check in and check out
        $roomsAvail = array_unique($roomsAvail);


        
        //Continue Only If There are somoe Available Cameras in this perios
        if(sizeof($arrayHotelRoomAvail)>0){
                //Geting all the dates
                $days=0;
                $since = $search["checkin"];
                $sinceTime = strtotime($since);
                $until = $search["checkout"];
                $untilTime= strtotime($until);
                //Calculating The day between since and until and adding there in an array
                $dates = array();
                while($sinceTime<$untilTime){
                    $days++;
                    array_push($dates,date('Y-m-d', $sinceTime));
                    $sinceTime+=86400;
                }


                //Algoritme to define personal calendars for every room disponible to reach if correspondts with check-in check-out period
                $calendar = array();
                foreach($roomsAvail as $room){
                    $roomCal = array("room"=> array());
                    foreach($dates as $date){
                        $add = array();
                        foreach($arrayHotelRoomAvail as $hotelroom){
                            $add = array("date"=>$date, "room"=>$room, "hotel"=>"", "disp"=>false);
                            if(($date ==  $hotelroom["date"])&&($room == $hotelroom["room"])){
                                $add["disp"]=true;
                                $add["hotel"]=$hotelroom["hotel"];
                                break;
                            }
                        }
                        array_push($roomCal["room"],$add);
                    }
                    array_push($calendar,$roomCal);
                }
                //I have done per Room, A personal calendar with the period disponibility
                //$calendar

                //Now i verify if the calendar is like the period of booking
                //I find if all the days for the rooms are available or if the is some nop in the middle
                $disponibility = array();

                foreach($calendar as $room){
                    //print_r( json_encode($room));
                    //if true add the room details to disponibility
                    $return = true;
                    $value = "";
                    foreach($room["room"] as $day){
                        if(!$day["disp"]){
                            $return = false;
                        }else{
                            $value = $day["room"];
                        }
                    }
                    if($return){
                        array_push($disponibility,$value);
                    }
                }
                //This is the disponibility The ID of the room disponible with tge guest requires

                //Only if there is hotels Disponible
                if(sizeof($disponibility)>0){

                        //GETING All Data To Show and to pass in javascript script
                        $query = "SELECT * FROM room_type 
                        INNER JOIN hotel ON hotel.id = room_type.hotel 
                        INNER JOIN hotel_detail ON hotel_detail.hotel = hotel.id
                        INNER JOIN room_detail ON room_type.id = room_detail.room 
                        INNER JOIN location ON hotel.location = location.id ";
                        foreach($disponibility as $index => $dis){
                            if( $index == 0 && $index == sizeof($disponibility)-1){
                                $query .= " WHERE room_type.id = '$dis'";
                            }elseif( $index == 0){
                                $query .= " WHERE room_type.id = '$dis' OR ";
                            }elseif($index == sizeof($disponibility)-1){
                                $query .= " room_type.id = '$dis' ";
                            }else{
                                $query .= "room_type.id = '$dis' OR ";
                            }
                        }
                        
                        //Query prepared
                        $result = $db->query($query);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()){
                                array_push($optimalResults,$row);
                            }
                        }

                        //Formatted Whelll and get Prices
                        //Formated wheell
                        $a = array();
                        
                        array_push($a,array("hotel"=>array(),"rooms"=>array()));
                        foreach($optimalResults as $index => $res){
                            $old = "NO";
                            $new = false;
                            foreach($a as $index => $ar){
                                if($ar["hotel"] == $res["hotel"]){
                                    $old = $index;
                                }
                            }
                            if($old==="NO"){
                                array_push($a,array("hotel"=>$res["hotel"],"rooms"=>array()));
                                array_push($a[sizeof($a)-1]["rooms"],array("hotelid"=>$res["hotel"],"prTot"=>null,"desc"=>$res["descr"],"pnrTot"=>null,"dis"=>$res["dis"],"stars"=>$res["stars"],"animal"=>$res["animal"],"privpark"=>$res["privpark"],"break"=>$res["break"],"park"=>$res["park"],"rest"=>$res["rest"],"dispMin"=>"","nights"=>$days,"name"=>$res["name"],"ac"=>$res["ac"],"bedDouble"=>$res["bedDouble"],"bedSingle"=>$res["bedSingle"],"cod"=>$res["cod"],"hotelchain"=>$res["hotelchain"],"img"=>$res["img"],"lati"=>$res["lati"],"longi"=>$res["longi"],"minibar"=>$res["minibar"],"mun"=>$res["mun"],"nameBea"=>$res["nameBea"],"privatebath"=>$res["privatebath"],"quantity"=>$res["quantity"],"room"=>$res["room"],"safe"=>$res["safe"],"sofa"=>$res["sofa"],"state"=>$res["state"],"street"=>$res["street"],"tecaffe"=>$res["tecaffe"],"tel"=>$res["tel"],"tv"=>$res["tv"],"wifi"=>$res["wifi"],"pnr"=>"","pr"=>""));
                            }else{
                                array_push($a[$old]["rooms"],array("hotelid"=>$res["hotel"],"prTot"=>null,"pnrTot"=>null,"desc"=>$res["descr"],"dis"=>$res["dis"],"stars"=>$res["stars"],"animal"=>$res["animal"],"privpark"=>$res["privpark"],"break"=>$res["break"],"park"=>$res["park"],"rest"=>$res["rest"],"dispMin"=>"","nights"=>$days,"name"=>$res["name"],"ac"=>$res["ac"],"bedDouble"=>$res["bedDouble"],"bedSingle"=>$res["bedSingle"],"cod"=>$res["cod"],"hotelchain"=>$res["hotelchain"],"img"=>$res["img"],"lati"=>$res["lati"],"longi"=>$res["longi"],"minibar"=>$res["minibar"],"mun"=>$res["mun"],"nameBea"=>$res["nameBea"],"privatebath"=>$res["privatebath"],"quantity"=>$res["quantity"],"room"=>$res["room"],"safe"=>$res["safe"],"sofa"=>$res["sofa"],"state"=>$res["state"],"street"=>$res["street"],"tecaffe"=>$res["tecaffe"],"tel"=>$res["tel"],"tv"=>$res["tv"],"wifi"=>$res["wifi"],"pnr"=>"","pr"=>""));
                            }
                        }

                        foreach($a as $index => $res){
                            if(empty($res["hotel"])){
                                unset($a[$index]);
                            }
                        }
                        $optimalResults = $a;

                        //Well FORMATING TO ARRAY PER Javascript
                        $a = array();
                        foreach( $optimalResults as $res){
                            array_push($a,array("info"=>$res));
                        }
                        $optimalResults = $a;


                    
                        //Finding Price For Hotel Room
                        //fOREACH Hotel For Each Camera bind with price for the period
                        
                        $a = $optimalResults;
                        foreach($optimalResults as $index => $hotel){
                            foreach($hotel["info"]["rooms"] as $i => $room){
                                //Query
                                $query = "SELECT SUM(priceNR) AS pnr , SUM(priceR) AS pr FROM price_date WHERE date < '$search[checkout]' AND date >= '$search[checkin]' AND room=$room[room]";
                                
                                //Query prepared
                                $result = $db->query($query);
                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    $a[$index]["info"]["rooms"][$i]["pnr"] = $row["pnr"];
                                    $a[$index]["info"]["rooms"][$i]["pr"] = $row["pr"];
                                }
                            }
                        }
                        $optimalResults = $a;

                        //Finding Min Disponibility in amount of rooms for each type of room
                        //SELECT MIN(disp) AS dispMin FROM price_date WHERE room = 28 AND date < "2020/02/26" AND date >= "2020/02/24" 
                        $optimalResults;
                        foreach($optimalResults as $e => $hotel){
                            foreach($hotel["info"]["rooms"] as $i => $rooms){
                                $query = "SELECT MIN(disp-book) AS dispMin FROM price_date WHERE room = $rooms[room] AND date < '$search[checkout]' AND date >= '$search[checkin]'";
                                $result = $db->query($query);
                                if ($result->num_rows == 1) {
                                    $dispMin = $result->fetch_assoc()["dispMin"];
                                    $optimalResults[$e]["info"]["rooms"][$i]["dispMin"] = $dispMin;
                                }
                            }
                        }

                        //Ordering Rooms for each hotel in base of person per camera, the biggest first
                        $a = array();
                        foreach($optimalResults as $res){
                            $rooms = $res["info"]["rooms"];
                            //print_r($res["info"]["hotel"]);
                            $newRooms = array("info"=>array("hotel"=>$rooms[0]["name"],"rooms"=>array()));               
                            for($i=0;$i < sizeof($rooms);$i++){
                                for($e=$i+1;$e < sizeof($rooms);$e++){
                                    $copy = "";
                                    $totI = ($rooms[$i]["sofa"]+$rooms[$i]["bedSingle"]+2*$rooms[$i]["bedDouble"]);
                                    $totE = ($rooms[$e]["bedSingle"]+2*$rooms[$e]["bedDouble"]+$rooms[$e]["sofa"]);
                                    if($totE< $totI){
                                        $copy = $rooms[$i];
                                        $rooms[$i] =$rooms[$e];
                                        $rooms[$e] =$copy;
                                    }
                                }
                                $newRooms["info"]["rooms"]= $rooms;
                            }
                            array_push($a,$newRooms); 
                        }
                        $optimalResults = $a;
                        

                        
                        
                        //Filtering the HOltels only the hotel that can
                        //Filtering all the records to extract only the results with availability for all the person
                        // pupose camera suficiently to all people for booking and in base of the request amount of camera
                        //For each type of room it need to be an amount request by the user
                        $a = array();
                        foreach($optimalResults as $res){
                            $searchPersRooms = $search["roomsPerson"];
                            $rooms = $res["info"]["rooms"];
                            $roomsPersonTot = array();
                            foreach($rooms as $room){
                                array_push($roomsPersonTot ,array("person"=>($room["bedSingle"]+2*$room["bedDouble"]+$room["sofa"]),"dispMin"=>$room["dispMin"]));
                            }
                            $contSearch = 0;
                            $stop=false;
                            $pnrTot = 0;
                            $prTot = 0;
                            foreach($roomsPersonTot  as $key => $roomPersonTot){
                                if($stop){break;}
                                for($i=0;$i< $roomPersonTot["dispMin"];$i++){
                                    if($contSearch>=sizeof($searchPersRooms)){$stop=true;break;}
                                    //print();
                                    if($roomPersonTot["person"]-$searchPersRooms[$contSearch]>=0){
                                        $contSearch++;
                                        $pnrTot += $res["info"]["rooms"][$key]["pnr"];
                                        $prTot += $res["info"]["rooms"][$key]["pr"];
                                    }else{
                                        break;
                                    }
                                }
                            }

                            foreach($rooms as $key => $room){
                                $res["info"]["rooms"][$key]["pnrTot"] = $pnrTot;
                                $res["info"]["rooms"][$key]["prTot"] = $prTot;
                            }

                            if($contSearch>=sizeof($searchPersRooms)){
                                array_push($a,$res);
                            }
                        }
                        $optimalResults = $a;  


                        //Filter HotelChain
                        if(isset($_POST['hc']) && !empty($_POST['hc'])){
                           
                            $a = array();
                            foreach($optimalResults as $e => $hotel){
                                $roomEx = $hotel["info"]["rooms"][0];
                                if($roomEx["hotelchain"]==$_POST['hc']){
                                    array_push($a,$hotel);
                                }
                            }
                            $optimalResults = $a;
                        }

                        //APPLYINS FILTER
                        //echo"<hr/>";
                        if(!empty($_POST["filter"])){
                            $_POST["filter"]=str_replace("=on&","%",$_POST["filter"]);
                            $_POST["filter"]=str_replace("&","%",$_POST["filter"]);
                            $_POST["filter"]=str_replace("=on","%",$_POST["filter"]);
                            $options = explode("%",$_POST["filter"]);
                            $a = array();
                            foreach($optimalResults as $e => $hotel){
                                
                                $r = array();
                                foreach($hotel["info"]["rooms"] as $i => $room){
                                    $stop=false;
                                    
                                    foreach($options as $option){
                                        switch ($option) {
                                            case "checkRest":
                                                if(!$room["rest"]){$stop=true;break;}
                                                break;
                                            case "checkPark":
                                                if(!$room["park"]){$stop=true;break;}
                                                break;
                                            case "checkWifi":
                                                if(!$room["wifi"]){$stop=true;break;}
                                                break;
                                            case "checkBreak":
                                                if(!$room["break"]){$stop=true;break;}
                                                break;
                                            case "checkPrivPark":
                                                if(!$room["privpark"]){$stop=true;break;}
                                                break;
                                            case "checkPets":
                                                if(!$room["animal"]){$stop=true;break;}
                                                break;
                                            case "checkDisab":
                                                if(!$room["dis"]){$stop=true;break;}
                                                break;
                                            case "checkAc":
                                                if(!$room["ac"]){$stop=true;break;}
                                                break;
                                            case "checkPrivBath":
                                                if(!$room["privatebath"]){$stop=true;break;}
                                                break;
                                            case "checkTv":
                                                if(!$room["tv"]){$stop=true;break;}
                                                break;
                                            case "checkMiniBar":
                                                if(!$room["minibar"]){$stop=true;break;}
                                                break;
                                            case "checkSafe":
                                                if(!$room["safe"]){$stop=true;break;}
                                                break;
                                            case "checkTeCaffe":
                                                if(!$room["tecaffe"]){$stop=true;break;}
                                                break;
                                            case "checkPhone":
                                                if(!$room["tel"]){$stop=true;break;}
                                                break;
                                            case "star1":
                                                if($room["stars"]<1){$stop=true;break;}
                                                break;
                                            case "star2":
                                                if($room["stars"]<2){$stop=true;break;}
                                                break; 
                                            case "star3":
                                                if($room["stars"]<3){$stop=true;break;}
                                                break;
                                            case "star4":
                                                if($room["stars"]<4){$stop=true;break;}
                                                break;
                                            case "star5":
                                                if($room["stars"]<5){$stop=true;break;}
                                                break;                          
                                        }

                                        if(substr($option,0,10)=="maxPInput="){
                                            if(substr($option,10)<$room["pnrTot"]){
                                                
                                                $stop=true;
                                            }
                                        }
                                        if(substr($option,0,10)=="minPInput="){
                                            if(substr($option,10)>$room["pnrTot"]){
                                                
                                                $stop=true;
                                            }
                                        }
                                    }
                                    if(!$stop){array_push($r,$room);}
                                }                                
                                if(!empty($r)){array_push($a,array("info"=>array("hotel"=>$hotel["info"]["rooms"][0]["name"],"rooms"=>$r)));}
                            } 
                            $optimalResults = $a;
                           // print_r($optimalResults);
                           // echo"<hr/>";
                        }

                        //Apliyng filter for hotel , for alert
                        if(!empty($_POST["hotelFilter"])){
                            $a = array();
                            foreach($optimalResults as $e => $hotel){
                                $add = false;
                                $roomEx = $hotel["info"]["rooms"][0];
                                foreach($_POST["hotelFilter"] as $hotelfilter){
                                    if($hotelfilter==$roomEx["hotelid"]){
                                        $add = true;
                                        break;
                                    }
                                }
                                if($add)
                                array_push($a,$hotel);
                            }
                            $optimalResults = $a;
                        }

                        
                }
        }
}



print(json_encode($optimalResults));

/*
print_r($hotelsAll);
echo "(hotelsAll);<br/>";
print_r($hotelsAvail);
echo "(hotelsAvail);<br/>";
print_r($roomsAvail);
echo "(roomsAvail);<br/>";
print_r($disponibility);
echo "(disponibility);<br/>";
//DEBUGING
*/

?>
