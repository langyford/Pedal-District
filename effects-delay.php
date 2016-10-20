<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Delay";
    
    // get delay items from effects table in db
    $productQuery = "SELECT * FROM PedalDistrict.products WHERE subcategory = 'Delay';";
    $productQueryResults = $dbconnection->query($productQuery);
    
    $productsArray = array();
    
    if ($productQueryResults->num_rows > 0) {

        while($row = $productQueryResults->fetch_assoc()) {
            
            array_push($productsArray, $row);
            
        }
    
    }
    else {
        
        // didn't find any items in db table
        
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
            
            <?php
                
                echo "<h2>Delay Pedals</h2>";
                echo "<br>";
                
                $numItems = count($productsArray);
                $numRows = ceil($numItems / 3);
                $temp = 0;
                
                for ($i = 0; $i < $numRows; $i++) {
                    
                    echo "<div class='row'>";
                    
                        $numCols = $numItems - ($temp);
                        
                        
                        for ($p = 0; $p < $numCols && $p < 3; $p++) {
                            
                            echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                        
                                // product image
                                echo "<div class='centerBlock'>";
                                    echo "<img src='" . "images/" . $productsArray[$temp]['coverImage'] . "' class='home-product-image' id='new-product-image'>";
                                echo "</div>";
                                
                                // product title
                                echo "<h4>".$productsArray[$temp]['title']."</h4>";
                                
                                // product price
                                echo "<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>";
                                    if ($productsArray[$temp]["tradable"] != 0) {
                                        echo "<h4> $".$productsArray[$temp]['price']." or trade"."</h4>";
                                    }
                                    else {
                                        echo "<h4> $".$productsArray[$temp]['price']."</h4>";
                                    }
                                echo "</div>";
                                
                                // view button
                                echo "<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>";
                                    echo "<a class=\"btn btn-default pull-right\" href=\"product-detail.php?id=".$productsArray[$temp]['id']."\";>View</a>";
                                echo "</div>";
                            
                            echo "</div>";
                            
                            $temp++;
                            
                        }
                    
                    echo "</div>";
                    
                }
            
            ?>
                
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>