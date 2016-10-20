<?php

    session_start();
    
    unset($_SESSION["email"]);
    session_destroy();
    
    $_SESSION['currentpage'] = "Logging Out";

?>

<!doctype HTML>
<html>
        
    <?php include("head.php");?>
        
    <body>
        
        <?php include("navigation.php");?>
        
        <div class="container page">
            
            <div class="row">
                
                <div class="col-md-12">
                    <p>Thank you for visiting</p>
                </div>
                
            </div>
            
        </div>
        
        <!--a javascript timer to show the home page after 3000ms-->
        <script>
        
            var timer = setTimeout(function() {
                
                window.location.href="index.php";
                
            },3000);
            
        </script>
        
        <?php include("footer.php"); ?>
        
    </body>
</html>