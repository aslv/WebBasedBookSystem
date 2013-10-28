<?php
mb_internal_encoding('UTF-8');

require 'lib' . DIRECTORY_SEPARATOR . 'password.php';

$heading = 'Система за индексиране на книги';

// using default DB user
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db = 'books';

function db_connect() // a sample of singletone
{
	static $connection;
	if ($connection)
	{
		return $connection;
	}
    $connection = mysqli_connect($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db']) or
    											$GLOBALS['error'] = 'Възникна грешка!<br>Моля, опитайте по-късно!';
    mysqli_set_charset($connection, 'utf8') or $GLOBALS['error'] = 'Възникна грешка!<br>Моля, опитайте по-късно!';
    return $connection;
}

function searchHandler()
{
	if(isset($_GET))
	{
		if (isset($_GET['searchAuthor']))
        {
            $connection = db_connect();
            $author = trim($_GET['searchAuthor']);
            $author = mysqli_real_escape_string($connection, $author);
            if (mb_strlen($author) < 3)
            {
                $errorA = 'Дължината на името на автора не може да е по-малка от 3 символа!';
            }
            $query = 'SELECT author_id FROM authors WHERE author_name="' . $author . '"';
            $q = mysqli_query($connection, $query) or $errorA = 'Възникна грешка!<br>Моля, опитайте по-късно!';
            if (!isset($errorA))
            {
                if (mysqli_num_rows($q) == 0)
                {
                    $errorA = 'Няма такъв автор!';
                }
                else
                {
                    $row = mysqli_fetch_assoc($q);
                    header('Location: author_books.php?author_id=' . $row['author_id']);
                    exit;
                }
            }
        }
        if (isset($_GET['searchBook']))
        {
            $connection = db_connect();
            $book = trim($_GET['searchBook']);
            $book = mysqli_real_escape_string($connection, $book);
            if (mb_strlen($book) < 3)
            {
                $errorB = 'Дължината на наименованието на книгата не може да е по-малка от 3 символа!';
            }
            $query = 'SELECT book_id FROM books WHERE book_title="' . $book . '"';
            $q = mysqli_query($connection, $query) or $errorB = 'Възникна грешка!<br>Моля, опитайте по-късно!';
            if (!isset($error_))
            {
                if (mysqli_num_rows($q) == 0)
                {
                    $errorB = 'Няма такава книга!';
                }
                else
                {
                    $row = mysqli_fetch_assoc($q);
                    header('Location: book.php?book_id=' . $row['book_id']);
                    exit;
                }
            }
        }

        if (isset($errorA))
        {
        	$GLOBALS['errorA'] = $errorA;
        }
        if (isset($errorB))
        {
        	$GLOBALS['errorB'] = $errorB;
        }
    }
}

searchHandler();
?>