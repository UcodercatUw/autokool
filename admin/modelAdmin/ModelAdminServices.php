<?php
class ModelAdminServices {

    public static function getServicesList($onlyActive = false) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return [];

        $query = "SELECT services.*, categories.name AS category_name 
                  FROM services 
                  LEFT JOIN categories ON services.category_id = categories.id";
                  
        if ($onlyActive) {
            $query .= " WHERE services.is_active = 1";
        }
        
        // Сортировка по title в алфавитном порядке (A -> Z)
        $query .= " ORDER BY services.title ASC";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getServiceById($id, $onlyActive = false) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        $query = "SELECT services.*, categories.name AS category_name 
                  FROM services 
                  LEFT JOIN categories ON services.category_id = categories.id 
                  WHERE services.id = :id";

        if ($onlyActive) {
            $query .= " AND services.is_active = 1";
        }
                  
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getCategories() {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return [];

        $stmt = $db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addService($title, $description, $price, $categoryId, $isActive, $image = '') {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        $query = "INSERT INTO services (title, description, price, category_id, is_active, image) 
                  VALUES (:title, :description, :price, :category_id, :is_active, :image)";
        $stmt = $db->prepare($query);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':category_id' => $categoryId,
            ':is_active' => $isActive,
            ':image' => $image
        ]);
    }

    public static function updateService($id, $title, $description, $price, $categoryId, $isActive, $image = null) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        if ($image !== null) {
            $query = "UPDATE services 
                      SET title = :title, description = :description, price = :price, 
                          category_id = :category_id, is_active = :is_active, image = :image 
                      WHERE id = :id";
            $params = [
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':price' => $price,
                ':category_id' => $categoryId,
                ':is_active' => $isActive,
                ':image' => $image
            ];
        } else {
            $query = "UPDATE services 
                      SET title = :title, description = :description, price = :price, 
                          category_id = :category_id, is_active = :is_active 
                      WHERE id = :id";
            $params = [
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':price' => $price,
                ':category_id' => $categoryId,
                ':is_active' => $isActive
            ];
        }

        $stmt = $db->prepare($query);
        return $stmt->execute($params);
    }

    public static function deleteService($id) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;

        $stmt = $db->prepare("DELETE FROM services WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>