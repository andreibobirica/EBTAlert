
<?php
//print_r($_POST);

if(!isset($_SESSION["nameAccount"])){
    // echo $actual_link;
    echo "<meta http-equiv='refresh' content='0;url=index.php?myaccount'>";
    exit();
}

$roomsDetailPerson = array();
$search['roomsPerson'] = array();
$search['totPeop'] = 0;
for($i = 1; $i <= $_POST["nRooms"];$i++){
    if(isset($_POST["nAdults".$i]) && isset($_POST["nChilds".$i])){
        if(empty($_POST["nAdults".$i])){
            $_POST["nAdults".$i]=0;
        }
        if(empty($_POST["nChilds".$i])){
            $_POST["nChilds".$i]=0;
        }
        array_push($roomsDetailPerson,array("nAdults"=>$_POST["nAdults".$i],"nChilds"=>$_POST["nChilds".$i]));
        array_push($search['roomsPerson'],$_POST["nAdults".$i]+$_POST["nChilds".$i]);
        $search['totPeop']+=$_POST["nAdults".$i]+$_POST["nChilds".$i];
    }
}

//Saving search propertis
$search['location'] = $_POST["locationId"];
$search['checkin']  = $_POST["check-in"];
$search['checkout']  = $_POST["check-out"];
$search['nRooms']  = $_POST["nRooms"];

if(isset($_POST['saveAlert'])){

    saveAlert($db,$search);
    echo '
    <div class="card">
        <div class="card-body">
            <h1 class="display-4">Alert Created, Thank You ;)</h1>
            <meta http-equiv="refresh" content="3;url=index.php?myaccount">
        </div>
    </div>';
    exit();
}

function saveAlert($db,$search){
    $sql = "INSERT INTO `alert` (`id`, `location`, `check-in`, `check-out`, `account`, `create`) VALUES (NULL, '$search[location]', '$search[checkin]', '$search[checkout]', '$_SESSION[loginAccount]', current_timestamp());"; 
    $db->query($sql);

    $query = "SELECT LAST_INSERT_ID() AS id;";
    $result = $db->query($query);
    $alertId = "";
    if ($result->num_rows == 1) {
        $alertId = $result->fetch_assoc()["id"];
    }

    $sql = "INSERT INTO `alert_room` (`nRooms`, `totpeop`, `alert`) VALUES ('$_POST[nRooms]', '$search[totPeop]', '$alertId')"; 
    $db->query($sql);

    //Alert Filter data
    $priceMax = (!empty($_POST["maxPInput"])) ? $_POST["maxPInput"] : 9999999999;
    $priceMin = (!empty($_POST["minPInput"])) ? $_POST["minPInput"] : 0;
    $rest = (isset($_POST["checkRest"]) ? true : 0);
    $park = (isset($_POST["checkPark"]) ? true : 0);
    $wifi = (isset($_POST["checkWifi"]) ? true : 0);
    $break = (isset($_POST["checkBreak"]) ? true : 0);
    $privpark = (isset($_POST["checkPrivPark"]) ? true : 0);
    $pets = (isset($_POST["checkPets"]) ? true : 0);
    $disab = (isset($_POST["checkDisab"]) ? true : 0);
    $ac = (isset($_POST["checkAc"]) ? true : 0);
    $privbath = (isset($_POST["checkPrivBath"]) ? true : 0);
    $tv = (isset($_POST["checkTv"]) ? true : 0);
    $minibar = (isset($_POST["checkMiniBar"]) ? true : 0);
    $safe = (isset($_POST["checkSafe"]) ? true : 0);
    $tecaffe = (isset($_POST["checkTeCaffe"]) ? true : 0);
    $phone = (isset($_POST["checkPhone"]) ? true : 0);

    $sql = "INSERT INTO `alert_filter` (`alert`, `priceMax`, `priceMin`, `rest`, `park`, `wifi`, `break`, `privpark`, `pets`, `disab`, `ac`, `privbath`, `tv`, `minibar`, `safe`, `tecaffe`, `phone`) 
    VALUES ('$alertId', '$priceMax','$priceMin', '$rest', '$park', '$wifi', '$break', '$privpark', '$pets', '$disab', '$ac', '$privbath', '$tv', '$minibar', '$safe', '$tecaffe', '$phone');"; 
    $db->query($sql);

    //alert hotel

    foreach($_POST as $name => $item){
        if(substr($name,0,10)=="alertHotel"){
            $sql = "INSERT INTO `alert_hotel` (`alert`, `hotel`) VALUES ('$alertId', '$item');";
            $db->query($sql);
        }
    }

    //alert roomPerson
    foreach($search['roomsPerson'] as $name => $item){
        $sql = "INSERT INTO `alert_roompeople` (`alert`, `people`) VALUES ('$alertId', '$item');";
        $db->query($sql);
    }  
      
    //Sending email Confirm
    $htmlEmailConfirmation = "
    <h3>Good Morning $_SESSION[nameAccount] $_SESSION[surnameAccount],<br/> Your alert was succesfully created</h3>
    <small>Here are the detail:</small>
    <p>$search[checkin] <small> to </small> $search[checkout] </p>
    <small>to check detail login in our site...</small>
    <br/>
    <p>Thank you for your time... EBT Site</p>
    ";
    sendEmail($_SESSION["emailAccount"],"Alert Created",$htmlEmailConfirmation);
    
}



