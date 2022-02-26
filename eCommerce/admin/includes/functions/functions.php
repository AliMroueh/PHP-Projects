<?php

/*
** Get All Function v2.0
** Function To Get All Records From Any Database Table
*/

function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC"){

    global $con;

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;
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