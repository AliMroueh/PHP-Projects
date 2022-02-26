<?php
include('partial/menu.php');
?>

<div class="main-content">
<div class="wrapper">
<h1>MANAGE CATEGORY</h1>
<br/><br/><br/>
<?php
    if(isset($_SESSION['add'])){
        echo $_SESSION['add'];
        unset($_SESSION['add']);
    }
    if(isset($_SESSION['remove'])){
        echo $_SESSION['remove'];
        unset($_SESSION['remove']);
    }
    if(isset($_SESSION['delete'])){
        echo $_SESSION['delete'];
        unset($_SESSION['delete']);
    }
    if(isset($_SESSION['no-category-found'])){
        echo $_SESSION['no-category-found'];
        unset($_SESSION['no-category-found']);
    }
    if(isset($_SESSION['update'])){
        echo $_SESSION['update'];
        unset($_SESSION['update']);
    }
    if(isset($_SESSION['upload'])){
        echo $_SESSION['upload'];
        unset($_SESSION['upload']);
    }
    if(isset($_SESSION['failed_remove'])){
        echo $_SESSION['failed_remove'];
        unset($_SESSION['failed_remove']);
    }
?>
<br/><br/>
<!-- Button to add admin -->
<a href="<?php echo SITEURL; ?>admin/add-category.php" class="btn-primary">Add category</a>
<br/><br/><br/>
<table class="tbl-full">
    <tr>
        <th>S.N.</th>
        <th>Title</th>
        <th>Image</th>
        <th>Feature</th>
        <th>Active</th>
        <th>Actions</th>
    </tr>

    <?php
    // Query to get all categories from database
        $sql = "select * from tbl_category";

        // Execute query
        $res = mysqli_query($conn, $sql);

        // Count rows
        $count = mysqli_num_rows($res);

        // Create serial number variable and assign value as 1
        $sn=1;

        // check whether we have data in database or not
        if($count>0){
            // we have data in database
            // get the data and display
            while($row=mysqli_fetch_assoc($res)){
                $id=$row['id'];
                $title=$row['title'];
                $image_name = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];

                ?>

    <tr>
        <td><?php echo $sn++; ?></td>
        <td><?php echo $title; ?></td>

        <td>
        <?php
        // check whether image name is available or not 
        if($image_name != ""){
            // display the image
            ?>
            <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" width='100px'>
            <?php
        }else{
            // display the message
            echo "<div class='error'> Image not added.</div>";
        }
        ?>
        </td>

        <td><?php echo $featured; ?></td>
        <td><?php echo $active; ?></td>
        
        <td>
            <a href="<?php echo SITEURL; ?>admin/update-category.php?id=<?php echo $id; ?>" class="btn-secondary">Update Category</a>
            <a href="<?php echo SITEURL; ?>admin/delete-category.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-danger">Delete Category</a>
        </td>
    </tr>

<?php
            }

        }else{
            // we do not have the data
            // we will display the message inside table
            ?>
            <tr>
                <td colspan="6"><div class="error">No category added.</div></td>
            </tr>
            <?php
        }
    ?>

    
</table>
</div>
</div>

<?php
include('partial/footer.php');
?>