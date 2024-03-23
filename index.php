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
        $TAG='';
        if (isset($_GET['tag'])) {
            $TAG="?tag=".$_GET['tag'];
		}
        //echo "Location: index.php$TAG";
        header("Location: index.php$TAG");

    }
    $ArrContent = [
        [
            'Name' => 'Создана База АН СССР в Коми АССР',
            'Date' => '1944-06-03',
            'content' => 'Создана База АН СССР в Коми АССР. После реэвакуации Кольской базы АН СССР в г. Кировск в Сыктывкаре оставлены 21 научный сотрудник и 5 чел. научно-технического персонала.',
            'tag' => null,
            'doc' => '',
            'level' => 1
        ],
        [
            'Name' => 'Утверждена структура Базы Академии наук СССР в Коми АССР',
            'Date' => '1944-07-01',
            'content' => 'утверждена структура Базы Академии наук СССР в Коми АССР: дирекция, геологический, биологический отделы, секторы гидрологии и гидробиологии, научно-вспомогательные подразделения',
            'tag' => null,
            'doc' => '',
            'level' => 2
        ],
        [
            'Name' => 'Утвержден состав Ученого совета Базы АН СССР в Коми АССР',
            'Date' => '1944-12-11',
            'content' => 'постановлением бюро Совета филиалов и баз АН СССР был утвержден состав Ученого совета Базы АН СССР в Коми АССР в количестве 25 чел. В состав Ученого совета включены президент АН СССР академик В.Л. Комаров, академики В.Н. Образцов и И.Г. Эйхфельд. В совет вошли 13 сотрудников Базы, в том числе консультанты А.М. Преображенский, Е.Н. Иванова, А.Л. Курсанов.',
            'tag' => [
                'А.Л. Курсанов',
                'В.Л. Комаров',
                'В.Н. Образцов',
                'И.Г. Эйхфельд',
                'А.М. Преображенский',
                'Е.Н. Иванова'
            ],
            'doc' => '',
            'level' => 3
        ],
        [
            'Name' => 'Первое заседание',
            'Date' => '1945-04-04',
            'content' => 'Президиум Академии наук СССР принял решение ходатайствовать перед Советом Народных Комиссаров СССР о восстановлении Базы АН СССР по изучению Севера, преобразовав ее в Базу АН СССР в Коми АССР, с местонахождением в г. Сыктывкаре.',
            'tag' => null,
            'doc' => 'Ф.1. Оп.1. Д.68 «а». Л.5',
            'level' => 1
        ],
        [
            'Name' => 'Решение комиссии',
            'Date' => '1945-04-06',
            'content' => 'бюро Совета Филиалов и баз увеличило масштаб Базы АН ССР в Коми АССР до 85 чел.',
            'tag' => null,
            'level' => 2
        ],
        [
            'Name' => 'Распоряжение',
            'Date' => '1945-04-15',
            'content' => 'издано распоряжение Совнаркома СССР № 8153-р о реорганизации Базы АН СССР по изучению Севера',
            'tag' => null,
            'level' => 3
        ],
        [
            'Name' => 'Фото',
            'Date' => '1946-01-06',
            'content' => 'Визит директора Базы АН СССР в Коми АСССР, академика В.Н. Образцова <img class="timeline-img" src="img/3.jpg" alt="">',
            'tag' => ['В.Н. Образцов'],
            'level' => 1
        ],
        [
            'Name' => 'Сотрудникам вручены медали',
            'Date' => '1946-03-02',
            'content' => 'Сотрудникам К.А. Моисееву, А.И. Подоровой... вручены медали "За доблесный труд в Великой Отечественной войне 1941-45гг."',
            'tag' => ['К.А. Моисеев', 'А.И. Подоровой'],
            'level' => 2
        ],
        [
            'Name' => 'Организован сектор',
            'Date' => '1946-04-11',
            'content' => 'организованы сектор лесного хозяйства и группа по экономическим исследованиям',
            'tag' => null,
            'level' => 3
        ],
    ];

    $ArrPercon = [
        [
            'Name' => 'В.Н. Образцов',
            'FullName' => 'Образцов Владимир Николаевич',
            'Foto' => [['Date' => '1940-01-01', 'URL' => '1.jpg'], ['Date' => '1940-01-01', 'URL' => '2.jpg']],
            'content' => 'Образцов Владимир Николаевич (1874–1949), академик АН СССР (1939). В 1944 г. назначен директором Базы АН СССР в Коми АССР. К этому времени был членом Президиума АН СССР, заместителем председателя Совета филиалов и баз. Базой АН СССР в Коми АССР управлял дистанционно, постоянно проживая в Москве. Приезжал в Сыктывкар в августе 1945 г. и августе 1946 г.'
        ]
    ];

?>
<!DOCTYPE html>
<head>

	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="ico/font/bootstrap-icons.css" rel="stylesheet" >
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
	<link href="main.css?<?php echo time();?>" rel="stylesheet">
	<title></title>
</head>
<!------ Include the above in your HEAD tag ---------->
<body>
<div class="container">
	<div class="page-header">
		<h1 id="timeline">История КОМИ НАУЧНОГО ЦЕНТРА</h1>
		<?php
            $TAG='';
            if (isset($_GET['tag'])) {
                $TAG="&tag=".$_GET['tag'];
            }
		?>
		<p><a class="btn btn-lg btn-primary" href="?level=pl<?=$TAG?>">+</a>
			<a class="btn btn-lg btn-primary" href="?level=mn<?=$TAG?>">-</a><?php
                echo $_SESSION['level'] ?></p>
	</div>
    <?php
        if (isset($_GET['tag'])) {
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
        }
    ?>
	<ul class="timeline">
        <?php
            $Inv = false;
            $InvClass = '';
            foreach ($ArrContent as $i) {
                $InvClass = '';
                $skip = true;
                if ($Inv) {
                    $InvClass = 'class="timeline-inverted"';
                }
                $TAG = "";
                if (is_array($i['tag'])) {
                    foreach ($i['tag'] as $tag) {
                        $TAG .= "<a href='?tag={$tag}'>[$tag] </a>";
                        if (isset($visibleTag)) {
                            if ($visibleTag == $tag) {
                                $skip = false;
                            }
                        }
                    }
                    $TAG = "<p><small class='text-muted'>$TAG</small></p>";
                }
                $level['class'] = [
                    1 => 'danger',
                    2 => 'info',
                    3 => 'success'
                ];/*1. danger 2.info  3.success*/
                $level['glyphicon'] = [
                		/*<i class="bi bi-exclamation-octagon"></i>*/
                    1 => 'bi-exclamation-octagon',
                    2 => 'bi-card-checklist',
                    3 => 'bi-patch-check'
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
							<p>{$i['content']}</p>$TAG
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