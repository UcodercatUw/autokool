<?php
class ControllerAdminServices {

    public static function listServices() {
        global $lang;
        $services = ModelAdminServices::getServicesList(false);
        include __DIR__ . "/../viewAdmin/adminServicesList.php";
    }

    public static function addServiceForm() {
        global $lang;
        $categories = ModelAdminServices::getCategories();
        include __DIR__ . "/../viewAdmin/adminServiceAdd.php";
    }

    public static function addServiceProcess() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $categoryId = (int)($_POST['category_id'] ?? 1);
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            $image = '';

            // Загрузка фото, если передано
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $target = __DIR__ . "/../../public/images/" . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image = $imageName;
                }
            }

            if (!empty($title) && $price >= 0) {
                ModelAdminServices::addService($title, $description, $price, $categoryId, $isActive, $image);
                $_SESSION['success'] = "Kursus lisatud / Курс добавлен!";
            } else {
                $_SESSION['error'] = "Täitke väljad / Заполните поля.";
            }
        }
        header("Location: ./");
        exit();
    }

    public static function editServiceForm($id) {
        global $lang;
        $id = (int)$id;
        $service = ModelAdminServices::getServiceById($id, false);
        $categories = ModelAdminServices::getCategories();
        include __DIR__ . "/../viewAdmin/adminServiceEdit.php";
    }

    public static function editServiceProcess() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $categoryId = (int)($_POST['category_id'] ?? 1);
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            $image = null;

            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $target = __DIR__ . "/../../public/images/" . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image = $imageName;
                }
            }

            if ($id > 0 && !empty($title)) {
                ModelAdminServices::updateService($id, $title, $description, $price, $categoryId, $isActive, $image);
                $_SESSION['success'] = "Kursus uuendatud / Курс обновлен!";
            }
        }
        header("Location: ./");
        exit();
    }

    public static function deleteServiceProcess($id) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id = (int)$id;
        if ($id > 0) {
            ModelAdminServices::deleteService($id);
            $_SESSION['success'] = "Kursus kustutatud / Курс удален!";
        }
        header("Location: ./");
        exit();
    }
}
?>