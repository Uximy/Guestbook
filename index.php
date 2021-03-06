<?php
	include_once('./db.php');
	$db = connect('localhost', 'uximy', '', 'books');

	$input = $_POST['save'] ?? 0;

	$date = date('Y-m-d H:i:s', time() + 3600 * 5);

	$arr = [$_POST['Nick'] ?? 0, $_POST['Text'] ?? 0, $date];

	$page = $_GET['page'] ?? 1;

	$kol = 5;

	$art = ($page * $kol) - $kol;

	function RenderListReview($input, $db, $arr, $art, $kol)
	{
		if ($input) {
			try {
				$db -> query("INSERT INTO `review`(`Name`, `Text`, `date`) VALUES ('$arr[0]', '$arr[1]', '$arr[2]')");
			} catch (PDOException $e) {
				echo "Query error: " . $e->getMessage();
			}

			echo "
			<div class='info alert alert-info'>
				Запись успешно сохранена!
			</div>
			";
		}

		function sort_date($a_new, $b_new) {

			$a_new = strtotime($a_new["date"]);
			$b_new = strtotime($b_new["date"]);
		
			return $b_new - $a_new;
		
		}

		$result = $db->query("SELECT * FROM `review` LIMIT $art, $kol")->fetchAll();

		$res = $db->query("SELECT COUNT(*) FROM `review`");

		$row = $res->fetch();
	
		$total = $row[0];
	
		$str_pag = (int)ceil($total / $kol);

		if ($result) {
			usort($result, "sort_date");
			foreach ($result as $k => $value) {
				$dateresylt = $db->query("SELECT DATE_FORMAT('$value[3]', '%d.%m.%Y %H:%i:%s')")->fetchAll()[0];

				echo "
				<div class='note'>
				<p>
				<span class='date'>$dateresylt[0]</span>
				<span class='name'>$value[1]</span>
				</p>
				<p>
				$value[2]
				</p>
				</div>		
				";
			}
			echo "
				<nav>
					<ul class='pagination'>
						<li>
							<a href='?page=1' aria-label='Previous'>
								<span aria-hidden='true'>&laquo;</span>
							</a>
						</li>";
				for ($i = 1; $i <= $str_pag; $i++){
					echo '<li><a href=?page='.$i.'>'.$i.'</a></li>';
				}
				echo "
						<li>
							<a href=?page=".$str_pag ." aria-label='Next'>
								<span aria-hidden='true'>&raquo;</span>
							</a>
						</li>
					</ul>
				</nav>";
		}
		else{
			echo "<p style='display: flex;justify-content: center;margin: 20px 0;'>В моей базе нету данных!</p>";
		}
	}
?>



<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">  
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="icon" type="image/png" href="favicon.png" />
	</head>
	<body>
		<div id="wrapper">
			<h1><a href="/" style="text-decoration: none;">Гостевая книга</a></h1>

			<?php 
				RenderListReview($input, $db, $arr, $art, $kol);
			?>

			<div id="form">
				<form method="POST">
					<p><input class="form-control" name="Nick" placeholder="Ваше имя"></p>
					<p><textarea class="form-control" name="Text" placeholder="Ваш отзыв"></textarea></p>
					<p><input type="submit" name="save" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>
	</body>
</html>