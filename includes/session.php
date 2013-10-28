<?php
session_set_cookie_params(0, '/', '', false, true);
session_start();
/*
if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] !== true) // if not logged
	&& basename($_SERVER['PHP_SELF']) != 'index.php' && basename($_SERVER['PHP_SELF']) != 'register.php')
// if on index.php || register.php, we do not redirect to index.php
{
	header('Location: index.php');
	exit;
}
*/
?>