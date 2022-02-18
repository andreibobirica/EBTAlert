<?php
    include_once "../../../core/Database.php";
    $db = new Database();

    print_r($_GET);
    if(isset($_GET["ModSingle"])){

        if((!empty($_GET["dis"]) && $_GET["dis"]!="") || $_GET["dis"]=="0"){
            $query = "SELECT * FROM price_date WHERE date='$_GET[date]' AND room='$_GET[room]'";
            echo $query;
            $result = $db->query($query);
            if ($result->num_rows == 1) {
                //Esiste già -> Modifica
                $row = $result->fetch_assoc();
                $sql = "UPDATE price_date SET disp='$_GET[dis]' WHERE date='$_GET[date]' AND room='$_GET[room]'";
                echo $sql;
                $db->query($sql);
            }elseif($result->num_rows == 0){
                //Non esiste, inseriamo
                $sql = "INSERT INTO price_date (disp,date,room) VALUES ('$_GET[dis]','$_GET[date]','$_GET[room]')";
                echo $sql;
                $db->query($sql);
            }
        }elseif((!empty($_GET["pnr"]) && $_GET["pnr"]!="") || $_GET["pnr"]=="0"){
            $query = "SELECT * FROM price_date WHERE date='$_GET[date]' AND room='$_GET[room]'";
            echo $query;
            $result = $db->query($query);
            if ($result->num_rows == 1) {
                //Esiste già -> Modifica
                $row = $result->fetch_assoc();
                $sql = "UPDATE price_date SET priceNR='$_GET[pnr]' WHERE date='$_GET[date]' AND room='$_GET[room]'";
                echo $sql;
                $db->query($sql);
            }elseif($result->num_rows == 0){
                //Non esiste, inseriamo
                $sql = "INSERT INTO price_date (priceNR,date,room) VALUES ('$_GET[pnr]','$_GET[date]','$_GET[room]')";
                echo $sql;
                $db->query($sql);
            }
        }elseif((!empty($_GET["pr"]) && $_GET["pr"]!="") || $_GET["pr"]=="0"){
            $query = "SELECT * FROM price_date WHERE date='$_GET[date]' AND room='$_GET[room]'";
            echo $query;
            $result = $db->query($query);
            if ($result->num_rows == 1) {
                //Esiste già -> Modifica
                $row = $result->fetch_assoc();
                $sql = "UPDATE price_date SET priceR='$_GET[pr]' WHERE date='$_GET[date]' AND room='$_GET[room]'";
                echo $sql;
                $db->query($sql);
            }elseif($result->num_rows == 0){
                //Non esiste, inseriamo
                $sql = "INSERT INTO price_date (priceR,date,room) VALUES ('$_GET[pr]','$_GET[date]','$_GET[room]')";
                echo $sql;
                $db->query($sql);
            }
        }
    }
    elseif(isset($_GET["ModAll"])){

        $now = time();
        $since = $_GET["since"];
        $sinceTime = strtotime($since);
        $until = $_GET["until"];
        $untilTime= strtotime($until);

        $room = $_GET["room"];

        //Calculating The day between since and until and adding there in an array
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
                array_push($price_dates,array("date"=> $date,"room"=>null));
            }
        }



        echo $_GET["dis"];
        if((!empty($_GET["dis"]) && $_GET["dis"]!="") || $_GET["dis"]=="0"){
            foreach ($price_dates as $key => $value) {
                if(!empty($value["room"])){
                    //Modify
                    $row = $result->fetch_assoc();
                    $sql = "UPDATE price_date SET disp='$_GET[dis]' WHERE date='$value[date]' AND room='$_GET[room]'";
                    echo $sql;
                    echo "</br>";
                    $db->query($sql);
                }else{
                    //insert
                    $sql = "INSERT INTO price_date (disp,date,room) VALUES ('$_GET[dis]','$value[date]','$_GET[room]')";
                    echo $sql;
                    echo "</br>";
                    $db->query($sql);
                }
            }
            exit();
        }
        elseif((!empty($_GET["pnr"]) && $_GET["pnr"]!="") || $_GET["pnr"]=="0"){
            foreach ($price_dates as $key => $value) {
                if(!empty($value["room"])){
                    //Modify
                    $row = $result->fetch_assoc();
                    $sql = "UPDATE price_date SET priceNR='$_GET[pnr]' WHERE date='$value[date]' AND room='$_GET[room]'";
                    echo $sql;
                    echo "</br>";
                    $db->query($sql);
                }else{
                    //insert
                    $sql = "INSERT INTO price_date (priceNR,date,room) VALUES ('$_GET[pnr]','$value[date]','$_GET[room]')";
                    echo $sql;
                    echo "</br>";
                    $db->query($sql);
                }
            }
            exit();
        }
        elseif((!empty($_GET["pr"]) && $_GET["pr"]!="") || $_GET["pr"]=="0"){
            foreach ($price_dates as $key => $value) {
                if(!empty($value["room"])){
                    //Modify
                    $row = $result->fetch_assoc();
                    $sql = "UPDATE price_date SET priceR='$_GET[pr]' WHERE date='$value[date]' AND room='$_GET[room]'";
                    echo $sql;
                    echo "</br>";
                    $db->query($sql);
                }else{
                    //insert
                    $sql = "INSERT INTO price_date (priceR,date,room) VALUES ('$_GET[pr]','$value[date]','$_GET[room]')";
                    echo $sql;
                    echo "</br>";
                    $db->query($sql);
                }
            }
            exit();
        }
    }
?>