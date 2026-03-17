<?php if (isset($this->mensaje)): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-3 text-success"></i>
        <div>
            <?= $this->mensaje; ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>