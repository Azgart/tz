
<?php
include_once "connection.php";
/* выдача/возврат книги ученику */

?>
<h3>
Выдача книг
</h3>
<?php
/* 
	Передача 
	книги 
	ученику
 */
$today = date("Y.m.d");
$sql_given_book = mysqli_query($connection,"
	SELECT id, title, publication
	FROM book	
	");
if (mysqli_num_rows($sql_given_book)>0){
	echo '<form action=given_return_book.php method="post">';
		echo '<table>';
			echo '<tr><td>Номер:</td><td> <input required type="number" name="given_id"></td></tr>';
									// echo '<tr><td>Фамилия:</td><td> <input type="text" name="given_surname"></td></tr>';
									// echo '<tr><td>Имя:</td><td> <input type="text" name="given_name"></td></tr>';
									// echo '<tr><td>Класс:</td><td> <input  type="text" name="given_class" minlength="2" maxlength="3"></td></tr>';
									// echo '<tr><td>Телефон:</td><td> <input  type="text" name="given_phone" minlength="11" maxlength="11"></td></tr>';
									// echo '<tr><td>Книга:</td><td> <input  type="text" name="given_book_id"></td></tr>';
								echo '</table>';
								echo '<select name="given_book_id">';
									while ($row = mysqli_fetch_assoc($sql_given_book)){
										$id = $row['id'];
										$title = $row['title'];
										echo '<option value="'.$id.'">'.$title.'</option>';
									}
									echo '<p><input type="submit" value="Отправить"></p>';
								}
								echo "</select>";
								echo'</form>';
								
if (isset($_POST['given_id'])&&
	isset($_POST['given_book_id'])){
		$given_id = $_POST['given_id'];
		$given_book_id = $_POST['given_book_id'];
		
		
		$insert_given_book = mysqli_query($connection,"
			INSERT INTO rents_book (student_ID, book_ID, date_pick_up, sign_ID)
			VALUES
			('{$_POST['given_id']}','{$_POST['given_book_id']}','$today','2');
		")or die("Ошибка ". mysqli_error($connection));
	}
		if($insert_given_book){
		echo "<span style='color:blue;'>данные добавлены</span>";
	}
							
?>
<h3>Возвращение книги</h3>
<?php
/* Возвращение */
/* Возьми все состояния таблицы sign */
	$sql_return_search = mysqli_query($connection,"
	SELECT *
	FROM sign
	") or die("Ошибка ".mysqli_error($connection));
	
	/* Форма состояния */
echo '<form action=given_return_book.php method="post">';
	echo '<table>';
		echo '<tr><td>Номер:</td><td> <input required type="number" name="return_id_book"></td></tr>';
	echo '</table>';
	echo '<select name="return_book">';
		while( $row=mysqli_fetch_assoc($sql_return_search) ){
			$id = $row['id'];
			$state = $row['state'];
			echo '<option value="'.$id.'">'.$state.'</option>';
		}
		echo '<tr><td><input type="submit" value="Отправить"></td></tr>';
	echo '</select>';
echo  '</form>';

/* 
	Изменяем 
	таблицу 
	rents_book
	возврат
	книги 
	учеником
*/
	
if (isset($_POST['return_id_book'])&&(isset($_POST['return_book']))){
	$return_id_book = $_POST['return_id_book'];
	$return_book = $_POST['return_book'];
	$sql_return_book_select = mysqli_query($connection,"
		UPDATE `rents_book`
		SET `sign_ID` = '$return_book',
			`date_return` = '$today'
		WHERE `book_ID` = '$return_id_book' AND `date_return` IS NULL
		
	") or die("Ошибка ". mysqli_error($connection));
	if (mysqli_num_rows($sql_return_book_select)>0){
		echo "<span style='color:blue;'>данные обновлены</span>";
	}else{echo'Такую книгу ни кто не должен';}
}
?>
<?php

mysqli_close($connection);
?>