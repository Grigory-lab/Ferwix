<?php
    include "DB.php"; //включение файла с подключением к БД
    $error = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['login-r']) && !empty($_POST['password-r']) && !empty($_POST['mail-r'])
    && !empty($_POST['name-r']) && !empty($_POST['phone-r'])) { //сохранение данных из формы в переменные сессии
        $_SESSION['login-r'] = htmlspecialchars($_POST['login-r']);
        $_SESSION['password-r'] = htmlspecialchars($_POST['password-r']);
        $_SESSION['name-r'] = htmlspecialchars($_POST['name-r']);
        $_SESSION['mail-r'] = htmlspecialchars($_POST['mail-r']);
        $_SESSION['phone-r'] = htmlspecialchars($_POST['phone-r']);
    }  

    if (isset($_SESSION['login-r'])) { //Сохранение переменных сессии в другие переменные
        $login = $_SESSION['login-r'];
        $password2 = $_SESSION['password-r'];
        $name = $_SESSION['name-r'];
        $mail = $_SESSION['mail-r'];
        $phone = $_SESSION['phone-r'];
    }

    if (isset($_POST['enter-acc']) && isset($_SESSION['login-r']) && isset($_SESSION['password-r']) && isset($_SESSION['mail-r'])
    && isset($_SESSION['name-r']) && isset($_SESSION['phone-r'])) { //условие "если переменные сессии установлены"
        $sql = "SELECT Login FROM clients WHERE Login = '$login'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows ($result) == 0) //если нет похожего пользователя, то данные добавляются в БД и открывается личный кабинет 
        {
            $sql = "INSERT INTO clients (Full_name, Phone_number, Mail, Login, Password, Site_visits) VALUES 
            ('$name', '$phone', '$mail', '$login', '$password2', '1')";
            $result = mysqli_query($conn, $sql);
            $error = 1;
            $_SESSION['error'] = 1;
            header("Location: Account.php");
        }
        else //случай если пользователь уже существует
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
            <h1>Регистрация</h1>
            <p>Создайте свой аккаунт</p>
            <form method="post" class="form-section" action=""> <!-- форма регистрации -->
                <div class="form-reg">
                    <input type="text" placeholder="Логин" class="enter-login" name="login-r" required title="Введите логин">
                    <input type="text" placeholder="Пароль" class="enter-password" name="password-r" required title="Введите пароль">
                    <input type="text" placeholder="ФИО [Иванов Иван Иванович]" class="enter-FIO" name="name-r" id="only-letters" required title="Введите своё ФИО">
                    <input type="email" placeholder="Эл. почта [alex@gmail.com]" class="enter-mail" name="mail-r" required>
                    <input type="text" placeholder="Телефон [8 (000) 000 00 00]" class="enter-phone" name="phone-r" id="c-phone" required title="Формат: 8 (XXX) XXX XX XX" pattern="8 \(\d{3}\) \d{3} \d{2} \d{2}">
                </div>
                <input type="submit" value="Зарегистрироваться" class="finish" id="enter-acc" name="enter-acc">
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
            alert('Пользователь стаким логином уже существует');
        }

        document.getElementById('only-letters').addEventListener('input', function () {
            this.value = this.value.replace(/[^A-Za-zА-Яа-яЁё\s]/g, ''); // Удаление всего, кроме букв и пробелов
        });

        document.getElementById('c-phone').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9()+\s]/g, '');  // Удаление всего, кроме цифр, +, () и пробелов
        });
    </script>
</body>
</html>