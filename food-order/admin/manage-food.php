<?php
include('partial/menu.php');
?>

<div class="main-content">
<div class="wrapper">
<h1>MANAGE FOOD</h1>
<br/><br/>
<?php
if(isset($_SESSION['add'])){
        echo $_SESSION['add'];
        unset($_SESSION['add']);
    }
    if(isset($_SESSION['delete'])){
        echo $_SESSION['delete'];
        unset($_SESSION['delete']);
    }
    if(isset($_SESSION['upload'])){
        echo $_SESSION['upload'];
        unset($_SESSION['upload']);
    }
    if(isset($_SESSION['unauthorize'])){
        echo $_SESSION['unauthorize'];
        unset($_SESSION['unauthorize']);
    }
    if(isset($_SESSION['remove'])){
        echo $_SESSION['remove'];
        unset($_SESSION['remove']);
    }
    if(isset($_SESSION['failed_remove'])){
        echo $_SESSION['failed_remove'];
        unset($_SESSION['failed_remove']);
    }
    if(isset($_SESSION['update'])){
        echo $_SESSION['update'];
        unset($_SESSION['update']);
    }
    ?>
<br/><br/><br/>

<!-- Button to add admin -->
<a href="<?php echo SITEURL; ?>admin/add-food.php" class="btn-primary">Add food</a>
<br/><br/><br/>
<table class="tbl-full">
    <tr>
        <th>S.N.</th>
        <th>Title</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image</th>
        <th>Featured</th>
        <th>Active</th>
        <th>Actions</th>
    </tr>

    <?php 
    // Create a SQL query to get all the food
    $sql = "select * from tbl_food";

    // Excute the query
    $res = mysqli_query($conn, $sql);

    // Count rows to check whether we have foods or not
    $count = mysqli_num_rows($res);

    // Create serial number variable and set default value as 1
    $sn=1;

    if($count>0){
        // we have food in database
        // Get the foods from datase and display
        while($row=mysqli_fetch_assoc($res)){
            // get the values from individual columns
            $id = $row['id'];
            $title = $row['title'];
            $description = $row['description'];
            $price = $row['price'];
            $image_name = $row['image_name'];
            $featured = $row['featured'];
            $active = $row['active'];
            ?>

            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $title; ?></td>
                <td><?php echo $description; ?></td>
                <td><?php echo $price; ?></td>
                <td><?php 
                
                // check whether we have image or not
                if($image_name == ""){
                    // we do not have image, display error message
                    echo "<div class='error'>Image not added.</div>";
                }else{
                    // We have image, display image
                    ?>
                    <img src="<?php echo SITEURL ; ?>images/food/<?php echo $image_name; ?>" width="100px">
                    <?php
                }
                
                ?></td>
                <td><?php echo $featured; ?></td>
                <td><?php echo $active; ?></td>
                <td>
                    <a href="<?php echo SITEURL; ?>admin/update-food.php?id=<?php echo $id; ?>" class="btn-secondary">Update Food</a>
                    <a href="<?php echo SITEURL; ?>admin/delete-food.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-danger">Delete Category</a>
                </td>
            </tr>

            <?php
        }
    }else{
        // Food not added in database
        echo "<tr><td colspan='7' class='error'>Food not added yet. </td></tr>";
    }
    ?>

    

    
</table>
</div>
</div>

<?php
include('partial/footer.php');
?>