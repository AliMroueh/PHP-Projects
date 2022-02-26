<?php
include('../config/constants.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Food order system</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    
    <div class="login">
    <h1 class="text-center">Login</h1>
    <br><br>
<?php
    if(isset($_SESSION['login'])){
    echo $_SESSION['login'];
    unset($_SESSION['login']);
}

if(isset($_SESSION['no-login-message'])){
    echo $_SESSION['no-login-message'];
    unset($_SESSION['no-login-message']);
}
?>
<br><br>
    <!-- Login form starts here -->
    <form action="" method="post" class="text-center">
    Username:<br>
    <input type="text" name="username" placeholder="Enter username"><br><br>
    Password:<br>
    <input type="password" name="password" placeholder="Enter password"><br><br>
    <input type="submit" name="submit" value="Login" class="btn-primary"><br><br>
    </form>
    <!-- Login form ends here -->
    
    <p class="text-center">Created by - <a href="www.vijaythapa.com">Vijay Thapa</a></p>
    </div>

</body>
</html>

<?php

    // check whether the submit button is clicked or not
    if(isset($_POST['submit'])){
        // proccess for login
        // 1. get the data from login form
        $username = $_POST['username'];
        $password = md5($_POST['password']);

     


        // 2. sql to check whether the username and password exists or not
        $sql = "select * from tbl_admin where username='$username' and password='$password'";

        // 3. execute the query
        $res = mysqli_query($conn,$sql);

        // 4.count rows to check whether the user exists or not
        $count = mysqli_num_rows($res);

        if($count==1){
            // user available and login success
            $_SESSION['login'] = "<div class='success'>Login Successful. </div>";
            $_SESSION['user'] = $username; // check whether the user is logged in or not and logout will unset it

            // Redirect to home page/dashboard
            header("location:".SITEURL.'admin/');
        }else{
            // user not available and login failed
            $_SESSION['login'] = "<div class='error text-center'>Username or Password did not match.</div>";
            // Redirect to home page/dashboard
            header("location:".SITEURL.'admin/login.php');
        }
    }

?>