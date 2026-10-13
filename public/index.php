<?php
// Front Controller - Punto de entrada único

// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Cargar helpers (session.php ya hace session_start())
require_once __DIR__ . '/../helpers/funciones.php';
require_once __DIR__ . '/../helpers/session.php';

// Verificar CSRF en cada POST
csrf_verificar();

// Definir BASE_URL si no está
if (!defined('BASE_URL')) {
    define('BASE_URL', '/cine-rewind/public/');
}

// Obtener la ruta solicitada
$ruta = isset($_GET['ruta']) ? $_GET['ruta'] : 'home';

if (empty($ruta)) {
    $ruta = 'home';
}

// Enrutamiento
switch ($ruta) {

    // ==================== RUTAS PÚBLICAS ====================
    case 'login':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        (new AuthControlador())->mostrarLogin();
        break;

    case 'login_post':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        (new AuthControlador())->login();
        break;

    case 'registro':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        (new AuthControlador())->mostrarRegistro();
        break;

    case 'registro_post':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        (new AuthControlador())->registro();
        break;

    case 'logout':
        require_once __DIR__ . '/../controladores/AuthControlador.php';
        (new AuthControlador())->logout();
        break;

    case 'nosotros':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->nosotros();
        break;

    case 'contacto':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->contacto();
        break;

    // ==================== RUTAS DE USUARIO ====================
    case 'home':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->index();
        break;

    case 'cartelera':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->cartelera();
        break;

    case 'decada':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->decada();
        break;

    case 'buscar':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->buscar();
        break;

    case 'detalle':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->detallePelicula();
        break;

    case 'comentar':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->comentar();
        break;

    case 'reservar':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->reservar();
        break;

    case 'entrada':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->entrada();
        break;

    case 'procesar_compra':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->procesarCompra();
        break;

    case 'perfil':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->perfil();
        break;

    case 'actualizar_perfil':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->actualizarPerfil();
        break;

    case 'cambiar_password':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->cambiarPassword();
        break;

    case 'historial':
        require_once __DIR__ . '/../controladores/UsuarioControlador.php';
        (new UsuarioControlador())->historial();
        break;

    // ==================== RUTAS DE ADMIN ====================
    case 'admin':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->dashboard();
        break;

    case 'admin_peliculas':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->listarPeliculas();
        break;

    case 'admin_peliculas_agregar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->agregarPelicula();
        break;

    case 'admin_peliculas_editar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->editarPelicula();
        break;

    case 'admin_peliculas_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->eliminarPelicula();
        break;

    case 'admin_funciones':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->listarFunciones();
        break;

    case 'admin_funciones_agregar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->agregarFuncion();
        break;

    case 'admin_funciones_editar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->editarFuncion();
        break;

    case 'admin_funciones_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->eliminarFuncion();
        break;

    case 'admin_salas':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->listarSalas();
        break;

    case 'admin_salas_agregar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->agregarSala();
        break;

    case 'admin_salas_editar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->editarSala();
        break;

    case 'admin_salas_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->eliminarSala();
        break;

    case 'admin_usuarios':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->listarUsuarios();
        break;

    case 'admin_usuarios_cambiar_rol':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->cambiarRol();
        break;

    case 'admin_usuarios_eliminar':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->eliminarUsuario();
        break;

    case 'admin_comentarios':
        require_once __DIR__ . '/../controladores/AdminControlador.php';
        (new AdminControlador())->moderarComentarios();
        break;

    // ==================== 404 ====================
    default:
        http_response_code(404);
        echo "<h1>Error 404 - Página no encontrada</h1>";
        echo "<p>La ruta solicitada no existe: " . e($ruta) . "</p>";
        echo "<a href='" . BASE_URL . "?ruta=home'>Volver al inicio</a>";
        break;
}
?>