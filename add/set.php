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
    function del($table,$id){
        $db = (new BDconnect())->connect();
        //$USER_ID=$_SESSION['user']['id'];
        $id =(int)$id;
        if ($id>0) {
            $SQL = "DELETE FROM $table where id = '$id'";
            $query = mysqli_query($db, $SQL) or
            die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));

        }
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
    $USER_ID=$_SESSION['user']['id'];
    /*********
     * Tag
     ********/
    if (isset($_GET['tag'])) {
        if (isset($_POST['tag'])) {
            if (isset($_GET['del'])) {
                die(del('tag', $_POST['tag']));
            } else {
                die(add('tag', $_POST['tag']));
            }
        } else die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
    }
    /*********
     * sci_theme
     ********/
    if (isset($_GET['sci_theme'])) {
        if (isset($_POST['sci_theme'])) {
            if (isset($_GET['del'])) {
                die(del('sci_theme', $_POST['sci_theme']));
            } else {
                die(add('sci_theme', $_POST['sci_theme']));
            }
        } else die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
    }
    /*********
     * sci_department
     ********/
    if (isset($_GET['sci_department'])) {
            $db = (new BDconnect())->connect();
            if (isset($_GET['del'])) {
                //die(del('tag', $_POST['tag']));
            } else {
                /*Очистка от каки*/
                foreach ($_POST as $i=>$value) {
                    if (!is_array($value)) {
                        $_POST[$i] = mysqli_escape_string($db, $value);
                    }
                }
                $name=$_POST['sci_department_name'];
                $date1=$_POST['sci_department_date1'];
                $date2=$_POST['sci_department_date2'];

                /*sci_department_owner array*/


                //die(add('tag', $_POST['tag']));
            }

    }
    /*********
     * PERSON
     ********/
    if (isset($_GET['pers'])) {
        $db = (new BDconnect())->connect();
            if (isset($_GET['del'])) {
                //die(del('sci_field', $_POST['sci_field']));
            } else {
                /*Очистка от каки*/
                foreach ($_POST as $i=>$value) {
                    if (!is_array($value)) {
                        $_POST[$i] = mysqli_escape_string($db, $value);
                    }
                }
                /** Основные*/
                $pers_F=$_POST['pers_F'];
                $pers_I=$_POST['pers_I'];
                $pers_O=$_POST['pers_O'];
                $pers_date1=$_POST['pers_date1'];
                $pers_date2=$_POST['pers_date2'];
                $pers_Desc=$_POST['pers_Desc'];
                $pers_dol=$_POST['pers_dol'];
                $SQL="INSERT INTO person (F, I, O, comment, dol, dayN, dayD, create_user) value 
                    ('$pers_F','$pers_I','$pers_O','$pers_Desc','$pers_dol','$pers_date1','$pers_date2','$USER_ID')";
                $result=mysqli_query($db, $SQL) or
                    die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
                $InsertId=mysqli_insert_id($db);
                /** теги pers_tag[]*/
                if (!empty($_POST['pers_tag'])) {
                    foreach ($_POST['pers_tag'] as $i=>$value) {
                        if (is_numeric($value)){
                            $value=(int)$value;
                            $SQL="INSERT INTO tag_person (idTag, idPerson) value ($value,$InsertId)";
                            $result=mysqli_query($db, $SQL) or
                                die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
                        } else {
                            /* TODO обработка если ключевые слова добавили ручками*/
                        }
                    }
                }
                /** структурное подразделение *** pers_SP[]*/

                /** Научная тематика *** pers_tem[]*/
                if (!empty($_POST['pers_SP'])) {
                    foreach ($_POST['pers_SP'] as $i=>$value) {
                        if (is_numeric($value)){
                            $value=(int)$value;
                            $SQL="INSERT INTO sci_theme_pers (idTheme, idPers)  value ($value,$InsertId)";
                            $result=mysqli_query($db, $SQL) or
                                die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
                        } else {
                            /*TODO обработка если ключевые слова добавили ручками*/
                        }
                    }
                }

            }

       // } else die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
    }
}