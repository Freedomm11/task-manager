<?php

require_once "functions.php";

//получение данных из $_POST и $_GET
$title = $_POST['title'];
$description = $_POST['description'];
$id = $_GET['id'];

//валидация: проверка данных на пустоту
validate();

//получение имени файла (картинки)
$uploaddir = 'assets/media/uploads/';
$uploadfile = $uploaddir.basename($_FILES['image']['name']);
$image = $_FILES['image']['name'];

//соединение с БД
$pdo = config();

//проверка на пустоту файла (картинки)
if($image!="")
{
    $stmt = $pdo->prepare('UPDATE article SET title=?, description=?, image=? WHERE id=?');
    $result = $stmt->execute([$title, $description, $image, $id]);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
}
else
{
    $stmt = $pdo->prepare('UPDATE article SET title=?, description=? WHERE id=?');
    $result = $stmt->execute([$title, $description, $id]);
}

// переадресация на главную
header('Location: /list.php'); exit;






