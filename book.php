<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

if (isset($_GET) && isset($_GET['book_id']))
{
	$connection = db_connect();

	$bookID = trim($_GET['book_id']);

	$bookID = (int)mysqli_real_escape_string($connection, $bookID);

	$q = mysqli_query($connection, 'SELECT book_title FROM books WHERE book_id=' . $bookID) or $error='Възникна грешка!<br>Моля, опитайте по-късно!';
	if (mysqli_num_rows($q) == 0)
	{
		$error='Няма такава книга!';
	}
	else
	{
		$row = mysqli_fetch_assoc($q);
		$bookTitle = $row['book_title'];
	}
}
else
{
	header('Location: books.php');
	exit;
}

if ($_POST && isset($_POST['title']) && isset($_POST['content']))
{
	$title = trim($_POST['title']);
	$content = trim($_POST['content']);

	$connection = db_connect();
	$title = mysqli_real_escape_string($connection, $title);
	$content = mysqli_real_escape_string($connection, $content);

	if (mb_strlen($title) > 50)
	{
		$errorC = 'Заглавието на съобщението е прекалено дълго!';
	}
	if (mb_strlen($content) > 250)
	{
		$errorC = 'Съдържанието на съобщението е прекалено дълго!';
	}
	if ($title == '' || $content == '')
	{
		$errorC = 'Въведените данни са невалидни!';
	}

	if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== true)
	{
		$errorC = 'Вие нямате права да добавяте коментари!<br>Моля, влезте в системата, ако искате да коментирате!';
	}

	if (!isset($errorC))
	{
        $query = 'INSERT INTO comments(book_id, user_id, title, content)
        		  VALUES(' . $bookID . ', ' . $_SESSION['userID'] . ', "' . $title . '", "'. $content .'")';
    	mysqli_query($connection, $query) or $errorC='Възникна грешка!<br>Моля, опитайте по-късно!';
    	if (!isset($errorC))
    	{
    		$successC = 'Коментарът бе успешно записан!';
    	}
	}
}


$pageTitle = "Книга";
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

	include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';
	?>

	<section class="grid_8">
		<header>
			Книга
		</header>

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
				<th>#</th>
				<th>Книга</th>
				<th>Автор(и)</th>
				<th>Брой коментари</th>
			</tr>
			<?php
			if (isset($bookTitle))
			{
				$connection = db_connect();
				//
				$query = 'SELECT * FROM books
						  INNER JOIN books_authors ON books.book_id=books_authors.book_id
						  INNER JOIN authors ON authors.author_id=books_authors.author_id
						  WHERE books.book_id=' . $bookID;
				if (isset($_POST['sort']))
				{
					$sort = strtoupper(mysqli_real_escape_string($connection, trim($_POST['sort'])));
					if ($sort == 'ASC' || $sort == 'DESC')
					{
						$query .= ' ORDER BY books.book_title ' . $sort;
					}
				}
				$q = mysqli_query($connection, $query) or $errorB = 'Възникна грешка!<br>Моля, опитайте по-късно!';
				if (!isset($errorB))
				{
					$result = array();
					while ($row = mysqli_fetch_assoc($q))
					{
						$result[$row['book_id']]['book_title'] = $row['book_title'];
						$result[$row['book_id']]['author_names'][] = array('author_name' => $row['author_name'], 'author_id' => $row['author_id']);
					}
					//echo '<pre>' . print_r($result, true) . '</pre>';
					foreach ($result as $index => $book)
					{
						echo '<tr><td>' . $index . '</td><td><a href="book.php?book_id=' . $index . '">' . $book['book_title'] . '</a></td><td>';
						foreach ($book['author_names'] as $author)
						{
							//echo '<pre>' . print_r($author, true) . '</pre>';
							echo '<a href="author_books.php?author_id=' . $author['author_id'] . '">' . $author['author_name'] . '</a><br>';
						}
						echo '</td><td>';
						$q = mysqli_query($connection, 'SELECT COUNT(*) FROM comments WHERE book_id=' . $index) or $errorB = 'Възникна грешка!<br>Моля, опитайте по-късно!';
						if (!isset($errorB))
						{
							echo mysqli_fetch_assoc($q)['COUNT(*)'];
						}
						echo '</td></tr>';
					}
				}
				else
				{
					echo '<div class="error">' . $errorB . '</div>';
				}
			}
			?>
		</table>
		<?php
		include 'includes' . DIRECTORY_SEPARATOR . 'sort.php';
		?>

		<?php if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] === true): ?>
		<div class="filter" style="clear: both;">
		<h3>Коментирай книгата</h3>
		<form action="" method="POST">
        	<table>
        		<tr>
        			<td><label for="title">Заглавие</label></td>
        			<td><input type="text" id="title" maxlenght="50" required name="title" /></td>
        		</tr>
        		<tr>
        			<td><label for="content">Съдържание</label></td>
        			<td><textarea id="content" rows="5" maxlength="250" required name="content"></textarea></td>
        		</tr>
        		<tr>
        			<td colspan="2"><input type="submit" value="Създай" /></td>
        		</tr>
        	</table>
        </form>
        <?php
		if (isset($errorC))
		{
			echo '<div class="error">' . $errorC . '</div>';
		}
		if (isset($successC))
		{
			echo '<div class="success">' . $successC . '</div>';
		}
		?>
        </div>
    	<?php endif; ?>

		<div style="float: left;">
		<table class="msg">
            <tr>
                <th>#</th><th>Публикуван</th><th>Потребителско име</th><th>Име</th><th>Заглавие</th>
            </tr>
            <?php
            $connection = db_connect();
            $query = 'SELECT * FROM comments
            		  INNER JOIN users ON users.user_id=comments.user_id
            		  WHERE comments.book_id=' . $bookID;
            $q = mysqli_query($connection, $query) or $errorA = 'Възникна грешка!<br>Моля, опитайте по-късно!';
            if (!isset($errorA))
            {
            	while ($row = mysqli_fetch_assoc($q))
            	{
                	echo '<tr><td>' . $row['comment_id'] . '</td><td>' . $row['datetime'] . '</td><td>'
                	 . wordwrap($row['username'], 20, '<br>', true) . '</td><td>' 
               		 . wordwrap($row['name'], 20, '<br>', true) . '</td><td>'
                	 . wordwrap($row['title'], 20, '<br>', true). '</td></tr>';
               		echo '<tr><td colspan="4">' . wordwrap($row['content'], 50, '<br>', true) . '</td></tr>';
            	}
            }
            ?>
        </table>
        </div>
		<?php
		if (isset($errorA))
		{
			echo '<div class="error">' . $errorA . '</div>';
		}
		?>

	</section>

	<?php
	include 'includes' . DIRECTORY_SEPARATOR . 'aside.php';

include 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
?>