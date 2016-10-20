<?php

    session_start();
    include("dbconnection.php");
    
    // make sure user is logged in
    if (!$_SESSION["email"]) {
        
        header("location: login.php");
        exit();
        
    }
    
    $_SESSION['currentpage'] = "Dashboard";
    $_SESSION['dashboardtab'] = "Transaction History";
    
    // get current user details
    $userEmail = $_SESSION["email"];
    $userQuery = "SELECT * FROM users WHERE email='$userEmail'";
    $queryResult = $dbconnection->query($userQuery);
    
    $user;
    if ($queryResult->num_rows == 1) {
        
        $user = $queryResult->fetch_assoc();
        
    }
    $userId = $user["id"];
    
    // get all rows from transaction table where sellerid = currentuser
    $transactionsQuery = "SELECT * FROM transactions WHERE sellerId='$userId'";
    $queryResult = $dbconnection->query($transactionsQuery);
    
    $transactions = array();
    if ($queryResult->num_rows > 0) {
        
        while ($row = $queryResult->fetch_assoc()) {
            array_push($transactions, $row);
        }
        
    }
    
    $userItems = array();
    // get all of users sell items from each transaction
    for ($i = 0; $i < count($transactions); $i++) {
        
        $itemId = $transactions[$i]["productId"];
        $itemQuery = "SELECT * FROM soldItems WHERE id='$itemId'";
        $queryResult = $dbconnection->query($itemQuery);
        
        if ($queryResult->num_rows == 1) {
            
            $row = $queryResult->fetch_assoc();
            array_push($userItems, $row);
            
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
            
            <h2>Transactions For Sold Items</h2>
            
            <?php
            
                for ($i = 0; $i < count($transactions); $i++) {
                    
                    // item box
                    echo "<div class='row'>";
                        
                        // image
                        echo "<div class='col-xs-12 col-sm-12 col-md-2 col-lg-2'>";
                            echo "<div class='offer-item-image centerBlock'>";
                                $coverImage = "images/" . $userItems[$i]["coverImage"];
                                echo "<img src='$coverImage' class='img-responsive'>";
                            echo "</div>";
                        echo "</div>";
                        
                        // item info + action buttons
                        echo "<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>";
                        
                            // title and price
                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                            
                                echo "<div class='col-xs-12 col-sm-12 col-md-8 col-lg-8'>";
                                    echo "<h3>" . $userItems[$i]['title'] . "</h3>";
                                echo "</div>";
                                
                            echo "</div>";
                            
                            // date + if trade item was included in offer
                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                            
                                if ($transactions[$i]["tradeProductId"] != 0) {
                                    
                                    echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                        echo "<h4 style='color: grey'>plus trade item</h4>";
                                    echo "</div>";
                                    
                                }
                            
                            echo "</div>";
                        
                            // action buttons
                            echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                
                                echo "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
                                    echo "<a class=\"btn btn-default\" style=\"width: 100%;\" href=\"transaction-details.php?transactionId=".$transactions[$i]['id']."\";>View Transaction</a>";
                                echo "</div>";
                                
                            echo "</div>";
                        
                        echo "</div>";

                    echo "</div>";
                    
                    echo "<hr>";
                    
                }
            
            ?>
                
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>