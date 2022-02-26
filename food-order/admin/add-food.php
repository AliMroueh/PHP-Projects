<?php 
include('partial/menu.php');
?>

<div class="main-content">
<div class="wrapper">
<h1>Add Food</h1>
<br/><br/>

<?php 
    if(isset($_SESSION['upload'])){
        echo $_SESSION['upload'];
        unset($_SESSION['upload']);
    }
?>

<form action="" method="post" enctype="multipart/form-data">

    <table class="tbl-30">
        <tr>
            <td>Title: </td>
            <td>
                <input type="text" name="title" placeholder="Title of the food">
            </td>
        </tr>

        <td>Description: </td>
            <td>
                <textarea name="description" cols="30" rows="5" placeholder="Description of the food"></textarea>
            </td>
        </tr>

        <tr>
            <td>Price: </td>
            <td>
                <input type="number" name="price" >
            </td>
        </tr>

        <tr>
            <td>Select Image: </td>
            <td>
                <input type="file" name="image" >
            </td>
        </tr>

        <tr>
            <td>Food: </td>
            <td>
                <select name="category">
                <?php 
                // Create php code to display categories from database
                // 1. Create sql to get all active categories from database
                $sql = "select * from tbl_category where active='Yes'";

                // Execute the query
                $res = mysqli_query($conn, $sql);
                
                // Count rows to check whether we have categories or not
                $count = mysqli_num_rows($res);

                // if count is greater than zero , we have categories else we do not have categories 
                if($count>0){
                    // We have categories
                    while($row=mysqli_fetch_assoc($res)){
                        // get the details of the categories
                        $id = $row['id'];
                        $title = $row['title'];
                         ?>
                          <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
                          <?php  
                    }
                }else{
                    // We do not have category
                    ?>
                    <option value="0">No category found</option>
                    <?php
    
                }
                
                // 2. Display on dropdown
                ?>

                </select>
            </td>
        </tr>

        <tr>
            <td>Feature: </td>
            <td>
                <input type="radio" name="featured" value="Yes" > Yes
                <input type="radio" name="featured" value="No" > No
            </td>
        </tr>

        <tr>
        <td>Active: </td>
            <td>
                <input type="radio" name="active" value="Yes" > Yes
                <input type="radio" name="active" value="No" > No
            </td>
        </tr>

        <tr>
            <td colspan="2">
            <input type="submit" name="submit" value="Add food" class="btn-secondary">
            </td>
        </tr>

    </table>
</form>

<?php
    // Check whether the button is clicked or not
    if(isset($_POST['submit'])){
        // Add the food in the database
        // echo "clicked";

        // 1. Get the data from form
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        // check whether radio button for featured and active are checked or not
        if(isset($_POST['featured'])){
            $featured = $_POST['featured'];
        }else{
            $featured = "No"; // Setting the default value
        }

        if(isset($_POST['active'])){
            $active = $_POST['active'];
        }else{
            $active = "No"; // Setting the default value
        }

        // 2.upload the image if selected
        // check whether the select image is clicked or  not and upload the image is selected
        if(isset($_FILES['image']['name'])){
            // Get the details of the selected image 
            $image_name = $_FILES['image']['name'];

            // check whether the image is selected or not and upload image only if selected
            if($image_name!=""){
                // Image is selected
                // A. Rename the image
                // Get the extension of selected image (jpg, png, gif, etc.)
                $ext = end(explode('.', $image_name));

                // Create new name for image
                $image_name = "Food-Name-".rand(0000,9999).".".$ext; // New image name may be "Food-Name-6350.png"

                // B. Upload the image
                // Get the src path and destination path 

                // Source path is the current location of the image
                $src = $_FILES['image']['tmp_name'];

                // Destination path for the image to be uploaded
                $dst = "../images/food/".$image_name;

                // Finally upload the food image
                $upload = move_uploaded_file($src, $dst);

                // Check whether image upload or not
                if($upload == false){
                    // Failed to upload the image
                    // Redirect to add food page with error message
                    $_SESSION['upload'] = "<div class='error'>Failed to upload image.</div>";
                    header("location:".SITEURL.'admin/add-food.php');

                    // Stop the process
                    die();
                }
            }
        }else{
            $image_name = ""; // Setting default value as blank
        }

        // 3. Insert into database
        
        // Create a SQL query to save or add food
        // For numerical value we do not need to pass value inside quotes '' but for string value it is compulsory to add quotes ''
        $sql2 = "insert into tbl_food set
        title = '$title' ,
        description = '$description',
        price = $price,
        image_name = '$image_name',
        category_id = $category,
        featured = '$featured',
        active = '$active'
        ";

        // Execute the query
        $res2 = mysqli_query($conn, $sql2);
        // Check whether data inserted or not 

         // 4. Redirect with message to manage food page
        if($res2 == true){
            // Data inserted successfully
            $_SESSION['add'] = "<div class='success'>Food Added successfully.</div>";
            header("location:".SITEURL.'admin/manage-food.php');
        }else{
            // Failed to insert data
            $_SESSION['add'] = "<div class='error'>Failed to add food .</div>";
            header("location:".SITEURL.'admin/manage-food.php');
        }

       
    }
?>

</div>
</div>

<?php 
include('partial/footer.php');
?>