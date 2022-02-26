<?php
include("partial/menu.php");
?>

<div class="main-content">
    <div class="wrapper">
    <h1>Update Food</h1>
    <br><br>
    <?php
    // check whether the id is set or not
    if(isset($_GET['id'])){
        // Get all the details
        $id = $_GET['id'];

        // display the information from database
        $sql = "select * from tbl_food where id=$id";

        $res=mysqli_query($conn, $sql);

        $count = mysqli_num_rows($res);

        if($count==1){

            $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $description = $row['description'];
        $price = $row['price'];
        $current_image = $row['image_name'];
        $current_category = $row['category_id'];
        $featured = $row['featured'];
        $active = $row['active'];
    }else{
        header("location:".SITEURL.'admin/manage-food.php');
    }
}

    ?>

    <form action="" method="post" enctype="multipart/form-data">

    <table class="tbl-30">
        <tr>
            <td>Title: </td>
            <td>
                <input type="text" name="title" value='<?php echo $title;?>'>
            </td>
        </tr>

        <td>Description: </td>
            <td>
                <textarea name="description" cols="30" rows="5"><?php echo $description;?></textarea>
            </td>
        </tr>

        <tr>
            <td>Price: </td>
            <td>
                <input type="number" name="price" value='<?php echo $price;?>'>
            </td>
        </tr>

        <tr>
        <td>Current image: </td>
        <td>
        <?php
        if($current_image == ""){
            // Image is not available
            echo "<div class='error'>Image not available.</div>";
        }else{
            // Image available
            ?>  
         
        <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width='150px' >
        </td> 
        <?php 
        }           
            ?>
        </tr>

        <tr>
            <td>Select new Image: </td>
            <td>
                <input type="file" name="image" >
            </td>
        </tr>
        <tr>
                <td>Category: </td>
                <td>
                <select name="category">
                <?php
                // Query to get active categories
                    $sql1= "select * from tbl_category where active='Yes'";
                    // Execute the query
                    $res1 = mysqli_query($conn, $sql1);
                    // count rows
                    $count1 = mysqli_num_rows($res1);
                    // check whether category available or not
                    if($count1>0){
                        // Category available
                        while($row1=mysqli_fetch_assoc($res1)){
                            $category_title = $row1['title'];
                            $category_id = $row1['id'];
                            // echo "<option value='$category_id'>$category_title</option>";
                            
                            ?>
                            <option <?php if($current_category==$category_id){ echo 'selected';} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                            <?php
                        }
                    }else{
                        // Category not available
                        echo "<option value='0'>Category not available.</option>";
                    }

                ?>
                </select>
                </td>
               
        </tr>
        <tr>
            <td>Feature: </td>
            <td>
                <input type="radio" name="featured" <?php if($featured == 'Yes') echo 'checked'; ?> value="Yes"> Yes
                <input type="radio" name="featured" <?php if($featured == 'No') echo 'checked'; ?> value="No"> No
            </td>
        </tr>

        <tr>
        <td>Active: </td>
            <td>
                <input type="radio" name="active" <?php if($active == 'Yes') echo 'checked'; ?> value="Yes"> Yes
                <input type="radio" name="active" <?php if($active == 'No') echo 'checked'; ?> value="No"> No
            </td>
        </tr>

        <tr>
            <td>
            <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submit" value="Update food" class="btn-secondary">
            </td>
        </tr>
    
    
    </table>
    
    </form>

    <?php
         
        
        // 3. update the database
        // 4. Redirect to manage category with message

    if(isset($_POST['submit'])){

        // 1. Get all the values from our form
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $current_image = $_POST['current_image'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $featured = $_POST['featured'];
        $active = $_POST['active'];
        // 2. updating new image if selected
        if(isset($_FILES['image']['name'])){
        //A. upload the new image
            $image_name = $_FILES['image']['name']; // new image name
            // Check whether the file is available or not
            if($image_name != ''){

                $ext = end(explode('.',$image_name));

                $image_name="Food-Name-".rand(0000,9999).".".$ext;

                $source_path = $_FILES['image']['tmp_name'];

                $destination_path = "../images/food/".$image_name; 

                $upload = move_uploaded_file($source_path, $destination_path);

                // Check whether the image is uploaded or not
                if($upload == false){
                    // Set message
                    $_SESSION['upload'] = "<div class='error'>Failed to upload new image. </div>";
                    // Redirect to add category page
                    header("location:".SITEURL."admin/manage-food.php");
                    // Stop the process
                    die();
                }
                // B. Remove the current image if available

                if($current_image!=''){
                    $remove_path = "../images/food/".$current_image;
                    $remove = unlink($remove_path);

                    if($remove == false){
                        // Failed to remove image
                        $_SESSION['failed_remove'] = "<div class='error'>Failed to remove current image.</div>";
                        header("location:".SITEURL.'admin/manage-food.php');
                        die();// stop the process
                    }
                }

            }else{
                $image_name = $current_image;
            }

        }else{
            $image_name = $current_image;
        }

        $sql2 = "update tbl_food set
        title = '$title',
        description = '$description',
        price = '$price',
        image_name = '$image_name',
        featured = '$featured',
        active = '$active'
        where id = $id
        ";

        $res2 = mysqli_query($conn, $sql2);

        if($res2==true){
            // category updated
            $_SESSION['update'] = "<div class='success'>Food updated successfully.</div>";
            header("location:".SITEURL.'admin/manage-food.php');

        }else{
            // failed to update category
            $_SESSION['update'] = "<div class='error'>Failed to update food.</div>";
            header("location:".SITEURL.'admin/manage-food.php');
        }
        }
    
    ?>   
</div>
    </div>

    <?php
include("partial/footer.php");
?>