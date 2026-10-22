<?php
$action = $_GET['action'] ?? $_GET['page'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

switch ($action) {
    case 'add':
        ControllerAdminServices::addServiceForm();
        break;

    case 'addResult':
    case 'addProcess':
        ControllerAdminServices::addServiceProcess();
        break;

    case 'edit':
        ControllerAdminServices::editServiceForm($id);
        break;

    case 'editResult':
    case 'editProcess':
        ControllerAdminServices::editServiceProcess();
        break;

    case 'delete':
        ControllerAdminServices::deleteServiceProcess($id);
        break;

    case 'list':
    case 'services':
    default:
        ControllerAdminServices::listServices();
        break;
}
?>