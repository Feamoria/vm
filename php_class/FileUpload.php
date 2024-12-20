<?php

    ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once 'connect.php';

    class FileUpload
    {

        protected array $FileArray;
        protected $connect;
        protected string $uploaddir;
        protected string $client_path;
        protected string $regExpFile;
        protected string $HtmlInput;
        /**
         * @var int $type 0-Обычные файлы, 1-Файлы для колекций
         */
        protected int $type;

        /**
         * Статика которую на наследнике можно менять
         */
        protected const UploadDIR = "/vm/img"; // Куда загружать на сервере
        protected const ClientPath = "/vm/img/"; // откуда будет отображатся файл на клиенте
        protected const RegExpFile = '/.*\.(pdf|jpg|jpeg)$/i'; //регулярка валидации файлов
        protected const TYPE = 0; // тип файла  0-Обычные файлы, 1-Файлы для колекций
        protected const HTMLINPUT = 'file_F'; //

        public function __construct()
        {
            $this->connect = (new BDconnect())->connect();
            $this->uploaddir = $_SERVER['DOCUMENT_ROOT'] . static::UploadDIR;
            $this->client_path = static::ClientPath;
            $this->regExpFile = static::RegExpFile;
            $this->type = static::TYPE;
            $this->HtmlInput = static::HTMLINPUT;
        }

        public function getDataFile(): array
        {
            return $this->FileArray;
        }

        /*
         * chmode /var/www/html/vm/img/
         * sudo chmod -R 777 /var/www/html/vm/img/
         * sudo chown -R  www-data:www-data /var/www/html/vm/
         * TODO
         * chmode /var/www/html/vm/collect/
         * sudo chmod -R 777 /var/www/html/vm/collect/
         * sudo chown -R  www-data:www-data /var/www/html/vm/collect/
         * */
        public function getFiles($files)
        {
            $resp = array();
            //$resp['ok'] = '0';
            $uploadDir = $this->uploaddir;
            foreach ($files as $j => $file) {
                $resp[$j]['ok'] = '0';
                $resp[$j]['info1'] = $file;
                $OFileName = $file['name'];
                $OFileTemp = $file['tmp_name'];
                if (is_array($OFileName)) {
                    $OFileName = $file['name'][0];
                    $OFileTemp = $file['tmp_name'][0];
                }
                $info = pathinfo($OFileName);
                //$origname = str_replace(' ', '_', $OFileName);
                $resp[$j]['info2'] = $info;
                $file_nameFull = trim(
                    str_replace('.' . $info['extension'], '', $OFileName) . '-' . rand(
                        1,
                        999
                    ) . '.' . $info['extension']
                );
                $file_nameFull = str_replace(' ', '_', $file_nameFull);
                $file_name = $OFileName;
                if (preg_match($this->regExpFile, $file_name)) {
                    if (move_uploaded_file($OFileTemp, "$uploadDir/$file_nameFull")) {
                        $path = realpath("$uploadDir/$file_nameFull");
                        $resp[$j]['ok'] = '1';
                        $resp[$j]['file'] = $file_name;
                        $resp[$j]['serv_path'] = $path;
                        $resp[$j]['client_path'] = $this->client_path . "$file_nameFull";
                    } else {
                        $resp[$j]['ok'] = '0';
                        $resp[$j]['mess'] = "Ошибка перемещения";
                        $resp[$j]['f_name'] = $OFileTemp;
                        $resp[$j]['f_dest'] = "$uploadDir/$file_nameFull";
                        $resp[$j]['_FILES'] = $_FILES;
                    }
                } else {
                    $resp[$j]['ok'] = '0';
                    $resp[$j]['mess'] = "Не коректный тип файла:$file_name : " . $info['extension'];
                }
            }

            $this->FileArray = $resp;
        }

        /**
         * @param $data : array
         * id_file
         * file_date
         * file_name
         * file_Desc
         * file_doc
         * file_pers            : array
         * file_sci_department  : array
         * file_tem             : array
         * file_tag             : array
         */
        public function updateBD($data): array
        {
            foreach ($data as $i => $value) {
                if (!is_array($value)) {
                    $data[$i] = mysqli_escape_string($this->connect, $value);
                }
            }
            $id = (int)$data['id_file'];
            $date = $data['file_date'];
            $name = $data['file_name'];
            $Desc = $data['file_Desc'];
            $doc = $data['file_doc'];
            $MainType='';
            $MainId='';
            if (isset($data['MainType'])) {
                $MainType = $data['MainType'];
                if ($MainType >= 0) {
                    $MainType = $data['MainType'];
                    $MainId = $data['MainId'];
                } else {
                    $MainType='';
                }
            }
            $ret = [];
            /*Основа // И вообще оберёнм в транзацию ибо нех*/
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            mysqli_begin_transaction($this->connect);
            try {
                $SQL = "UPDATE  file 
                SET
                    `name`='$name',
                    `date`='$date',
                    `disc`='$Desc',
                    `doc`='$doc',
                    mainType = '$MainType',
                    mainId = '$MainId',
                    `type`=$this->type
                WHERE id=$id;";
                mysqli_query($this->connect, $SQL);
                /*file_pers*/
                $VALUES = [];
                $SQL = "DELETE FROM file_person WHERE idFile=$id";
                mysqli_query($this->connect, $SQL);
                if (isset($data['file_pers'])) {
                    foreach ($data['file_pers'] as $val) {
                        $VALUES[] = "($id,$val)";
                    }
                    $VALUES = implode(',', $VALUES);
                    $SQL = "INSERT INTO file_person (idFile, idPerson) VALUES $VALUES";
                    mysqli_query($this->connect, $SQL);
                    $ret['file_person'] = $SQL;
                }
                /*file_sci_department*/
                $VALUES = [];
                $SQL = "DELETE FROM sci_department_file WHERE idFile=$id";
                mysqli_query($this->connect, $SQL);
                if (isset($data['file_sci_department'])) {
                    foreach ($data['file_sci_department'] as $val) {
                        $VALUES[] = "($id,$val)";
                    }
                    $VALUES = implode(',', $VALUES);
                    $SQL = "INSERT INTO sci_department_file (idFile, idSciDepartment) VALUES $VALUES";
                    mysqli_query($this->connect, $SQL);
                }
                /*file_tem*/
                $VALUES = [];
                $SQL = "DELETE FROM sci_theme_file WHERE idFile=$id";
                mysqli_query($this->connect, $SQL);
                if (isset($data['file_tem'])) {
                    foreach ($data['file_tem'] as $val) {
                        $VALUES[] = "($id,$val)";
                    }
                    $VALUES = implode(',', $VALUES);
                    $SQL = "INSERT INTO sci_theme_file (idFile, idSciTheme) VALUES $VALUES";
                    mysqli_query($this->connect, $SQL);
                }
                /*file_tag*/
                $VALUES = [];
                $SQL = "DELETE FROM tag_file WHERE idFile=$id";
                mysqli_query($this->connect, $SQL);
                if (isset($data['file_tag'])) {
                    foreach ($data['file_tag'] as $val) {
                        $VALUES[] = "($id,$val)";
                    }
                    $VALUES = implode(',', $VALUES);
                    $SQL = "INSERT INTO tag_file (idFile, idTag) VALUES $VALUES";
                    mysqli_query($this->connect, $SQL);
                }
                /** commit */
                mysqli_commit($this->connect);
                $ret['ok'] = 'ok';
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
        }

        public function setBD($POST): array
        {
            $ret = [];
            if (isset($this->FileArray[$this->HtmlInput])) {
                if ($this->FileArray[$this->HtmlInput]['ok'] == '1') {
                    $data = $this->FileArray[$this->HtmlInput];
                    $Date = $POST['file_date'];
                    if ($Date == '') {
                        $Date = 'null';
                    } else {
                        $Date = "'" . $Date . "'";
                    }
                    $name = $POST['file_name'];
                    $doc = $POST['file_doc'];
                    $Desc = $POST['file_Desc'];
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    mysqli_begin_transaction($this->connect);
                    try {
                        $SQL = "insert into file (date, name, disc, doc, pathServ, pathWeb,   create_user,type)  values 
                            ($Date,'$name','$Desc','$doc','{$data['serv_path']}',
                             '{$data['client_path']}',                         
                            '{$_SESSION['user']['id']}',$this->type );";
                        mysqli_query($this->connect, $SQL);
                        $InsertId = mysqli_insert_id($this->connect);
                        /*Персоналии -file_pers*/
                        if (!empty($POST['file_pers'])) {
                            foreach ($POST['file_pers'] as $i => $value) {
                                if (is_numeric($value)) {
                                    $value = (int)$value;
                                    $SQL = "INSERT INTO file_person (idPerson, idFile)  value ($value,$InsertId)";
                                    mysqli_query($this->connect, $SQL);
                                }
                            }
                        }
                        /*Научная тематика - file_tem*/
                        if (!empty($POST['file_tem'])) {
                            foreach ($POST['file_tem'] as $i => $value) {
                                if (is_numeric($value)) {
                                    $value = (int)$value;
                                    $SQL = "INSERT INTO sci_theme_file (idSciTheme, idFile)  value ($value,$InsertId)";
                                    mysqli_query($this->connect, $SQL);
                                }
                            }
                        }
                        /*Ключевые слова -file_tag*/
                        if (!empty($POST['file_tag'])) {
                            foreach ($POST['file_tag'] as $i => $value) {
                                if (is_numeric($value)) {
                                    $value = (int)$value;
                                    $SQL = "INSERT INTO tag_file (idTag, idFile)  value ($value,$InsertId)";
                                    mysqli_query($this->connect, $SQL);
                                } else {
                                    $value = mysqli_escape_string($this->connect, $value);
                                    if ($value == !'') {
                                        $SQL = "INSERT INTO tag (Name,create_user) value ('$value',{$_SESSION['user']['id']})";
                                        mysqli_query($this->connect, $SQL);
                                        $ret['SQL_TAG'][] = $SQL;
                                        $InsertIdTag = mysqli_insert_id($this->connect);
                                        $SQL = "INSERT INTO tag_file (idTag, idFile)  value ($InsertIdTag,$InsertId)";
                                        $ret['SQL_TAG'][] = $SQL;
                                        mysqli_query($this->connect, $SQL);
                                    }
                                }
                            }
                        }
                        /*Научное подразделение -file_sci_department*/
                        if (!empty($POST['file_sci_department'])) {
                            foreach ($POST['file_sci_department'] as $i => $value) {
                                if (is_numeric($value)) {
                                    $value = (int)$value;
                                    $SQL = "INSERT INTO sci_department_file (idSciDepartment, idFile)  value ($value,$InsertId)";
                                    mysqli_query($this->connect, $SQL);
                                }
                            }
                        }
                        $ret['ok'] = 'ok';
                        mysqli_commit($this->connect);
                    } catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($this->connect);
                        $ret = [
                            'err'=>'Ошибка запроса. Отправьте ошибку на hohlov.r.n@ib.komisc.ru\nCODE:'.$exception->getCode().'\nException:'.$exception->getMessage(),
                            'errorSQL',
                            'SQL' => $SQL,
                            'exception' => $exception->getMessage(),
                            'code' => $exception->getCode()
                        ];
                    }
                    return $ret;
                } else {
                    $ret['err'] = 'FileArray is not ok.'.$this->FileArray[$this->HtmlInput]['mess'];
                }
            } else {
                $ret['err'] = 'FileArray empty';
            }
            return $ret;
        }

        public function delFile($id): array
        {
            $File = $this->getBD($id);
            $ret = [];
            foreach ($File as $meta) {
                $pathServ = $meta['pathServ'];
                if (unlink($pathServ)) {
                    $SQL = "DELETE FROM file where id=$id";
                    if (!mysqli_query($this->connect, $SQL)) {
                        $ret = ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)];
                    } else {
                        $ret = ['ok' => "ok"];
                    }
                } else {
                    $ret = ['err' => 'Ошибка удаления файла. '.var_export(error_get_last(), true)];
                }
            }
            return $ret;
        }

        public function getBD($id = null, $search = null): array
        {
            $SQL = "SELECT id, cast(date as DATE ) as `date` , name, disc, doc, pathServ, pathWeb, type, create_date, create_user,mainId,mainType   
                    FROM file 
                    where type = $this->type
                    order by date";
            if ($id != null) {
                $SQL = "SELECT id, cast(date as DATE ) as `date` , name, disc, doc, pathServ, pathWeb, type, create_date, create_user,mainId,mainType  
                        FROM file 
                        where id=$id";
            }
            if ($search != null) {
                $Where = " type = $this->type ";
                $and = 'and';
                if (isset($search['s_id'])) {
                    $Where .= " $and `id` = '{$search['s_id']}'";
                    $and = 'and';
                }
                if (isset($search['dep'])) {
                    if( $search['dep']=='on') {
                        $search['dep'] = 'true';
                    }
                    if ($search['dep'] == 'true') {
                        $Where .= " $and create_user in (SELECT id from user where dep = '{$_SESSION['user']['dep']}')";
                    }
                }
                if (isset($search['s_Name'])) {
                    $Where .= " $and `name` like '%{$search['s_Name']}%'";
                    $and = 'and';
                }
                if (isset($search['s_pers'])) {
                    $s = implode(',', $search['s_pers']);
                    $Where .= " $and id in (SELECT `idFile` from `file_person` where `idPerson` in ($s))";
                }
                //s_tem
                if (isset($search['s_tem'])) {
                    $s = implode(',', $search['s_tem']);
                    $Where .= " $and id in (SELECT `idFile` from `sci_theme_file` where `idSciTheme` in ($s))";
                }
                //s_tag
                if (isset($search['s_tag'])) {
                    $s = implode(',', $search['s_tag']);
                    $Where .= " $and id in (SELECT `idFile` from `tag_file` where `idTag` in ($s))";
                }

                $SQL = "SELECT id, cast(date as DATE ) as `date` , name, disc, doc, pathServ, pathWeb, type, create_date, create_user ,mainId,mainType  
                        FROM file 
                        where $Where";
            }
            //echo $SQL;
            $query = mysqli_query($this->connect, $SQL) or die(
                $SQL . "|Couldn't execute query." . mysqli_error(
                    $this->connect
                )
            );
            $res = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $data = [];
            foreach ($res as $val) {
                //ТЕГИ
                $i = $val['id'];
                $data[$val['id']] = $val;
                $SQL = "SELECT tag.id,tag.Name FROM tag,(select * from tag_file where idFile={$val['id']}) as tag_file
                    where tag.id=tag_file.idTag";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['tag'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
                /*Научная тематика */
                $SQL = "SELECT sci_theme.id,sci_theme.Name FROM sci_theme,
                                        (select * from sci_theme_file where idFile={$val['id']}) as sci_theme_file
                    where sci_theme.id=sci_theme_file.idSciTheme";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['sci_theme'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
                /*Персоналии*/
                $SQL = "SELECT person.id , CONCAT(F,' ',I,' ',O) as Name FROM person,
                                                      (select * from file_person where idFile={$val['id']}) as file_person
                    where person.id=file_person.idPerson";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);

                /* Научное подразделение -file_sci_department*/
                $SQL = "SELECT sci_department.id,sci_department.Name FROM sci_department,(select * from sci_department_file where idFile={$val['id']}) as sci_department_file
                    where sci_department.id=sci_department_file.idSciDepartment";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['sci_department'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            }
            return $data;
        }
    }