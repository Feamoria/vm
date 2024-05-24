<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once '../php_class/connect.php';
    //die(hash('sha512', '15142342'));
    session_start([
                      'cookie_lifetime' => 86400,
                  ]);/*Аутентификация*/
    if (isset($_GET['auth'])) {
        $db = (new BDconnect())->connect();
        $SQL = "SELECT *,dep FROM user WHERE login='" . mysqli_real_escape_string($db, $_POST['login']) . "' LIMIT 1";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        $data = mysqli_fetch_assoc($query);
        # Сравниваем пароли

        if ($data['pass'] === hash('sha512', $_POST['password']))//Авторизация пройдена
        {
            $_SESSION['user']['id'] = $data['id'];
            $_SESSION['user']['site'] = 'vm';
            $_SESSION['user']['dep'] = $data['dep'];
            $_SESSION['user']['role'] = $data['role'];
            $_SESSION['user']['FIO'] = $data['FIO'];
            $_SESSION['user']['login'] = $data['login'];
            $_SESSION['user']['pass'] = $data['pass'];
            # Обновляем страницу с данными авторизации

        } else//Авторизация отклонена
        {
            $_SESSION['mess'] = 'Вы ввели неверный логин/пароль';
        }
        header("Location: /vm/add/index.php");
    } else /*Аутентификация*/ {
        if (!isset($_SESSION['user']['site'])) {
            $mess = '';
            if (isset($_SESSION['mess'])) {
                $mess = $_SESSION['mess'];
                $_SESSION['mess'] = null;
            }
            $html = '<h1 style="color: red;" align="center">Не авторизовано</h1>
			<h2 align="center">Авторизуйтесь в системе</h2>
			<div style="position: fixed;top: 180px;left: 50%;width: 250px; height:80px; background: #f0f0f0;transform: translate(-50%, 0%);padding: 15px;">
			<form method="POST" class="" action="index.php?auth"  >
			<div style="padding-bottom:10px;">
				<label for="login">Логин</label>
				<input name="login" style="float:right;width:140px;" type="text">
			</div>
			<div style="padding-bottom:10px;">
				<label for="password">Пароль</label>
				<input name="password" style="float:right;width:140px;" type="password">
			</div>
			<div style="margin:auto;padding:10px;width:50px;">
			    <P style="color: red">' . $mess . '</P>
				<input name="submit" value="Войти" type="submit">
			</div>
			</form></div>';
            die($html);
        }
    }


?>
<!DOCTYPE html>
<html lang='ru'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'/>
	<title>Добавление данных</title>
	<link rel="icon" type="image/png" href="favicon.ico"/>
	<script src="../js/jquery/jquery-3.7.1.min.js"></script>
	<script src="../js/jquery/jquery.form.js"></script>
	<script src="../js/jquery/jquery-ajax-native.js"></script>
	<script src="../js/jquery/jquery-ui/jquery-ui.min.js"></script>
	<script src="../js/jquery/jquery-ui/jquery.ui.datepicker-ru.js"></script>
	<link href="../js/jquery/jquery-ui/jquery-ui.min.css" rel="stylesheet">
	<link href="../js/jquery/jquery-ui/jquery-ui.structure.css" rel="stylesheet">
	<link href="../js/jquery/jquery-ui/jquery-ui.theme.css" rel="stylesheet">
	<!--multiselect  https://github.com/ehynds/jquery-ui-multiselect-widget-->
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.js'></script>
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.ru.js'></script>
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.filter.js'></script>
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.filter.ru.js'></script>
	<link rel='stylesheet' type='text/css' media='screen' href='../js/jquery/jquery-multiselect/jquery.multiselect.css'/>
	<link rel='stylesheet' type='text/css' media='screen' href='../js/jquery/jquery-multiselect/jquery.multiselect.filter.css'/>
	<!-- bootstrap -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../ico/font/bootstrap-icons.css" rel="stylesheet">
	<script src="../js/bootstrap/bootstrap.bundle.min.js"></script>

	<!-- Upload_master jQuery plugin -->
	<script src="../js/Upload_master/js/vendor/jquery.ui.widget.js"></script>
	<script src="../js/Upload_master/js/jquery.iframe-transport.js"></script>
	<script src="../js/Upload_master/js/jquery.fileupload.js"></script>

	<!-- tablesorter -->


	<!-- blue theme stylesheet -->
	<link rel="stylesheet" href="../js/tablesorter/css/theme.default.min.css">
	<!-- tablesorter plugin -->
	<script src="../js/tablesorter/js/jquery.tablesorter.js"></script>

	<!-- tablesorter widget file - loaded after the plugin -->
	<script src="../js/tablesorter/js/jquery.tablesorter.widgets.js"></script>


	<!-- MY -->
	<script src="js/main.js?<?php
        echo time(); ?>"></script>
	<link href="css/main.css?<?php
        echo time(); ?>" rel="stylesheet">
