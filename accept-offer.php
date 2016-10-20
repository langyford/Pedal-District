<?php

    include("dbconnection.php");
    
    // get offer details
    $offerId = $_GET["offerId"];
    $offerQuery = "SELECT * FROM offers WHERE id='$offerId'";
    $queryResult = $dbconnection->query($offerQuery);
    
    $offer;
    if ($queryResult->num_rows == 1) {
        $offer = $queryResult->fetch_assoc();
    }
    
    $sellerId = $offer["sellerId"];
    $buyerId = $offer["buyerId"];
    $saleProductId = $offer["saleProductId"];
    $tradeProductId;
    if ($offer["tradeProductId"] != 0) {
        $tradeProductId = $offer["tradeProductId"];
    }
    $price = $offer["price"];
    $conditions = $offer["conditions"];
    
    // insert offer details into transactions table
    $insertQuery = "INSERT INTO `PedalDistrict`.`transactions` (`id`, `sellerId`, `buyerId`, `productId`, `tradeProductId`, `price`, `conditions`) 
                    VALUES (NULL, '$sellerId', '$buyerId', '$saleProductId', '$tradeProductId', '$price', '$conditions');";
    $queryResult = $dbconnection->query($insertQuery);
    
    if ($queryResult) {
        
        // offer details moved to transactions table
        
        // move products of transaction to soldItems table
        
        // insert sale product into sold items table
        // get sale product details
        $productQuery = "SELECT * FROM products WHERE id='$saleProductId'";
        $queryResult = $dbconnection->query($productQuery);
        
        $saleProduct;
        if ($queryResult->num_rows == 1) {
            $saleProduct = $queryResult->fetch_assoc();
        }
        
        $saleProductId = $saleProduct["id"];
        $title = $saleProduct["title"];
        $description = $saleProduct["description"];
        $coverImage = $saleProduct["coverImage"];
        $category = $saleProduct["category"];
        $subcategory = $saleProduct["subcategory"];
        
        $insertQuery = "INSERT INTO `PedalDistrict`.`soldItems` (`id`, `title`, `description`, `coverImage`, `category`, `subcategory`) 
        VALUES ('$saleProductId', '$title', '$description', '$coverImage', '$category', '$subcategory');";
        $queryResult = $dbconnection->query($insertQuery);
        
        if ($queryResult) {
            
            // inserted successfully
            // insert trade product if one exists
            if ($tradeProductId != 0) {
                
                // get trade product details
                $productQuery = "SELECT * FROM products WHERE id='$tradeProductId'";
                $queryResult = $dbconnection->query($productQuery);
                
                $tradeProduct;
                if ($queryResult->num_rows == 1) {
                    $tradeProduct = $queryResult->fetch_assoc();
                }
                
                $tradeProductId = $tradeProduct["id"];
                $title = $tradeProduct["title"];
                $description = $tradeProduct["description"];
                $coverImage = $tradeProduct["coverImage"];
                $category = $tradeProduct["category"];
                $subcategory = $tradeProduct["subcategory"];
                
                $insertQuery = "INSERT INTO `PedalDistrict`.`soldItems` (`id`, `title`, `description`, `coverImage`, `category`, `subcategory`) 
                VALUES ('$tradeProductId', '$title', '$description', '$coverImage', '$category', '$subcategory');";
                $queryResult = $dbconnection->query($insertQuery);
                
            }
            
            // everything inserted, remove sold products from products table
            $offerQuery = "SELECT * FROM offers WHERE id='$offerId'";
            $queryResult = $dbconnection->query($offerQuery);
            
            $offer;
            if ($queryResult->num_rows == 1) {
                $offer = $queryResult->fetch_assoc();
            }
            
            // remove sale product
            $saleProductId = $offer["saleProductId"];
            $deleteQuery = "DELETE FROM products WHERE id='$saleProductId'";
            $queryResult = $dbconnection->query($deleteQuery);
            
            if ($queryResult) {
                
                $tradeProductId = $offer["tradeProductId"];
                if ($tradeProductId != 0) {
                    
                    // remove trade product
                    $tradeProductId = $offer["tradeProductId"];
                    $deleteQuery = "DELETE FROM products WHERE id='$tradeProductId'";
                    $queryResult = $dbconnection->query($deleteQuery);
                    
                }
                
                // delete offer from offers table
                $offerId = $offer["id"];
                $deleteQuery = "DELETE FROM offers WHERE id='$offerId'";
                $queryResult = $dbconnection->query($deleteQuery);
                
                if ($queryResult) {
                    // everything deleted, redirect to transaction history page
                    header("location: transaction-history.php");
                }
                
            }
                    
        }
        
    }                

?>