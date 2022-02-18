<?php
    include_once "../../../core/Database.php";
    $db = new Database();

//GET Price Data

if(isset($_GET["pricelist"])){
    $query = "SELECT * FROM price_list WHERE room=$_GET[room]";
    $result = $db->query($query);
    $price_list = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($price_list,$row);
        }
    }
    print(json_encode($price_list));
    exit();
}



//Disponibility GET DATA    

//print_r($_GET);
    $now = time();
    $since = $_GET["since"];
    $sinceTime = strtotime($since);
    $until = $_GET["until"];
    $untilTime= strtotime($until);

    $room = $_GET["room"];

    //Calculating The day between since and until and adding there in an array
    //print(($untilTime+86400-$sinceTime)/86400);
    if(($untilTime+86400-$sinceTime)/86400){
        $untilTime+=((30-(($untilTime+86400-$sinceTime)/86400)))*86400;
    }
    //print(($untilTime+86400-$sinceTime)/86400);


    $dates = array();
    while($sinceTime<$untilTime+86400){
        array_push($dates,date('Y-m-d', $sinceTime));
        $sinceTime+=86400;
    }
    //print_r($dates);
   
    //Geting data from Database
    $price_dates = array();
    foreach($dates as $date){
        $query = "SELECT * FROM price_date WHERE date='$date' AND room='$room'";
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            array_push($price_dates,$row);
        }elseif($result->num_rows == 0) {
            array_push($price_dates,array("date"=> $date, "disp"=>"0", "book"=>"0", "priceNR"=>"0", "priceR"=>"0", "room"=>""));
        }
    }

    //debugresults
    /*
    foreach($price_dates as $price_date){
        echo "<br/>";
        print_r($price_date);
    }
    */
    print(json_encode($price_dates));
    
    
?>