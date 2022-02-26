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
        $path = "../images/category/".$image_name;
        // Remove the image
        $remove = unlink($path);

        // if failed to remove image then add an error message and stop the process
        if($remove == false){
            // Set the session message
            $_SESSION['remove'] = "<div class='error'>Failed to remove category image.</div>";
            // redirect to manage category page
            header("location:".SITEURL.'admin/manage-category.php');
            // stop the process
            die();
        }
    }

    // Delete Data from database
    // SQL query to delete data from database
    $sql = "delete from tbl_category where id=$id";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // check whether the data is delete from database or not
    if($res==true){
        // Set success message and redirect
        $_SESSION['delete'] = "<div class='success'>Category deleted successfully. </div>";
        // Redirect to manage category
        header("location:".SITEURL.'admin/manage-category.php');
    }else{
        // set fail message and redirect
        $_SESSION['delete'] = "<div class='error'>Failed to delete catrgory. </div>";
        // Redirect to manage category
        header("location:".SITEURL.'admin/manage-category.php'); 
    }
    // Redirect to manage category page with message

}else{
    // redirect to manage category page
    header("location:".SITEURL.'admin/manage-category.php');
}
?>