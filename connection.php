<!DOCTYPE html>
<html lang=ru>
<html>
	<head>
	<meta charset="utf-8">
	<title>Интерфейс</title>
	</head>
	<body>
<?php
$connection = mysqli_connect('192.168.0.158','root','','library');

if($connection == false){
	echo 'Не удалось подключится к базе данных! <br>';
	echo mysqli_connect_error();
	exit();
}
?>
	</body>
</html>