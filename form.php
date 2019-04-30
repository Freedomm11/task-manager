<?php

require_once "functions.php";
$pdo = config();

session_start();

//Если пустая переменная auth из сессии ИЛИ она равна false (для авторизованного она true).
if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
    //Проверяем, не пустые ли нужные нам куки
    if ( !empty($_COOKIE['email']) and !empty($_COOKIE['key']) ) {
        //Пишем email и ключ из КУК в переменные (для удобства работы):
        $email = $_COOKIE['email'];
        $key = $_COOKIE['key']; //ключ из кук (аналог пароля, в базе поле cookie)

        //Формируем и отсылаем SQL запрос:
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND cookie = ?');
        $stmt->execute([$email, $key]);
        $result = $stmt->fetch();

        //Если база данных вернула не пустой ответ - значит пара email-ключ_к_кукам подошла.
        if (!empty($result)) {

            //Пишем в сессию информацию о том, что мы авторизовались:
            $_SESSION['auth'] = true;
            //Пишем в сессию email и id пользователя
            $_SESSION['id'] = $result['id'];
            $_SESSION['email'] = $result['email'];
        }
    }
}

//получение данных из $_POST
$title = $_POST['title'];
$description = $_POST['description'];

//получение имени файла (картинки)
$uploaddir = 'assets/media/uploads/';
$uploadfile = $uploaddir.basename($_FILES['image']['name']);
$image = $_FILES['image']['name'];

//валидация: проверка данных на пустоту
validate();

//подготовка и выполнение запроса к БД
$sql = 'INSERT INTO article (title, description, image, id_user) VALUES (?, ?, ?, ?)';
$statement = $pdo->prepare($sql);
$result = $statement->execute([$title, $description, $image, $_SESSION['id']]);


errors($result);

//перемещение файла в дерикторию с картинками
if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
}
//переадресация на главную
header('Location: /list.php'); exit;

