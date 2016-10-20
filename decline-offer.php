<?php

    include("dbconnection.php");
    
    $offerId = $_GET["offerId"];
    
    $deleteQuery = "DELETE FROM offers WHERE id='$offerId'";
    $queryResult = $dbconnection->query($deleteQuery);
    
    if ($queryResult) {
        
        // deleted offer from table, redirect to offers page
        header("location: offers.php");
        
    }
    else {
        
        // failed, redirect to home page
        echo "<script>alert('failed to decline offer');</script>";
        header("location: index.php");
        
    }

?>