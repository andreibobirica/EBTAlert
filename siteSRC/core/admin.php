<?php
include_once "Database.php";
$db = new Database();

if(isset($_POST["delUser"])){
    //Delete User
    $query="DELETE FROM account WHERE account.id = '$_POST[delUser]'";
    $result = $db->query($query);
}

if(isset($_POST["saveUser"])){
    $query="UPDATE account SET email = '$_POST[email]', pass = '$_POST[pass]' WHERE account.id = '$_POST[saveUser]'";
    $result = $db->query($query);
    $query="UPDATE user SET name = '$_POST[name]',surname = '$_POST[surname]',birthdate = '$_POST[birthdate]',sex = '$_POST[sex]' WHERE user.account = '$_POST[saveUser]'";
    $result = $db->query($query);
}

if(isset($_POST["saveProp"])){
    $query="UPDATE account SET email = '$_POST[email]', pass = '$_POST[pass]' WHERE account.id = '$_POST[saveProp]'";
    $result = $db->query($query);
    $query="UPDATE user SET name = '$_POST[uname]',surname = '$_POST[surname]',birthdate = '$_POST[birthdate]',sex = '$_POST[sex]' WHERE user.account = '$_POST[saveProp]'";
    $result = $db->query($query);
    $query="UPDATE prop SET company = '$_POST[company]' WHERE prop.account = '$_POST[saveProp]'";
    $result = $db->query($query);
    $query="UPDATE location SET lati = '$_POST[lati]',longi = '$_POST[longi]', street = '$_POST[street]', mun = '$_POST[mun]',state = '$_POST[state]'  WHERE id = '$_POST[locationId]'";
    $result = $db->query($query);
    $query="UPDATE hotelchain SET name = '$_POST[hcname]', img = '$_POST[hcimg]' WHERE prop = '$_POST[saveProp]'";
    $result = $db->query($query);
}

if(isset($_POST["insertProp"])){
    $query="INSERT INTO account (email,pass) VALUES ('$_POST[email]','$_POST[pass]')";
    $result = $db->query($query);
    //Geting Info About Room_detail
    $query = "SELECT LAST_INSERT_ID() AS id;";
    $result = $db->query($query);
    $account = "";
    if ($result->num_rows == 1) {
        $account = $result->fetch_assoc()["id"];
    }
    $query="INSERT INTO user (name,surname,birthdate,sex,account) VALUES ('$_POST[uname]','$_POST[surname]','$_POST[birthdate]','$_POST[sex]','$account')";
    $result = $db->query($query);
    $query="INSERT INTO location (lati,longi,street,mun,state) VALUES ('$_POST[lati]','$_POST[longi]','$_POST[street]','$_POST[mun]','$_POST[state]')";
    $result = $db->query($query);

    $query = "SELECT LAST_INSERT_ID() AS id;";
    $result = $db->query($query);
    $locationId = "";
    if ($result->num_rows == 1) {
        $locationId = $result->fetch_assoc()["id"];
    }
    $query="INSERT INTO prop (company,account,locationProp) VALUES ('$_POST[company]','$account','$locationId')";
    $result = $db->query($query);
    
    echo $query;
    
    $query="INSERT INTO hotelchain (name,img,prop) VALUES ('$_POST[hcname]','$_POST[hcimg]','$account')";
    $result = $db->query($query);
}




?>
<html>
<body id="body">
<h1>Admin Table</h1>
<hr/>

<h3>User Account</h3>
<table border='1'>
<tr><th>Email</th><th>Password</th><th>Name</th><th>Surname</th><th>birthdate</th><th>Sex</th></tr>
<?php
    $query="SELECT * FROM account INNER JOIN user ON account.id = user.account";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row= $result->fetch_assoc()){
            print("<form action='' method='POST'><tr>
            <td><input type='text' name='email' value='$row[email]'/></td>
            <td><input type='text' name='pass' value='$row[pass]'/></td>
            <td><input type='text' name='name' value='$row[name]'/></td>
            <td><input type='text' name='surname' value='$row[surname]'/></td>
            <td><input type='text' name='birthdate' value='$row[birthdate]'/></td>
            <td><input type='text' name='sex' value='$row[sex]'/></td>
            <td><button type='submit' name='delUser' value='$row[id]' color='red'>X</button></td>
            <td><button type='submit' name='saveUser' value='$row[account]' color='red'>Save</button></td>
            </tr></form>");
        }
    }
