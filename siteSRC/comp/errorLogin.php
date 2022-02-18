<div class='container'>
<div class="row">
<div class="card text-white bg-danger mb-3 col-12">
  <div class="card-header"><?php echo $_GET["error"];?></div>
  <div class="card-body">
    <h5 class="card-title">Please be patience and control your data in the authentication phase, Thank you</h5>
    <div class="badge badge-warning"><p id="timer">4</p></div>
  </div>
  </div>
</div>
</div>

<script>
    $( document ).ready(function() {
        timerRec();
        function timerRec(){
            setTimeout(function(){
            var timer = $("#timer").html();
            timer = Number(timer);
            timer = timer -1;
            $("#timer").text(timer);
            if(timer==0){
                window.history.back();
            }else{timerRec();}
            }, 1000);
        }
    });

</script>
