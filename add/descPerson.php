<?php
    // TODO пРОВЕРКА НА ДОСТУП
    session_start([
                      'cookie_lifetime' => 86400,
                  ]);
    //if ()
    if (!isset($_SESSION['user']['site'])) {
        header("Location: /vm/add/index.php");
        die();
    }
    if ($_SESSION['user']['role'] =='3') {
        http_response_code(403);
        die('Forbidden');
    }

?>
<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'/>
    <title>Модерация:Персоналии</title>
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

    <!--tinymce -->
    <script src="../js/tinymce/tinymce.min.js"></script>

    <!-- MY -->
    <script src="js/descPers.js?<?php
        echo time(); ?>"></script>

</head>
<body>
<script>
    var curent=<?php
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        require_once '../php_class/connect.php';
        $db = (new BDconnect())->connect();

        $SQL ="SELECT ID from person where moderated=0 order by id limit 1";
        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            echo $row['ID'];
        }
        ?>;
</script>
<div class="">
    <div class="row">
        <div class="input-group">
            <label for="idPerson" class="input-group-text text-bg-success">ID персоны</label>
            <input id="idPerson" readonly class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="input-group">
            <label for="create_user" class="input-group-text text-bg-success">Создатель</label>
            <input id="create_user" readonly class="form-control">
            <label for="create_dep" class="input-group-text text-bg-success">Подразделение</label>
            <input id="create_dep" readonly class="form-control">
            <label for="create_date" class="input-group-text text-bg-success">Дата создания</label>
            <input id="create_date" readonly class="form-control">
        </div>
    </div>
    <div id="isModerated" class="row" style="display: none">
        <div class="input-group">
            <label for="moderated_user" class="input-group-text text-bg-success">Редактор</label>
            <input id="moderated_user" readonly class="form-control">
            <label for="moderated_dep" class="input-group-text text-bg-success">Подразделение</label>
            <input id="moderated_dep" readonly class="form-control">
            <label for="moderated_date" class="input-group-text text-bg-success">Дата редактирования</label>
            <input id="moderated_date" readonly class="form-control">
        </div>
    </div>
    <div class="row">
        <!-- ФИО -->
        <div class="input-group">
            <label for="FIO" class="input-group-text text-bg-success">ФИО Персоналии</label>
            <input id="FIO" readonly class="form-control">
        </div>
    </div>
    <div class="row">
        <!-- ФИО -->
        <div class="input-group">
            <label for="dol" class="input-group-text text-bg-success">Должность, звание</label>
            <input id="dol" name="dol" class="form-control">
        </div>
    </div>
    <div class="row">
        <!-- Биографические данные -->
        <div class="col-12">
            <label for="comment" class="input-group-text text-bg-success">Биографические данные</label>
            <textarea id="comment" name="comment"></textarea>
        </div>
    </div>
    <div class="row">
        <!-- Биографические данные -->
        <div class="col-12">
            <label for="publications" class="input-group-text text-bg-success">Основные публикации</label>
            <textarea id="publications" name="publications"></textarea>
        </div>
    </div>
    <div class="row">
        <!-- Награды, звания -->
        <div class="col-12">
            <label for="awards" class="input-group-text text-bg-success">Награды, звания</label>
            <textarea id="awards" name="awards"></textarea>
        </div>
    </div>





    <div class="row">
        <!-- кнопки -->
        <div class="col-3 position-relative"> <button id="btnBack" class="btn btn-sm btn-primary position-absolute top-50 start-50 ">Назад</button> </div>
        <div class="col-3 position-relative"> <button id="btnNext" class="btn btn-sm btn-primary position-absolute top-50 start-50 ">Вперёд</button></div>
        <div class="col-3 position-relative">
			<button id="btnPublic" class="btn btn-sm btn-success position-absolute top-50 start-25">Опубликовать</button>
			<button id="btnSave" class="btn btn-sm btn-success position-absolute top-50 start-50 ">Сохранить</button>
		</div>
        <div class="col-3 position-relative">
            <div class="row position-absolute top-50 start-40 ">
                <div class="col-6">
                    <input id="Step" class="form-control" aria-label="id события">
                </div>
                <div class="col-6">
                    <button id="btnStep" class="btn btn-sm btn-primary  ">Перейти</button>
                </div>
            </div>
        </div>
    </div>

</div>
</body>

