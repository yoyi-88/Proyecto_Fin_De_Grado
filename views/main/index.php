<section class="hero-section">
    <div class="container text-center">
        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">De Mi Casa a la Tuya</h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">Alta cocina, ingredientes de mercado y experiencias exclusivas sin salir de tu hogar.</p>
        
        <div class="hero-actions d-flex justify-content-center gap-3">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= URL ?>auth/login" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm callToAction" data-aos="fade-up" data-aos-delay="300">Reservar Ahora</a>
                <a href="<?= URL ?>menu" class="btn btn-outline-light btn-lg px-4" data-aos="fade-up" data-aos-delay="300">Ver Carta</a>
            <?php else: ?>
                <a href="<?= URL ?>citas/new" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm callToAction" data-aos="fade-up" data-aos-delay="300">Nueva Reserva</a>
                <a href="<?= URL ?>menu" class="btn btn-outline-light btn-lg px-4" data-aos="fade-up" data-aos-delay="300">Ver Carta</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="features-section container py-5 my-4">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="feature-box">
                <i class="bi bi-calendar-check feature-icon" data-aos="fade-right" data-aos-delay="300"></i>
                <h3 class="feature-title" data-aos="fade-right" data-aos-delay="300">Reserva Fácil</h3>
                <p class="text-muted" data-aos="fade-right" data-aos-delay="300">Elige el día y la hora. Nosotros nos encargamos de la compra, la preparación y la limpieza.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <i class="bi bi-stars feature-icon" data-aos="zoom-in" data-aos-delay="300"></i>
                <h3 class="feature-title" data-aos="zoom-in" data-aos-delay="300">Calidad Premium</h3>
                <p class="text-muted" data-aos="zoom-in" data-aos-delay="300">Ingredientes seleccionados personalmente cada mañana en el mercado local para garantizar su frescura.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <i class="bi bi-heart feature-icon" data-aos="fade-left" data-aos-delay="300"></i>
                <h3 class="feature-title" data-aos="fade-left" data-aos-delay="300">Menú Personalizado</h3>
                <p class="text-muted" data-aos="fade-left" data-aos-delay="300">Adaptamos cada plato a las alergias, intolerancias y preferencias gastronómicas de tus invitados.</p>
            </div>
        </div>
    </div>
</section>

<section class="chef-section bg-white py-5">
    <div class="container my-4">
        <div class="row align-items-center shadow-sm rounded-4 overflow-hidden chef-card">
            
            <div class="col-md-5 p-0">
                <img src="public/images/img-cocinero.jpeg" 
                     alt="Retrato del Chef" 
                     class="chef-portrait">
            </div>

            <div class="col-md-7 p-5">
                <span class="badge bg-light text-primary border border-primary mb-3 px-3 py-2 letter-spacing-1">SOBRE MÍ</span>
                <h2 class="chef-name mb-4">Rafael Gómez Albela</h2>
                
                <p class="text-muted lead fs-6 mb-4">
                    Tras haber trabajado en numerosas cocinas de diferentes estilos, decidi dar el salto a este proyecto para que puedas degustar la alta gastronomía desde la comodidad de tu casa.
                </p>

                <ul class="chef-list list-unstyled mb-4">
                    <li class="mb-3"><i class="bi bi-check2-circle text-primary me-2 fs-5 align-middle"></i> Graduado en la escuela de hostelería de Cádiz.</li>
                    <li class="mb-3"><i class="bi bi-check2-circle text-primary me-2 fs-5 align-middle"></i> Especialista en cocina fusión y técnicas de vanguardia.</li>
                    <li class="mb-3"><i class="bi bi-check2-circle text-primary me-2 fs-5 align-middle"></i> Especialista en cocina tradicional.</li>
                </ul>

                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Firma_de_ejemplo.svg" alt="Firma del Chef" class="chef-signature opacity-50" style="max-height: 60px;">
            </div>

        </div>
    </div>
</section>