<!DOCTYPE html>
<html lang="ru">
<html>
	<head> <meta charset="utf-8">
		<title>Интерфейс</title>
	</head>
	<body>
<?php
include_once "connection.php";
echo'<a href="index.php"> Назад </a><hr>';
?>

<form action="create_book.php" method="post">
	<table>
	<tr><td>
	<?php
		
$select_author = mysqli_query($connection,"
SELECT id, surname, name
FROM author
")or die('Ошибка: '.mysqli_error($connection));
if (mysqli_num_rows($select_author)>0){
	echo 'Автор: </td><td><select name="select_author">';
		while ($row = mysqli_fetch_assoc($select_author)){
			$id = $row['id'];
			$title = $row['surname'].$row['name'];
			echo'<option value="'.$id.'">'.$title.'</option>';
		}
echo "</select>";
}
?></td></tr>
<tr><td>Название</td><td><input required type="text" name="title" maxlength="30" size="30"></td></tr>
<tr><td>Дата публикации</td><td><input required type="date" name="publication"></td></tr>
<tr><td>Номер шкафа</td><td><input required type="number" name="num_locker" max="20" min="0"></td></tr>
<tr><td>Номер полки</td><td><input required type="number" name="num_shelf" max="15" min="0"></td></tr>
<tr><td><?php
$select_genre = mysqli_query($connection,"
	SELECT id, title 
	FROM genre
")or die('Ошибка: '.mysqli_error($connection));

if (mysqli_num_rows($select_genre)>0){
	echo 'Жанр: </td><td><select name="select_genre">';
		while ($row = mysqli_fetch_assoc($select_genre)){
			$id = $row['id'];
			$title = $row['title'];
		echo'<option value="'.$id.'">'.$title.'</option>';
	}
	echo "</select>";
}
?></td></tr>
<tr><td colspan=2><input type="submit" value="Ввод"></td></tr>
</table>
</form>

<?php
if (!isset($_POST['select_author']) ||
	!isset($_POST['title']) ||
    !isset($_POST['publication']) ||
	!isset($_POST['num_locker']) ||
    !isset($_POST['num_shelf']) || 
	!isset($_POST['select_genre'])){
        die ("Не все данные введены.<br>
                Пожалуйста, вернитесь назад и закончите ввод");
}



/* Создаём массив */
$sql_array_book = mysqli_query($connection,"
	INSERT INTO book (title,publication)
		VALUES
		('{$_POST['title']}','{$_POST['publication']}');
	") or die('Ошибка: '.mysqli_error($connection));

$select_max_id_book = mysqli_query($connection,"
	SELECT MAX(Id)
		FROM `book`
	") or die('Ошибка: '.mysqli_error($connection));
while ($row = mysqli_fetch_assoc($select_max_id_book)){
	$id_book = $row['MAX(Id)'];
		echo $id_book;
}

$sql_array_locker = mysqli_query($connection,"
	INSERT INTO locker (num_locker,num_shelf,book_ID)
		VALUES
		('{$_POST['num_locker']}','{$_POST['num_shelf']}','$id_book');
	") or die('Ошибка: '.mysqli_error($connection));

$sql_array_categories = mysqli_query($connection,"
	INSERT INTO categories (genre_ID,book_ID)
		VALUES
		('{$_POST['select_genre']}','$id_book');
	") or die('Ошибка: '.mysqli_error($connection));


$sql_array_books_authors = mysqli_query($connection,"
	INSERT INTO books_authors (book_ID,author_ID)
		VALUES
		('$id_book','{$_POST['select_author']}');
	") or die('Ошибка: '.mysqli_error($connection));

?>

<?php

mysqli_close($connection);
?>
	</body>
</html>