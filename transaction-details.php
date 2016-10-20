<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Transaction Details";
    
    $transactionId = $_GET["transactionId"];
    
    // get transaction details
    $transQuery = "SELECT * FROM transactions WHERE id='$transactionId'";
    $queryResult = $dbconnection->query($transQuery);
    
    $transaction;
    if ($queryResult->num_rows == 1) {
        
        $transaction = $queryResult->fetch_assoc();
        
    }
    
    // get sell item details
    $sellItem;
    $sellItemId = $transaction["productId"];
    $sellQuery = "SELECT * FROM soldItems WHERE id='$sellItemId'";
    $queryResult = $dbconnection->query($sellQuery);
    
    if ($queryResult->num_rows == 1) {
        
        $sellItem = $queryResult->fetch_assoc();
        
    }
    
    // get tradeItem if any
    $tradeProduct;
    if ($transaction["tradeProductId"] != 0) {
        
        $tradeId = $transaction["tradeProductId"];
        $tradeQuery = "SELECT * FROM soldItems WHERE id='$tradeId'";
        $queryResult = $dbconnection->query($tradeQuery);
        
        if ($queryResult->num_rows == 1) {
            
            $tradeProduct = $queryResult->fetch_assoc();
            
        }
        
    }
    
    // get buyers information
    $buyer;
    $buyerId = $transaction["buyerId"];
    $buyerQuery = "SELECT * FROM users WHERE id='$buyerId'";
    $queryResult = $dbconnection->query($buyerQuery);
    
    if ($queryResult->num_rows == 1) {
        
        $buyer = $queryResult->fetch_assoc();
        
    }
    
?>

<!doctype HTML>
<html>
    
    <head>
        
        <?php include("head.php"); ?>
        
    </head>
    
    <body>
        
        <?php include("navigation.php"); ?>
        
        <div class="container page">
        
            <h2>Transaction Details</h2>
        
            <h2>Item Sold</h2>
            <?php 
            
                // item box
                echo "<div class='row'>";
                    
                    // image
                    echo "<div class='col-xs-12 col-sm-12 col-md-2 col-lg-2'>";
                        echo "<div class='offer-item-image centerBlock'>";
                            $coverImage = "images/" . $sellItem["coverImage"];
                            echo "<img src='$coverImage' class='img-responsive'>";
                        echo "</div>";
                    echo "</div>";
                    
                    // item info
                    echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                    
                        // title
                        echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                        
                            echo "<div class='col-xs-12 col-sm-12 col-md-8 col-lg-8'>";
                                echo "<h3>" . $sellItem['title'] . "</h3>";
                            echo "</div>";
                            
                        echo "</div>";
                    
                    echo "</div>";

                echo "</div>";
            

                if ($transaction["tradeProductId"] != 0) {
                    
                    echo "<h2>Trade Product</h2>";
                    
                    // item box
                    echo "<div class='row'>";
                        
                        // image
                        echo "<div class='col-xs-12 col-sm-12 col-md-2 col-lg-2'>";
                            echo "<div class='offer-item-image centerBlock'>";
                                $coverImage = "images/" . $tradeProduct["coverImage"];
                                echo "<img src='$coverImage' class='img-responsive'>";
                            echo "</div>";
                        echo "</div>";
                        
                        // item info
                        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                        
                            // title
                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                            
                                echo "<div class='col-xs-12 col-sm-12 col-md-8 col-lg-8'>";
                                    echo "<h3>" . $tradeProduct['title'] . "</h3>";
                                echo "</div>";
                                
                            echo "</div>";
                        
                        echo "</div>";
    
                    echo "</div>";
                        
                } 

            ?>
            
            <hr>
            
            <h2>Buyers Information</h2>
            <?php
            
                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                    echo "<h4>name: " . $buyer["name"] . "</h4>";
                    echo "<h4>email: " . $buyer["email"] . "</h4>";
                    echo "<h4>phone: " . $buyer["phonenumber"] . "</h4>";
                    echo "<h4>locality: " . $buyer["suburb"] . ", " . $buyer["city"] . ", " . $buyer["postcode"] . "</h4>";
                echo "</div>";
                
            ?>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>