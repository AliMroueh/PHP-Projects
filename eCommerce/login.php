<?php

    
    $pageTitle = "Login";

    if(isset($_SESSION['user'])){
        header('location:index.php'); // Redirect to index page
        $sessionUser = $_SESSION['user'];
    }

    include 'init.php';
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['login'])){

    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $hashedPass = sha1($pass);

    // Check if the user exist in the database

    $stmt = $con->prepare("SELECT Username, Password, UserID FROM users WHERE Username = ? AND Password = ?");
    $stmt->execute(array($user,$hashedPass));

    $get = $stmt->fetch();
    
    $count = $stmt->rowCount(); // check how many row have the username and password

    if($count > 0){
        $_SESSION['user'] = $user; // Register session name
        $_SESSION['uid'] = $get['UserID']; // Register user id in session
        header('location:index.php'); // Redirect to Dashboard page
        exit();
    }
}else{
    $formErrors = array();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];

    if(isset($username)){

        $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

        if(strlen($filterdUser) < 4){
            $formErrors[] = "Username must be larger than 4 characters";
        }
    }

    if(isset($password) && isset($password2)){

        if(empty($password)){
            $formErrors[] = 'Sorry password can not be empty';
        }

        if(sha1($password) != sha1($password2)){
            $formErrors[] = 'Sorry Password Is Not Match';
        }
    }

    if(isset($email)){

        $filterdEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if(filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){
            $formErrors[] = 'This Email Is Not Valid';
        }
    }

    // Check if there is no error proceed the user add

    if(empty($formErrors)){

        // Check if user exist in database

        $check = checkItem('Username' , 'users' , $username);

        if($check == 1){
          
            $formErrors[] = "Sorry this user is exists";

        }else{

          // Insert userinfo in the database

          $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,RegStatus,Date)
          VALUES(:zuser, :zpass, :zmail, 0, now() )");

          $stmt->execute(array(
            'zuser' => $username ,
            'zpass' => sha1($password),
            'zmail' => $email
          ));

          // Echo success message

          $successMsg = 'Congrats You Are Now Registerd User';
      }
    }

}

}
?>
    <div class='container login-page'>
        <h1 class='text-center'>
            <span class='selected' data-class='login'>Login</span> | <span data-class='signup'>Signup</span>
        </h1>
        <!-- Start Login form -->
        <form class='login' action="<?php echo $_SERVER['PHP_SELF']?>" method='POST'>
            <div class='input-container'>
            <input class='form-control' type='text' name='user' autocomplete='off' placeholder='Type your username' required/>
</div>
            <div class='input-container'>
            <input class='form-control' type='password' name='pass' autocomplete='new-password' placeholder='Type your password' required/>
</div>
            <input class='btn btn-primary btn-block' type='submit' name='login' value='Login'/>

</form>
<!-- End Login form -->
<!-- Start Signup form -->
<form class='signup' action="<?php echo $_SERVER['PHP_SELF']?>" method='POST'>
<div class='input-container'>
            <input pattern=".{4,}" title="Username must be 4 charecters" class='form-control' type='text' name='username' autocomplete='off' placeholder='Type your username' required/>
</div>
<div class='input-container'>
            <input minlength='4' class='form-control' type='password' name='password' autocomplete='new-password' placeholder='Type a complex password' required/>
</div>
<div class='input-container'>
            <input minlength='4' class='form-control' type='password' name='password2' autocomplete='new-password' placeholder='Type a password again' required/>
</div>
<div class='input-container'>
            <input class='form-control' type='email' name='email' placeholder='Type a valid email' required/>
</div>

            <input class='btn btn-success btn-block' type='submit' name='signup' value='Signup'/>
</form>
<!-- End Signup form -->
<div class='the-errors text-center'>
    <?php 
    if(!empty($formErrors)){
        foreach($formErrors as $error){
            echo '<div class="msg error">' . $error . '</div>';
        }
    }

    if(isset($successMsg)){
        echo '<div class="msg success">' . $successMsg . '</div>';
    }
    ?>
</div>
</div>

<?php
    include $tpl . 'footer.php';
?>