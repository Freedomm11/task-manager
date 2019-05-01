<?php
session_start();
require_once 'functions.php';

//Очищаем сессию
    unset($_SESSION['auth']);
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    session_destroy();

//Удаляем куки:
    setcookie('email', '', time()); //удаляем email
    setcookie('key', '', time()); //удаляем ключ

//Переадрессация на авторизацию
header('Location: /login-form.php'); exit;
