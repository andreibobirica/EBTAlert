
<?php
    $db = new Database();
    //Printing Scaling HTML VIEW To Desktop View
    print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");


    //Save The modify element
    if(isset($_POST["save"])){
        //print_r($_POST);
        //Risultato modifiche
        $return = true;

        //insert Img Upload
        //imgUpl
        include_once "./core/addImg.php";
        $imgLink = "";
        //print_r($_FILES);
        //echo "-";print_r($_FILES["imgUpl"]);echo "-";
        if(isset($_FILES["imgUpl"]) && $_FILES["imgUpl"]["error"]==0){
            $imgLink = insert("upload/","imgUpl",$_POST["Hname"],$_POST["Hid"]);
            if($imgLink==false){$return = false;}
        }
        //QUERY Modifica Hotel
        if($imgLink == "")
        $sql = "UPDATE hotel SET hotel.descr = '$_POST[descr]', hotel.name='$_POST[Hname]' WHERE hotel.id='$_POST[Hid]'";
        else
        $sql = "UPDATE hotel SET hotel.descr = '$_POST[descr]', hotel.name='$_POST[Hname]', hotel.img='$imgLink' WHERE hotel.id='$_POST[Hid]'";
        //echo $sql;
        if($db->query($sql)!=true){$return=false;}

        //QUERY Modifica hotel-detail
        $dis = isset($_POST['checkDis']) ? 1 : 0;
        $rest = isset($_POST['checkRest']) ? 1 : 0;
        $park = isset($_POST['checkPark']) ? 1 : 0;
        $wifi = isset($_POST['checkWifi']) ? 1 : 0;
        $break = isset($_POST['checkBreak']) ? 1 : 0;
        $privpark = isset($_POST['checkPrivPark']) ? 1 : 0;
        $animal = isset($_POST['checkAnimal']) ? 1 : 0;
        $sql = "UPDATE hotel_detail SET hotel_detail.stars='$_POST[nStars]', hotel_detail.dis='$dis', hotel_detail.rest='$rest', hotel_detail.park='$park', hotel_detail.wifi='$wifi', hotel_detail.break='$break', hotel_detail.privpark='$privpark', hotel_detail.animal='$animal' WHERE hotel_detail.hotel='$_POST[Hid]'";
        if($db->query($sql)!=true){$return=false;}

        //Query Location
        $sql = "UPDATE location SET location.street='$_POST[Hstreet]', location.mun='$_POST[Hmun]', location.state='$_POST[Hstate]', location.lati='$_POST[Hlati]', location.longi='$_POST[Hlongi]' WHERE location.id='$_POST[HlocationId]'";
        if($db->query($sql)!=true){$return=false;}

        if($return){
            //succesfully
            echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&hotel=$_POST[Hid]'>";
        }else{
            //Messaggio errore
        }
        exit;
    }//Delete the Hotel
    elseif(isset($_POST["delete"])){
        //delete
        $return = true;
        $sql = "DELETE FROM hotel WHERE hotel.id=$_POST[Hid]";
        if (!($db->query($sql) == TRUE)) {$return = false;}
        $sql = "DELETE FROM location WHERE location.id=$_POST[HlocationId]";
        if (!($db->query($sql) == TRUE)) {$return = false;}

        if($return){
            echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel'>";
        }else{
            //Messaggio errore
        }
        exit;
    }//Insert New Hotel
    elseif(isset($_POST["insert"])){
        //Insert Algorithms
        $return = true;
        //Insert Location
        $sql = "INSERT INTO location (lati, longi, street, mun, state)
        VALUES ('$_POST[Hlati]', '$_POST[Hlongi]', '$_POST[Hstreet]', '$_POST[Hmun]', '$_POST[Hstate]')";
        if (!($db->query($sql) === TRUE)){$return = false;}
    
        //Get location id
        //Geting ID location
        $query = "SELECT LAST_INSERT_ID() AS id;";
        $result = $db->query($query);
        $locationId = "";
        if ($result->num_rows == 1) {
            $locationId = $result->fetch_assoc()["id"];
        }
        
    
        //insert Img Upload
        //imgUpl
        include_once "./core/addImg.php";
        $imgLink = "";
        if(isset($_FILES["imgUpl"]) && $_FILES["imgUpl"]["error"]==0){
            $imgLink = insert("upload/","imgUpl",$_POST["Hname"],$_POST["Hid"]);
            if($imgLink==false){$return = false;}
        }
        //Insert Hotel
        if($imgLink==""){
            $sql = "INSERT INTO hotel (descr,name, location, hotelchain)
        VALUES ('$_POST[descr]','$_POST[Hname]', '$locationId', '$_POST[HCid]')";
        }else{
            $sql = "INSERT INTO hotel (descr,name, location, hotelchain, img)
            VALUES ('$_POST[descr]','$_POST[Hname]', '$locationId', '$_POST[HCid]', '$imgLink')";
        }
        if (!($db->query($sql) === TRUE)){$return = false;}
    
        //Geting Info About Room_detail
        $query = "SELECT LAST_INSERT_ID() AS id;";
        $result = $db->query($query);
        $hotelId = "";
        if ($result->num_rows == 1) {
            $hotelId = $result->fetch_assoc()["id"];
        }
    
        //Insert Hotel Detail
        $dis = isset($_POST['checkDis']) ? 1 : 0;
        $rest = isset($_POST['checkRest']) ? 1 : 0;
        $park = isset($_POST['checkPark']) ? 1 : 0;
        $wifi = isset($_POST['checkWifi']) ? 1 : 0;
        $break = isset($_POST['checkBreak']) ? 1 : 0;
        $privpark = isset($_POST['checkPrivPark']) ? 1 : 0;
        $animal = isset($_POST['checkAnimal']) ? 1 : 0;
    
        $sql = "INSERT INTO hotel_detail (hotel, stars, dis, rest, park, wifi, break, privpark, animal)
        VALUES ('$hotelId', '$_POST[nStars]', '$dis', '$rest', '$park', '$wifi', '$break', '$privpark', '$animal')";
        if (!($db->query($sql) === TRUE)){$return = false;}

        if($return){
            echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&hotel=$hotelId'>";
        }
        exit;
    }

    //Hotel Proper form info standard
    $iHid=$iHLocation=$iHname=$iHimg=$iHstreet=$iHmun=$iHstate=$iHlati=$iHlongi=$iHlocationId=$iHDescr="";
    $iHstars=1;
    $HDetail = array(
        "rest" => false,
        "park" => false,
        "wifi" => false,
        "break" => false,
        "privpark" => false,
        "pets" => false,
        "dis" => false,
        "animal" => false
    );

    //Algoritm To get Info Hotel to Modify
    if(isset($_GET["ModHotel"])){
        //Geting Detail of the hotel
        $query = "SELECT * FROM hotel_detail WHERE hotel_detail.hotel =".$_GET["ModHotel"];
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $ret = true;
            $row = $result->fetch_assoc();
            $HDetail = $row;
        }
        $iHstars=$HDetail["stars"];
        //Geting Info of the hotel
        $query = "SELECT * FROM hotel WHERE hotel.id =".$_GET["ModHotel"];
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $ret = true;
            $hotel = $result->fetch_assoc();
        }
        $iHDescr = $hotel["descr"];
        $iHid = $hotel["id"];
        $iHimg = $hotel["img"];
        $iHname = $hotel["name"];
        //Geting Location
        $query = "SELECT * FROM location WHERE location.id =".$hotel["location"]."";
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $HLocation = $row;
        }
        //$iHLocation=$HLocation;
        $iHstreet=$HLocation["street"];
        $iHmun=$HLocation["mun"];
        $iHstate=$HLocation["state"];
        $iHlati=$HLocation["lati"];
        $iHlongi=$HLocation["longi"];
        $iHlocationId=$HLocation["id"];
    }

