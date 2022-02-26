<?php
// Include constants file
include('../config/constants.php');
// echo "Delete Page";
// check whether the id and image_name value is set or not
if(isset($_GET['id']) && isset($_GET['image_name'])){
    // Get the value and delete
    // echo "Get value and Delete";
    $id = $_GET['id'];
    $image_name = $_GET['image_name'];

    // Remove the physical image file is available
    if($image_name != ""){
        // image is available so remove it
        $path = "../images/food/".$image_name;
        // Remove the image
        $remove = unlink($path);

        // if failed to remove image then add an error message and stop the process
        if($remove == false){
            // Set the session message
            $_SESSION['upload'] = "<div class='error'>Failed to remove food image.</div>";
            // redirect to manage category page
            header("location:".SITEURL.'admin/manage-food.php');
            // stop the process
            die();
        }
    }

    // Delete Data from database
    // SQL query to delete data from database
    $sql = "delete from tbl_food where id=$id";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // check whether the data is delete from database or not
    // Redirect to manage category page with message
    if($res==true){
        // Set success message and redirect
        $_SESSION['delete'] = "<div class='success'>Food deleted successfully. </div>";
        // Redirect to manage category
        header("location:".SITEURL.'admin/manage-food.php');
    }else{
        // set fail message and redirect
        $_SESSION['delete'] = "<div class='error'>Failed to delete food. </div>";
        // Redirect to manage category
        header("location:".SITEURL.'admin/manage-food.php'); 
    }
    

}else{
    // redirect to manage category page
    $_SESSION['unauthorize'] = "<div class='error'>Unauthorized access. </div>";
    header("location:".SITEURL.'admin/manage-food.php');
}
?>