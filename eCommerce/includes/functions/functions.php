<?php

/*
** Get All Function v2.0
** Function To Get All Records From Any Database Table
*/

function getAllFrom1($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC"){

    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;
}

/* 
** Get All function v 1.0
** Function to get All Records from Any Database Table
*/

function getAllFrom($tableName, $orderBy, $Where = NULL){

    global $con;

    $sql = $Where == NULL ? '' : $Where;

    $getAll = $con->prepare("SELECT * FROM $tableName $sql ORDER BY $orderBy DESC");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;
}

/* 
** Get categories function v 1.0
** Function to get Categories from database 
*/

function getCat(){

    global $con;

    $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

    $getCat->execute();

    $Cats = $getCat->fetchAll();

    return $Cats;
}


/* 
** Get AD items function v 2.0
** Function to get AD Categories from database 
*/

function getItems($where, $value, $approve = NULL){

    global $con;

    $sql = $approve == NULL ? 'AND Approve = 1' : '';

    $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");

    $getItems->execute(array($value));

    $items = $getItems->fetchAll();

    return $items;
}

/* 
** Check if user is not activated
** Function to check the RegStatus of the user
*/

function checkUserStatus($user){
    global $con;

    $stmtx = $con->prepare("SELECT Username, RegStatus FROM users WHERE Username = ? AND RegStatus = 0");
    $stmtx->execute(array($user));
    
    $status= $stmtx->rowCount(); // check how many row have the username and password

    return $status;
}


/*
 ** Title function v1.0
 ** title function that echo the page title in case the page has the variable $pageTitle and echo default title for other pages
*/

function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else{
        echo 'Default';
    }
}

/*
** Home redirect function v2.0
** This function accept parameter
** $theMsg = echo the error message [ error | success | warning]
** url = The link you want to direct to
** $seconds = seconds before redirecting
*/

function redirectHome($theMsg, $url = null, $seconds = 3) {

    if($url == null){
        $url = 'index.php';

        $link = 'Homepage';
    }else{

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

        $url = $_SERVER['HTTP_REFERER'];

        $link = 'Previous page';
        }else{
            $url = 'index.php';

            $link = 'Homepage';
        }
       }
    echo $theMsg;
    echo "<div class='alert alert-info'>You will be redirect to $link after $seconds seconds. </div>";
    header("refresh:$seconds;url=$url");
    exit();
}

/*
** Check items function v1.0
** Function to check item in database [ function accept parameters ]
** $select = The item to select [ Example: user, item, category ]
** $from = The table to select from [ Example: users, items, categories ]
** $value = The value of select [ Example: osama, box, electronics]
*/

function checkItem($select, $from, $value){
    global $con;

    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");

    $statement->execute(array($value));

    $count = $statement->rowCount();

    return $count;

}

/*
** Count number of items function v1.0
** Function to count number of items rows
** $item = the item to count
** $table = the table to choose from
*/

function countItems($item, $table){

    global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();

    return $stmt2->fetchColumn();
}

/* 
** Get latest records function v 1.0
** Function to get latest items from database [ Users, Items, Comments ]
** $select = Field To Select
** $table = The Table To Choose From
** $order = The Desc order
** $limit = Number Of Records To Get
*/

function getLatest($select, $table, $order, $limit = 5){

    global $con;

    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

    $getStmt->execute();

    $rows = $getStmt->fetchAll();

    return $rows;
}