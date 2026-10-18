<?php
require_once __DIR__ . '/ConexionBD.php';

class TemaModelo {

    /**
     * Obtiene todos los temas activos
     */
    public static function obtenerTodos() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT * FROM temas WHERE activo = 1 ORDER BY es_predeterminado DESC, nombre ASC");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un tema por ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM temas WHERE id_tema = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtiene el tema activo actualmente
     */
    public static function obtenerActivo() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("
            SELECT t.* FROM temas t
            INNER JOIN configuracion c ON c.valor = t.id_tema
            WHERE c.clave = 'tema_activo' AND t.activo = 1
            LIMIT 1
        ");
        $tema = $stmt->fetch();

        // Si no hay tema activo, devolver el primero disponible
        if (!$tema) {
            $stmt = $db->query("SELECT * FROM temas WHERE activo = 1 ORDER BY id_tema ASC LIMIT 1");
            $tema = $stmt->fetch();
        }
        return $tema;
    }

    /**
     * Activa un tema
     */
    public static function activar($id_tema) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("
            INSERT INTO configuracion (clave, valor) 
            VALUES ('tema_activo', ?) 
            ON DUPLICATE KEY UPDATE valor = ?
        ");
        return $stmt->execute([$id_tema, $id_tema]);
    }

    /**
     * Crea un tema nuevo
     */
    public static function crear($datos) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("
            INSERT INTO temas 
            (nombre, color_fondo, color_primario, color_acento, 
             color_acento_hover, color_texto, color_texto_claro) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $datos['nombre'],
            $datos['color_fondo'],
            $datos['color_primario'],
            $datos['color_acento'],
            $datos['color_acento_hover'],
            $datos['color_texto'],
            $datos['color_texto_claro']
        ]);
    }

    /**
     * Actualiza un tema
     */
    public static function actualizar($id, $datos) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("
            UPDATE temas SET 
                nombre = ?, color_fondo = ?, color_primario = ?, 
                color_acento = ?, color_acento_hover = ?, 
                color_texto = ?, color_texto_claro = ?
            WHERE id_tema = ? AND activo = 1
        ");
        return $stmt->execute([
            $datos['nombre'],
            $datos['color_fondo'],
            $datos['color_primario'],
            $datos['color_acento'],
            $datos['color_acento_hover'],
            $datos['color_texto'],
            $datos['color_texto_claro'],
            $id
        ]);
    }

    /**
     * Elimina un tema (solo si NO es predeterminado)
     */
    public static function eliminar($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE temas SET activo = 0 WHERE id_tema = ? AND es_predeterminado = 0");
        return $stmt->execute([$id]);
    }

    /**
     * Cuenta cuántos temas hay
     */
    public static function contarTodos() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT COUNT(*) as total FROM temas WHERE activo = 1");
        $r = $stmt->fetch();
        return $r['total'];
    }
}
?>