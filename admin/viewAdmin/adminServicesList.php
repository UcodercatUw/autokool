<?php 
global $lang;
$currentLang = $_SESSION['lang'] ?? $_GET['lang'] ?? 'et';
$pageTitle = $lang['admin_courses_title'] ?? 'Kursuste haldamine';
ob_start(); 
?>

<div class="py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1"><?php echo htmlspecialchars($lang['admin_courses_title'] ?? 'Kursuste haldamine'); ?></h1>
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">
                <?php echo htmlspecialchars($lang['admin_role'] ?? 'Administraator'); ?>
            </span>
        </div>

        <a href="?action=add&lang=<?php echo $currentLang; ?>" class="btn btn-success rounded-pill px-4 fw-medium shadow-sm">
            + <?php echo htmlspecialchars($lang['admin_add_course'] ?? 'Lisa uus kursus'); ?>
        </a>
    </div>

    <!-- Таблица курсов -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="ps-4"><?php echo htmlspecialchars($lang['photo'] ?? 'Pilt'); ?></th>
                        <th><?php echo htmlspecialchars($lang['title'] ?? 'Pealkiri'); ?></th>
                        <th><?php echo htmlspecialchars($lang['category'] ?? 'Kategooria'); ?></th>
                        <th><?php echo htmlspecialchars($lang['price'] ?? 'Hind'); ?></th>
                        <th style="width: 120px;"><?php echo htmlspecialchars($lang['status'] ?? 'Olek'); ?></th>
                        <th style="width: 180px;" class="text-end pe-4"><?php echo htmlspecialchars($lang['actions'] ?? 'Tegevused'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $row): ?>
                            <?php 
                                $id = (int)($row['id'] ?? 0);
                                $name = $row['name'] ?? $row['title'] ?? '';
                                $category = $row['category_name'] ?? '—';
                                $price = isset($row['price']) ? number_format((float)$row['price'], 2, '.', ' ') . ' €' : '0.00 €';
                                $isActive = isset($row['is_active']) ? (int)$row['is_active'] : 1;
                                $image = $row['image'] ?? $row['photo'] ?? '';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if (!empty($image) && file_exists(__DIR__ . '/../../public/images/' . $image)): ?>
                                        <img src="../public/images/<?php echo htmlspecialchars($image); ?>" alt="" style="width: 55px; height: 38px; object-fit: cover; border-radius: 6px;">
                                    <?php else: ?>
                                        <div style="width: 55px; height: 38px; border-radius: 6px;" class="d-flex align-items-center justify-content-center bg-secondary-subtle">
                                            <span class="small">🚗</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="fw-semibold text-body"><?php echo htmlspecialchars($name); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-body border">
                                        <?php echo htmlspecialchars($category); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success"><?php echo htmlspecialchars($price); ?></span>
                                </td>
                                <td>
                                    <?php if ($isActive === 1): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <?php echo htmlspecialchars($lang['active'] ?? 'Aktiivne'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <?php echo htmlspecialchars($lang['hidden'] ?? 'Peidetud'); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="?action=edit&id=<?php echo $id; ?>&lang=<?php echo $currentLang; ?>" class="btn btn-outline-primary">
                                            <?php echo htmlspecialchars($lang['edit'] ?? 'Muuda'); ?>
                                        </a>
                                        <a href="?action=delete&id=<?php echo $id; ?>&lang=<?php echo $currentLang; ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('<?php echo htmlspecialchars(addslashes($lang['confirm_delete'] ?? 'Kustuta?')); ?>')">
                                            <?php echo htmlspecialchars($lang['delete'] ?? 'Kustuta'); ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <?php echo htmlspecialchars($lang['no_courses'] ?? 'Kursusi ei leitud.'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include __DIR__ . '/templates/adminLayout.php'; 
?>