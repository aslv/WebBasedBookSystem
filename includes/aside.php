    <aside class="grid_2">

        <?php
        if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] === true) 
        {
            echo '<div class="search">Здравей, <a href="user.php"><span class="sc">';
            if ($_SESSION['name'])
            {
                echo $_SESSION['name'];
            }
            else
            {
                echo $_SESSION['username'];
            } 
            echo '</span></a>!';
            echo '<div class="centre"><a href="logout.php" class="button">Изход</a></div></div>';
        }
        ?>

        <div class="search">
            <form action="" method="GET">
                <label>
                    <span class="labelTitle">Търсене по автор:</span>
                    <input type="text" name="searchAuthor" />
                </label>
                <!--<input type="submit" value="Виж" />-->
                <button type="submit" border="0">Търсене <img src="img/search.png" alt="" /></button>
            </form>
        <?php
        if (isset($GLOBALS['errorA']))
        {
            echo '<div class="error">' . $GLOBALS['errorA'] . '</div>';
        }
        ?>
        </div>

        <div class="search">
            <form action="" method="GET">
                <label>
                    <span class="labelTitle">Търсене по книга:</span>
                    <input type="text" name="searchBook" />
                </label>
                <button type="submit" border="0">Търсене <img src="img/search.png" alt="" /></button>
            </form>
        <?php
        if (isset($GLOBALS['errorB']))
        {
            echo '<div class="error">' . $GLOBALS['errorB'] . '</div>';
        }
        ?>
        </div>

    </aside>