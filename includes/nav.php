	<nav class="grid_2">
        <ul>
            <li>
                <a href="index.php">Вход</a>
            </li>
            <li>
                <a href="books.php">Списък книги</a>
            </li>
            <li>
                <a href="book.php">Книга</a>
            </li>
            <li>
                <a href="add_book.php">Нова книга</a>
            </li>
            <li>
                <a href="add_author.php">Нов автор</a>
            </li>
            <li>
                <a href="author_books.php">Книги от автор</a>
            </li>
            <li>
                <a href="add_author_to_book.php">Добавяне на автор към книга</a>
            </li>
            <li>
                <a href="register.php">Регистриране на потребител</a>
            </li>
        <?php
        if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] === true)
        {
            echo '<li><a href="user.php"><span class="sc">';
            if ($_SESSION['name'] !== '')
            {
                echo $_SESSION['name'];
            }
            else
            {
                echo $_SESSION['username'];
            }
            echo '</span></a></li><li><a href="logout.php">Изход</a></li>';
        }
        ?>
        </ul>
    </nav>