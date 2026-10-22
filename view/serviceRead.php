<?php 
ob_start(); 
$currentLang = $_SESSION['lang'] ?? 'et';
$isLoggedIn = isset($_SESSION['userId']);
$userId = $_SESSION['userId'] ?? 0;

// Проверяем, записан ли текущий пользователь на этот курс
$isAlreadyEnrolled = false;
if ($isLoggedIn && isset($service['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $checkQuery = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $checkQuery->execute([$userId, $service['id']]);
    if ($checkQuery->rowCount() > 0) {
        $isAlreadyEnrolled = true;
    }
}
?>

<?php if ($service): ?>
    <div class="card shadow-sm p-4">
        <?php if (!empty($service['image']) && file_exists("public/images/" . $service['image'])): ?>
            <img src="public/images/<?php echo htmlspecialchars($service['image']); ?>" 
                 class="img-fluid rounded mb-3" 
                 style="max-height: 350px; width: 100%; object-fit: cover;" 
                 alt="<?php echo htmlspecialchars($service['title'] ?? ''); ?>">
        <?php endif; ?>

        <span class="badge bg-primary w-auto me-auto mb-2">
            <?php echo htmlspecialchars($service['category_name'] ?? ''); ?>
        </span>

        <h2><?php echo htmlspecialchars($service['title'] ?? ''); ?></h2>

        <?php if (!empty($service['schedule_days'])): ?>
            <p class="text-muted my-2">
                📅 <strong><?php echo $currentLang === 'ru' ? 'Дни проведения занятий:' : ($currentLang === 'en' ? 'Schedule days:' : 'Toimumise päevad:'); ?></strong> 
                <span class="text-dark fw-bold"><?php echo htmlspecialchars($service['schedule_days']); ?></span>
            </p>
        <?php endif; ?>

        <h4 class="text-success my-3">
            <?php echo $lang['price'] ?? 'Hind'; ?>: €<?php echo htmlspecialchars($service['price'] ?? '0'); ?>
        </h4>

        <p class="lead">
            <?php echo nl2br(htmlspecialchars($service['description'] ?? '')); ?>
        </p>

        <!-- Блок управления записью с кнопкой отмены -->
        <div class="card bg-light border-0 p-3 my-4">
            <?php if (($_SESSION['status'] ?? '') === 'admin'): ?>
                <p class="mb-0 text-muted text-center fst-italic">
                    <?php echo $currentLang === 'ru' ? 'Администраторам не нужно записываться на курсы.' : 'Adminid ei pea kursustele registreeruma.'; ?>
                </p>
            <?php elseif (!$isLoggedIn): ?>
                <p class="mb-2 text-muted"><?php echo $lang['login_to_enroll'] ?? 'Kursusele registreerumiseks palun logige sisse.'; ?></p>
                <div>
                    <a href="login" class="btn btn-primary rounded-pill px-4">
                        <?php echo $lang['login'] ?? 'Logi sisse'; ?>
                    </a>
                </div>
            <?php elseif ($isAlreadyEnrolled): ?>
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 alert alert-success mb-0 py-2 px-3 rounded-pill">
                    <span>✅ <?php echo $lang['already_enrolled'] ?? 'Olete sellele kursusele juba registreeritud'; ?></span>
                    <form action="unenroll" method="POST" class="mb-0">
                        <input type="hidden" name="course_id" value="<?php echo $service['id']; ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4">
                            <?php echo $lang['unenroll_btn'] ?? ($currentLang === 'ru' ? 'Отменить запись' : 'Loobu'); ?>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <form action="enroll" method="POST" class="d-flex align-items-center">
                    <input type="hidden" name="course_id" value="<?php echo $service['id']; ?>">
                    <button type="submit" class="btn btn-success px-4 rounded-pill">
                        <?php echo $lang['enroll_btn'] ?? 'Registreeru kursusele'; ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <a href="./" class="btn btn-secondary rounded-pill">
                <?php echo $lang['back'] ?? 'Tagasi'; ?>
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning text-center">
        <h2><?php echo $lang['course_not_found'] ?? 'Kursust ei leitud'; ?></h2>
        <a href="./" class="btn btn-primary mt-3">
            <?php echo $lang['back'] ?? 'Tagasi'; ?>
        </a>
    </div>
<?php endif; ?>

<?php 
$content = ob_get_clean(); 
include "view/templates/layout.php"; 
?>