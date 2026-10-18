<?php
require_once __DIR__ . '/../models/PeliculaModelo.php';
require_once __DIR__ . '/../models/SalaModelo.php';
require_once __DIR__ . '/../models/FuncionModelo.php';
require_once __DIR__ . '/../models/UsuarioModelo.php';
require_once __DIR__ . '/../models/ReservaModelo.php';
require_once __DIR__ . '/../models/ComentarioModelo.php';
require_once __DIR__ . '/../models/TemaModelo.php';
require_once __DIR__ . '/../helpers/funciones.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/AuthControlador.php';

class AdminControlador {
    
    public function dashboard() {
        AuthControlador::verificarAdmin();
        
        $total_peliculas = PeliculaModelo::contarTodas();
        $total_usuarios = UsuarioModelo::contarTodos();
        $estadisticas_ventas = ReservaModelo::obtenerEstadisticas();
        $comentarios_pendientes = ComentarioModelo::obtenerPendientes();
        $funciones_proximas = FuncionModelo::obtenerProximas(10);
        $mensaje = getMensaje();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    // ==================== PELÍCULAS ====================
    
    public function listarPeliculas() {
        AuthControlador::verificarAdmin();
        $peliculas = PeliculaModelo::obtenerTodas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/peliculas/listar.php';
    }
    
    public function agregarPelicula() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => sanitizar($_POST['titulo'] ?? ''),
                'descripcion' => sanitizar($_POST['descripcion'] ?? ''),
                'duracion' => intval($_POST['duracion'] ?? 0),
                'genero' => sanitizar($_POST['genero'] ?? ''),
                'clasificacion' => sanitizar($_POST['clasificacion'] ?? ''),
                'anio_estreno' => intval($_POST['anio_estreno'] ?? 0),
                'posterurl' => sanitizar($_POST['posterurl'] ?? ''),
                'trailerurl' => sanitizar($_POST['trailerurl'] ?? ''),
                'destacada' => isset($_POST['destacada']) ? 1 : 0
            ];
            
            if (empty($datos['titulo']) || empty($datos['descripcion']) || 
                $datos['duracion'] <= 0 || empty($datos['genero']) || 
                $datos['anio_estreno'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_peliculas_agregar');
            }
            
            $resultado = PeliculaModelo::crear($datos);
            setMensaje($resultado ? 'success' : 'error', 
                       $resultado ? 'Película agregada correctamente.' : 'Error al agregar.');
            redirigir('admin_peliculas');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/peliculas/agregar.php';
    }
    
    public function editarPelicula() {
        AuthControlador::verificarAdmin();
        
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_peliculas');
        }
        
        $pelicula = PeliculaModelo::obtenerPorId($id);
        if (!$pelicula) {
            setMensaje('error', 'Película no encontrada.');
            redirigir('admin_peliculas');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => sanitizar($_POST['titulo'] ?? ''),
                'descripcion' => sanitizar($_POST['descripcion'] ?? ''),
                'duracion' => intval($_POST['duracion'] ?? 0),
                'genero' => sanitizar($_POST['genero'] ?? ''),
                'clasificacion' => sanitizar($_POST['clasificacion'] ?? ''),
                'anio_estreno' => intval($_POST['anio_estreno'] ?? 0),
                'posterurl' => sanitizar($_POST['posterurl'] ?? ''),
                'trailerurl' => sanitizar($_POST['trailerurl'] ?? ''),
                'destacada' => isset($_POST['destacada']) ? 1 : 0
            ];
            
