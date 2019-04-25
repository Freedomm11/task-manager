<?php
//Если переменная auth из сессии не пуста и равна true, то...
    session_start();
    session_destroy(); //разрушаем сессию для пользователя

    //Удаляем куки авторизации путем установления времени их жизни на текущий момент:
    setcookie('email', '', time()); //удаляем логин
    setcookie('key', '', time()); //удаляем ключ

header('Location: /login-form.php'); exit;
?>