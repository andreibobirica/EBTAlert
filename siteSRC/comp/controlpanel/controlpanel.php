
<?php
    $db = new Database();
//Printing Scaling HTML VIEW To Desktop View
print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");


    //Verificare se per questo Id è un Prop
    $query = "SELECT * FROM prop WHERE prop.account =".$_SESSION["loginAccount"];
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $ret = true;
        $row = $result->fetch_assoc();
        $prop = $row;
    }
    //Geting Location
    $query = "SELECT * FROM location WHERE location.id =".$row["locationProp"]."";
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $HCLocation = $row;
    }
    $addres = $HCLocation["street"]." ,".$HCLocation["mun"]." - ".$HCLocation["state"];

    //Prendiamo Info della Hotel Chain
    $query = "SELECT * FROM hotelchain WHERE hotelchain.prop = ".$_SESSION["loginAccount"]."";
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $HC = $row;
    }
    //Prendiamo Info di Tutti Gli Hotel
    $query = "SELECT * FROM hotel_detail JOIN hotel WHERE hotel.hotelchain = $HC[id] AND hotel_detail.hotel = hotel.id";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $Hinfo = array();
        while($row = $result->fetch_assoc()){
            array_push($Hinfo,$row);
        }
        
    }
    


?>


<div class="panel container">
    <div class="row">
    <div class="card-info card bg-dark text-white col-lg-5">
        <div class="card-body">
            <h3><small class="lead">Welcome</small> <?php echo $_SESSION["nameAccount"];?></h3>
            <small class="card-text">Here are the control panel of Hotel Chain:<br/>
            <?php 
                echo "<span class='lead'>'$HC[name]'</span>"; 
                echo "<br/>Company: <br/><span class='lead'>$prop[company]</span>";
            ?> 
            </small>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-9">
                    <p><small><?php echo $addres ?><br/><?php print("lat:".$HCLocation["lati"]." , long:".$HCLocation["longi"]); ?></small></p>
                </div>
                <div class="col-3">
                <img id="HCimg" src="<?php echo $HC["img"];?>" style="width: 5rem; height: 5rem;" class="rounded-circle float-right">
                </div>
            </div>
            <hr/>
        </div>
    </div>
    
    <div class="card bg-dark text-white col-lg-5 offset-lg-2">
        <div class="card-body">
            <h5 class="card-title lead">Hotels List</h5>
            <div class="list-group">
                <?php
                    if(!empty($Hinfo)){
                        foreach ($Hinfo as $key => $value) {
                        echo "<a href='index.php?controlpanel&hotel=$value[id]' class='list-group-item list-group-item-action lead'>$value[name]<small class='float-right'>id:<img src='https://dummyimage.com/100x4:3/8a008a/ffffff.png&text=$value[id]' style='width: 2rem; height: 2rem;' class='rounded-circle'></small></a>";
                        }
                    }
                ?>  
            </div>       
        </div>
        
        <div class="card-footer text-muted">
        <div class="row">
            <div class="col">
                <a type="button" href="./index.php?myaccount" class="btn btn-secondary btn-block">My Account</a>
            </div>
            <div class="col">
                <a type="button" href="./index.php?controlpanel&InsHotel&hc=<?php echo $HC["id"];?>" class="btn btn-warning btn-block">Insert Hotel</a>
            </div>
        </div>
        </div> 
    </div>
    </div>
</div>



<script>
    $( document ).ready(function() {
        //Modify padding to fit verticaly centered
        $('.panel').css('padding-bottom', 150 + 'px');
        //Adding Map to Prop bussenes Hotel Chain
        $.ajax({                
                url : "https://open.mapquestapi.com/geocoding/v1/address?key=VYuZMOZ9JCOPzk2Dx2eZB5Zu4Y9WDy4u&location=<?php echo $addres;?>",
                type : 'get',
                dataType: 'json',
                success : function(data){
                    var map = data.results[0].locations[0];
                    //console.log(map);
                    $("#HCmapImg").attr("src",map.mapUrl);
                    $("#HCmapImg").attr("alt",map.adminArea5);

                    $("#HCmapImg").show();
                    $("#imgSpin").hide();
                }
        });
    });
            
</script>