<?php

include('partial/menu.php');

    // // include constants.php file here
    // include('../config/constants.php');

    ?>

    <div class="main-content">
    <div class="wrapper">
    <h1>Update Admin</h1>
    <br><br>

    <?php 
        // 1. Get the ID of selected Admin
        $id= $_GET['id'];

        //2. Create SQL Query to get the details
        $sql = "select * from tbl_admin where id=$id";

        // Excute the Query
        $res = mysqli_query($conn, $sql);

        // check whether the query is executed or not 
        if($res == true){
            // check whether the data is available or not
            $count = mysqli_num_rows($res);
            //check whether we have admin data or not
            if($count == 1){
                //get the details
                // echo "Admin available";
                $row= mysqli_fetch_assoc($res);

                $full_name = $row['full_name'];
                $username = $row['username'];
            }else{
                // redirect to manage admin page
                header('location:'.SITEURL.'admin/manage-admin.php'); // we make this step so if one write a id that is not found in the database table up in the url to redirect it to the admin page . This is made for hacker
            }
        }
    ?>

    <form action="" method="post">
    <table class="tbl-30">
    <tr>

    <td>Full Name:</td>
    <td>
    <input type="text" name="full_name" value="<?php echo $full_name?>">
    </td>
</tr>
<tr>
    <td>Username:</td>
    <td>
    <input type="text" name="username" value="<?php echo $username?>">
    </td>

    </tr>
    <tr>
        <td colspan="2">
            <input type="hidden" name="id" value="<?php echo $id; ?>"> 
        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
        </td>
    </tr>
    </table>
    </form>
    </div>
    </div>

    <?php 
    // check whether the submit button is clicked or not
    if(isset($_POST['submit'])){
        // echo 'button click';
        // Get all the values from form to update
        $id = $_POST['id'];
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];

        //create a sql query to update admin
        $sql = "update tbl_admin set 
        full_name = '$full_name',
        username = '$username'
        where id='$id'";

        // Execute the query
        $res = mysqli_query($conn, $sql);

        // check whether the query executed successfully or not
        if($res == true){
            // query executed and admin update
            $_SESSION['update'] = "<div class='success'>Admin updated successfully.</div>";
            // Redirect to manage admin page
            header('location:'.SITEURL.'admin/manage-admin.php');
        }else{
            // failed to update admin
             $_SESSION['update'] = "<div class='error'>Failed to delete admin.</div>";
             // Redirect to manage admin page
             header('location:'.SITEURL.'admin/manage-admin.php');
        }
    }
    ?>

<?php 
    include('partial/footer.php');
?>