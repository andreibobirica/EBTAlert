<?php
    //Find If Loged In
    $loged=false;
    if(isset($_SESSION["nameAccount"])){
        $loged=true;
    }

    //Routing Pages

    if(isset($_GET["myaccount"])){
        include_once "./comp/myaccount.php";
    }
    elseif(isset($_GET["error"])){
        include_once "./comp/errorLogin.php";
    }
    elseif(isset($_GET["contact"])){
        include_once "./comp/contact.php";
    }
    elseif(isset($_GET["viewAlert"])){
        include_once "./comp/viewAlert.php";
    }
    elseif(isset($_GET["example"])){//Alert
        include_once "./comp/example.php";
    }
    elseif(isset($_GET["hotelchains"])){
        include_once "./comp/search/hotelchains.php";
    }
    elseif(isset($_GET["controlpanel"]) && isset($_SESSION["loginAccount"]) && $auth->getIfProp($_SESSION["loginAccount"])){
        if(isset($_GET["hotel"]) && !empty($_GET["hotel"])){
            include_once "./comp/controlpanel/hotel.php";
        }elseif((isset($_GET["ModHotel"]) && !empty($_GET["ModHotel"])) || isset($_GET["InsHotel"])){
            include_once "./comp/controlpanel/ModHotel.php";
        }elseif(isset($_GET["rooms"]) && !empty($_GET["rooms"])){
            include_once "./comp/controlpanel/rooms.php";
        }elseif(isset($_GET["roomImage"]) && !empty($_GET["roomImage"])){
            include_once "./comp/controlpanel/roomImage.php";
        }elseif((isset($_GET["ModRoom"]) && !empty($_GET["ModRoom"])) || isset($_GET["InsRoom"])){
            include_once "./comp/controlpanel/ModRoom.php";
        }
        
        elseif(isset($_GET["ViewDisp"]) && !empty($_GET["ViewDisp"])){
            include_once "./comp/controlpanel/disp/ViewDisp.php";
        }elseif(isset($_GET["ModDisp"]) && !empty($_GET["ModDisp"])){
            include_once "./comp/controlpanel/disp/ModDisp.php";
        }else{
            include_once "./comp/controlpanel/controlpanel.php";
        }
    }elseif(isset($_GET["ViewResults"])){
        include_once "./comp/search/ViewResults.php";
    }elseif(isset($_GET["checkinForm"])){//BOOKING PART
        include_once "./comp/search/book.php";
    }elseif(isset($_GET["alert"])){//Alert
        include_once "./comp/search/alert.php";
    }
    else{
        include_once "./comp/search/search.php";
    }


?>