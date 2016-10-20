<?php

    session_start();
    include("dbconnection.php");
    
    // make sure user is logged in
    if (!$_SESSION["email"]) {
        
        header("location: login.php");
        exit();
        
    }
    
    $_SESSION['currentpage'] = "Make Offer";
    
    // get product for sale
    $saleProductId = $_GET["productId"];   
    $saleProductQuery = "SELECT * FROM products WHERE id='$saleProductId'";
    $saleProductResult = $dbconnection->query($saleProductQuery);
    
    $saleProduct;
    if ($saleProductResult->num_rows == 1) {
        
        // found the product
        $saleProduct = $saleProductResult->fetch_assoc();
        
    }
    
    // if sale product is tradable, get all of buyers active items
    $buyerEmail = $_SESSION["email"];
    $buyerQuery = "SELECT * FROM users WHERE email='$buyerEmail'";
    $buyerResult = $dbconnection->query($buyerQuery);
    
    $buyer;
    if ($buyerResult->num_rows == 1) {
        
        // found the buyer
        $buyer = $buyerResult->fetch_assoc();
        
    }
    $buyerId = $buyer["id"];
    $_SESSION["currentProductBuyerId"] = $buyerId;
    
    $activeItemsArray = array();
    $activeItemsQuery = "SELECT * FROM products WHERE sellerId='$buyerId'";
    $activeItemsResult = $dbconnection->query($activeItemsQuery);
    
    if ($activeItemsResult->num_rows > 0) {
        
        // load each item into the array
        while ($row = $activeItemsResult->fetch_assoc()) {
            array_push($activeItemsArray, $row);
        }
        
    }
    
    // on form submit
    if (isset($_POST["submit"]) && count($_POST["submit"]) > 0) {
        
        // get all input values
        $saleProductId = $_SESSION["saleProductId"];
        $buyerId = $_SESSION["currentProductBuyerId"];
        if ($_POST["optradio"] == "empty") {
            $tradeProductId = null;
        }
        else {
            $tradeProductId = $_POST["optradio"];
        }
        
        $sellerId = $_SESSION["sellerId"];
        $status = "pending";
        $dateArray = getdate();
        $dateFormat = $dateArray["weekday"] . ", " . $dateArray["mday"] . "/" . $dateArray["mon"] . "/" . $dateArray["year"];
        $conditions = $_POST["description"];
        $price = $_POST["price"];
        
        // insert the offer data into the offers db table
        $offerInsertQuery = "INSERT INTO offers (`saleProductId`, `tradeProductId`, `sellerId`, `buyerId`, `status`, `date`, `conditions`, `price`) 
                            VALUES ('$saleProductId', '$tradeProductId', '$sellerId', '$buyerId', '$status', '$dateFormat', '$conditions', '$price');";
                            
        if ($dbconnection->query($offerInsertQuery)) {
            
            // success; redirect to your offers page
            header("location: offers.php");
            
        }
        else {
            
            // failed; alert user
            // testing
            $sqlErrorMsg = mysqli_error($dbconnection);
            echo "<script>console.log('failed query: $sqlErrorMsg');</script>";
            
        }
        
    }
    else {
        
        $_SESSION["saleProductId"] = $_GET["productId"];
        $saleProductId = $_SESSION["saleProductId"];
        $saleProductQuery = "SELECT * FROM products WHERE id='$saleProductId'";
        $saleProductResult = $dbconnection->query($saleProductQuery);
        
        $saleProduct;
        if ($saleProductResult->num_rows == 1) {
            
            // found the product
            $saleProduct = $saleProductResult->fetch_assoc();
            
            $_SESSION["sellerId"] = $saleProduct["sellerId"];
            
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
            
            <h2>Make a Offer</h2>
            
            <div class="row">
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3>Offer for</h3>
                </div>
                <!--sale product box-->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    
                    <?php
                        
                        // product image
                        echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>";
                        
                            echo "<div class='offer-item-image centerBlock'>";
                                $coverImage = "images/" . $saleProduct["coverImage"];
                                echo "<img src='$coverImage' class='img-responsive'>";
                            echo "</div>";
                        
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
                                
                                    if ($saleProduct["tradable"] == 1) { 
                                        
                                        // product price
                                        echo "<h3>$" . $saleProduct["price"] ." or trade</h3>"; 
                                        
                                    }
                                    else {
                                    
                                        // product price
                                        echo "<h3>$" . $saleProduct["price"] ."</h3>";
                                    
                                    }
                                    
                                
                                echo "</div>";
                            
                            echo "</div>";
                        
                        echo "</div>";
                    
                    ?>
                    
                </div>
                
                <!--user offer box-->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    
                    <form id="makeOffer" method="post" action="makeOffer.php">
                    
                    <?php
                    
                        if ($saleProduct["tradable"] == 1) {
                            
                            // show add trade item box
                            echo "<label for='addItemBtn'>Add Item to trade</label>";
                            echo "<p class='btn btn-default' id='addItemBtn'>add item</p>";
                            echo "<p class='btn btn-default' id='hideItemsBoxBtn' style='display: none;'>hide</p>";
                            
                            echo "<div id='buyerActiveItemsBox' class='pre-scrollable' style='display: none;'>";
                            
                                // no trade item row
                                echo "<div class='row'>";
                                
                                    // select item radio button
                                    echo "<div class='col-xs-12 col-sm-2 col-md-2 col-lg-2'>";
                                        echo "<input type=\"radio\" name=\"optradio\" value=\"empty\" checked=\"checked\">";
                                    echo "</div>";
                                    echo "<div class='col-xs-12 col-sm-2 col-md-2 col-lg-2'>";
                                        echo "<h3>no item</h3>";
                                    echo "</div>";
                                        
                                echo "</div>";
                            
                                // load all users active items into box
                                for ($i = 0; $i < count($activeItemsArray); $i++) {
                    
                                    // item row
                                    echo "<div class='row'>";
                                    
                                        // select item radio button
                                        echo "<div class='col-xs-12 col-sm-2 col-md-2 col-lg-2'>";
                                            $activeItemId = $activeItemsArray[$i]['id'];
                                            echo "<input type=\"radio\" name=\"optradio\" value=\"$activeItemId\">";
                                        echo "</div>";
                                    
                                        // product image
                                        echo "<div class='col-xs-12 col-sm-2 col-md-2 col-lg-2'>";
                                        
                                            $coverImage = "images/" . $activeItemsArray[$i]["coverImage"];
                                            echo "<img src='$coverImage' class='active-item-product-image center-block'>";
                                        
                                        echo "</div>";
                                        // product details + action buttons
                                        echo "<div class='col-xs-12 col-sm-8 col-md-6 col-lg-6'>";
                                        
                                            // item details
                                            echo "<div class='row'>";
                                            
                                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                                
                                                    // product title
                                                    echo "<h3>" . $activeItemsArray[$i]["title"] . "</h3>";
                                                
                                                echo "</div>";
                                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                                
                                                    // product price
                                                    echo "<h3>$" . $activeItemsArray[$i]["price"] . "</h3>";
                                                
                                                echo "</div>";
                                            
                                            echo "</div>";
                                        
                                        echo "</div>";
                                    
                                    echo "</div>";
                                    
                                    echo "<hr>";
                    
                                }
                            
                            echo "</div>";
                            
                        }
                    
                    ?>
                    
                    <!--price-->
                    <div class="form-group">
                            
                        <label for="price">price</label>
                        <input id="price" name="price" class="form-control" type="number" placeholder="offer price">
                        
                    </div>
                    
                    <!--description-->
                    <div class="form-group">
                        
                        <label for="description">Description</label>
                        <textarea form="makeOffer" id="description" name="description" class="form-control" rows="5">notes / conditions of trade/offer</textarea>
                        
                    </div>
                    
                    <!--SUBMIT-->
                    <button class="btn btn-default" name="submit" type="submit">send offer</button>
                    
                    </form>
                    
                </div>
                
            </div>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>

<script type="text/javascript">
    
    $(document).ready(function() {

        $('#addItemBtn').click(function() {
            
            $('#buyerActiveItemsBox').show();
            $('#hideItemsBoxBtn').show();
            $('#addItemBtn').show();
            
        });
        
        $('#hideItemsBoxBtn').click(function() {
           
           $('#buyerActiveItemsBox').hide();
            $('#hideItemsBoxBtn').hide();
            
        });
        
    });
       
    
</script>