<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once '../php_class/connect.php';
    $db = (new BDconnect())->connect();
    $SQL = "SELECT * FROM user order by dep";
    $SQL1='';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    echo "<table border='1'><tr>
                <th>Подразделение</th>
                <th>ФИО</th>
                <th>Событий</th>
                <th>Файлов</th>
                <th>Персон</th>
            </tr>";
    try {
        $query = mysqli_query($db, $SQL);
        $data = mysqli_fetch_all($query,MYSQLI_ASSOC);
       // var_dump($data);
        $first=true;
        $dep='';
        $CountEvent=0;
        $CountFile=0;
        $CountPerson=0;
        $st="style='background-color: grey;'";
        foreach ($data as $user) {
            if ($first) {
                $first=false;
                $dep=$user['dep'];
                $CountEvent=0;
                $CountFile=0;
                $CountPerson=0;
            }
            if ( $dep!=$user['dep']) {
                echo "<tr>
                    <td></td>
                    <td $st><strong>ИТОГО:</strong></td>
                    <td $st><strong>$CountEvent</strong></td>
                    <td $st><strong>$CountFile</strong></td>
                    <td $st><strong>$CountPerson</strong></td>
                    </tr>";
                $dep=$user['dep'];
                $CountEvent=0;
                $CountFile=0;
                $CountPerson=0;
            }
            $SQL1="SELECT 
                (SELECT COUNT(*) FROM event where create_user={$user['id']}) as CountEvent,
                (SELECT COUNT(*) FROM file where create_user={$user['id']}) as CountFile,
                (SELECT COUNT(*) FROM person where create_user={$user['id']}) as CountPerson";
            $query1 = mysqli_query($db, $SQL1);
            $data1 = mysqli_fetch_all($query1,MYSQLI_ASSOC);
            $CountEvent+=$data1[0]['CountEvent'];
            $CountFile+=$data1[0]['CountFile'];
            $CountPerson+=$data1[0]['CountPerson'];
            echo "<tr>
                    <td>{$user['dep']}</td>
                    <td>{$user['FIO']}</td>
                    <td>{$data1[0]['CountEvent']}</td>
                    <td>{$data1[0]['CountFile']}</td>
                    <td>{$data1[0]['CountPerson']}</td>
                    </tr>";

        }
        echo "<tr>
                    <td></td>
                    <td $st>ИТОГО:</td>
                    <td $st><strong>$CountEvent</strong></td>
                    <td $st><strong>$CountFile</strong></td>
                    <td $st><strong>$CountPerson</strong></td>
                    </tr>";
        echo "</table>";
    } catch (mysqli_sql_exception $exception) {
       // mysqli_rollback($db);
        $ret = [
            'errorSQL',
            'SQL' => $SQL,
            'SQL1' => $SQL1,
            'exception' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];
        die(json_encode($ret));
    }