<?php
//GIVE DATA IF FORNITE BY GET PARAMETER IN ALERT
if(isset($_GET["nRooms"])){
    $_POST["nRooms"]=$_GET["nRooms"];
    for($i = 1; $i <= $_POST["nRooms"];$i++){
        $_POST["nAdults".$i] = $_GET["nAdults".$i];
        $_POST["nChilds".$i] = $_GET["nChilds".$i];
    }
    $_POST["locationId"] = $_GET["locationId"];
    $_POST["check-in"] = $_GET["check-in"];
    $_POST["check-out"] = $_GET["check-out"];
    $_POST["lng"] = $_GET["lng"];
    $_POST["lat"] = $_GET["lat"];
}

//Calculate for each camera person , and total
if(!isset($_POST["nRooms"])){
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    exit();
}

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
        array_push($search['roomsPerson'],$_POST["nAdults".$i]+$_POST["nChilds".$i]);
        $search['totPeop']+=$_POST["nAdults".$i]+$_POST["nChilds".$i];
    }
}

//Saving search propertis
$search['location'] = $_POST["locationId"];
$search['checkin']  = $_POST["check-in"];
$search['checkout']  = $_POST["check-out"];
$search['nRooms']  = $_POST["nRooms"];

//Coord
$search['lat']  = $_POST["lat"];
$search['lng']  = $_POST["lng"];
?>

    <div class="panel container-fluid">
        <div class='row'>
            <?php
    $search["hc"] = false;
    if(isset($_POST["hc"])){
        $search["hc"] =  $_POST["hc"];
    }

    if($search["hc"] != false){
        echo "<div class='col'> <p class='display-4 text-white float-right'>$_POST[hcname]</p></div>";
    }?>
        </div>
        <div class="row">
            <div id="formBookFilter" class="col-lg-2">
                <div id="showMenu" class="row d-block d-lg-none" style="margin-top: -35px ">
                    <button id="showFormBook" class="col">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                            <input id="locationIdBtn" type="text" readonly class="form-control">
                        </div>
                        <p>
                            <?php echo $search['checkin']." <i class='fa fa-arrows-h' aria-hidden='true'></i> ".$search['checkout']." | <i class='fa fa-user' aria-hidden='true'></i>".$search['totPeop'];?></p>
                    </button>
                </div>
                <div id="formBook" class="d-none d-lg-block card" style="margin-top: -35px">
                    <form id='formSearch' method='POST' action='./index.php?alert' class='card-body'>
                        <div>
                            <div class="form-group">
                                <label for="locationId" class="bmd-label-floating">Destination</label>
                                <input id="locationId" name="locationId" type="text" class="form-control">
                                <input id="formLocation" style="display: none;" readonly value="<?php if(isset($search['location'])){echo $search['location'];} ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-12">
                                <div class="form-group">
                                    <label for="check-in" class="label">check-in</label>
                                    <input id="check-in" name="check-in" type="date" class="form-control">
                                    <input id="formCheckin" style="display: none;" readonly value="<?php if(isset($search['checkin'])){echo $search['checkin'];} ?>" />
                                </div>
                            </div>
                            <div class="col-6 col-md-12">
                                <div class="form-group">
                                    <label for="check-out" class="label">check-out</label>
                                    <input id="check-out" name="check-out" type="date" class="form-control">
                                    <input id="formCheckout" style="display: none;" readonly value="<?php if(isset($search['checkout'])){echo $search['checkout'];} ?>" />
                                </div>
                            </div>
                            <div class="col-6 col-md-12">
                                <label for="nRooms" class="label">Rooms</label>
                                <table>
                                    <tr>
                                        <td>
                                            <span id="RoomsMin" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                        </td>
                                        <td>
                                            <input id="nRooms" name="nRooms" type="text" readonly min="1" max="30" class="form-control" value="<?php echo $search['nRooms']; ?>">
                                        </td>
                                        <td>
                                            <span id="RoomsPlus" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                            <td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-6 col-md-6'>
                                <p class="text-center">n. Adults</p>
                            </div>
                            <div class='col-6 col-md-6'>
                                <p class="text-center">n. Children</p>
                            </div>
                            <div id="roomList">
                                <?php
                            foreach($search['roomsPerson'] as $key => $roomPers){
                                $key+=1;
                                print("<div id='room$key'>
                                    <!--<hr/>-->
                                    <div class='row'>
                                        <div class='col-6 col-md-6 row'>
                                        <a href='#' class='badge badge-success'>ROOM $key</a>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-6 col-md-6'>
                                        <input id='nAdults$key' name='nAdults$key' type='number' min='1' max='30' class='form-control n' value='".$_POST["nAdults".$key]."'>
                                        </div>
                                        <div class='col-6 col-md-6'>
                                        <input id='nChilds$key' name='nChilds$key' type='number' min='0' max='30' class='form-control n' value='".$_POST["nChilds".$key]."'>
                                        </div>
                                    </div>
                                </div>");
                            }
                            ?>
                            </div>
                        </div>
                        <button type="button" id="btnSubmitFormBook" class="btn btn-raised btn-success float-right">Search</button>
                    </form>
                </div>
            </div>

            <div id="noResults" class="col-lg-10 card bg-dark text-white">
                <h1 class="display-4">Sorry, No Results was found</h1>
            </div>

            <div id="yesResults" class="col-lg-10 card bg-dark text-white">
                <div id="resList" class="row outer"></div>
            </div>

            <form action='./index.php?alert' method='POST' id='oneHotelAlert'><input type='hidden' name='oneHotel' id='oneHotel'/><input type='hidden' name='oneHotelName' id='oneHotelName'/></form>

            <!-- Modal MAp-->
            <div class="modal fade" id="modalMap" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog fpd" role="document">
                    <div class="modal-content bg-warning fpc">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Results Maps</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="mapContainer">
                            <div id="mapResults"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HOTEL-->
    <form action='./index.php?book=1' method='GET'>
        <input name="checkinForm" id="checkinForm" style="display: none;" readonly value="<?php echo $search['checkin'];?>" />
        <input name="checkoutForm" id="checkoutForm" style="display: none;" readonly value="<?php echo $search['checkout'];?>" />
        <input name="nRoomsForm" id="nRoomsForm" style="display: none;" readonly/>
        <div class="modal fade" id="modalHotel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title float-left" id="hotelName">Nome Hotel</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="lead" id="hotelDesc">
                            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus.
                        </p>
                        <p><em id="hotelAddress">This line rendered as italicized text.</em></p>
                        <div id="roomsList">
                        </div>
                    </div>
                    <div class="modal-footer row">
                        <div class="col-md-2 float-left">
                            <p class="lead" id="priceTot">Total € 0</p>
                        </div>
                        <div class="col float-right ">
                            <button type="button" class="btn btn-default offset-md-9" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">BOOK</button>
                        </div>
                    </div>
                    <button type="submit" form='oneHotelAlert' id='createAlertHotel' class="btn btn-warning">Create Alert</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Filter-->
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content bg-success text-white">
                <div class="modal-header">
                    <h4 class="modal-title float-left" id="hotelName">Filter Hotel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body ">
                    <form id='formFilter' class='card-body bg-dark text-white row'>
                    <div class="row">
                        <div class='col-12'>
                            <div class="form-group">
                                <label class='lead' for="formControlRange">Min Price € 
                                    <div style='display:inline' id="minP"><?php if(isset($_GET["priceMin"])){$d=$_GET["priceMin"];}else{$d=0;}echo $d;?></div>
                                </label>
                                <input type="range" name="minPInput" id="minPInput" value="<?php if(isset($_GET["priceMin"])){$d=$_GET["priceMin"];}else{$d=0;}echo $d;?>" min="0" max="1500" step="5" id="formControlRange">
                            </div>
                        </div>
                        <div class='col-12'>
                            <div class="form-group">
                                <label class='lead' for="formControlRange">Max Price €
                                    <div style='display:inline' id="maxP"><?php if(isset($_GET["priceMax"])){$d=$_GET["priceMax"];}else{$d=1500;}echo $d;?></div>
                                </label>
                                <input type="range" name="maxPInput" id="maxPInput" value="<?php if(isset($_GET["priceMax"])){$d=$_GET["priceMax"];}else{$d=1500;}echo $d;?>" min="0" max="1500" step="5" id="formControlRange">
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
                            <div id="starContainer"></div>
                            <p class="lead">Hotel Stars: </p>
                            <br/>
                            <div class="rating">
                                <span><input type="radio" name="star" id="str5" value="5"><label for="str5"></label></span>
                                <span><input type="radio" name="star" id="str4" value="4"><label for="str4"></label></span>
                                <span><input type="radio" name="star" id="str3" value="3"><label for="str3"></label></span>
                                <span><input type="radio" name="star" id="str2" value="2"><label for="str2"></label></span>
                                <span><input type="radio" name="star" id="str1" value="1"><label for="str1"></label></span>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    // Check Radio-box
                                    $(".rating input:radio").attr("checked", false);

                                    $('.rating input').click(function() {
                                        $(".rating span").removeClass('checked');
                                        $(this).parent().addClass('checked');
                                    });
                                    $('input:radio').change(
                                        function() {
                                            var userRating = this.value;
                                            $("#starContainer").html("<input  style='display: none;' name='star" + userRating + "' value='on' readonly/>");
                                        });
                                });
                            </script>
                        </div>
                        <div class="row">
                            <div class="col foo border">
                                <input class="check" id="checkRest" name="checkRest" type="checkbox" <?php if(isset($_GET["rest"])&&$_GET["rest"]){echo "checked";}?>/>
                                <label class="check" for="checkRest"></label>
                                <i class="fa fa-cutlery fa-1x" aria-hidden="true"><p class="miniSmall">Restaurant</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkPark" name="checkPark" type="checkbox" <?php if(isset($_GET["park"])&&$_GET["park"]){echo "checked";}?>/>
                                <label class="check" for="checkPark"></label>
                                <i class="fa fa-car fa-1x" aria-hidden="true"><p class="miniSmall">Park</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkWifi" name="checkWifi" type="checkbox" <?php if(isset($_GET["wifi"])&&$_GET["wifi"]){echo "checked";}?>/>
                                <label class="check" for="checkWifi"></label>
                                <i class="fa fa-wifi fa-1x" aria-hidden="true"><p class="miniSmall">Wifi</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkBreak" name="checkBreak" type="checkbox" <?php if(isset($_GET["break"])&&$_GET["break"]){echo "checked";}?>/>
                                <label class="check" for="checkBreak"></label>
                                <i class="fa fa-coffee fa-1x" aria-hidden="true"><p class="miniSmall">Breakfast</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkPrivPark" name="checkPrivPark" type="checkbox" <?php if(isset($_GET["break"])&&$_GET["break"]){echo "checked";}?>/>
                                <label class="check" for="checkPrivPark"></label>
                                <i class="fa fa-car fa-1x" aria-hidden="true"><p class="miniSmall">Private Park</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkPets" name="checkPets" type="checkbox"  <?php if(isset($_GET["pets"])&&$_GET["pets"]){echo "checked";}?>/>
                                <label class="check" for="checkPets"></label>
                                <i class="fa fa-paw fa-1x" aria-hidden="true"><p class="miniSmall">Pets</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkDisab" name="checkDisab" type="checkbox" <?php if(isset($_GET["disab"])&&$_GET["disab"]){echo "checked";}?>/>
                                <label class="check" for="checkDisab"></label>
                                <i class="fa fa-wheelchair fa-1x" aria-hidden="true"><p class="miniSmall">Disable</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkAc" name="checkAc" type="checkbox"  <?php if(isset($_GET["ac"])&&$_GET["ac"]){echo "checked";}?>/>
                                <label class="check" for="checkAc"></label>
                                <p class="miniSmall">AC</p>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkPrivBath" name="checkPrivBath" type="checkbox" <?php if(isset($_GET["privbath"])&&$_GET["privbath"]){echo "checked";}?>/>
                                <label class="check" for="checkPrivBath"></label>
                                <i class="fa fa-user fa-1x" aria-hidden="true"><p class="miniSmall">Private Bathroom</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkTv" name="checkTv" type="checkbox" <?php if(isset($_GET["tv"])&&$_GET["tv"]){echo "checked";}?>/>
                                <label class="check" for="checkTv"></label>
                                <p>TV</p>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkMiniBar" name="checkMiniBar" type="checkbox" <?php if(isset($_GET["minibar"])&&$_GET["minibar"]){echo "checked";}?>/>
                                <label class="check" for="checkMiniBar"></label>
                                <i class="fa fa-glass fa-1x" aria-hidden="true"><p class="miniSmall"><p>Mini Bar</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkSafe" name="checkSafe" type="checkbox" <?php if(isset($_GET["safe"])&&$_GET["safe"]){echo "checked";}?>/>
                                <label class="check" for="checkSafe"></label>
                                <i class="fa fa-life-ring fa-1x" aria-hidden="true"><p class="miniSmall"><p>Safe</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkTeCaffe" name="checkTeCaffe" type="checkbox" <?php if(isset($_GET["tecaffe"])&&$_GET["tecaffe"]){echo "checked";}?>/>
                                <label class="check" for="checkTeCaffe"></label>
                                <i class="fa fa-coffee fa-1x" aria-hidden="true"><p class="miniSmall"><p>The/Coffee</p></i>
                            </div>
                            <div class="col foo border">
                                <input class="check" id="checkPhone" name="checkPhone" type="checkbox" <?php if(isset($_GET["phone"])&&$_GET["phone"]){echo "checked";}?>/>
                                <label class="check" for="checkPhone"></label>
                                <i class="fa fa-phone fa-1x" aria-hidden="true"><p class="miniSmall"><p>Phone</p></i>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer text-white">
                    <button type="button" class="btn btn-default text-white" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#container-footer").append('\
                <div class="Bookfooter">\
                <!-- Button trigger modal -->\
                    <div class="row">\
                        <div class="col">\
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalMap">\
                            Show <br/>On Map\
                        </button>\
                        </div>\
                        <div class="col">\
                        <button class="btn btn-success float-center" type="button" data-toggle="modal" data-target="#modalFilter">\
                            Filter<br/>Resultats\
                        </button>\
                        </div>\
                        <div class="col">\
                        <button name="createAlert" id="createAlert" form="formSearch" class="btn btn-danger" type="submit">\
                            Create<br/>Alert\
                        </button>\
                        </div>\
                    </div>\
                </div>\
        ');
        });
    </script>

    <script>
        $(document).ready(function() {
            //Modify padding to fit verticaly centered
            $('.panel').css('padding-bottom', 150 + 'px');

            //Hide all waiting for results
            $("#noResults").hide();
            $("#yesResults").hide();

            //Geting Data From PHP
            var searchData = [];
            //Result Data
            var resultData = [];

            //HC 
            var hc = null;
            <?php print("hc = '$search[hc]';");?>

            //Filter

            var filter = null;

            <?php echo "searchData = JSON.parse('".json_encode($search)."');";?>

            //Listener to create alert on an hotel of the result
            $("body").on("click","#createAlertHotel",function(){
                console.log("crete alert");
                event.preventDefault();
                console.log($("#formSearch")[0]);
                var dataSend = new FormData($("#formSearch")[0]);
                console.log(dataSend);
                // Display the key/value pairs
                for(var pair of dataSend.entries()) {
                    $("#oneHotelAlert").append('<input type="hidden" name="'+pair[0]+'" value="'+pair[1]+'" /> ');
                }
                $('#oneHotelAlert').submit();
            });


            //Make ajax call to get Data results
            getData(searchData <?php print(",null,$search[hc]");?>);

            //Get data with an ajax request to getResults.php
            //It needs parameter for search
            function getData(searchData, filterData = null, hc = null,hotelFilter=null) {
                $('#spinnerModal').modal('show');

                console.log(searchData);
                //Agiorno checkin checkout per il form del BOOk
                $("#checkinForm").val(searchData.checkin);
                $("#checkoutForm").val(searchData.checkout);

                $.ajax({
                    type: "POST",
                    url: "./comp/search/getResults.php",
                    data: {
                        search: searchData,
                        filter: filterData,
                        hotelFilter: hotelFilter,
                        hc: hc
                    },
                    success: function(optimalData) {
                        $('#spinnerModal').hide();
                        //This Are data
                        console.log(optimalData);
                        //Check If array is Empty Or not
                        if (optimalData === undefined || optimalData.length == 0) {
                            $("#noResults").show();
                            $("#yesResults").hide();
                        } else {
                            $("#noResults").hide();
                            $("#yesResults").show();
                            //save result data
                            resultData = optimalData;

                            //Show Map
                            showMap(optimalData);
                            //Show Data
                            showData(optimalData);

                        }
                    },
                    error: function() {
                        console.log("eror");
                        $("#noResults").show();
                        $("#yesResults").hide();
                        $('.modal-backdrop').remove();
                        $('#spinnerModal').remove();
                    },
                    dataType: "json"
                });
            }

            //Show Data Function That shoes the hotel in the page
            function showData(data) {
                //Get Another Data search
                var roomsNumber = $("#formRooms").val();
                var totpeop = searchData.totPeop;
                $("#resList").empty();
                //foreachhotel
                data.forEach(function(item, index) {
                    var rooms = item.info.rooms;
                    var min = 999999999999;
                    var indexMin = 0;
                    //foreachroom find a room with low costs
                    rooms.forEach(function(itemRoom, indexRoom) {
                        if (Number(itemRoom.pnr) < min) {

                            min = Number(itemRoom.pnr);
                            indexMin = indexRoom;

                        }
                    });
                    var roomEx = item.info.rooms[indexMin];
                    var stars = "";
                    for (var i = 0; i < 5; i++) {
                        if (i < roomEx.stars) {
                            stars += '<i class="fa fa-star" aria-hidden="true"></i>';
                        } else {
                            stars += '<i class="fa fa-star-o" aria-hidden="true"></i>';
                        }
                    }
                    var card = "\
                <div class='col-md inner'>\
                <button class='btn btnHotel' hotel='" + index + "' data-toggle='modal' data-target='#modalHotel'>\
                <div class='card bg-dark img-fluid ' style='width:18rem; height:12rem''>\
                    <img class='card-img-top' src='./" + roomEx.img + "' alt='Card image'>\
                    <div class='card-img-overlay'>\
                    <a class='btn btn-secondary float-left text-white' disabled><small class='miniSmall'>" + stars + "</small></a>\
                    <a class='btn btn-dark  btn-lg float-right text-white' disabled><small class='miniSmall'></small>" + roomEx.name + "</a>\
                    <a class='btn btn-outline-light btn-lg float-left prtxt' disabled><small class='miniSmall'>" + roomEx.nights + " nights, " + totpeop + " people</small><br/>" + (Math.round(roomEx.pnrTot * 100) / 100).toFixed(2) + " €<small class='miniSmall'></small></a>\
                    </div>\
                </div>\
                </button>\
                </div>";
                        $("#resList").append(card);
                    });

            }

            //Function that shows the map and re results on the map
            function showMap(data) {
                $("#mapContainer").html('<div id="mapResults"></div>');
                var map = L.map('mapResults').setView([data[0].info.rooms[0].lati, data[0].info.rooms[0].longi], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                data.forEach(function(item, index) {
                    item.info.rooms.forEach(function(room, i) {
                        L.marker([room.lati, room.longi]).bindPopup("<button data-target='#modalHotel' data-toggle='modal' class='btnHotel btn btn-sm' hotel='" + index + "'>" + room.name + "</button><small>" + (Math.round(room.pnr * 100) / 100).toFixed(2) + " €</small>").addTo(map);
                    });
                });

                //Listener For Adjust Map in Modal
                $('#modalMap').on('show.bs.modal', function() {
                    setTimeout(function() {
                        console.log("recalcule");
                        map.invalidateSize(true);
                    }, 400);
                });

            }

            //SUBMIT FORM SERACH ANOTHER TIME
            var location = $("#formLocation").val();
            var checkin = $("#formCheckin").val();
            var checkout = $("#formCheckout").val();
            $("#locationIdBtn").val(location);
            $("#locationId").val(location);
            $("#check-in").val(checkin);
            $("#check-out").val(checkout);

            //Function that gets the geocoordinates info toking it from search input
            //Add listener to the form

            $("#btnSubmitFormBook").click(function(event) {
                var location = $("#locationId").val();
                $.ajax({
                    url: "https://www.mapquestapi.com/geocoding/v1/address?key=VYuZMOZ9JCOPzk2Dx2eZB5Zu4Y9WDy4u&inFormat=kvp&outFormat=json&location=" + location + "&thumbMaps=false",
                    type: 'get',
                    dataType: 'text',
                    success: function(data) {
                        var coord = JSON.parse(data).results[0].locations[0].latLng;
                        //Get another time the data

                        var data = {
                            roomsPerson: [],
                            totPeop: 0,
                            location: "",
                            checkin: "",
                            checkout: "",
                            nRooms: "",
                            lat: "",
                            lng: ""
                        };
                        data.lat = coord.lat;
                        data.lng = coord.lng;
                        data.location = $("#locationId").val();
                        data.checkin = $("#check-in").val();
                        data.checkout = $("#check-out").val();
                        data.nRooms = $("#nRooms").val();

                        var sum = false;
                        var peopRoom = 0;
                        $.each($("#formSearch").serializeArray(), function(_, kv) {
                            if (kv.name.substring(0, 7) == "nAdults" || kv.name.substring(0, 7) == "nChilds") {
                                data.totPeop += Number(kv.value);
                                peopRoom += Number(kv.value);
                                if (sum) {
                                    data.roomsPerson.push(peopRoom);
                                    peopRoom = 0;
                                }
                                sum = !sum;
                            }
                        });
                        getData(data, filter, hc);
                        searchData = data;

                    },
                    error: function(data) {
                        //anulla
                    }
                });

            });

            //ShowMenuForm Listener
            $("#showFormBook").click(function() {
                $("#formBook").removeClass("d-none d-lg-block");
                $("#showMenu").empty();
            });

            //FILTER 
            var hotelFilter = [];
            <?php
                if(isset($_GET["hotels"])){
                    echo "hotelFilter=JSON.parse('$_GET[hotels]');";
                    echo 'filter = $("#formFilter").serialize();
                    getData(searchData, filter, hc,hotelFilter);';
                } 
            ?>
            $("#formFilter :input").change(function() {
                filter = $("#formFilter").serialize();
                getData(searchData, filter, hc);
                //$(this).closest('form').data('changed', true);
            });

            //SHOW detail Hotel

            $('body').on('click', '.btnHotel', function() {
                var id = $(this).attr("hotel");
                var exRoom = resultData[id]["info"]["rooms"][0];
                $("#hotelName").html(exRoom["name"]);
                $("#hotelDesc").html(exRoom["desc"]);

                $("#hotelAddress").html(exRoom["street"] + " | " + exRoom["mun"] + " | " + exRoom["state"]);
                $("#roomsList").html("");

                //FORM
                $("#nRoomsForm").val(resultData[id]["info"]["rooms"].length);
                resultData[id]["info"]["rooms"].forEach(function(item, index) {
                    $("#roomsList").append(getDetailRoom(item, index));
                });

                //Modify hotel input for one hotel alert
                console.log(exRoom);
                $("#oneHotel").val(exRoom["hotelid"]);
                $("#oneHotelName").val(exRoom["name"]);
            });

            //Function that return HTML detail data for each room in format of table and img 
            function getDetailRoom(roomData, index) {
                var ret = "<input name='roomId" + index + "' value='" + roomData.room + "' style='display: none;' readonly/>\
        <input name='PricePR" + roomData.room + "' value='" + roomData.pr + "' style='display: none;' readonly/>\
        <input name='PricePNR" + roomData.room + "' value='" + roomData.pnr + "' style='display: none;' readonly/>\
         <div class='card'><table class='table'>\
            <tr>\
            <th scope='col' rowspan='3'>Type<br/>";
                //NAME AND BED IMAGE
                ret += "<small>" + roomData.nameBea + "</small><br/><hr/>";
                //BED
                if (roomData.bedDouble > 0) {
                    ret += "<small>" + roomData.bedDouble + " Bed Double </small>"
                    for (var i = 0; i < roomData.bedDouble; i++) {
                        ret += '<img src="./img/bedDouble.png" width="20" height="20" />';
                    }
                }
                if (roomData.bedSingle > 0) {
                    ret += "<br/><small>" + roomData.bedSingle + " Bed Single </small>"
                    for (var i = 0; i < roomData.bedSingle; i++) {
                        ret += '<img src="./img/bedSingle.png" width="20" height="20" />';
                    }
                }
                if (roomData.sofa > 0) {
                    ret += "<br/><small>" + roomData.sofa + " Sofa </small>"
                    for (var i = 0; i < roomData.sofa; i++) {
                        ret += '<img src="./img/sofa.png" width="20" height="20" />';
                    }
                }
                ret += "</th>";

                ret += "<th>For</th>\
            <th scope='col'>Price</th>\
            <th scope='col'>N.</th>\
            </tr>\
            <tr>\
            <th scope='row' rowspan='2'>" + (2 * Number(roomData.bedDouble) + Number(roomData.bedSingle) + Number(roomData.sofa)) + "<small> People</small></th>";
                //Price No Rembursable
                ret += "<td><p>" + (Math.round(roomData.pnr * 100) / 100).toFixed(2) + " €</p><p class='extraSmall'>No Rembursable</p></td>\
            <td>";
                //SELECT FORM
                ret += "<div class='form-group'>\
                    <select class='form-control form-control-sm roomSelectPNR selec'  p='" + Number(roomData.pnr) + "' room='" + roomData.room + "' name='PNR" + roomData.room + "' id='roompnr" + roomData.room + "'>\
                    <option value='0' selected>-</option>";
                for (var i = 1; i <= roomData.dispMin; i++) {
                    ret += "<option value='" + i + "'>" + i + " - " + (Math.round(i * Number(roomData.pnr) * 100) / 100).toFixed(2) + "€</option>";
                }
                ret += "</select></div></td></tr>";

                ret += "<tr>";
                //Price Remburlsable
                ret += "<td><p>" + (Math.round(roomData.pr * 100) / 100).toFixed(2) + " €</p><p class='extraSmall'>Rembursable</p></td>\
            <td>";
                //SELECT FORM
                ret += "<div class='form-group'>\
                    <select class='form-control form-control-sm roomSelectPR selec'  p='" + Number(roomData.pr) + "' room='" + roomData.room + "' name='PR" + roomData.room + "' id='roompr" + roomData.room + "'>\
                    <option value='0' selected>-</option>";
                for (var i = 1; i <= roomData.dispMin; i++) {
                    ret += "<option value='" + i + "'>" + i + "  - " + (Math.round(i * Number(roomData.pr) * 100) / 100).toFixed(2) + "€</option>";
                }
                ret += "</select></div></td></tr></table>";
                ret += "<div class='scrollmenu' id='carousel" + roomData.room + "'></div></div>";
                getImageRoom(roomData.room);
                return ret;
            }

            function getImageRoom(roomid) {
                //Carousel
                var images = [];
                $.get("./comp/search/getImagesRoom.php?room=" + roomid, function(data, status) {
                    images = JSON.parse(data);
                    var imgData = "";

                    images.forEach(function(item, index) {
                        imgData += "<a class='modalBtn'><div style='width: auto; height: 15rem;' class='deleteImg'><img style='width:100%;height: 13rem;' data-toggle='modal' data-target='#myModal' src='./" + item + "' class='rounded modalBtn'></div></a>";
                    });

                    $("#carousel" + roomid).html(imgData);
                });
            }

            /*Listener roomselectror*/
            $('#roomsList').on('change', '.roomSelectPNR', function() {
                changeTotal()
                var allOptions = $("#roompr" + $(this).attr("room")).children();
                var lastOption = allOptions.last();
                allOptions.each(function() {
                    $(this).removeAttr("disabled");
                });
                for (var i = 0; i < $(this).val(); i++) {
                    lastOption.attr('disabled', 'disabled');;
                    lastOption = lastOption.prev();
                }
            });
            $('#roomsList').on('change', '.roomSelectPR', function() {
                changeTotal();
                var allOptions = $("#roompnr" + $(this).attr("room")).children();
                var lastOption = allOptions.last();
                allOptions.each(function() {
                    $(this).removeAttr("disabled");
                });
                for (var i = 0; i < $(this).val(); i++) {
                    lastOption.attr('disabled', 'disabled');;
                    lastOption = lastOption.prev();
                }
            });

            function changeTotal() {
                var precioTot = 0;
                $(".selec").each(function() {
                    var precio = $(this).attr("p");
                    precioTot += $(this).val() * precio;
                });
                $("#priceTot").html("Total €" + "<input name='totPrice' type='text' readonly class='form-control' size='3' value='" + (Math.round(precioTot * 100) / 100).toFixed(2) + "'>");
            }
        });
    </script>

    <script>
        //Script to increment rooms or not
        $(document).ready(function() {

            //Rooms
            var nRooms = <?php echo $search['nRooms'];?>;
            $("#RoomsMin").click(function() {
                if (nRooms > 1) {
                    //console.log("Childs min");
                    $("#nRooms").val(--nRooms);
                    removeRoom();
                }
            });
            $("#RoomsPlus").click(function() {
                if (nRooms < 30) {
                    //console.log("Rooms plus");
                    $("#nRooms").val(++nRooms);
                    addRoom($("#nRooms").val());
                }
            });

            function removeRoom(n) {
                $('#roomList').children().last().remove();
            }

            function addRoom(n) {
                var room = "<div id='room$key'>\
                        <!--<hr/>-->\
                        <div class='row'>\
                            <div class='col-6 col-md-6 row'>\
                            <a href='#' class='badge badge-success'>ROOM " + n + "</a>\
                            </div>\
                        </div>\
                        <div class='row'>\
                            <div class='col-6 col-md-6'>\
                            <input id='nAdults" + n + "' name='nAdults" + n + "' type='number' min='1' max='30' class='n form-control' value='1'>\
                            </div>\
                            <div class='col-6 col-md-6'>\
                            <input id='nAdults" + n + "' name='nChilds" + n + "' type='number' min='0' max='30' class='n form-control' value='0'>\
                            </div>\
                        </div>\
                    </div>";

                $("#roomList").append(room);
            }
        });
    </script>