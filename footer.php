<footer>

    <?php include("dbconnection.php"); ?>

    <div class="container-fluid">

        <div class='row'>
            
            <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                <h3>Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#.php">About Us</a></li>
                    <li><a href="#.php">Terms and Conditions</a></li>
                    <li><a href="#.php">Privacy Policy</a></li>
                </ul>
            </div>
                            
            <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                <h3>Contact Us</h3>
                <h4>Phone: 9918 9761</h4>
                <a class="btn btn-default outline" style="width: 100%" href="contactus.php">Message Us</a>
                <hr>
                <div class='row'>
                    <div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>
                        <a class="btn btn-default outline" style="width: 100%" href="http://facebook.com">facebook</a>
                    </div>
                    <div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>
                        <a class="btn btn-default outline" style="width: 100%" href="http://twitter.com">twitter</a>
                    </div>
                    <div class='col-xs-12 col-sm-12 col-md-4 col-lg-4'>
                        <a class="btn btn-default outline" style="width: 100%" href="http://instagram.com">instagram</a>
                    </div>
                </div>
            </div>
            
            <?php
            
                // newsletter signup button on click
                if (isset($_POST["newsletterEmail"]) && $_SESSION["newsletterSignedUp"] != true) {
                    
                    // get email address input
                    $email = $_POST["newsletterEmail"];
                    
                    $insertQuery = "INSERT INTO `PedalDistrict`.`newsletter` ('id', 'emailAddress')
                                    VALUES (NULL, '$email');";
                    $insertQuery = "INSERT INTO `PedalDistrict`.`newsletter` (`id`, `emailAddress`) VALUES (NULL, '$email');";
                    $queryResult = $dbconnection->query($insertQuery);
                    
                    if ($queryResult) {
                        
                        $_SESSION["newsletterSignedUp"] = true;
                        echo "<script>alert('newsletter signup successfull');</script>";
                        
                    }
                    else {
                        
                        echo "<script>alert('newsletter signup failed. Please try again');</script>";
                        
                    }
                    
                }
            
            ?>
            
            <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                <h3>Newsletter</h3>
                <form name="newsletter" method="post" id="newsletter" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                    <input type="email" name="newsletterEmail" class="form-control btn-default outline" placeholder="email address">
                    <input type="submit" name="signup" class="btn btn-default outline pull-right" value="signup">
                </form>
            </div>
            
        </div>
        <br>    
        <br>
                            
    </div>

</footer>