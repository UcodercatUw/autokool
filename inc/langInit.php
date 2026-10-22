<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Проверяем, переключил ли пользователь язык через URL (?lang=ru, ?lang=et или ?lang=en)
if (isset($_GET['lang'])) {
    $langChoice = strtolower($_GET['lang']);
    
    if (in_array($langChoice, ['et', 'ru', 'en'])) {
        $_SESSION['lang'] = $langChoice;
    }
}

// 2. Язык по умолчанию — эстонский
$currentLang = $_SESSION['lang'] ?? 'et';

// 3. Подключаем нужный языковой файл
$langFilePath = __DIR__ . "/../lang/{$currentLang}.php";

if (file_exists($langFilePath)) {
    $lang = require $langFilePath;
} else {
    // Резервный вариант, если файла нет
    $lang = require __DIR__ . "/../lang/et.php";
}
?>