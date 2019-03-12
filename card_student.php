<?php
include_once "connection.php";
/* Добавление/редактирование карточки ученика */
?>

<h1>Добавить ученика</h1>
<form action="card_student.php" method="post">
<table>
	<tr><td>Фамилия:</td><td> <input required type="text" name="add_surname"></td></tr>
	<tr><td>Имя:</td><td> <input required type="text" name="add_name"></td></tr>
	<tr><td>Класс:</td><td> <input required type="text" name="add_class" minlength="2" maxlength="3"></td></tr>
	<tr><td>Телефон:</td><td> <input required type="text" name="add_phone" minlength="11" maxlength="11"></td></tr>
	<tr colspan="2"><td><input type="submit" name="add_student" value="Добавить"></td></tr>
</table>
</form>

<h1>Изменить данные карточки ученика</h1>
<form action="card_student.php" method="post">
<table>
	<tr><td>Номер:</td><td> <input required type="number" name="edit_id"></td></tr>
	<tr><td>Фамилия:</td><td> <input required type="text" name="edit_surname"></td></tr>
	<tr><td>Имя:</td><td> <input required type="text" name="edit_name"></td></tr>
	<tr><td>Класс:</td><td> <input required type="text" name="edit_class" minlength="2" maxlength="3"></td></tr>
	<tr><td>Телефон:</td><td> <input required type="text" name="edit_phone" minlength="11" maxlength="11"></td></tr>
	<tr colspan="2"><td><input type="submit" name="edit_student" value="Изменить"></td></tr>
</table>

<?php 
if 	(isset ($_POST['add_student'])){
if 	(!isset ($_POST['add_surname'])  ||
	!isset ($_POST['add_name'])  ||
	!isset ($_POST['add_class'])  ||
	!isset ($_POST['add_phone']) ) {
       die ("Не все данные введены.<br>
                Пожалуйста, вернитесь назад и закончите ввод");
	}
		
$sql_array_student = mysqli_query($connection,"
INSERT INTO student (surname, name, class, phone)
VALUES
('{$_POST['add_surname']}','{$_POST['add_name']}','{$_POST['add_class']}','{$_POST['add_phone']}');
");
		$sql_array_show_student = mysqli_query($connection,"
		SELECT id,surname,name,class,phone 
		FROM student
		");
			echo '<table>';
				echo '<tr><th>Номер</td><td>Фамилия</td><td>Имя</td><td>класс</td><td>Номер телефона</td></tr>';
			while($row = ( mysqli_fetch_assoc($sql_array_show_student) )){
				echo '<tr><td>'.$row['id'].'</td><td>'.$row['surname'].'</td><td>'.$row['name'].'</td><td>'.$row['class'].'</td><td>'.$row['phone'].'</td></tr>';
			}
			echo '</table>';
}
			
?>
<?php
/* 
	Редактирование карточки студента
 */
echo'<table>';
if 	(isset ($_POST['edit_student'])){
if 	(!isset ($_POST['edit_id'])  ||
	!isset ($_POST['edit_surname'])  ||
	!isset ($_POST['edit_name'])  ||
	!isset ($_POST['edit_class'])  ||
	!isset ($_POST['edit_phone']) ) {
       die ("Не все данные введены.<br>
                Пожалуйста, вернитесь назад и закончите ввод");
	}
	
	
	if(isset ($_POST['edit_id'])  &&
	isset ($_POST['edit_surname'])  &&
	isset ($_POST['edit_name'])  &&
	isset ($_POST['edit_class'])  &&
	isset ($_POST['edit_phone'])) {
		
	$edit_id= 		$_POST['edit_id'];
	$edit_surname= 	$_POST['edit_surname'];
	$edit_name=	 	$_POST['edit_name'];
	$edit_class= 	$_POST['edit_class'];
	$edit_phone= 	$_POST['edit_phone'];
		
$sql_edit_student = mysqli_query($connection,"
	UPDATE `student`
	SET surname = '$edit_surname',
		name = '$edit_name',
		class = '$edit_class',
		phone = '$edit_phone'
	WHERE id = '$edit_id'

")or die("Ошибка ". mysqli_error($connection));
	}
		if($sql_edit_student){
		echo "<span style='color:blue;'>данные обновлены</span>";
	}
}
echo'<table>';



?>
<?php
mysqli_close($connection);
?>
