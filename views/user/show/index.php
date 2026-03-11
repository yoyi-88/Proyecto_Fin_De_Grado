<div class="container">
    <br><br><br><br>

    <?php require_once("template/partials/mensaje.partial.php") ?>
    <?php require_once("template/partials/error.partial.php") ?>

    <main>
        <legend><?= $this->title ?></legend>
        <form>

            <div class="mb-3">
                <label for="id" class="form-label">ID:</label>
                <input type="text" class="form-control" value="<?= $this->user->id ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" class="form-control" value="<?= $this->user->name ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" class="form-control" value="<?= $this->user->email ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Rol Asignado:</label>
                <input type="text" class="form-control" value="<?= $this->role_name ?>" disabled>
            </div>

            <?php if (isset($this->user->created_at)): ?>
                <div class="mb-3">
                    <label class="form-label">Fecha de Creación:</label>
                    <input type="text" class="form-control"
                        value="<?= date('d/m/Y H:i:s', strtotime($this->user->created_at)) ?>" disabled>
                </div>
            <?php endif; ?>

            <a class="btn btn-secondary" href="<?= URL ?>user" role="button">Volver a la lista</a>

        </form>
        <br><br><br>
    </main>
</div>