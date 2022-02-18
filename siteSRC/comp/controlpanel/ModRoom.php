
<?php
    $db = new Database();
    //Printing Scaling HTML VIEW To Desktop View
    print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");


    if(isset($_POST["save"])){
    
        $ac = isset($_POST['checkAc']) ? 1 : 0;
        $privBathroom = isset($_POST['checkPrivBathroom']) ? 1 : 0;
        $tv = isset($_POST['checkTv']) ? 1 : 0;
        $tecaffe = isset($_POST['checkTeCaffe']) ? 1 : 0;
        $minibar = isset($_POST['checkMinibar']) ? 1 : 0;
        $safe = isset($_POST['checkSafe']) ? 1 : 0;
        $phone = isset($_POST['checkPhone']) ? 1 : 0;

        if($_POST['bedDouble']==""){$_POST['bedDouble']=0;}
        if($_POST['bedSingle']==""){$_POST['bedSingle']=0;}
        if($_POST['sofa']==""){$_POST['sofa']=0;}
        if($_POST['room']==""){$_POST['room']=0;}
        if($_POST['quantity']==""){$_POST['quanitity']=0;}

        $return = true;
        $sql = "UPDATE room_type SET room_type.cod='$_POST[cod]', room_type.nameBea='$_POST[nameBea]', room_type.bedDouble='$_POST[bedDouble]', room_type.bedSingle='$_POST[bedSingle]', room_type.sofa='$_POST[sofa]', room_type.room='$_POST[room]', room_type.quantity='$_POST[quantity]' WHERE room_type.id='$_POST[idRoom]'";
        if($db->query($sql)!=true){$return=false;}
        //echo $sql;

        $sql = "UPDATE room_detail SET room_detail.ac='$ac',room_detail.privatebath='$privBathroom', room_detail.tv='$tv', room_detail.tecaffe='$tecaffe' , room_detail.minibar='$minibar' , room_detail.safe='$safe' , room_detail.tel='$phone' WHERE room_detail.room='$_POST[idRoom]'";
        if($db->query($sql)!=true){$return=false;}
        //echo $sql;

        if($return)
        echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&rooms=$_GET[h]'>";
        
        exit();
    }
    elseif(isset($_POST["delete"])){
        // sql to delete a record
        $sql = "DELETE FROM room_type WHERE id=$_POST[idRoom]";
        //echo $sql;
        if ($db->query($sql) === TRUE) {
            echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&rooms=$_GET[h]'>";
        }
        exit();
    }
    elseif(isset($_POST["insert"])){
        $ac = isset($_POST['checkAc']) ? 1 : 0;
        $privBathroom = isset($_POST['checkPrivBathroom']) ? 1 : 0;
        $tv = isset($_POST['checkTv']) ? 1 : 0;
        $tecaffe = isset($_POST['checkTeCaffe']) ? 1 : 0;
        $minibar = isset($_POST['checkMinibar']) ? 1 : 0;
        $safe = isset($_POST['checkSafe']) ? 1 : 0;
        $phone = isset($_POST['checkPhone']) ? 1 : 0;


        if($_POST['bedDouble']==""){$_POST['bedDouble']=0;}
        if($_POST['bedSingle']==""){$_POST['bedSingle']=0;}
        if($_POST['sofa']==""){$_POST['sofa']=0;}
        if($_POST['room']==""){$_POST['room']=0;}
        if($_POST['quantity']==""){$_POST['quantity']=0;}

        $return = true;
        $sql = "INSERT INTO room_type (cod,nameBea,bedDouble,bedSingle,sofa,room,quantity,hotel) VALUES ('$_POST[cod]','$_POST[nameBea]','$_POST[bedDouble]','$_POST[bedSingle]','$_POST[sofa]','$_POST[room]','$_POST[quantity]', '$_GET[h]')";
        if($db->query($sql)!=true){$return=false;}
        //echo $sql;
        

        //Geting Info About Room_detail
        $query = "SELECT LAST_INSERT_ID() AS id;";
        $result = $db->query($query);
        $roomId = "";
        if ($result->num_rows == 1) {
            $roomId = $result->fetch_assoc()["id"];
        }

        $sql = "INSERT INTO room_detail (ac,privatebath,tv,tecaffe,minibar,safe,tel,room) VALUES ('$ac','$privBathroom','$tv','$tecaffe','$minibar','$safe','$phone','$roomId')";
        if($db->query($sql)!=true){$return=false;}
        //echo $sql;

        if($return)
        echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&rooms=$_GET[h]'>";
        exit();
    }
    

    $roomType = array(
        "id" => "",
        "cod" => "",
        "nameBea" => "",
        "bedSingle" => "",
        "bedDouble" => "",
        "sofa" => "",
        "room" => "",
        "dis" => "",
        "quantity" => ""
    );
    $people = "";
    $roomDetail = array(
        "ac" => false,
        "privatebath" => false,
        "tv" => false,
        "tecaffe" => false,
        "wifi" => false,
        "minibar" => false,
        "safe" => false,
        "tel" => false
    );
    if(isset($_GET["ModRoom"])){
            //Geting Info about Room_type
        $query = "SELECT * FROM room_type WHERE room_type.id =$_GET[ModRoom]";
        $result = $db->query($query);
        if ($result->num_rows == 1) {
            $roomType = $result->fetch_assoc();
        }

        //Geting Info About Room_detail
        $query = "SELECT * FROM room_detail WHERE room_detail.room =$_GET[ModRoom]";
        $result = $db->query($query);
        $roomDetail = "";
        if ($result->num_rows == 1) {
            $roomDetail = $result->fetch_assoc();
        }

        //Calculate people
        $people = $roomType["bedSingle"]+2*($roomType["bedDouble"])+$roomType["sofa"];

    }
  

