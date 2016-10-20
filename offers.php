<?php

    session_start();
    include("dbconnection.php");
    
    // make sure user is logged in
    if (!$_SESSION["email"]) {
        
        header("location: login.php");
        exit();
        
    }
    
    $_SESSION['currentpage'] = "Offers";
    
    // get current user details
    $userEmail = $_SESSION['email'];
    $userQuery = "SELECT * FROM users WHERE email='$userEmail';";
    $userResult = $dbconnection->query($userQuery);
    
    $user;
    if ($userResult->num_rows > 0) {
        
        $user = $userResult->fetch_assoc();
        
    }
    $userId = $user['id'];
    
    /* SENT PENDING OFFERS */
    
    // get all users sent pending offers from offers table
    $offerQuery = "SELECT * FROM offers WHERE buyerId='$userId' AND status='pending';";
    $queryResult = $dbconnection->query($offerQuery);
    
    $sentPendingOffers = array();
    if ($queryResult->num_rows > 0) {
        
        // found pending offers sent by the user; fill array
        while ($row = $queryResult->fetch_assoc()) {
            array_push($sentPendingOffers, $row);
        }
        
    }
    
    // get an assoc array of the selling items in each offer
    $sentPendingOffersSellItems = array();
    for ($i = 0; $i < count($sentPendingOffers); $i++) {
        
        $sellItemId = $sentPendingOffers[$i]["saleProductId"];
        $itemQuery = "SELECT * FROM products WHERE id='$sellItemId'";
        $queryResult = $dbconnection->query($itemQuery);
        
        if ($queryResult->num_rows == 1) {
            
            // found the sell items from the offers; fill array
            $row = $queryResult->fetch_assoc();
            array_push($sentPendingOffersSellItems, $row);
            
        }
        
    }
    
    /* SENT DECLINED OFFERS */
    
    // get all users sent countered offers from offers table
    $offerQuery = "SELECT * FROM offers WHERE buyerId='$userId' AND status='declined';";
    $queryResult = $dbconnection->query($offerQuery);
    
    $sentDeclinedOffers = array();
    if ($queryResult->num_rows > 0) {
        
        // found pending offers sent by the user; fill array
        while ($row = $queryResult->fetch_assoc()) {
            array_push($sentDeclinedOffers, $row);
        }
        
    }
    
    // get an assoc array of the selling items in each offer
    $sentDeclinedOffersSellItems = array();
    for ($i = 0; $i < count($sentDeclinedOffers); $i++) {
        
        $sellItemId = $sentDeclinedOffers[$i]["saleProductId"];
        $itemQuery = "SELECT * FROM products WHERE id='$sellItemId'";
        $queryResult = $dbconnection->query($itemQuery);
        
        if ($queryResult->num_rows == 1) {
            
            // found the sell items from the offers; fill array
            $row = $queryResult->fetch_assoc();
            array_push($sentDeclinedOffersSellItems, $row);
            
        }
        
    }
    
    /* RECEIVED PENDING OFFERS */
    // get all users received pending offers from offers table
    $offerQuery = "SELECT * FROM offers WHERE sellerId='$userId' AND status='pending';";
    $queryResult = $dbconnection->query($offerQuery);
    
    $receivedPendingOffers = array();
    if ($queryResult->num_rows > 0) {
        
        // found pending offers sent by the user; fill array
        while ($row = $queryResult->fetch_assoc()) {
            array_push($receivedPendingOffers, $row);
        }
        
    }
    
    // get an assoc array of the selling items in each offer
    $receivedPendingOffersSellItems = array();
    for ($i = 0; $i < count($receivedPendingOffers); $i++) {
        
        $sellItemId = $receivedPendingOffers[$i]["saleProductId"];
        $itemQuery = "SELECT * FROM products WHERE id='$sellItemId'";
        $queryResult = $dbconnection->query($itemQuery);
        
        if ($queryResult->num_rows == 1) {
            
            // found the sell items from the offers; fill array
            $row = $queryResult->fetch_assoc();
            array_push($receivedPendingOffersSellItems, $row);
            
        }
        
    }
    
    /* RECEIVED DECLINED OFFERS */
    // get all users received declined offers from offers table
    $offerQuery = "SELECT * FROM offers WHERE sellerId='$userId' AND status='declined';";
    $queryResult = $dbconnection->query($offerQuery);
    
    $receivedDeclinedOffers = array();
    if ($queryResult->num_rows > 0) {
        
        // found pending offers sent by the user; fill array
        while ($row = $queryResult->fetch_assoc()) {
            array_push($receivedDeclinedOffers, $row);
        }
        
    }
    
    // get an assoc array of the selling items in each offer
    $receivedDeclinedOffersSellItems = array();
    for ($i = 0; $i < count($receivedDeclinedOffers); $i++) {
        
        $sellItemId = $receivedDeclinedOffers[$i]["saleProductId"];
        $itemQuery = "SELECT * FROM products WHERE id='$sellItemId'";
        $queryResult = $dbconnection->query($itemQuery);
        
        if ($queryResult->num_rows == 1) {
            
            // found the sell items from the offers; fill array
            $row = $queryResult->fetch_assoc();
            array_push($receivedDeclinedOffersSellItems, $row);
            
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
            
            <h2>Sent Offers</h2>
            
            <!--sent offers-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                
                <?php
                
                    if (count($sentPendingOffersSellItems) > 0) {
                    
                    echo "<h3>Pending</h3>";
                    
                        for ($i = 0; $i < count($sentPendingOffersSellItems); $i++) {
                            
                            // offer box
                            echo "<div class='row'>";
                            
                                // image
                                echo "<div class='col-xs-12 col-sm-12 col-md-2 col-lg-2'>";
                                    echo "<div class='offer-item-image centerBlock'>";
                                        $coverImage = "images/" . $sentPendingOffersSellItems[$i]["coverImage"];
                                        echo "<img src='$coverImage' class='img-responsive'>";
                                    echo "</div>";
                                echo "</div>";
                                
                                // offer info + action buttons
                                echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                                
                                    // title and price
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        echo "<div class='col-xs-12 col-sm-12 col-md-8 col-lg-8'>";
                                            echo "<h3>" . $sentPendingOffersSellItems[$i]['title'] . "</h3>";
                                        echo "</div>";
                                        echo "<div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>";
                                            echo "<h3>$" . $sentPendingOffersSellItems[$i]['price'] . "</h3>";
                                        echo "</div>";
                                        
                                    echo "</div>";
                                    
                                    // date + if trade item was included in offer
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        if ($sentPendingOffers[$i]["tradeProductId"] != 0) {
                                            
                                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                                echo "<h4 style='color: grey'>plus trade item " . $sentPendingOffers[$i]["date"] . "</h4>";
                                            echo "</div>";
                                            
                                        }
                                        else {
                                            
                                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                                echo "<h4 style='color: grey'>" . $sentPendingOffers[$i]["date"] . "</h4>";
                                            echo "</div>";
                                            
                                        }
                                    
                                    echo "</div>";
                                
                                    // offer action buttons
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                        
                                        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                                            echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"product-detail.php?id=".$sentPendingOffersSellItems[$i]['id']."\";>View Item</a>";
                                        echo "</div>";
                                        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                                            echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"offer-detail.php?id=".$sentPendingOffers[$i]['id']."\";>View Offer</a>";
                                        echo "</div>";
                                        
                                    echo "</div>";
                                
                                echo "</div>";
    
                            echo "</div>";
                            
                        }
                        
                    }
                    else {
                        echo "<h3 style='color: grey'>you have no pending sent offers</h3>";
                    }
                
                ?>
                
            </div>
            
            <h2>Received Offers</h2>
            
            <!--received offers-->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                
                <?php
                
                    if (count($receivedPendingOffersSellItems) > 0) {
                
                        echo "<h3>Need Responding to</h3>";
                    
                        for ($i = 0; $i < count($receivedPendingOffersSellItems); $i++) {
                            
                            // offer box
                            echo "<div class='row'>";
                            
                                // image
                                echo "<div class='col-xs-12 col-sm-12 col-md-2 col-lg-2'>";
                                    echo "<div class='offer-item-image centerBlock'>";
                                        $coverImage = "images/" . $receivedPendingOffersSellItems[$i]["coverImage"];
                                        echo "<img src='$coverImage' class='img-responsive'>";
                                    echo "</div>";
                                echo "</div>";
                                
                                // offer info + action buttons
                                echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                                
                                    // title and price
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        echo "<div class='col-xs-12 col-sm-12 col-md-8 col-lg-8'>";
                                            echo "<h3>" . $receivedPendingOffersSellItems[$i]['title'] . "</h3>";
                                        echo "</div>";
                                        echo "<div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>";
                                            echo "<h3>$" . $receivedPendingOffersSellItems[$i]['price'] . "</h3>";
                                        echo "</div>";
                                        
                                    echo "</div>";
                                    
                                    // if trade item was included in offer
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    
                                        if ($receivedPendingOffers[$i]["tradeProductId"] != 0) {
                                            
                                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                                echo "<h4 style='color: grey'>plus trade item " . $receivedPendingOffers[$i]["date"] . "</h4>";
                                            echo "</div>";
                                            
                                        }
                                        else {
                                            
                                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                                echo "<h4 style='color: grey'>" . $receivedPendingOffers[$i]["date"] . "</h4>";
                                            echo "</div>";
                                            
                                        }
                                    
                                    echo "</div>";
                                
                                    // offer action buttons
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                        
                                        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                                            echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"product-detail.php?id=".$receivedPendingOffersSellItems[$i]['id']."\";>View Item</a>";
                                        echo "</div>";
                                        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                                            echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"offer-detail.php?id=".$receivedPendingOffers[$i]['id']."\";>View Offer</a>";
                                        echo "</div>";
                                        
                                    echo "</div>";
                                
                                echo "</div>";
    
                            echo "</div>";
                            
                        }
                        
                    }
                    else {
                        echo "<h3 style='color: grey'>you have no received offers to respond to</h3>";
                    }
                
                ?>
                
            </div>
                
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>