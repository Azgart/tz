<?php
include_once "connection.php";
?>
<?php
function bord (){
	echo'<table border ="1">';
echo '<tr><td>Название</td><td>'.' место '.'</td><td>'.' шкаф '.'</td><td>'.' полка '.'</td><td>'.'Состояние'.'</td><tr>';
}
function table ($mq){
if ( mysqli_num_rows($mq)>0 ){
	while( $row = mysqli_fetch_assoc($mq) ){
		if ($row['COUNT(b.id)']<=1){
			continue;
		}
	echo '<tr><td>'.$row['title'].'</td><td>'.$row['id'].'</td><td>'.$row['num_locker'].'</td><td>'.$row['num_shelf'].'</td><td>'.'в библиотеке'.'</td><tr>';
}
}else{echo'Записей не обнаружено';}
}







/* 
	Поиск 
	по 
	жанру 
*/
$search_genre = mysqli_query($connection,"
SELECT id, title 
FROM genre
");

if (mysqli_num_rows($search_genre)>0){
	echo '<form action=search.php method="post">';
	echo '<p>По жанру: <select name="genre">';
		while ($row = mysqli_fetch_assoc($search_genre)){
			$id = $row['id'];
			$title = $row['title'];
			echo'<option value="'.$id.'">'.$title.'</option>';
	}	echo '<p><input type="submit" value="Отправить"></p>';

echo "</select></p>";
echo'</form>';


if (isset($_POST['genre'])){/* В этот пос попадает id */
bord ();
	$id = $_POST['genre'];
	$genre = mysqli_query($connection,"
		SELECT COUNT(b.id), b.title, l.id, l.num_locker, l.num_shelf
		FROM `book` as b
		INNER JOIN 
		`categories` as c
		ON b.id = c.book_ID
			INNER JOIN
			`genre` as g
			ON c.genre_ID = g.id AND g.id = '$id'
					INNER JOIN
					locker as l
					ON b.id = l.book_id
						INNER JOIN 
						`rents_book` as rb
                    	ON b.id != rb.book_id and rb.date_return IS NULL
							GROUP BY(b.id)
						");


table($genre);
}
echo'</table>';
}






/* 	Поиск 
	по 
	полному
	названию
	книги
*/
$search_title_book = mysqli_query($connection,"
SELECT id, title
FROM book
GROUP BY title
");
if( mysqli_num_rows($search_title_book)>0){
	echo'<form action="search.php" method="post">';
		echo '<p>По имени: <select name="search_title_book">';
			while ( $row = mysqli_fetch_assoc($search_title_book) ){
				$id = $row['id'];
				$title = $row['title'];
			
				echo '<option value="'.$title.'">'.$title.'</option>';
			}
			echo '<p><input type="submit" value="Отправить"></p>';
		echo "</select></p>";
	echo'</form>';



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
}
/* 
	Поиск 
	по 
	Наименованию 
	через 
	строку 
*/
echo'<form action="search.php" method="post">';
	echo '<p>По части: <input type="text" name="search_title_string">';
	echo '<input type="submit" value="Отправить часть имени"></p>';
echo'</form>';

if(isset($_POST['search_title_string'])){
	$search_title_string = $_POST['search_title_string'];
			$search_title_string_rows = mysqli_query($connection,"
            SELECT id, title
			FROM book	
			");
			$array_title = array();
				while ( $row = mysqli_fetch_assoc($search_title_string_rows) ){
					$array_title[] = $row['title'];
					$array_id[] = $row['id'];
				}	
				

$array_row_result_title = array();
$array_row_result_id = array();

foreach ($array_title as $index=>$value){
	if (mb_stripos($value, $search_title_string,0,'UTF-8')!==false){
	
      $array_row_result_title[] = $value;
	  $array_row_result_id[] = $array_id[$index];
}
}

echo'<table border ="1">';
echo '<tr><td>Название</td><td>'.' место '.'</td><td>'.' шкаф '.'</td><td>'.' полка '.'</td><td>'.'Состояние'.'</td><tr>';
		foreach ($array_row_result_id as $index=>$value){	
				$_POST['search_title'] = $value;
if(isset($_POST['search_title'])){
						
$sql_search_title = mysqli_query ($connection,"
SELECT COUNT(b.id), b.title, l.id, l.num_locker, l.num_shelf
		FROM `book` as b  
        	INNER JOIN
        		`books_authors` as ba
                ON ba.book_id = b.id AND b.id = '{$_POST['search_title']}'
					INNER JOIN 
					`author` as a
					ON ba.author_id = a.id 
						INNER JOIN 
						`rents_book` as rb
                    	ON b.id != rb.book_id and rb.date_return IS NULL
							INNER JOIN
							locker as l
							ON l.book_id = b.id
								GROUP BY(b.id)
");

}
table($sql_search_title);
}
		echo'</table>';
}
/* 
	по 
	автору 
	частичному 
	и полному
*/
	echo'<form action="search.php" method="post">';
echo '<p>По ФИО: <input type="text" name="search_FIO">';
	echo '<input type="submit" value="Отправить ФИО"></p>';
	echo'</form>';
		if(isset($_POST['search_FIO'])){
			$search_FIO = $_POST['search_FIO'];
			
				$search_FIO_rows = mysqli_query($connection,"
				SELECT id, surname, name
				FROM author
				");
					/* Загоняем таблицу в массив */
					$search_FIO_array_row  = array();
					
						while ( $row = mysqli_fetch_assoc($search_FIO_rows) ){
							/* Засунь surname и name в один index массива */
						
							$search_FIO_array_row[] = $row['surname'].' '.$row['name'].' '.$row['id'];
						}
						

$search_FIO_result = array();

foreach ($search_FIO_array_row as $key=>$val){
	if (mb_stripos($val, $search_FIO,0,'UTF-8')!==false){
		/* Запиши в переменную $val-название книги */
      $search_FIO_result[] = $val;
	  /* Убери строки */
	  $str[] = preg_replace("/[^0-9]/", '', $val);
	  /* Убери цифры */
	  $num[] = preg_replace("/\d/",'',$val);
}
}

echo'<form action="search.php" method="post">';
	echo'<select name ="FIO">';
		foreach ($num as $index=>$value){	
			 echo"<option value='$str[$index]'>".$value.'</option>';
		 }
		echo "<input type='submit' value='Выбрать'>";
	echo'</select>';
echo'<form>';
}	

if(isset($_POST['FIO'])){
	bord ();
	/* 
		Заливаем две таблицы, 
		если id повторяются 
		то значит книга на руках.
	*/
$sql_FIO = mysqli_query($connection,"
SELECT COUNT(b.id), b.title, l.id, l.num_locker, l.num_shelf
		FROM `book` as b  
        	INNER JOIN
        		`books_authors` as ba
                ON ba.book_id = b.id
               	 INNER JOIN 
          			 `author` as a
            	ON ba.author_id = a.id AND a.id = '{$_POST['FIO']}'
                	INNER JOIN 
                    `rents_book` as rb
                    	ON b.id != rb.book_id and rb.date_return IS NULL
							INNER JOIN
								locker as l
									ON l.book_id = b.id
							GROUP BY(b.id)
	");
table($sql_FIO);
}
?>
<?php
mysqli_close($connection);
?>