?>

<form action="" id="myform" method="POST" >
<input name="idRoom" style="display: none;" readonly value="<?php if(isset($_GET['ModRoom'])){echo $_GET['ModRoom'];} ?>"/>
<input name="h" style="display: none;" readonly value="<?php if(isset($_GET['h'])){echo $_GET['h'];} ?>"/>
<div class="panel container">
    <div class="row">
        <div class="card-info card bg-dark text-white col-lg-4">
            <table class="table table-striped table-dark">
            <tbody>
                <tr><th>COD</th><td><input maxlength='75' class="form-control form-control-sm" name="cod" type="text" value="<?php echo $roomType["cod"];?>"></td></tr>
                <tr><th>Name</th><td><input maxlength='75' class="form-control form-control-sm" name="nameBea" type="text" value="<?php echo $roomType["nameBea"];?>"></td></tr>
                <tr><th>Single Bed</th><td><input maxlength='11' class="form-control form-control-sm" name="bedSingle" type="number" pattern="[0-9]*" min="0" value="<?php echo $roomType["bedSingle"];?>"></td></tr>
                <tr><th>Double Bed</th><td><input maxlength='11' class="form-control form-control-sm" name="bedDouble" type="number" pattern="[0-9]*" min="0" value="<?php echo $roomType["bedDouble"];?>"></td></tr>
                <tr><th>Sofa</th><td><input maxlength='11' class="form-control form-control-sm" name="sofa" type="number" pattern="[0-9]*" min="0" value="<?php echo $roomType["sofa"];?>"></td></tr>
                <tr><th>Room</th><td><input maxlength='11' class="form-control form-control-sm" name="room" type="number" pattern="[0-9]*" min="0" value="<?php echo $roomType["room"];?>"></td></tr>
                <tr><th>Quantity</th><td><input maxlength='11' class="form-control form-control-sm" name="quantity" type="number" pattern="[0-9]*" min="0" value="<?php echo $roomType["quantity"];?>"></td></tr>
            </tbody>
            </table>
            <div class="card-footer text-muted">
                <?php
                if(isset($_GET["InsRoom"])):
                ?>
                <div class="row">
                    <div class="col">
                        <a type="button" href="./index.php?controlpanel&rooms=<?php echo $_GET["h"];?>" class="btn btn-secondary btn-block">Back</a>
                    </div>
                    <div class="col">
                        <button type="submit" name="insert" class="btn btn-warning btn-block">Insert</button>                    </div>
                </div>
                <?php
                elseif(isset($_GET["ModRoom"])):
                ?>
                <div class="row">
                    <div class="col">
                        <a type="button" href="./index.php?controlpanel&rooms=<?php echo $_GET["h"];?>" class="btn btn-secondary btn-block">Back</a>
                    </div>
                    <div class="col">
                        <button type="submit" name="save" class="btn btn-warning btn-block">Save</button>
                        <button type="submit" name="delete" value="<?php echo $_GET["ModRoom"];?>" class="btn btn-danger btn-block">Delete</button>
                    </div>
                </div>
                <?php
                endif;
                ?>
            </div>
        </div>
        <div class="card-info card bg-dark text-white col-lg-8">
            <p class="lead">Room Detail</p>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <span class='lead'> AC </span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkAc" name="checkAc" <?php if($roomDetail['ac']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkAc"></label>
                        </div>
                    </div>
                    <div class="col">
                        <span class='lead'> Priv.Bathroom </span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkPrivBathroom" name="checkPrivBathroom" <?php if($roomDetail['privatebath']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkPrivBathroom"></label>
                        </div>
                    </div>
                    <div class="col">
                        <span class='lead'> TV </span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkTv" name="checkTv" <?php if($roomDetail['tv']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkTv"></label>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col col-lg-4">
                        <span class='lead'> Minibar </span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkMinibar" name="checkMinibar" <?php if($roomDetail['minibar']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkMinibar"></label>
                        </div>
                    </div>
                    <div class="col col-lg-4">
                        <span class='lead'> Safe </span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkSafe" name="checkSafe" <?php if($roomDetail['safe']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkSafe"></label>
                        </div>
                    </div>
                    
                </div>
                <hr/>
                <div class="row">
                    <div class="col col-lg-4">
                        <span class='lead'> The-Caffe</span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkTeCaffe" name="checkTeCaffe" <?php if($roomDetail['tecaffe']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkTeCaffe"></label>
                        </div>
                    </div>
                    <div class="col col-lg-4">
                        <span class='lead'> Phone </span>
                        <div style="display: inline;"> 
                                <input class="check" id="checkPhone" name="checkPhone" <?php if($roomDetail['tel']){echo "checked";}?> type="checkbox" />
                                <label class="check" for="checkPhone"></label>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
</form>


<script>
    $( document ).ready(function() {
        //Modify padding to fit verticaly centered
        $('.panel').css('padding-bottom', 150 + 'px');
    });
</script>
