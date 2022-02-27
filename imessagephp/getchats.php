<?php
require 'connect.php';
error_reporting(E_ERROR);
//echo $_GET['id'];
// print_r($request);

// Sanitize
$chatid = $_GET['id'];
$chats = [];
$sql = "SELECT * FROM message where chatid = '{$chatid}'";
    

if($result = mysqli_query($con, $sql))
{
    $cr = 0;
    while($row = mysqli_fetch_assoc($result))
    {
        $chats[$cr]['chatid'] = $row['chatid'];
        $chats[$cr]['sender_id'] = $row['sender_id'];
        $chats[$cr]['msg'] = $row['msg'];
        $chats[$cr]['time'] = $row['time'];
        $cr++;
    }
//$chats[$cr]['lastindex'] = $cr;
    echo json_encode($chats);
}
else{
    http_response_code(404);
}

?>