?>
</table>
<h3>User Prop</h3>
<table border='1'>
<tr><th>Email</th><th>Password</th><th>Name</th><th>Surname</th><th>birthdate</th><th>Sex</th><th>Company</th><th>Lat</th><th>Long</th><th>Street</th><th>Mun</th><th>State</th><th>Hotel Chain Name</th><th>Hotel Chain IMG Link</th></tr>
<?php
    $query="SELECT location.id as locationId, account.id as account,email,pass,user.name as uname, surname, birthdate, sex,company,lati,longi,street,mun,state,hotelchain.name as hcname, hotelchain.img as hcimg FROM account INNER JOIN user ON account.id = user.account INNER JOIN prop ON account.id = prop.account INNER JOIN location ON prop.locationProp = location.id INNER JOIN hotelchain ON hotelchain.prop = prop.account";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row= $result->fetch_assoc()){
            print("<form action='' method='POST'><tr>
            <td><input type='text' name='email' value='$row[email]'/><input type='hidden' name='locationId' value='$row[locationId]'/></td>
            <td><input type='text' name='pass' value='$row[pass]'/></td>
            <td><input type='text' name='uname' value='$row[uname]'/></td>
            <td><input type='text' name='surname' value='$row[surname]'/></td>
            <td><input type='text' name='birthdate' value='$row[birthdate]'/></td>
            <td><input type='text' name='sex' value='$row[sex]'/></td>
            <td><input type='text' name='company' value='$row[company]'/></td>
            <td><input type='text' name='lati' value='$row[lati]'/></td>
            <td><input type='text' name='longi' value='$row[longi]'/></td>
            <td><input type='text' name='street' value='$row[street]'/></td>
            <td><input type='text' name='mun' value='$row[mun]'/></td>
            <td><input type='text' name='state' value='$row[state]'/></td>
            <td><input type='text' name='hcname' value='$row[hcname]'/></td>
            <td><input type='text' name='hcimg' value='$row[hcimg]'/></td>
            <td><button type='submit' name='delProp' value='$row[account]' style='color:red;'>X</button></td>
            <td><button type='submit' name='saveProp' value='$row[account]' style='color:blue;'>Save</button></td>
            </tr></form>");
        }
    }
    print("<form action='' method='POST'><tr>
            <td><input type='text' name='email' /></td>
            <td><input type='text' name='pass' /></td>
            <td><input type='text' name='uname' /></td>
            <td><input type='text' name='surname' /></td>
            <td><input type='text' name='birthdate' value='1970-12-31' /></td>
            <td><input type='text' name='sex' /></td>
            <td><input type='text' name='company' /></td>
            <td><input type='text' name='lati' /></td>
            <td><input type='text' name='longi' /></td>
            <td><input type='text' name='street' '/></td>
            <td><input type='text' name='mun' /></td>
            <td><input type='text' name='state' /></td>
            <td><input type='text' name='hcname' /></td>
            <td><input type='text' name='hcimg' /></td>
            <td colspan='2'><button type='submit' name='insertProp'  style='color:green;'>Insert</button></td>
            </tr></form>");
?>
</table>

<script type="text/javascript">
    function show_prompt() {
        var name = prompt('Please enter your name','Poppy');
        if (name != null && name != "") {
            alert(name);
        }
    }
</script>
</body>
</html>
<script type="text/javascript">
    disableForm()
    var auth = getCookie("auth");
    if(!auth)
    show_prompt();
    else
    enableForm();
    function show_prompt() {
        var pass = prompt('Please enter password:');
        if (pass!="123456788") {
            show_prompt();
        }else{
            enableForm();
            setCookie('auth',true,1);
        }
    }

    function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    function eraseCookie(name) {   
        document.cookie = name+'=; Max-Age=-99999999;';  
    }

    function disableForm() {
        var inputs = document.getElementsByTagName("input");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = true;
        }
        var selects = document.getElementsByTagName("select");
        for (var i = 0; i < selects.length; i++) {
            selects[i].disabled = true;
        }
        var textareas = document.getElementsByTagName("textarea");
        for (var i = 0; i < textareas.length; i++) {
            textareas[i].disabled = true;
        }
        var buttons = document.getElementsByTagName("button");
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].disabled = true;
        }
    }

    function enableForm() {
        var inputs = document.getElementsByTagName("input");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = false;
        }
        var selects = document.getElementsByTagName("select");
        for (var i = 0; i < selects.length; i++) {
            selects[i].disabled = false;
        }
        var textareas = document.getElementsByTagName("textarea");
        for (var i = 0; i < textareas.length; i++) {
            textareas[i].disabled = false;
        }
        var buttons = document.getElementsByTagName("button");
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].disabled = false;
        }
    }
</script>
