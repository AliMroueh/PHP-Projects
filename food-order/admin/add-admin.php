<?php
include ("partial/menu.php");
?>


<div class="main-content">
<div class="wrapper">
<h1>ADD ADMIN</h1>
<br/><br/>
<?php
if(isset($_SESSION['add'])){ // Checking whether the session is set or not 
    echo $_SESSION['add'];// Display the session message if set
    unset($_SESSION['add']);// Remove session message
}
?>
<br/><br/>
<form action="" method="post">
    <table class="tbl-30">
        <tr>
            <td>Full name: </td>
            <td><input type="text" name="full_name" placeholder="Enter your name"></td>
        </tr>

        <tr>
            <td>Username: </td>
            <td><input type="text" name="username" placeholder="Enter your username"></td>
        </tr>

        <tr>
            <td>Password: </td>
            <td><input type="password" name="password" placeholder="Enter your password"></td>
        </tr>

        <tr>
            <td colspan="2">
            <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
            </td>
        </tr>
</table>
</form>

</div>
</div>

<?php
include ("partial/footer.php");
?>

<?php
    // Process the value from Form and save it in database

    // Check whether the button is clicked or not 

    if(isset($_POST['submit'])){
        // Button clicked 
        // echo 'button clicked';

        //1. Get the data from Form
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $password = md5($_POST['password']); // password encryption with MD5

        //2. SQL query to save the data into database
        $sql = "insert into tbl_admin set
        full_name='$full_name',
        username='$username',
        password='$password'";

        //3. Excuting query and saving data into database
        $res = mysqli_query($conn,$sql) or die(mysql_error());

        //4. Check whether the (query is executed) data is inserted or not and display appropriate message
        if($res == TRUE){
            // data inserted
            // echo 'data inserted';
            // create a session variable to display message
            $_SESSION['add'] = 'Admin Added Successfully';
            // Redirct page to manage admin
            header("location:".SITEURL.'admin/manage-admin.php');
        }else{
            // failed to inser data
            // echo 'failed to inser data';
            $_SESSION['add'] = 'Failed to Add Admin';
            // Redirct page to add admin
            header("location:".SITEURL.'admin/add-admin.php');
        }
    }
?>