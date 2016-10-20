<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Search";
    
    // get the search input
    $searchInput = $_GET["search"];
    $searchQuery = "SELECT * FROM products 
                    WHERE title LIKE '%$searchInput%'
                    OR category LIKE '%$searchInput%'
                    OR subcategory LIKE '%$searchInput%'";
    $queryResult = $dbconnection->query($searchQuery);
    
    $searchProducts = array();
    if ($queryResult->num_rows > 0) {
        
        // found search results
        while ($row = $queryResult->fetch_assoc()) {
            array_push($searchProducts, $row);
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
                
            <h2>Search Results</h2>
            <hr>
            
            <?php
            
                if (count($searchProducts) > 0) {
                    
                    // array not empty, show searched products
                    for ($i = 0; $i < count($searchProducts); $i++) {
                    
                        // item row
                        echo "<div class='row'>";
                        
                            // product image
                            echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                            
                                $coverImage = "images/" . $searchProducts[$i]["coverImage"];
                                echo "<img src='$coverImage' class='active-item-product-image center-block'>";
                            
                            echo "</div>";
                            // product details + action buttons
                            echo "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>";
                            
                                // item details
                                echo "<div class='row'>";
                                
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        // product title
                                        echo "<h3>" . $searchProducts[$i]["title"] . "</h3>";
                                    
                                    echo "</div>";
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        // product price
                                    
                                        if ($searchProducts[$i]["tradable"] == 1) {
                                            echo "<h3>$" . $searchProducts[$i]["price"] . " or trade offer" . "</h3>";
                                        }
                                        else {
                                            echo "<h3>$" . $searchProducts[$i]["price"] . "</h3>";
                                        }
                                    
                                    echo "</div>";
                                
                                echo "</div>";
                                
                                // action buttons
                                echo "<div class='row'>";
                                
                                    echo "<div class='col-xs-4 col-sm-4 col-md-12 col-lg-12'>";
                                        echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"product-detail.php?id=".$searchProducts[$i]['id']."\";>View</a>";
                                    echo "</div>";
                                
                                echo "</div>";
                            
                            echo "</div>";
                        
                        echo "</div>";
                        
                        echo "<hr>";
                    
                    }
                    
                }
                else {
                    
                    // array empty, show no results text
                    echo "<h3 style='color: grey'>no results found</h3>";
                    
                }
            
            ?>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>