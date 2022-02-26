<?php 
    include('partial/menu.php');
?>

<!-- Menu content section starts -->    
<div class="main-content">
<div class="wrapper">
<h1>DASHBOARD</h1>
<br><br>
<?php
    if(isset($_SESSION['login'])){
    echo $_SESSION['login'];
    unset($_SESSION['login']);
}
?>
<br><br>
<?php 
// sql query
$sql = "select * from tbl_category";
// Execute query
$res = mysqli_query($conn, $sql);
// Count Rows
$count = mysqli_num_rows($res);
?>
<div class="col-4 text-center">
    <h1><?php echo $count; ?></h1>
    <br/>
    Categories
</div>

<div class="col-4 text-center">

<?php 
// sql query
$sql2 = "select * from tbl_food";
// Execute query
$res2 = mysqli_query($conn, $sql2);
// Count Rows
$count2 = mysqli_num_rows($res2);
?>

    <h1><?php echo $count2; ?></h1>
    <br/>
    Foods
</div>

<div class="col-4 text-center">

<?php 
// sql query
$sql3 = "select * from tbl_order";
// Execute query
$res3 = mysqli_query($conn, $sql3);
// Count Rows
$count3 = mysqli_num_rows($res3);
?>

    <h1><?php echo $count3; ?></h1>
    <br/>
    Total Orders
</div>

<div class="col-4 text-center">

    <?php
    
    // create sql query to get total revenue generated
    // Aggregation function in sql
    $sql4 = "select sum(total) as Total from tbl_order where status='Delivered'";

    // Execute the query
    $res4 = mysqli_query($conn, $sql4);

    // Get the value
    $row4 = mysqli_fetch_assoc($res4);

    // Get the Total revenue
    $total_revenue = $row4['Total'];
    
    ?>

    <h1>$<?php echo $total_revenue;?></h1>
    <br/>
    Revenue Generated
</div>
<div class="clearfix"></div>
</div>
</div>
<!-- Menu content section ends -->  

<?php 
    include('partial/footer.php');
?>