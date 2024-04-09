<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require_once 'php_class/connect.php';
    $db = (new BDconnect())->connect();
    $SQL="SELECT MAX(DateN), MIN(DateN) from event";
    $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
    $res=mysqli_fetch_all($query);
    $res=$res[0];
    $res[0]=explode('.',$res[0]);
    $res[1]=explode('.',$res[1]);
    $maxY=$res[0][0];
    $minY=$res[1][0];
?>
<!DOCTYPE html>
<head>

	<script src="js/jquery/jquery-3.7.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="ico/font/bootstrap-icons.css" rel="stylesheet">
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
	<script src="js/jquery/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="js/jquery/jquery-ui/jquery-ui.min.css"/>
	<script src="js/main.js?<?php
        echo time(); ?>"></script>
	<link href="css/main.css?<?php
        echo time(); ?>" rel="stylesheet">
	<title>История КОМИ НАУЧНОГО ЦЕНТРА</title>
</head>

<body>
<div class="container">
	<div class="page-header">
		<h1 id="timeline">История КОМИ НАУЧНОГО ЦЕНТРА</h1>
		<div class="container">
			<div class="row">
				<div class="col" style="margin-bottom: 20px;margin-top: 20px;">
					<div id='years'>
						<div id="custom-handle-min" year="<?=$minY?>" class="ui-slider-handle"></div>
						<div id="custom-handle-max" year="<?=$maxY?>" class="ui-slider-handle"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="btn-group" role="group">
					<?php
                        $SQL="SELECT id, Name from sci_theme";
                        $query = mysqli_query($db, $SQL) or die($SQL . "|Couldn't execute query." . mysqli_error($db));
                        $res=mysqli_fetch_all($query,MYSQLI_ASSOC);
                        //var_dump($res);
                        foreach ($res as $i => $val) {
                        	//var_dump($val);
                        	echo "<button id='' value='{$val['id']}' class='btn btn-primary b1'>{$val['Name']}</button>";

						}
					?>
				</div>
			</div>
		</div>
	</div>
	<ul class="timeline">
	</ul>
</div>
</body>