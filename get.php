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
    $input = json_decode(file_get_contents("php://input"), true);

    ///var_dump($input);
    if (isset($_GET['event'])) {
        $level = $input['level'];
        //$level = 5;
        $SQL = "SELECT id,Name,DateN as `Date`,`doc`,importance as `level` FROM event
			where importance <= $level
			order by DateN";
    } elseif (isset($_POST['person'])) {
        // Выбрать ВСЮ инфу о персоналии
        $SQL = "SELECT id,Name,DateN as `Date`,`doc`,importance as `level` FROM event
			where importance <= $level
			order by DateN";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        //Выбрать события с этой персоналией (без разбивки по важности?) where importance <= {$_SESSION['level']}
        $SQL = "SELECT id,Name,DateN as `Date`,`doc`,importance as `level` FROM event
			
			order by DateN";
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
			SELECT file.id,file.Name,file.pathWeb
				FROM file, (SELECT * from file_event where idEvent={$val['id']}) as file_event
			where file.id=file_event.idFile";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['event'][$i]['file'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }


    die(json_encode($data, JSON_UNESCAPED_UNICODE));