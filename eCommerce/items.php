<?php 


   $pageTitle = 'Show Items';
   
   include 'init.php';

   // Check if get request catid is numeric & get the numeric value of it
   $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

   // Select all data depend on this id
   $stmt = $con->prepare("SELECT items.*
                                ,categories.Name AS category_name
                                ,users.Username FROM items
                                INNER JOIN
                                users
                                on 
                                users.UserID = items.Member_ID
                                INNER JOIN
                                categories
                                on 
                                categories.ID = items.Cat_ID
                                WHERE 
                                Item_ID = ?
                                AND
                                Approve = 1");

   // Excute query
   $stmt->execute(array($itemid));

   $count = $stmt->rowCount();

   if($count > 0){

   // Fetch the data
   $item = $stmt->fetch();
   
   ?>

   <h1 class='text-center'><?php echo $item['Name']; ?></h1>
   <div class='container'>
       <div class="row">
           <div class="col-md-3">
               <img src="layout/images/menu-burger.jpg" alt="" class="img-responsive img-thumbnail center-block">
           </div>
           <div class="col-md-9 item-info">
              <h2><?php echo $item['Name'] ?></h2>
              <p><?php echo $item['Description'] ?></p>
              <ul class="list-unstyled">
              <li>                  
                  <i class="fa fa-calendar fa-fw"></i>
                    <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                </li>
              <li>
              <i class="fa fa-money fa-fw"></i>
                <span>Price</span> : $<?php echo $item['Price'] ?>
            </li>
              <li>
              <i class="fa fa-building fa-fw"></i>
                  <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                </li>
              <li>
              <i class="fa fa-tags fa-fw"></i>
              <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
            </li>
                <li>
                  <i class="fa fa-user fa-fw"></i>
                  <span>Added By</span> : <a href="#"><?php echo $item['Username'] ?></a>
                </li>
                <li class='tags-items'>
                  <i class="fa fa-user fa-fw"></i>
                  <span>Tags</span> : 
                  <?php
                    $allTags = explode(",", $item['tags']);
                    foreach ($allTags as $tag){
                      $tag = str_replace(' ','', $tag);
                      $lowertag = strtolower($tag);
                      if(! empty($tag)){
                      echo "<a href='tags.php?name={$lowertag}'>" .$tag . '</a>';
                      }
                    }
                  ?>
                </li>
              </ul>
            </div>
       </div>
       <hr class='custom-hr'>
       <?php if(isset($_SESSION['user'])){ ?>
       <!-- Start add comment -->
       <div class="row">
         <div class="col-md-offset-3">
           <div class="add-comment">
           <h3>Add Your Comment</h3>
           <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID']?>" method='POST'>

                    <textarea name='comment' required></textarea>
                    <input class='btn btn-primary' type="submit" value="Add Comments">
              </form>
              <?php
              
                if($_SERVER['REQUEST_METHOD'] == 'POST'){

                  $comment  = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                  $itemid   = $item['Item_ID'];
                  $userid   = $_SESSION['uid'];

                  if(!empty($comment) ){
                    $stmt = $con->prepare("INSERT INTO
                    comments(comment, status, comment_date, item_id, user_id)
                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

                    $stmt->execute(array(
                      'zcomment' => $comment,
                      'zitemid'  => $itemid,
                      'zuserid'  => $userid
                    ));
                       
                    if($stmt){
                      
                      echo '<div class="alert alert-success">Comment Added</div>';
                      
                      }
                     
                  }else{
                    echo '<div class="alert alert-danger">You Must Add Comment</div>';
                  }
              
              ?>
         </div>
       </div>
   </div>
   <!-- End add comment -->
  
       <hr class='custom-hr'>
       
             <?php
           // select all user except admin

              $stmt = $con->prepare("SELECT 
              comments.*,  users.Username AS Member
              FROM comments 
              INNER JOIN 
              users
              ON 
              users.UserID = comments.user_id
              WHERE
              item_id = ?
              AND 
              status = 1
              ORDER BY c_id DESC
              ");

              // Excute the statement

              $stmt->execute(array(
                $item['Item_ID']
              ));

              // Assign to variable

              $comments = $stmt->fetchAll();

             

?>
            
              <?php
            foreach($comments as $comment){ ?>

            <div class="comment-box">

                      <div class="row">
                        
                      <div class="col-sm-2 text-center">
                      <img class="img-responsive img-thumbnail img-circle center-block" src="layout/images/menu-burger.jpg" alt=""/>  
                      <?php echo $comment['Member'] ?>
                    </div>
                      <div class="col-sm-10">
                        <p class='lead'><?php echo $comment['comment'] ?></p>
                      </div>
                      </div>
              </div>
              <hr class="custom-hr">
             <?php } ?>
       </div>
   </div>
   <?php 
  
  }

}else{
     
     echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';

  }
     }else{
       echo '<div class="container">';
         echo '<div class="alert alert-danger">There\'s No Such ID Or This Item Is Waiting Approval</div>';
         echo '</div>';
     }

   include $tpl . "footer.php";
    
    ?>