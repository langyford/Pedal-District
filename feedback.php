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
    
    // insert values
    $userEmail = $_SESSION["email"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];
    $insertFeedbackQuery = "INSERT INTO PedalDistrict.feedback ('useremail', 'rating', 'comment')
                        VALUES ('$userEmail', $rating', '$comment');";
    $dbconnection->query($insertFeedbackQuery);
?>


<html>
    
    <head>
        
        <?php include("head.php"); ?>
        
    </head>
    
    <body>
        
        <?php include("navigation.php"); ?>
        
        
        <form action="#" method="post">
            
        <select name="rating">
        <option value="positive">Positive</option>
        <option value="nuetral">Neutral</option>
        <option value="negative">Negative</option>
        <br>
        Comment: <input type="text" name="comment">
        </select>
        
        <input type="submit" name="submit" value="Leave Feedback" />
        
        </form>
        
        
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>