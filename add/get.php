<?php
require_once '../php_class/connect.php';
session_start();
if (isset($_GET['person'])) {
    $db = (new BDconnect())->connect();
    $SQL = "SELECT `id`, CONCAT(F,' ',I,' ',O) as Name FROM person ;";
    $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    die(json_encode($data, JSON_UNESCAPED_UNICODE));
}
if (isset($_GET['tag'])) {
    $db = (new BDconnect())->connect();
    $SQL = "SELECT id,Name FROM tag;";
    $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    die(json_encode($data, JSON_UNESCAPED_UNICODE));
}
if (isset($_GET['sci_field'])) {
    $db = (new BDconnect())->connect();
    $SQL = "SELECT id,Name FROM sci_field;";
    $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    die(json_encode($data, JSON_UNESCAPED_UNICODE));
}
