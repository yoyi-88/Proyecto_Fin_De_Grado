<div class="container form-page">

    <div class="form-card shadow-sm border border-danger">
        <div class="form-card-header bg-danger text-white flex-column align-items-stretch gap-3 pb-0">
            <h5 class="form-card-title"><i class="bi bi-exclamation-triangle"></i> <?= $this->title ?></h5>
            <?php require_once("views/account/partials/menu.partial.php") ?>
        </div>
        
        <div class="form-card-body text-center py-5">
            <h3 class="text-danger mb-4">¿Estás completamente seguro?</h3>
            <p class="text-muted mb-5">Esta acción borrará tu cuenta (<strong><?= htmlspecialchars($this->account->email); ?></strong>) y todos tus datos asociados de nuestra base de datos. Esta acción no se puede deshacer.</p>

            <form action="<?= URL ?>account/delete_confirmed" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-light btn-lg" href="<?= URL ?>account">Mejor no, cancelar</a>
                    <button type="submit" class="btn btn-danger btn-lg fw-bold">Sí, eliminar mi cuenta definitivamente</button>
                </div>
            </form>
        </div>
    </div>
</div>