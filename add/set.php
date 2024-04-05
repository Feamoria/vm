<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    function add($table, $tag)
    {
        $db = (new BDconnect())->connect();
        $USER_ID = $_SESSION['user']['id'];
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
        $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        return json_encode($data, JSON_UNESCAPED_UNICODE);
        //die();

    }

    function del($table, $id, $show = true)
    {
        $db = (new BDconnect())->connect();
        //$USER_ID=$_SESSION['user']['id'];
        $id = (int)$id;
        $data = [];
        if ($id > 0) {
            $SQL = "DELETE FROM $table where id = '$id'";
            mysqli_query($db, $SQL) or
            die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
            $data = ['ok'];
        }
        if ($show) {
            $SQL = "SELECT * FROM $table order by Name";
            $query = mysqli_query($db, $SQL) or
            die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    require_once '../php_class/connect.php';
    session_start(
        [
            'cookie_lifetime' => 86400,
        ]
    );
//var_dump($_POST);
    if (isset($_SESSION['user'])) {
        $USER_ID = $_SESSION['user']['id'];
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
            } else {
                die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
            }
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
            } else {
                die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
            }
        }
        /*********
         * sci_department
         ********/
        if (isset($_GET['sci_department'])) {
            $db = (new BDconnect())->connect();
            if (isset($_GET['del'])) {
                die(del('sci_department', $_POST['sci_department']));
            } else {
                /*Очистка от каки*/
                foreach ($_POST as $i => $value) {
                    if (!is_array($value)) {
                        $_POST[$i] = mysqli_escape_string($db, $value);
                    }
                }
                $name = $_POST['sci_department_name'];
                $date1 = $_POST['sci_department_date1'];
                $date2 = $_POST['sci_department_date2'];
                $Date_dectroy = '';
                $Date_dectroy_data = '';
                if ($date2 != '') {
                    $Date_dectroy = 'Date_dectroy,';
                    $Date_dectroy_data = "'$date2',";
                }
                $SQL = "INSERT INTO sci_department (Name,  Date_create, $Date_dectroy create_user) value 
                        ('$name','$date1',$Date_dectroy_data $USER_ID)";
                //echo $SQL;
                $result = mysqli_query($db, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $InsertId = mysqli_insert_id($db);
                /**sci_department_owner array*/
                if (isset($_POST['sci_department_owner'])) {
                    if (is_array($_POST['sci_department_owner'])) {
                        $owner = implode(',', $_POST['sci_department_owner']);
                        $SQL = "UPDATE sci_department SET
                               owner = '$owner'
                                where id=$InsertId";
                        $result = mysqli_query($db, $SQL) or
                        die(
                        json_encode(
                            ['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)],
                            JSON_UNESCAPED_UNICODE
                        )
                        );
                    }
                }
            }
            $SQL = "SELECT * FROM sci_department order by Name";
            $query = mysqli_query($db, $SQL) or
            die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            die(json_encode($data, JSON_UNESCAPED_UNICODE));
        }
        /*********
         * PERSON
         ********/
        if (isset($_GET['pers'])) {
            $db = (new BDconnect())->connect();
            if (isset($_GET['del'])) {
                /*Капец.. надо вырезать все связи*/
                $text = 'ok';
                $del = del('person', $_POST['pers'], false);
                if ($del !== false) {
                    $text = $del;
                }
                die(json_encode(['ok' => "$text"], JSON_UNESCAPED_UNICODE));
            } else {
                /*Очистка от каки*/
                foreach ($_POST as $i => $value) {
                    if (!is_array($value)) {
                        $_POST[$i] = mysqli_escape_string($db, $value);
                    }
                }
                /** Основные*/
                $persID = $_POST['persID'];
                if ($persID === '') {
                    $persID = null;
                }
                $pers_F = $_POST['pers_F'];
                $pers_I = $_POST['pers_I'];
                $pers_O = $_POST['pers_O'];
                $pers_date1 = $_POST['pers_date1'];
                $pers_date2 = $_POST['pers_date2'];
                if ($_POST['pers_date2'] == '') {
                    $pers_date2 = 'null';
                } else {
                    $pers_date2 = "'{$_POST['pers_date2']}'";
                }
                $pers_Desc = $_POST['pers_Desc'];
                $pers_dol = $_POST['pers_dol'];
                $ret = [];

                //die(json_encode($_POST, JSON_UNESCAPED_UNICODE));
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                mysqli_begin_transaction($db);
                try {
                    if ($persID == null) {
                        $SQL = "INSERT INTO person (F, I, O, comment, dol, dayN, dayD, create_user) value 
                        ('$pers_F','$pers_I','$pers_O','$pers_Desc','$pers_dol','$pers_date1',$pers_date2,'$USER_ID');";
                    } else {
                        $SQL = "UPDATE person SET 
                                F='$pers_F',
                                I='$pers_I', 
                                O='$pers_O', 
                                comment='$pers_Desc', 
                                dol='$pers_dol', 
                                dayN='$pers_date1', 
                                dayD='$pers_date2'
                                 where id=$persID;";
                    }
                    $result = mysqli_query($db, $SQL);
                    $InsertId = mysqli_insert_id($db);
                    /** теги pers_tag[]*/
                    if ($persID != null) {
                        $SQL = "DELETE FROM tag_person WHERE idPerson=$persID";
                        mysqli_query($db, $SQL);
                        $InsertId = $persID;
                    }
                    if (!empty($_POST['pers_tag'])) {
                        foreach ($_POST['pers_tag'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO tag_person (idTag, idPerson) value ($value,$InsertId)";
                            } else {
                                $value = mysqli_escape_string($db, $value);
                                $SQL = "INSERT INTO tag (Name,create_user) value ($value,$USER_ID)";
                                mysqli_query($db, $SQL);
                                $InsertIdTag = mysqli_insert_id($db);
                                $SQL = "INSERT INTO tag_person (idTag, idPerson) value ($InsertIdTag,$InsertId)";
                            }
                            mysqli_query($db, $SQL);
                        }
                    }
                    /** структурное подразделение *** pers_SP[] */
                    if ($persID != null) {
                        $SQL = "DELETE FROM sci_department_person WHERE idPerson=$persID";
                        mysqli_query($db, $SQL);
                    }
                    if (!empty($_POST['pers_sci_department'])) {
                        foreach ($_POST['pers_sci_department'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_department_person (idSciDepartment, idPerson)  value ($value,$InsertId)";
                                $result = mysqli_query($db, $SQL) or
                                die(
                                json_encode(
                                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)],
                                    JSON_UNESCAPED_UNICODE
                                )
                                );
                            }
                        }
                    }
                    /** Научная тематика *** pers_tem[]*/
                    if ($persID != null) {
                        $SQL = "DELETE FROM sci_theme_pers WHERE idPers=$persID";
                        mysqli_query($db, $SQL);
                    }
                    if (!empty($_POST['pers_tem'])) {
                        foreach ($_POST['pers_tem'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_theme_pers (idTheme, idPers)  value ($value,$InsertId)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    mysqli_commit($db);
                    $ret = ['ok'];
                } catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($db);
                    $ret = [
                        'errorSQL',
                        'SQL' => $SQL,
                        'exception' => $exception->getMessage(),
                        'code' => $exception->getCode()
                    ];
                }
            }
            die(json_encode($ret, JSON_UNESCAPED_UNICODE));
            // } else die(json_encode(['err' => 'Ошибка добавления:5'], JSON_UNESCAPED_UNICODE));
        }
        /*********
         * FILE
         ********/
        if (isset($_GET['file'])) {
            require_once '../php_class/fileUpload.php';
            $UPLOAD = new FileUpload();
            if (isset($_GET['del'])) {
                $id_file = $_POST['file'];
                $UPLOAD->delFile($id_file);
            } else {
                if (empty($_POST['id_file'])) {
                    $UPLOAD->getFiles($_FILES);
                    $dataFile['UPLOAD'] = $UPLOAD->getDataFile();
                    $dataFile['POST'] = $_POST;
                    $UPLOAD->setBD($_POST);
                } else {
                    $dataFile['POST'] = $_POST;
                    $dataFile['UPDATE'] = $UPLOAD->updateBD($_POST);
                }
            }
            $dataFile['GET'] = $UPLOAD->getBD();
            die(json_encode($dataFile, JSON_UNESCAPED_UNICODE));
        }
        /*********
         * event
         ********/
        if (isset($_GET['event'])) {
            $db = (new BDconnect())->connect();
            $ret = [];
            if (isset($_GET['del'])) {
                $text = 'ok';
                $del = del('event', $_POST['event'], false);
                if ($del !== false) {
                    $text = $del;
                }
                $ret = ['ok' => "$text"];
            } else {
                /*Очистка от каки*/
                foreach ($_POST as $i => $value) {
                    if (!is_array($value)) {
                        $_POST[$i] = mysqli_escape_string($db, $value);
                    }
                }
                /** Основные*/
                $eventID = $_POST['eventID'];
                if ($eventID === '') {
                    $eventID = null;
                }
                $Name = $_POST['ev_Name'];
                $DateN = $_POST['ev_Y_n'];
                if (!empty($_POST['ev_M_n'])) {
                    $DateN .= '.' . $_POST['ev_M_n'];
                }
                if (!empty($_POST['ev_D_n'])) {
                    $DateN .= '.' . $_POST['ev_D_n'];
                }
                $DateK = $_POST['ev_Y_e'];
                if (!empty($_POST['ev_M_e'])) {
                    $DateK .= '.' . $_POST['ev_M_e'];
                }
                if (!empty($_POST['ev_D_e'])) {
                    $DateK .= '.' . $_POST['ev_D_e'];
                }
                //$Desc_short=$_POST['ev_Desc_short'];
                $Desc = $_POST['ev_Desc'];
                $Doc = $_POST['ev_doc'];
                $importance = $_POST['ev_importance'];
                $latitude = $_POST['latitude'];
                $longitude = $_POST['longitude'];
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                mysqli_begin_transaction($db);
                try {
                    if ($eventID == null) {
                        $SQL = "INSERT INTO event (Name, DateN, DateK,  `Desc`, Doc, importance, latitude, longitude, create_user) value 
                        ('$Name','$DateN','$DateK','$Desc','$Doc','$importance','$latitude','$longitude',$USER_ID) ";
                    } else {
                        $SQL = "UPDATE event SET 
                                Name='$Name',
                                DateN='$DateN', 
                                DateK='$DateK', 
                                `Desc`='$Desc', 
                                Doc='$Doc', 
                                importance='$importance', 
                                latitude='$latitude',
                                longitude='$longitude'
                                 where id=$eventID;";
                    }
                    mysqli_query($db, $SQL);
                    $InsertId = mysqli_insert_id($db);

                    /** Персоналии ev_pers */
                    if ($eventID != null) {
                        $SQL = "DELETE FROM person_event WHERE idEvent=$eventID";
                        mysqli_query($db, $SQL);
                        $InsertId = $eventID;
                    }
                    if (!empty($_POST['ev_pers'])) {
                        foreach ($_POST['ev_pers'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO person_event (idEvent, idPerson)  value ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    /** Файл ev_file */
                    if ($eventID != null) {
                        $SQL = "DELETE FROM file_event WHERE idEvent=$eventID";
                        mysqli_query($db, $SQL);
                        $InsertId = $eventID;
                    }
                    if (!empty($_POST['ev_file'])) {
                        foreach ($_POST['ev_file'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO file_event (idEvent, idFile)  value ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    /** Структурное подразделение ev_sci_department */
                    if ($eventID != null) {
                        $SQL = "DELETE FROM sci_department_event WHERE idEvent=$eventID";
                        mysqli_query($db, $SQL);
                        $InsertId = $eventID;
                    }
                    if (!empty($_POST['ev_sci_department'])) {
                        foreach ($_POST['ev_sci_department'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_department_event (idEvent, idSciDepartment)  value ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    /** Научная тематика ev_tem */
                    if ($eventID != null) {
                        $SQL = "DELETE FROM sci_theme_event WHERE idEvent=$eventID";
                        mysqli_query($db, $SQL);
                        $InsertId = $eventID;
                    }
                    if (!empty($_POST['ev_tem'])) {
                        foreach ($_POST['ev_tem'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_theme_event (idEvent, idTheme)  value ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    /** Ключевые слова ev_tag */
                    if ($eventID != null) {
                        $SQL = "DELETE FROM tag_event WHERE idEvent=$eventID";
                        mysqli_query($db, $SQL);
                        $InsertId = $eventID;
                    }
                    if (!empty($_POST['ev_tag'])) {
                        foreach ($_POST['ev_tag'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO tag_event (idEvent, idTag)  value ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    mysqli_commit($db);
                    $ret = ['ok'];
                } catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($db);
                    $ret = [
                        'errorSQL',
                        'SQL' => $SQL,
                        'exception' => $exception->getMessage(),
                        'code' => $exception->getCode()
                    ];
                }
            }
            die(json_encode($ret, JSON_UNESCAPED_UNICODE));
        }
    }