</head>
<body>
<div class="row">
	<div class="col-md-12">Пользователей онлайн: <span id="UserOnline"></span></div>
</div>
<div id="dialog_del" title="Удаление" style="display: none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Удалить запись?</p>
</div>
<div id="dialogImportance" style="display: none">
	<p>1 уровень – события на уровне ФИЦ <span style="color: green">(см. издание «Коми научный центр УрО РАН (1944-2019). Сыктывкар, 2019. 168 с.)</span>
	</p>
	<p>2 уровень - события на уровне ОП (создан отдел, сектор, лаборатория и т.д.)</p>
	<p>3 уровень – открытия /находки <span style="color: green">(например, На р. Чусовая на широте 58° с. ш. геологи и археологи Коми филиалаАН СССР открыли самую северную в мире стоянку древнепалеолитического человека, жившего около 250 тыс. лет назад)</span>.
	</p>
	<p>4 уровень – издания/публикации/монографии (фундаментальные работы)</p>
	<p>5 уровень – защита докторских диссертаций</p>
	<p>6 уровень – конгрессы/конференции <span style="color: green">(международные, всероссийские; конференции, которые носят статус продолжающихся, вносятся в систему однажды)</span>
	</p>

</div>
<div id="dialog_file" title="Выбор файлов" style="display: none">
	<div class="row">
		<!--<div class="input-group">
			<span style="width: 20%" class="input-group-text text-bg-success">Фильтрация</span>
-->
		<form id="s_form">
			<div class="container">
				<div class="row row-cols-2">
					<div class="col">
						<input id="s_Name" name="s_Name" type="text" class="form-control" placeholder="Название" aria-label="Название">
					</div>
					<div class="col">
						<select id="s_pers" name="s_pers[]" multiple class="form-select" aria-label="Персоналии"></select>
					</div>
					<div class="col">
						<select id="s_tem" name="s_tem[]" multiple class="form-select" aria-label="Направление науки"></select>
					</div>
					<div class="col">
						<select id="s_tg" name="s_tag[]" multiple class="form-select" aria-label="Ключевые слова"></select>
					</div>
					<div class="col">
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" role="switch" id="my_Efile">
							<label class="form-check-label" for="my_Efile">Показать файлы моего отдела</label>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!--
		</div>-->
	</div>
	<div id="dialog_file_cont" class="row"></div>
