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
    if (isset($_GET['eventDisc'])){
        $id=(int)$_POST["id"];
        $SQL = "SELECT id,`Desc`,Name FROM event
			where id=$id";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data['Desc'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    ///var_dump($input);
    if (isset($_GET['event'])) {
        $YearN=(int)$input["year"][0];
        $YearE=(int)$input["year"][1];
        $mod =$input["mod"];// round(((int)$input["year"][1]-(int)$input["year"][0])/5);
        $level =ceil((5.0001)-($YearE-$YearN)/$mod);
        $data['level']=$level;
        $data['mod']=$mod;
        //$level = 5;
        $YearN=$YearN-1;
        $YearE=$YearE+1;
        $them_sql='';
        $them=(int)$input["them"];
        if ($them>0) {
            $them_sql="and id in (SELECT idEvent from sci_theme_event where idTheme=$them)";
        }
        $SQL = "SELECT id,Name,DateN as `Date`,`doc`,importance as `level` FROM event
			where importance <= $level
            and DateN between '$YearN' and '$YearE'
            $them_sql
			order by DateN";
        $data['SQL']=$SQL;
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