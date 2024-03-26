<?php

    require_once '../php_class/connect.php';
    session_start([
                      'cookie_lifetime' => 86400,
                  ]);
    if (isset($_GET['person'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT `id`, CONCAT(F,' ',I,' ',O) as Name, F, I, O, COMMENT, DOL, DAYN, DAYD, CREATE_DATE, CREATE_USER FROM person ;";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data = mysqli_fetch_all($query, MYSQLI_ASSOC);

        foreach ($data as $i=>$val) {
            //ТЕГИ
            $SQL="SELECT tag.Name FROM tag,(select * from tag_person where idPerson={$val['id']}) as tag_person
                    where tag.id=tag_person.idTag";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['tag']=mysqli_fetch_all($query);
            /*Структурное подразделение */
            $SQL="SELECT sci_department.Name FROM sci_department,(select * from sci_department_person where idPerson={$val['id']}) as sci_department_person
                    where sci_department.id=sci_department_person.idSciDepartment";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['sci_department']=mysqli_fetch_all($query);
            /*Научная тематика */
            $SQL="SELECT sci_theme.Name FROM sci_theme,(select * from sci_theme_pers where idPers={$val['id']}) as sci_theme_pers
                    where sci_theme.id=sci_theme_pers.idTheme";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['sci_theme']=mysqli_fetch_all($query);
            /*Файлы*/
            $SQL="SELECT file.name,file.pathWeb FROM file,(select * from file_person where idPerson={$val['id']}) as file_person
                    where file.id=file_person.idFile";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['file']=mysqli_fetch_all($query, MYSQLI_ASSOC);
        }
        /*Структурное подразделение */

        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['tag'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT id,Name FROM tag;";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['sci_theme'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT id,Name FROM sci_theme;";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['sci_department'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT id, CONCAT(Name,'( c ',Date_create,' по ',(
                    if(Date_dectroy is null ,'',Date_dectroy)
                    ),')') as Name FROM sci_department 
                order by Date_create";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['file'])) {
        require_once '../php_class/fileUpload.php';
        $UPLOAD=new FileUpload();
        die(json_encode($UPLOAD->getBD(), JSON_UNESCAPED_UNICODE));
    }

