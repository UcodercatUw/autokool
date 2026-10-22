<?php
class ControllerServices {

    public static function startPage() {
        global $lang;
        if (!isset($lang)) {
            @include "inc/langInit.php";
        }
        
        $userId = $_SESSION['userId'] ?? null;
        $enrolledCourseIds = ModelServices::getUserEnrolledCourseIds($userId);
        
        $services = ModelServices::getServicesList();
        include "view/servicesList.php";
    }

    public static function serviceRead($id) {
        global $lang;
        if (!isset($lang)) {
            @include "inc/langInit.php";
        }

        $id = (int)$id;
        $userId = $_SESSION['userId'] ?? null;
        $enrolledCourseIds = ModelServices::getUserEnrolledCourseIds($userId);

        $service = ModelServices::getServiceById($id);
        include "view/serviceRead.php";
    }

    public static function enrollProcess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Запрещаем администратору записываться на курсы
        if (isset($_SESSION['status']) && $_SESSION['status'] === 'admin') {
            $_SESSION['error'] = "Administraator ei saa kursusele registreeruda / Администратор не может записаться на курс.";
            header("Location: ./");
            exit();
        }

        $userId = $_SESSION['userId'] ?? null;
        $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

        if (!$userId) {
            $_SESSION['error'] = "Kursusele registreerumiseks palun logige sisse / Войдите для записи.";
            header("Location: login");
            exit();
        }

        if (!$courseId) {
            header("Location: ./");
            exit();
        }

        $result = ModelServices::enrollUser($userId, $courseId);

        if ($result === 'success') {
            $_SESSION['success'] = "Edukalt registreeritud! / Вы успешно записались на курс!";
        } elseif ($result === 'already_enrolled') {
            $_SESSION['error'] = "Olete juba registreeritud / Вы уже записаны на этот курс.";
        } else {
            $_SESSION['error'] = "Viga registreerumisel / Ошибка записи.";
        }

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? './'));
        exit();
    }

    public static function unenrollProcess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['userId'] ?? null;
        $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;

        if (!$userId || !$courseId) {
            header("Location: ./");
            exit();
        }

        $result = ModelServices::unenrollUser($userId, $courseId);

        if ($result === 'success') {
            $_SESSION['success'] = "Registreerumine tühistatud / Запись успешно отменена.";
        } else {
            $_SESSION['error'] = "Viga tühistamisel / Ошибка при отмене записи.";
        }

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? './'));
        exit();
    }
}
?>