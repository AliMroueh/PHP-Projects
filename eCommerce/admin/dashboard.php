<?php

ob_start(); // output buffering start // for the problem related to header

session_start();

if($_SESSION['Username']){

    $pageTitle = "Dashboard";

    include 'init.php';

    $numUsers = 6; // Number of latest user

    $latestUsers = getLatest('*', 'users', 'UserID', $numUsers); // Latest user array

    $numItems = 6; // Number of latest item

    $latestItems = getLatest('*', 'items', 'Item_ID', $numItems); // Latest item array

    $numComments = 4;

      /* Start Dashboard page */
?>
<div class="home-stats">
    <div class="container text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class='fa fa-users'></i>
                <div class='info'>
                Total members
                <span><a href="members.php"><?php echo countItems('UserID', 'users')?></a></span>
                </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-pending">
                <div class='info'>
                    <i class='fa fa-user-plus'></i>
               <a href='members.php?do=Manage&page=Pending'> Pending members </a>
                <span>
                <?php echo checkItem('RegStatus', 'users', 0)?>
                </span>
                </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-items">
                <div class='info'>
                    <i class='fa fa-tag'></i>
                Total Items
                    <span><a href='items.php'><?php echo countItems('Item_ID', 'items')?></a></span>
                    </div>
                    </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-comments">
                <div class='info'>
                    <i class='fa fa-comments'></i>
                Total Comments
                <span><a href='comments.php'><?php echo countItems('c_id', 'comments')?></a></span>
                </div>
                </div>
            </div>
        </div>
    </div>
        </div>

        <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                <div class="panel panel-default">
                
                    <div class="panel-heading">
                        <span class='toggle-info pull-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                        <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registered Users
                    </div>
                    <div class="panel-body">
                    <ul class='list-unstyled latest-users'>
                    <?php    
                    if(! empty($latestUsers)){
                    foreach($latestUsers as $user){
                        echo '<li>';
                            echo $user['Username'];
                                echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                        echo '<i class="fa fa-edit"></i> Edit';
                        if($user['RegStatus'] == 0){
                            echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '" class="btn btn-info pull-right activate"><i class="fa fa-check"></i> Activate</a>';
                          }
                                    echo '</span>';
                                echo '</a>';
                        echo '</li>';
                    }
                }else{
                    echo 'There\'s No Members To Show';
                }
                    ?>
                    </ul>
                    </div>
                </div>
                </div>

                <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <span class='toggle-info pull-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                        <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                    </div>
                    <div class="panel-body">
                    <ul class='list-unstyled latest-users'>
                    <?php    
                    if(! empty($latestItems)){
                    foreach($latestItems as $item){
                        echo '<li>';
                            echo $item['Name'];
                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                        echo '<i class="fa fa-edit"></i> Edit';
                        if($item['Approve'] == 0){
                            echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class="btn btn-info pull-right activate"><i class="fa fa-check"></i> Approve</a>';
                          }
                                    echo '</span>';
                                echo '</a>';
                        echo '</li>';
                    }
                }else{
                    echo 'There\'s No Items To Show';
                }
                    ?>
                    </ul>
                    
                    </div>
                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                <div class="panel panel-default">
                
                    <div class="panel-heading">
                        <span class='toggle-info pull-right'>
                            <i class='fa fa-plus fa-lg'></i>
                        </span>
                        <i class="fa fa-comments-o"></i> Latest <?php echo $numComments; ?> Comments
                    </div>
                    <div class="panel-body">
                    <?php             
                       $stmt = $con->prepare("SELECT 
                       comments.*, users.Username AS Member
                       FROM comments 
                        INNER JOIN 
                       users
                       ON users.UserID = comments.user_id
                       ORDER BY c_id DESC
                       LIMIT $numComments
                       ");
                       $stmt->execute();
                       $comments = $stmt->fetchAll();

                       if(! empty($comments)){

                       foreach($comments as $comment){
                        
                        echo '<div class="comment-box">';
                            echo '<span class="member-n">
                            <a href="members.php?do=Edit&userid=' . $comment['user_id'] . '">' . $comment['Member'] . '</a></span>';
                            echo '<p class="member-c">'. $comment['comment'] . '</p>';
                        echo '</div>';
                        }
                    }else{
                        echo 'There\'s No Comments To Show';
                    }
                    //    foreach($rows as $row){
                    //        echo '<li>';
                    //        echo '<div class="box">';
                    //        echo '<span class="box-name">';
                    //        echo $row['Member'];
                    //        echo '</span>';
                    //        echo '<p class="pull-right member-box">';
                    //        echo $row['comment'];
                    //        echo '</p>';
                    //        echo '</div>';

                    //        echo '</li>';
                    //    }
                           ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>

<?php
      /* End Dashboard page */

    include $tpl . "footer.php";
}else{
    header('location:index.php');
    exit();
}

ob_end_flush();

?>