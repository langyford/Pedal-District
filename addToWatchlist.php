<?php

    include("dbconnection.php");
    
    // make sure user is logged in
    if ($_GET["userId"] != "") {
    
        $userId = $_GET["userId"];
        $productId = $_GET["productId"];
        
        $insertQuery = "INSERT INTO `PedalDistrict`.`watchlist` (`id`, `itemId`, `userId`) VALUES (NULL, '$productId', '$userId');";
        $insertResult = $dbconnection->query($insertQuery);
        
        $success = array();
        if ($insertResult) {
        
            // success, redirect to watchlist.php
            header("location: watchlist.php");
            
        }
        else {
            
            $success["query"] = mysqli_error($dbconnection);
            echo $success["query"];
            header("location: index.php");
            
        }
    
    }
    else {
        
        echo "not logged in";
        header("location: login.php");
        
    }

?>