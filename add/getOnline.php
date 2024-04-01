<?php
    require_once '../php_class/connect.php';
    $db = (new BDconnect())->connect();
    session_start();
    if (isset($_SESSION['user'])) {
        $SQL="UPDATE user set  
                online=CURRENT_TIMESTAMP() 
                where id=".$_SESSION['user']['id'];
        $result = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        $SQL="SELECT COUNT(*) as COUNT from user where online > (CURRENT_TIMESTAMP() - INTERVAL 2 MINUTE )";
        $result = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        die(json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC)));
    }