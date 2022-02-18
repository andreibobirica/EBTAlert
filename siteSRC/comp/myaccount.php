<?php
    
    require_once "./core/Authentification.php";
    include_once "./core/Database.php";
    $db = new Database();
    $auth = new Authentification();
    $auth->sessionTimeExpire();

    
    //logout
    if(isset($_POST["logout"])){ 
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        print('<meta http-equiv="refresh" content="0;url=index.php">');
        exit;
    }

    //Delete reservation
    if(isset($_POST["delAlert"])){
        $query = "DELETE FROM `alert` WHERE `alert`.`id` = $_POST[res]";
        $result = $db->query($query);
    }

	//Auth
	if(!isset($_SESSION["nameAccount"])):?>

        <?php 
        //Login or SignIn Procedure
            if(isset($_POST["login"])){
                if($auth->login($_POST["email"],$_POST["password1"])){
                    if(isset($_POST["redirect"])){
                        $actual_link = $_POST["redirect"];
                        $actual_link = str_replace("andand","&",$actual_link);
                        $actual_link = str_replace("inicio","?",$actual_link);
                        $actual_link = str_replace("egual","=",$actual_link);
                        print("<meta http-equiv='refresh' content='0;url=$actual_link'>");
                    }else{
                        print('<meta http-equiv="refresh" content="0;url=index.php?myaccount">');
                    }
                }else{
                    echo "<meta http-equiv='refresh' content='0;url=index.php?error=Error Login'>";
                }
                exit;
            }
            elseif(isset($_POST["signin"])){
                if($auth->register($_POST["email"],$_POST["password1"],$_POST["password2"],$_POST["name"],$_POST["surname"],"1970-12-31","M")){
                    $auth->login($_POST["email"],$_POST["password1"]);
                    if(isset($_POST["redirect"])){
                        $actual_link = $_POST["redirect"];
                        $actual_link = str_replace("andand","&",$actual_link);
                        $actual_link = str_replace("inicio","?",$actual_link);
                        $actual_link = str_replace("egual","=",$actual_link);
                        print("<meta http-equiv='refresh' content='0;url=$actual_link'>");
                    }else{
                        print('<meta http-equiv="refresh" content="0;url=index.php?myaccount">');
                    }
                }else{
                    echo "<meta http-equiv='refresh' content='0;url=index.php?error=Error Registration'>";
                }
                exit;
            }
        ?>

        <div class="container slcard">
            <div class="mx-auto"><button class="btn btn-raised btn-warning" id="LoginSignIn"><span>Sign In?</span></button></div>
            <div class="mx-auto bg-dark text-white card mb-3" style="display: none" id="signInCard">
                <div class="card-body">
                    <div class="row" >
                        <div class="col-md-6 mx-auto ">
                            <form class="form-horizontal" action='' method="POST">
                            <input name="redirect" style="display: none;" readonly value="<?php if(isset($_GET['redirect'])){echo $_GET['redirect'];} ?>"/>
                                <fieldset>
                                    <div id="legend">
                                        <legend class="">Sign In</legend>
                                    </div>
                                    <div class="raw">
                                        <div class="col">
                                            <div class="control-group">
                                                <label class="control-label"  for="username">Name</label>
                                                <div class="controls">
                                                    <input type="text" id="name" name="name" placeholder="" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div> 
                                        
                                        <div class="col">
                                            <div class="control-group">
                                                <label class="control-label"  for="username">Surname</label>
                                                <div class="controls">
                                                    <input type="text" id="surname" name="surname" placeholder="" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="control-group">
                                            <label class="control-label" for="email">E-mail</label>
                                            <div class="controls">
                                                <input type="email" id="email" name="email" placeholder="" class="form-control input-lg">
                                                <p class="help-block">Please provide your E-mail</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="control-group">
                                            <label class="control-label" for="password">Password</label>
                                            <div class="controls">
                                                <input type="password" id="password1" name="password1" placeholder="" class="form-control input-lg">
                                                <p class="help-block">Password should be at least 6 characters</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="control-group">
                                            <label class="control-label"  for="password2">Password (Confirm)</label>
                                            <div class="controls">
                                                <input type="password" id="password2" name="password2" placeholder="" class="form-control input-lg">
                                                <p class="help-block">Please confirm password</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="control-group">
                                            <!-- Button -->
                                            <div class="controls">
                                                <button class="btn btn-raised btn-success" name="signin">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-dark text-white mb-3" id="logInCard">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mx-auto ">
                            <form class="form-horizontal" action='' method="POST">
                            <input name="redirect" style="display: none;" readonly value="<?php if(isset($_GET['redirect'])){echo $_GET['redirect'];} ?>"/>
                                <fieldset>
                                    <div id="legend">
                                        <legend class="">Log In</legend>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="email">E-mail</label>
                                        <div class="controls">
                                            <input type="email" id="email" name="email" placeholder="" class="form-control input-lg">
                                            <p class="help-block">Please provide your E-mail</p>
                                        </div>
                                        </div>

                                        <br/>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="password">Password</label>
                                        <div class="controls">
                                            <input type="password" id="password1" name="password1" placeholder="" class="form-control input-lg">
                                            <p class="help-block">Password should be at least 6 characters</p>
                                        </div>
                                    </div>
                                
                                    <div class="control-group">
                                        <!-- Button -->
                                        <div class="controls">
                                            <button class="btn btn-raised btn-success" name="login">Log In</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SCRIPT -->
        <script>
            $(document).ready(function() {
                //move tabs center
                $('.slcard').css('padding-bottom', 100 + 'px');

                /**Animation Show Hide of Log IN AND Sign Up*/
                var logsign = true;
                $("#LoginSignIn").click(function() {
                    if(logsign){
                        logsign = false;
                        $("#LoginSignIn span").text("Log In?");
                        $("#signInCard").show();
                        $("#logInCard").hide();
                        console.log("login");
                    }else{
                        logsign = true;
                        $("#LoginSignIn span").text("Sign In?");
                        $("#signInCard").hide();
                        $("#logInCard").show();
                        console.log("signin");
                    }
                });
            });
        </script>
        
    <?php
    else :
    ?>

        <div class="row slcard">
            <div class="mx-auto card text-white bg-dark mb-3 col-lg" style="max-width: 18rem;">
                <h1 class="display-5">Info</h1>
                <div class="card-header"><?php print($_SESSION["nameAccount"]." ".$_SESSION["surnameAccount"]);?></div>
                    <div class="card-body">
                        <p class="card-title"><?php print("Birthday: ".$_SESSION["birthdateAccount"]);?></p>
                        <p class="card-text"><?php print("Gender: ".$_SESSION["genderAccount"]);?></p>
                        <p class="card-text"><?php print("Id: ".$_SESSION["loginAccount"]);?></p>
                        <p class="card-text"><?php print("Email: ".$_SESSION["emailAccount"]);?></p>
                        <form method="post"><button class="btn btn-raised btn-danger" name="logout" value=>Log out</button><form>
                    </div>
                </div>
                <div class="mx-auto card text-white bg-dark mb-3 col-lg" style="max-width: 25rem;">
                    <h1 class="display-5">Your Bookings</h1>
                    <ul class="list-group ">
                        <?php echo getBooks($db);?>
                    </ul>
                <script>
                        $("button").click(function(){
                            $("button").removeClass('active');
                            $(this).addClass('active');
                        }); 
                </script>
                </div>
                <div class="mx-auto card bg-dark mb-3 col-lg" style="max-width: 25rem;">
                    <h1 style="color:white;" class="display-5">Your Alert</h1>
                    <ul class="list-group">
                        <?php echo getAlerts($db);?>
                    </ul>		
                </div>
            </div>
        </div>

        <script>
            //move tabs center
            $('.slcard').css('padding-top', 50 + 'px');
            $('.slcard').css('padding-bottom', 100 + 'px');
        </script>

