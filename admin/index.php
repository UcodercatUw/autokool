<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверка доступа (только для админа)
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login");
    exit();
}

// Подключаем инициализацию языка (перевод ET/RU)
require_once __DIR__ . '/../inc/langInit.php';

// Подключаем базу данных из папки admin/config/database.php
require_once __DIR__ . '/config/database.php';

// Подключаем модель, контроллер и маршруты с учетом вашей структуры папок
require_once __DIR__ . '/modelAdmin/ModelAdminServices.php';
require_once __DIR__ . '/controllerAdmin/ControllerAdminServices.php';
require_once __DIR__ . '/routeAdmin/routeAdmin.php';
?>