<div class="container py-5">

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-4 col-lg-3 bg-light border-end">
                <?php require_once("views/account/partials/menu.partial.php") ?>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="p-5">
                    <h4 class="mb-4 text-dark font-serif fw-bold">Editar Perfil</h4>

                    <form action="<?= URL ?>account/update" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6 form-group">
                                <label for="name" class="form-label text-muted small fw-bold text-uppercase">Nombre
                                    Completo</label>
                                <input id="name" type="text"
                                    class="form-control form-control-lg bg-light border-0 <?= (isset($this->errors['name'])) ? 'is-invalid' : '' ?>"
                                    name="name" value="<?= htmlspecialchars($this->account->name); ?>" required
                                    autofocus>
                                <small class="text-danger"><?= $this->errors['name'] ?? '' ?></small>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="email" class="form-label text-muted small fw-bold text-uppercase">Correo
                                    Electrónico</label>
                                <input id="email" type="email"
                                    class="form-control form-control-lg bg-light border-0 <?= (isset($this->errors['email'])) ? 'is-invalid' : '' ?>"
                                    name="email" value="<?= htmlspecialchars($this->account->email); ?>" required>
                                <small class="text-danger"><?= $this->errors['email'] ?? '' ?></small>
                            </div>
                        </div>

                        <div class="d-flex gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                            <button type="reset" class="btn btn-link text-muted text-decoration-none">Deshacer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>