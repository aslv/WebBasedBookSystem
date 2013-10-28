<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
require 'includes' . DIRECTORY_SEPARATOR . 'config.php';

if ($_POST && isset($_POST['user']) && isset($_POST['pass']))
{
    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);
    $name = trim($_POST['name']);

    $connection = db_connect();
    $user = mysqli_real_escape_string($connection, $user);
    $pass = mysqli_real_escape_string($connection, $pass);
    $name = mysqli_real_escape_string($connection, $name);

    if (mb_strlen($user) < 3)
    {
         $error = 'Дължината на потребителското име трябва да е най-малко 3 (три) символа.';
    }
    if (mb_strlen($pass) < 3)
    {
         $error = 'Дължината на паролата трябва да е най-малко 3 (три) символа.';
    }
    $q = mysqli_query($connection, 'SELECT username FROM users WHERE username="' . $user . '"') or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
    if (mysqli_num_rows($q) > 0)
    {
        $error = 'Потребител с такова потребителско име вече съществува!<br>Моля, изберете друго.';
    }
    if (!isset($error))
    {
        $pass = password_hash($pass, PASSWORD_BCRYPT);
        $q = mysqli_query($connection,
        'INSERT INTO users (username, password, name) VALUES ("' . $user . '", "' . $pass . '", "' . $name . '")')
        or $error = 'Възникна грешка!<br>Моля, опитайте по-късно!';
    }
    if (!isset($error))
    {
        $success = 'Новият потребител бе регистриран успешно!';
    }
    
}

$pageTitle = 'Registration';
include 'includes' . DIRECTORY_SEPARATOR . 'header.php';

include 'includes' . DIRECTORY_SEPARATOR . 'nav.php';

?>
	<section class="grid_8">
        <header>
            Регистрирай нов потребител
        </header>
        <form action="" method="POST">
            <table class="login">
                <tr>
                    <td><label for="user">Потребителсто име</label></td>
                    <td><input type="text" id="user" required name="user" /></td>
                </tr>
                <tr>
                    <td><label for="pass">Парола</label></td>
                    <td><input type="password" id="pass" required name="pass" /></td>
                </tr>
                <tr>
                    <td><label for="name">Име</label></td>
                    <td><input type="text" id="name" name="name" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Изпрати" /></td>
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