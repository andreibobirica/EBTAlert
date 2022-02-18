
<div class="container-fluid">
    <!--<form action="./index.php?ViewResults" onsubmit="return getGeoCoord()" method='POST'>   -->
    <form action="./index.php?ViewResults" id='formSearch' method='POST'>
    <input name="lat" id="lat" style="display: none;" readonly />
    <input name="lng" id="lng" style="display: none;" readonly />
    <div id="tab1" class="tabs">
            <div class="card bg-dark text-light">
                <div class="card-body">
                    <?php if(isset($_GET["hc"])){
                            echo "<h1 class='display-4'>$_GET[hcname]</h1><hr/>";
                            echo"<input name='hc' style='display: none;' readonly value='$_GET[hc]'/>";
                            echo"<input name='hcname' style='display: none;' readonly value='$_GET[hcname]'/>";
                        }?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="locationId" class="bmd-label-floating">Destination</label>
                                <input id="locationId" name="locationId" type="text" class="form-control">
                                <span class="bmd-help">Name of City, State, Hotel, etc ...</span>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="form-group">
                                <label for="check-in" class="label">check-in</label>
                                <input id="check-in" name="check-in" type="date" class="form-control" value="<?php echo date('Y-m-d', time());?>">
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="form-group">
                                <label for="check-out" class="label" >check-out</label>
                                <input id="check-out" name="check-out" type="date" class="form-control" value="<?php echo date('Y-m-d', time()+86400);?>">
                            </div>
                        </div>
                        <div class="col-6 col-lg-2 ">
                            <label for="nRooms" class="label">Rooms</label>
                            <table><tr><td>
                                <span id="RoomsMin" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>
                            </td><td>
                                <input id="nRooms" name="nRooms" type="text"  readonly min="1" max="30" class="form-control" value="1">
                            </td><td>
                                <span id="RoomsPlus" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>
                            <td></tr></table>
                        </div>    
                        <div class="spinner-border" id="spin" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="col">
                            <button type="button" id="btnSubmit" class="btn btn-raised btn-success float-right">Search</button>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-12 col-md-4 row">
                            <div class="col">
                                <p class="text-center lead">n. Adults</p>
                            </div>
                            <div class="col">
                                <p class="text-center lead">n. Children</p>
                            </div>
                        </div>
                        
                    </div>
                    <div id="roomList">
                        <div id="room1">
                            <!--<hr/>-->
                            <div class="row">
                                <div class="col-6 col-md-4 row">
                                <a href="#" class="badge badge-success">ROOM 1</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-2">
                                    <div class="form-group">
                                        <table><tr><td>
                                            <span id="AdultsMin1" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                        </td><td>
                                            <input id="nAdults1" name="nAdults1" type="text" readonly min="1" max="30" class="form-control" value="1">
                                        </td><td>
                                            <span id="AdultsPlus1" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                        <td></tr></table>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="form-group">
                                        <table><tr><td>
                                            <span id="ChildsMin1" class="oi oi-minus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                        </td><td>
                                            <input id="nChilds1" name="nChilds1" type="text" readonly min="1" max="10" class="form-control" value="0">
                                        </td><td>
                                            <span id="ChildsPlus1" class="oi oi-plus btn btn-secondary" title="plus" aria-hidden="true"></span>
                                        <td></tr></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
<div>
<div class='container-fluid panel'>
<hr/>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class=" w-100" src="./img/hotel.jpg" alt="First slide">
        <div class="carousel-caption d-md-block">
            <div class='card bg-dark'><h5 class='display-4'>Hotel List</h5>
            <p>Search hotels for yout holidays</p></div>
        </div>
    </div>
    <div class="carousel-item">
      <img class=" w-100" src="./img/esotic.jpg" alt="Second slide">
        <div class="carousel-caption d-md-block">
            <div class='card bg-dark'><h5 class='display-4'>Alert</h5>
            <p>We Alert You, Create an interactive alert system to go in the best places</p></div>
        </div>
    </div>
    <div class="carousel-item">
      <img class=" w-100" src="./img/hotelchains.jpg" alt="Third slide">
      <div class="carousel-caption d-md-block" >
            <div class='bg-dark text-white'><h5 class='display-4'>Hotel Chains</h5>
            <p>Make your reservation with best hotel chain</p></div>
        </div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

</div>   

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
                            <input id="nChilds'+n+'" name="nChilds'+n+'" type="text" readonly min="1" max="10" class="form-control" value="0">\
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
    
                
<script>
$( document ).ready(function() {
    //Function that gets the geocoordinates info toking it from search input
    //Add listener to the form
    $( "#btnSubmit" ).click(function( event ) {
        $('#spin').show();
        var location = $("#locationId").val();     
        $.ajax({                
            url : "https://www.mapquestapi.com/geocoding/v1/address?key=VYuZMOZ9JCOPzk2Dx2eZB5Zu4Y9WDy4u&inFormat=kvp&outFormat=json&location="+location+"&thumbMaps=false",
            type : 'get',
            dataType: 'text',
            success : function(data){
                var coord = JSON.parse(data).results[0].locations[0].latLng;
                console.log(coord);
                $("#lat").val(coord.lat);
                $("#lng").val(coord.lng);

                console.log($("#lat").val());
                console.log($("#lng").val());
                $("#formSearch").submit();
            },
            error: function(data){
                event.preventDefault();
                $("#formSearch").submit();
            }
        }); 
        
    });
    
});
</script>
