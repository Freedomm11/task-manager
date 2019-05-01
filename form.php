<?php
require_once "functions.php";
$pdo = config();

session_start();

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

