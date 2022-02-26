<?php 

   include 'init.php';
   ?>

   <div class='container'>
      <h1 class='text-center'>Show Category</h1>
      <div class='row'>
      <?php
      // $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
      if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
        $category = intval($_GET['pageid']);
        $allItems = getAllFrom1("*", "items", "where Cat_ID = {$category}", "AND Approve = 1", "Item_ID");
      foreach($allItems as $item){
        echo '<div class="col-md-3 col-sm-6">';
        echo '<div class="thumbnail item-box">';
        echo '<span class="price-tag">' . $item['Price'] . '</span>';
        echo '<img class="img-responsive" src="layout/images/menu-burger.jpg" alt=""/>';
        echo '<div class="caption">';
          echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
          echo '<p>' . $item['Description'] . '</p>';
          echo '<div class="date">' . $item['Add_Date'] . '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
    }else{
      echo 'You Must Add Page ID';
    }
      ?>
      </div>
 </div>

   <?php
    include $tpl . "footer.php";