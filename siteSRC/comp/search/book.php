<?php
    if(!isset($_SESSION["nameAccount"])){
      //  echo "NOT LOGED IN";
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actual_link = str_replace("&","andand",$actual_link);
        $actual_link = str_replace("?","inicio",$actual_link);
        $actual_link = str_replace("=","egual",$actual_link);
       // echo $actual_link;
        echo "<meta http-equiv='refresh' content='0;url=index.php?myaccount&redirect=$actual_link'>";
        exit();
    }

    //Geting INFO
    $checkin = $_GET["checkinForm"];
    $checkout = $_GET["checkoutForm"];
    $account = $_SESSION["loginAccount"];
    $tot = $_GET["totPrice"];
    $resDate = date('Y-m-d', time());
    $hotel = "";

    $nRooms = $_GET["nRoomsForm"];
    $rooms=array();
    for($i = 0; $i < $nRooms;$i++){
        array_push($rooms,array("id"=>$_GET["roomId".$i],"pr"=>$_GET["PricePR".$_GET["roomId".$i]],"pnr"=>$_GET["PricePNR".$_GET["roomId".$i]],"Npnr"=>$_GET["PNR".$_GET["roomId".$i]],"Npr"=>$_GET["PR".$_GET["roomId".$i]]));
    }
    
    //UPDATE PRICE_DATES , book column, increment book column by 1 ,
    foreach($rooms as $room){
        $query = "UPDATE price_date SET book = book + ($room[Npnr]+$room[Npr]) WHERE room='$room[id]' AND date >= '$checkin' AND date < '$checkout'";
        $db->query($query);
    }
    //GETING HOTEL
    $id=$rooms[0]["id"];
    $query = "SELECT hotel FROM room_type WHERE id='$id'";
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hotel = $row["hotel"];
    }
    //CREATE BOOK
    $sql = "INSERT INTO book (resDate,since,until,account,tot,hotel) VALUES ('$resDate','$checkin','$checkout','$account','$tot','$hotel')";
    $db->query($sql);
    $query = "SELECT LAST_INSERT_ID() AS id;";
    $idBook = $db->query($query)->fetch_assoc()["id"];
    //Create detail BOOK
    foreach($rooms as $room){
        if($room["Npnr"]>0 || $room["Npr"]>0){
        $sql = "INSERT INTO book_detail (room_type,priceR,priceNR,nRPR,nRPNR,book) VALUES ('$room[id]','$room[pr]','$room[pnr]','$room[Npr]','$room[Npnr]','$idBook')";
        $db->query($sql);
        }
    }



    function getBookings($db,$idbook,$hotel){
      $return = "";
      $query = "SELECT user.name as name,user.surname as surname,since,until,resDate, book.id as id, tot, hotel.name as namehotel FROM book JOIN hotel ON hotel.id=book.hotel JOIN account ON account.id=book.account JOIN user ON user.account=account.id WHERE hotel='$hotel' AND book.id='$idbook'";
      $result = $db->query($query);
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()){
              $return .= "<h5>order n.$row[id]</h5><h4>$row[namehotel]</h4><table>";
              $query = "SELECT * FROM book_detail JOIN room_type ON room_type.id = book_detail.room_type WHERE book='$row[id]'";
              $r = $db->query($query);
              if ($r->num_rows > 0) {
                  while($ro = $r->fetch_assoc()){
                      if($ro["nRPR"])
                      $return .= "<tr><td>".$ro["nRPR"]." X ".$ro["nameBea"]."<small> Remborsable</small></td></tr>";
                      if($ro["nRPNR"])
                      $return .= "<tr><td>".$ro["nRPNR"]." X ".$ro["nameBea"]."<small> No Remborsable</small></td></tr>";
                  }
              }
              $return .= "</table>";

          }
      }
      return $return;
    }

    
    //Sending email Confirm
    $htmlEmailConfirmation = "
    <h3>Good Morning $_SESSION[nameAccount] $_SESSION[surnameAccount],<br/> Your reservation was succesfully completed today $resDate</h3>
    <small>Here are the detail:</small>
    <p>$_GET[checkinForm] <small> to </small> $_GET[checkoutForm]</p>
    <hr/><ul>
    ".getBookings($db,$idBook,$hotel)."
    </ul><hr/>
    <p>Total : <h4>$tot EURO</h4></p><br/>
    <small>to check detail login in our site...</small>
    <br/>
    <p>Thank you for your time... EBT Site</p>
    ";
    sendEmail($_SESSION["emailAccount"],"Reservation Confermed",$htmlEmailConfirmation);
?>

<div class="card">
  <div class="card-body">
  <h1 class="display-4">Book Completed, Thank You ;)</h1>
    <meta http-equiv='refresh' content='3;url=index.php?myaccount'>
  </div>
</div>