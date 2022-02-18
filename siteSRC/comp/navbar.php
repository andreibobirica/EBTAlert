<?php
  //Scripting navbar
  $auth = new Authentification();
  $accountName = "Guest";
  $prop = "";
  if(isset($_SESSION["nameAccount"])){
    //If there is a loged in person, SHOW his nickname
    $accountName = $_SESSION["nameAccount"];
    //Not if Prop or User
    if($auth->getIfProp($_SESSION["loginAccount"])){
      $prop = '
      <li class="nav-item"><a href="index.php?controlpanel" class="nav-link">Control Panel</a></li>
      ';
    }
  }



?>

<header>
  <nav id="navbar_top" class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">EBT Alert</a>
    <span style="color:white"><?php echo "Hi, ".$accountName;?></span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="main_nav">	
      <ul class="navbar-nav ml-auto">
      <li class="nav-item "><a href="./" class="nav-link">Search</a></li>
      <li class="nav-item "><a href="?hotelchains" class="nav-link">Hotel Chain</a></li>
      <li class="nav-item "><a href="?myaccount" class="nav-link">My Account</a></li>
      <li class="nav-item "><a href="?contact" class="nav-link">Contact</a></li>
      <li class="nav-item "><a href="?example" class="nav-link">Example</a></li>

      <?php echo $prop;?>
      </ul>
    </div> <!-- navbar-collapse.// -->
  </nav>
</header>



<script>
$('#navbar_top').addClass("fixed-top");
// add padding top to show content behind navbar
$('body').css('padding-top', $('.navbar').outerHeight()+50 + 'px');


</script>





