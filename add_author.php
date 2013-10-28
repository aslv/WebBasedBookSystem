<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET) && isset($_GET['author']))
{
	$connection = db_connect();

	$author = trim($_GET['author']);
	$author = mysqli_real_escape_string($connection, $author);

	if (mb_strlen($author) < 3)
	{
		$error = 'Дължината на името на автора не трябва да е по-малка от 3 символа!';
	}
	// checking whether an author with the same name exists
	$q = mysqli_query($connection, 'SELECT author_id FROM authors WHERE author_name="' . $author . '"') or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	if (mysqli_num_rows($q) > 0)
	{
		$error = 'Вече има въведен автор с такова име!<br>Моля, изберете друго!';
	}
	if (!isset($error))
	{
		$query = 'INSERT INTO authors(author_name) VALUES ("' . $author . '")';
		mysqli_query($connection, $query) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
		if (!isset($error))
		{
			$success = 'Автроът \'' . $author . '\' бе успешно добавен!';
		}
	}
}

$pageTitle = "Каталог за книги";
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

	include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';
	?>

	<section class="grid_8">
		<header>
			Добави автор
		</header>
		
		<form action="" method="GET">
			<label>
				Автор:
				<input type="text" required name="author" />
			</label>
			<input type="submit" value="Добави" />
		</form>

		<?php
		if (isset($error))
		{
			echo '<div class="error">' . $error . '</div>';
		}
		if (isset($success))
		{
			echo '<div class="success">' . $success . '</div>';
		}
		?>

		<table class="msg">
			<tr>
				<th>Автори</th>
			</tr>
			<?php
			$connection = db_connect();
			$query = 'SELECT author_name FROM authors';
			$q = mysqli_query($connection, $query) or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
			while ($row = mysqli_fetch_assoc($q))
			{
				echo '<tr><td>' . $row['author_name'] . '</td></tr>';
			}
			?>
		</table>

	</section>

	<?php
	include 'includes' . DIRECTORY_SEPARATOR . 'aside.php';

include 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
?>