<?php
include ("partial/menu.php");
?>

<div class="main-content">
<div class="wrapper">
<h1>CHANGE PASSWORD</h1>
<br/><br/>

<?php 
    if(isset($_GET['id'])){

        $id = $_GET['id'];
    }
?>

<form action="" method="post">

    <table class='tbl-30'>
        <tr>
            <td>Current Password:</td>
            <td>
                <input type="password" name="current_password" placeholder="Current password">
            </td>
        </tr>

        <tr>
            <td>New Password:</td>
            <td>
                <input type="password" name="new_password" placeholder="New password">
            </td>
</tr>

<tr>
            <td>Confirm Password:</td>
            <td>
                <input type="password" name="confirm_password" placeholder="Confirm password">
            </td>
        </tr>

        <tr> 
            <td colspan='2'>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name='submit' value='change Password' class='btn-secondary'>
            </td>
        </tr>
</table>

</form>


</div>
</div>

<?php
//check whether the submit button is clicked or not
if(isset($_POST['submit'])){
    // echo "clicked";

    //1. Get the data from form
    $id=$_POST['id'];
    $current_password = md5($_POST['current_password']);
    $new_password = md5($_POST['new_password']);
    $confirm_password = md5($_POST['confirm_password']);
    
    //2. Check whether the user with current ID and current password exists or not
    $sql = "select * from tbl_admin where id=$id and password='$current_password'";

    //Excute the query
    $res= mysqli_query($conn, $sql);

    if($res == true){
        //check whether data is available or not
        $count = mysqli_num_rows($res);

        if($count == 1){
            // user exists and password can be changed
            // echo 'user found';
            // check whether the new password and confirm match or not
            if($new_password == $confirm_password){
                //update the password
                // echo "Password match";
                $sql2 = "update tbl_admin set
                password='$new_password' where id=$id
                ";
                // Excute the query
                $res2 = mysqli_query($conn, $sql2);

                // check whether the query executed or not
                if($res2 == true){
                    //display success message
                    //redirect to manage admin page with success message
                    $_SESSION['change-pwd'] = "<div class='success'>Password changed successfully. </div>";
                    //Redirect the user
                header("location:".SITEURL.'admin/manage-admin.php');
                }else{
                    //display error message
                    //redirect to manage admin page with error message
                    $_SESSION['change-pwd'] = "<div class='error'>Failed to change password. </div>";
                    //Redirect the user
                header("location:".SITEURL.'admin/manage-admin.php');
                }
            }else{
                //redirect to manage admin page with error message
                $_SESSION['pwd-not-match'] = "<div class='error'>Password did not match. </div>";
            //Redirect the user
            header("location:".SITEURL.'admin/manage-admin.php');
            }
        }else{
            // user does not exist set message and redirect
            $_SESSION['user-not-found'] = "<div class='error'>User Not Found. </div>";
            //Redirect the user
            header("location:".SITEURL.'admin/manage-admin.php');

    }
    }
    //3. Check whether the new password and confirm password match or not

    //4. Change password if all above is true
}
?>

<?php 
    include('partial/footer.php');
?>