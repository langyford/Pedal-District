<?php

    include("dbconnection.php");
    
    $itemId = $_GET["id"];
    $userId = $_GET["userId"];
    
    $removeQuery = "DELETE FROM watchlist WHERE userId='$userId' AND itemId='$itemId'";
    $queryResult = $dbconnection->query($removeQuery);
    
    if ($queryResult) {
        
        // success, redirect to watchlist
        echo "<script>console.log('success');</script>";
        header("location: watchlist.php");
        
    }
    else {
        
        // failed, redirect to home page
        $sqlError = mysqli_error($dbconnection);
        
        header("location: index.php");
        
    }

?>