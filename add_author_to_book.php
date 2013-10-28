<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET) && isset($_GET['author']) && isset($_GET['title'])) 
{
	$connection = db_connect();

	$bookID = trim($_GET['title']);
	$authorID = trim($_GET['author']);

	$bookID = (int)mysqli_real_escape_string($connection, $bookID);
	$authorID = (int)mysqli_real_escape_string($connection, $authorID);

	$q = mysqli_query($connection, 'SELECT book_title FROM books WHERE book_id=' . $bookID) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	if (mysqli_num_rows($q) == 0)
	{
		$error = 'Няма такава книга!';
	}
	$q = mysqli_query($connection, 'SELECT author_name FROM authors WHERE author_id=' . $authorID) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	if (mysqli_num_rows($q) == 0)
	{
		$error = 'Няма такъв автор!';
	}
	$q = mysqli_query($connection, 'SELECT * FROM books_authors WHERE book_id=' . $bookID . ' AND author_id=' . $authorID) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	if (mysqli_num_rows($q) > 0)
	{
		$error = 'Посоченият автор вече е автор на посочената книга!';
	}

	if (!isset($error))
	{
		$query = 'INSERT INTO books_authors VALUES (' . $bookID . ', ' . $authorID . ')';
		mysqli_query($connection, $query) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
		if (!isset($error))
		{
			$success = 'Данните са успешно въведени!';
		}
	}
}

$pageTitle = "Каталог за книги";
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

	include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';
	?>

	<section class="grid_8">
		<header>
			Добави автор към книга
		</header>
		
		<form action="" method="GET">
			<label>
				Изберете книга, към която да се добави автор:
				<select required name="title">
				<?php
				$connection = db_connect();
				$q = mysqli_query($connection, 'SELECT * FROM books') or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
				while ($row = mysqli_fetch_assoc($q))
				{
					echo '<option value="' . $row['book_id'] . '">' . $row['book_title'] . '</option>';
				}
				?>
				</select>
			</label>
			<br>
			<label>
				Изберете автор:
				<select required name="author">
				<?php
				$connection = db_connect();
				$q = mysqli_query($connection, 'SELECT * FROM authors') or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
				while ($row = mysqli_fetch_assoc($q))
				{
					echo '<option value="' . $row['author_id'] . '">' . $row['author_name'] . '</option>';
				}
				?>
				</select>
			</label>
			<br>
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

	</section>

	<?php
	include 'includes' . DIRECTORY_SEPARATOR . 'aside.php';

include 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
?>