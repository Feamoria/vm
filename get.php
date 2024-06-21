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
    if (isset($_GET['eventDisc'])) {
        $id = (int)$_POST["id"];
        $SQL = "SELECT id,`Desc`,Name FROM event
			where id=$id";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['Desc'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    } elseif (isset($_GET['event'])) {

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
            /*$SQL = "Select id from event where id > $idEvent order by id asc limit 1";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data['next'] =mysqli_fetch_all($query, MYSQLI_ASSOC);
            $SQL = "Select id from event where id < $idEvent order by id desc limit 1";
            $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
            $data['back'] =mysqli_fetch_all($query, MYSQLI_ASSOC);*/
            $SQL = "SELECT * FROM event
			where id = $idEvent";
        } else {
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

    } elseif (isset($_GET['person'])) {
        // Выбрать ВСЮ инфу о персоналии
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
        // Выбрать ВСЮ инфу о персоналии
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