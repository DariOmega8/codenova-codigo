<?php
include "conexion.php";

function liberarMesasAntiguas($conexion) {
    // Primero, registrar en un log las mesas que se van a liberar (para mantener historial)
    $sql_mesas_a_liberar = "SELECT m.`id mesa`, m.numero, m.`fecha de asignacion`, 
                           chm.`cliente_id cliente`, chm.`cliente_usuario_id usuario`
                    FROM mesa m 
                    JOIN cliente_has_mesa chm ON m.`id mesa` = chm.`mesa_id mesa`
                    WHERE m.estado = 'ocupada' 
                    AND m.`fecha de asignacion` < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    $resultado = mysqli_query($conexion, $sql_mesas_a_liberar);
    $mesas_liberadas = [];
    
    while($mesa = mysqli_fetch_assoc($resultado)) {
        $mesas_liberadas[] = $mesa;
    }
    
    // Liberar las mesas antiguas
    $sql_liberar = "UPDATE mesa 
                   SET estado = 'disponible' 
                   WHERE estado = 'ocupada' 
                   AND `fecha de asignacion` < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    $resultado_liberar = mysqli_query($conexion, $sql_liberar);
    
    if ($resultado_liberar) {
        $count = mysqli_affected_rows($conexion);
        
        // Registrar en log
        $log_message = "[" . date('Y-m-d H:i:s') . "] Mesas liberadas automáticamente: " . $count;
        foreach($mesas_liberadas as $mesa) {
            $log_message .= "\n  - Mesa " . $mesa['numero'] . " (ID: " . $mesa['id mesa'] . ")";
        }
        error_log($log_message);
        
        echo "Limpieza completada: " . $count . " mesas liberadas\n";
    } else {
        echo "Error en la limpieza: " . mysqli_error($conexion);
    }
}

liberarMesasAntiguas($conexion);
?>