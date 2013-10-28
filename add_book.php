<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET) && isset($_GET['authors']) && isset($_GET['title'])) 
{
	$connection = db_connect();

	$title = trim($_GET['title']);
	$validatedIDSs = array();
	foreach ($_GET['authors'] as $ID)
	{
		$validatedIDSs[] = trim($ID);
	}

	$title = mysqli_real_escape_string($connection, $title);
	for ($i=0, $length = count($validatedIDSs); $i < $length; $i++)
	{ 
		$validatedIDSs[$i] =mysqli_real_escape_string($connection, $validatedIDSs[$i]);
	}
	// now in $validatedIDSs[] we have all author_ids normalized and validated
	if (mb_strlen($title) < 3)
	{
		$error = 'Дължината на наименованието на книгата не трябва да е по-малка от 3 символа!';
	}

	$q = mysqli_query($connection, 'SELECT book_id FROM books WHERE book_title="' . $title . '"') or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	if (mysqli_num_rows($q) > 0)
	{
		$error = 'Вече има въведена книга с такова име!<br>Моля, изберете друго!';
	}
	
	/*
	these nested loops may seem a bit complicated,
	but we just check if there really are authors with passed IDs
	so in $realIDs[] we have list of those passed author_ids, which identify existing authors
	*/
	$q = mysqli_query($connection, 'SELECT author_id FROM authors') or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	$realIDs = array();
	while ($row = mysqli_fetch_assoc($q))
	{
		$found = false;
		foreach ($validatedIDSs as $validatedID)
		{
			if ($validatedID === $row['author_id'])
			{
				$found = true;
				break;
			}
		}
		if ($found)
		{
			$realIDs[] = $validatedID;
		}
	}
	if (count($realIDs) == 0)
	{
		$error = 'Не съществуват такива автори, които сте въвели!';
	}

	if (!isset($error))
	{
		mysqli_query($connection, 'INSERT INTO books (book_title) VALUES ("' . $title . '")') or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
		$q = mysqli_query($connection, 'SELECT book_id FROM books WHERE book_title="' . $title . '"') or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
		if (mysqli_num_rows($q) == 0)
		{
			$error = 'Неуспешно въведена книга!';
		}
		else
		{
			$row = mysqli_fetch_assoc($q);
			$bookID = $row['book_id'];
		}
		if (!isset($error))
		{
			$query = 'INSERT INTO books_authors VALUES ';
			$lengthMinus1 = count($realIDs) - 1;
			for ($i=0; $i < $lengthMinus1; $i++)
			{ 
				$query .= '(' . $bookID . ', ' . $realIDs[$i] . '),';
			}
			$query .= '(' . $bookID . ', ' . $realIDs[$lengthMinus1] . ')';
			mysqli_query($connection, $query) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
			if (!isset($error))
			{
				$success = 'Данните са успешно въведени!';
			}
		}
	}

}

$pageTitle = "Каталог за книги";
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

	include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';
	?>

	<section class="grid_8">
		<header>
			Добави книга
		</header>
		
		<form action="" method="GET">
			<label>
				Наименование на книгата:
				<input type="text" required name="title" />
			</label>
			<br>
			<label>
				Изберете автор(и): 
				<select multiple name="authors[]">
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