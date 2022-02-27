<?php
require 'connect.php';
error_reporting(E_ERROR);
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){

$request = json_decode($postdata);

// print_r($request);

// Sanitize
$useremail = $request->useremail;
$id = [];
$sql = "SELECT unique_id FROM user where email = '{$useremail}' LIMIT 1";

if($result = mysqli_query($con, $sql))
{
    
    while($row = mysqli_fetch_assoc($result))
    {
        $id = $row['unique_id'];
        
    }

    echo json_encode($id);
}
else{
    http_response_code(404);
}}

?>