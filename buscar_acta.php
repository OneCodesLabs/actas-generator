<?php
$conexion = new mysqli("localhost", "root", "", "actas_db");
$codigo = $_GET['codigo'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$sql = "SELECT * FROM actas WHERE codigo_tarjeta LIKE '%$codigo%' AND nombre LIKE '%$nombre%'";
$result = $conexion->query($sql);
echo "<h3>Resultados de búsqueda</h3>";
echo "<table border='1'><tr><th>Código</th><th>Nombre</th><th>Unidad</th><th>Fono</th></tr>";
if ($result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$fila['codigo_tarjeta']}</td>
            <td>{$fila['nombre']}</td>
            <td>{$fila['unidad']}</td>
            <td>{$fila['fono']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No se encontraron resultados</td></tr>";
}
echo "</table>";
$conexion->close();
?>
