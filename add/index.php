<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once '../php_class/connect.php';
    //die(hash('sha512', '15142342'));
    session_start();/*Аутентификация*/
    if (isset($_GET['auth'])){
        $db = (new BDconnect())->connect();
        $SQL = "SELECT * FROM user WHERE login='" . mysqli_real_escape_string($db, $_POST['login']) . "' LIMIT 1";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));;
        $data = mysqli_fetch_assoc($query);
        # Сравниваем пароли

        if ($data['pass'] === hash('sha512', $_POST['password']))//Авторизация пройдена
        {
            $_SESSION['user']['id']=$data['id'];
            $_SESSION['user']['role']=$data['role'];
            $_SESSION['user']['FIO']=$data['FIO'];
            $_SESSION['user']['login']=$data['login'];
            $_SESSION['user']['pass']=$data['pass'];
            # Обновляем страницу с данными авторизации

        } else//Авторизация отклонена
        {
            $_SESSION['mess']='Вы ввели неверный логин/пароль';
        }
        header("Location: /vm/add/index.php");
    }
    else
    /*Аутентификация*/
    if (!isset($_SESSION['user'])) {
        $mess='';
        if (isset($_SESSION['mess'])) {
            $mess=$_SESSION['mess'];
            $_SESSION['mess']=null;
        }
        $html='<h1 style="color: red;" align="center">Не авторизовано</h1>
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
			    <P style="color: red">'.$mess.'</P>
				<input name="submit" value="Войти" type="submit">
			</div>
			</form></div>';
        die($html);
    }


?>
<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'/>
    <title>Добавление данных</title>
    <link rel="icon" type="image/png" href="favicon.ico" />
	<script src="../js/jquery/jquery-3.7.1.min.js"></script>
	<script src="../js/jquery/jquery.form.js"></script>
	<script src="../js/jquery/jquery-ajax-native.js"></script>
	<script src="../js/jquery/jquery-ui/jquery-ui.min.js"></script>
	<script src="../js/jquery/jquery-ui/jquery.ui.datepicker-ru.js"></script>
	<link href="../js/jquery/jquery-ui/jquery-ui.min.css" rel="stylesheet" >
	<link href="../js/jquery/jquery-ui/jquery-ui.structure.css" rel="stylesheet" >
	<link href="../js/jquery/jquery-ui/jquery-ui.theme.css" rel="stylesheet" >
	<!--multiselect  https://github.com/ehynds/jquery-ui-multiselect-widget-->
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.js'></script>
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.ru.js'></script>
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.filter.js'></script>
	<script type='text/javascript' src='../js/jquery/jquery-multiselect/jquery.multiselect.filter.ru.js'></script>
	<link rel='stylesheet' type='text/css' media='screen' href='../js/jquery/jquery-multiselect/jquery.multiselect.css'/>
	<link rel='stylesheet' type='text/css' media='screen' href='../js/jquery/jquery-multiselect/jquery.multiselect.filter.css'/>
	<!-- bootstrap -->
	<link href="../css/bootstrap.min.css" rel="stylesheet" >
	<link href="../ico/font/bootstrap-icons.css" rel="stylesheet" >
	<script src="../js/bootstrap/bootstrap.bundle.min.js"></script>

	<!-- Upload_master jQuery plugin -->
	<script src="../js/Upload_master/js/vendor/jquery.ui.widget.js"></script>
	<script src="../js/Upload_master/js/jquery.iframe-transport.js"></script>
	<script src="../js/Upload_master/js/jquery.fileupload.js"></script>

	<!-- MY -->
	<script src="js/main.js?<?php echo time();?>"></script>
