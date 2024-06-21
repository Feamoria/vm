<?php
    ini_set('post_max_size', '101M');
    ini_set('upload_max_filesize', '100M');
    if (isset($_FILES['model'])) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $uploaddir = __DIR__ . '/models';
        $uploadfile = $uploaddir . '/model.ply';// basename($_FILES['userfile']['name']);
        echo '<pre>';
        if (file_exists($uploadfile)) {
            unlink($uploadfile);
        }
        if (move_uploaded_file($_FILES['model']['tmp_name'], $uploadfile)) {
            header('Location: model.html');
            exit;
        } else {
            echo "Ошибка!\n";
        }

        echo 'Некоторая отладочная информация:';
        print_r($_FILES);

        print "</pre>";
    }
?>
<form enctype="multipart/form-data" method="POST">
	<h2 style="color: red">Ограничение размера файла до 100мб</h2>
	<input type="file" name="model" accept=".ply">
	<button type="submit">Отправить</button>
</form>