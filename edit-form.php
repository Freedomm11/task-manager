<?php
session_start();
//Если сессия и куки пустые, тогда делаем переадрессацию на авторизацию
if (empty($_SESSION['auth']) and empty($_COOKIE['key']) )
{
    header('Location: login-form.php');exit;
}

require_once "functions.php";

$id = $_GET['id'];

//подготовка и выполнение запроса к БД
$pdo = config();
$stmt = $pdo->prepare('SELECT * FROM article WHERE id = ?');
$stmt->execute([$id]);
$edit_article = $stmt->fetch();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Edit Task</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
      <form class="form-signin"  enctype="multipart/form-data" action="edit.php?id=<?echo $id?>" method="post">
        <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Добавить запись</h1>
        <label for="inputEmail" class="sr-only">Название</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="Название" name="title" value="<?echo $edit_article['title'];?> ">
        <label for="inputEmail" class="sr-only">Описание</label>
        <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Описание"><?echo $edit_article['description'];?> </textarea>
        <input type="file" name="image" multiple accept="image/*">
        <img src="assets/media/uploads/<?echo $edit_article['image'];?>" alt="" width="300" class="mb-3">
        <button class="btn btn-lg btn-success btn-block" type="submit">Редактировать</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2018-2019</p>
      </form>
    </div>
  </body>
</html>