</head>
<body>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">События</a></li>
		<li><a href="#tabs-2">Персоналии</a></li>
		<li><a href="#tabs-3">Файлы</a></li>
        <li><a href="#tabs-4">Ключевые слова</a></li>
        <li><a href="#tabs-5">Научная тематика</a></li>
	</ul>
	<div id="tabs-1">
		<form class="row" id="event">
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Название события</span>
				<input id="ev_Name" name="ev_Name" type="text" class="form-control" placeholder="Название" aria-label="Название">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Дата начала<span style="color: red">*</span></span>
				<input type="text" id='ev_Y_n' required name="ev_Y_n" aria-label="" class="form-control" placeholder="Год (обязательно)">
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
				<span style="width: 20%" class="input-group-text text-bg-success">Событие кратко<span style="color: red">*</span></span>
				<textarea id="ev_Desc_short" required name="ev_Desc_short" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="ev_Desc_short_COUNT"></span>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Событие полное</span>
				<textarea id="ev_Desc" name="ev_Desc" class="form-control" aria-label=""></textarea>
			</div>
			<!--Персоналии multiple -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Персоналии</span>
				<select id="ev_pers" name="ev_pers[]" class="form-select" multiple aria-label="">

				</select>
			</div>
			<!-- TODO Файлы -->
			<!--Структурное подразделение -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="ev_SP">Структурное подразделение</span>
				<select id="" name="" class="form-select" aria-label="">
					<option selected disabled>--</option>
					<option value="1">1е</option>
					<option value="2">2е</option>
					<option value="3">3е</option>
				</select>
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Научная тематика</span>
				<select id="ev_tem" name="ev_tem[]" class="form-select" multiple aria-label="">
					<option value="1">1я</option>
					<option value="2">2я</option>
					<option value="3">3я</option>
				</select>
			</div>
			<!--Ссылки на архивный документ -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ссылка на архивный документ</span>
				<input type="text" id='' name='ev_doc' aria-label="" class="form-control" placeholder="">
			</div>
			<!--Важность события -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Важность события<span style="color: red">*</span></span>
				<select id="" name="" required class="form-select" aria-label="">
					<option selected value="" disabled>--</option>
					<option value="1">1е</option>
					<option value="2">2е</option>
					<option value="3">3е</option>
					<option value="4">4е</option>
					<option value="5">5е</option>
				</select>
			</div>
			<!--Место (координаты) -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Координаты</span>
				<input type="text" id='' name='latitude' aria-label="" class="form-control" placeholder="Ю.Ш.">
				<input type="text" id='' name='longitude' aria-label="" class="form-control" placeholder="В.Д.">

			</div>
			<!--Ключевые слова -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="ev_tag" name="ev_tag[]" class="form-select" multiple aria-label="">
					<option value="1">1я</option>
					<option value="2">2я</option>
					<option value="3">3я</option>
				</select>
				<input style="width: 10%" type="text" id='ev_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary"  id='ev_tag_add_btn'>Добавить</button>
			</div>
<!--
			<div class="col-auto input-group mb-3">
				<span class="input-group-text" id="ev_"></span>
				<input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="ev_">
			</div>
