<?php
session_start();
var_dump($_SESSION);

if (!isset($_SESSION['login'])) header("location: ./controleur/login.php");
else header('location: ./controleur/index.php')
?>