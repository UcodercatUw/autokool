<?php 
global $lang;
$currentLang = $_SESSION['lang'] ?? $_GET['lang'] ?? 'et';
ob_start(); 
?>

<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4 p-md-5">
        <h2 class="card-title mb-4"><?php echo $lang['admin_edit_course'] ?? 'Muuda kursust'; ?></h2>
        
        <form action="?action=editResult&lang=<?php echo $currentLang; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">

            <div class="mb-3">
                <label for="category_id" class="form-label"><?php echo $lang['category'] ?? 'Kategooria'; ?>:</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $service['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label"><?php echo $lang['title'] ?? 'Pealkiri'; ?>:</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($service['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label"><?php echo $lang['description'] ?? 'Kirjeldus'; ?>:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($service['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label"><?php echo $lang['price'] ?? 'Hind'; ?> (€):</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?php echo htmlspecialchars($service['price']); ?>" required>
            </div>

            <!-- Добавлено поле для редактирования дней проведения -->
            <div class="mb-3">
                <label for="schedule_days" class="form-label"><?php echo $lang['schedule_days'] ?? 'Toimumise päevad / Дни проведения:'; ?>:</label>
                <input type="text" name="schedule_days" id="schedule_days" class="form-control" value="<?php echo htmlspecialchars($service['schedule_days'] ?? ''); ?>" placeholder="E, K, R">
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo ($service['is_active'] ?? 1) == 1 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_active">
                    <?php echo $lang['status_active_label'] ?? 'Aktiivne (nähtav kodulehel)'; ?>
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label d-block"><?php echo $lang['current_image'] ?? 'Praegune pilt'; ?>:</label>
                <?php if (!empty($service['image'])): ?>
                    <img src="../public/images/<?php echo htmlspecialchars($service['image']); ?>" style="max-height: 120px;" class="rounded-3 shadow-sm border border-secondary border-opacity-25 mb-2">
                <?php else: ?>
                    <p class="text-muted small"><?php echo $lang['no_img'] ?? 'Pilt puudub'; ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="image" class="form-label"><?php echo $lang['new_image'] ?? 'Uus pilt'; ?>:</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between pt-3 border-top border-secondary border-opacity-25">
                <a href="./?lang=<?php echo $currentLang; ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <?php echo $lang['back'] ?? 'Tagasi'; ?>
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">
                    <?php echo $lang['update'] ?? 'Uuenda'; ?>
                </button>
            </div>

        </form>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/templates/adminLayout.php'; 
?>