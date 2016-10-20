<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Watchlist";
    
    // make sure user is logged in
    if (!$_SESSION["email"]) {
        
        header("location: login.php");
        exit();
        
    }
    
    // get current user details
    $userEmail = $_SESSION["email"];
    $userQuery = "SELECT * FROM users WHERE email='$userEmail'";
    $queryResult = $dbconnection->query($userQuery);
    
    $user;
    $userId;
    if ($queryResult->num_rows == 1) {
        
        $user = $queryResult->fetch_assoc();
        $userId = $user["id"];
        
    }
    
    // load all items in users watchlist from watchlist table
    $watchlistQuery = "SELECT * FROM watchlist WHERE userId='$userId'";
    $queryResult = $dbconnection->query($watchlistQuery);
    
    $watchlist = array();
    $products = array();
    if ($queryResult->num_rows > 0) {
        
        while ($row = $queryResult->fetch_assoc()) {
            array_push($watchlist, $row);
        }
        
        // for each row in watchlist array
        for ($i = 0; $i < count($watchlist); $i++) {
            
            // get product
            $productId = $watchlist[$i]["itemId"];
            $productQuery = "SELECT * FROM products WHERE id='$productId'";
            $queryResult = $dbconnection->query($productQuery);
            
            if ($queryResult->num_rows > 0) {
                
                $row = $queryResult->fetch_assoc();
                array_push($products, $row);
                
            }
            
        }
        
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
                
            <h2>Your Watchlist</h2>
            
            <?php
            
                if (count($watchlist) > 0) {
                    
                    // display each item in watchlist array
                     for ($i = 0; $i < count($watchlist); $i++) {
                    
                        // item row
                        echo "<div class='row'>";
                        
                            // product image
                            echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                            
                                $coverImage = "images/" . $products[$i]["coverImage"];
                                echo "<img src='$coverImage' class='active-item-product-image center-block'>";
                            
                            echo "</div>";
                            // product details + action buttons
                            echo "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>";
                            
                                // item details
                                echo "<div class='row'>";
                                
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        // product title
                                        echo "<h3>" . $products[$i]["title"] . "</h3>";
                                    
                                    echo "</div>";
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        // product price
                                        echo "<h3>$" . $products[$i]["price"] . "</h3>";
                                    
                                    echo "</div>";
                                
                                echo "</div>";
                                
                                // action buttons
                                echo "<div class='row'>";
                                
                                    echo "<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>";
                                        echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"product-detail.php?id=".$products[$i]['id']."\";>View</a>";
                                    echo "</div>";
                                    echo "<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>";
                                        echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"remove-item-watchlist.php?id=".$products[$i]['id']."&userId=" .$userId."\";>Remove</a>";
                                    echo "</div>";
                                
                                echo "</div>";
                            
                            echo "</div>";
                        
                        echo "</div>";
                        
                        echo "<hr>";
                        
                    }
                    
                }
                else {
                    echo "<h4 style='color: grey'>no items in your watchlist</h4>";
                }
            
            ?>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>