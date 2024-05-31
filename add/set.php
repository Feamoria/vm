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
        //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах

        $id = (int)$id;
        $data = [];
        if ($id > 0) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $SQL = "DELETE FROM $table where id = '$id'";
                mysqli_query($db, $SQL);
                $data = ['ok'];
            } catch (mysqli_sql_exception $exception) {
                mysqli_rollback($db);
                $ret = [
                    'errorSQL',
                    'SQL' => $SQL,
                    'exception' => $exception->getMessage(),
                    'code' => $exception->getCode()
                ];
                if ($exception->getCode() == 1451) {
                    $ret['err'] = 'Удалить связаные элементы невозможно. Обратитесь к разработчику';
                }
                die(json_encode($ret, JSON_UNESCAPED_UNICODE));
            }
        }
        if ($show) {
            $SQL = "SELECT * FROM $table order by Name";
            $query = mysqli_query($db, $SQL) or
            die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    function checkPermition($table, $id): bool
    {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT create_user FROM $table where id = '$id'";
        $query = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $create_user = $data[0]['create_user'];
        $USER_ID = $_SESSION['user']['id'];
        $SQL = "SELECT id from user where 
                          dep in
                    (SELECT dep FROM user where id = '$USER_ID')
                and id = '$create_user;'";
        $query = mysqli_query($db, $SQL) or
        die(json_encode(['err' => $SQL . "|Couldn't execute query." . mysqli_error($db)], JSON_UNESCAPED_UNICODE));
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
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
                if (!checkPermition('person', (int)$_POST['pers'])) {
                    die(json_encode(['err' => "Удалить чужую персоналию невозможно"]));
                }
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
                    $pers_date2 = "'" . $_POST['pers_date2'] . "'";
                }
                $pers_Desc = $_POST['pers_Desc'];
                $pers_dol = $_POST['pers_dol'];
                $pers_publications = $_POST['pers_publications'];
                $pers_awards = $_POST['pers_awards'];
                $ret = [];
                $SQL = '';
                //die(json_encode($_POST, JSON_UNESCAPED_UNICODE));
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                mysqli_begin_transaction($db);
                try {
                    if ($persID == null) {
                        $SQL = "INSERT INTO person (F, I, O, comment, dol, dayN, dayD, create_user,publications,awards) value 
                        ('$pers_F','$pers_I','$pers_O','$pers_Desc','$pers_dol','$pers_date1',$pers_date2,'$USER_ID','$pers_publications','$pers_awards');";
                    } else {
                        if (!checkPermition('person', (int)$persID)) {
                            die(json_encode(['err' => "Изменить чужую персоналию невозможно"]));
                        }
                        //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах
                        $SQL = "UPDATE person SET 
                                F='$pers_F',
                                I='$pers_I', 
                                O='$pers_O', 
                                comment='$pers_Desc', 
                                dol='$pers_dol', 
                                dayN='$pers_date1', 
                                dayD=$pers_date2,
                                publications='$pers_publications',
                                awards='$pers_awards'
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
                                mysqli_query($db, $SQL);
                            } else {
                                $value = mysqli_escape_string($db, $value);
                                if ($value != '') {
                                    $SQL = "INSERT INTO tag (Name,create_user) value ('$value',$USER_ID)";
                                    mysqli_query($db, $SQL);
                                    $ret['SQL_TAG'][] = $SQL;
                                    $InsertIdTag = mysqli_insert_id($db);
                                    $SQL = "INSERT INTO tag_person (idTag, idPerson) value ($InsertIdTag,$InsertId)";
                                    $ret['SQL_TAG'][] = $SQL;
                                    mysqli_query($db, $SQL);
                                }
                            }
                            //mysqli_query($db, $SQL);
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
                    $ret['ok'] = ['ok'];
                } catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($db);
                    $ret = [
                        'err' => 'Ошибка запроса. Отправьте ошибку на hohlov.r.n@ib.komisc.ru\nCODE:' . $exception->getCode(
                            ) . '\nException:' . $exception->getMessage(),
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
            require_once '../php_class/FileUpload.php';
            $UPLOAD = new FileUpload();
            if (isset($_GET['del'])) {
                //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах
                $id_file = $_POST['file'];
                if (!checkPermition('file', $id_file)) {
                    die(json_encode(['err' => "Удилить чужой файл невозможно"]));
                }

                $UPLOAD->delFile($id_file);
            } else {
                if (empty($_POST['id_file'])) {
                    $UPLOAD->getFiles($_FILES);
                    $dataFile['UPLOAD'] = $UPLOAD->getDataFile();
                    $dataFile['POST'] = $_POST;
                    $dataFile['setBD'] = $UPLOAD->setBD($_POST);
                    if (isset($dataFile['setBD']['err'])) {
                        $dataFile['err'] = $dataFile['setBD']['err'];
                    }
                } else {
                    // ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах
                    $dataFile['POST'] = $_POST;
                    if (!checkPermition('file', (int)$_POST['id_file'])) {
                        die(json_encode(['err' => "Изменить чужой файл невозможно"]));
                    }
                    $dataFile['UPDATE'] = $UPLOAD->updateBD($_POST);
                }
            }
            $dataFile['GET'] = $UPLOAD->getBD(null, $_POST);
            die(json_encode($dataFile, JSON_UNESCAPED_UNICODE));
        }
        /*********
         * event
         ********/
        if (isset($_GET['event'])) {
            $db = (new BDconnect())->connect();
            $ret = [];
            $SQL = '';
            if (isset($_GET['del'])) {
                //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах
                $text = 'ok';
                if (!checkPermition('event', (int)$_POST['event'])) {
                    die(json_encode(['err' => "Удалить чужое событие невозможно"]));
                }
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
                        //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах
                        if (!checkPermition('event', (int)$eventID)) {
                            die(json_encode(['err' => "Изменить чужое событие невозможно"]));
                        }
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
                            } else {
                                $value = mysqli_escape_string($db, $value);
                                if ($value != '') {
                                    $SQL = "INSERT INTO tag (Name,create_user) value ('$value',$USER_ID)";
                                    mysqli_query($db, $SQL);
                                    $ret['SQL_TAG'][] = $SQL;
                                    $InsertIdTag = mysqli_insert_id($db);
                                    $SQL = "INSERT INTO tag_event (idEvent, idTag)  value ($InsertId,$InsertIdTag)";
                                    $ret['SQL_TAG'][] = $SQL;
                                    mysqli_query($db, $SQL);
                                }
                            }
                        }
                    }
                    mysqli_commit($db);
                    $ret = ['ok'];
                } catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($db);
                    $ret = [
                        'err' => 'Ошибка запроса. Отправьте ошибку на hohlov.r.n@ib.komisc.ru\nCODE:' . $exception->getCode(
                            ) . '\nException:' . $exception->getMessage(),
                        'errorSQL',
                        'SQL' => $SQL,
                        'exception' => $exception->getMessage(),
                        'code' => $exception->getCode()
                    ];
                }
            }
            die(json_encode($ret, JSON_UNESCAPED_UNICODE));
        }
        /*********
         * collection
         ********/
        //var_dump($_GET);
        if (isset($_GET['collection'])) {
            $db = (new BDconnect())->connect();
            $ret = [];
            if (isset($_GET['del'])) {
                //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах
                $text = 'ok';
                if (!checkPermition('collection', (int)$_POST['collection'])) {
                    die(json_encode(['err' => "Удалить чужую колекцию невозможно"]));
                }
                $del = del('collection', $_POST['collection'], false);
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
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                mysqli_begin_transaction($db);
                try {
                    //var_dump($_POST);
                    //collection_name collection_Desc collection_sci_department
                    if (empty($_POST['id_collection'])) {
                        $SQL = "INSERT INTO collection (Name, collection_Desc, create_user,url) value 
                        ('{$_POST['collection_name']}','{$_POST['collection_Desc']}',{$_SESSION['user']['id']},'{$_POST['collection_url']}')";
                        mysqli_query($db, $SQL);
                        $InsertId = mysqli_insert_id($db);
                    } else {
                        $id = (int)$_POST['id_collection'];
                        $SQL = "UPDATE collection set 
                            Name='{$_POST['collection_name']}' ,
                            collection_Desc='{$_POST['collection_Desc']}' ,
                            url='{$_POST['collection_url']}'
                        where id =$id";
                        mysqli_query($db, $SQL);
                        $InsertId = $id;
                    }
                    if (!empty($_POST['id_collection'])) {
                        $SQL = "DELETE FROM sci_department_collection where idCollection=$InsertId";
                        mysqli_query($db, $SQL);
                    }
                    if (!empty($_POST['collection_sci_department'])) {
                        foreach ($_POST['collection_sci_department'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_department_collection (idCollection, idSciDepartment)  value ($InsertId,$value)";
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
        /*********
         * collectionItem
         ********/
        //var_dump($_GET);
        if (isset($_GET['collectionItem'])) {
            $db = (new BDconnect())->connect();
            require_once '../php_class/FileUpload.php';

            class FileUploadCollect extends FileUpload
            {
                protected const UploadDIR = "/vm/collect/"; // Куда загружать на сервере
                protected const ClientPath = "/vm/collect/"; // откуда будет отображатся файл на клиенте
                protected const RegExpFile = '/.*\.(jpg|jpeg)$/i'; //регулярка валидации файлов
                protected const TYPE = 1; // тип файла  0-Обычные файлы, 1-Файлы для колекций
                protected const HTMLINPUT = 'collectionItemFile'; //

                public function setBD($POST): array
                {
                    $ret = [];
                    if (isset($this->FileArray[$this->HtmlInput])) {
                        if ($this->FileArray[$this->HtmlInput]['ok'] == '1') {
                            $data = $this->FileArray[$this->HtmlInput];
                            $name = $POST['collectionItemName'];
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                            mysqli_begin_transaction($this->connect);
                            try {
                                $SQL = "insert into file (name, pathServ, pathWeb,   create_user,type)  values 
                                ('$name','{$data['serv_path']}',
                                 '{$data['client_path']}',                         
                                '{$_SESSION['user']['id']}',$this->type );";
                                mysqli_query($this->connect, $SQL);
                                $InsertId = mysqli_insert_id($this->connect);
                                /*Персоналии -file_pers*/
                                $ret['InsertIdFile'] = $InsertId;
                                $ret['ok'] = 'ok';
                                mysqli_commit($this->connect);
                            } catch (mysqli_sql_exception $exception) {
                                mysqli_rollback($this->connect);
                                $ret = [
                                    'errorSQL',
                                    'SQL' => $SQL,
                                    'exception' => $exception->getMessage(),
                                    'code' => $exception->getCode()
                                ];
                            }
                            return $ret;
                        } else {
                            $ret['err'] = 'FileArray is not ok';
                        }
                    } else {
                        $ret['err'] = 'FileArray empty';
                    }
                    return $ret;
                }

            }

            $UPLOAD = new FileUploadCollect();
            $ret = [];
            if (isset($_GET['del'])) {
                //  Удаление!
                //  ЗАПРОС КТО СОЗДАЛ ЗАПИСЬ ЕСЛИ НЕ РАВЕН С СЕССИОН ID то иди нах

                $id = $_POST['collectionItem'];
                if (!checkPermition('collectionItem', $id)) {
                    die(json_encode(['err' => "Удилить чужой файл невозможно"]));
                }
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                try {
                    $SQL = "SELECT id,idFile from collectionItem where id=$id";
                    $query = mysqli_query($db, $SQL);
                    $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
                    $idFile = null;
                    foreach ($res as $val) {
                        $idFile = $val['idFile'];
                    }
                    $del = $UPLOAD->delFile($idFile);
                    if (isset($del['ok'])) {
                        del('collectionItem', $id);
                    } else {
                        $ret['err'] = $del['err'];
                    }
                } catch (mysqli_sql_exception $exception) {
                    $ret = [
                        'errorSQL',
                        'SQL' => $SQL,
                        'exception' => $exception->getMessage(),
                        'code' => $exception->getCode()
                    ];
                }
            } else {
                /*Очистка от каки*/
                foreach ($_POST as $i => $value) {
                    if (!is_array($value)) {
                        $_POST[$i] = mysqli_escape_string($db, $value);
                    }
                }
                $_POST['FILE'] = $_FILES;
                /** Основные*/
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                mysqli_begin_transaction($db);
                try {
                    $idColl = $_POST['collectionItemColl'];
                    $Name = $_POST['collectionItemName'];
                    $Desc = $_POST['collectionItemDesc'];
                    $Place = $_POST['collectionItemPlace'];
                    $Time = $_POST['collectionItemTime'];
                    $Material = $_POST['collectionItemMaterial'];
                    $Size = $_POST['collectionItemSize'];
                    $Nom = $_POST['collectionItemNom'];
                    $latitude = $_POST['latitude'];
                    $longitude = $_POST['longitude'];
                    //collectionItemId
                    if (empty($_POST['collectionItemId'])) {
                        $SQL = "INSERT INTO collectionItem 
                    (idCollection,Name, `Desc`, Place, Time, Material, Size, Nom, create_user, latitude, longitude) value 
                    ('$idColl','$Name','$Desc','$Place','$Time','$Material','$Size','$Nom','{$_SESSION['user']['id']}',
                        '$latitude','$longitude')";
                        mysqli_query($db, $SQL);
                        $InsertId = mysqli_insert_id($db);
                    } else {
                        $InsertId = (int)$_POST['collectionItemId'];
                        $SQL = "UPDATE collectionItem set 
                            idCollection='$idColl',
                            Name='$Name',
                            `Desc`='$Desc',
                            Place='$Place', 
                            Time='$Time', 
                            Material='$Material', 
                            Size='$Size', 
                            Nom='$Nom',  
                            latitude='$latitude', 
                            longitude='$longitude',
                            create_user='{$_SESSION['user']['id']}'
                            where id= $InsertId";
                        mysqli_query($db, $SQL);
                        //$InsertId = (int)$_POST['collectionItemId'];
                    }
                    if (!empty($_POST['collectionItemId'])) {
                        $SQL = "DELETE FROM person_collectionItem where idCollectionItem=$InsertId";
                        mysqli_query($db, $SQL);
                    }
                    if (!empty($_POST['collectionItem_pers'])) {
                        foreach ($_POST['collectionItem_pers'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO  person_collectionItem
                                        (idCollectionItem, idPerson)  value 
                                        ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    if (!empty($_POST['collectionItemId'])) {
                        $SQL = "DELETE FROM sci_theme_collectionItem where idCollectionItem=$InsertId";
                        mysqli_query($db, $SQL);
                    }
                    if (!empty($_POST['collectionItem_tem'])) {
                        foreach ($_POST['collectionItem_tem'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_theme_collectionItem 
                                        (idCollectionItem, idSciDepartment)  value 
                                        ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            }
                        }
                    }
                    if (!empty($_POST['collectionItemId'])) {
                        $SQL = "DELETE FROM tag_collectionItem where idCollectionItem=$InsertId";
                        mysqli_query($db, $SQL);
                    }
                    if (!empty($_POST['collectionItem_tag'])) {
                        foreach ($_POST['collectionItem_tag'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO tag_collectionItem 
                                        (idCollectionItem, idTag)  value 
                                        ($InsertId,$value)";
                                mysqli_query($db, $SQL);
                            } else {
                                $value = mysqli_escape_string($db, $value);
                                if ($value == !'') {
                                    $SQL = "INSERT INTO tag (Name,create_user) value ('$value',$USER_ID)";
                                    mysqli_query($db, $SQL);
                                    $ret['SQL_TAG'][] = $SQL;
                                    $InsertIdTag = mysqli_insert_id($db);
                                    $SQL = "INSERT INTO tag_collectionItem 
                                        (idCollectionItem, idTag)  value 
                                        ($InsertId,$InsertIdTag)";
                                    $ret['SQL_TAG'][] = $SQL;
                                    mysqli_query($db, $SQL);
                                }
                            }
                        }
                    }
                    /*
                    !file!
                    */
                    if (empty($_POST['collectionItemId'])) {
                        $UPLOAD->getFiles($_FILES);
                        $dataFile['UPLOAD'] = $UPLOAD->getDataFile();
                        $dataFile['POST'] = $_POST;
                        $dataFile['setBD'] = $UPLOAD->setBD($_POST);
                        if (isset($dataFile['setBD']['err'])) {
                            mysqli_rollback($db);
                            $ret['FILE'] = $dataFile;
                            $ret['err'] = $dataFile['setBD']['err'];
                            $ret['_FILES'] = $_FILES;
                            die(json_encode($ret, JSON_UNESCAPED_UNICODE));
                        }
                        $InsertIdFile = $dataFile['setBD']['InsertIdFile'];
                        $dataFile['InsertIdFile'] = $dataFile['setBD']['InsertIdFile'];
                        /*** ДОБАВИТЬ id файла колекции */
                        $SQL = "Update collectionItem set 
                                idFile= $InsertIdFile
                                where id=$InsertId";
                        mysqli_query($db, $SQL);
                        $ret['FILE'] = $dataFile;
                    }
                    mysqli_commit($db);
                    $ret['ok'] = ['ok'];
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
    } else {
        die(json_encode(['err' => 'Сессия закрыта, обновите страницу', 'errCode' => 1], JSON_UNESCAPED_UNICODE));
    }