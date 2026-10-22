<?php
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host, '/');
$uri = explode('/', $host);
$path = $uri[$num];

// Проверка админ-маршрутов
if ($path == 'admin' || $path == 'admin/') {
    // Проверка прав администратора
    if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
        header("Location: login");
        exit();
    }

    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        case 'add':
            ControllerAdminServices::addServiceForm();
            break;
        case 'addProcess':
            ControllerAdminServices::addServiceProcess();
            break;
        case 'edit':
            $id = $_GET['id'] ?? 0;
            ControllerAdminServices::editServiceForm($id);
            break;
        case 'editProcess':
            ControllerAdminServices::editServiceProcess();
            break;
        case 'delete':
            $id = $_GET['id'] ?? 0;
            ControllerAdminServices::deleteServiceProcess($id);
            break;
        default:
            ControllerAdminServices::listServices();
            break;
    }
} 
// Публичные маршруты
elseif ($path == '' || $path == 'index' || $path == 'index.php') {
    ControllerServices::startPage();
} elseif ($path == 'service' && isset($_GET['id'])) {
    ControllerServices::serviceRead($_GET['id']);
} elseif ($path == 'enroll') {
    ControllerServices::enrollProcess();
} elseif ($path == 'unenroll') {
    ControllerServices::unenrollProcess();
} elseif ($path == 'login') {
    ControllerUser::loginForm();
} elseif ($path == 'loginProcess') {
    ControllerUser::loginProcess();
} elseif ($path == 'register') {
    ControllerUser::registerForm();
} elseif ($path == 'registerProcess') {
    ControllerUser::registerProcess();
} elseif ($path == 'logout') {
    ControllerUser::logout();
} else {
    ControllerServices::startPage();
}
?>