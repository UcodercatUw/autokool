<?php 
global $lang;
$currentLang = $_SESSION['lang'] ?? $_GET['lang'] ?? 'et';
ob_start(); 
?>

<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4 p-md-5">
        <h2 class="card-title mb-4"><?php echo $lang['admin_add_course'] ?? 'Lisa uus kursus'; ?></h2>
        
        <form action="?action=addResult&lang=<?php echo $currentLang; ?>" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="category_id" class="form-label"><?php echo $lang['category'] ?? 'Kategooria'; ?>:</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <?php if (!empty($categories)): foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label"><?php echo $lang['title'] ?? 'Pealkiri'; ?>:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label"><?php echo $lang['description'] ?? 'Kirjeldus'; ?>:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label"><?php echo $lang['price'] ?? 'Hind'; ?> (€):</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
            </div>

            <!-- Добавлено поле для дней проведения -->
            <div class="mb-3">
                <label for="schedule_days" class="form-label"><?php echo $lang['schedule_days'] ?? 'Toimumise päevad / Дни проведения (например: E, K, R / ПН, СР, ПТ)'; ?>:</label>
                <input type="text" name="schedule_days" id="schedule_days" class="form-control" placeholder="E, K, R">
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">
                    <?php echo $lang['status_active_label'] ?? 'Aktiivne (nähtav kodulehel)'; ?>
                </label>
            </div>

            <div class="mb-4">
                <label for="image" class="form-label"><?php echo $lang['photo'] ?? 'Pilt'; ?>:</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between pt-3 border-top border-secondary border-opacity-25">
                <a href="./?lang=<?php echo $currentLang; ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <?php echo $lang['back'] ?? 'Tagasi'; ?>
                </a>
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-medium">
                    <?php echo $lang['save'] ?? 'Salvesta'; ?>
                </button>
            </div>

        </form>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/templates/adminLayout.php'; 
?>