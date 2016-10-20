<?php

    include("dbconnection.php");

    // get product information
    $productId = $_GET["productId"];
    
    $deleteQuery = "DELETE FROM products WHERE id='$productId'";
    $queryResult = $dbconnection->query($deleteQuery);
    
    if ($queryResult) {
        
        // redirect to active items php
        header("location: user-dashboard-activeitems.php");
        
    }
    else {
        header("location: index.php");
    }

?>