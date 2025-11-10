<?php
// Incluye el archivo de conexión a la base de datos
include "conexion.php";

// Función para liberar mesas que han estado ocupadas por más de 6 horas
function liberarMesasAntiguas($conexion) {
    // Consulta SQL para identificar mesas que deben ser liberadas
    // Busca mesas ocupadas con fecha de asignación mayor a 6 horas
    $sql_mesas_a_liberar = "SELECT m.`id mesa`, m.numero, m.`fecha de asignacion`, 
                           chm.`cliente_id cliente`, chm.`cliente_usuario_id usuario`
                    FROM mesa m 
                    JOIN cliente_has_mesa chm ON m.`id mesa` = chm.`mesa_id mesa`
                    WHERE m.estado = 'ocupada' 
                    AND m.`fecha de asignacion` < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    // Ejecuta la consulta de selección
    $resultado = mysqli_query($conexion, $sql_mesas_a_liberar);
    $mesas_liberadas = [];
    
    // Almacena todas las mesas que serán liberadas en un array
    while($mesa = mysqli_fetch_assoc($resultado)) {
        $mesas_liberadas[] = $mesa;
    }
    
    // Consulta SQL para actualizar el estado de las mesas antiguas a "disponible"
    $sql_liberar = "UPDATE mesa 
                   SET estado = 'disponible' 
                   WHERE estado = 'ocupada' 
                   AND `fecha de asignacion` < DATE_SUB(NOW(), INTERVAL 6 HOUR)";
    
    // Ejecuta la consulta de actualización
    $resultado_liberar = mysqli_query($conexion, $sql_liberar);
    
    // Verifica si la actualización fue exitosa
    if ($resultado_liberar) {
        // Obtiene el número de mesas afectadas por la actualización
        $count = mysqli_affected_rows($conexion);
        
        // Crea un mensaje de log con la fecha y hora actual
        $log_message = "[" . date('Y-m-d H:i:s') . "] Mesas liberadas automáticamente: " . $count;
        
        // Agrega detalles de cada mesa liberada al mensaje de log
        foreach($mesas_liberadas as $mesa) {
            $log_message .= "\n  - Mesa " . $mesa['numero'] . " (ID: " . $mesa['id mesa'] . ")";
        }
        
        // Registra el mensaje en el log de errores de PHP
        error_log($log_message);
        
        // Muestra mensaje de éxito en la salida
        echo "Limpieza completada: " . $count . " mesas liberadas\n";
    } else {
        // Muestra mensaje de error si la consulta falló
        echo "Error en la limpieza: " . mysqli_error($conexion);
    }
}

// Ejecuta la función de liberación de mesas
liberarMesasAntiguas($conexion);
?>