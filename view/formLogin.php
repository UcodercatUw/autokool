<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 shadow-sm">
            <h3 class="mb-3 text-center"><?php echo $lang['login'] ?? 'Sisene'; ?></h3>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="loginProcess" method="POST">
                <div class="mb-3">
                    <label class="form-label"><?php echo $lang['username'] ?? 'Kasutajanimi'; ?>:</label>
                    <input type="text" name="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?php echo $lang['password'] ?? 'Parool'; ?>:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="btnLogin" class="btn btn-primary w-100"><?php echo $lang['login'] ?? 'Sisene'; ?></button>
            </form>
            <div class="mt-3 text-center">
                <a href="register"><?php echo $lang['register'] ?? 'Registreeru'; ?></a>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include "view/templates/layout.php"; 
?>