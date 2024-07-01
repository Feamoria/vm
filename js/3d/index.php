<?php
    ini_set('post_max_size', '101M');
    ini_set('upload_max_filesize', '100M');

    function getExtension($filename) {
        return substr(strrchr($filename, '.'), 1);
    }

    if (isset($_FILES['model'])) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $uploaddir = __DIR__ . '/models';
        $uploadfile = $uploaddir . '/model.';
        echo '<pre>';
        if (file_exists($uploadfile)) {
            unlink($uploadfile);
        }
        $extension=getExtension($_FILES['model']['name']);
        print_r($uploadfile.$extension."\n");
        if (move_uploaded_file($_FILES['model']['tmp_name'], $uploadfile.$extension)) {
            header("Location: model.php?$extension");
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
	<input type="file" name="model" accept=".ply,.nxz">
	<button type="submit">Отправить</button>
</form>