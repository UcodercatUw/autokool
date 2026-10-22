<?php
class ControllerUser {

    public static function loginForm() {
        global $lang;
        include_once "view/formLogin.php";
    }

    public static function loginProcess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['btnLogin'])) {
            $login = trim($_POST['login'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($login) || empty($password)) {
                $_SESSION['error'] = "Täitke kõik väljad / Заполните все поля";
                header("Location: login");
                exit();
            }

            $user = ModelUser::checkUser($login, $password);

            if ($user) {
                // Защита от перехвата сессии
                session_regenerate_id(true);

                $_SESSION['userId'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['status'] = $user['status'];
                unset($_SESSION['error']);

                // Если статус админ - отправляем в админку, иначе на главную
                if ($user['status'] === 'admin') {
                    header("Location: admin");
                } else {
                    header("Location: ./");
                }
                exit();
            } else {
                $_SESSION['error'] = "Vale kasutajanimi või parool / Неверный логин или пароль";
                header("Location: login");
                exit();
            }
        }
    }

    public static function registerForm() {
        global $lang;
        include_once "view/formRegister.php";
    }

    public static function registerProcess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['btnRegister'])) {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $login = trim($_POST['login'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Проверка на заполненность
            if (empty($username) || empty($email) || empty($login) || empty($password)) {
                $_SESSION['error'] = "Kõik väljad on kohustuslikud / Все поля обязательны к заполнению";
                header("Location: register");
                exit();
            }

            // Валидация формата email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Vigane e-posti aadress / Некорректный email";
                header("Location: register");
                exit();
            }

            // Проверка минимальной длины пароля
            if (strlen($password) < 4) {
                $_SESSION['error'] = "Parool peab olema vähemalt 4 tähemärki / Пароль должен быть не менее 4 символов";
                header("Location: register");
                exit();
            }

            // Проверка на дубликат логина или email
            if (ModelUser::isUserExists($login, $email)) {
                $_SESSION['error'] = "Kasutajanimi või e-post on juba kasutusel / Этот логин или email уже занят";
                header("Location: register");
                exit();
            }

            $result = ModelUser::registerUser($username, $email, $login, $password);
            if ($result) {
                unset($_SESSION['error']);
                $_SESSION['success'] = "Registreerimine õnnestus! Logige sisse. / Вы успешно зарегистрировались! Войдите в систему.";
                header("Location: login");
                exit();
            } else {
                $_SESSION['error'] = "Viga registreerumisel / Ошибка при регистрации";
                header("Location: register");
                exit();
            }
        }
    }

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: ./");
        exit();
    }
}
?>