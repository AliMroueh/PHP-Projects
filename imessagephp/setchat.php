<?php
require 'connect.php';

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){

$request = json_decode($postdata);

// print_r($request);

// Sanitize
$chatid = $request->id;
$userid = $request->userid;
$msg = $request->data;

// store
//$sql= "INSERT INTO user(
//    fName,
//    lName,
//    email,
//    unique_id
//)VALUES(
//    '{$first_name}',
//    '{$last_name}',
//    '{$email}',
//    '{$unique_id}'
//) WHERE NOT EXISTS (
//SELECT email FROM user WHERE email = '{$email}
//)";
    
    $sql=" INSERT INTO message (
    chatid, 
    sender_id, 
    msg)
VALUES(
'{$chatid}', 
'{$userid}', 
'{$msg}'
)";
    
//    $sql= "INSERT INTO students(
//    fName,
//    lName,
//    email
//)VALUES(
//    '{$first_name}',
//    '{$last_name}',
//    '{$email}'
//)";

// if(mysqli_query($con,$sql)){
//     http_response_code(201);
// }else{
//     http_response_code(422);
// }
if (mysqli_query($con, $sql)) {
    // echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
  }
}
?>