<?php

require_once "functions.php";

//получение данных из $_POST
$email = $_POST['email'];
$password = $_POST['password'];

//валидация: проверка данных на пустоту
validate();

//подготовка и выполнение запроса к БД
$pdo = config();
$stmt = $pdo->query('SELECT * FROM users ');

foreach ($stmt as $row) {
    if ($email == $row['email'] and  password_verify($password, $row['password']) == $row['password']  )
    {
        session_start();
        $_SESSION['auth'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];

        //Проверяем, что была нажата галочка 'Запомнить меня':
        if (!empty($_REQUEST['remember']) and $_REQUEST['remember'] == 1) {
            //Сформируем случайную строку для куки (используем функцию generateSalt):
            $key = generateSalt(); //назовем ее $key

            //Пишем куки (имя куки, значение, время жизни - сейчас+месяц)
            setcookie('email', $row['email'], time()+360000); //email
            setcookie('key', $key, time()+360000); //случайная строка
            /*
				Пишем эту же куку в базу данных для данного юзера.
				Формируем и отсылаем SQL запрос:
			*/
            $stmt = $pdo->prepare('UPDATE users SET cookie=? WHERE email=?');
            $result = $stmt->execute([$key, $row['email']]);
        }
        header('Location: list.php');exit;
    }
};

$errorMessage = 'Пожалуйста, заполните все поля!';
include 'errors.php';
exit;










