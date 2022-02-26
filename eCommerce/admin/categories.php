<?php

session_start();

$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start mange page
    if($do == 'Manage'){ // Manage members page 

      $sort = 'ASC';

      $sort_array = array('ASC', 'DESC');

      if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
        $sort = $_GET['sort'];
      }

        $stmt2 = $con->prepare("SELECT * FROM categories where parent = 0 ORDER BY Ordering $sort");

        $stmt2->execute();

        $cats = $stmt2->fetchAll();

      
        ?>

        <h1 class='text-center'>Manage Categories</h1>
        <div class="container categories">
        <div class="panel panel-default">
        <div class="panel-heading">
        <i class="fa fa-edit"></i> Manage Categories
        <div class='option pull-right'>
        <i class="fa fa-sort"></i> Ordering: [
        <a href='?sort=ASC' class='<?php if($sort == 'ASC'){ echo 'active'; }?>'>Asc</a> |
        <a href='?sort=DESC' class='<?php if($sort == 'DESC'){ echo 'active'; }?>'>Desc</a> ]
        <i class="fa fa-eye"></i> View: [
        <span class='active' data-view='full'>Full</span> |
        <span>Classic</span> ]
        </div>
        </div>
        <div class="panel-body">
        <?php
        
          foreach($cats as $cat){
            echo '<div class="cat">';
            echo '<div class="hidden-buttons">';
              echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
              echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";

            echo '</div>';
             echo "<h3>" . $cat['Name']."</h3>";
             echo "<div class='full-view'>";
                echo "<p>"; 
                if($cat['Description'] == ''){ echo 'This category has no description'; }else{echo $cat['Description']; } echo '</p>';
                if($cat['Visibility'] == 1){ echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';}
                if($cat['Allow_Comment'] == 1){ echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
                if($cat['Allow_Ads'] == 1){ echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
                echo '</div>';

                 // Get Child Categories
            $ChildCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID", "ASC");
            if(! empty($ChildCats)){
         
           echo '<h4 class="child-head">Child Categories</h4>';
           echo '<ul class="list-unstyled child-cats">';
           foreach ($ChildCats as $c){
          echo '<li class="child-link">
          <a href="categories.php?do=Edit&catid=' . $c['ID'] . '">' . $c["Name"] . 
          '</a>
          <a href="categories.php?do=Delete&catid=' . $c['ID'] . '" class="show-delete confirm">Delete</a>
          </i>';
         }
         echo '</ul>';
         }
            
            echo '</div>';

           echo '<hr>';
        }

        ?>
        </div>
        </div>
        <a href='categories.php?do=Add' class='add-category btn btn-primary'>Add New Categories</a>
        </div>

        <?php

    }elseif($do == 'Add'){
?>
        <h1 class='text-center'>Add New Category</h1>
        <div class='container'>
        <form class="form-horizontal" action='?do=Insert'  method='POST'>
        <!-- Start Name field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="name" class='form-control' autocomplete='off' required = 'required' placeholder="Name Of The Category">
        </div>
        </div>
        <!-- End Name field -->
        <!-- Start Description field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Description</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="description" class='form-control' placeholder="Describe The Category" />
        </div>
        </div>
        <!-- End Description field -->
        <!-- Start Ordering field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Ordering</label>
        <div class="col-sm-10 col-md-6">
        <input type="text" name="ordering" class='form-control' placeholder="Number To Arrange The Categories">
        </div>
        </div>
        <!-- End Ordering field -->
        <!-- Start Category Type -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Parent?</label>
        <div class="col-sm-10 col-md-6">
        <select name="parent">
          <option value="0">None</option>

          <?php
            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
            foreach($allCats as $cat) {
              echo "<option value='" . $cat['ID'] . "'>" . $cat["Name"] . "</option>";
            }
          ?>
        </select>
        </div>
        </div>
        <!-- End Category Type -->
        
        <!-- Start Visibility field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Visible</label>
        <div class="col-sm-10 col-md-6">
        <div>
        <input type="radio" name="visibility" id="vis-yes" value='0' checked />
        <label for='vis-yes'>Yes</label>
        </div>

        <div>
        <input type="radio" name="visibility" id="vis-no" value='1' />
        <label for='vis-no'>No</label>
        </div>
        </div>
        </div>
        <!-- End Visibility field -->

         <!-- Start Commenting field -->
         <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Allow Commenting</label>
        <div class="col-sm-10 col-md-6">
        <div>
        <input type="radio" name="commenting" id="com-yes" value='0' checked />
        <label for='com-yes'>Yes</label>
        </div>

        <div>
        <input type="radio" name="commenting" id="com-no" value='1' />
        <label for='com-no'>No</label>
        </div>
        </div>
        </div>
        <!-- End Commenting field -->

        <!-- Start Ads field -->
        <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Allow Ads</label>
        <div class="col-sm-10 col-md-6">
        <div>
        <input type="radio" name="ads" id="ads-yes" value='0' checked />
        <label for='ads-yes'>Yes</label>
        </div>

        <div>
        <input type="radio" name="ads" id="ads-no" value='1' />
        <label for='ads-no'>No</label>
        </div>
        </div>
        </div>
        <!-- End Ads field -->

        <!-- Start Submit field -->
        <div class="form-group form-group-lg">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Add Category" class='btn btn-primary btn-lg'>
        </div>
        </div>
        <!-- End Submit field -->
        </form>
        </div>
<?php
    }elseif($do == 'Insert'){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo  "<h1 class='text-center'>Add Category</h1>";
            echo "<div class='container'>";
    
            //   Get variables from Form
    
            $name = $_POST['name'];
            $desc = $_POST['description']; // we do not put the sha1 here since when we check if empty password it will not return empty but a coded text 
            $parent = $_POST['parent'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];
    
              // Check if category exist in database
    
              $check = checkItem('Name' , 'categories' , $name);
    
              if($check == 1){
                $theMsg = "<div class='alert alert-danger'>Sorry this category is exists</div>";
                
                redirectHome($theMsg,'back');
    
              }else{
    
                // Insert userinfo in the database
    
                $stmt = $con->prepare("INSERT INTO categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads )");
    
                $stmt->execute(array(
                  'zname'   => $name ,
                  'zdesc'   => $desc,
                  'zparent'=> $parent,
                  'zorder'  => $order,
                  'zvisible' => $visible,
                  'zcomment' => $comment,
                  'zads' => $ads
                ));
    
                // Echo success message
    
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
    
                redirectHome($theMsg,'back');
            }
    
          }else{
     
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>Sorry you can not browse this page directly</div>";
    
            redirectHome($theMsg);
    
            echo "</div>";
          }
          echo '</div>';

    }elseif($do == 'Edit'){

       // Check if get request catid is numeric & get the numeric value of it
       $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

       // Select all data depend on this id

       $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");

       // Excute query

       $stmt->execute(array($catid));

       // Fetch the data

       $cat = $stmt->fetch();

       // The row count

       $count = $stmt->rowCount();
       
       // If there is such id show the form 

       if($count > 0){
      
?>
      <h1 class='text-center'>Edit Category</h1>
      <div class='container'>
      <form class="form-horizontal" action='?do=Update'  method='POST'>
      <input type="hidden" name="catid" value="<?php echo $catid?>">
      <!-- Start Name field -->
      <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10 col-md-6">
      <input type="text" name="name" class='form-control' required = 'required' placeholder="Name Of The Category" value="<?php echo $cat['Name']; ?>">
      </div>
      </div>
      <!-- End Name field -->
      <!-- Start Description field -->
      <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Description</label>
      <div class="col-sm-10 col-md-6">
      <input type="text" name="description" class='form-control' placeholder="Describe The Category" value="<?php echo $cat['Description']; ?>"/>
      </div>
      </div>
      <!-- End Description field -->
      <!-- Start Ordering field -->
      <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Ordering</label>
      <div class="col-sm-10 col-md-6">
      <input type="text" name="ordering" class='form-control' placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering']; ?>">
      </div>
      </div>
      <!-- End Ordering field -->
       <!-- Start Category Type -->
       <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Parent?</label>
        <div class="col-sm-10 col-md-6">
        <select name="parent">
          <option value="0">None</option>

          <?php
            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
            foreach($allCats as $c) {
              echo "<option value='" . $c['ID'] . "'";
              if($cat['parent']  == $c['ID']) { echo 'selected'; }
              echo ">" . $c["Name"] . "</option>";
            }
          ?>
        </select>
        </div>
        </div>
        <!-- End Category Type -->
      <!-- Start Visibility field -->
      <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Visible</label>
      <div class="col-sm-10 col-md-6">
      <div>
      <input type="radio" name="visibility" id="vis-yes" value='0' <?php if($cat['Visibility'] == '0'){ echo 'checked';}?> />
      <label for='vis-yes'>Yes</label>
      </div>

      <div>
      <input type="radio" name="visibility" id="vis-no" value='1' <?php if($cat['Visibility'] == '1'){ echo 'checked';}?>/>
      <label for='vis-no'>No</label>
      </div>
      </div>
      </div>
      <!-- End Visibility field -->

       <!-- Start Commenting field -->
       <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Allow Commenting</label>
      <div class="col-sm-10 col-md-6">
      <div>
      <input type="radio" name="commenting" id="com-yes" value='0' <?php if($cat['Allow_Comment'] == '0'){ echo 'checked';}?> />
      <label for='com-yes'>Yes</label>
      </div>

      <div>
      <input type="radio" name="commenting" id="com-no" value='1' <?php if($cat['Allow_Comment'] == '1'){ echo 'checked';}?> />
      <label for='com-no'>No</label>
      </div>
      </div>
      </div>
      <!-- End Commenting field -->

      <!-- Start Ads field -->
      <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Allow Ads</label>
      <div class="col-sm-10 col-md-6">
      <div>
      <input type="radio" name="ads" id="ads-yes" value='0' <?php if($cat['Allow_Ads'] == '0'){ echo 'checked';}?> />
      <label for='ads-yes'>Yes</label>
      </div>

      <div>
      <input type="radio" name="ads" id="ads-no" value='1' <?php if($cat['Allow_Ads'] == '1'){ echo 'checked';}?> />
      <label for='ads-no'>No</label>
      </div>
      </div>
      </div>
      <!-- End Ads field -->

      <!-- Start Submit field -->
      <div class="form-group form-group-lg">
      <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" value="Update Category" class='btn btn-primary btn-lg'>
      </div>
      </div>
      <!-- End Submit field -->
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

      echo  "<h1 class='text-center'>Update Categories</h1>";
      echo "<div class='container'>";

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //   Get variables from Form

        $id = $_POST['catid'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $order = $_POST['ordering'];
        $parent = $_POST['parent'];
        $visible = $_POST['visibility'];
        $comment = $_POST['commenting'];
        $ads = $_POST['ads'];
        // $visibility = if($_POST['visibility'])

        $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, parent = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ?
         WHERE ID = ?");

        $stmt->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));

        // Echo success message

        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

        redirectHome($theMsg,'back');
        

      }else{

        echo "Sorry you can not browse this page directly";

      }

      echo '</div>';
    

    }elseif($do == 'Delete'){

      echo  "<h1 class='text-center'>Delete Member</h1>";
      echo "<div class='container'>";

      // Check if get request userid is numeric & get the numeric value of it
      $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

      // Select all data depend on this id
      $check = checkItem('ID', 'categories' , $catid);

      // If there is such ID show the form
      if($check > 0){
        $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zcat");

        $stmt->bindParam(':zcat', $catid);

        $stmt->execute();

        $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted </div>';

        redirectHome($theMsg,'back');
      }else{
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
