<?php

    session_start();
    include("dbconnection.php");
    
    $_SESSION['currentpage'] = "Dashboard";
    $_SESSION['dashboardtab'] = "Edit Account";
    
    // check if user is logged in or not
    if(!$_SESSION["email"]) {
        
        header("location:login.php");
        
        //make sure we exit to stop the script from processing any further
        exit();
        
    }
    
    //receive update data
    if (isset($_POST["submit"]) && count($_POST["submit"]) > 0) {
        
        $submitted = true;
        $errors = array();
        
        $id = $_POST["id"];
        $newemail = filter_var($_POST["newemail"], FILTER_SANITIZE_EMAIL);
        $newpassword = $_POST["newpassword"];
        $repeatpassword = $_POST["newpassword1"];
        
        if ($newpassword != $repeatpassword && $newpassword != "") {
            
            $errors["password"] = "passwords not the same";
            
        }
        
        //check if new email is already used by another user with different id
        $emailCheckQuery = "SELECT * FROM PedalDistrict.users WHERE email='$newemail' AND id<>'$id'";
        $checkResult = $dbconnection->query($emailCheckQuery);
        
        if ($checkResult->num_rows > 0) {
            
            $errors["email"] = "the email address is already in use by another user";
        
        }
        
        //if there is no other user using the new email address
        //check if there are no errors
        if (count($errors) == 0) {
            
            //update user data
            
            //if password is filled in but not email
            if ($newpassword != "" && $newemail == "") {
                
                $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE PedalDistrict.users SET password='$newpassword' WHERE id='$id'";
            
            }
            //if email is filled in but not the password
            else if ($newemail != "" && $newpassword == "") {
                
                $updateQuery = "UPDATE PedalDistrict.users SET email='$newemail' WHERE id='$id'";
                
            }
            //if both password and email have been filled in
            else if ($newemail != "" && $newpassword != "") {
                
                $newpassword = password_hash($newpassword,PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET email='$newemail',password='$newpassword' WHERE id='$id'";
                
                $_SESSION["email"] = $newemail;
                
            }
            
            // run update query in check statement
            if ($dbconnection->query($updateQuery)) {
                
                $success = true;
                
                if ($newemail != "") {
                    
                    $_SESSION["email"] = $newemail;
                    
                }
                
            }
            else {
                
                $success = false;
                
            }
        }
        
    }
    
    //get user account data
    $useremail = $_SESSION["email"];
    $query = "SELECT * FROM PedalDistrict.users WHERE email='$useremail'";
    $result = $dbconnection->query($query);
    
    if ($result->num_rows > 0) {
        
        $userdata = $result->fetch_assoc();
    
    }
    
    $id = $userdata["id"];
    $email = $userdata["email"];

?>

<!doctype HTML>
<html>
    
    <?php include("head.php");?>
    
    <body>
        
        <?php include("navigation.php");?>
        
        <div class="container page">
            
            <div class="row">
                
                <div class="col-md-4">
                    
                    <h2>Update Your Details</h2>
                    
                    <form id="user-details" method="post" action="user-dashboard.php">
                        
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo $id;?>">
                            <input type="hidden" name="email" value="<?php echo $email;?>">
                            
                            <label for="email">Change Email Address</label>
                            <input class="form-control" id="email" name="newemail" type="email" placeholder="<?php echo $email;?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Create A New Password</label>
                            <input class="form-control" id="password" name="newpassword" type="password" placeholder="change password">
                        </div>
                        <div class="form-group">
                            <label for="password1">Retype Password</label>
                            <input class="form-control" id="password1" name="newpassword1" type="password" placeholder="retype new password">
                        </div>
                        <button class="btn btn-default" type="submit" name="submit">Change Your Details</button>
                    </form>
                    
                    <?php
                    
                        if (count($errors) > 0 && $submitted == true) {
                            
                            echo "<div class=\"alert alert-warning\">";
                            echo "update failed<br>";
                            
                            if ($errors["email"]) {
                                
                                echo $errors["email"]."<br>";
                                
                            }
                            if ($errors["password"]) {
                                
                                echo $errors["password"];
                                
                            }
                            
                            echo "</div>";
                            
                        }
                        else if (count($errors) == 0 && $submitted == true && $success == true) {
                            
                            echo "<div class=\"alert alert-success\">Successfully Update Account Details</div>";
                            
                        }
                        else if (count($errors) == 0 && $submitted == true && $success == false) {
                            
                            echo "<div class=\"alert alert-danger\">Update Failed</div>";
                            
                        }
                        
                    ?>
                
                </div>
            </div>
            
        </div>
        
        <hr>
        <?php include("footer.php"); ?>
        
    </body>
</html>