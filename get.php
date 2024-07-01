<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    /** TODO
     * POST
     * ?event&tag=[]&year=[]&them=[]&dep=[]
     * ?person&tag=[]&year=[]&them=[]&dep=[]
     * */
    require_once 'php_class/connect.php';
    $db = (new BDconnect())->connect();
    $data = [];
    $SQL = '';
    //$input = json_decode(file_get_contents("php://input"), true);
    /**
     * eventDisc! Описание у эвента.
     */
    if (isset($_GET['eventDisc'])) {
        $id = (int)$_POST["id"];
        $SQL = "SELECT id,`Desc`,Name FROM event
			where id=$id";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['Desc'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    /**
     * EVENT!
     */
    } elseif (isset($_GET['event'])) {
        /**
         * comment: Если у эвента есть id
         */
        if (!empty($_GET['event'])) {

            $idEvent = (int)$_GET['event'];
            if ($idEvent <= 0) {
                die(json_encode(['err' => '$_GET[event]=' . $idEvent], JSON_UNESCAPED_UNICODE));
            }
            $SQL="SELECT id from event order by DateN,id";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $arrId=[];
            while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                $arrId[]=$row['id'];
            }
            $data['next']=null;
            $data['back']=null;
            foreach ($arrId as $i =>$val){
                if ($val==$idEvent){
                    if (isset($arrId[$i+1])) {
                        $data['next'] =$arrId[$i+1];
                    }
                    if (isset($arrId[$i-1])) {
                        $data['back']=$arrId[$i-1];
                    }
                }
            }
            $SQL = "SELECT * FROM event
			where id = $idEvent";
        }
        else /// Иначе выводим период
        {
            if (isset($_POST["data"])) {
                $data = json_decode($_POST["data"], true);
                // var_dump($data);
                $YearN = (int)$data['year'][0];
                $YearE = (int)$data['year'][1];
                if (!empty($data["mod"])) {
                    $mod = $data["mod"];// round(((int)$input["year"][1]-(int)$input["year"][0])/5);
                    $level = ceil((5.0001) - ($YearE - $YearN) / $mod);
                    $data['level'] = $level;
                    $data['mod'] = $mod;
                } else {
                    $level = $data['level'];
                }
                $them = (int)$data["them"];
            } else {
                $YearN = (int)$_POST['yearB'];
                $YearE = (int)$_POST['yearN'];
                $them = (int)$_POST['them'];
                $level = (int)$_POST['level'];
                $YearN = $YearN - 1;
                $YearE = $YearE + 1;
            }
            $them_sql = '';
            if ($them > 0) {
                $them_sql = "and id in (SELECT idEvent from sci_theme_event where idTheme=$them)";
            }
            $SQL = "SELECT id,Name,DateN as `Date`,DateK,`doc`,importance as `level` FROM event
			where importance <= $level
            and DateN between '$YearN' and '$YearE'
            $them_sql
			order by DateN";
        }
        $data['SQL'] = $SQL;
        //} else die(json_encode(['err'=>'отсутствует POST параметр data '],JSON_UNESCAPED_UNICODE));

    }
    /**
     * department
     */
    elseif (isset($_GET['department'])) {
        if (!empty($_GET['department'])) {
            /**
             *  ЕСЛИ запрос идёт по id
             *  1) выбирает инфу о  department
             *  2) Коллекции
             *  3) Персоналии подразделения
             *  4) запрос о событиях подразделения
             *
             */
            $idDepartment = (int)$_GET['department'];
            if  ($idDepartment > 0) {
                $SQL = "SELECT * from sci_department where id=$idDepartment";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['department'] = mysqli_fetch_all($query, MYSQLI_ASSOC);

                $SQL = "SELECT * from collection where id in (
                                select idCollection from sci_department_collection 
                                where idSciDepartment=$idDepartment )";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['collection'] = mysqli_fetch_all($query, MYSQLI_ASSOC);

                $SQL = "SELECT * from person where id in (
                                select idPerson from sci_department_person 
                                where idSciDepartment=$idDepartment )";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['person']= mysqli_fetch_all($query, MYSQLI_ASSOC);

                $SQL = "SELECT * from file where id in (
                                select idFile from sci_department_file 
                                where idSciDepartment=$idDepartment )";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['file']= mysqli_fetch_all($query, MYSQLI_ASSOC);

                $SQL = "SELECT * from event where id in (
                                select idEvent from sci_department_event 
                                where idSciDepartment=$idDepartment )";
            } else die(json_encode(['err'=>'idDepartment is not found'], JSON_UNESCAPED_UNICODE));
        } else {
            /** Вывод списка подразделений*/
            $SQL = "SELECT * from sci_department ";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data['department'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            die(json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }
    /**
     * collection
     */
    elseif (isset($_GET['collection'])) {
        $idCollection = (int)$_GET['collection'];
        if  ($idCollection > 0) {
            $SQL="SELECT * from collection where id = $idCollection";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data['collection'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $SQL = "SELECT * from collectionItem
                    where idCollection = $idCollection";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data['collectionItem'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
            foreach ($data['collectionItem'] as $i => $val) {
                $idItem=(int)$val['id'];
                //person
                $SQL = "SELECT * from person where id in (
                                select idPerson from person_collectionItem 
                                where idCollectionItem=$idItem )";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['collectionItem'][$i]['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
                //sci_theme
                $SQL = "SELECT * from sci_theme where id in (
                                select idSciDepartment from sci_theme_collectionItem 
                                where idCollectionItem=$idItem )";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['collectionItem'][$i]['sci_theme']= mysqli_fetch_all($query, MYSQLI_ASSOC);
                //sci_theme
                $SQL = "SELECT * from tag where id in (
                                select idTag from tag_collectionItem 
                                where idCollectionItem=$idItem )";
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['collectionItem'][$i]['tag']=mysqli_fetch_all($query, MYSQLI_ASSOC);
                //file
                $SQL = "SELECT * from file where id =".$val['idFile'];
                $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                $data['collectionItem'][$i]['file']=mysqli_fetch_all($query, MYSQLI_ASSOC);

            }
            die(json_encode($data, JSON_UNESCAPED_UNICODE));
        } else die(json_encode(['err'=>'idCollection is not found'], JSON_UNESCAPED_UNICODE));
    }
    /**
     * Выбрать ВСЮ инфу о персоналии
     */
    elseif (isset($_GET['person'])) {

        $idPers = (int)$_POST['person'];
        $SQL = "SELECT * from person where id=$idPers";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $SQL = "SELECT file.id,file.Name,file.pathWeb,file.disc from file where id in (SELECT idFile from file_person where idPerson=$idPers)";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['personFile'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        $SQL = "SELECT id,Name,DateN as `Date`,`doc`,importance as `level` FROM event
			where id in (SELECT idEvent from person_event where idPerson=$idPers)
			order by DateN";
    } elseif (isset($_GET['personAll'])) {
        /**
         * Выбрать ВСЮ инфу о всех персоналииях
         */
        //$idPers=(int)$_POST['person'];
        $SQL = "SELECT * from person";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    } else {
        die(json_encode(['err' => 'не выбран метод'], JSON_UNESCAPED_UNICODE));
    }
    $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
    $data['event'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach ($data['event'] as $i => $val) {
        /** Создатель и модератор **/
        $SQL="SELECT FIO,dep FROM user where id=".$val['create_user'];
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $user=mysqli_fetch_all($query, MYSQLI_ASSOC);
        $data['event'][$i]['create_user']=['id'=>$val['create_user'],'fio'=>$user[0]['FIO'],'dep'=>$user[0]['dep']];
        if (!empty($val['moderated_user'])){
            $SQL="SELECT FIO FROM user where id=".$val['moderated_user'];
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $user=mysqli_fetch_all($query, MYSQLI_ASSOC);
            $data['event'][$i]['moderated_user']=['id'=>$val['moderated_user'],'fio'=>$user[0]['FIO'],'dep'=>$user[0]['dep']];;
        }
        /** person */
        $SQL = "
			SELECT person.id,CONCAT(person.F,' ',person.I,' ',person.O) as Name 
				FROM person, (SELECT * from person_event where idEvent={$val['id']}) as person_event
			where person.id=person_event.idPerson";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['event'][$i]['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        /** tag */
        $SQL = "
			SELECT tag.id,tag.Name 
				FROM tag, (SELECT * from tag_event where idEvent={$val['id']}) as tag_event
			where tag.id=tag_event.idTag";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['event'][$i]['tag'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        /** file */
        $SQL = "
			SELECT file.id,file.Name,file.pathWeb,file.disc
				FROM file, (SELECT * from file_event where idEvent={$val['id']}) as file_event
			where file.id=file_event.idFile";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['event'][$i]['file'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        /*sci_theme*/
        $SQL = "
			SELECT sci_theme.id,sci_theme.Name
				FROM sci_theme, (SELECT * from sci_theme_event where idEvent={$val['id']}) as sci_theme_event
			where sci_theme.id=sci_theme_event.idTheme";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['event'][$i]['sci_theme'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        /**/
        $SQL = "
			SELECT sci_department.id,sci_department.Name
				FROM sci_department, (SELECT * from sci_department_event where idEvent={$val['id']}) as sci_department_event
			where sci_department.id=sci_department_event.idSciDepartment";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['event'][$i]['sci_department'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }


    die(json_encode($data, JSON_UNESCAPED_UNICODE));