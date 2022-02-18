
<?php
    $db = new Database();
    //Printing Scaling HTML VIEW To Desktop View
    print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");


    if(isset($_POST["delImg"])){
        // sql to delete a record
        $sql = "DELETE FROM room_img WHERE id=$_POST[delImg]";
        if ($db->query($sql) === TRUE) {
            echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&roomImage=$_POST[idRoom]&h=$_POST[h]'>";
        }
        exit();
    }
    elseif(isset($_FILES["file-upload"]) && !empty($_FILES["file-upload"])){
        //insert Img Upload
        //imgUpl
        include_once "./core/addImg.php";
        $imgLink = "";
        if(isset($_FILES["file-upload"]) && $_FILES["file-upload"]["error"]==0){
            $imgLink = insert("upload/","file-upload",time(),time());
            if($imgLink==false){$return = false;}
        }
        //Insert Hotel
        if($imgLink==""){
        $sql = "INSERT INTO room_img (name, room, img)
        VALUES ('', '$locationId', '$_GET[h]')";
        }else{
            $sql = "INSERT INTO room_img (name, room, img)
            VALUES ('', '$_POST[idRoom]', '$imgLink')";
        }
        if (!($db->query($sql) === TRUE)){$return = false;}
        echo "<meta http-equiv='refresh' content='0;url=index.php?controlpanel&roomImage=$_POST[idRoom]&h=$_POST[h]'>";
        exit();
    }

    //Geting Info About Room
    $query = "SELECT * FROM room_type WHERE room_type.id =".$_GET["roomImage"];
    $result = $db->query($query);
    $room="";
    if ($result->num_rows == 1) {
        $ret = true;
        $room = $result->fetch_assoc();
    }

     //Geting Img About de Room
     $query = "SELECT * FROM room_img WHERE room_img.room =$_GET[roomImage]";
     $result = $db->query($query);
     $roomImg = array();
     if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()){
             array_push($roomImg,$row);
         }
     }
?>



<div class="panel container">
    <form action="" id="myform" method="POST" enctype="multipart/form-data">
    <input name="idRoom" style="display: none;" readonly value="<?php if(isset($_GET['roomImage'])){echo $_GET['roomImage'];} ?>"/> 
    <input name="h" style="display: none;" readonly value="<?php if(isset($_GET['h'])){echo $_GET['h'];} ?>"/> 
    <div class="row">
        <div class="card-info card bg-dark text-white col-lg-12">
        <div class="card-header">
        <div class="row">
                <div class="col">
                    <h1 class="lead">Type of Rooms </h1>
                    <span class='lead'>COD: <?php echo $room['cod'];?> - </span> 
                    <span class='lead'>Name: <?php echo $room['nameBea'];?></span>
                    <div class="pull-right">
                    <a type="button" href="./index.php?controlpanel&rooms=<?php echo $_GET['h']?>" class="btn btn-secondary btn-lg"> Back </a>
                    </div>
                </div>
            </div>
        </div>
            <div class="scrollmenu">
            <label  for="file-upload" class="custom-file-upload">
            <a><img src='./img/plusImage.png' style='width: auto; height: 10rem;' class='rounded no-bg'></a>
            </label>
            <input id="file-upload" name="file-upload" style="display:none" type="file"/>
                <?php
                    foreach($roomImg as $img){
                        print("<a class='modalBtn'><div style='width: auto; height: 15rem;' class='deleteImg'><button type='submit' name='delImg' value='$img[id]'  class='deleteImgBtn btn btn-danger'>X</button><img style='width:100%;height: 13rem;' data-toggle='modal' data-target='#myModal' src='$img[img]' alt='$img[name]' src='$img[img]' name='$img[name]' class='rounded modalBtn'></div></a>");
                    }
                ?>
            </div>
        </div>
    </div>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"  id="headerImgModal"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <img id="srcImgModal" src='' style='width: 100%; height: 100%;'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade bg-dark text-white" id="spinnerModal" role="dialog">
    <div class="row">
        <div class="col offset-4" style="padding-top:20%">
            <hr/>
            <div class="spinner-border text-white" style="width: 5rem; height: 5rem;" role="status"></div>
            <hr/>
            <p class="lead">EBT is Loading...</p>
        </div>
    </div>
</div>
<script>
$('#spinnerModal').modal('show');
window.onload = function(e){ 
    $('.modal-backdrop').remove();
    $('#spinnerModal').remove();
}
</script>


<script>
    $( document ).ready(function() {
        //Modify padding to fit verticaly centered
        $('.panel').css('padding-bottom', 150 + 'px');  

        //Upload Img And refresh
        $("#file-upload").change(function() {
            setTimeout(() => { console.log("World!"); }, 2000);
            $('#myform').submit();
        });

        //Img Modal
        $(".modalBtn").click(function() { 
            console.log("hello");
            $("#headerImgModal").html($(this).attr("name"));
            $("#srcImgModal").attr("src",$(this).attr("src"));
        });
    }); 

</script>
