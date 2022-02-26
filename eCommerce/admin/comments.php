<?php

ob_start();

session_start();

$pageTitle= 'Comments';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start mange page
    if($do == 'Manage'){ // Manage members page 

         // select all user except admin

    $stmt = $con->prepare("SELECT 
    comments.*, items.Name AS Item_Name , users.Username AS Member
    FROM comments 
    INNER JOIN 
    items
    ON items.Item_ID = comments.item_id
    INNER JOIN 
    users
    ON users.UserID = comments.user_id
    ORDER BY c_id DESC
    ");

    // Excute the statement

    $stmt->execute();

    // Assign to variable

    $comments = $stmt->fetchAll();
    if(! empty($comments)){
        ?>

<h1 class='text-center'>Manage Comments</h1>
        <div class='container'>
          <div class="table-responsive">
          <table class="main-table text-center table table-bordered">
          <tr>
          <td>#ID</td>
          <td>Comment</td>
          <td>Item Name</td>
          <td>User Name</td>
          <td>Added Date</td>
          <td>Control</td>
          </tr>

            <?php
            
            foreach($comments as $comment){

              echo '<tr>';
                    echo '<td>' . $comment['c_id'] . '</td>';
                    echo '<td>' . $comment['comment'] . '</td>';
                    echo '<td>' . $comment['Item_Name'] . '</td>';
                    echo '<td>' . $comment['Member'] . '</td>';
                    echo '<td>' . $comment['comment_date'] . '</td>';
                    echo '<td> 
                    <a href="comments.php?do=Edit&comid=' . $comment['c_id'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                    <a href="comments.php?do=Delete&comid=' . $comment['c_id'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>';

                      if($comment['status'] == 0){
                        echo '<a href="comments.php?do=Approve&comid=' . $comment['c_id'] . '" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                      }

                    echo '</td>';
              echo '</tr>';
            }
            
            ?>

</table>
          <a href="comments.php?do=Add" class='btn btn-primary'><i class="fa fa-plus"></i> New Comment</a>
        </div>
        <?php }else{
          echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Comment To Show</div>';
            echo '</div>';  
        } ?>
<?php

    }elseif($do == 'Edit'){

      // Check if get request userid is numeric & get the numeric value of it
      $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      // Select all data depend on this id

      $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");

      // Excute query

      $stmt->execute(array($comid));

      // Fetch the data

      $row = $stmt->fetch();

      // The row count

      $count = $stmt->rowCount();
      
      // If there is such id show the form 

      if($count > 0){
     
      ?>

      <h1 class='text-center'>Edit Comments</h1>
      <div class='container'>
      <form class="form-horizontal" action='?do=Update'  method='POST'>
      <input type="hidden" name="comid" value="<?php echo $comid?>">
      <!-- Start comment field -->
      <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Comment</label>
      <div class="col-sm-10 col-md-6">
      <textarea class='form-control' name='comment'><?php echo $row['comment']; ?></textarea>
      </div>
      </div>
      <!-- End comment field -->
     
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

      echo  "<h1 class='text-center'>Update Comments</h1>";
      echo "<div class='container'>";

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //   Get variables from Form

        $comid = $_POST['comid'];
        $comment = $_POST['comment'];

        // Update the datase with this info
        $stmt = $con->prepare('UPDATE comments SET  comment = ? WHERE c_id = ?');

        $stmt->execute(array($comment,$comid));

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Updated</div>";

        redirectHome($theMsg,'back');
        

      }else{

        $theMsg = "<div class='alert alert-danger'>Sorry you can not browse this page directly</div>";

        redirectHome($theMsg);
      }

      echo '</div>';
    

    }elseif($do == 'Delete'){

      echo  "<h1 class='text-center'>Delete Comment</h1>";
      echo "<div class='container'>";

      // Check if get request userid is numeric & get the numeric value of it
      $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      // Select all data depend on this id
      $check = checkItem('c_id', 'comments' , $comid);

      // If there is such ID show the form
      if($check > 0){
        $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");

        $stmt->bindParam(':zid', $comid);

        $stmt->execute();

        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Comments Deleted </div>';

        redirectHome($theMsg,'back');
      }else{
        $theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";

        redirectHome($theMsg);
      }
      echo '</div>';

    }elseif($do == 'Approve'){

      echo  "<h1 class='text-center'>Approve Comment</h1>";
      echo "<div class='container'>";

      // Check if get request userid is numeric & get the numeric value of it
      $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      // Select all data depend on this id

      $check = checkItem('c_id', 'comments', $comid);

      // If there is such ID show the form

      if($check > 0){

        $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

        $stmt->execute(array($comid));

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Approved</div>';

        redirectHome($theMsg,'back');
      } else{

        $theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";

        redirectHome($theMsg);
      }

      echo '</div>';

    }
    include $tpl . 'footer.php';
}else{
    header('Location: index.php');

    exit();
}

ob_end_flush();
?>
