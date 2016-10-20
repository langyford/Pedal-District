<?php

    session_start();
    include("dbconnection.php");
    
    // make sure user is logged in
    if (!$_SESSION["email"]) {
        
        header("location: login.php");
        exit();
        
    }
    
    $_SESSION['currentpage'] = "Offer Details";
    
    // get current user details
    $userEmail = $_SESSION['email'];
    $userQuery = "SELECT * FROM users WHERE email='$userEmail';";
    $userResult = $dbconnection->query($userQuery);
    
    $user;
    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
    }
    
    // get the offer details from the offer table
    $offerId = $_GET["id"];
    $offerQuery = "SELECT * FROM offers WHERE id='$offerId'";
    $queryResult = $dbconnection->query($offerQuery);
    
    $offer;
    if ($queryResult->num_rows == 1) {
        $offer = $queryResult->fetch_assoc();
    }
    
    // product for sale
    $saleProductId = $offer["saleProductId"];
    $productQuery = "SELECT * FROM products WHERE id='$saleProductId'";
    $queryResult = $dbconnection->query($productQuery);
    
    $saleProduct;
    if ($queryResult->num_rows == 1) {
        $saleProduct = $queryResult->fetch_assoc();
    }
    
    // product for trade (if applicable)
    $tradeProductId = $offer["tradeProductId"];
    $productQuery = "SELECT * FROM products WHERE id='$tradeProductId'";
    $queryResult = $dbconnection->query($productQuery);
    
    $tradeProduct;
    if ($queryResult->num_rows == 1) {
        $tradeProduct = $queryResult->fetch_assoc();
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
            
            <h2>Product for Sale</h2>
            
            <?php
                
                // product for sale box
                // item row
                echo "<div class='row'>";
                
                    echo "<hr>";
                
                    // product image
                    echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                        $coverImage = "images/" . $saleProduct["coverImage"];
                        echo "<img src='$coverImage' class='active-item-product-image center-block'>";
                    echo "</div>";
                    // product details
                    echo "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>";
                    
                        // item details
                        echo "<div class='row'>";
                        
                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                            
                                // product title
                                echo "<h3>" . $saleProduct["title"] . "</h3>";
                            
                            echo "</div>";
                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                            
                                // product price and if is tradable
                                if ($saleProduct["tradable"] == 1) {
                                    
                                    echo "<h3>$" . $saleProduct["price"] . " or trade offer" . "</h3>";
                                    
                                }
                                else {
                                    echo "<h3>$" . $saleProduct["price"] . "</h3>";   
                                }
                            
                            echo "</div>";
                        
                        echo "</div>";
                    
                    echo "</div>";
                
                echo "</div>";
                
                echo "<hr>";
                
                if ($offer["tradeProductId"] != 0) {
                    
                    // show trade offer product
                    
                    echo "<h2>Product for Trade</h2>";
                    
                    // product for sale box
                    // item row
                    echo "<div class='row'>";
                    
                        echo "<hr>";
                    
                        // product image
                        echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                            $coverImage = "images/" . $tradeProduct["coverImage"];
                            echo "<img src='$coverImage' class='active-item-product-image center-block'>";
                        echo "</div>";
                        // product details
                        echo "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>";
                        
                            // item details
                            echo "<div class='row'>";
                            
                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                
                                    // product title
                                    echo "<h3>" . $tradeProduct["title"] . "</h3>";
                                
                                echo "</div>";
                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                
                                    // product price
                                    echo "<h3>$" . $tradeProduct["price"] . "</h3>";
                                
                                echo "</div>";
                            
                            echo "</div>";
                        
                        echo "</div>";
                    
                    echo "</div>";
                    
                    echo "<hr>";
                    
                }
            
            ?>
            
            <?php
            
                echo "<div class='row'>";
                
                    echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
                        
                        echo "<h2>Offer Details</h2>";
                    
                        echo "<h4>Status: " . $offer["status"] . "</h4>";
                        echo "<h4>Offer Date: " . $offer["date"] . "</h4>";
                        echo "<br>";
                        echo "<h4>Offer price: $" . $offer["price"] . "</h4>";
                        echo "<h4>Notes / Conditions of Trade: </h4>";
                        echo "<textarea rows='4' cols='50' readonly>" . $offer["conditions"] . "</textarea>";
                    echo "</div>";
                    
                    echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
                    
                        if ($offer["sellerId"] == $user["id"]) {
                        
                            // seller is viewing the offer
                            
                            // show buyer's information
                            $buyerId = $offer["buyerId"];
                            $buyerQuery = "SELECT * FROM users WHERE id='$buyerId'";
                            $queryResult = $dbconnection->query($buyerQuery);
                            
                            $buyer;
                            if ($queryResult->num_rows == 1) {
                                
                                $buyer = $queryResult->fetch_assoc();
                                
                                echo "<h2>Buyer's Details</h2>";
                                
                                echo "<h4>name: " . $buyer["name"] . "</h4>";
                                echo "<h4>email: " . $buyer["email"] . "</h4>";
                                echo "<h4>phone: " . $buyer["phonenumber"] . "</h4>";
                                echo "<h4>locality: " . $buyer["suburb"] . ", " . $buyer["city"] . ", " . $buyer["postcode"] . "</h4>";
                                echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"new-message.php?id="."\";>Message Buyer</a>";
                                
                            }
                        
                        }
                        else if ($offer["buyerId"] == $user["id"]) {
                            
                            // buyer is viewing the offer
                            
                            // show seller's information
                            $sellerId = $offer["sellerId"];
                            $sellerQuery = "SELECT * FROM users WHERE id='$sellerId'";
                            $queryResult = $dbconnection->query($sellerQuery);
                            
                            $seller;
                            if ($queryResult->num_rows == 1) {
                                
                                $seller = $queryResult->fetch_assoc();
                                
                                echo "<h2>Seller's Details</h2>";
                                
                                echo "<h4>name: " . $seller["name"] . "</h4>";
                                echo "<h4>email: " . $seller["email"] . "</h4>";
                                echo "<h4>phone: " . $seller["phonenumber"] . "</h4>";
                                echo "<h4>locality: " . $seller["suburb"] . ", " . $seller["city"] . ", " . $seller["postcode"] . "</h4>";
                                echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"new-message.php?id="."\";>Message Seller</a>";
                                
                            }
                            
                        }
                    
                    echo "</div>";
                echo "</div>";
                
                echo "<div class='row'>";
                
                    if ($offer["sellerId"] == $user["id"]) {
                        
                        // show buttons for accepting or declining the offer
                        echo "<hr>";
                        // accept offer button
                        echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
                            echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"accept-offer.php?offerId=" . $offer["id"] ."\";>Accept Offer</a>";
                        echo "</div>";
                        
                        // decline offer button
                        echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
                            echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"decline-offer.php?offerId=" . $offer["id"] ."\";>Decline Offer</a>";
                        echo "</div>";
                        
                    }
                    
                echo "</div>";
            
            ?>
                
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>