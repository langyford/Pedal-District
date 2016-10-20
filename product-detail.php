<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Product";
    
    // get current user if they are logged in
    if ($_SESSION["email"]) {
        
        $userEmail = $_SESSION["email"];
        $userQuery = "SELECT * FROM users WHERE email='$userEmail'";
        $queryResult = $dbconnection->query($userQuery);
        
        $user;
        if ($queryResult->num_rows == 1) {
            $user = $queryResult->fetch_assoc();
        }
        $userId = $user["id"];
        
    }
    
    $id = $_GET["id"];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    // item from products db
    $productQuery = "SELECT * FROM PedalDistrict.products WHERE id = '$id';";
    $productQueryResults = $dbconnection->query($productQuery);
    
    $product = array();
    
    if ($productQueryResults->num_rows > 0) {

        // found the item
        $product = $productQueryResults->fetch_assoc();
    
    }
    else {
        
        // didn't find any items in db table
        
    }
    
    // get seller information
    $sellerId = $product['sellerId'];
    $sellerQuery = "SELECT * FROM PedalDistrict.users WHERE id = '$sellerId';";
    $sellerResults = $dbconnection->query($sellerQuery);
    $seller = array();
    
    if ($sellerResults->num_rows > 0) {
        
        // found the seller in the user table
        $seller = $sellerResults->fetch_assoc();
        
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
        
            <div class="row">
            
                <!--image-->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 center-block">
                    
                        <!--product image-->
                        <?php $coverImage = "images/" . $product['coverImage']; ?>
                        <div class="centerBlock">
                            <img src="<?php echo $coverImage; ?>" class="product-detail-image" id="product-detail-image">
                        </div>
                
                </div>
                <!--item details-->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <!--title-->
                        <h2><?php echo $product['title']; ?></h2>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <!--categories-->
                        <p style="color: grey;"><?php echo $product['category']." -> ". $product['subcategory']; ?></p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <!--item location-->
                        <h3 style="color: grey;"><?php echo $seller["suburb"] . ", " . $seller["city"] . " " . $seller["state"]; ?></h3>
                    </div>
                    <div class="col-xs-8">
                        <!--price-->
                        <h2 style="color: grey;"><?php echo "$".$product['price']; if ($product["tradable"] == 1) { echo " or trade offer"; }?></h2>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <!--buttons-->
                        <a class="btn btn-default pull-right" href="makeOffer.php?productId=<?php echo $product['id']; ?>">make offer</a>
                        <a class="btn btn-default pull-right" href="addToWatchlist.php?productId=<?php echo $product['id']; ?>&userId=<?php echo $userId; ?>">add to watchlist</a>
                    </div>
                
                </div>
                    
            </div>
            
            <!--divider-->
            <hr>
            
            <div class="row">
                
                <!--side nav-->
                <div class="col-md-4">
                    
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active"><a data-toggle="pill" href="#description">Description</a></li>
                        <li><a data-toggle="pill" href="#seller-info">Seller Info</a></li>
                        <li><a data-toggle="pill" href="#shipping-options">Location and Shipping</a></li>
                        <li><a data-toggle="pill" href="#images">More Images</a></li>
                    </ul>
                    
                </div>
                
                <!--product info section-->
                <div class="col-md-8">
                    
                    <div class="tab-content">
                        <div id="description" class="tab-pane fade in active">
                            <h3>Description</h3>
                            <p><?php echo $product['description']; ?></p>
                        </div>
                        
                        <div id="seller-info" class="tab-pane fade">
                            <h3>Seller Info</h3>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <h4>Name:</h4>
                                <h4 style="color: grey;"><?php echo $seller['name']; ?></h4>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-6 col-lg-4">
                                <h4>Email:</h4>
                                <h4 style="color: grey;"><?php echo $seller['email']; ?></h4>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <h4>Phone Number:</h4>
                                <h4 style="color: grey;"><?php echo $seller['phonenumber']; ?></h4>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Seller Reviews:</h3>
                                <h4 style="color: grey;">This seller currently has no reviews</h4>
                            </div>
                        </div>
                        
                        <div id="shipping-options" class="tab-pane fade">
                            <h3>Shipping Options: <?php echo $product['shippingOptions']; ?></h3>
                            <?php if ($product["shippingPrice"] != 0) { echo "<h3>Shipping Price: $".$product["shippingPrice"]."</h3>"; } ?>
                            <img src="https://maps.google.com/maps/api/staticmap?center=<?php echo $seller['postcode']; ?>, + Australia&zoom=14&size=800x300&maptype=roadmap&markers=color:ORANGE|label:<?php echo $seller['suburb']; ?>|<?php echo $seller['postcode']; ?>, +Australia&sensor=false" style="width: 100%;">
                        </div>
                        
                        <div id="images" class="tab-pane fade">
                            <h3>more images</h3>
                            <h4 style="color: grey">no other images to show</h4>
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>