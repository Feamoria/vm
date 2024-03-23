<?php
    require_once '../php_class/connect.php';
    session_start();
    if (isset($_GET['person'])){
        $db = (new BDconnect())->connect();
        $SQL = "SELECT * FROM person ;";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));;
        $data = mysqli_fetch_all($query,MYSQLI_ASSOC);
        die(json_encode($data,JSON_UNESCAPED_UNICODE));
    }
