<!DOCTYPE html>
<html lang='ru'>
<html>
	<head>
	<meta charset="utf-8">
	<link href="css/table.css" rel="stylesheet">
	<title>
	Меню</title>
	
<?php

	$array = array(	
					"Добавить книгу"=>"create_book.php",
					"Редактирование книг"=>"edit.php",
					"Движение книг"=>"given_return_book.php",
					"Карта ученика"=>"card_student.php",
					"Выданные книги"=>"look_given_book.php",
					"Поиск"=>"search.php",
					"Информация"=>"info.php",
					// ""=>""
					);
	function show_url($arr){
		echo'<table border="1">';
			echo'<tr><td>Интерфейс библиотекаря</td></tr>';
		foreach ($arr as $index=>$value){
			
			echo"<tr><td><a href='$value'>".$index."</a></td></tr> ";
			
		}
		echo'</table>';
	}
	show_url($array);
		
?>
	</body>
	
</html>