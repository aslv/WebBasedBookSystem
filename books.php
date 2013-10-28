<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

$pageTitle = "Каталог за книги";
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

	include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';
	?>

	<section class="grid_8">
		<header>
			Списък на книгите
		</header>
		<table class="msg">
			<tr>
				<th>#</th>
				<th>Книга</th>
				<th>Автор(и)</th>
				<th>Брой коментари</th>
			</tr>
			<?php
			$connection = db_connect();
			// we select all records
			$query = 'SELECT books.book_title, books.book_id, authors.author_name, authors.author_id FROM books
					  INNER JOIN books_authors as ba ON books.book_id=ba.book_id
					  INNER JOIN authors ON authors.author_id=ba.author_id';
			if (isset($_POST['sort'])) // if any sort is set
			{
				$sort = strtoupper(mysqli_real_escape_string($connection, trim($_POST['sort'])));

				if ($sort == 'ASC' || $sort == 'DESC')
				{
					$query .= ' ORDER BY books.book_title ' . $sort;
				}
			}
			$q = mysqli_query($connection, $query) or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
			if (!isset($error))
			{
				// rearranging result in a user-friendly way
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
					$q = mysqli_query($connection, 'SELECT COUNT(*) FROM comments WHERE book_id=' . $index) or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
					if (!isset($error))
					{
						echo mysqli_fetch_assoc($q)['COUNT(*)'];
					}
					echo '</td></tr>';
				}
			}
			else
			{
				echo '<div class="error">' . $error . '</div>';
			}
			?>
		</table>
		<?php
		include 'includes' . DIRECTORY_SEPARATOR . 'sort.php';
		?>
	</section>

	<?php
	include 'includes' . DIRECTORY_SEPARATOR . 'aside.php';

include 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
?>