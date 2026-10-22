<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h3 class="mb-3 text-center"><?php echo $lang['reg_title'] ?? 'Registreerimine'; ?></h3>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="registerProcess" method="POST">
                <div class="mb-3">
                    <label class="form-label"><?php echo $lang['name'] ?? 'Nimi'; ?>:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?php echo $lang['email'] ?? 'E-post'; ?>:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?php echo $lang['username'] ?? 'Kasutajanimi'; ?>:</label>
                    <input type="text" name="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?php echo $lang['password'] ?? 'Parool'; ?>:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="btnRegister" class="btn btn-success w-100"><?php echo $lang['reg_button'] ?? 'Registreeru'; ?></button>
            </form>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include "view/templates/layout.php"; 
?>