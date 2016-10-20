<?php

    session_start();
    include("dbconnection.php");
    $_SESSION["currentpage"] = "Feedback";
    
    // make sure user is logged in
    // check if user is logged in or not
    if(!$_SESSION["email"]) {
        
        header("location:login.php");
        
        //make sure we exit to stop the script from processing any further
        exit();
        
    }
    

?>


<html>
    
    <head>
        
        <?php include("head.php"); ?>
        
    </head>
    
    <body>
        
        <?php include("navigation.php"); ?>
        
        <div class="col-md-6 col-md-offset-3">
                    
                    <h3>Contact Us</h3>
                    
                    <!--LOGIN FORM-->
                    <form id="contact" method="post" action="contact.php">
                        
                        <!--Name-->
                        <div class="form-group">
                            
                            <label for="name">Name</label>
                            <input id="name" name="name" class="form-control" type="text" placeholder="John Smith">
                            
                        </div>
                        
                        <!--EMAIL-->
                        <div class="form-group">
                            
                            <label for="email">Email</label>
                            <input id="email" name="email" class="form-control" type="email" placeholder="johnsmith@gmail.com">
                            
                        </div>
                        
                        <!--Message-->
                        <div class="form-group">
                            
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" type="text" placeholder="message"></textarea>
                            
                        </div>
                        
                        <!--SUBMIT-->
                        <button class="btn btn-default" name="submit" type="submit">Submit</button>
                        
                    </form>
        </div>
        <?php include("footer.php"); ?>
        
    </body>
    
</html>