<?php

session_start();

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start mange page
    if($do == 'Manage'){ // Manage members page 

      $query = '';

      if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
        $query = 'AND RegStatus = 0';
      }
    // select all user except admin

    $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

    // Excute the statement

    $stmt->execute();

    // Assign to variable

    $rows = $stmt->fetchAll();
    
    if(! empty($rows)){
    
    ?>

        <h1 class='text-center'>Manage Members</h1>
        <div class='container'>
          <div class="table-responsive">
          <table class="main-table text-center table table-bordered">
          <tr>
          <td>#ID</td>
          <td>Username</td>
          <td>Email</td>
          <td>Full Name</td>
          <td>Registered Date</td>
          <td>Control</td>
          </tr>

            <?php
            
            foreach($rows as $row){

              echo '<tr>';
                    echo '<td>' . $row['UserID'] . '</td>';
                    echo '<td>' . $row['Username'] . '</td>';
                    echo '<td>' . $row['Email'] . '</td>';
                    echo '<td>' . $row['FullName'] . '</td>';
                    echo '<td>' . $row['Date'] . '</td>';
                    echo '<td> 
                    <a href="members.php?do=Edit&userid=' . $row['UserID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                    <a href="members.php?do=Delete&userid=' . $row['UserID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>';

                      if($row['RegStatus'] == 0){
                        echo '<a href="members.php?do=Activate&userid=' . $row['UserID'] . '" class="btn btn-info activate"><i class="fa fa-check"></i> Activate</a>';
                      }

                    echo '</td>';
              echo '</tr>';
            }
            
            ?>
          </div>
</table>
          <a href="members.php?do=Add" class='btn btn-primary'><i class="fa fa-plus"></i> New Member</a>
        </div>

        <?php }else{
          echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Members To Show</div>';
            echo "<a href='members.php?do=Add' class='btn btn-primary'><i class='fa        fa-plus'></i> New Member</a>";
          echo '</div>'; 
        } ?>

  <?php  
  }elseif($do == 'Add'){

      // Add page
?>
      <h1 class='text-center'>Add New Member</h1>
        <div class='container'>
        <form class="form-horizontal" action='?do=Insert'  method='POST'>
        <!-- Start username field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="username" class='form-control' autocomplete='off' required = 'required' placeholder="Username to login into shop">
        </div>
        </div>
        <!-- End username field -->
        <!-- Start Password field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10 col-md-6">
        <input type="password" name="password" class='password form-control' autocomplete='password' placeholder="Password must be hard and complex" required = 'required'>
        <i class="show-pass fa fa-eye fa-2x"></i>
        </div>
        </div>
        <!-- End Password field -->
        <!-- Start Email field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10 col-md-6">
        <input type="email" name="email" class='form-control' required = 'required' placeholder="Email must be valid">
        </div>
        </div>
        <!-- End Email field -->
        <!-- Start Full Name field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Full Name</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="full" class='form-control' required = 'required' placeholder="Full name appear in your profile page">
        </div>
        </div>
        <!-- End Full Name field -->
        <!-- Start Submit field -->
        <div class="form-group form-group-lg">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Add Member" class='btn btn-primary btn-lg'>
        </div>
        </div>
        <!-- End Submit field -->
        </form>
        </div>
<?php
}elseif($do == 'Insert'){


      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        echo  "<h1 class='text-center'>Add Member</h1>";
        echo "<div class='container'>";

        //   Get variables from Form

        $user = $_POST['username'];
        $pass = $_POST['password']; // we do not put the sha1 here since when we check if empty password it will not return empty but a coded text 
        $email = $_POST['email'];
        $name = $_POST['full'];

        $hashPass = sha1($_POST['password']);

        // Validate the form
        $formErrors = array();

        if(strlen($user) < 4) {
          $formErrors[] = 'Username can not be less than <strong>4 characters</strong>';
        }

        if(strlen($user) > 20 ) {
          $formErrors[] = 'Username can not be greater than <strong>20 characters</strong>';
        }

        if(empty($user)){
          $formErrors[] = 'Username can not be <strong>empty</strong>';
        }

        if(empty($pass)){
          $formErrors[] = 'Password can not be <strong>empty</strong>';
        }

        if(empty($name)){
          $formErrors[] = 'Full name can not be <strong>empty</strong>';
        }

        if(empty($email)){
          $formErrors[] = 'Email can not be <strong>empty</strong>';
        }

        foreach($formErrors as $errors){
          echo '<div class="alert alert-danger">' . $errors . '</div>';
        }

        // Check if there is no error proceed the update operation

        if(empty($formErrors)){

          // Check if user exist in database

          $check = checkItem('Username' , 'users' , $user);

          if($check == 1){
            $theMsg = "<div class='alert alert-danger'>Sorry this user is exists</div>";
            
            redirectHome($theMsg,'back');

          }else{

            // Insert userinfo in the database

            $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,FullName,RegStatus,Date)
            VALUES(:zuser, :zpass, :zmail, :zname, 1, now() )");

            $stmt->execute(array(
              'zuser' => $user ,
              'zpass' => $hashPass,
              'zmail' => $email,
              'zname' => $name
            ));

            // Echo success message

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';

            redirectHome($theMsg,'back');
        }
      }

      }else{
 
        echo "<div class='container'>";
        $theMsg = "<div class='alert alert-danger'>Sorry you can not browse this page directly</div>";

        redirectHome($theMsg);

        echo "</div>";
      }
      echo '</div>';

    }elseif($do == 'Edit'){

        // Check if get request userid is numeric & get the numeric value of it
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Select all data depend on this id

        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

        // Excute query

        $stmt->execute(array($userid));

        // Fetch the data

        $row = $stmt->fetch();

        // The row count

        $count = $stmt->rowCount();
        
        // If there is such id show the form 

        if($count > 0){
       
        ?>

        <h1 class='text-center'>Edit Member</h1>
        <div class='container'>
        <form class="form-horizontal" action='?do=Update'  method='POST'>
        <input type="hidden" name="userid" value="<?php echo $userid?>">
        <!-- Start username field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="username" value="<?php echo $row['Username']?>" class='form-control' autocomplete='off' required = 'required'>
        </div>
        </div>
        <!-- End username field -->
        <!-- Start Password field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10 col-md-6">
        <input type="hidden" name="oldpassword" value='<?php echo $row['Password']?>' >
        <input type="password" name="newpassword" class='form-control' autocomplete='new-password' placeholder="Leave blank if you do not want to change the password">
        <!-- autocomplete='new-password' it will make the browser do not remember the password -->
        </div>
        </div>
        <!-- End Password field -->
        <!-- Start Email field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10 col-md-6">
        <input type="email" name="email" value="<?php echo $row['Email']?>" class='form-control' required = 'required'>
        </div>
        </div>
        <!-- End Email field -->
        <!-- Start Full Name field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Full Name</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="full" value="<?php echo $row['FullName']?>" class='form-control' required = 'required'>
        </div>
        </div>
        <!-- End Full Name field -->
        <!-- Start Full Name field -->
        <div class="form-group form-group-lg">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Save" class='btn btn-primary btn-lg'>
        </div>
        </div>
        <!-- End Full Name field -->
        </form>
        </div>

        <?php

        // if there is no id show error message
         
        }else{

          echo '<div class="container">';

          $theMsg = '<div class="alert alert-danger">there is no such id</div>';

            redirectHome($theMsg,'back');

            echo '</div>';
        }
    }elseif($do == 'Update'){

      echo  "<h1 class='text-center'>Update Member</h1>";
      echo "<div class='container'>";

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //   Get variables from Form

        $id = $_POST['userid'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $name = $_POST['full'];

        $pass= empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

        // Validate the form
        $formErrors = array();

        if(strlen($user) < 4) {
          $formErrors[] = 'Username can not be less than <strong>4 characters</strong>';
        }

        if(strlen($user) > 20 ) {
          $formErrors[] = 'Username can not be greater than <strong>20 characters</strong>';
        }

        if(empty($user)){
          $formErrors[] = 'Username can not be <strong>empty</strong>';
        }

        if(empty($pass)){
          $formErrors[] = 'Password can not be <strong>empty</strong>';
        }

        if(empty($name)){
          $formErrors[] = 'Full name can not be <strong>empty</strong>';
        }

        if(empty($email)){
          $formErrors[] = 'Email can not be <strong>empty</strong>';
        }

        foreach($formErrors as $errors){
          echo '<div class="alert alert-danger">' . $errors . '</div>';
        }

        // Check if there is no error proceed the update operation

        if(empty($formErrors)){

          $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
          $stmt2->execute(array($user,$id));
          $count = $stmt2->rowCount();

          if($count == 1){

            $theMsg =  '<div class="alert alert-danger">Sorry This User Is Exist</div>';

          redirectHome($theMsg,'back');
       
        }else{
           // Update the datase with this info

        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ? ");
        
        $stmt->execute(array($user,$email,$name,$pass,$id));

        // Echo success message

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

        redirectHome($theMsg,'back');
        }
      }
      }else{

        echo "Sorry you can not browse this page directly";

      }
    
    
      echo '</div>';
    

    }elseif($do == 'Delete'){

      echo  "<h1 class='text-center'>Delete Member</h1>";
      echo "<div class='container'>";

      // Check if get request userid is numeric & get the numeric value of it
      $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

      // Select all data depend on this id

      $check = checkItem('userid', 'users', $userid);

      // If there is such ID show the form

      if($check > 0){

        $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

        $stmt->bindParam(':zuser' , $userid);

        $stmt->execute();

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';

        redirectHome($theMsg,'back');
      } else{

        $theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";

        redirectHome($theMsg);
      }

      echo '</div>';

    }elseif($do == 'Activate'){
      
      echo  "<h1 class='text-center'>Activate Member</h1>";
      echo "<div class='container'>";

      // Check if get request userid is numeric & get the numeric value of it
      $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

      // Select all data depend on this id

      $check = checkItem('userid', 'users', $userid);

      // If there is such ID show the form

      if($check > 0){

        $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

        $stmt->execute(array($userid));

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

        redirectHome($theMsg);
      } else{

        $theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";

        redirectHome($theMsg);
      }

      echo '</div>';
    }

    include $tpl . 'footer.php';
}else {
    header('location : index.php');
    exit();

}