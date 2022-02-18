<?php
    $db = new Database();

    //Printing Scaling HTML VIEW To Desktop View
    print("<script>$('head').append(\"<meta name='viewport' content='width=1024,height=device-height'/>\");</script>");

    //print_r($_GET);
    //Geting info about Room Types
    $query = "SELECT * FROM room_type WHERE room_type.hotel =$_GET[ViewDisp]";
    $result = $db->query($query);
    $roomTypes = array();
    if ($result->num_rows > 0) {
        $ret = true;
        while($row = $result->fetch_assoc()){
            array_push($roomTypes,$row);
        }
    }

?>

<!-- Modal Mod -->
<div class="modal fade" id="modalModPriceList" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Here are the Price List:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='modlMod'>

      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Show -->
<div class="modal fade" id="modalShowPriceList" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Here are the Price List:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modlShow">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="panel container-fluid">
    <div class="card bg-dark text-light">
        <div class="card-body">
        <h1 class="display-4">Availability</h1>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="since" class="label">Since</label>
                        <input id="since" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="until" class="label">Until</label>
                        <input id="until" type="date" class="form-control">
                    </div>
                </div>
                <div class="col">
                    <div class="pull-right">
                        <a type="button" href="./index.php?controlpanel&hotel=<?php echo $_GET["ViewDisp"];?>" class="btn btn-secondary btn-lg"> Back </a>                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="accordion" id="accordionExample">
    <?php
        foreach($roomTypes as $room){
            print("
            <div class='card'>
                <div class='card-header row' id='heading$room[id]'>
                <div class='col'>
                    <h2 class='mb-0'>
                        <button class='btn btn-sm showCalendar' type='button' data-toggle='collapse' data-target='#collapse$room[id]' aria-expanded='true' aria-controls='collapse$room[id]'>
                            <h1 class='lead'>$room[cod] - $room[nameBea] - ID:$room[id]</h1>
                        </button>
                    </h2>
                </div>
                <div class='col-3 float-right'><!-- Da finire
                    <div class='row'>
                    <a type='button' id='ModpricesList' room='$room[id]' data-toggle='modal' data-target='#modalModPriceList' class='modPrice lead'>Modify Price List</a><br/><hr/>
                    <a type='button' id='ShowpricesList' room='$room[id]' data-toggle='modal' data-target='#modalShowPriceList' class='showPrice lead'>Show Price List</a>
                    </div> -->
                </div> 
                </div>
                <div id='collapse$room[id]' class='collapse' room='$room[id]'quantity='$room[quantity]' aria-labelledby='heading$room[id]' data-parent='#accordionExample'>
                <div class='card-body row'>
                    <div class='calendarHeader col-2' id='header$room[id]'></div>
                    <div class='scrollmenu col'>
                        <div class='calendar' id='$room[id]'></div>
                    </div>
                </div>
                </div>
            </div>
            ");

        }
    ?>


<script>
//SCRIPT MODIFY AVAILABILILTY AND PRICING
    $( document ).ready(function() {
        //Modify padding to fit verticaly centered
        $('.panel').css('padding-bottom', 150 + 'px');

        //Geting Click on Room Button Show Calendar
        $('.collapse').on('show.bs.collapse', function () {
            //Remove elements when drag up
            $(".calendar").empty();
            $(".calendarHeader").empty();
            var since = $("#since").val();
            var until = $("#until").val();
            var room = $(this).attr("room");
            var quantity = $(this).attr("quantity");
            if( since && until && room)
            getCalendar(since,until,room,quantity);
        })
        
        //Function that get the calendar that from GetDisp.php script
        function getCalendar(since,until,room,quantity){
            $.ajax({                
                url : "./comp/controlpanel/disp/getDisp.php?since="+since+"&until="+until+"&room="+room,
                type : 'get',
                dataType: 'text',
                success : function(data){
                    createTableCalendar(data,room,quantity,since.toString(),until.toString());
                }
            });
        }

        //*function that creates the tables with da days prestabiled ad show the availability
        function createTableCalendar(calendar,room,quantity,since,until){
            calendar = JSON.parse(calendar);
            console.log(calendar);
            var dates=[];
            var disps=[];
            var books=[];
            var priceNRs=[];
            var priceRs=[];
            calendar.forEach(function(item) {
                dates.push(item["date"]);
                disps.push(item["disp"]);
                books.push(item["book"]);
                priceNRs.push(item["priceNR"]);
                priceRs.push(item["priceR"]);
            });

            //Creating table
            var table = "<table class='table table-bordered table-dark'><thead><tr><th scope='col'></th>"
            dates.forEach(function(item,index){
                var bg = "bg-success";
                if((parseInt(disps[index])<0) || (parseInt(books[index])>=parseInt(disps[index]))){bg="bg-danger";}
                table+="<th scope='col' class='mod "+bg+"' index='"+index+"' id='date"+index+"'>"+item+"</th>";
            });
            table +="</tr></thead><tbody>";
            table += "<tr><th scope='row' style='visibility: hidden;'>A</th>"
            disps.forEach(function(item,index){
                var bg = "";
                if((parseInt(disps[index])<0) || (parseInt(books[index])>=parseInt(disps[index]))){bg="bg-danger";}
                table+="<td scope='col' class='tddisp mod "+bg+"' tot='"+quantity+"' dis='"+item+"'room='"+room+"'date='"+dates[index]+"' id='dis"+index+"' index='"+index+"'>"+item+"/"+quantity+"</td>"
            });
            table +="</tr>";
            
            table += "<tr><th scope='row' style='visibility: hidden;'>B</th>"
            books.forEach(function(item,index){
                var bg = "";
                if((parseInt(disps[index])<0) || (parseInt(books[index])>=parseInt(disps[index]))){bg="bg-danger";}
                table+="<td scope='col' class='mod "+bg+"' id='book"+index+"'>"+item+"</td>"
            });
            table +="</tr>";
            
            table += "<tr><th scope='row' style='visibility: hidden;'>P</th>"
            priceNRs.forEach(function(item,index){
                var bg = "";
                if((parseInt(disps[index])<0) || (parseInt(books[index])>=parseInt(disps[index]))){bg="bg-danger";}
                table+="<td scope='col' class='tdpnr mod "+bg+"' pnr='"+item+"' room='"+room+"' date='"+dates[index]+"' index='"+index+"' id='pnr"+index+"'>"+item+"</td>"
            });
            table +="</tr>";

            table += "<tr><th scope='row' style='visibility: hidden;'>S</th>"
            priceRs.forEach(function(item,index){
                var bg = "";
                if((parseInt(disps[index])<0) || (parseInt(books[index])>=parseInt(disps[index]))){bg="bg-danger";}
                table+="<td scope='col' class='tdpr mod "+bg+"' pr='"+item+"' room='"+room+"' date='"+dates[index]+"' id='pr"+index+"' index='"+index+"'>"+item+"</td>"
            });
            table +="</tr>";

            
            table +="</tbody></table>";

            $("#"+room).html("");
            $("#"+room).html(table);

            //Caledar Header
            var table = " \
            <table class='table table-bordered table-dark'><thead><tr><th scope='col' style='visibility: hidden;'>2020</th></tr></thead><tbody> \
                <tr><th scope='row'><div class='row'><div class='col'>Availability</div></th><th>\
                    <div class='btn-group dropright'><i class='fa fa-arrow-down dropdown-toggle' data-toggle='dropdown' ></i><div class='dropdown-menu'>\
                        <div class='dropdown-item disabled'><p>Availability<br/>Change for All:</p></div>\
                        <a class='dropdown-item'><input class='form-control input-sm' asince='"+since+"' auntil='"+until+"' aroom='"+room+"' id='disFA' min='0' type='number'></a>\
                        <a class='dropdown-item'><button type='button' class='btn btn-primary' id='disFABtn' btn-sm'>SAVE</button></a>\
                    </div></div></div></div>\
                </th></tr>\
                <tr><th scope='row'>Booked</th></tr>\
                <tr><th scope='row'><div class='row'><div class='col'>Price NR €</div></th><th>\
                    <div class='btn-group dropup'><i class='fa fa-arrow-down dropdown-toggle' data-toggle='dropdown' ></i><div class='dropdown-menu'>\
                        <div class='dropdown-item disabled'><p>Price NR<br/>Change for All:</p></div>\
                        <a class='dropdown-item'><input class='form-control input-sm' pnrsince='"+since+"' pnruntil='"+until+"' pnrroom='"+room+"' id='pnrFA' min='0' type='number'></a>\
                        <a class='dropdown-item'><button type='button' class='btn btn-primary' id='pnrFABtn' btn-sm'>SAVE</button></a>\
                    </div></div></div></div>\
                </th></tr>\
                <tr><th scope='row'><div class='row'><div class='col'>Price €</div></th><th>\
                    <div class='btn-group dropup'><i class='fa fa-arrow-down dropdown-toggle' data-toggle='dropdown' ></i><div class='dropdown-menu'>\
                        <div class='dropdown-item disabled'><p>Price <br/>Change for All:</p></div>\
                        <a class='dropdown-item'><input class='form-control input-sm' prsince='"+since+"' pruntil='"+until+"' prroom='"+room+"' id='prFA' min='0' type='number'></a>\
                        <a class='dropdown-item'><button type='button' class='btn btn-primary' id='prFABtn' btn-sm'>SAVE</button></a>\
                    </div></div></div></div>\
                </th></tr>\
            </tbody></table>";
            $("#header"+room).html(table);            
        }


        //Remove clickable (add disabled button) whitin the dates are impostated
        $(".showCalendar").prop('disabled', true);
        

        //When the dates are completed remove disabled from buttons
        //Otherwise set the min and max for each other dates
        $('#since').change(function(){
            $('#until').prop('min',$('#since').val())
            if($('#until').val()){
                $(".showCalendar").removeAttr("disabled");
            }
            
            if(!$('#since').val()){
                $(".showCalendar").prop('disabled', true);
            }
            $(".collapse").removeClass("show");
        });
        $('#until').change(function(){
            console.log($('#until').val());
            $('#since').prop('max',$('#until').val())
            if($('#since').val()){
                $(".showCalendar").removeAttr("disabled");
            }
            
            if(!$('#until').val()){
                $(".showCalendar").prop('disabled', true);
            }
            $(".collapse").removeClass("show");
        });

        //DOUBLE click on DT to modify

        //Modify Availability
        $("body").on("dblclick", ".tddisp", modAvail);
        $("body").on("ontouchstart", ".tddisp", modAvail);
        function modAvail(){
            var dis = $(this).attr("dis");
            var tot = $(this).attr("tot");
            var date = $(this).attr("date");
            var room = $(this).attr("room");
            var index = $(this).attr("index");
            var bookElem = $(".calendar").find("#book"+index);
            var td = $(this);
            td.removeClass("tddisp");
            td.removeClass("mod");
            td.html("<input type='number' min='0' id='disData' class='form-control input-sm'>");
            $(".calendar").find("#disData").val(dis);
            $("body").on("click", ".mod", function() {
                var disData =$(".calendar").find("#disData").val();
                td.html(disData+"/"+tot);
                td.attr('dis', disData);
                td.addClass("tddisp");
                td.addClass("mod");
                $("body").off("click", ".mod");

                //Modifica
                var d1 = $(".calendar").find("#date"+index);
                var d2 = td;
                var d3 = bookElem;
                var d4 = $(".calendar").find("#pnr"+index);
                var d5 = $(".calendar").find("#pr"+index);
                if(parseInt(disData)<0 || parseInt(d3.html()) >= parseInt(disData)){
                    d1.addClass("bg-danger");
                    d2.addClass("bg-danger");
                    d3.addClass("bg-danger");
                    d4.addClass("bg-danger");
                    d5.addClass("bg-danger");
                }else{
                    d1.removeClass("bg-danger");
                    d1.addClass("bg-success");
                    d2.removeClass("bg-danger");
                    d3.removeClass("bg-danger");
                    d4.removeClass("bg-danger");
                    d5.removeClass("bg-danger");
                   
                }
                modifyPrice(disData,"","",room,date);
            });
        }

        //Modify Price NR
        $("body").on("dblclick", ".tdpnr", modPriceNR);
        $("body").on("ontouchstart", ".tdpnr", modPriceNR);
        function modPriceNR(){
            console.log("modify");
            var pnr = $(this).attr("pnr");
            var date = $(this).attr("date");
            var room = $(this).attr("room");
            var td = $(this);
            td.removeClass("tdpnr");
            td.removeClass("mod");
            td.html("<input type='number' min='0' id='pnrData' class='form-control input-sm'>");
            $(".calendar").find("#pnrData").val(pnr);
            $("body").on("click", ".mod", function() {
                console.log("save");
                var pnrData =$(".calendar").find("#pnrData").val();
                td.html(pnrData);
                td.attr('pnr', pnrData);
                td.addClass("tdpnr");
                td.addClass("mod");
                $("body").off("click", ".mod");
                //Modifica
                modifyPrice("",pnrData,"",room,date);
            });
        }

        
        //Modify Price R
        $("body").on("dblclick", ".tdpr", modPriceR);
        $("body").on("ontouchstart", ".tdpr", modPriceR);
        function  modPriceR(){
            console.log("modify");
            var pr = $(this).attr("pr");
            var date = $(this).attr("date");
            var room = $(this).attr("room");
            var td = $(this);
            td.removeClass("tdpr");
            td.removeClass("mod");
            td.html("<input type='number' min='0' id='prData' class='form-control input-sm'>");
            $(".calendar").find("#prData").val(pr);
            $("body").on("click", ".mod", function() {
                console.log("save");
                var prData =$(".calendar").find("#prData").val();
                td.html(prData);
                td.attr('pr', prData);
                td.addClass("tdpr");
                td.addClass("mod");
                $("body").off("click", ".mod");
                //Modifica
                modifyPrice("","",prData,room,date);
            });
        }

        /**Function that send this parameters to be modifyied */
        function modifyPrice(dis,pnr,pr,room,date){
            $.ajax({                
                url : "./comp/controlpanel/disp/ModDisp.php?ModSingle&dis="+dis+"&pnr="+pnr+"&pr="+pr+"&room="+room+"&date="+date,
                type : 'get',
                dataType: 'text',
                success : function(data){
                    //console.log(data);
                },
                error: function(data){
                    //console.error(data);
                }
            });
        }

        //Modify All
        //DIS FORALBTn
        $("body").on("click", "#disFABtn", modPriceAvAll);
        function  modPriceAvAll(){
            console.log("modify All");
            var dis = $(".calendarHeader").find("#disFA").val();
            var room = $(".calendarHeader").find("#disFA").attr("aroom");
            var since = $(".calendarHeader").find("#disFA").attr("asince");
            var until = $(".calendarHeader").find("#disFA").attr("auntil");
            var quantity = $("#collapse"+room).attr("quantity");

            console.log(dis);console.log(room);console.log(since);console.log(until);
            modifyPriceAll(dis,"","",room,since,until,quantity);
        }

       
        //PRN FORALBTn
        $("body").on("click", "#pnrFABtn", modPricePnrAll);
        function  modPricePnrAll(){
            console.log("modify All");
            var pnr = $(".calendarHeader").find("#pnrFA").val();
            var room = $(".calendarHeader").find("#pnrFA").attr("pnrroom");
            var since = $(".calendarHeader").find("#pnrFA").attr("pnrsince");
            var until = $(".calendarHeader").find("#pnrFA").attr("pnruntil");
            var quantity = $("#collapse"+room).attr("quantity");

            console.log(pnr);console.log(room);console.log(since);console.log(until);
            modifyPriceAll("",pnr,"",room,since,until,quantity);
        }


        //PR FORALBTn
        $("body").on("click", "#prFABtn", modPricePrAll);
        function  modPricePrAll(){
            console.log("modify All");
            var pr = $(".calendarHeader").find("#prFA").val();
            var room = $(".calendarHeader").find("#prFA").attr("prroom");
            var since = $(".calendarHeader").find("#prFA").attr("prsince");
            var until = $(".calendarHeader").find("#prFA").attr("pruntil");
            var quantity = $("#collapse"+room).attr("quantity");

            console.log(pr);console.log(room);console.log(since);console.log(until);
            modifyPriceAll("","",pr,room,since,until,quantity);
        }
        


        function  modifyPriceAll(dis,pnr,pr,room,since,until,quantity){
            $.ajax({                
                url : "./comp/controlpanel/disp/ModDisp.php?ModAll&dis="+dis+"&pnr="+pnr+"&pr="+pr+"&room="+room+"&since="+since+"&until="+until,
                type : 'get',
                dataType: 'text',
                success : function(data){
                    getCalendar(since,until,room,quantity);
                },
                error: function(data){
                    //console.error(data);
                }
            });
        }


    });          
</script>

<script>
//SCRIPT PRICE LIST



//DA FINIRE

//to finish


$( ".showPrice" ).click(function() {
    console.log("showPrice");
    var room = $(this).attr("room");
    $.ajax({                
        url : "./comp/controlpanel/disp/getDisp.php?pricelist&room="+room,
        type : 'get',
        dataType: 'json',
        success : function(data){
            putModal(data);
        }
    });

    function putModal(data){
        console.log(data);
        var table="<table class='table'>";
        data.forEach(function(item, index) {
            table+="<tr><th>Name:</th><td>"+item["name"]+"</td><th>Price:</th><td>"+item["price"]+"</td></tr>";
        }); 
        table+="</table>";
        $("#modlShow").html(table);
    }
});

$( ".modPrice" ).click(function() {
    console.log("modPrice");
    var room = $(this).attr("room");
    $.ajax({                
        url : "./comp/controlpanel/disp/getDisp.php?pricelist&room="+room,
        type : 'get',
        dataType: 'json',
        success : function(data){
            putModal(data,room);
        }
    });

    function putModal(data,room){
        console.log(data);
        var table="<table class='table'><form>";
        data.forEach(function(item, index) {
            table+="<tr><th>Name:</th><td><input class='form-control input-sm' type='text' room='"+room+"' id='name"+index+"'></td><th>Price:</th><td><input class='form-control input-sm' type='text' id='price"+index+"'></td></tr>";
        }); 
        data.forEach(function(item, index) {
            console.log($("#modalModPriceList").find("#name"+index));
            $("#modalModPriceList").find("#name"+index).val(item);
        }); 
        table+="</form></table>";
        $("#modlMod").html(table);
    }
});
</script>