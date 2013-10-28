<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== true)
{
	header('Location: index.php');
	exit;
}

$pageTitle = "Потребител";
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

	include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';
	?>

	<section class="grid_8">
		<header>
			<?php
			echo '<span class="sc">';
			if ($_SESSION['name'] !== '')
            {
                echo $_SESSION['name'];
            }
            else
            {
                echo $_SESSION['username'];
            }
            echo '</span>';
            ?>
		</header>

		<div style="float: left;">
		<table class="msg">
            <tr>
                <th>#</th><th>Публикуван</th><th>Потребителско име</th><th>Име</th><th>Заглавие</th><th>Към книга</th>
            </tr>
            <?php
            $connection = db_connect();
            $query = 'SELECT * FROM comments
            		  INNER JOIN users ON users.user_id=comments.user_id
            		  INNER JOIN books ON books.book_id=comments.book_id
            		  WHERE comments.user_id=' . $_SESSION['userID'];
            $q = mysqli_query($connection, $query) or $errorA = 'Възникна грешка!<br>Моля, опитайте по-късно!';
            if (!isset($errorA))
            {
            	while ($row = mysqli_fetch_assoc($q))
            	{
                	echo '<tr><td>' . $row['comment_id'] . '</td><td>' . $row['datetime'] . '</td><td>'
                	 . wordwrap($row['username'], 20, '<br>', true) . '</td><td>' 
               		 . wordwrap($row['name'], 20, '<br>', true) . '</td><td>'
               		 . wordwrap($row['book_title'], 20, '<br>', true) . '</td><td>'
                	 . wordwrap($row['title'], 20, '<br>', true). '</td></tr>';
               		echo '<tr><td colspan="4">' . wordwrap($row['content'], 50, '<br>', true) . '</td></tr>';
            	}
            }
            ?>
        </table>
        </div>

		</section>

	<?php
	include 'includes' . DIRECTORY_SEPARATOR . 'aside.php';

include 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
?>