?>

<div class='panel container-fluid'>
    <p class='display-4 text-white'>Create Personal Alert</p> 
    <form id='formSearch' method='POST' action='' class='card-body'>
        <?php
        if(!isset($_POST["oneHotel"])):
        ?>
            <div class='row'>
                <div class='col'>
                    <p class='lead text-white'>Please select your hotel witch you would like to be alerted</p>
                </div>
                <div class='col'>
                    <label for='hotelFinder' class='text-white'>Search Hotel Name</label>
                    <input type='text' id='hotelFinder' class='form-control' placeholder='Hotel Name...'/>
                </div>
            </div>
            <div id='mapResult'>
                <div id="map" style="width: 100%; height: 500px; border: 1px solid #ccc"></div>
            </div>
        <?php
        else:
        ?>
            <div class='row'>
                <input type="hidden" name="alertHotel" value="<?php echo $_POST["oneHotel"];?>"/>
                <p class='lead text-white'>For Hotel <?php echo $_POST["oneHotelName"];?></p>
            </div>
        <?php
        endif;
        ?>
        <hr/>
        <div class="card bg-secondary text-white">
            <div id="formAlert">
                    <div class='row'>
                        <div class='col'>
                            <div class="form-group">
                                <label for="locationId" class="bmd-label-floating">Destination</label>
                                <input id="locationId" name="locationId" type="text" class="form-control" value="<?php if(isset($search['location'])){echo $search['location'];} ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="check-in" class="label">check-in</label>
                                <input id="check-in" name="check-in" type="date" class="form-control" value="<?php if(isset($search['checkin'])){echo $search['checkin'];} ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="check-out" class="label">check-out</label>
                                <input id="check-out" name="check-out" type="date" class="form-control" value="<?php if(isset($search['checkout'])){echo $search['checkout'];} ?>">
                            </div>
                        </div>
                        <div class="col">
                            <label for="nRooms" class="label">Rooms</label>
                            <table><tr><td>
                                <span id="RoomsMin" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>
                            </td><td>
                                <input id="nRooms"name="nRooms" type="text"  readonly min="1" max="30" class="form-control" value="<?php echo $search['nRooms']; ?>">
                            </td><td>
                                <span id="RoomsPlus" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>
                            <td></tr></table>
                        </div>  
                    </div>
                    <hr/>
                    <div class="row"> 
                        <div class='col-6 col-md-2'>
                            <p class="text-center">n. Adults</p>
                        </div>
                        <div class='col-6 col-md-2'>
                            <p class="text-center">n. Children</p>
                        </div>
                    </div>
                    <div id="roomList">
                        <?php
                        foreach($roomsDetailPerson as $key => $roomPers){
                            $key+=1;
                            print("
                                        <div id='room$key'>
                                            <!--<hr/>-->
                                            <div class='row'>
                                                <div class='col-6 col-md-4 row'>
                                                <a href='#' class='badge badge-success'>ROOM $key</a>
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class='col-6 col-md-2'>
                                                    <div class='form-group'>
                                                        <table><tr><td>
                                                            <span id='AdultsMin$key' class='oi oi-minus btn btn-secondary' title='plus' aria-hidden='true'></span>
                                                        </td><td>
                                                            <input id='nAdults$key' name='nAdults$key' type='text' readonly min='1' max='30' class='form-control' value='$roomPers[nAdults]'>
                                                        </td><td>
                                                            <span id='AdultsPlus$key' class='oi oi-plus btn btn-secondary' title='plus' aria-hidden='true'></span>
                                                        <td></tr></table>
                                                    </div>
                                                </div>
                                                <div class='col-6 col-md-2'>
                                                    <div class='form-group'>
                                                        <table><tr><td>
                                                            <span id='ChildsMin$key' class='oi oi-minus btn btn-secondary' title='plus' aria-hidden='true'></span>
                                                        </td><td>
                                                            <input id='nChilds$key' name='nChilds$key' type='text' readonly min='0' max='10' class='form-control' value='$roomPers[nChilds]'>
                                                        </td><td>
                                                            <span id='ChildsPlus$key' class='oi oi-plus btn btn-secondary' title='plus' aria-hidden='true'></span>
                                                        <td></tr></table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            ");
                        }
                        ?>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class='col-12'>
                            <div class="form-group">
                                <label class='lead' for="formControlRange">Min Price € 
                                    <div style='display:inline' id="minP">0</div>
                                </label>
                                <input type="range" name="minPInput" id="minPInput" value="0" min="0" max="1500" step="5" id="formControlRange">
                            </div>
                        </div>
                        <div class='col-12'>
                            <div class="form-group">
                                <label class='lead' for="formControlRange">Max Price €
                                    <div style='display:inline' id="maxP">∞</div>
                                </label>
                                <input type="range" name="maxPInput" id="maxPInput" value="1500" min="0" max="1500" step="5" id="formControlRange">
                            </div>
                        </div>
                    </div>
                    <script>
                        //Script to increment rooms or not
                        $(document).ready(function() {
                            $("#maxPInput").change(max);
                            $('#maxPInput').on('keyup',max);
                            function min(){
                                $("#minP").html($("#minPInput").val());
                            }
                            $("#minPInput").change(min);
                            $('#minPInput').on('keyup',min);
                            function max(){
                                $("#maxP").html($("#maxPInput").val());
                                if($("#maxPInput").val()==1500){
                                    $("#maxP").html("∞");
                                }
                            }
                        });
                    </script>
                    
                    <div class="row">
                            <div class="col  border"> 
                                <input class="check" id="checkRest" name="checkRest" type="checkbox" />
                                <label class="check" for="checkRest"></label>
                                <i class="fa fa-cutlery fa-1x" aria-hidden="true"><p class="miniSmall">Restaurant</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkPark" name="checkPark" type="checkbox" />
                                <label class="check" for="checkPark"></label>
                                <i class="fa fa-car fa-1x" aria-hidden="true"><p class="miniSmall">Park</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkWifi" name="checkWifi" type="checkbox" />
                                <label class="check" for="checkWifi"></label>
                                <i class="fa fa-wifi fa-1x" aria-hidden="true"><p class="miniSmall">Wifi</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkBreak" name="checkBreak" type="checkbox" />
                                <label class="check" for="checkBreak"></label>
                                <i class="fa fa-coffee fa-1x" aria-hidden="true"><p class="miniSmall">Breakfast</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkPrivPark" name="checkPrivPark" type="checkbox" />
                                <label class="check" for="checkPrivPark"></label>
                                <i class="fa fa-car fa-1x" aria-hidden="true"><p class="miniSmall">Private Park</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkPets" name="checkPets" type="checkbox" />
                                <label class="check" for="checkPets"></label>
                                <i class="fa fa-paw fa-1x" aria-hidden="true"><p class="miniSmall">Pets</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkDisab" name="checkDisab" type="checkbox" />
                                <label class="check" for="checkDisab"></label>
                                <i class="fa fa-wheelchair fa-1x" aria-hidden="true"><p class="miniSmall">Disable</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkAc" name="checkAc" type="checkbox" />
                                <label class="check" for="checkAc"></label>
                                <p class="miniSmall">AC</p>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkPrivBath" name="checkPrivBath" type="checkbox" />
                                <label class="check" for="checkPrivBath"></label>
                                <i class="fa fa-user fa-1x" aria-hidden="true"><p class="miniSmall">Private Bathroom</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkTv" name="checkTv" type="checkbox" />
                                <label class="check" for="checkTv"></label>
                                <p>TV</p>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkMiniBar" name="checkMiniBar" type="checkbox" />
                                <label class="check" for="checkMiniBar"></label>
                                <i class="fa fa-glass fa-1x" aria-hidden="true"><p class="miniSmall"><p>Mini Bar</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkSafe" name="checkSafe" type="checkbox" />
                                <label class="check" for="checkSafe"></label>
                                <i class="fa fa-life-ring fa-1x" aria-hidden="true"><p class="miniSmall"><p>Safe</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkTeCaffe" name="checkTeCaffe" type="checkbox" />
                                <label class="check" for="checkTeCaffe"></label>
                                <i class="fa fa-coffee fa-1x" aria-hidden="true"><p class="miniSmall"><p>The/Coffee</p></i>
                            </div>
                            <div class="col  border"> 
                                <input class="check" id="checkPhone" name="checkPhone" type="checkbox" />
                                <label class="check" for="checkPhone"></label>
                                <i class="fa fa-phone fa-1x" aria-hidden="true"><p class="miniSmall"><p>Phone</p></i>
                            </div>
                        </div>
                <button type="submit" form='formSearch' name='saveAlert' class="btn btn-raised btn-lg btn-danger float-right col">Create Personal Alert</button>
            </div>           
        </div>  
    </form>
</div>

<?php
if(!isset($_POST["oneHotel"])):
?>
<script>
$( document ).ready(function() {
    //Modify padding to fit verticaly centered
    $('.panel').css('padding-bottom', 150 + 'px');
    var coord = "";
    var hotels = [];

    find();
    function doneWaitType(time,func,input)
    {
            var typingTimer;                //timer identifier
            var doneTypingInterval = time;  //time in ms, 5 second for example
            var $input = $(input);

            //on keyup, start the countdown
            $input.on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            //on keydown, clear the countdown 
            $input.on('keydown', function () {
                clearTimeout(typingTimer);
            });

            //user is "finished typing," do something
            function doneTyping () {
                func();
            }
    }   
    doneWaitType(600,function(){
        find();
    },"#locationId");


    function find(){
        $.ajax({
        url: "https://www.mapquestapi.com/geocoding/v1/address?key=VYuZMOZ9JCOPzk2Dx2eZB5Zu4Y9WDy4u&inFormat=kvp&outFormat=json&location=" + $("#locationId").val() + "&thumbMaps=false",
        type: 'get',
        dataType: 'text',
        success: function(data) {
            coord = JSON.parse(data).results[0].locations[0].latLng;
            //Get another time the dat
            showmap(coord.lat,coord.lng,false);
            
        },
        error: function(data) {
            //anulla
        }
        });
    }

    /*filtre seraching the hotel name*/
    doneWaitType(600,function(){
        hotels.forEach(hotel => {
            if(hotel.name==$("#hotelFinder").val()){
                //Look into hotel
                showmap(coord.lat,coord.lng,hotel);
            }
        });
    },"#hotelFinder");

    //SHOW ON MAP THE RESULTS
    function showmap(lat,lng,h){
        if(h){
            lat = h.lati;
            lng= h.longi;
        }
        //MAP
        $("#mapResult").empty();
        $("#mapResult").html('<div id="map" style="width: 100%; height: 500px; border: 1px solid #ccc"></div>');
        var map = L.map('map').setView([lat,lng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        // Set the title to show on the polygon button
        L.drawLocal.draw.toolbar.buttons.circle = 'Mark the area to be alerted!';
        var drawControl = new L.Control.Draw({
            position: 'topleft',
            draw: {
                rectangle: false,
                polyline: false,
                polygon: false,
                circle: true,
                circlemarker: false,
                marker: false
            }
        });
        drawControl.setDrawingOptions({circle: {shapeOptions: {color: '#004a80'}}});
        map.addControl(drawControl);

        //On Circle Created EVENT
        map.on(L.Draw.Event.CREATED, function (e) {
            var type = e.layerType,
                layer = e.layer;

            if (type === 'circle') {
                //layer.bindPopup('A popup!');
            }
            updateHotelMap(e.layer._latlng,e.layer._mRadius);
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);
        });

        getAllHotels();
        function getAllHotels(){
            $.get("./comp/search/getAllHotels.php", function(data, status){
                hotels = JSON.parse(data);
                hotels.forEach(hotel => {
                    showHotelOnMap(hotel);
                });
            });
        }

        function showHotelOnMap(hotel){
            var content = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' class='form-check-input cbbig hotelcb' id='check"+hotel.id+"'  name='alertHotel"+hotel.id+"' value='"+hotel.id+"'><small>"+hotel.name+"</small>"
            var popupOptions = {autoPan:false,autoClose:false,closeOnClick:false,closeOnEscapeKey:false,closeButton:false};
            if(h.name==hotel.name){
                console.log("h diverse false");
                content = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' class='form-check-input cbbig hotelcb' id='check"+hotel.id+"' checked='checked'  name='alertHotel"+hotel.id+"' value='"+hotel.id+"'><small>"+hotel.name+"</small>"
            }  
            
            var marker = L.marker([hotel.lati,hotel.longi]).bindPopup(L.popup(popupOptions).setContent(content)).addTo(map);
            marker.openPopup();
            marker.off('click');
            
        }

        function updateHotelMap(coord,radius){
            console.log(hotels);
            //Disable all the hotels
            $(".hotelcb").prop('checked', false);
            hotels.forEach(hotel => {
                var distance = distVincenty(hotel.lati,hotel.longi,coord.lat,coord.lng);
                if(distance<radius){
                    $("#check"+hotel.id).prop('checked', true);
                }
            });
        }
    }

   

    function toRad(n) {
        return n * Math.PI / 180;
    };
    function distVincenty(lat1, lon1, lat2, lon2) {
        var a = 6378137,
            b = 6356752.3142,
            f = 1 / 298.257223563, // WGS-84 ellipsoid params
            L = toRad(lon2-lon1),
            U1 = Math.atan((1 - f) * Math.tan(toRad(lat1))),
            U2 = Math.atan((1 - f) * Math.tan(toRad(lat2))),
            sinU1 = Math.sin(U1),
            cosU1 = Math.cos(U1),
            sinU2 = Math.sin(U2),
            cosU2 = Math.cos(U2),
            lambda = L,
            lambdaP,
            iterLimit = 100;
        do {
        var sinLambda = Math.sin(lambda),
            cosLambda = Math.cos(lambda),
            sinSigma = Math.sqrt((cosU2 * sinLambda) * (cosU2 * sinLambda) + (cosU1 * sinU2 - sinU1 * cosU2 * cosLambda) * (cosU1 * sinU2 - sinU1 * cosU2 * cosLambda));
        if (0 === sinSigma) {
        return 0; // co-incident points
        };
        var cosSigma = sinU1 * sinU2 + cosU1 * cosU2 * cosLambda,
            sigma = Math.atan2(sinSigma, cosSigma),
            sinAlpha = cosU1 * cosU2 * sinLambda / sinSigma,
            cosSqAlpha = 1 - sinAlpha * sinAlpha,
            cos2SigmaM = cosSigma - 2 * sinU1 * sinU2 / cosSqAlpha,
            C = f / 16 * cosSqAlpha * (4 + f * (4 - 3 * cosSqAlpha));
        if (isNaN(cos2SigmaM)) {
        cos2SigmaM = 0; // equatorial line: cosSqAlpha = 0 (§6)
        };
        lambdaP = lambda;
        lambda = L + (1 - C) * f * sinAlpha * (sigma + C * sinSigma * (cos2SigmaM + C * cosSigma * (-1 + 2 * cos2SigmaM * cos2SigmaM)));
        } while (Math.abs(lambda - lambdaP) > 1e-12 && --iterLimit > 0);

        if (!iterLimit) {
        return NaN; // formula failed to converge
        };

        var uSq = cosSqAlpha * (a * a - b * b) / (b * b),
            A = 1 + uSq / 16384 * (4096 + uSq * (-768 + uSq * (320 - 175 * uSq))),
            B = uSq / 1024 * (256 + uSq * (-128 + uSq * (74 - 47 * uSq))),
            deltaSigma = B * sinSigma * (cos2SigmaM + B / 4 * (cosSigma * (-1 + 2 * cos2SigmaM * cos2SigmaM) - B / 6 * cos2SigmaM * (-3 + 4 * sinSigma * sinSigma) * (-3 + 4 * cos2SigmaM * cos2SigmaM))),
            s = b * A * (sigma - deltaSigma);
        return s.toFixed(3); // round to 1mm precision
    };


    //  Bind the event handler to the "submit" JavaScript event
    $('#formSearch').submit(function () {
        // Get the Login Name value and trim it
        var checks = $( ".cbbig:checked" );
        console.log(checks)
        // Check if empty of not
        if (checks.length < 1) {
            alert('Please Select almost one hotel in the map.');
            return false;
        }
    });

});
</script>
<?php
endif;
?>


<script>
$(document).ready(function() {
    //Modify padding to fit verticaly centered
    $('.panel').css('padding-bottom', 200 + 'px');

    //Hide Spinner
    $('#spin').hide();

    //Script minus, plus  FOR rooms number AND only for the fist room detail
    var nAdults = $("#nAdults1").val();
    var nChilds = $("#nChilds1").val();
    var nRooms = $("#nRooms").val();
    //Adults
    $("#AdultsMin1").click(function() { 
        if(nAdults>1){ 
            //console.log("Adults min");
            $("#nAdults1").val(--nAdults);
        }
    });
    $("#AdultsPlus1").click(function() {
        if(nAdults<30){ 
            //console.log("Adults plus");
            $("#nAdults1").val(++nAdults);
        }
    });
    //Childs
    $("#ChildsMin1").click(function() { 
        if(nChilds>0){ 
            //console.log("Childs min");
            $("#nChilds1").val(--nChilds);
        }
    });
    $("#ChildsPlus1").click(function() {
        if(nChilds<10){ 
            //console.log("Rooms plus");
            $("#nChilds1").val(++nChilds);
        }
    });

    //Rooms
    $("#RoomsMin").click(function() { 
        if(nRooms>1){ 
            //console.log("Childs min");
            $("#nRooms").val(--nRooms);
            removeRoom($("#nRooms").val()+1);
        }
    });
    $("#RoomsPlus").click(function() {
        if(nRooms<30){ 
            //console.log("Rooms plus");
            $("#nRooms").val(++nRooms);
            addRoom($("#nRooms").val());
        }
    });

    
    var roomTotalCreated = 0;
    var roomTotalCreatedNow = 1;
    function removeRoom(n){
        $('#roomList').children().last().remove();
        roomTotalCreated--;
    }

    function addRoom(n){
        roomTotalCreated++;
        var room = '<div id="room1">\
            <div class="row">\
                <div class="col-6 col-md-4 row">\
                <a href="#" class="badge badge-success">ROOM '+n+'</a>\
                </div>\
            </div>\
            <div class="row">\
                <div class="col-6 col-md-2">\
                    <div class="form-group">\
                        <table><tr><td>\
                            <span id="AdultsMin'+n+'" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>\
                        </td><td>\
                            <input id="nAdults'+n+'" name="nAdults'+n+'" type="text" readonly min="1" max="30" class="form-control" value="1">\
                        </td><td>\
                            <span id="AdultsPlus'+n+'" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>\
                        <td></tr></table>\
                    </div>\
                </div>\
                <div class="col-6 col-md-2">\
                    <div class="form-group">\
                        <table><tr><td>\
                            <span id="ChildsMin'+n+'" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>\
                        </td><td>\
                            <input id="nChilds'+n+'" name="nChilds'+n+'" type="text" readonly min="0" max="10" class="form-control" value="0">\
                        </td><td>\
                            <span id="ChildsPlus'+n+'" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>\
                        <td></tr></table>\
                    </div>\
                </div>\
            </div>\
        </div>';

        $("#roomList").append(room);

        //Make Listener only if the listener dont exist , so if the rooms crated now coresponds to room created in total 
        if(roomTotalCreated==roomTotalCreatedNow){
            roomTotalCreatedNow++;
            //Adults
            $('#roomList').on('click','#AdultsMin'+n, function() {
                if($("#nAdults"+n).val()>1){ 
                    //console.log("Adults min");
                    var nAdults = $("#nAdults"+n).val()
                    $("#nAdults"+n).val(--nAdults);
                }
            });
            $('#roomList').on('click','#AdultsPlus'+n, function() {
                if($("#nAdults"+n).val()<30){ 
                    //console.log("Adults plus");
                    var nAdults = $("#nAdults"+n).val()
                    $("#nAdults"+n).val(++nAdults);
                }
            });
            //Children
            $('#roomList').on('click','#ChildsMin'+n, function() {
                if($("#nChilds"+n).val()>0){ 
                    var nAdults = $("#nChilds"+n).val()
                    $("#nChilds"+n).val(--nAdults);
                }
            });
            $('#roomList').on('click','#ChildsPlus'+n, function() {
                if($("#nChilds"+n).val()<30){ 
                    //console.log("Adults plus");
                    var nAdults = $("#nChilds"+n).val()
                    $("#nChilds"+n).val(++nAdults);
                }
            });

        }
    }
});

</script>