?>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="panel container">
        <div class="row">
            <div class="card-info card bg-dark text-white col-lg-5">
                <div class="card-body">
                <input name="Hid" style="display: none;" readonly value="<?php echo $iHid; ?>"/>
                <input name="HlocationId" style="display: none;" readonly value="<?php echo $iHlocationId; ?>"/>
                <input name="HCid" style="display: none;" readonly value="<?php echo $_GET["hc"]; ?>"/>
                    <h3><small class="lead">Modify Hotel</small></h3>
                    <p class="card-text">Name:<br/> 
                    <?php 
                        echo "<input  maxlength='75' id='Hname' name='Hname' class='form-control form-control-sm' value='$iHname' type='text'>";
                    ?> 
                    <p>Image: <input type="file" class="btn btn-secondary" name="imgUpl" id="imgUpl"></p>
                    </p><hr/>
                    <p>Description:
                    <textarea name="descr" class="form-control" rows="3"><?php echo $iHDescr; ?></textarea></p>
                    <hr/>
                    <p>Address:</p>
                    <p>Street<input maxlength='75' id='Hstreet' name='Hstreet' class='form-control form-control-sm col' value='<?php echo $iHstreet; ?>' type='text'  ></p>
                    <div class="row">
                        <div class="col-md">
                            <p>Mun<input maxlength='75' id='Hmun'  name='Hmun' class='form-control form-control-sm col' value='<?php echo $iHmun; ?>' type='text'  ></p>
                        </div>
                        <div class="col-md">
                            <p>State<input maxlength='75' id='Hstate' name='Hstate' class='form-control form-control-sm col' value='<?php echo $iHstate; ?>' type='text'  ></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <p>lati:<input maxlength='75' id='Hlati'  name='Hlati' class='form-control form-control-sm col' value='<?php echo $iHlati; ?>' type='text'  ></p>
                        </div>
                        <div class="col-md">
                            <p>longi:<input maxlength='75' id='Hlongi' name='Hlongi' class='form-control form-control-sm col' value='<?php echo $iHlongi; ?>' type='text'  ></p>
                        </div>
                    </div>
                    <div class="row">
                        <div id='map' style='width: 100%; height:18rem'></div>
                    </div>
                </div>
            </div>
            <div class="card bg-dark text-white col-lg-5 offset-lg-2">
                <div class="card-body">
                    <h5 class="card-title">Hotels Detail</h5>
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group">
                                    <span class='lead'>Stars</span>  
                                    <table><tr><td>
                                        <span id="StarsMin" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                    </td><td>
                                        <input id="nStars" name="nStars" type="text" readonly min="1" max="5" class="form-control" value="<?php echo $iHstars; ?>">
                                    </td><td>
                                        <span id="StarsPlus" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                    <td></tr></table>
                                </div>
                            </div>
                            <div class="col-5">
                                <?php
                                    //Restaurant
                                    echo "<span class='lead'>Restaurant</span><br/>";
                                    echo '<i class="fa fa-cutlery fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                    <input class="check" id="checkRest" name="checkRest" <?php if($HDetail['rest']){echo "checked";}?> type="checkbox" />
                                    <label class="check" for="checkRest"></label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col">
                                <?php
                                    //Parking
                                    echo "<span class='lead'>Park</span><br/>";
                                    echo '<i class="fa fa-car fa-2x fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                    <input class="check" id="checkPark" name="checkPark" <?php if($HDetail['park']){echo "checked";}?> type="checkbox" />
                                    <label class="check" for="checkPark"></label>
                                </div>
                            </div>
                            <div class="col">
                                <?php
                                    //Wifi
                                    echo "<span class='lead'>Wifi</span><br/>";
                                    echo '<i class="fa fa-wifi fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                    <input class="check" id="checkWifi" name="checkWifi" <?php if($HDetail['wifi']){echo "checked";}?> type="checkbox" />
                                    <label class="check" for="checkWifi"></label>
                                </div>
                            </div>
                            <div class="col">
                                <?php
                                    //Breakfast
                                    echo "<span class='lead'>Breakfast</span><br/>";
                                    echo '<i class="fa fa-coffee fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                        <input class="check" id="checkBreak" name="checkBreak" <?php if($HDetail['break']){echo "checked";}?> type="checkbox" />
                                        <label class="check" for="checkBreak"></label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col">
                                <?php
                                    //Private Park
                                    echo "<span class='lead'>PrivPark</span><br/>";
                                    echo '<i class="fa fa-car fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                    <input class="check" id="checkPrivPark" name="checkPrivPark" <?php if($HDetail['privpark']){echo "checked";}?> type="checkbox" />
                                    <label class="check" for="checkPrivPark"></label>
                                </div>
                            </div>
                            <div class="col">
                                <?php
                                    //Pets
                                    echo "<span class='lead'>Pets</span><br/>";
                                    echo '<i class="fa fa-paw fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                    <input class="check" id="checkAnimal" name="checkAnimal" <?php if($HDetail['animal']){echo "checked";}?> type="checkbox" />
                                    <label class="check" for="checkAnimal"></label>
                                </div>
                            </div>
                            <div class="col">
                                <?php
                                    //Disability
                                    echo "<span class='lead'>Disab</span><br/>";
                                    echo '<i class="fa fa-wheelchair fa-2x" aria-hidden="true"></i>';
                                ?>
                                <div style="display: inline;"> 
                                    <input class="check" id="checkDis" name="checkDis" <?php if($HDetail['dis']){echo "checked";}?> type="checkbox" />
                                    <label class="check" for="checkDis"></label>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <?php
                            if(isset($_GET["ModHotel"])):
                            ?>
                                <div class="col">
                                <a type="button" href="./index.php?controlpanel&hotel=<?php echo $iHid; ?>" class="btn btn-secondary btn-block">Cancel</a>
                                <button type="submit" name="delete" class="btn btn-danger btn-block">Delete</button>
                                </div>
                                <div class="col">
                                    <button type="submit" name="save" class="btn btn-success btn-block">Save</button>
                                </div>
                            <?php
                            elseif(isset($_GET["InsHotel"])):
                            ?>
                                    <div class="col">
                                        <a type="button" href="./index.php?controlpanel" class="btn btn-secondary btn-block">Cancel</a>
                                    </div>
                                    <div class="col">
                                        <button type="submit" name="insert" class="btn btn-warning btn-block">Insert</button>
                                    </div>
                            <?php
                            endif;
                            ?>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $( document ).ready(function() {
            //Modify padding to fit verticaly centered
            $('.panel').css('padding-bottom', 150 + 'px');
            
            //+ and - button for adding nre stars
               //Script minus, plus 
            var nStars = $("#nStars").val();
            //Adults
            $("#StarsMin").click(function() { 
                if(nStars>1){ 
                    $("#nStars").val(--nStars);
                }
            });
            $("#StarsPlus").click(function() {
                if(nStars<5){ 
                    $("#nStars").val(++nStars);
                }
            });


            //Open Layers Map
            const map = new ol.Map({
                view: new ol.View({
                center: [0,0],
                zoom: 2,
                projection: ""
                }),
                layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
                ],
                target: 'map'
            })

            var modLocation = function (evt) {
                var coord = ol.proj.toLonLat(evt.coordinate);
                console.log(coord);
                reverseGeocode(coord);
                function reverseGeocode(coords) {
                fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + coords[0] + '&lat=' + coords[1])
                    .then(function(response) {
                            return response.json();
                        }).then(function(json) {
                            $("#Hlati").val(json['lat']);
                            $("#Hlongi").val(json['lon']);
                            var location = json["display_name"].split(",")
                            var lunLocation = location.length;
                            $("#Hstate").val(location[location.length-1])
                            $("#Hstreet").val("");
                            $("#Hmun").val("");
                            for (let i = 0; i < lunLocation-1; i++) {
                                if(i<lunLocation/2){
                                    $("#Hstreet").val($("#Hstreet").val()+""+location[i]);
                                }else{
                                    $("#Hmun").val($("#Hmun").val()+""+location[i]);
                                }
                            }
                            //Control legth new Strings
                            if($("#Hstreet").val().length>75){
                                $("#Hstreet").val($("#Hstreet").val().substring(0, 75));
                            }
                            if($("#Hstate").val().length>75){
                                $("#Hstate").val($("#Hstate").val().substring(0, 75));
                            }
                            if($("#Hmun").val().length>75){
                                $("#Hmun").val($("#Hmun").val().substring(0, 75));
                            }
                        });
                }
            }
            map.on('click', modLocation);
            map.on('ontouchstart', modLocation);

    });       
    </script>
</form>



