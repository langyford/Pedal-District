<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Login";
    
    if (isset($_POST['submit']) && count($_POST['submit']) > 0) {
     
        $submitted = true;
        
        // get input values
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // clean email address
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // INPUT CHECKING
        
        
        $errors = array();
        
        $userQuery = "SELECT * FROM PedalDistrict.users WHERE email = '$email';";
        $userQueryResult = $dbconnection->query($userQuery);
        
        if ($userQueryResult) {
        
            $userdata = $userQueryResult->fetch_assoc();
            
            // check if email address is in the database
            if ($userQueryResult->num_rows == 0) {
                
                $errors['email'] = "email address not found";
                
            }
            else {
            
                if (password_verify($password, $userdata["password"])) {
                    
                    //login successful
                    $_SESSION["email"] = $userdata["email"];
                    
                    // check if user is an admin; if so - goto admin dashboard
                    if ($userdata["admin"] == '1') {
                        
                      $_SESSION["admin"] = 1;
                      header("location: dashboard.php");
                      
                    }
                    else {
                        
                      header("location: user-dashboard.php");
                      
                    }
                    
                }
                else {
                    
                    $errors['password'] = "password incorrect";
                    
                }
            
            }
        }
        else {
            
            $errors['query'] = mysqli_error($dbconnection);
            
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
            
            <div class="row">
                
                <div class="col-md-6 col-md-offset-3">
                    
                    <h3>Login</h3>
                    
                    <!--LOGIN FORM-->
                    <form id="login" method="post" action="login.php">
                        
                        <!--EMAIL-->
                        <div class="form-group">
                            
                            <label for="email">Email</label>
                            <input id="email" name="email" class="form-control" type="email" placeholder="email address">
                            
                        </div>
                        
                        <!--PASSWORD-->
                        <div class="form-group">
                            
                            <label for="password">Password</label>
                            <input id="password" name="password" class="form-control" type="password" placeholder="password">
                            
                        </div>
                        
                        <!--SUBMIT-->
                        <button class="btn btn-default" name="submit" type="submit">Login</button>
                        
                    </form> 
                    
                    <?php
                    
                        // check if there were any errors
                        if(count($errors) > 0) {
                            
                            echo "<div class=\"alert alert-warning\">";
                            
                            if ($errors['email']) {
                                
                                echo $errors['email'] . "<br>";
                                
                            }
                            if ($errors['password']) {
                                
                                echo $errors['password'] . "<br>";
                                
                            }
                            if ($errors['query']) {
                                
                                echo $errors['query'] . "<br>";
                                
                            }
                            
                            echo "</div>";
                            
                        }
                        else if (count($errors) == 0 && $submitted == true) {

                            echo "<div class='alert alert-success'>
                            Login Successful
                            </div>";
                            
                        }
                    
                    ?>
                    
                </div>
                
            </div>
            
        </div>
        
        <hr>
        
        <?php include("footer.php"); ?>
        
    </body>
    
</html>