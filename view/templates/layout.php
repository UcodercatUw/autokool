<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'et'; ?>" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php 
    if (!empty($pageTitle)) {
        echo htmlspecialchars($pageTitle) . ' | ' . ($lang['site_title'] ?? 'Autokool');
    } else {
        echo $lang['site_title'] ?? 'Autokool - Sõiduõpe';
    }
?></title>

    <!-- Скрипт предотвращения мигания темы при загрузке -->
    <script>
    (function() {
        const savedTheme = localStorage.getItem('theme');
        
        if (savedTheme) {
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        } else {
            const currentHour = new Date().getHours();
            const timeBasedTheme = (currentHour >= 6 && currentHour < 18) ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', timeBasedTheme);
        }
    })();
</script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Наш единый style.css -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php 
    $currentLang = $_SESSION['lang'] ?? 'et';
    
    if ($currentLang === 'ru') {
        $welcomeText = $lang['greeting'] ?? $lang['welcome'] ?? 'Привет';
        $logoutText = $lang['logout'] ?? 'Выйти';
        $loginText = $lang['login'] ?? 'Войти';
    } elseif ($currentLang === 'en') {
        $welcomeText = $lang['greeting'] ?? $lang['welcome'] ?? 'Welcome';
        $logoutText = $lang['logout'] ?? 'Log out';
        $loginText = $lang['login'] ?? 'Log in';
    } else {
        $welcomeText = $lang['greeting'] ?? $lang['welcome'] ?? 'Tere';
        $logoutText = $lang['logout'] ?? 'Välju';
        $loginText = $lang['login'] ?? 'Sisene';
    }

    $currentUri = $_SERVER['REQUEST_URI'];
    $cleanUri = preg_replace('/([?&])lang=[a-z]+(&|$)/', '$1', $currentUri);
    $cleanUri = rtrim($cleanUri, '?&');
    $separator = (parse_url($cleanUri, PHP_URL_QUERY) === null) ? '?' : '&';
    ?>

    <!-- Навбар публичной части -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="./">
                <?php echo $lang['brand_name'] ?? $lang['site_title'] ?? 'Autokool'; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="./"><?php echo $lang['menu_courses'] ?? 'Kursused'; ?></a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Переключатель языков (ET / RU / EN) -->
                    <div class="btn-group btn-group-sm" role="group" aria-label="Language selector">
                        <a href="<?php echo $cleanUri . $separator; ?>lang=et" class="btn btn-outline-primary <?php echo $currentLang === 'et' ? 'active' : ''; ?>">ET</a>
                        <a href="<?php echo $cleanUri . $separator; ?>lang=ru" class="btn btn-outline-primary <?php echo $currentLang === 'ru' ? 'active' : ''; ?>">RU</a>
                        <a href="<?php echo $cleanUri . $separator; ?>lang=en" class="btn btn-outline-primary <?php echo $currentLang === 'en' ? 'active' : ''; ?>">EN</a>
                    </div>

                    <!-- Кнопка смены темы -->
                    <button class="theme-toggle-btn" id="themeToggleBtn" type="button" aria-label="Toggle theme">
                        <svg id="themeIconSun" class="d-none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                        <svg id="themeIconMoon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    </button>

                    <!-- Блок авторизованного пользователя / гостя -->
                    <?php if (isset($_SESSION['user']) || isset($_SESSION['username']) || isset($_SESSION['name']) || isset($_SESSION['status'])): ?>
                        
                        <span class="small fw-medium">
                            <?php 
                                $username = $_SESSION['user'] ?? $_SESSION['username'] ?? $_SESSION['name'] ?? 'Kasutaja';
                                echo htmlspecialchars($welcomeText) . ', ' . htmlspecialchars($username) . '!';
                            ?>
                        </span>

                        <?php if (($_SESSION['status'] ?? '') === 'admin'): ?>
                            <a href="admin/" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                <?php echo $lang['admin_panel'] ?? $lang['admin_panel_btn'] ?? 'Admin Panel'; ?>
                            </a>
                        <?php endif; ?>

                        <a href="logout" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-medium">
                            <?php echo htmlspecialchars($logoutText); ?>
                        </a>

                    <?php else: ?>
                        
                        <a href="login" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <?php echo htmlspecialchars($loginText); ?>
                        </a>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Основное содержимое страницы -->
    <main class="container flex-grow-1">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Подвал -->
    <footer class="py-4 text-center">
        <div class="container">
            <small>&copy; <?php echo date('Y'); ?> Autokool. <?php echo $lang['rights_reserved'] ?? 'Kõik õigused kaitstud.'; ?></small>
        </div>
    </footer>

    <!-- Подключение JS библиотек и скриптов -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Подключаем главный файл скриптов -->
    <script src="public/js/main.js"></script>

    <!-- Логика смены темы и иконок -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('themeToggleBtn');
            const iconSun = document.getElementById('themeIconSun');
            const iconMoon = document.getElementById('themeIconMoon');

            function updateIcons(theme) {
                if (theme === 'light') {
                    iconSun.classList.remove('d-none');
                    iconMoon.classList.add('d-none');
                } else {
                    iconSun.classList.add('d-none');
                    iconMoon.classList.remove('d-none');
                }
            }

            let currentTheme = localStorage.getItem('theme');
            if (!currentTheme) {
                const currentHour = new Date().getHours();
                currentTheme = (currentHour >= 6 && currentHour < 18) ? 'light' : 'dark';
            }
            
            updateIcons(currentTheme);

            toggleBtn.addEventListener('click', () => {
                const activeTheme = document.documentElement.getAttribute('data-bs-theme');
                const newTheme = activeTheme === 'dark' ? 'light' : 'dark';

                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateIcons(newTheme);
            });
        });
    </script>
</body>
</html>