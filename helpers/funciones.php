<?php
// Funciones auxiliares

/**
 * Sanitiza un string para prevenir XSS (al GUARDAR)
 */
function sanitizar($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
    return $dato;
}

/**
 * Escapa un string para mostrar en HTML (al MOSTRAR)
 * Uso: <?php echo e($variable); ?>
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Valida un email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Redirige a una URL
 */
function redirigir($url) {
    // Si la URL ya tiene index.php, no agregar nada extra
    if (strpos($url, 'index.php') === 0) {
        header('Location: ' . BASE_URL . $url);
    } else {
        header('Location: ' . BASE_URL . 'index.php?ruta=' . $url);
    }
    exit();
}

/**
 * Muestra un mensaje flash en sesión
 */
function setMensaje($tipo, $texto) {
    $_SESSION['mensaje'] = [
        'tipo' => $tipo, // 'success', 'error', 'warning', 'info'
        'texto' => $texto
    ];
}

/**
 * Obtiene y elimina un mensaje flash
 */
function getMensaje() {
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
        return $mensaje;
    }
    return null;
}

/**
 * Verifica si el usuario está logueado
 */
function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

/**
 * Verifica si el usuario es administrador
 */
function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

/**
 * Genera un slug a partir de un texto
 */
function generarSlug($texto) {
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9-]/', '-', $texto);
    $texto = preg_replace('/-+/', '-', $texto);
    return trim($texto, '-');
}

/**
 * Formatea una fecha
 */
function formatearFecha($fecha, $formato = 'd/m/Y H:i') {
    $datetime = new DateTime($fecha);
    return $datetime->format($formato);
}

/**
 * Obtiene la década a partir de un año
 */
function obtenerDecada($anio) {
    $decada = floor($anio / 10) * 10;
    if ($decada >= 2000) {
        return $decada . "'s";
    } else {
        return substr($decada, -2) . "'s";
    }
}

/**
 * Trunca un texto a cierta longitud
 */
function truncarTexto($texto, $longitud = 100) {
    if (strlen($texto) <= $longitud) {
        return $texto;
    }
    return substr($texto, 0, $longitud) . '...';
}

/**
 * CSRF - Genera o devuelve el token de la sesión
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF - Campo oculto para formularios
 * Uso: <?php echo csrf_field(); ?>
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * CSRF - Verifica el token en POST
 * Llamar al inicio del index.php o en cada controlador que reciba POST
 */
function csrf_verificar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }

    $enviado = $_POST['csrf_token'] ?? '';
    $guardado = $_SESSION['csrf_token'] ?? '';

    if (empty($guardado) || !hash_equals($guardado, $enviado)) {
        http_response_code(403);
        die('Token de seguridad inválido. Volvé atrás e intentá de nuevo.');
    }
    return true;
}
?>