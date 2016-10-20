<?php

    include("dbconnection.php");
    
    // get input from user
    $itemId = $_POST["itemId"];
    $title = $_POST["title"];
    $category = $_POST["category"];
    $subcategory = $_POST["subcategory"];
    $price = $_POST["price"];
    $tradable = $_POST["tradable"];
    $description = $_POST["description"];
    $shippingOptions = $_POST["shippingOptions"];
    //set reply array
    $success = array();
    $updateQuery = "UPDATE products SET title='$title', category='$category', subcategory='$subcategory', price='$price', tradable='$tradable', description='$description', shippingOptions='$shippingOptions' WHERE id='$itemId'";
    $updateQueryResult = $dbconnection->query($updateQuery);
    
    $success["sqlError"] = mysqli_error($dbconnection);
    
    if ($updateQueryResult) {
        
        $success["query"] = $updateQueryResult;
        $success["success"]=true;
        
    }
    else {
        $success["success"]=false;
    }
    echo json_encode($_POST);

?>