</div>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">События</a></li>
		<li><a href="#tabs-2">Персоналии</a></li>
		<li><a href="#tabs-3">Файлы</a></li>
		<li><a href="#tabs-4">Ключевые слова</a></li>
		<li><a href="#tabs-5">Направление науки</a></li>
		<li><a href="#tabs-6">Структурное подразделение</a></li>
		<li><a href="#tabs-7">Коллекции</a></li>
		<li><a href="#tabs-8">Экземпляры колекции</a></li>
	</ul>
	<div id="tabs-1">
		<form class="row" id="event">
			<input id="eventID" name="eventID" type="hidden">
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">Название события (краткая аннотация)<span style="color: red">*</span></span>
				<textarea id="ev_Name" required name="ev_Name" class="form-control" aria-label="" title="Обязательное поле с краткой информацией в научном стиле* до 200 знаков с пробелами"></textarea>
				<span class="input-group-text" id="ev_Name_COUNT"></span>
			</div>
			<div id="" class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Дата начала<span style="color: red">*</span></span>
				<input type="text" id='ev_Y_n' required name="ev_Y_n" aria-label="" class="form-control" placeholder="Год (обязательно)" title="Обязательное поле с указанием года, (при наличии) даты и месяца">
				<input type="text" id='ev_M_n' name="ev_M_n" aria-label="" class="form-control" placeholder="Месяц (1-12)">
				<input type="text" id='ev_D_n' name="ev_D_n" aria-label="" class="form-control" placeholder="День (1-31)">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Дата конца&nbsp;&nbsp;</span>
				<input type="text" id='ev_Y_e' name="ev_Y_e" aria-label="" class="form-control" placeholder="Год">
				<input type="text" id='ev_M_e' name="ev_M_e" aria-label="" class="form-control" placeholder="Месяц (1-12)">
				<input type="text" id='ev_D_e' name="ev_D_e" aria-label="" class="form-control" placeholder="День (1-31)">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Полное описание события</span>
				<textarea id="ev_Desc" name="ev_Desc" class="form-control" aria-label="" title="Расширенная версия события в научном стиле"></textarea>
			</div>
			<div id="div_ev_file" class="input-group" title="Фотографии, связанная с событием">
				<span style="width: 20%" class="input-group-text text-bg-success">Файл</span>
				<select id="ev_file" name="ev_file[]" class="form-select" style="display: none" multiple aria-label="">
				</select>
				<button style="width: 5%" class="btn btn-info" id="btn_open_file"><i class="bi bi-folder-fill"></i>
				</button>
				<span id="ev_file_text" style="width: 75%" class="input-group-text"></span>
			</div>
			<!--Важность события -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Важность события<span style="color: red">*</span><i id="importance" class="bi bi-question-circle-fill"></i> </span>
				<select id="ev_importance" name="ev_importance" required class="form-select" aria-label="">
					<option selected value="">--</option>
					<option value="1">1 уровень</option>
					<option value="2">2 уровень</option>
					<option value="3">3 уровень</option>
					<option value="4">4 уровень</option>
					<option value="5">5 уровень</option>
					<option value="6">6 уровень</option>
				</select>
			</div>
			<!--Ссылки на архивный документ -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ссылка</span>
				<input type="text" id='ev_doc' name='ev_doc' aria-label="" class="form-control" title="ссылка на архивный документ/гиперссылка на сайт/видео/статью/книгу и др.)">
			</div>
			<!--Персоналии multiple -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Персоналии</span>
				<select id="ev_pers" name="ev_pers[]" class="form-select" multiple aria-label=""></select>
				<input style="width: 30%" type="text" id='ev_pers_add' aria-label="" class="form-control" placeholder="">
			</div>
			<!--Структурное подразделение -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="ev_SP">Структурное подразделение</span>
				<select id="ev_sci_department" name="ev_sci_department[]" multiple class="form-select" aria-label="">

				</select>
			</div>
			<!--Направление науки -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Направление науки</span>
				<select id="ev_tem" name="ev_tem[]" multiple class="form-select" aria-label="">
				</select>
			</div>
			<!--Ключевые слова -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="ev_tag" name="ev_tag[]" class="form-select" multiple aria-label="">
				</select>
				<input style="width: 30%" type="text" id='ev_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary" id='ev_tag_add_btn'>Добавить</button>
			</div>
			<!--Место (координаты) -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Координаты</span>
				<input type="text" id='ev_latitude' name='latitude' aria-label="" class="form-control" placeholder="Ю.Ш.">
				<input type="text" id='ev_longitude' name='longitude' aria-label="" class="form-control" placeholder="В.Д.">

			</div>
			<div class="input-group">
				<button class="btn btn-primary" id='ev_btn_send'>Отправить</button>
			</div>
		</form>
		<div class="col-md-12" style="margin-top: 15px;margin-bottom: 15px;">
			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="my_event">
				<label class="form-check-label" for="my_event">Показать события моего отдела</label>
			</div>
		</div>
		<div id="div_tbl_event">

		</div>

	</div>
	<div id="tabs-2">
		<form class="row" method="post" id="pers">
			<div class="input-group" id="div_persFio" title="Обязательное поле с указанием Фамилии, Имени, Отчества (полностью)">
				<input id="persID" name="persID" type="hidden">
				<span style="width: 20%" class="input-group-text text-bg-success">ФИО персоны<span style="color: red">*</span></span>
				<input id="pers_F" name="pers_F" required type="text" class="form-control" placeholder="Фамилия" aria-label="Фамилия">
				<input id="pers_I" name="pers_I" required type="text" class="form-control" placeholder="Имя" aria-label="Имя">
				<input id="pers_O" name="pers_O" required type="text" class="form-control" placeholder="Отчество" aria-label="Отчество">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Годы жизни</span>
				<input id="pers_date1" required name="pers_date1" type="text" class="form-control" placeholder="c (обязательное)" aria-label="" title="Обязательное поле с указанием даты рождения (число, месяц, год). Если по невозможно определить точно укажите значение 00.00.0000 ">
				<input id="pers_date2" name="pers_date2" type="text" class="form-control" placeholder="по" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ученая степень/должность<span style="color: red">*</span></span>
				<input id="pers_dol" name="pers_dol" required type="text" class="form-control" placeholder="" aria-label="" title="Обязательное поле с указанием ученой степени (кандидат/доктор (каких?) наук), должности (заведующий лабораторией и т.д.)">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Биографические данные (кратко)</span>
				<textarea id="pers_Desc" name="pers_Desc" class="form-control" aria-label="" title="Пример: Окончил историко-филологический факультет Коми государственного пединститута (1971). С 1971 г. работает в ИЯЛИ Коми НЦ УрО РАН: до 1976 г. – лаборант, с 1976 г. – младший научный сотрудник, с 1982 г. по 1985 г.−  ученый секретарь, с 1985 г. по 1987 г. – заведующий сектором языка, с 1987 г. по 1997 г. – старший научный сотрудник, с 1997 г. по 2004 г. – ведущий научный сотрудник, с 2004 по настоящее время – главный научный сотрудник."></textarea>
				<span class="input-group-text" id="pers_Desc_short_COUNT"></span>
			</div>
			<!--Основные публикации -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Основные публикации</span>
				<textarea id="pers_publications" name="pers_publications" class="form-control" aria-label="" title="не более 10; по ГОСТу Р 7.0.5–2008"></textarea>
			</div>
			<!--Награды, звания -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Награды, звания</span>
				<textarea id="pers_awards" name="pers_awards" class="form-control" aria-label="" title="от федеральных к региональным и ведомственным. Для примера:Почетная грамота Республики Коми (2002)."></textarea>
			</div>
			<!--Структурное подразделение -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Структурное подразделение</span>
				<select id="pers_sci_department" name="pers_sci_department[]" multiple class="form-select" aria-label="">

				</select>
			</div>
			<!--Направление науки -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Направление науки</span>
				<select id="pers_tem" name="pers_tem[]" class="form-select" multiple aria-label="">
				</select>
			</div>
			<!--Ключевые слова -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="pers_tag" name="pers_tag[]" class="form-select" multiple aria-label="">
				</select>
				<input style="width: 10%" type="text" id='pers_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary" id='pers_tag_add_btn'>+</button>
			</div>
			<div class="input-group">
				<button class="btn btn-primary" id='pers_btn_send'>Сохранить персоналию</button>
			</div>
		</form>
		<div class="col-md-12" style="margin-top: 15px;margin-bottom: 15px;">
			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="my_pers">
				<label class="form-check-label" for="my_pers">Показать персоналии моего отдела</label>
			</div>
		</div>
		<div id="div_tbl_person">

		</div>

	</div>
	<div id="tabs-3">
		<form class="row" method="post" id="file">
			<div class="input-group">
				<input id="id_file" name="id_file" type="hidden">
				<span style="width: 20%" class="input-group-text text-bg-success">Файл<span style="color: red">*</span></span>
				<input id="file_F" name="file_F" required type="file" class="form-control" placeholder="" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Дата</span>
				<input id="file_date" name="file_date" type="text" class="form-control" placeholder="1947.01.01 или 1947.02.01  если неизвестна точная дата или месяц" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Название файла<span style="color: red">*</span></span>
				<input id="file_name" name="file_name" required type="text" class="form-control" placeholder="" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Аннотация</span>
				<textarea id="file_Desc" name="file_Desc" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="file_Desc_short_COUNT"></span>
			</div>
			<!--Ссылки на архивный документ -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ссылка</span>
				<input style="width: 10%" type="text" id='file_doc' name='file_doc' aria-label="" class="form-control" title="ссылка на архивный документ/гиперссылка на сайт/видео/статью/книгу и др.)">
			</div>
			<!--Персоналии -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Персоналии</span>
				<select id="file_pers" name="file_pers[]" class="form-select" multiple aria-label=""></select>
				<input style="width: 30%" type="text" id='file_pers_add' aria-label="" class="form-control" placeholder="">
			</div>
			<!--Структурное подразделение -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Структурное подразделение</span>
				<select id="file_sci_department" name="file_sci_department[]" class="form-select" multiple aria-label=""></select>
			</div>
			<!--Направление науки -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Направление науки</span>
				<select id="file_tem" name="file_tem[]" class="form-select" multiple aria-label="">

				</select>
			</div>

			<!--Ключевые слова -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="file_tag" name="file_tag[]" class="form-select" multiple aria-label="">

				</select>
				<input style="width: 10%" type="text" id='file_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary" id='file_tag_add_btn'>+</button>
			</div>
			<div class="input-group">
				<button class="btn btn-primary" id='file_btn_send'>Сохранить файл</button>
			</div>
		</form>
		<div class="col-md-12" style="margin-top: 15px;margin-bottom: 15px;">
			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" role="switch" id="my_file">
				<label class="form-check-label" for="my_file">Показать файлы моего отдела</label>
			</div>
		</div>
		<div id="div_tbl_file">

		</div>
	</div>
	<div id="tabs-4">
		<form class="row" method="post" id="tag">
			<div class="input-group">
				<span style="width: 30%" class="input-group-text text-bg-success">Ключевое слово</span>
				<input style="width: 30%" type="text" id='tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary" id='tag_add_btn'>Добавить</button>
			</div>
		</form>
		<table class="" id="tbl_tag">

		</table>
	</div>
	<div id="tabs-5">
		<form class="row" method="post" id="sci_field">
			<div class="input-group">
				<span style="width: 30%" class="input-group-text text-bg-success">Направление науки</span>
				<input style="width: 30%" type="text" id='sci_field_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary" id='sci_field_add_btn'>Добавить</button>
			</div>
		</form>
		<table class="" id="tbl_sci_field">

		</table>
	</div>
	<div id="tabs-6">
		<form class="row" method="post" id="sci_department">
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Структурное подразделение<span style="color: red">*</span></span>
				<input type="text" id='sci_department_name' name='sci_department_name' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Даты<span style="color: red">*</span></span>
				<input type="text" id='sci_department_date1' required name="sci_department_date1" aria-label="" class="form-control" placeholder="Дата образования (обязательно)">
				<input type="text" id='sci_department_date2' name="sci_department_date2" aria-label="" class="form-control" placeholder="Дата окончания существования">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Родительское подразделение (Основано на)</span>
				<select id="sci_department_owner" name="sci_department_owner[]" class="form-select" multiple aria-label="">
				</select>
			</div>
			<div class="input-group">
				<button class="btn btn-primary" id='sci_department_add_btn'>Добавить</button>
			</div>
		</form>
		<table class="" id="tbl_sci_department">

		</table>
	</div>
	<div id="tabs-7">
		<form class="row" method="post" id="collection">
			<input id="id_collection" name="id_collection" type="hidden">
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">Название колекции<span style="color: red">*</span></span>
				<input type="text" id='collection_name' name='collection_name' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">История формирования коллекции</span>
				<textarea id="collection_Desc" name="collection_Desc" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="collection_Desc"></span>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">Ссылка на коллекцию</span>
				<input id="collection_url" name="collection_url" class="form-control" aria-label="">
				<span class="input-group-text" id="collection_url"></span>
			</div>
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap" id="">Структурное подразделение</span>
				<select id="collection_sci_department" name="collection_sci_department[]" class="form-select" multiple aria-label=""></select>
			</div>
			<div class="input-group">
				<button class="btn btn-primary" id='collection_add_btn'>Добавить</button>
			</div>
		</form>

		<div id="div_tbl_collection">

		</div>

	</div>
	<div id="tabs-8">
		<form class="row" method="post" id="collectionItem">
			<input id="collectionItemId" name="collectionItemId" type="hidden">
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Раздел колекции<span style="color: red">*</span></span>

				<select type="text" id='collectionItemColl' name='collectionItemColl' required aria-label="" class="form-select" >

				</select>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Предмет (название)<span style="color: red">*</span></span>
				<input type="text" id='collectionItemName' name='collectionItemName' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<input id="id_file" name="id_file" type="hidden">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">Цифровое изображение (фотография)<span style="color: red">*</span></span>
				<input id="collectionItemFile" name="collectionItemFile" required type="file" class="form-control" placeholder="" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Аннотация (описание)</span>
				<textarea id="collectionItemDesc" name="collectionItemDesc" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="collectionItemDesc_COUNT"></span>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">Место нахождения (памятник), Географический регион</span>
				<textarea id="collectionItemPlace" name="collectionItemPlace" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="collectionItemPlace_COUNT"></span>
			</div>

			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Авторство</span>
				<select id="collectionItem_pers" name="collectionItem_pers[]" class="form-select" multiple aria-label=""></select>
				<input style="width: 30%" type="text" id='collectionItem_pers_add' aria-label="" class="form-control" placeholder="">
			</div>

			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Время создания</span>
				<input type="text" id='collectionItemTime' name='collectionItemTime' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Материал, техника</span>
				<input type="text" id='collectionItemMaterial' name='collectionItemMaterial' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Размер</span>
				<input type="text" id='collectionItemSize' name='collectionItemSize' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success text-wrap">Учетный номер (инвентарный и / или по книге поступления)</span>
				<input type="text" id='collectionItemNom' name='collectionItemNom' required aria-label="" class="form-control" placeholder="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Направление науки</span>
				<select id="collectionItem_tem" name="collectionItem_tem[]" class="form-select" multiple aria-label="">

				</select>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="collectionItem_tag" name="collectionItem_tag[]" class="form-select" multiple aria-label="">
				</select>
				<input style="width: 10%" type="text" id='collectionItem_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary" id='collectionItem_tag_add_btn'>+</button>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Координаты</span>
				<input type="text" id='collectionItem_latitude' name='latitude' aria-label="" class="form-control" placeholder="Ю.Ш.">
				<input type="text" id='collectionItem_longitude' name='longitude' aria-label="" class="form-control" placeholder="В.Д.">
			</div>
			<div class="input-group">
				<button class="btn btn-primary" id='collectionItem_add_btn'>Добавить</button>
			</div>

		</form>

		<div id="div_tbl_collectionItem">

		</div>
	</div>
</div>
</body>
