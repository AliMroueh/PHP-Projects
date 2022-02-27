<?php
require 'connect.php';
$c=1;
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){

$request = json_decode($postdata);

// print_r($request);

// Sanitize
$first_name = $request->first_name;
$last_name = $request->last_name;
$email = $request->email;
$unique_id = $request->unique_id;

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
    
    $sql=" INSERT INTO user (fName, lName, email)
SELECT * FROM (SELECT '{$first_name}', '{$last_name}', '{$email}') AS tmp
WHERE NOT EXISTS (
    SELECT email FROM user WHERE email = '{$email}'
) LIMIT 1";
    $c++;

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