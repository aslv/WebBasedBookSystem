<?php
require 'includes' . DIRECTORY_SEPARATOR . 'session.php';
session_destroy();
header('Location: index.php');
?>
