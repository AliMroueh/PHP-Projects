<?php
    include('partial/menu.php');
?>

<div class="main-content">
<div class="wrapper">
<h1>UPDATE CATEGORY</h1>
<br/><br/>

<?php
// check whether the id is set or not 
if(isset($_GET['id'])){
    // Get the ID and all other details
    // echo "getting the data";
    $id = $_GET['id'];
    // Create sql query to get all other details
    $sql = "select * from tbl_category where id=$id";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // Count the rows to check whether the id is valid or not 
    $count = mysqli_num_rows($res);

    if($count == 1){
        // Get all the data 
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $current_image = $row['image_name'];
        $featured = $row['featured'];
        $active = $row['active'];
    }else{
        // Redirect to manage category with session message
        $_SESSION['no-category-found'] = "<div class='error'>Category not found.</div>";
        header("location:".SITEURL.'admin/manage-category.php');

    }
}else{
    // redirect to manage category
    header("location:".SITEURL.'admin/manage-category.php');

}
?>

<form action="" method="post" enctype="multipart/form-data">
<table class="tbl-30">
        <tr>
            <td>Title: </td>
            <td>
            <input type="text" name="title" value="<?php echo $title;?>">
            </td>
        </tr>
        <tr>
            <td>Current Image: </td>
            <td>
            <?php
                if($current_image != ""){
                    // Display the image
                    ?>
                    <img src="<?php echo SITEURL; ?>images/category/<?php echo $current_image; ?>" width='150px' >
                    <?php
                }else{
                    // Display Message
                    echo "<div class='error'>Image not Added.</div>";
                }
                ?>
            </td>
        </tr>

        <tr>
            <td>New Image: </td>
            <td>
                <input type="file" name="image">
            </td>
        </tr>
        <tr>
            <td>Featured: </td>
            <td>
            <input <?php if($featured=="Yes"){ echo "checked";}?> type="radio" name="featured" value="Yes">Yes
            <input <?php if($featured=="No"){ echo "checked";}?> type="radio" name="featured" value="No">No
            </td>
        </tr>
        <tr>
        <td>Active: </td>
        <td>
        <input <?php if($active=="Yes"){ echo "checked";}?> type="radio" name="active" value="Yes" >Yes
        <input <?php if($active=="No"){ echo "checked";}?> type="radio" name="active" value="No" >No
        </td>
        </tr>

        <tr>
        <td>
        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="submit" value="Update category" class="btn-secondary">
        </td>
        </tr>
    </table>
    </form>

    <?php 
        if(isset($_POST['submit'])){
            // echo "clicked";
            // 1. Get all the values from our form

            $title = $_POST['title'];
            $current_image = $_POST['current_image'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // 2. updating new image if selected
            // check whether the image is selected or not
            if(isset($_FILES['image']['name'])){
                // Get the image details
                $image_name = $_FILES['image']['name'];
                // check whether the image is available or not
                if($image_name!=""){
                    // image available
                    //A. upload the new image
                    // upload the image
            // To upload image we need image name, source path and destination path
            $image_name = $_FILES['image']['name'];

            // Upload the image only if image is selected
            if($image_name != ""){

                // Auto rename our image 
                // Get the extension of our image (jpg, png, gif, etc) e.g "specialfood1.jpg"
                $ext = end(explode('.',$image_name));

                // Rename the image
                $image_name="Food_Category_".rand(000,999).'.'.$ext; // e.g. Food_Category_834.jpg
                $source_path = $_FILES['image']['tmp_name'];

                $destination_path = "../images/category/".$image_name;

                // Finally upload the image
                $upload = move_uploaded_file($source_path, $destination_path);

                // Check whether the image is uploaded or not 
                // And if the image is not uploaded then we will stop the process and redirect with error message
                if($upload==false){
                    // Set message
                    $_SESSION['upload'] = "<div class='error'>Failed to upload image. </div>";
                    // Redirect to add category page
                    header("location:".SITEURL.'admin/manage-category.php');
                    // Stop the process
                    die();

                    // B. Remove the current image if available
                    if($current_image !=""){
                        $remove_path = "../images/category/".$current_image;
                        $remove = unlink($remove_path);

                        // check whether the image is remove or not 
                        // if failed to remove then display message and stop the process
                        if($remove==false){
                        // Failed to remove image
                        $_SESSION['failed_remove'] = "<div class='error'>Failed to remove current image.</div>";
                        header("location:".SITEURL.'admin/manage-category.php');
                        die();// stop the process
                        }
                    }
                    
                }
                }
                }else{
                    $image_name = $current_image;
                }
            }else{
                $image_name = $current_image;
            }

            // 3. update the database 
            $sql2 = "update tbl_category set
            title = '$title',
            image_name = '$image_name',
            featured = '$featured',
            active = '$active'
            where id = $id";

            // Execute the query
            $res2 = mysqli_query($conn, $sql2);

            // 4. Redirect to manage category with message
            // Check whether executed or not
            if($res2==true){
                // category updated
                $_SESSION['update'] = "<div class='success'>Category updated successfully.</div>";
                header("location:".SITEURL.'admin/manage-category.php');

            }else{
                // failed to update category
                $_SESSION['update'] = "<div class='error'>Failed to update category.</div>";
                header("location:".SITEURL.'admin/manage-category.php');
            }
        } 
    ?>

</div>
</div>
<?php
    include('partial/footer.php');
?>