<?php
endif;


function getBooks($db){
    //Get all book from book table
    $return = "";
    $query = "SELECT tot, since, until , hotel.name as name FROM book JOIN hotel ON hotel.id=book.hotel WHERE account='$_SESSION[loginAccount]'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $return .= "<li class='btn list-group-item bg-dark text-white'><span class='badge badge-pill badge-secondary'>$row[since]</span><small>To</small><span class='badge badge-pill badge-secondary'>$row[until]</span><p class='lead float-right'>€ $row[tot]</p><p class='lead'>$row[name]</p></li>";
        }
    }
    return $return;
}

function getAlerts($db){
    //Get all book from book table
    $return = "";
    $query = "SELECT * FROM alert INNER JOIN alert_filter ON alert_filter.alert = alert.id WHERE account='$_SESSION[loginAccount]'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $return .= "<li class='btn list-group-item";
            if($row["success"]==1){ $return .= " bg-success";}else{ $return .= " bg-dark";} 
            $return .= " text-white'><a class='btn text-white' href='./index.php?viewAlert=$row[id]'><span class='badge badge-pill badge-secondary'>".$row["check-in"]."</span><small>To</small><span class='badge badge-pill badge-secondary'>".$row["check-out"]."</span><p class='extraSmall float-right'>".$row["location"]."</p><p class='miniSmall'> €".$row["priceMin"]."-".$row["priceMax"]."</p></a><form action='' method='POST'><input name='res' style='display: none;' readonly value='$row[id]'/><button name='delAlert' class='btn btn-danger'>X</button></form></li>";
        }
    }
    return $return;
}
?>





