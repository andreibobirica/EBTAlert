
<?php
    $db = new Database();

    //Printing Scaling HTML VIEW To Desktop View
    print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");


    //Geting Info of the hotel
    $query = "SELECT * FROM hotel WHERE hotel.id =".$_GET["rooms"];
    $result = $db->query($query);
    if ($result->num_rows == 1) {
        $ret = true;
        $hotel = $result->fetch_assoc();
    }

    //Geting info about Room Types
    $query = "SELECT * FROM room_type WHERE room_type.hotel =$_GET[rooms]";
    $result = $db->query($query);
    $roomTypes = array();
    if ($result->num_rows > 0) {
        $ret = true;
        while($row = $result->fetch_assoc()){
            array_push($roomTypes,$row);
        }
    }

    //Geting Info About Room_detail
    $roomsDetail=array();
    foreach($roomTypes as $room){
        $query = "SELECT * FROM room_detail WHERE room_detail.room =$room[id]";
        $result = $db->query($query);
        $roomDetail = "";
        if ($result->num_rows == 1) {
            $roomDetail = $result->fetch_assoc();
            //Styling icon about Options (Detail) for printing it into View
            if($roomDetail["ac"]==1){$ac='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $ac='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            if($roomDetail["privatebath"]==1){$privatebath='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $privatebath='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            if($roomDetail["tv"]==1){$tv='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $tv='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            if($roomDetail["tecaffe"]==1){$tecaffe='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $tecaffe='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            if($roomDetail["minibar"]==1){$minibar='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $minibar='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            if($roomDetail["safe"]==1){$safe='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $safe='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            if($roomDetail["tel"]==1){$tel='<i class="fa fa-check-circle" aria-hidden="true"></i>';}else{
                $tel='<i class="fa fa-times fa-1x" aria-hidden="true"></i>';}
            //Storing rooms detail in the array
            array_push($roomsDetail,array("ac"=>$ac,"privatebath"=>$privatebath,"tv"=>$tv,"tecaffe"=>$tecaffe,"minibar"=>$minibar,"safe"=>$safe,"tel"=>$tel));            
        }
    }


    
    //Geting Img About de Room
    $query = "SELECT * FROM room_img WHERE room_img.room =$_GET[rooms]";
    $result = $db->query($query);
    $roomImg = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            array_push($roomImg,$row);
        }
    }

?>




<div class="panel container">
    
  
    <div class="row">    
        <div class="card bg-dark text-white col-lg-12">
            
            <div class="row">
                <div class="col">
                    <h1 class="lead">Type of Rooms </h1>
                    <span class='lead'>Hotel: <?php echo $hotel['name'];?> - </span> 
                    <span class='lead'>Hotel id: <?php echo $hotel['id'];?></span>
                    <div class="pull-right">
                    <a type="button" href="./index.php?controlpanel&hotel=<?php echo $_GET["rooms"];?>" class="btn btn-secondary btn-lg"> Back </a>
                    <a type="button" href="index.php?controlpanel&InsRoom&h=<?php echo $_GET["rooms"];?>" class="btn btn-warning btn-lg">Insert</a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-dark">
            <thead>
                <tr>
                    <th scope="col"><small>COD</small></th>
                    <th scope="col"><small>Name</small></th>
                    <th scope="col"><small>Quantity</small></th>
                    <th scope="col"><small>Options</small></th>
                    <th scope="col"><small>Single Bed</small></th>
                    <th scope="col"><small>Double Bed</small></th>
                    <th scope="col"><small>Sofa</small></th>
                    <th scope="col"><small>Rooms</small></th>
                    <th scope="col"><small>Max People</small></th>
                    <th scope="col"><small>Images</small></th>
                    <th scope="col"><small>Modify</small></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                foreach ($roomTypes as $value) {
                    $people = $value["bedSingle"]+2*($value["bedDouble"])+$value["sofa"];
                    print("<tr>
                    <th scope='row'>$value[cod]</th>
                    <td>$value[nameBea]</td>
                    <td>$value[quantity]</td>
                    <td id='noClick'>
                    
                        <button class='btn btn-secondary  dropdown-toggle dropdown-toggle-split' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                         
                        </button>
                        <div class='dropdown-menu bg-dark' aria-labelledby='dropdownMenuButton'>
                            <table  class='table table-bordered table-dark'>
                                <tr><td>AC</td><td>".$roomsDetail[$i]['ac']."</td><td></td><td>Priv.Bath</td><td>".$roomsDetail[$i]['privatebath']."</td></tr>
                                <tr><td>TV</td><td>".$roomsDetail[$i]['tv']."</td><td></td><td>The-Caffe</td><td>".$roomsDetail[$i]['tecaffe']."</td></tr>
                                <tr><td>Safe</td><td>".$roomsDetail[$i]['safe']."</td><td></td><td>Phone</td><td>".$roomsDetail[$i]['tel']."</td></tr>
                            </table>
                        </div>
                    
                    </td>
                    <td>$value[bedSingle]</td>
                    <td>$value[bedDouble]</td>
                    <td>$value[sofa]</td>
                    <td>$value[room]</td>
                    <td>$people</td>
                    <td><a href='./index.php?controlpanel&roomImage=$value[id]&h=$_GET[rooms]' type='button' class='btn btn-outline-success btn-sm'>Images</button></td>
                    <td><a href='./index.php?controlpanel&ModRoom=$value[id]&h=$_GET[rooms]' type='button' class='btn btn-warning btn-sm'>Modify</a></td>
                    </tr>");
                    //Adding 1 to the counter
                    $i=$i+1;
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
    
</div>



<script>
    $( document ).ready(function() {
        //Modify padding to fit verticaly centered
        $('.panel').css('padding-bottom', 150 + 'px');  
    }); 

</script>