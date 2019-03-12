
<?php
include_once "connection.php";
echo'<a href="index.php"> Назад </a><hr>';
 function bord (){
	echo'<table>';
		echo '<tr><td>'.'Номер книги'.'</td><td>'.'Название'.'</td><td>'.' шкаф '.'</td><td>'.' полка '.'</td><td>'.'Состояние'.'</td><tr>';
}
function table ($mq){
if ( mysqli_num_rows($mq)>0 ){
	while( $row = mysqli_fetch_assoc($mq) ){
		if ($row['COUNT(b.id)']<=1){
			continue;
		}
	echo '<tr><td>'.$row['id'].'</td><td>'.$row['title'].'</td><td>'.$row['num_locker'].'</td><td>'.$row['num_shelf'].'</td><td>'.'в библиотеке'.'</td><tr>';
}
}else{echo'Записей не обнаружено';}
}
?>
<?php
function authors($select_author, $select_author_id){
	if (mysqli_num_rows($select_author)>0){
		echo 'Автор: </td><td><select name="'.$select_author_id.'">';
			while ($row = mysqli_fetch_assoc($select_author)){
				$id = $row['id'];
				$title = $row['surname'].' '.$row['name'];
			echo'<option value="'.$id.'">'.$title.'</option>';
			}
	echo "</select>";
}
}
?>
<h3>Редактирование книги</h3>
<table>
<form action="edit.php" method="POST">
	<tr><td>
	<tr><td>Номер Книги</td><td><input  type="number" name="id" maxlength="30" size="30"></td></tr>
	<tr><td>Название</td><td><input type="text" name="title" maxlength="30" size="30"></td></tr>
<tr><td>
<?php
$select_author = mysqli_query($connection,"
	SELECT id, surname, name
	FROM author
		")or die('Ошибка: '.mysqli_error($connection));

$select_author_id_1 = 'select_author_id_1';
	authors($select_author, $select_author_id_1);

$select_author_2 = mysqli_query($connection,"
	SELECT id, surname, name
	FROM author
		")or die('Ошибка: '.mysqli_error($connection));
$select_author_id_2 = 'select_author_id_2';
	authors($select_author_2, $select_author_id_2);
?></td></tr>

<tr><td>Дата публикации</td><td><input  type="date" name="publication"></td></tr>
<tr><td>Номер шкафа</td><td><input  type="number" name="num_locker" max="20" min="0"></td></tr>
<tr><td>Номер полки</td><td><input type="number" name="num_shelf" max="15" min="0"></td></tr>
<tr><td>
<?php
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
<tr><td><input type="submit" value="Редактировать"></td></tr>
</form>
</table>
<?php
if(isset($_POST['id'])) {
echo'<table border="1">';
	$id = $_POST['id'];

 // отвечает за id автора на который надо сменить
	
	$num_locker = $_POST['num_locker'];
	$num_shelf = $_POST['num_shelf'];

	
	if (isset($_POST['select_author_id_1']) &&
	isset($_POST['select_author_id_2'])  !="") {
		$author_1 = $_POST['select_author_id_1']; // отвечает за id автора который надо сменить
		$author_2 = $_POST['select_author_id_2'];
		$update_books_authors = mysqli_query($connection,"
			UPDATE `books_authors`
				SET author_ID = '$author_2'
			WHERE book_ID = '$id' AND author_ID = '$author_1'
		") or die("Ошибка ". mysqli_error($connection));
		echo'<tr><td>Автор был изменён</td></tr>';
	}
		
	if (($_POST['title']) !="") {
			$title =$_POST['title'];
		$update_title =  mysqli_query($connection,"
			UPDATE `book`
				SET title = '$title'
			WHERE id = '$id'
		") or die("Ошибка ". mysqli_error($connection));
		echo'<tr><td>Название книги было изменено</td></tr>';		
	}
	
	if (($_POST['publication']) !="") {
		$publication = $_POST['publication'];
		$update_publication = mysqli_query($connection,"
			UPDATE `book`
				SET publication = '$publication'
			WHERE id = '$id'
		") or die("Ошибка ". mysqli_error($connection));
		echo'<tr><td>Дата публикации книги было изменено<br>';
	}
	
	if (isset($_POST['num_locker']) !="") {
		$update_num_locker = mysqli_query($connection,"
			UPDATE `locker`
				SET num_locker = '$num_locker'
			WHERE book_ID = '$id'
		") or die("Ошибка ". mysqli_error($connection));
		echo'<tr><td>Номер шкафа книги был изменён</td></tr>';
	}
	
	if (isset($_POST['num_shelf']) !="") {
		$update_num_shelf = mysqli_query($connection,"
			UPDATE `locker`
				SET num_shelf = '$num_shelf'
			WHERE book_ID = '$id'
		") or die("Ошибка ". mysqli_error($connection));
		echo'<tr><td>Номер полки книги был изменён</td></tr>';
	}
	
	if (isset($_POST['select_genre']) !=""){
			$select_genre = $_POST['select_genre'];
		$update_select_genre =  mysqli_query($connection,"
			UPDATE `categories`
				SET genre_ID = '$select_genre'
			WHERE book_ID = '$id'
		") or die("Ошибка ". mysqli_error($connection));
		echo'<tr><td>Жанр для книги был изменён</td></tr>';
	}
echo'</table>';
}

?>
<h3>Поиск книги по имени</h3>
<?php
$search_title_book = mysqli_query($connection,"
	SELECT id, title
		FROM book
		GROUP BY title
");
if( mysqli_num_rows($search_title_book)>0){
	echo'<form action="edit.php" method="post">';
		echo 'По имени:</td><td><select name="search_title_book">';
			while ( $row = mysqli_fetch_assoc($search_title_book) ){
				$id = $row['id'];
				$title = $row['title'];
				echo '<option value="'.$title.'">'.$title.'</option>';
			}
			echo '<input type="submit" value="Отправить">';
		echo "</select>";
	echo'</form>';
}
?></td></tr>

<?php
if (isset($_POST['search_title_book'])){
	bord ();
	$title = $_POST['search_title_book'];
	
	$search_title_book_array = mysqli_query($connection,"
        SELECT COUNT(b.id), b.title, l.id, l.num_locker, l.num_shelf
		FROM `book` as b
			INNER JOIN
            locker as l 
				ON b.title = '$title' AND l.book_id = b.id
                INNER JOIN
		             `rents_book` as rb
                    	ON b.id != rb.book_id and rb.date_return IS NULL
							GROUP BY(b.id)
	");
table($search_title_book_array);
}
echo'</table>';
?>
<?php
mysqli_close($connection);
?>
</body>
</html>