
<?php
require 'connect.php';
error_reporting(E_ERROR);
$chats = [];
$sql = "SELECT * FROM chatname";

if($result = mysqli_query($con, $sql))
{
    $cr = 0;
    while($row = mysqli_fetch_assoc($result))
    {
        $chats[$cr]['chatName'] = $row['chatName'];
        $chats[$cr]['chatId'] = $row['chatID'];
        $cr++;
    }

    echo json_encode($chats);
}
else{
    http_response_code(404);
}

?>