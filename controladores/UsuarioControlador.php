<?php
require_once __DIR__ . '/../models/PeliculaModelo.php';
require_once __DIR__ . '/../models/FuncionModelo.php';
require_once __DIR__ . '/../models/ButacaModelo.php';
require_once __DIR__ . '/../models/ReservaModelo.php';
require_once __DIR__ . '/../models/ComentarioModelo.php';
require_once __DIR__ . '/../models/UsuarioModelo.php';
require_once __DIR__ . '/../helpers/funciones.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/AuthControlador.php';

class UsuarioControlador {
    
    public function index() {
        AuthControlador::verificarAutenticacion();
        
        $peliculas = PeliculaModelo::obtenerTodas(8);
        $destacadas = PeliculaModelo::obtenerDestacadas(5);
        $proximas = FuncionModelo::obtenerProximas(5);
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/usuario/index.php';
    }
    
    public function cartelera() {
        AuthControlador::verificarAutenticacion();
        
        $peliculas = PeliculaModelo::obtenerTodas();
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/usuario/cartelera.php';
    }
    
    public function decada() {
        AuthControlador::verificarAutenticacion();
        
        $decada = isset($_GET['decada']) ? intval($_GET['decada']) : 0;
        
        // Validar rango amplio (1900 a 2030)
        if ($decada < 1900 || $decada > 2030) {
            setMensaje('error', 'Década no válida.');
            redirigir('cartelera');
        }
        
        $peliculas = PeliculaModelo::obtenerPorDecada($decada);
        $titulo_decada = ($decada >= 2000) ? $decada . "'s" : substr($decada, -2) . "'s";
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/usuario/decada.php';
    }
    
    /**
     * Búsqueda con filtros
     */
    public function buscar() {
        AuthControlador::verificarAutenticacion();

        $filtros = [
            'q'      => sanitizar($_GET['q'] ?? ''),
            'genero' => sanitizar($_GET['genero'] ?? ''),
            'anio'   => intval($_GET['anio'] ?? 0),
            'decada' => intval($_GET['decada'] ?? 0)
        ];

        $peliculas = PeliculaModelo::buscarConFiltros($filtros);
        $generos = PeliculaModelo::obtenerGeneros();
        $mensaje = getMensaje();

        require_once __DIR__ . '/../views/usuario/buscar.php';
    }
    
    /**
     * Detalle de película con comentarios
     */
    public function detallePelicula() {
        AuthControlador::verificarAutenticacion();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            redirigir('cartelera');
        }

        $pelicula = PeliculaModelo::obtenerPorId($id);
        if (!$pelicula) {
            setMensaje('error', 'Película no encontrada.');
            redirigir('cartelera');
        }

        $comentarios = ComentarioModelo::obtenerPorPelicula($id);
        $promedio = ComentarioModelo::obtenerPromedio($id);
        $yaComento = ComentarioModelo::yaComento($_SESSION['usuario_id'], $id);
        $mensaje = getMensaje();

