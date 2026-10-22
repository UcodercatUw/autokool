<?php
class ModelServices {

    public static function getServicesList() {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return [];
        
        $query = "SELECT services.*, categories.name AS category_name 
                  FROM services 
                  LEFT JOIN categories ON services.category_id = categories.id 
                  WHERE services.is_active = 1 
                  ORDER BY services.title ASC";
                  
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getServiceById($id) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return false;
        
        $query = "SELECT services.*, categories.name AS category_name 
                  FROM services 
                  LEFT JOIN categories ON services.category_id = categories.id 
                  WHERE services.id = :id AND services.is_active = 1";
                  
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Получить ID курсов, на которые уже записан пользователь
     */
    public static function getUserEnrolledCourseIds($userId) {
        if (!$userId) return [];
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return [];

        $stmt = $db->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Массив вида [1, 4, 7]
    }

    public static function enrollUser($userId, $courseId) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return 'error';

        $service = self::getServiceById($courseId);
        if (!$service) return 'not_found';

        $check = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $check->execute([$userId, $courseId]);
        if ($check->rowCount() > 0) {
            return 'already_enrolled';
        }

        $stmt = $db->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'enrolled')");
        return $stmt->execute([$userId, $courseId]) ? 'success' : 'error';
    }

    public static function unenrollUser($userId, $courseId) {
        $database = new Database();
        $db = $database->getConnection();
        if (!$db) return 'error';

        $stmt = $db->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ?");
        return $stmt->execute([$userId, $courseId]) ? 'success' : 'error';
    }
}
?>