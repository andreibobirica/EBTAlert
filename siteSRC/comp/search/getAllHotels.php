<?php
//Database
include_once "../../core/Database.php";
$db = new Database();


$hotelsAll = array();
//Finding Hotels in coordinates by searching
$query = "SELECT * FROM location INNER JOIN hotel ON hotel.location = location.id";
$result = $db->query($query);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        array_push($hotelsAll,$row);
    }
}


print(json_encode($hotelsAll));


?>