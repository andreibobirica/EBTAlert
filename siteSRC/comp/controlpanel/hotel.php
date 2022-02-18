
<?php
    $db = new Database();
//Printing Scaling HTML VIEW To Desktop View
print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");


    //Delete reservation
    if(isset($_POST["delReservation"])){
        //Geting all the rooms of the booking
        $query = "SELECT room_type as rm, since, until, nRPNR , nRPR  FROM book INNER JOIN book_detail ON book_detail.book = book.id WHERE book.id = $_POST[res]";
        $result = $db->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                //reset displonibility to +1 decrementing book column from price_date by -1
                $query = "UPDATE price_date SET book = book - ($row[nRPNR]+$row[nRPR]) WHERE room = $row[rm] AND date >= '$row[since]' AND date < '$row[until]'";
                $db->query($query);
            }
        }

        //Delete booking
        $query = "DELETE FROM `book` WHERE `book`.`id` = $_POST[res]";
        $db->query($query);

        print("<meta http-equiv='refresh' content='0;url=#reservation'>");
    }
    
    //Geting Detail of the hotel
    $query = "SELECT * FROM hotel_detail WHERE hotel_detail.hotel =".$_GET["hotel"];
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $HDetail = $row;
    }
    //Geting Infoof the hotel
    $query = "SELECT * FROM hotel WHERE hotel.id =".$_GET["hotel"];
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $hotel = $result->fetch_assoc();
    }
    //Geting Location
    $query = "SELECT * FROM location WHERE location.id =".$hotel["location"]."";
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $HLocation = $row;
    }
    $addres = $HLocation["street"]." - ".$HLocation["mun"]." - ".$HLocation["state"];

    function getBookings($db){
        $return = "";
        $query = "SELECT user.name as name,user.surname as surname,since,until,resDate, book.id as id, tot FROM book JOIN hotel ON hotel.id=book.hotel JOIN account ON account.id=book.account JOIN user ON user.account=account.id WHERE hotel='$_GET[hotel]'";
        $result = $db->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $return .= "<tr><th scope='row'>$row[name] $row[surname]</th><td>$row[since]</td><td>$row[until]</td><td>";
                $query = "SELECT * FROM book_detail JOIN room_type ON room_type.id = book_detail.room_type WHERE book='$row[id]'";
                $r = $db->query($query);
                if ($r->num_rows > 0) {
                    while($ro = $r->fetch_assoc()){
                        if($ro["nRPR"])
                        $return .= $ro["nRPR"]." X ".$ro["nameBea"]."<p class='extraSmall'>Remborsable</p>";
                        if($ro["nRPNR"])
                        $return .= $ro["nRPNR"]." X ".$ro["nameBea"]."<p class='extraSmall'>No Remborsable</p>";
                    }
                }
                $return .= "</td><td>$row[resDate]</td><td>$row[id]</td><td>€ $row[tot]</td><td><form action='' method='POST'><input name='res' style='display: none;' readonly value='$row[id]'/><button name='delReservation' class='btn btn-danger'>X</button></form></td></tr>";

            }
        }

        return $return;
    }
?>

