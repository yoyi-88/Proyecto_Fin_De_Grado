<div class="container py-5 mt-5">
    <div class="card border-danger shadow">
        <div class="card-header bg-danger text-white fw-bold">
            <i class="bi bi-bug-fill me-2"></i> EXCEPCIÓN CRÍTICA DE BASE DE DATOS
        </div>
        <div class="card-body bg-light">
            <h5 class="text-danger mb-3"><?= htmlspecialchars($e->getMessage()) ?></h5>
            <ul class="list-group list-group-flush mb-3 font-monospace small">
                <li class="list-group-item bg-transparent"><strong>Código:</strong> <?= htmlspecialchars($e->getCode()) ?></li>
                <li class="list-group-item bg-transparent"><strong>Archivo:</strong> <?= htmlspecialchars($e->getFile()) ?></li>
                <li class="list-group-item bg-transparent"><strong>Línea:</strong> <?= htmlspecialchars($e->getLine()) ?></li>
            </ul>
            <div class="p-3 bg-dark text-white-50 rounded font-monospace small" style="max-height: 200px; overflow-y: auto;">
                <?= nl2br(htmlspecialchars($e->getTraceAsString())) ?>
            </div>
        </div>
    </div>
</div>