<?php
//Database
include_once "../../core/Database.php";
$db = new Database();

if(isset($_GET["room"])){
    $roomImgs = array();
    $query = "SELECT * FROM room_img where room = $_GET[room]";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            array_push($roomImgs,$row["img"]);
        }
    }

    print(json_encode($roomImgs));
}

?>