        require_once __DIR__ . '/../views/usuario/detalle.php';
    }
    
    /**
     * Enviar comentario
     */
    public function comentar() {
        AuthControlador::verificarAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('cartelera');
        }

        $id_pelicula = intval($_POST['id_pelicula'] ?? 0);
        $comentario  = sanitizar($_POST['comentario'] ?? '');
        $puntaje     = intval($_POST['puntaje'] ?? 0);

        if ($id_pelicula <= 0 || empty($comentario) || $puntaje < 1 || $puntaje > 5) {
            setMensaje('error', 'Comentario inválido. Puntaje entre 1 y 5.');
            redirigir('detalle&id=' . $id_pelicula);
        }

        if (ComentarioModelo::yaComento($_SESSION['usuario_id'], $id_pelicula)) {
            setMensaje('error', 'Ya comentaste esta película.');
            redirigir('detalle&id=' . $id_pelicula);
        }

        $ok = ComentarioModelo::crear(
            $_SESSION['usuario_id'],
            $id_pelicula,
            $comentario,
            $puntaje
        );

        if ($ok) {
            setMensaje('success', '¡Comentario enviado! Será revisado por un administrador.');
        } else {
            setMensaje('error', 'Error al enviar el comentario.');
        }

        redirigir('detalle&id=' . $id_pelicula);
    }
    
    public function reservar() {
        AuthControlador::verificarAutenticacion();
        
        $id_pelicula = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id_pelicula <= 0) {
            setMensaje('error', 'Película no especificada.');
            redirigir('cartelera');
        }
        
        $pelicula = PeliculaModelo::obtenerPorId($id_pelicula);
        if (!$pelicula) {
            setMensaje('error', 'Película no encontrada.');
            redirigir('cartelera');
        }
        
        $funciones = FuncionModelo::obtenerPorPelicula($id_pelicula);
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/usuario/reservar.php';
    }
    
    /**
     * Selección de butacas
     */
    public function entrada() {
        AuthControlador::verificarAutenticacion();

        $id_funcion = intval($_GET['funcion'] ?? 0);
        if ($id_funcion <= 0) {
            setMensaje('error', 'Función no especificada.');
            redirigir('cartelera');
        }

        $funcion = FuncionModelo::obtenerPorId($id_funcion);
        if (!$funcion) {
            setMensaje('error', 'Función no encontrada.');
            redirigir('cartelera');
        }

        $butacas = ButacaModelo::obtenerTodasPorFuncion($id_funcion);
        $mensaje = getMensaje();

        require_once __DIR__ . '/../views/usuario/entrada.php';
    }
    
    /**
     * Procesar compra multi-butaca
     */
    public function procesarCompra() {
        AuthControlador::verificarAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('cartelera');
        }

        $id_funcion = intval($_POST['id_funcion'] ?? 0);
        $butacas = $_POST['butacas'] ?? [];

        if ($id_funcion <= 0 || empty($butacas)) {
            setMensaje('error', 'Debes seleccionar al menos una butaca.');
            redirigir('entrada&funcion=' . $id_funcion);
        }

        $butacas = array_map('intval', $butacas);

        try {
            $resultado = ReservaModelo::crear(
                $_SESSION['usuario_id'],
                $id_funcion,
                $butacas
            );

            setMensaje('success', 
                '¡Compra realizada! Código: ' . $resultado['codigo'] . 
                ' — ' . count($butacas) . ' entrada(s).');
            redirigir('historial');

        } catch (Exception $e) {
            setMensaje('error', $e->getMessage());
            redirigir('entrada&funcion=' . $id_funcion);
        }
    }
    
    public function perfil() {
        AuthControlador::verificarAutenticacion();
        
        $usuario = UsuarioModelo::obtenerPorId($_SESSION['usuario_id']);
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/usuario/perfil.php';
    }
    
    public function actualizarPerfil() {
        AuthControlador::verificarAutenticacion();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('perfil');
        }
        
        $nombre = sanitizar($_POST['nombre'] ?? '');
        $email = sanitizar($_POST['email'] ?? '');
        
        if (empty($nombre) || empty($email)) {
            setMensaje('error', 'Todos los campos son obligatorios.');
            redirigir('perfil');
        }
        
        if (!validarEmail($email)) {
            setMensaje('error', 'El email no es válido.');
            redirigir('perfil');
        }
        
        $usuarioExistente = UsuarioModelo::obtenerPorEmail($email);
        if ($usuarioExistente && $usuarioExistente['id_usuario'] != $_SESSION['usuario_id']) {
            setMensaje('error', 'El email ya está en uso por otro usuario.');
            redirigir('perfil');
        }
        
        $resultado = UsuarioModelo::actualizarPerfil(
            $_SESSION['usuario_id'],
            $nombre,
            $email
        );
        
        if ($resultado) {
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['usuario_email'] = $email;
            setMensaje('success', 'Perfil actualizado correctamente.');
        } else {
            setMensaje('error', 'Error al actualizar el perfil.');
        }
        
        redirigir('perfil');
    }
    
    public function cambiarPassword() {
        AuthControlador::verificarAutenticacion();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('perfil');
        }
        
        $password_actual = $_POST['password_actual'] ?? '';
        $password_nueva = $_POST['password_nueva'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        if (empty($password_actual) || empty($password_nueva) || empty($password_confirm)) {
            setMensaje('error', 'Todos los campos son obligatorios.');
            redirigir('perfil');
        }
        
        if (strlen($password_nueva) < 6) {
            setMensaje('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
            redirigir('perfil');
        }
        
        if ($password_nueva !== $password_confirm) {
            setMensaje('error', 'Las contraseñas no coinciden.');
            redirigir('perfil');
        }
        
        $usuario = UsuarioModelo::obtenerPorId($_SESSION['usuario_id']);
        if (!password_verify($password_actual, $usuario['password'])) {
            setMensaje('error', 'La contraseña actual es incorrecta.');
            redirigir('perfil');
        }
        
        $resultado = UsuarioModelo::cambiarPassword($_SESSION['usuario_id'], $password_nueva);
        
        if ($resultado) {
            setMensaje('success', 'Contraseña cambiada correctamente.');
        } else {
            setMensaje('error', 'Error al cambiar la contraseña.');
        }
        
        redirigir('perfil');
    }
    
    public function historial() {
        AuthControlador::verificarAutenticacion();
        
        $historial = ReservaModelo::obtenerHistorial($_SESSION['usuario_id']);
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/usuario/historial.php';
    }
    
    public function nosotros() {
        require_once __DIR__ . '/../views/shared/nosotros.php';
    }
    
    public function contacto() {
        require_once __DIR__ . '/../views/shared/contacto.php';
    }
}
?>