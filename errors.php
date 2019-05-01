<?php

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
    else {
        //Если сессия и куки пустые, тогда делаем переадрессацию на авторизацию
        header('Location: login-form.php');exit;
    }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Errors</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="container text-center mt-5">
        <?php if(isset($errorMessage)): ?>
        <p> <?php echo $errorMessage; ?></p>
        <?php else: ?>
      <p>Заполните все поля.</p>
        <?php endif; ?>
      <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Назад</a>
    </div>
  </body>
</html>
