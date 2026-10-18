<?php
require_once __DIR__ . '/ConexionBD.php';

class ButacaModelo {
    
    /**
     * Obtiene todas las butacas de una función
     */
    public static function obtenerPorFuncion($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas WHERE id_funcion = ? AND activo = 1 ORDER BY numero_butaca");
        $stmt->execute([$id_funcion]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene butacas disponibles de una función
     */
    public static function obtenerDisponibles($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas 
                              WHERE id_funcion = ? AND estado = 'disponible' AND activo = 1 
                              ORDER BY numero_butaca");
        $stmt->execute([$id_funcion]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene TODAS las butacas de una función con su estado
     */
    public static function obtenerTodasPorFuncion($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas 
                              WHERE id_funcion = ? AND activo = 1 
                              ORDER BY id_butaca ASC");
        $stmt->execute([$id_funcion]);
        return $stmt->fetchAll();
    }
    
    /**
     * Agrupa las butacas por fila (A, B, C...)
     * Devuelve: ['A' => [...butacas...], 'B' => [...butacas...], ...]
     */
    public static function agruparPorFila($butacas) {
        $agrupadas = [];
        foreach ($butacas as $butaca) {
            // El numero_butaca es "A-01", "B-07", etc.
            $fila = substr($butaca['numero_butaca'], 0, 1);
            if (!isset($agrupadas[$fila])) {
                $agrupadas[$fila] = [];
            }
            $agrupadas[$fila][] = $butaca;
        }
        ksort($agrupadas);
        return $agrupadas;
    }
    
    /**
     * Cuenta las butacas disponibles de una función
     */
    public static function contarDisponibles($id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT COUNT(*) as disponibles 
                              FROM butacas 
                              WHERE id_funcion = ? AND estado = 'disponible' AND activo = 1");
        $stmt->execute([$id_funcion]);
        $resultado = $stmt->fetch();
        return $resultado['disponibles'];
    }
    
    /**
     * Reserva una butaca
     */
    public static function reservar($id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE butacas SET estado = 'reservada' WHERE id_butaca = ? AND estado = 'disponible'");
        return $stmt->execute([$id_butaca]);
    }
    
    /**
     * Confirma una reserva (cambia a ocupada)
     */
    public static function confirmar($id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE butacas SET estado = 'ocupada' WHERE id_butaca = ?");
        return $stmt->execute([$id_butaca]);
    }
    
    /**
     * Libera una butaca (cancela reserva)
     */
    public static function liberar($id_butaca) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("UPDATE butacas SET estado = 'disponible' WHERE id_butaca = ?");
        return $stmt->execute([$id_butaca]);
    }
    
    /**
     * Obtiene una butaca por su ID
     */
    public static function obtenerPorId($id) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas WHERE id_butaca = ? AND activo = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Verifica que una butaca pertenece a una función y está disponible
     */
    public static function validarDisponibilidad($id_butaca, $id_funcion) {
        $db = ConexionBD::getInstancia()->getConexion();
        $stmt = $db->prepare("SELECT * FROM butacas 
                              WHERE id_butaca = ? AND id_funcion = ? 
                              AND estado = 'disponible' AND activo = 1");
        $stmt->execute([$id_butaca, $id_funcion]);
        return $stmt->fetch();
    }
}
?>