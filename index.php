<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

function valid_user($username, $password)
{
	if ($username == '' || $password == '')
	{
		$GLOBALS['error'] = 'Въведените потребителско име и/или парола са грешни!'; // empty username || password
	}
	$connection = db_connect();
	$username = mysqli_real_escape_string($connection, $username);
	$password = mysqli_real_escape_string($connection, $password);
	$q = mysqli_query($connection, 'SELECT * FROM users WHERE username="' . $username . '"') or $GLOBALS['error'] = 'Възникна грешка!<br>Моля, опитайте по късно!';
	if (mysqli_num_rows($q) == 1)
	{
		$row = mysqli_fetch_assoc($q);
		if (password_verify($password, $row['password']))
		{
			return $row;
		}
		else
		{
			$GLOBALS['error'] = 'Въведените потребителско име и/или парола са грешни!'; // invalid password
		}
	}
	elseif (mysqli_num_rows($q) == 0)
	{
		$GLOBALS['error'] = 'Въведените потребителско име и/или парола са грешни!'; // invalid username
	}
	else
	{
		$GLOBALS['error'] = 'Възникна грешка!<br>Моля, опитайте по късно!'; // broken database
	}
	return false;
}

if ($_POST && isset($_POST['user']) && isset($_POST['pass']))
{
	$username = trim($_POST['user']);
	$password = trim($_POST['pass']);
	if ($row = valid_user($username, $password))
	{
		$_SESSION['isLogged'] = true;
		$_SESSION['userID'] = $row['user_id'];
		$_SESSION['username'] = $row['username'];
		$_SESSION['name'] = $row['name'];
		header('Location: books.php');
		exit;
	}
}

$pageTitle = 'Login';
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';
?>

    <?php include 'includes' . DIRECTORY_SEPARATOR . 'nav.php'; ?>
    
    <section class="grid_8">
        <header>
            Вход
        </header>
        <form action="" method="POST">
        	<table class="login">
        		<tr>
        			<td><label for="user">Потребителсто име</label></td>
        			<td><input type="text" id="user" required autofocus autocomplete="on" name="user" /></td>
        		</tr>
        		<tr>
        			<td><label for="pass">Парола</label></td>
        			<td><input type="password" id="pass" required name="pass" /></td>
        		</tr>
        		<tr>
        			<td colspan="2"><input type="submit" value="Влез" /></td>
        		</tr>
        	</table>
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
    ?>

<?php
include 'includes' . DIRECTORY_SEPARATOR . 'footer.php';
?>