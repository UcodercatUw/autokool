<?php
class ModelUser {

    /**
     * Проверка пользователя при входе с автообновлением старых паролей
     */
    public static function checkUser($login, $password) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        $query = "SELECT * FROM users WHERE login = :login LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false;
        }

        $passwordCorrect = false;

        // 1. Проверяем современный безопасный хэш
        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            $passwordCorrect = true;
        }
        // 2. Обратная совместимость для старых аккаунтов (открытый пароль или md5)
        elseif (
            (!empty($user['password']) && $user['password'] === $password) ||
            (!empty($user['pass']) && $user['pass'] === $password) ||
            (!empty($user['password']) && $user['password'] === md5($password))
        ) {
            $passwordCorrect = true;

            // Сразу обновляем старый пароль на защищенный хэш (мягкая миграция)
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $db->prepare("UPDATE users SET password = :hash, pass = :hash WHERE id = :id");
            $update->execute([':hash' => $newHash, ':id' => $user['id']]);
        }

        return $passwordCorrect ? $user : false;
    }

    /**
     * Проверка: занят ли логин или email
     */
    public static function isUserExists($login, $email) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        $stmt = $db->prepare("SELECT id FROM users WHERE login = :login OR email = :email LIMIT 1");
        $stmt->execute([':login' => $login, ':email' => $email]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Безопасная регистрация нового пользователя
     */
    public static function registerUser($username, $email, $login, $password) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        // Хэшируем пароль
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // В поля password и pass записываем хэш, чтобы не ломать MySQL-структуру таблицы
        $query = "INSERT INTO users (username, email, login, password, pass, status, registration_date) 
                  VALUES (:username, :email, :login, :password, :pass, 'user', CURDATE())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $hashedPassword); 
        $stmt->bindParam(':pass', $hashedPassword); // Записываем хэш вместо открытого пароля

        return $stmt->execute();
    }
}
?>