<?php
require_once "functions.php";

//получение данных из $_POST
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

//валидация: проверка данных на пустоту
validate();

//подготовка и выполнение запроса к БД
$pdo = config();
$sql = 'SELECT id FROM users WHERE email=:email';
$statement = $pdo->prepare($sql);
$statement->execute([':email' => $email]);
$user = $statement->fetchColumn();
if($user) {
    $errorMessage = 'Пользователь с таким email уже существует';
    include 'errors.php';
    exit;
}

$sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
$statement = $pdo->prepare($sql);
$_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
$result = $statement->execute($_POST);
errors($result);

//переадресация на авторизацию
header('Location: /login-form.php'); exit;
