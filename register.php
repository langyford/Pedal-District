<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Register";
    
    if (isset($_POST['submit'])) {

        $submitted = true;
        
        $name = $_POST["name"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $streetnumber = $_POST["address_streetnumber"];
        $streetname = $_POST["address_streetname"];
        $suburb = $_POST["address_suburb"];
        $postcode = $_POST["address_postcode"];
        $city = $_POST["address_city"];
        $state = $_POST["address_state"];
        $phonenumber = $_POST["phonenumber"];
        
        // clean email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        
        // INPUT CHECKING
        
        // check if email already exists
        $checkEmailQuery  = "SELECT * FROM PedalDistrict.users WHERE email ='$email';";
        $emailResults = $dbconnection->query($checkEmailQuery);
        
        // check if username already exists
        $checkUsernameQuery = "SELECT * FROM  PedalDistrict.users WHERE username ='$username';";
        $usernameResults = $dbconnection->query($checkUsernameQuery);
        
        $errors = array();
        
        if ($emailResults->num_rows > 0) {
            
            // email address is already in the db
            $errors['email'] = "Email address already in use";
            
        }
        else if ($usernameResults->num_rows > 0) {
            
            $errors['username'] = "Username already in use";
            
        }
        else {
            
            // email address and username not in use, insert user into db
            
            // hash password
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            $insertQuery = "INSERT INTO PedalDistrict.users (`id`, `admin`, `name`, `username`, `email`, `password`, `streetnumber`, `streetname`, `suburb`, `postcode`, `city`, `state`, `phonenumber`)
                            VALUES (NULL, '0', '$name',  '$username',  '$email',  '$password',  '$streetnumber', '$streetname', '$suburb', '$postcode', '$city', '$state',  '$phonenumber')";
             
            
            $insertQueryResult = $dbconnection->query($insertQuery);
            
            // run insert query with completion check
            if ($insertQueryResult) {
                
                // redirect to login
                header("Location: login.php");
                
            }
            else if (!$insertQueryResult) {
                
                $errors['insert'] = "failed to register account" . mysqli_error($dbconnection);
                
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
            
            <div class="row">
                
                <div class="col-md-6 col-md-offset-3">
                    
                    <h3>Register</h3>
                    
                    <!--REGISTER FORM-->
                    <form id="register" method="post" action="register.php">
                        
                        <!--NAME-->
                        <div class="form-group">
                            
                            <label for="name">Name</label>
                            <input id="name" name="name" class="form-control" type="text" placeholder="First and Last Name">
                            
                        </div>
                        
                        <!--USERNAME-->
                        <div class="form-group">
                            
                            <label for="username">Username</label>
                            <input id="username" name="username" class="form-control" type="text" placeholder="Username">
                            
                        </div>
                        
                        <!--EMAIL-->
                        <div class="form-group">
                            
                            <label for="email">Email</label>
                            <input id="email" name="email" class="form-control" type="email" placeholder="youremail@domain.com">
                            
                        </div>
                        
                        <!--PASSWORD-->
                        <div class="form-group">
                            
                            <label for="password">Password</label>
                            <input id="password" name="password" class="form-control" type="password">
                            
                        </div>
                        
                        <!--ADDRESS-->
                        <div class="form-group">
                            
                            <label for="address">Address</label>
                            <input id="address_streetnumber" name="address_streetnumber" class="form-control" type="text" placeholder="Street Number">
                            </br>
                            <input id="address_streetname" name="address_streetname" class="form-control" type="text" placeholder="Street Name">
                            </br>
                            <input id="address_suburb" name="address_suburb" class="form-control" type="text" placeholder="Suburb">
                            </br>
                            <input id="address_postcode" name="address_postcode" class="form-control" type="number" placeholder="Postcode">
                            </br>
                            <input id="address_city" name="address_city" class="form-control" type="text" placeholder="City">
                            </br>
                            <select id="address_state" name="address_state" class="form-control">
                                <option value="NSW">NSW</option>
                                <option value="VIC">VIC</option>
                                <option value="NT">NT</option>
                                <option value="WA">WA</option>
                                <option value="TAS">TAS</option>
                                <option value="QLD">QLD</option>
                                <option value="SA">SA</option>
                            </select>
                            
                        </div>
                        
                        <!--PHONE NUMBER-->
                        <div class="form-group">
                            
                            <label for="phonenumber">Phone Number</label>
                            <input id="phonenumber" name="phonenumber" class="form-control" type="text" placeholder="+61.......">
                            
                        </div>
                        
                        <!--SUBMIT-->
                        <button class="btn btn-default" name="submit" type="submit">Register</button>
                        
                    </form> 
                    
                    <?php
                    
                        // check if there were any errors
                        if(count($errors) > 0) {
                            
                            echo "<div class=\"alert alert-warning\">";
                            
                            if ($errors['email']) {
                                
                                echo $errors['email'] . "<br>";
                                
                            }
                            if ($errors['username']) {
                                
                                echo $errors['username'] . "<br>";
                                
                            }
                            if ($errors['insert']) {
                                
                                echo $errors['insert'] . "<br>";
                                
                            }
                            
                            echo "</div>";
                            
                        }
                        else if (count($errors) == 0 && $submitted == true) {

                            echo "<div class='alert alert-success'>
                            Your account has been created
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