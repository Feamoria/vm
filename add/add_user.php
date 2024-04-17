<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once '../php_class/connect.php';
    $db = (new BDconnect())->connect();
    session_start();
    if (isset($_SESSION['user'])) {
        if (isset($_POST['login'])) {
        	foreach ($_POST as $i=>$val){
                $_POST[$i]=mysqli_escape_string($db,$val);
			}
        	if ($_POST['pass'] == $_POST['pass2']){
        		/* проверка на дубль*/
				$SQL="SELECT * FROM user where login='{$_POST['login']}'";
                $result = mysqli_query($db, $SQL);
                if (!$result) {
                    $_SESSION['err']=$SQL . "|Couldn't execute query." . mysqli_error($db);
                }
				$num=mysqli_num_rows($result);
				if ($num >0) {
                    $_SESSION['err']='Такой пользователь уже существует';
					die();
				}
				/*добавление*/
				$pass=hash('sha512', $_POST['pass']);
        		$SQL="INSERT INTO user (role, FIO, login, pass,dep) value 
    				(2,'{$_POST['FIO']}','{$_POST['login']}','$pass','{$_POST['dep']}')";
                $result = mysqli_query($db, $SQL);
					if (!$result) {
                        $_SESSION['err']=$SQL . "|Couldn't execute query." . mysqli_error($db);
					}
            } else {
        		$_SESSION['err']='Пароли не совпадают';
			}
        }
    } else {
        http_response_code(403);
    }
?>
<head>
	<!-- bootstrap -->
	<link href='../css/bootstrap.min.css' rel='stylesheet'>
	<link href='../ico/font/bootstrap-icons.css' rel='stylesheet'>
	<script src='../js/bootstrap/bootstrap.bundle.min.js'></script>
</head>
<body>
<div class="container-fluid vh-100" style="margin-top:20px">
	<div class="" style="margin-top:20px">
		<div class="rounded d-flex justify-content-center">
			<div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
				<div class="text-center">
					<h3 class="text-primary">Создать аккаунт</h3>
				</div>
				<div class="p-4">
					<form method="post">
						<?php if(isset($_SESSION['err'])) {
                            echo "
						<div class='input-group mb-3'>
                                    <span class='input-group-text bg-warning'>{$_SESSION['err']}</span>
						</div>";
                            $_SESSION['err']=null;
                        }
                        ?>
						<div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
												class="bi bi-person-plus-fill text-white"></i></span>
							<input type="text" required name="FIO" class="form-control" placeholder="ФИО">
						</div>

						<div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
												class="bi bi-envelope text-white"></i></span>
							<input type="text" required name="login" class="form-control" placeholder="Логин">
						</div>
						<div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
												class="bi bi-envelope text-white"></i></span>
							<input type="text" required name="dep" class="form-control" placeholder="Подразделение">
						</div>
						<div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
												class="bi bi-key-fill text-white"></i></span>
							<input type="password" required name="pass" class="form-control" placeholder="Пароль">
						</div>
						<div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i
												class="bi bi-key-fill text-white"></i></span>
							<input type="password" required name="pass2"  class="form-control" placeholder="Пароль повтор">
						</div>
						<div class="d-grid col-12 mx-auto">
							<button class="btn btn-primary" type="submit"><span></span>Регистрировать</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>