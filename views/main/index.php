<div class="bg-dark text-white py-5 text-center" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1556910103-1c02745a30bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 60vh; display: flex; align-items: center;">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">De Mi Casa a la Tuya</h1>
        <p class="lead mb-4 fs-4">Alta cocina, ingredientes de mercado y experiencias exclusivas sin salir de tu hogar.</p>
        
        <div class="d-flex justify-content-center gap-3">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= URL ?>auth/login" class="btn btn-warning btn-lg px-4 fw-bold">Reservar Ahora</a>
                <a href="<?= URL ?>menu" class="btn btn-outline-light btn-lg px-4">Ver Carta</a>
            <?php else: ?>
                <a href="<?= URL ?>citas/new" class="btn btn-warning btn-lg px-4 fw-bold">Nueva Reserva</a>
                <a href="<?= URL ?>menu" class="btn btn-outline-light btn-lg px-4">Ver Carta</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="p-3">
                <i class="bi bi-calendar-check fs-1 text-warning mb-3"></i>
                <h3>Reserva Fácil</h3>
                <p class="text-muted">Elige el día y la hora. Nosotros nos encargamos de la compra y la preparación.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <i class="bi bi-stars fs-1 text-warning mb-3"></i>
                <h3>Calidad Premium</h3>
                <p class="text-muted">Ingredientes seleccionados personalmente cada mañana en el mercado local.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <i class="bi bi-heart fs-1 text-warning mb-3"></i>
                <h3>Personalizado</h3>
                <p class="text-muted">Adaptamos el menú a alergias, intolerancias y preferencias de tus invitados.</p>
            </div>
        </div>
    </div>
</div>