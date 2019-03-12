<?php
include_once "connection.php";
?>
<?php

$search_class = mysqli_query($connection,"
SELECT class
	FROM student
	GROUP BY (class)
");
echo'<h3>Просмотр книг выданных определённому ученику</h3>';
if (mysqli_num_rows($search_class)>0){
	echo '<form action=look_given_book.php method="post">';
		echo'<select name="student_class">';
			while ($row = mysqli_fetch_assoc($search_class)){
			$id=	$row['class'];
			echo	$row['class'];
		echo '<option value="'.$id.'">'.$id.'</option>';
	}
echo '<input type="submit" value="выбрать класс">';
echo "</select>";
echo'</form>';								
}
if (isset($_POST['student_class'])){
	$student_class = $_POST['student_class'];
	$select_class = mysqli_query($connection,"
			SELECT *
				FROM `student`
				WHERE `class` = '$student_class'
		");
echo'<h3>Выбери ученика</h3>';	
if (mysqli_num_rows($search_class)>0){
	echo '<form action=look_given_book.php method="post">';
		echo'<select name="student_id">';
			while ($row = mysqli_fetch_assoc($select_class)){
				$id=		$row['id'];
				$surname=	$row['surname'];
				$name=		$row['name'];
				$phone=		$row['phone'];
		echo '<option value="'.$id.'">'.$surname.' '.$name.'</option>';
	}
echo '<input type="submit" value="выбрать студента">';
echo "</select>";
echo'</form>';
}
}

if(isset($_POST['student_id'])){
	$student_id = $_POST['student_id'];
	$select_student_id = mysqli_query($connection,"
			SELECT book_ID
				FROM `rents_book`
				WHERE `student_ID` = '$student_id' and date_return IS NULL
		");

echo '<p><table border="1">';
	while( $row = mysqli_fetch_assoc($select_student_id) ){
		$id = $row['book_ID'];
		$book_title = mysqli_query($connection,"
		SELECT id, title
			FROM `book`
			WHERE `id` = '$id'
		");
		if (mysqli_num_rows($book_title)>0){
			echo '<tr><td colspan="2">Должен книги</td></tr>';
				while( $row = mysqli_fetch_assoc($book_title) ){
					echo '<tr><td>'.$row['id'].'</td><td>'.$row['title'].'</td></tr>';
				}
		}
		}
	}
echo '</table></p>';
?>
<?php
mysqli_close($connection);
?>