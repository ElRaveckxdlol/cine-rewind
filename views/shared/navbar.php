<?php
// Navbar unificado con dropdown de usuario y tema dinámico
require_once __DIR__ . '/../../models/TemaModelo.php';
$tema_actual = TemaModelo::obtenerActivo();
?>
<style>
:root {
    --color-fondo: <?php echo e($tema_actual['color_fondo'] ?? '#e9bd96'); ?>;
    --color-primario: <?php echo e($tema_actual['color_primario'] ?? '#1a1a1a'); ?>;
    --color-acento: <?php echo e($tema_actual['color_acento'] ?? '#f5c518'); ?>;
    --color-acento-hover: <?php echo e($tema_actual['color_acento_hover'] ?? '#ffd700'); ?>;
    --color-texto: <?php echo e($tema_actual['color_texto'] ?? '#333333'); ?>;
    --color-texto-claro: <?php echo e($tema_actual['color_texto_claro'] ?? '#ffffff'); ?>;
}
</style>
<header>
    <nav class="navbar">
        <h1><?php echo SITE_NAME; ?></h1>

        <ul class="nav-menu">
            <li><a href="<?php echo BASE_URL; ?>?ruta=home">Inicio</a></li>
            <li><a href="<?php echo BASE_URL; ?>?ruta=nosotros">Nosotros</a></li>
            <li><a href="<?php echo BASE_URL; ?>?ruta=cartelera">Cartelera</a></li>
            <li><a href="<?php echo BASE_URL; ?>?ruta=contacto">Contacto</a></li>
            <?php if (estaLogueado()): ?>
                <li><a href="<?php echo BASE_URL; ?>?ruta=reservar">Reservar</a></li>
            <?php endif; ?>
        </ul>

        <div class="nav-right">
            <?php if (estaLogueado()): ?>
                <form class="nav-search" method="GET" action="<?php echo BASE_URL; ?>">
                    <input type="hidden" name="ruta" value="buscar">
                    <button type="submit" class="nav-search-btn" aria-label="Buscar">
                        🔍 <span>Buscar</span>
                    </button>
                    <input type="text" 
                           name="q" 
                           class="nav-search-input"
                           placeholder="Buscar películas..."
                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                </form>
            <?php endif; ?>

            <?php if (estaLogueado()): ?>
                <div class="user-dropdown" id="userDropdown">
                    <button type="button" 
                            class="user-dropdown-btn" 
                            onclick="toggleDropdown(event)"
                            aria-haspopup="true" 
                            aria-expanded="false">
                        <span class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?>
                        </span>
                        <span class="user-name">
                            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </span>
                        <span class="user-caret">▾</span>
                    </button>

                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <div class="dropdown-header">
                            <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong>
                            <small><?php echo htmlspecialchars($_SESSION['usuario_email']); ?></small>
                            <span class="badge-rol <?php echo esAdmin() ? 'admin' : 'user'; ?>">
                                <?php echo esAdmin() ? 'Administrador' : 'Usuario'; ?>
                            </span>
                        </div>

                        <a href="<?php echo BASE_URL; ?>?ruta=perfil" class="dropdown-item">
                            Mi perfil
                        </a>
                        <a href="<?php echo BASE_URL; ?>?ruta=historial" class="dropdown-item">
                            Mis entradas
                        </a>

                        <?php if (esAdmin()): ?>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-subtitle">Administración</div>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin" class="dropdown-item">
                                Dashboard
                            </a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_peliculas" class="dropdown-item">
                                Películas
                            </a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones" class="dropdown-item">
                                Funciones
                            </a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_salas" class="dropdown-item">
                                Salas
                            </a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_usuarios" class="dropdown-item">
                                Usuarios
                            </a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_comentarios" class="dropdown-item">
                                Comentarios
                            </a>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_temas" class="dropdown-item">
                                Temas
                            </a>
                        <?php endif; ?>

                        <div class="dropdown-divider"></div>
                        <a href="<?php echo BASE_URL; ?>?ruta=logout" class="dropdown-item logout">
                            Cerrar sesión
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="button nav-user-btn">
                    <a href="<?php echo BASE_URL; ?>?ruta=login">
                        <p>Iniciar Sesión</p>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
function toggleDropdown(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    const menu = document.getElementById('userDropdownMenu');
    const btn = dropdown.querySelector('.user-dropdown-btn');
    const abierto = dropdown.classList.toggle('open');
    btn.setAttribute('aria-expanded', abierto);
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        dropdown.classList.remove('open');
        const btn = dropdown.querySelector('.user-dropdown-btn');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.classList.remove('open');
    }
});
</script>