<?php
 function add($table,$tag){
        $db = (new BDconnect())->connect();
            $USER_ID=$_SESSION['user']['id'];
         $tag = mysqli_real_escape_string($db, $tag);
         $SQL = "SELECT * FROM $table where Name = '$tag'";
         $query = mysqli_query($db, $SQL) or
         die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
         if (mysqli_num_rows($query) > 0) {
             die(json_encode(['err' => 'Ошибка добавления:1:уже существует'], JSON_UNESCAPED_UNICODE));
         }
         $SQL = "Insert into $table (Name,create_user) value ('$tag',$USER_ID)";
         mysqli_query($db, $SQL) or
         die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
         $SQL = "SELECT * FROM $table order by Name";
         $query = mysqli_query($db, $SQL) or
         die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
         $data=mysqli_fetch_all($query,MYSQLI_ASSOC);
         return json_encode($data, JSON_UNESCAPED_UNICODE);
         //die();

 }
require_once '../php_class/connect.php';
session_start();
//var_dump($_POST);
if (isset($_SESSION['user'])) {
    if (isset($_GET['tag'])) {
        if (isset($_POST['tag'])) {
            die(add('tag',$_POST['tag']));
        } else die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['sci_field'])) {
        if (isset($_POST['sci_field'])) {
            die(add('sci_field',$_POST['sci_field']));
        } else die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
    }
}