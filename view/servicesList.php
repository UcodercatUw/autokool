<?php 
$currentLang = $_SESSION['lang'] ?? 'et';
$pageTitle = $lang['menu_courses'] ?? 'Kursused';
$isAdmin = isset($_SESSION['status']) && $_SESSION['status'] === 'admin';
$userId = $_SESSION['userId'] ?? null;

if (!isset($enrolledCourseIds)) {
    $enrolledCourseIds = ModelServices::getUserEnrolledCourseIds($userId);
}

ob_start(); 
?>

<div class="py-3">

    <!-- Приветственный баннер с поиском -->
    <div class="p-4 p-md-5 mb-4 rounded-4 shadow-sm" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-6 fw-bold mb-2"><?php echo htmlspecialchars($lang['site_title'] ?? 'Autokool'); ?></h1>
                <p class="text-body-secondary mb-3">
                    <?php 
                    if ($currentLang === 'ru') {
                        echo 'Выберите подходящую категорию и начните обучение с лучшими инструкторами.';
                    } elseif ($currentLang === 'en') {
                        echo 'Choose the right category and start your driving journey today.';
                    } else {
                        echo 'Vali sobiv kategooria ja alusta sõiduõpet parimate instruktoritega.';
                    }
                    ?>
                </p>
                
                <div class="input-group mb-2">
                    <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-color);">🔍</span>
                    <input type="text" id="courseSearch" class="form-control border-start-0" 
                           placeholder="<?php echo $currentLang === 'ru' ? 'Поиск курса (например: B-kategooria)...' : ($currentLang === 'en' ? 'Search course...' : 'Otsi kursust...'); ?>">
                </div>
            </div>

            <div class="col-lg-5 mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex align-items-center gap-2 text-body-secondary">
                        <span class="badge bg-success-subtle text-success p-2 rounded-circle">✓</span>
                        <span><?php echo $currentLang === 'ru' ? 'Новые учебные автомобили' : ($currentLang === 'en' ? 'Modern vehicles' : 'Kaasaegsed õppeautod'); ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-body-secondary">
                        <span class="badge bg-success-subtle text-success p-2 rounded-circle">✓</span>
                        <span><?php echo $currentLang === 'ru' ? 'Гибкий график занятий' : ($currentLang === 'en' ? 'Flexible schedule' : 'Paindlik tunniplaan'); ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-body-secondary">
                        <span class="badge bg-success-subtle text-success p-2 rounded-circle">✓</span>
                        <span><?php echo $currentLang === 'ru' ? 'Высокий процент сдачи экзаменов' : ($currentLang === 'en' ? 'High exam pass rate' : 'Kõrge eksamite läbivus'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Уведомления -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Счетчик курсов -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0"><?php echo htmlspecialchars($lang['menu_courses'] ?? 'Kursused'); ?></h2>
        <span class="text-body-secondary small" id="coursesCount">
            <?php echo count($services); ?> <?php echo $currentLang === 'ru' ? 'курсов' : ($currentLang === 'en' ? 'courses' : 'kursust'); ?>
        </span>
    </div>

    <!-- Карточки курсов -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="coursesGrid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <?php 
                    $serviceId = (int)($service['id'] ?? 0);
                    $title = $service['title'] ?? $service['name'] ?? '';
                    $description = $service['description'] ?? '';
                    $category = $service['category_name'] ?? '';
                    $price = isset($service['price']) ? number_format((float)$service['price'], 2, '.', ' ') . ' €' : '';
                    $image = $service['image'] ?? $service['photo'] ?? '';
                    $schedule = $service['schedule_days'] ?? '';
                    
                    $isEnrolled = in_array($serviceId, $enrolledCourseIds);
                ?>
                <div class="col course-item" data-title="<?php echo htmlspecialchars(mb_strtolower($title)); ?>" data-category="<?php echo htmlspecialchars(mb_strtolower($category)); ?>">
                    <div class="card h-100 course-card shadow-sm position-relative">
                        
                        <!-- Бейджи категории и отметки записи -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1" style="z-index: 2;">
                            <?php if (!empty($category)): ?>
                                <span class="course-badge position-static"><?php echo htmlspecialchars($category); ?></span>
                            <?php endif; ?>

                            <!-- Плашка видна только студентам (не админу) -->
                            <?php if ($isEnrolled && !$isAdmin): ?>
                                <span class="badge bg-success text-white shadow-sm mt-1 px-2 py-1 rounded-pill small">
                                    ✓ <?php echo $currentLang === 'ru' ? 'Вы записаны' : ($currentLang === 'en' ? 'Enrolled' : 'Registreeritud'); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Изображение курса -->
                        <div class="course-card-img-wrapper">
                            <?php if (!empty($image) && file_exists(__DIR__ . '/../public/images/' . $image)): ?>
                                <img src="public/images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <span class="display-6">🚗</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Тело карточки -->
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title mb-2"><?php echo htmlspecialchars($title); ?></h5>
                            
                            <?php if (!empty($schedule)): ?>
                                <div class="small text-body-secondary mb-2 d-flex align-items-center gap-1">
                                    <span>📅</span>
                                    <span><strong><?php echo htmlspecialchars($lang['schedule'] ?? 'Päevad'); ?>:</strong> <?php echo htmlspecialchars($schedule); ?></span>
                                </div>
                            <?php endif; ?>

                            <p class="card-text text-body-secondary small flex-grow-1">
                                <?php echo nl2br(htmlspecialchars($description)); ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <span class="h5 mb-0 fw-bold text-success"><?php echo htmlspecialchars($price); ?></span>
                                
                                <div class="btn-group">
                                    <a href="service?id=<?php echo $serviceId; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <?php echo htmlspecialchars($lang['read_more'] ?? 'Loe lähemalt'); ?>
                                    </a>

                                    <?php if ($isEnrolled): ?>
                                        <!-- Если записан (даже админ случайно) — даем кнопку отмены -->
                                        <form action="unenroll" method="POST" class="m-0 ms-1">
                                            <input type="hidden" name="course_id" value="<?php echo $serviceId; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                    onclick="return confirm('<?php echo htmlspecialchars($lang['unenroll_btn'] ?? 'Loobu kursusest?'); ?>')">
                                                ✕ <?php echo htmlspecialchars($lang['unenroll_btn'] ?? 'Loobu'); ?>
                                            </button>
                                        </form>
                                    <?php elseif (!$isAdmin): ?>
                                        <!-- Кнопка записи ТОЛЬКО для студентов -->
                                        <form action="enroll" method="POST" class="m-0 ms-1">
                                            <input type="hidden" name="course_id" value="<?php echo $serviceId; ?>">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <?php echo htmlspecialchars($lang['enroll_btn'] ?? 'Registreeru'); ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-muted text-center py-5"><?php echo htmlspecialchars($lang['no_courses'] ?? 'Kursusi ei leitud.'); ?></p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('courseSearch');
    const courseItems = document.querySelectorAll('.course-item');
    const countDisplay = document.getElementById('coursesCount');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            courseItems.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const category = item.getAttribute('data-category') || '';

                if (title.includes(query) || category.includes(query)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (countDisplay) {
                countDisplay.textContent = visibleCount + ' <?php echo $currentLang === 'ru' ? 'найдено' : ($currentLang === 'en' ? 'found' : 'leitud'); ?>';
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/templates/layout.php'; 
?>