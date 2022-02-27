<?php
require 'connect.php';

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){

$request = json_decode($postdata);

// print_r($request);

// Sanitize
$chatName = $request->chatName;
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
    
    $sql=" INSERT INTO chatname (chatName)
SELECT * FROM (SELECT '{$chatName}') AS tmp
WHERE NOT EXISTS (
    SELECT chatName FROM chatname WHERE chatName = '{$chatName}'
) LIMIT 1";

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