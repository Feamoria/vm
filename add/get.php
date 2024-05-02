<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once '../php_class/connect.php';
    session_start([
                      'cookie_lifetime' => 86400,
                  ]);
    if (isset($_GET['event'])) {
        $db = (new BDconnect())->connect();
        $where = '';
        if (!empty($_POST)) {
            if (isset($_POST['s_id'])) {
                $data['POST'] = $_POST;
                $where = ' where id = ' . (int)$_POST['s_id'];
            }
            if (isset($_POST['dep'])) {
                $data['POST'] = $_POST;
                if ($_POST['dep'] == 'true') {
                    $where = " where create_user in (SELECT id from user where dep = '{$_SESSION['user']['dep']}')";
                }
            }
        }
        $SQL = "SELECT * FROM event 
                $where
                order by id desc";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $data = [];
        foreach ($res as $i => $val) {
            //$i=$val['id'];
            $data[$i] = $val;
            //Персоналии
            $SQL = "SELECT person.id,CONCAT(F,' ',I,' ',O) as Name FROM person,(select * from person_event where idEvent={$val['id']}) as person_event
                    where person.id=person_event.idPerson";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['pers'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            //ТЕГИ
            $SQL = "SELECT tag.id,tag.Name FROM tag,(select * from tag_event where idEvent={$val['id']}) as tag_event
                    where tag.id=tag_event.idTag";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['tag'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            /*Структурное подразделение */
            $SQL = "SELECT sci_department.id,sci_department.Name FROM sci_department,(select * from sci_department_event where idEvent={$val['id']}) as sci_department_event
                    where sci_department.id=sci_department_event.idSciDepartment";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['sci_department'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            /*Научная тематика */
            $SQL = "SELECT sci_theme.id,sci_theme.Name FROM sci_theme,(select * from sci_theme_event where idEvent={$val['id']}) as sci_theme_event
                    where sci_theme.id=sci_theme_event.idTheme";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['sci_theme'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            /*Файлы*/
            $SQL = "SELECT file.id, file.name,file.pathWeb FROM file,(select * from file_event where idEvent={$val['id']}) as file_event
                    where file.id=file_event.idFile";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['file'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        }
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['person'])) {
        $db = (new BDconnect())->connect();
        $data = [];
        $where = '';
        if (!empty($_POST)) {
            if (isset($_POST['s_id'])) {
                $data['POST'] = $_POST;
                $where = ' where id = ' . (int)$_POST['s_id'];
            }
            if (isset($_POST['dep'])) {

                if ($_POST['dep'] == 'true') {
                    $data['POST'] = $_POST;
                    $where = " where create_user in (SELECT id from user where dep = '{$_SESSION['user']['dep']}')";
                }
            }
        }
        if (isset($_GET['term'])) {
            $term = mysqli_escape_string($db, $_GET['term']);
            $SQL = "SELECT id,CONCAT(F,' ',I,' ',O) as value FROM person 
                    where CONCAT(F,' ',I,' ',O) like '%$term%'
                    order by F,I,O;";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
        $SQL = "SELECT `id`, CONCAT(F,' ',I,' ',O) as Name, F, I, O, COMMENT, DOL, DAYN, DAYD, CREATE_DATE, CREATE_USER,publications,awards 
                FROM person 
                    $where
                    order by `id` desc";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $res = mysqli_fetch_all($query, MYSQLI_ASSOC);

        foreach ($res as $i => $val) {
            //$i=$val['id'];
            $data[$i] = $val;
            //ТЕГИ
            $SQL = "SELECT tag.id, tag.Name FROM tag,(select * from tag_person where idPerson={$val['id']}) as tag_person
                    where tag.id=tag_person.idTag";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['tag'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            /*Структурное подразделение */
            $SQL = "SELECT sci_department.id,sci_department.Name FROM sci_department,(select * from sci_department_person where idPerson={$val['id']}) as sci_department_person
                    where sci_department.id=sci_department_person.idSciDepartment";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['sci_department'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            /*Научная тематика */
            $SQL = "SELECT sci_theme.id,sci_theme.Name FROM sci_theme,(select * from sci_theme_pers where idPers={$val['id']}) as sci_theme_pers
                    where sci_theme.id=sci_theme_pers.idTheme";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['sci_theme'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            /*Файлы*/
            $SQL = "SELECT file.name,file.pathWeb FROM file,(select * from file_person where idPerson={$val['id']}) as file_person
                    where file.id=file_person.idFile";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data[$i]['file'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        }

        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['tag'])) {
        $db = (new BDconnect())->connect();
        $where = '';
        if (isset($_GET['term'])) {
            $term = mysqli_escape_string($db, $_GET['term']);
            $where = "where Name like '%$term%'";
        }
        $SQL = "SELECT tag.id,tag.Name as value,tag.Name,X.`SUMM` FROM tag 
                left join (
                    SELECT Z.idTag,SUM(Z.count) as `SUMM` from (
                        SELECT idTag,count(*) as count  from tag_file group by idTag
                        UNION 
                        SELECT idTag,count(*) as count  from tag_event group by idTag
                        UNION
                        SELECT idTag,count(*) as count  from tag_person group by idTag
                    ) as Z  group by Z.idTag
                    ) as X on X.idTag=tag.id
                $where
                order by X.SUMM desc 
                ";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['sci_theme'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT sci_theme.id,sci_theme.Name,X.SUMM FROM sci_theme
                left join (
                    SELECT Z.idSciTheme,SUM(Z.count) as `SUMM` from (
                        SELECT idSciTheme,count(*) as count  from sci_theme_file group by idSciTheme
                        UNION 
                        SELECT idTheme as idSciTheme,count(*) as count  from sci_theme_event group by idTheme
                        UNION
                        SELECT idTheme as idSciTheme,count(*) as count  from sci_theme_pers group by idTheme
                    ) as Z  group by Z.idSciTheme
                    ) as X on X.idSciTheme=sci_theme.id
                order by X.SUMM desc 

";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }
    if (isset($_GET['sci_department'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT id, CONCAT(Name,'( c ',Date_create,' по ',(
                    if(Date_dectroy is null ,'',Date_dectroy)
                    ),')') as Name FROM sci_department 
                order by Date_create";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }

    if (isset($_GET['file'])) {
        require_once '../php_class/fileUpload.php';
        $UPLOAD = new FileUpload();
        $ret = [];
        if (!empty($_POST)) {
            $ret['POST'] = $_POST;
        } else {
            $_POST = null;
        }
        $ret['GET'] = $UPLOAD->getBD(null, $_POST);
        die(json_encode($ret, JSON_UNESCAPED_UNICODE));
    }

