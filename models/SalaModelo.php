<?php
require_once __DIR__ . '/ConexionBD.php';
require_once __DIR__ . '/../helpers/funciones.php';

class SalaModelo {
    
    /**
     * Obtiene todas las salas activas
     */
    public static function obtenerTodas() {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->query("SELECT * FROM salas WHERE activo = 1 ORDER BY id_sala");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene una sala por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM salas WHERE id_sala = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Crea una nueva sala (solo con capacidades permitidas)
     */
    public static function crear($nombre, $capacidad) {
        $capacidades_validas = array_keys(capacidadesPermitidas());
        if (!in_array(intval($capacidad), $capacidades_validas)) {
            throw new Exception("Capacidad no válida. Elegí entre: " . implode(', ', $capacidades_validas));
        }
        
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("INSERT INTO salas (nombre, capacidad) VALUES (?, ?)");
        return $stmt->execute([$nombre, $capacidad]);
    }
    
    /**
     * Actualiza una sala
     */
    public static function actualizar($id, $nombre, $capacidad) {
        $capacidades_validas = array_keys(capacidadesPermitidas());
        if (!in_array(intval($capacidad), $capacidades_validas)) {
            throw new Exception("Capacidad no válida.");
        }
        
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE salas SET nombre = ?, capacidad = ? WHERE id_sala = ? AND activo = 1");
        return $stmt->execute([$nombre, $capacidad, $id]);
    }
    
    /**
     * Elimina una sala (borrado lógico)
     */
    public static function eliminar($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE salas SET activo = 0 WHERE id_sala = ?");
        return $stmt->execute([$id]);
    }
}
?>