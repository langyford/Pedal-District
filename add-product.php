<?php

    session_start();
    include("dbconnection.php");
    $_SESSION["currentpage"] = "Sell";
    
    // make sure user is logged in
    // check if user is logged in or not
    if(!$_SESSION["email"]) {
        
        header("location:login.php");
        
        //make sure we exit to stop the script from processing any further
        exit();
        
    }
    
    if (isset($_POST['submit']) && count($_POST['submit']) > 0) {
     
        $submitted = true;
        $errors = array();
        
        // get input values
        $title = $_POST["title"];
        $price = $_POST["price"];
        if (isset($_POST["tradable"])) {
            $tradable = 1;
        }
        else {
            $tradable = 0;
        }
        
        $shippingPrice;
        if ($_POST["shippingPrice"] == 0) {
            $shippingPrice = null;
        }
        else {
            $shippingPrice = $_POST["shippingPrice"];
        }
        
        $description = $_POST["description"];
        
        // get seller's id
        $useremail = $_SESSION["email"];
        $query = "SELECT id FROM PedalDistrict.users WHERE email='$useremail'";
        $result = $dbconnection->query($query);
        
        if ($result->num_rows > 0) {
            
            $userdata = $result->fetch_assoc();
            $sellerId = $userdata["id"];
    
            $shippingOptions = $_POST["shipping-options"];
            $category = $_POST["category"];
            $subcategory = $_POST["subcategory"];
            
            // create a unique name for the image
            $uniqueId = uniqid();
            // remove spaces from file name
            $imageFileName = str_replace(' ','', $_FILES["coverImage"]["name"]);
            // add unique id to image name
            $imageFileName = $uniqueId . $imageFileName;
            
            // upload image
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES["coverImage"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            
                        // check if any image was uploaded
            if ($_FILES["coverImage"]["tmp_name"] == null) {
                $errors["noimage"] = "no image was selected";
            }
            else {
            
                // Check if image file is a actual image or fake image
                $realImageCheck = getimagesize($_FILES["coverImage"]["tmp_name"]);
                if (!$realImageCheck) {
                    $errors["fakeimage"] = "image to be uploaded is not an image";
                }
                
                // Check if file already exists
                // hash image to be uploaded
                $coverImageHash = hash_file('md5', $_FILES["coverImage"]["tmp_name"]);
                $files = scandir("images/");
                foreach ($files as $file) {
                    
                    // check if file hash is same as cover image hash
                    if (hash_file('md5', $target_dir.$file) == $coverImageHash) {
                        
                        $errors["imageduplicate"] = "image to be uploaded is a duplicate";
                        
                    }
                    
                }
                
                // Check file size
                if ($_FILES["coverImage"]["size"] > (10 * 1000000)) {
                    $errors["imagesize"] = "image to be uploaded is too big";
                }
                
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $errors["imagetype"] = "image to be uploaded is not a supported file type";
                }
            
            }
            
            // check if there were any errors
            if (count($errors) == 0) {
                
                // no errors, try to upload file
                if (move_uploaded_file($_FILES["coverImage"]["tmp_name"], $target_dir . $imageFileName)) {
                    
                    $coverImage = $imageFileName;
                    
                    // insert item into product db table
                    $insertProductQuery = "INSERT INTO PedalDistrict.products (`id`, `title`, `price`, `tradable`, `shippingPrice`, `description`, `coverImage`, `image1`, `image2`, `image3`, `image4`, `image5`, `sellerId`, `shippingOptions`, `category`, `subcategory`)
                                    VALUES (NULL, '$title', '$price', '$tradable', '$shippingPrice', '$description', '$coverImage', NULL, NULL, NULL, NULL, NULL, '$sellerId', '$shippingOptions', '$category', '$subcategory');";
                    
                    $insertProductResult = $dbconnection->query($insertProductQuery);
            
                    // run insert query with completion check
                    if ($insertProductResult) {
                        
                        // product added to database
                        echo "<div class='alert alert-success'>
                            Your item has been listed!
                        </div>";
                        
                        // todo: redirect to active listings
                        
                    }
                    else if (!$insertProductResult) {
                        
                        // failed to insert into database
                        $queryError = mysqli_error($dbconnection);
                        //echo "<script>console.log('$queryError');</script>";
                        
                        // remove image which might have been uploaded from database
                        unlink($target_dir . $imageFileName);
                        
                    }
                    
                }
                
            }
            else {
                
                // display error array messages
                echo "<div class=\"alert alert-warning\">";
                
                    if ($errors['fakeimage']) {
                        echo $errors['fakeimage'] . "<br>";
                    }
                    if ($errors['imagesize']) {
                        echo $errors['imagesize'] . "<br>";
                    }
                    if ($errors['imageduplicate']) {
                        echo $errors['imageduplicate'] . "<br>";
                    }
                    if ($errors['imagetype']) {
                        echo $errors['imagetype'] . "<br>";
                    }
                    if ($errors['noimage']) {
                        echo $errors['noimage'] . "<br>";
                    }
                
                echo "</div>";
                
            }
            
        }
        
    }

