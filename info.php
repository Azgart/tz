<?php
include_once "connection.php";
?>
<form action="info.php" method="POST">
<p><input type="submit" name="overall_info" value="Общая информация"></p>
</form>
<?php
/* справочная 
	информация 
	по 
	книгам 
*/
/* 
	Сколько книг в бибилиотеке

*/

$sql_num_book = mysqli_query($connection,"
	SELECT COUNT(id)
	FROM book
") or die ('Ошибка: '. mysqli_error($connection));


/* 
	Сколько 
	книг 
	определённого 
	жанра
 */
$sql_num_hand = mysqli_query($connection,"
	SELECT COUNT(book_id)
	FROM rents_book
	WHERE date_return IS NULL
") or die ('Ошибка: '. mysqli_error($connection));
/* 
	
*/
$sql_num_book_genre = mysqli_query($connection,"
	SELECT COUNT(g.title), g.title
	FROM genre as g
		INNER JOIN
        categories as c
		ON g.id = c.genre_ID
		
        INNER JOIN 
        book as b
        ON c.book_ID = b.id
		GROUP BY g.title;
");

 
 if (isset($_POST['overall_info'])){
	echo'<table>';
	
/* 
	Подсчёт
	книг
	по
	жанрам
 */	echo'<h3>Количество книг по жанрам</h3>';
	while ($row=mysqli_fetch_assoc($sql_num_book_genre)){
		echo '<tr><td>'.$row['title'].'</td><td>'.$row['COUNT(g.title)'].'</td><tr>';
	}echo'</table>';

	
	
/* 
	Колличество
	книг 
	в 
	библиотеке 
	ВСЕГО
	
 */ echo'<h3>Общая информация по книгам</h3>';
 echo'<table>';
	echo'<tr><td>&nbsp;</td><td>&nbsp;</td><tr>';
		$row_num_book = mysqli_fetch_assoc($sql_num_book);
		 $in_library = $row_num_book['COUNT(id)'];
			echo '<tr><td>Всего в библиотеке книг: </td><td>'.$in_library.'</td><tr>';
			
/* 			
	Общее 
	количество 
	книг 
	на 
	руках
 */
		$row_num_hand = mysqli_fetch_assoc($sql_num_hand);
		$in_hand = $row_num_hand['COUNT(book_id)'];
		echo '<tr><td>Всего на руках: </td><td>'.$in_hand.'</td><tr>';
		
		
/* 	
	В 
	наличии 
	библиотеки
*/
	$result = $in_library - $in_hand;
	echo '<tr><td>В наличии в библиотеке </td><td>'.$result.'</td><tr>';
	echo'</table>';
	

 
  }
?>
<?php
mysqli_close($connection);
?>