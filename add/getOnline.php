<?php
    require_once '../php_class/connect.php';
    $db = (new BDconnect())->connect();
    session_start([
                      'cookie_lifetime' => 86400,
                  ]);
    //HTTP_USER_AGENT
    if (isset($_SESSION['user'])) {
        $SQL="UPDATE user set  
                online=CURRENT_TIMESTAMP(),
                UserAgent='{$_SERVER['HTTP_USER_AGENT']}'
                where id=".$_SESSION['user']['id'];
        $result = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        $SQL="SELECT COUNT(*) as COUNT from user where online > (CURRENT_TIMESTAMP() - INTERVAL 2 MINUTE ) and online is not null";
        $result = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        $ret=mysqli_fetch_all($result, MYSQLI_ASSOC);
        $SQL="SELECT FIO from user where online > (CURRENT_TIMESTAMP() - INTERVAL 2 MINUTE ) and online is not null";
        $result = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        $ret['user']=mysqli_fetch_all($result, MYSQLI_ASSOC);
        //$ret['$_CLIENT']=$_SERVER;
        die(json_encode($ret));
    }