-->
			<div class="input-group">
				<button class="btn btn-primary"  id='ev_btn_send'>Отправить</button>
			</div>
		</form>
	</div>
	<div id="tabs-2">
		<form class="row" method="post" id="pers">
			<div class="input-group">
                <span style="width: 20%" class="input-group-text text-bg-success">ФИО персоны<span style="color: red">*</span></span>
				<input id="pers_F" name="pers_F" required type="text" class="form-control" placeholder="Фамилия" aria-label="Фамилия">
				<input id="pers_I" name="pers_I" required type="text" class="form-control" placeholder="Имя" aria-label="Имя">
				<input id="pers_O" name="pers_O" required type="text" class="form-control" placeholder="Отчество" aria-label="Отчество">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Годы жизни</span>
				<input id="pers_date1" required name="pers_date1" type="text" class="form-control" placeholder="c (обязательное)" aria-label="">
				<input id="pers_date2"  name="pers_date2" type="text" class="form-control" placeholder="по" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Должность<span style="color: red">*</span></span>
				<input id="pers_dol" name="pers_dol" required type="text" class="form-control" placeholder="" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Аннотация</span>
				<textarea id="pers_Desc" name="pers_Desc" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="pers_Desc_short_COUNT"></span>
			</div>
			<!--Ключевые слова -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="pers_tag" name="pers_tag[]" class="form-select" multiple aria-label="">
					<option value="1">1я</option>
					<option value="2">2я</option>
					<option value="3">3я</option>
				</select>
				<input style="width: 10%" type="text" id='pers_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary"  id='pers_tag_add_btn'>+</button>
			</div>
			<!--Структурное подразделение -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Структурное подразделение</span>
				<select id="pers_SP" name="pers_SP" class="form-select" aria-label="">
					<option selected disabled>--</option>
					<option value="1">1е</option>
					<option value="2">2е</option>
					<option value="3">3е</option>
				</select>
			</div>
			<!--Научная тематика -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Научная тематика</span>
				<select id="pers_tem" name="pers_tem[]" class="form-select" multiple aria-label="">
					<option value="1">1я</option>
					<option value="2">2я</option>
					<option value="3">3я</option>
				</select>
			</div>
			<div class="input-group">
				<button class="btn btn-primary"  id='pers_btn_send'>Сохранить персоналию</button>
			</div>
		</form>
	</div>
	<div id="tabs-3">
		<form class="row" method="post" id="file">
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Файл<span style="color: red">*</span></span>
				<input id="file_F" name="file_F" required type="file" class="form-control" placeholder="" aria-label="">
				</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Дата</span>
				<input id="pers_date1" required name="file_date" type="text" class="form-control" placeholder="(обязательное) 1947.01.01 или 1947.02.01  если неизвестна точная дата или месяц" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Название файла<span style="color: red">*</span></span>
				<input id="pers_dol" name="file_name" required type="text" class="form-control" placeholder="" aria-label="">
			</div>
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Аннотация</span>
				<textarea id="pers_Desc" name="file_Desc" class="form-control" aria-label=""></textarea>
				<span class="input-group-text" id="file_Desc_short_COUNT"></span>
			</div>
			<!--Пероналии TODO -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Пероналии</span>

			</div>
			<!--Структурное подразделение -->
			<div class="col-auto input-group">
				<span style="width: 20%" class="input-group-text text-bg-success" id="">Структурное подразделение</span>
				<select id="file_SP" name="file_SP" class="form-select" aria-label="">
					<option selected disabled>--</option>
					<option value="1">1е</option>
					<option value="2">2е</option>
					<option value="3">3е</option>
				</select>
			</div>
			<!--Научная тематика -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Научная тематика</span>
				<select id="file_tem" name="file_tem[]" class="form-select" multiple aria-label="">

				</select>
			</div>
			<!--Ссылки на архивный документ -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ссылки на архивный документ</span>
				<input style="width: 10%" type="text" id='file_doc' aria-label="" class="form-control" placeholder="">
			</div>
			<!--Ключевые слова -->
			<div class="input-group">
				<span style="width: 20%" class="input-group-text text-bg-success">Ключевые слова</span>
				<select id="file_tag" name="file_tag[]" class="form-select" multiple aria-label="">

				</select>
				<input style="width: 10%" type="text" id='file_tag_add' aria-label="" class="form-control" placeholder="">
				<button class="btn btn-primary"  id='file_tag_add_btn'>+</button>
			</div>
			<div class="input-group">
				<button class="btn btn-primary"  id='file_btn_send'>Сохранить файл</button>
			</div>
		</form>
	</div>
    <div id="tabs-4">
        <form class="row" method="post" id="tag">
            <div class="input-group">
                <span style="width: 30%" class="input-group-text text-bg-success">Ключевое слово</span>
                <input style="width: 30%" type="text" id='tag_add' aria-label="" class="form-control" placeholder="">
                <button class="btn btn-primary"  id='tag_add_btn'>Добавить</button>
            </div>
        </form>
        <table class="" id="tbl_tag">

        </table>
    </div>
    <div id="tabs-5">
        <form class="row" method="post" id="sci_field">
            <div class="input-group">
                <span style="width: 30%" class="input-group-text text-bg-success">Научная тематика</span>
                <input style="width: 30%" type="text" id='sci_field_add' aria-label="" class="form-control" placeholder="">
                <button class="btn btn-primary"  id='sci_field_add_btn'>Добавить</button>
            </div>
        </form>
        <table class="" id="tbl_sci_field">

        </table>
    </div>
</div>
</body>
