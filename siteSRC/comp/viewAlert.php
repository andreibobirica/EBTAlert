<?php
    $alert = array();
    $query = "SELECT * FROM alert WHERE alert.id = '$_GET[viewAlert]'";
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $alert = $result->fetch_assoc();                    
    }
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
?>

<div class='container panel'>
    <h3 class="display-4 text-white">Alert Detail</h3>
    <div class='card text-white <?php if($alert["success"]){echo "bg-success";}else{ echo "bg-dark";}?>'>
        <div class='card-body row'>

        <!-- Filter -->
        <div class='col-lg-4'>
        <h2>Detail</h2>
        <label for="location">Location <input class='form-control' type='text' id='location' readonly value='<?php echo $alert["location"];?>'/></label>
        <label for="check-in">Check-in <input class='form-control' type='text' id='check-in' readonly value='<?php echo $alert["check-in"];?>'/></label>
        <label for="check-out">Check-out <input class='form-control' type='text' id='check-out' readonly value='<?php echo $alert["check-out"];?>'/></label>
        <label for="ca">Create At <input class='form-control' type='text' id='ca' readonly value='<?php echo $alert["create"];?>'/></label>
        <div class="col  border">
        <label for="location">Price Min <input class='form-control' type='text' id='location' readonly value='€ <?php echo $filter["priceMax"];?>'/></label>
        <label for="location">Price Max <input class='form-control' type='text' id='location' readonly value='€ <?php echo $filter["priceMin"];?>'/></label>
        </div>
        </div>
        <div class="row col-lg-8">
            <div class="col  border"> 
                <input class="check" id="checkRest" disabled name="checkRest" type="checkbox"  <?php if($filter["rest"]){echo "checked";}?>/>
                <label class="check" for="checkRest"></label>
                <i class="fa fa-cutlery fa-1x" aria-hidden="true"><p class="miniSmall">Restaurant</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkPark" disabled name="checkPark" type="checkbox"  <?php if($filter["park"]){echo "checked";}?>/>
                <label class="check" for="checkPark"></label>
                <i class="fa fa-car fa-1x" aria-hidden="true"><p class="miniSmall">Park</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkWifi" disabled name="checkWifi" type="checkbox"  <?php if($filter["wifi"]){echo "checked";}?> />
                <label class="check" for="checkWifi"></label>
                <i class="fa fa-wifi fa-1x" aria-hidden="true"><p class="miniSmall">Wifi</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkBreak" disabled name="checkBreak" type="checkbox"  <?php if($filter["break"]){echo "checked";}?>/>
                <label class="check" for="checkBreak"></label>
                <i class="fa fa-coffee fa-1x" aria-hidden="true"><p class="miniSmall">Breakfast</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkPrivPark" disabled name="checkPrivPark" type="checkbox" <?php if($filter["privpark"]){echo "checked";}?>/>
                <label class="check" for="checkPrivPark"></label>
                <i class="fa fa-car fa-1x" aria-hidden="true"><p class="miniSmall">Private Park</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkPets" disabled name="checkPets" type="checkbox"  <?php if($filter["pets"]){echo "checked";}?>/>
                <label class="check" for="checkPets"></label>
                <i class="fa fa-paw fa-1x" aria-hidden="true"><p class="miniSmall">Pets</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkDisab" disabled name="checkDisab" type="checkbox"  <?php if($filter["disab"]){echo "checked";}?>/>
                <label class="check" for="checkDisab"></label>
                <i class="fa fa-wheelchair fa-1x" aria-hidden="true"><p class="miniSmall">Disable</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkAc" disabled name="checkAc" type="checkbox"  <?php if($filter["ac"]){echo "checked";}?>/>
                <label class="check" for="checkAc"></label>
                <p class="miniSmall">AC</p>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkPrivBath" disabled name="checkPrivBath" type="checkbox"  <?php if($filter["privbath"]){echo "checked";}?>/>
                <label class="check" for="checkPrivBath"></label>
                <i class="fa fa-user fa-1x" aria-hidden="true"><p class="miniSmall">Private Bathroom</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkTv" disabled name="checkTv" type="checkbox"  <?php if($filter["tv"]){echo "checked";}?>/>
                <label class="check" for="checkTv"></label>
                <p>TV</p>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkMiniBar" disabled name="checkMiniBar" type="checkbox"  <?php if($filter["minibar"]){echo "checked";}?>/>
                <label class="check" for="checkMiniBar"></label>
                <i class="fa fa-glass fa-1x" aria-hidden="true"><p class="miniSmall"><p>Mini Bar</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkSafe" disabled name="checkSafe" type="checkbox"  <?php if($filter["safe"]){echo "checked";}?>/>
                <label class="check" for="checkSafe"></label>
                <i class="fa fa-life-ring fa-1x" aria-hidden="true"><p class="miniSmall"><p>Safe</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkTeCaffe" disabled name="checkTeCaffe" type="checkbox"  <?php if($filter["tecaffe"]){echo "checked";}?> />
                <label class="check" for="checkTeCaffe"></label>
                <i class="fa fa-coffee fa-1x" aria-hidden="true"><p class="miniSmall"><p>The/Coffee</p></i>
            </div>
            <div class="col  border"> 
                <input class="check" id="checkPhone" readonly name="checkPhone" type="checkbox" <?php if($filter["phone"]){echo "checked";}?>/>
                <label class="check" for="checkPhone"></label>
                <i class="fa fa-phone fa-1x" aria-hidden="true"><p class="miniSmall"><p>Phone</p></i>
            </div>
        </div>
        <div class='col-lg-6'>
            <h2>Hotels:</h2>
            <?php
                foreach($hotels as $hotel){
                    $sql = "SELECT * FROM hotel WHERE id = '$hotel[hotel]'"; 
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    echo "<p class='lead'>Hotel id: $hotel[id] | Name: $row[name]</p>";
                }
            ?>
        </div>
        <div class='col-lg-6'>
            <h2>Rooms:</h2>
            <label for="nRooms">nRooms <input class='form-control' type='text' id='nRooms' readonly value='<?php echo $room["nRooms"];?>'/></label>
            <label for="totpeop">Tot. People <input class='form-control' type='text' id='teoPeop' readonly value='<?php echo $room["totpeop"];?>'/></label>
            <label for="location">For each Room <input class='form-control' type='text' id='nRooms' readonly value='
                <?php 
                    foreach($roomPeople as $roomP){
                        print_r($roomP["people"]);
                        echo " | ";
                    }
                ?>
            '/></label>
        </div>
        <?php

        if($alert["success"]){
            $button = "<form action='./index.php?ViewResults";
            $button.="&nRooms=$room[nRooms]";
            $button.="&locationId=$alert[location]";
            $button.="&check-in=".$alert["check-in"];
            $button.="&check-out=".$alert["check-out"];
            $button.="&lng=0";
            $button.="&lat=0";
            $button.="&priceMax=$filter[priceMax]";
            $button.="&priceMin=$filter[priceMin]";
            $button.="&rest=$filter[rest]";
            $button.="&wifi=$filter[wifi]";
            $button.="&park=$filter[park]";
            $button.="&break=$filter[break]";
            $button.="&privpark=$filter[privpark]";
            $button.="&pets=$filter[pets]";
            $button.="&disab=$filter[disab]";
            $button.="&ac=$filter[ac]";
            $button.="&privbath=$filter[privbath]";
            $button.="&tv=$filter[tv]";
            $button.="&minibar=$filter[minibar]";
            $button.="&safe=$filter[safe]";
            $button.="&tecaffe=$filter[tecaffe]";
            $button.="&phone=$filter[phone]";
            $button.="&hotels=[";
            foreach($hotels as $h){
                $button.=$h["hotel"].",";
            }
            $button = rtrim($button, ",");
            $button.="]";
            
            foreach($roomPeople as $index => $roomP){
                $i = $index +1;
                $button.="&nAdults$i=$roomP[people]&nChilds$i=0";
            }
            $button.="' method='POST'><button class='btn btn-lg bg-danger text-white' type='submit'><h3 class='display-4'>New Result Was Found !</h3></button></form>";
            echo $button;
        }
        ?>

        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    //Modify padding to fit verticaly centered
    $('.panel').css('padding-bottom', 150 + 'px');
});
</script>