<div class="panel container">
    <div class="row">
        <div class="card-info card bg-dark text-white col-lg-5">
            <div class="card-body">
                <h3><small class="lead">View Hotel</small></h3>
                <small class="card-text">Here are the control panel of Hotel:<br/>
                <?php 
                    echo "<span class='lead'>'$hotel[name]'</span>"; 
                    echo "<br/>Hotel Id: <span class='lead'>$hotel[id]</span>";
                ?> 
                </small>
                <hr/><small class="card-text">Description:<br/>
                <?php 
                    echo "<p class='text-justify'>''$hotel[descr]''</p>"; 
                ?> 
                </small>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-8">
                        <p><small><?php echo $addres ?><br/><?php print("lat:".$HLocation["lati"]." , long:".$HLocation["longi"]); ?></small></p>
                    </div>
                    <div class="col-4">
                    <img id="Himg" src="<?php if(!empty($hotel["img"])){echo $hotel["img"];}else{echo "https://robohash.org/$hotel[id]";}?>" style="width: 5rem; height: 5rem;" class="rounded-circle float-right ph">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div id='map' lat='<?php echo$HLocation["lati"];?>' lng='<?php echo $HLocation["longi"]?>' style='width: 100%; height:18rem'></div>
                </div>
            </div>
        </div>
    
        <div class="card bg-dark text-white col-lg-5 offset-lg-2">
            <div class="card-body">
                <h5 class="card-title lead">Hotels Detail</h5>
                    <div class="row">
                        <div class="col">
                            <?php
                                //Stars 
                                echo "<span class='lead'>Stars</span><br/>";
                                
                                for($i = 0; $i < 5; $i++){
                                    if($i<$HDetail["stars"])
                                    echo '<i class="fa fa-star" aria-hidden="true"></i>';
                                    else
                                    echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                //Restaurant
                                echo "<span class='lead'>Restaurant</span><br/>";
                                echo '<i class="fa fa-cutlery fa-2x" aria-hidden="true"></i>';
                                if($HDetail["rest"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col">
                            <?php
                                //Parking
                                echo "<span class='lead'>Park</span><br/>";
                                echo '<i class="fa fa-car fa-2x fa-2x" aria-hidden="true"></i>';
                                if($HDetail["park"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                //Wifi
                                echo "<span class='lead'>Wifi</span><br/>";
                                echo '<i class="fa fa-wifi fa-2x" aria-hidden="true"></i>';
                                if($HDetail["wifi"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                //Breakfast
                                echo "<span class='lead'>Breakfast</span><br/>";
                                echo '<i class="fa fa-coffee fa-2x" aria-hidden="true"></i>';
                                if($HDetail["break"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col">
                            <?php
                                //Private Park
                                echo "<span class='lead'><smal>Priv</smal>Park</span><br/>";
                                echo '<i class="fa fa-car fa-2x" aria-hidden="true"></i>';
                                if($HDetail["privpark"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                //Pets
                                echo "<span class='lead'>Pets</span><br/>";
                                echo '<i class="fa fa-paw fa-2x" aria-hidden="true"></i>';
                                if($HDetail["animal"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                        <div class="col">
                            <?php
                                //Disability
                                echo "<span class='lead'>Disab</span><br/>";
                                echo '<i class="fa fa-wheelchair fa-2x" aria-hidden="true"></i>';
                                if($HDetail["dis"]==1){
                                    echo '<i class="fa fa-check fa-2x" aria-hidden="true"></i>';
                                }else{
                                    echo'<i class="fa fa-times fa-2x" aria-hidden="true"></i>';
                                }
                            ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col">
                            <a type="button" href="./index.php?controlpanel" class="btn btn-secondary btn-block">Back</a>
                            <a type="button" href="index.php?controlpanel&ModHotel=<?php echo $_GET["hotel"];?>" class="btn btn-warning btn-block">Modify</a>
                        </div>
                        <div class="col">
                            <a type="button" href="index.php?controlpanel&rooms=<?php echo $_GET["hotel"]; ?>" class="btn btn-success btn-block">Show Rooms</a>
                            <a type="button" href="index.php?controlpanel&ViewDisp=<?php echo $_GET["hotel"]; ?>" class="btn btn-primary btn-block">Show Availability</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        <div class="card bg-dark text-white col-lg-12" id='reservation'>
            Reservation
            <table class="table table-striped table-dark">
            <thead>
                <tr>
                <th scope="col">Guest</th>
                <th scope="col">check-in</th>
                <th scope="col">check-out</th>
                <th scope="col">Rooms</th>
                <th scope="col">Reservation Date</th>
                <th scope="col">Res. Id.</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php echo getBookings($db);?>
            </tbody>
            </table>
        </div>
    </div>



<script>
    $( document ).ready(function() {
        //Modify padding to fit verticaly centered
        $('.panel').css('padding-bottom', 150 + 'px');

        //Open Layers Map
        var lng = $("#map").attr("lng");
        var lat = $("#map").attr("lat");
        console.log([lat,lng])
        var map = L.map('map').setView([lat,lng], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lng]).bindPopup("<?php echo $hotel["name"];?>").addTo(map);
    });
            
</script>