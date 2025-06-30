<?php
    include "DB.php"; //включение файла с подключением к БД
    $error = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['login-a']) && !empty($_POST['password-a'])) { 
        $_SESSION['login-a'] = htmlspecialchars($_POST['login-a']); //сохранение данных из формы в переменные сессии
        $_SESSION['password-a'] = htmlspecialchars($_POST['password-a']);
    } 

    if (isset($_SESSION['login-a'])) { //Сохранение переменных сессии в другие переменные
        $login = $_SESSION['login-a'];
        $password2 = $_SESSION['password-a'];
    }

    if (isset($_POST['enter-acc']) && isset($_SESSION['login-a']) && isset($_SESSION['password-a'])) { //условие "если переменные сессии установлены"
        $sql = "SELECT Login, Password FROM clients WHERE Login = '$login' AND Password = '$password2'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows ($result) > 0) //если в БД найдены данные пользователя, то происходит вход в аккаунт
        {
            $update_visits = "UPDATE clients SET Site_visits = Site_visits + 1 WHERE Login = '$login'"; //+1 посещение сайта в БД
            mysqli_query($conn, $update_visits);
            $error = 1;
            $_SESSION['error'] = 1;
            header("Location: Account.php"); //открытие личного кабинета
        }
        else //случай если пользователь не найден
        { 
            $error = 2;
            $_SESSION['error'] = 2;
            session_unset(); //уничтожение переменных сессии
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferwix</title>
    <link rel="stylesheet" href="main.css"> <!-- подключение css -->
</head>
<body>
    <header class="header"> <!-- шапка сайта -->
        <div class="container header__content">
            <img src="img/logo.png" alt="Лого" width="100px" height="100px">
            <nav class="menu">
                <ul class="menu__list">
                    <li class="menu__item"><a class="menu__link" href="Main.php">Главная</a></li>
                    <li class="menu__item"><a class="menu__link" href="Masters.php">Мастера</a></li>
                    <li class="menu__item"><a class="menu__link" href="Services.php">Услуги</a></li>
                    <li class="menu__item"><a class="menu__link" href="Callback.php">Обратная связь</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main container-2">
        <section class="standart-block">
            <h1>Авторизация</h1>
            <p>Введите свои данные</p>
            <form method="post" class="form-section" action=""> <!-- форма авторизации -->
                <div class="form-block">
                    <input type="text" placeholder="Логин" class="enter-login" name="login-a" required>
                    <input type="text" placeholder="Пароль" class="enter-password" name="password-a" required>
                </div>
                <input type="submit" value="Войти" class="finish" id="enter-acc" name="enter-acc">
            </form>
        </section>
    </main>

    <footer class="footer"> <!-- подвал сайта -->
        <div class="container-2">
            <p>Ferwix 2025</p>
        </div>
    </footer>

    <script>
        let error = <?php echo json_encode($error); ?>; //преобразование переменной для JS

        if (error === 2) { //вывод сообщения при ошибке
            alert('Пользователь не найден. Введите правильные данные');
        }
    </script>
</body>
</html>