?>

<html>
    
    <head>
        
        <?php include("head.php"); ?>
        
    </head>
    
    <body>
        
        <?php include("navigation.php"); ?>
        
        <div class="container page">
            
            <!--add product form-->
            <div class="row">
                
                <h3>Sell an Item</h3>
                
                <div class="col-md-6 col-centered">
                    
                    <form id="add-product-form" action="add-product.php" method="post" enctype="multipart/form-data">
                        
                        <!--title-->
                        <div class="form-group">
                            
                            <label for="title">Title</label>
                            <input id="title" name="title" type="text" class="form-control" placeholder="product title">
                            
                        </div>
                        
                        <!--price-->
                        <div class="form-group">
                            
                            <label for="price">Price</label>
                            <input id="price" name="price" type="number" class="form-control" placeholder="product price">
                            
                        </div>
                        
                        <!--trade offer-->
                        <div class="form-group">
                            
                            <div class="checkbox">
                                <label><input type="checkbox" name="tradable" value="tradable">Allow trade offers</label>
                            </div>
                            
                        </div>
                        
                        <!--description-->
                        <div class="form-group">
                            
                            <label for="description">Description</label>
                            <textarea form="add-product-form" id="description" name="description" class="form-control" rows="5">product description</textarea>
                            
                        </div>
                        
                        <!--shipping options-->
                        <div class="form-group">
                            
                            <label for="shipping-options">Shipping Options</label>
                            <select id="shipping-options" name="shipping-options" class="form-control">
                                <option value="Pickup Only">Pickup Only</option>
                                <option value="Shipping Only">Shipping Only</option>
                                <option value="Pickup and Shipping">Pickup and Shipping</option>
                            </select>
                            
                        </div>
                        
                        <!--shipping price-->
                        <div class="form-group">
                            <input id="shippingPrice" name="shippingPrice" type="number" class="form-control" placeholder="shipping price (leave empty if pickup only)">
                        </div>
                        
                        <!--category-->
                        <div class="form-group">
                            
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control">
                                <option value="Effects">Effects</option>
                                <option value="Amps">Amps</option>
                                <option value="Accessories">Accessories</option>
                            </select>
                            
                        </div>
                        
                        <!--subcategory-->
                        <div class="form-group">
                            
                            <label for="subcategory">Subcategory</label>
                            <select id="subcategory" name="subcategory" class="form-control">
                                <option value="Chorus">Chorus</option>
                                <option value="Delay">Delay</option>
                                <option value="Tremolo">Tremolo</option>
                                <option value="Vibrato">Vibrato</option>
                                <option value="Wah">Wah</option>
                                <option value="Fender">Fender</option>
                                <option value="Vox">Vox</option>
                                <option value="Cables">Cables</option>
                                <option value="Powersupplies">Powersupplies</option>
                            </select>
                            
                        </div>
                        
                        <!--cover image-->
                        <div class="form-group">
                            
                            <label for="coverImage">Cover Image</label>
                            <input type="file" id="coverImage" name="coverImage">
                            
                        </div>
                        
                        <!--submit button-->
                        <div class="form-group">
                            <button class="btn btn-default" name="submit" type="submit">List Item</button>
                        </div>
                        
                    </form>
                    
                </div>
                
            </div>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>