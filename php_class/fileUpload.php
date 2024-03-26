<?php

    ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once 'connect.php';

    class FileUpload
    {
        private array $FileArray;
        private $connect;
        private string $uploaddir;
        private string $client_path;

        public function __construct(/*$arrFile*/)
        {
            $this->connect = (new BDconnect())->connect();
            $this->uploaddir = "{$_SERVER['DOCUMENT_ROOT']}/vm/img/";
            $this->client_path = '/vm/img/';
        }

        public function getDataFile(): array
        {
            return $this->FileArray;
        }

        /*
         * chmode /var/www/html/vm/img/
         * sudo chmod -R 777 /var/www/html/vm/img/
         * sudo chown -R  www-data:www-data /var/www/html/vm/
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
                $origname = str_replace(' ', '_', $OFileName);
                $info = pathinfo($origname);
                $resp[$j]['info2'] = $info;
                $file_nameFull = trim(
                    str_replace('.' . $info['extension'], '', $OFileName) . '-' . rand(
                        1,
                        999
                    ) . '.' . $info['extension']
                );
                $file_name = $OFileName;
                if (preg_match('/.*\.(xls|xlsx|doc|docx|pdf|jpg|jpeg)$/i', $file_name)) {
                    if (move_uploaded_file($OFileTemp, "/$uploadDir/$file_nameFull")) {
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

        public function setBD($POST): bool
        {
            if (isset($this->FileArray['file_F'])) {
                if ($this->FileArray['file_F']['ok'] == '1') {
                    //session_start();
                    $data = $this->FileArray['file_F'];
                    $Date = $POST['file_date'];
                    $name = $POST['file_name'];
                    $doc = $POST['file_doc'];
                    $Desc = $POST['file_Desc'];
                    $SQL = "insert into file (date, name, disc, doc, pathServ, pathWeb, type,  create_user)  values 
                            ('$Date','$name','$Desc','$doc','{$data['serv_path']}',
                             '{$data['client_path']}','{$data['info1']['type']}',                          
                            '{$_SESSION['user']['id']}' );";
                    $result = mysqli_query($this->connect, $SQL) or die(
                    json_encode(
                        ['errorSQL' => "Couldn't execute query." . mysqli_error($this->connect) . "<br> " . $SQL]
                    )
                    );
                    $InsertId = mysqli_insert_id($this->connect);
                    /*Персоналии -file_pers*/
                    if (!empty($POST['file_pers'])) {
                        foreach ($POST['file_pers'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO file_person (idPerson, idFile)  value ($value,$InsertId)";
                                $result = mysqli_query($this->connect, $SQL) or
                                die(
                                json_encode(
                                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                                    JSON_UNESCAPED_UNICODE
                                )
                                );
                            }
                        }
                    }
                    /*Научная тематика - file_tem*/
                    if (!empty($POST['file_tem'])) {
                        foreach ($POST['file_tem'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO sci_theme_file (idSciTheme, idFile)  value ($value,$InsertId)";
                                $result = mysqli_query($this->connect, $SQL) or
                                die(
                                json_encode(
                                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                                    JSON_UNESCAPED_UNICODE
                                )
                                );
                            }
                        }
                    }
                    /*Ключевые слова -file_tag*/
                    if (!empty($POST['file_tag'])) {
                        foreach ($POST['file_tag'] as $i => $value) {
                            if (is_numeric($value)) {
                                $value = (int)$value;
                                $SQL = "INSERT INTO tag_file (idTag, idFile)  value ($value,$InsertId)";
                                $result = mysqli_query($this->connect, $SQL) or
                                die(
                                json_encode(
                                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                                    JSON_UNESCAPED_UNICODE
                                )
                                );
                            }
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function getBD($id = null): array
        {
            $SQL = "SELECT * FROM file order by date";
            $query = mysqli_query($this->connect, $SQL) or die(
                $SQL . "|Couldn't execute query." . mysqli_error(
                    $this->connect
                )
            );
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            foreach ($data as $i => $val) {
                //ТЕГИ
                $SQL = "SELECT tag.Name FROM tag,(select * from tag_file where idFile={$val['id']}) as tag_file
                    where tag.id=tag_file.idTag";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['tag'] = mysqli_fetch_all($query);
                /*Научная тематика */
                $SQL = "SELECT sci_theme.Name FROM sci_theme,(select * from sci_theme_file where idFile={$val['id']}) as sci_theme_file
                    where sci_theme.id=sci_theme_file.idSciTheme";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['sci_theme'] = mysqli_fetch_all($query);
                /*Персоналии*/
                $SQL = "SELECT CONCAT(F,' ',I,' ',O) as Name FROM person,(select * from file_person where idFile={$val['id']}) as file_person
                    where person.id=file_person.idPerson";
                $query = mysqli_query($this->connect, $SQL) or
                die(
                json_encode(
                    ['err' => $SQL . "|Couldn't execute query." . mysqli_error($this->connect)],
                    JSON_UNESCAPED_UNICODE
                )
                );
                $data[$i]['person'] = mysqli_fetch_all($query);
            }
            return $data;
        }
    }