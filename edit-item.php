<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Edit";
    $seller;
    
    // check if user is logged in or not
    if(!$_SESSION["email"]) {
        
        header("location:login.php");
        
        //make sure we exit to stop the script from processing any further
        exit();
        
    }
    else {
        
        // get sellers id
        $sellerEmail = $_SESSION["email"];
        $sellerQuery = "SELECT * FROM PedalDistrict.users WHERE email='$sellerEmail';";
        $queryResult = $dbconnection->query($sellerQuery);
        
        if ($queryResult->num_rows > 0) {
            
            $seller = $queryResult->fetch_assoc();
            
        }
        else {
            
            // redirect to login page
            header("location: login.php");
            
        }
        
    }
    
    // get the item to edit
    $product = array();
    $itemId = $_GET["id"];
    $productQuery = "SELECT * FROM PedalDistrict.products WHERE id='$itemId';";
    $productResult = $dbconnection->query($productQuery);
    
    if ($productResult->num_rows > 0) {
        
        // found the item
        $product = $productResult->fetch_assoc();
        
    }
    else {
        
        // couldn't find product; go back to active items page
        header("Location: user-dashboard-activeitems.php");
        
    }
    
?>

<!doctype HTML>
<html>
    
    <?php include("head.php");?>
    
    <body>
        
        <?php include("navigation.php");?>
        
        <div class="container page">
            
                <div class="row">
                    <div class="col-md-8">
                        <h2>Edit Item</h2>
                    </div>
                    <div class="col-md-4">
                        <input class="btn btn-default pull-right" id="updateButton" type="submit" name="update" value="Update Details"/>
                    </div>
                </div>
                
                <div class="row">
                
                    <!--image-->
                    <div class="col-md-4">
                        
                            <!--product image-->
                            <?php
                                $coverImage = "images/" . $product['coverImage'];
                            ?>
                            <div class="centerBlock">
                                <img src="<?php echo $coverImage; ?>" class="product-detail-image" id="product-detail-image">
                            </div>
                    
                    </div>
                    
                    <!--item details-->
                    <div class="col-md-8">
                    
                        <div class="col-md-12">
                            <!--title-->
                            <h4>Title</h4>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo $product['title']; ?>">
                        </div>
                        <div class="col-md-12">
                            <!--categories-->
                            <h4>Category</h4>
                            <select id="category" name="category" class="form-control">
                                <option value="Effects" <?php if ($product['category'] == "Effects") { echo "selected='selected'"; } ?> >Effects</option>
                                <option value="Amps" <?php if ($product['category'] == "Amps") { echo "selected='selected'"; } ?> >Amps</option>
                                <option value="Accessories" <?php if ($product['category'] == "Accessories") { echo "selected='selected'"; } ?> >Accessories</option>
                            </select>
                            <h4>Subcategory</h4>
                            <select id="subcategory" name="subcategory" class="form-control">
                                <option value="Chorus" <?php if ($product['subcategory'] == "Chorus") { echo "selected='selected'"; } ?> >Chorus</option>
                                <option value="Delay" <?php if ($product['subcategory'] == "Delay") { echo "selected='selected'"; } ?> >Delay</option>
                                <option value="Tremolo" <?php if ($product['subcategory'] == "Tremolo") { echo "selected='selected'"; } ?> >Tremolo</option>
                                <option value="Vibrato" <?php if ($product['subcategory'] == "Vibrato") { echo "selected='selected'"; } ?> >Vibrato</option>
                                <option value="Wah" <?php if ($product['subcategory'] == "Wah") { echo "selected='selected'"; } ?> >Wah</option>
                                <option value="Fender" <?php if ($product['subcategory'] == "Fender") { echo "selected='selected'"; } ?> >Fender</option>
                                <option value="Vox" <?php if ($product['subcategory'] == "Vox") { echo "selected='selected'"; } ?> >Vox</option>
                                <option value="Cables" <?php if ($product['subcategory'] == "Cables") { echo "selected='selected'"; } ?> >Cables</option>
                                <option value="Powersupplies" <?php if ($product['subcategory'] == "Powersupplies") { echo "selected='selected'"; } ?> >Powersupplies</option>
                            </select>
                            
                        </div>
                        <div class="col-md-8">
                            <!--price-->
                            <h4>Price</h4>
                            <input type="number" name="price" id="price" class="form-control" value="<?php echo $product['price']; ?>">
                            <h4>Trade offers allowed</h4>
                            <?php
                                if ($product["tradable"] == 1) {
                                    echo "<input type=\"checkbox\" id=\"tradeCheckbox\" name=\"tradeCheckbox\" value=\"1\" checked>";
                                }
                                else {
                                    echo "<input type=\"checkbox\" id=\"tradeCheckbox\" name=\"tradeCheckbox\" value=\"0\">";
                                }
                            ?>
                        </div>
                    
                    </div>
                        
                </div>
                
                <div class="row">
                    
                    <!--product info section-->
                    <div class="col-md-12">
                        
                        <div class="col-md-12">
                            
                            <!--shipping options-->
                            <h3>Shipping Options</h3>
                            <select id="shipping-options" name="shipping-options" class="form-control">
                                <option value="Pickup Only" <?php if ($product['shippingOptions'] == "Pickup Only") { echo "selected='selected'"; } ?> >Pickup Only</option>
                                <option value="Shipping Only" <?php if ($product['shippingOptions'] == "Shipping Only") { echo "selected='selected'"; } ?> >Shipping Only</option>
                                <option value="Pickup and Shipping" <?php if ($product['shippingOptions'] == "Pickup and Shipping") { echo "selected='selected'"; } ?> >Pickup and Shipping</option>
                            </select>
                            
                        </div>
                        
                        <!--description-->
                        <div class="col-md-12">
                            
                            <h3>Description</h3>
                            <textarea class="form-control" rows="15" id="description" name="description"><?php echo $product['description']; ?></textarea>
                            
                        </div>
                        
                    </div>
                    
                </div>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
</html>

<script type="text/javascript">
$(document).ready(function() {

    $('#updateButton').click(function() {
       
       var itemId = <?php echo $itemId; ?>;
       var isTradable = $("#tradeCheckbox").is(":checked");
       var productTradable = 0;
       if (isTradable) {
           productTradable = 1;
       }
       
       $.ajax({
           type: "POST",
           url: 'updateItem.php',
           data:{ 
                   itemId: itemId,
                   title: $("input#title").val(), 
                   category: $("#category option:selected").val(), 
                   subcategory: $("#subcategory option:selected").val(), 
                   price: $("input#price").val(), 
                   tradable: productTradable,
                   shippingOptions: $("#shipping-options option:selected").val(), 
                   description: $("#description").val() }

      }).done(function(data) {
          
          console.log(data);
          window.location.href = "user-dashboard-activeitems.php";
          
      });
        
    });
    
});
</script>