            if (empty($datos['titulo']) || empty($datos['descripcion']) || 
                $datos['duracion'] <= 0 || empty($datos['genero']) || 
                $datos['anio_estreno'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_peliculas_editar&id=' . $id);
            }
            
            $resultado = PeliculaModelo::actualizar($id, $datos);
            setMensaje($resultado ? 'success' : 'error', 
                       $resultado ? 'Película actualizada.' : 'Error al actualizar.');
            redirigir('admin_peliculas');
        }
        
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/peliculas/editar.php';
    }
    
    public function eliminarPelicula() {
        AuthControlador::verificarAdmin();
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_peliculas');
        }
        $resultado = PeliculaModelo::eliminar($id);
        setMensaje($resultado ? 'success' : 'error', 
                   $resultado ? 'Película eliminada.' : 'Error al eliminar.');
        redirigir('admin_peliculas');
    }
    
    // ==================== FUNCIONES ====================
    
    public function listarFunciones() {
        AuthControlador::verificarAdmin();
        $funciones = FuncionModelo::obtenerTodas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/funciones/listar.php';
    }
    
    public function agregarFuncion() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_pelicula' => intval($_POST['id_pelicula'] ?? 0),
                'id_sala' => intval($_POST['id_sala'] ?? 0),
                'fecha' => $_POST['fecha'] ?? '',
                'horario' => $_POST['horario'] ?? '',
                'precio' => floatval($_POST['precio'] ?? 0)
            ];
            
            if ($datos['id_pelicula'] <= 0 || $datos['id_sala'] <= 0 || 
                empty($datos['fecha']) || empty($datos['horario']) || 
                $datos['precio'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_funciones_agregar');
            }
            
            try {
                $resultado = FuncionModelo::crear($datos);
                setMensaje($resultado ? 'success' : 'error', 
                           $resultado ? 'Función agregada con sus butacas.' : 'Error al agregar.');
            } catch (Exception $e) {
                setMensaje('error', $e->getMessage());
            }
            redirigir('admin_funciones');
        }
        
        $peliculas = PeliculaModelo::obtenerTodas();
        $salas = SalaModelo::obtenerTodas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/funciones/agregar.php';
    }
    
    public function editarFuncion() {
        AuthControlador::verificarAdmin();
        
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_funciones');
        }
        
        $funcion = FuncionModelo::obtenerPorId($id);
        if (!$funcion) {
            setMensaje('error', 'Función no encontrada.');
            redirigir('admin_funciones');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'id_pelicula' => intval($_POST['id_pelicula'] ?? 0),
                'id_sala' => intval($_POST['id_sala'] ?? 0),
                'fecha' => $_POST['fecha'] ?? '',
                'horario' => $_POST['horario'] ?? '',
                'precio' => floatval($_POST['precio'] ?? 0)
            ];
            
            if ($datos['id_pelicula'] <= 0 || $datos['id_sala'] <= 0 || 
                empty($datos['fecha']) || empty($datos['horario']) || 
                $datos['precio'] <= 0) {
                setMensaje('error', 'Todos los campos son obligatorios.');
                redirigir('admin_funciones_editar&id=' . $id);
            }
            
            $resultado = FuncionModelo::actualizar($id, $datos);
            setMensaje($resultado ? 'success' : 'error', 
                       $resultado ? 'Función actualizada.' : 'Error al actualizar.');
            redirigir('admin_funciones');
        }
        
        $peliculas = PeliculaModelo::obtenerTodas();
        $salas = SalaModelo::obtenerTodas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/funciones/editar.php';
    }
    
    public function eliminarFuncion() {
        AuthControlador::verificarAdmin();
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_funciones');
        }
        $resultado = FuncionModelo::eliminar($id);
        setMensaje($resultado ? 'success' : 'error', 
                   $resultado ? 'Función eliminada.' : 'Error al eliminar.');
        redirigir('admin_funciones');
    }
    
    // ==================== SALAS ====================
    
    public function listarSalas() {
        AuthControlador::verificarAdmin();
        $salas = SalaModelo::obtenerTodas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/salas/listar.php';
    }
    
    public function agregarSala() {
        AuthControlador::verificarAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = sanitizar($_POST['nombre'] ?? '');
            $capacidad = intval($_POST['capacidad'] ?? 0);
            
            $capacidades_validas = array_keys(capacidadesPermitidas());
            
            if (empty($nombre) || !in_array($capacidad, $capacidades_validas)) {
                setMensaje('error', 'Datos inválidos. Elegí una capacidad permitida.');
                redirigir('admin_salas_agregar');
            }
            
            try {
                $resultado = SalaModelo::crear($nombre, $capacidad);
                setMensaje($resultado ? 'success' : 'error', 
                           $resultado ? 'Sala agregada correctamente.' : 'Error al agregar.');
            } catch (Exception $e) {
                setMensaje('error', $e->getMessage());
            }
            redirigir('admin_salas');
        }
        
        $capacidades = capacidadesPermitidas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/salas/agregar.php';
    }
    
    public function editarSala() {
        AuthControlador::verificarAdmin();
        
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_salas');
        }
        
        $sala = SalaModelo::obtenerPorId($id);
        if (!$sala) {
            setMensaje('error', 'Sala no encontrada.');
            redirigir('admin_salas');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = sanitizar($_POST['nombre'] ?? '');
            $capacidad = intval($_POST['capacidad'] ?? 0);
            
            $capacidades_validas = array_keys(capacidadesPermitidas());
            
            if (empty($nombre) || !in_array($capacidad, $capacidades_validas)) {
                setMensaje('error', 'Datos inválidos.');
                redirigir('admin_salas_editar&id=' . $id);
            }
            
            try {
                $resultado = SalaModelo::actualizar($id, $nombre, $capacidad);
                setMensaje($resultado ? 'success' : 'error', 
                           $resultado ? 'Sala actualizada.' : 'Error al actualizar.');
            } catch (Exception $e) {
                setMensaje('error', $e->getMessage());
            }
            redirigir('admin_salas');
        }
        
        $capacidades = capacidadesPermitidas();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/salas/editar.php';
    }
    
    public function eliminarSala() {
        AuthControlador::verificarAdmin();
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_salas');
        }
        $resultado = SalaModelo::eliminar($id);
        setMensaje($resultado ? 'success' : 'error', 
                   $resultado ? 'Sala eliminada.' : 'Error al eliminar.');
        redirigir('admin_salas');
    }
    
    // ==================== USUARIOS ====================
    
    public function listarUsuarios() {
        AuthControlador::verificarAdmin();
        $usuarios = UsuarioModelo::obtenerTodos();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/usuarios/listar.php';
    }
    
    public function cambiarRol() {
        AuthControlador::verificarAdmin();
        $id = intval($_GET['id'] ?? 0);
        $rol = $_GET['rol'] ?? '';
        
        if ($id <= 0 || !in_array($rol, ['usuario', 'admin'])) {
            setMensaje('error', 'Datos no válidos.');
            redirigir('admin_usuarios');
        }
        if ($id == $_SESSION['usuario_id']) {
            setMensaje('error', 'No puedes cambiar tu propio rol.');
            redirigir('admin_usuarios');
        }
        
        $resultado = UsuarioModelo::actualizarRol($id, $rol);
        setMensaje($resultado ? 'success' : 'error', 
                   $resultado ? 'Rol actualizado.' : 'Error al actualizar rol.');
        redirigir('admin_usuarios');
    }
    
    public function eliminarUsuario() {
        AuthControlador::verificarAdmin();
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_usuarios');
        }
        if ($id == $_SESSION['usuario_id']) {
            setMensaje('error', 'No puedes eliminar tu propia cuenta.');
            redirigir('admin_usuarios');
        }
        $resultado = UsuarioModelo::eliminar($id);
        setMensaje($resultado ? 'success' : 'error', 
                   $resultado ? 'Usuario eliminado.' : 'Error al eliminar.');
        redirigir('admin_usuarios');
    }
    
    // ==================== COMENTARIOS ====================
    
    public function moderarComentarios() {
        AuthControlador::verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $accion = $_POST['accion'] ?? '';

            if ($id <= 0 || !in_array($accion, ['aprobar','rechazar','eliminar'])) {
                setMensaje('error', 'Acción no válida.');
                redirigir('admin_comentarios');
            }

            $resultado = false;
            $mensaje = '';
            switch ($accion) {
                case 'aprobar':
                    $resultado = ComentarioModelo::aprobar($id);
                    $mensaje = 'Comentario aprobado.';
                    break;
                case 'rechazar':
                    $resultado = ComentarioModelo::rechazar($id);
                    $mensaje = 'Comentario rechazado.';
                    break;
                case 'eliminar':
                    $resultado = ComentarioModelo::eliminar($id);
                    $mensaje = 'Comentario eliminado.';
                    break;
            }

            setMensaje($resultado ? 'success' : 'error', 
                    $resultado ? $mensaje : 'Error al procesar.');
            redirigir('admin_comentarios');
        }

        $filtro = $_GET['estado'] ?? null;
        $comentarios = ComentarioModelo::obtenerTodos($filtro);
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/comentarios/moderar.php';
    }

    // ==================== TEMAS ====================

    public function listarTemas() {
        AuthControlador::verificarAdmin();
        $temas = TemaModelo::obtenerTodos();
        $tema_activo = TemaModelo::obtenerActivo();
        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/temas/listar.php';
    }

    public function activarTema() {
        AuthControlador::verificarAdmin();
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID de tema no válido.');
            redirigir('admin_temas');
        }
        $ok = TemaModelo::activar($id);
        setMensaje($ok ? 'success' : 'error', 
                   $ok ? 'Tema activado correctamente.' : 'Error al activar.');
        redirigir('admin_temas');
    }

    public function agregarTema() {
        AuthControlador::verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre'             => sanitizar($_POST['nombre'] ?? ''),
                'color_fondo'        => sanitizar($_POST['color_fondo'] ?? '#000000'),
                'color_primario'     => sanitizar($_POST['color_primario'] ?? '#000000'),
                'color_acento'       => sanitizar($_POST['color_acento'] ?? '#000000'),
                'color_acento_hover' => sanitizar($_POST['color_acento_hover'] ?? '#000000'),
                'color_texto'        => sanitizar($_POST['color_texto'] ?? '#000000'),
                'color_texto_claro'  => sanitizar($_POST['color_texto_claro'] ?? '#000000'),
            ];

            if (empty($datos['nombre'])) {
                setMensaje('error', 'El nombre del tema es obligatorio.');
                redirigir('admin_temas_agregar');
            }

            $ok = TemaModelo::crear($datos);
            setMensaje($ok ? 'success' : 'error', 
                       $ok ? 'Tema creado.' : 'Error al crear.');
            redirigir('admin_temas');
        }

        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/temas/agregar.php';
    }

    public function editarTema() {
        AuthControlador::verificarAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_temas');
        }

        $tema = TemaModelo::obtenerPorId($id);
        if (!$tema) {
            setMensaje('error', 'Tema no encontrado.');
            redirigir('admin_temas');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre'             => sanitizar($_POST['nombre'] ?? ''),
                'color_fondo'        => sanitizar($_POST['color_fondo'] ?? '#000000'),
                'color_primario'     => sanitizar($_POST['color_primario'] ?? '#000000'),
                'color_acento'       => sanitizar($_POST['color_acento'] ?? '#000000'),
                'color_acento_hover' => sanitizar($_POST['color_acento_hover'] ?? '#000000'),
                'color_texto'        => sanitizar($_POST['color_texto'] ?? '#000000'),
                'color_texto_claro'  => sanitizar($_POST['color_texto_claro'] ?? '#000000'),
            ];

            if (empty($datos['nombre'])) {
                setMensaje('error', 'El nombre del tema es obligatorio.');
                redirigir('admin_temas_editar&id=' . $id);
            }

            $ok = TemaModelo::actualizar($id, $datos);
            setMensaje($ok ? 'success' : 'error', 
                       $ok ? 'Tema actualizado.' : 'Error al actualizar.');
            redirigir('admin_temas');
        }

        $mensaje = getMensaje();
        require_once __DIR__ . '/../views/admin/temas/editar.php';
    }

    public function eliminarTema() {
        AuthControlador::verificarAdmin();

        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            setMensaje('error', 'ID no válido.');
            redirigir('admin_temas');
        }

        $tema_activo = TemaModelo::obtenerActivo();
        if ($tema_activo && $tema_activo['id_tema'] == $id) {
            setMensaje('error', 'No podés eliminar el tema activo. Cambiá a otro primero.');
            redirigir('admin_temas');
        }

        $ok = TemaModelo::eliminar($id);
        setMensaje($ok ? 'success' : 'error', 
                   $ok ? 'Tema eliminado.' : 'No se puede eliminar.');
        redirigir('admin_temas');
    }
}
?>