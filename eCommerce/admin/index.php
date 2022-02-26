<?php 

    session_start();
    $noNavbar = "";
    $pageTitle = "Login";
    // print_r($_SESSION);

    if(isset($_SESSION['Username'])){
        header('location:dashboard.php'); // Redirect to dashboard page
        
    }

    include 'init.php';
    
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    // Check if the user exist in the database

    $stmt = $con->prepare("SELECT Username, Password, UserID FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
    $stmt->execute(array($username,$hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount(); // check how many row have the username and password

    if($count > 0){
        $_SESSION['Username'] = $username; // Register session name
        $_SESSION['ID'] = $row['UserID'];
        header('location:dashboard.php'); // Redirect to Dashboard page
        exit();
    }

}
?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method='POST'>
<h4 class='text-center'>Admin Login</h4>
<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
<input class="btn btn-primary btn-block" type="submit" value="Login" />
</form>

<?php 
    include $tpl . "footer.php";
?>