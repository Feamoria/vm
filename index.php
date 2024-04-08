<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    session_start();
    if (!isset($_SESSION['level'])) {
        $_SESSION['level'] = 1;
    }
    if (isset($_GET['level'])) {
        if ($_GET['level'] == 'pl') {
            $_SESSION['level']++;
        } else {
            $_SESSION['level']--;
        }
        if ($_SESSION['level'] < 1) {
            $_SESSION['level'] = 1;
        }
        $TAG = '';
        if (isset($_GET['tag'])) {
            $TAG = "?tag=" . $_GET['tag'];
        }
        //echo "Location: index.php$TAG";
        header("Location: index.php$TAG");
    }

    require_once 'php_class/connect.php';
    $db = (new BDconnect())->connect();
    $SQL = "SELECT id,Name,DateN as `Date`,`doc`,importance as `level` FROM event
			where importance <= {$_SESSION['level']}
			order by DateN";
    $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach ($data as $i => $val) {
        /** person */
        $SQL = "
			SELECT person.id,CONCAT(person.F,' ',person.I,' ',person.O) as Name 
				FROM person, (SELECT * from person_event where idEvent={$val['id']}) as person_event
			where person.id=person_event.idPerson";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data[$i]['person'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        /** tag */
        $SQL = "
			SELECT tag.id,tag.Name 
				FROM tag, (SELECT * from tag_event where idEvent={$val['id']}) as tag_event
			where tag.id=tag_event.idTag";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data[$i]['tag'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
        /** file */
        $SQL = "
			SELECT file.id,file.Name,file.pathWeb
				FROM file, (SELECT * from file_event where idEvent={$val['id']}) as file_event
			where file.id=file_event.idFile";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data[$i]['file'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    $ArrContent = $data;

?>
<!DOCTYPE html>
<head>
	<script src="js/jquery/jquery-3.7.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="ico/font/bootstrap-icons.css" rel="stylesheet">
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
	<script src="js/main.js?<?php
        echo time(); ?>"></script>
	<link href="css/main.css?<?php
        echo time(); ?>" rel="stylesheet">
	<title></title>
</head>

<body>
<pre><?php //var_dump($ArrContent); ?> </pre>
<div class="container">
	<div class="page-header">
		<h1 id="timeline">История КОМИ НАУЧНОГО ЦЕНТРА</h1>
        <?php
            $TAG = '';
            if (isset($_GET['tag'])) {
                $TAG = "&tag=" . $_GET['tag'];
            }
        ?>
		<p><a class="btn btn-lg btn-primary" href="?level=pl<?= $TAG ?>">+</a>
			<?php echo $_SESSION['level'] ?>
			<a class="btn btn-lg btn-primary" href="?level=mn<?= $TAG ?>">-</a></p>
	</div>
    <?php
        /* TODO ШАПКА ИНФЫ ДЛЯ ПЕРСОНАЛИИ*/

        /*if (isset($_GET['tag'])) {
            $visibleTag = $_GET['tag'];
            foreach ($ArrPercon as $pers) {
                if ($pers['Name'] == $visibleTag) {
                    $foto = '';
                    foreach ($pers['Foto'] as $pic) {
                        $foto .= "<img  style='height: 250px;' src='img/{$pic['URL']}' alt='{$pic['Date']}'>";
                    }
                    $HTML = "<div class='page-header'>
                            <h1 id='timeline'>{$pers['FullName']}</h1>
                            <p>$foto</p>
                            <p>{$pers['content']}</p>
                </div>";
                    echo $HTML;
                }
            }
        }*/
    ?>
	<ul class="timeline">
        <?php
            $Inv = false;
            $InvClass = '';
            foreach ($ArrContent as $id_EV => $i) {
                $InvClass = '';
                $skip = true;
                if ($Inv) {
                    $InvClass = 'class="timeline-inverted"';
                }
                /** person*/
                $person_html = "";
                if (isset($i['person'])) {
                    if (is_array($i['person'])) {
                        foreach ($i['person'] as $id => $person) {
                            $person_html .= "<a href='?person=" . $person['id'] . "'>[" . $person['Name'] . "]</a>";
                        }
                        if ($person_html !=='')
                        $person_html = "<p><small class='text-muted'>Персоналии: $person_html</small></p>";
                    }
                }
                /** file */
                $file_html = "";
                if (isset($i['file'])) {
                    if (is_array($i['file'])) {
                        foreach ($i['file'] as $id => $val) {
                            $file_html .= "<img class='timeline-img' src='".$val['pathWeb']."'>";
                        }
                        $file_html = "<p>$file_html</p>";
                    }
                }
                /** tag */
                $tag_html = "";
                if (isset($i['tag'])) {
                    if (is_array($i['tag'])) {
                        foreach ($i['tag'] as $id => $val) {
                            $tag_html .= "<a href='?tag=" . $val['id'] . "'>[" . $val['Name'] . "]</a>";
                        }
                        if ($tag_html !=='')
                            $tag_html = "<p><small class='text-muted'>Ключевые слова: $tag_html</small></p>";
                    }
                }
                /** dep */
                /** them */
                $them_html = "";
                if (isset($i['them'])) {
                    if (is_array($i['them'])) {
                        foreach ($i['them'] as $id => $val) {
                            $them_html .= "<a href='?them=" . $val['id'] . "'>[" . $val['Name'] . "]</a>";
                        }
                        if ($them_html !=='')
                            $them_html = "<p><small class='text-muted'>Тематика: $them_html</small></p>";
                    }
                }
				/** */
                $level['class'] = [
                    1 => 'danger',
                    2 => 'info',
                    3 => 'success',
                    4 => 'success'
                ];/*1. danger 2.info  3.success*/
                $level['glyphicon'] = [
                    /*<i class="bi bi-exclamation-octagon"></i>*/
                    1 => 'bi-exclamation-octagon',
                    2 => 'bi-card-checklist',
                    3 => 'bi-patch-check',
                    4 => 'bi-patch-check'
                ];//'glyphicon-check';
                $HTML = '';
                if ($i['level'] <= $_SESSION['level']) {
                    // $skip = false;

                    $HTML = "
					<li $InvClass>
					<div class='timeline-badge {$level['class'][$i['level']]}'><i class='bi {$level['glyphicon'][$i['level']]}'></i></div>
					<div class='timeline-panel'>
						<div class='timeline-heading'>
							<h4 class='timeline-title'>{$i['Name']}</h4>
							<p><small class='text-muted'><i class='bi bi-clock'></i> {$i['Date']}</small></p>
						</div>
						<div class='timeline-body'>
							$file_html
							<button class='btn btn-sm btn-info' onclick='getInfo($id_EV)'>Подробнее</button>
							$person_html
							$tag_html
						</div>
					</div>
					</li>";

                    if (!($skip and (isset($visibleTag)))) {
                        $Inv = !$Inv;
                        echo $HTML;
                    }
                }
            }
            //echo $HTML;
        ?>
	</ul>
</div>
</body>