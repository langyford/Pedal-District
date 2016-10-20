<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Contact Us";
    
    if (isset($_POST['contactsubmit']) && count($_POST['contactsubmit']) > 0) {
        
        // get input values
        $name = $_POST["name"];
        $email = $_POST["email"];
        $message = $_POST["message"];
        
        if ($name == "" || $email == "" || $message == "") {
            echo "<script>alert('Please fill in all fields')</script>";
        }
        else {
            
            // add message to contactmessages db table
            $insertQuery = "INSERT INTO `PedalDistrict`.`contactmessages` (`id`, `name`, `email`, `message`) VALUES (NULL, '$name', '$email', '$message')";
            $queryResult = $dbconnection->query($insertQuery);
            
            if ($queryResult) {
                
                header("location: index.php");
                echo "<script>alert('Message sent')</script>";
                
            }
            else {
                echo "<script>alert('Failed to sent message, please try again')</script>";
            }
            
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
            
            <!--contact form-->
            <h2>Contact Us</h2>
            
            <form id="contact" method="post" action="contactus.php">
                
                <!--name field-->
                <div class='form-group'>
                
                    <label for="name">Name</label>
                    <input id="name" name="name" class="form-control" type="text" placeholder="name">
                
                </div>
                
                <!--email field-->
                <div class='form-group'>
                
                    <label for="email">Email</label>
                    <input id="email" name="email" class="form-control" type="email" placeholder="email address">
                
                </div>
                
                <!--message field-->
                <div class='form-group'>
                
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" placeholder="your message"></textarea>
                
                </div>
                
                <div class="form-group">
                    
                    <!--SUBMIT-->
                    <button class="btn btn-default" name="contactsubmit" type="submit">Send</button>
                    
                </div>
                
            </form>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>