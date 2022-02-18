<div class='container'>
    <div class='row'> 
        <?php
            //GET hotelChains
            $query = "SELECT * FROM hotelchain;";
            $db->query($query);
            $result = $db->query($query);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    print("
                        <div class='card col-md-3 col-centered mt-4' style='width: 12rem;'>
                        <a href='./index.php?search&hc=$row[id]&hcname=$row[name]' class='btn'><img src='$row[img]' class='card-img-top' alt=''>
                        <div class='card-body'>
                            <p class='card-text'>$row[name]</p>
                        </div></a>
                        </div>
                    ");
                }
            }
        ?>
    </div>
</div>