<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Dashboard";
    $_SESSION['dashboardtab'] = "Active Items";
    
    // check if user is logged in or not
    if(!$_SESSION["email"]) {
        
        header("location:login.php");
        
        //make sure we exit to stop the script from processing any further
        exit();
        
    }
    
    // load users active items
    $itemsArray = array();
    $userEmail = $_SESSION["email"];
    $userQuery = "SELECT id FROM PedalDistrict.users WHERE email='$userEmail'";
    $userResult = $dbconnection->query($userQuery);
    $userId;
    
    if ($userResult->num_rows > 0) {
        
        $userData = $userResult->fetch_assoc();
        $userId = $userData["id"];
     
        // query for active items
        $activeItemsQuery = "SELECT * FROM PedalDistrict.products WHERE sellerId='$userId'";
        $activeItemsResult = $dbconnection->query($activeItemsQuery);
        
        if ($activeItemsResult->num_rows > 0) {
            
            // populate items array
            while ($row = $activeItemsResult->fetch_assoc()) {
                array_push($itemsArray, $row);
            }
            
        }
        
    }

?>

<!doctype HTML>
<html>
    
    <?php include("head.php");?>
    
    <body>
        
        <?php include("navigation.php");?>
        
        <div class="container page">
            
            <?php
            
                echo "<h2>Your Active Items</h2>";
                echo "<hr>";
                
                for ($i = 0; $i < count($itemsArray); $i++) {
                    
                    // item row
                    echo "<div class='row'>";
                    
                        // product image
                        echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                        
                            $coverImage = "images/" . $itemsArray[$i]["coverImage"];
                            echo "<img src='$coverImage' class='active-item-product-image center-block'>";
                        
                        echo "</div>";
                        // product details + action buttons
                        echo "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>";
                        
                            // item details
                            echo "<div class='row'>";
                            
                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                
                                    // product title
                                    echo "<h3>" . $itemsArray[$i]["title"] . "</h3>";
                                
                                echo "</div>";
                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                
                                    // product price
                                    echo "<h3>$" . $itemsArray[$i]["price"] . "</h3>";
                                
                                echo "</div>";
                            
                            echo "</div>";
                            
                            // action buttons
                            echo "<div class='row'>";
                            
                                echo "<div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>";
                                    echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"product-detail.php?id=".$itemsArray[$i]['id']."\";>View</a>";
                                echo "</div>";
                                echo "<div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>";
                                    echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"edit-item.php?id=".$itemsArray[$i]['id']."\";>Edit</a>";
                                echo "</div>";
                                echo "<div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>";
                                    echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"remove-item.php?productId=".$itemsArray[$i]['id']."\";>Remove</a>";
                                echo "</div>";
                            
                            echo "</div>";
                        
                        echo "</div>";
                    
                    echo "</div>";
                    
                    echo "<hr>";
                    
                }
            
            ?>
            
        </div>
        
        <?php include("footer.php"); ?>
        
    </body>
</html>