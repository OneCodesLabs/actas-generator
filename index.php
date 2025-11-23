<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "actas_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// --- Filtro de búsqueda ---
$codigo = $_GET['codigo'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$modo_busqueda = false;

if (!empty($codigo) || !empty($nombre)) {
    $sql = "SELECT * FROM actas WHERE codigo_tarjeta LIKE '%$codigo%' AND nombre LIKE '%$nombre%' ORDER BY id DESC";
    $modo_busqueda = true;
} else {
    $sql = "SELECT * FROM actas ORDER BY id DESC LIMIT 30";
}
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de actas</title>
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Generador de actas</h2>

    <!-- Formulario de creación -->
    <form action="guardar_acta.php" method="POST" class="formulario">
        <div class="grid">
            <div><label>Fecha Creación</label><input type="date" name="fecha_creacion" required></div>
            <div><label>Código tarjeta</label><input type="text" name="codigo_tarjeta" placeholder="Código tarjeta" required></div>
            <div><label>Rut</label><input type="text" name="rut" placeholder="Rut"></div>
            <div><label>Nombre Apellido</label><input type="text" name="nombre" placeholder="Nombre Apellido"></div>
            <div><label>Unidad</label><input type="text" name="unidad" placeholder="Unidad"></div>
            <div><label>Email</label><input type="email" name="email" placeholder="Email"></div>
            <div><label>¿Quién solicita?</label><input type="text" name="solicita" placeholder="¿Quién solicita?"></div>
            <div><label>Patente o NA</label><input type="text" name="patente" placeholder="Patente o NA"></div>
            <div><label>Fono</label><input type="text" name="fono" placeholder="Fono"></div>
            <div><label>Adquisición de tarjeta por...</label>
                <select name="adquisicion">
                    <option value="">Seleccionar</option>
                    <option value="Nueva">Nueva</option>
                    <option value="Compra">Compra</option>
                    <option value="Reposición">Reposición</option>
                </select>
            </div>
            <div><label>Tipo usuario</label>
                <select name="tipo_usuario">
                    <option value="">Seleccionar</option>
                    <option value="Honorario">Honorario</option>
                    <option value="Becado">Becado</option>
                    <option value="Contrata">Contrata</option>
                    <option value="Suplente">Suplente</option>
                    <option value="Externo">Externo</option>
                </select>
            </div>
            <div><label>Folio solo para compra</label><input type="text" name="folio" placeholder="Folio"></div>
        </div>

        <button type="submit" class="btn">Generar</button>
    </form>

    <hr>

    <!-- Sección de búsqueda -->
    <h3>Buscar acta</h3>
    <form method="GET" class="busqueda">
        <input type="text" name="codigo" placeholder="Código tarjeta" value="<?= htmlspecialchars($codigo) ?>">
        <input type="text" name="nombre" placeholder="Nombre Apellido" value="<?= htmlspecialchars($nombre) ?>">
        <button type="submit" class="btn">Buscar</button>

        <?php if ($modo_busqueda): ?>
            <a href="index.php" class="btn-secondary">Mostrar todas</a>
        <?php endif; ?>
    </form>

    <!-- Tabla de resultados -->
    <h3><?= $modo_busqueda ? "Resultados de búsqueda" : "Últimas 30 actas" ?></h3>
    <table>
        <thead>
            <tr>
                <th>N° Tarjeta</th>
                <th>Fecha</th>
                <th>Rut</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Solicitó</th>
                <th>Patente</th>
                <th>Correo</th>
                <th>Fono</th>
                <th>Usuario</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($result && $result->num_rows > 0) {
                    while ($fila = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo $fila['codigo_tarjeta']; ?></td>
                            <td><?php echo $fila['fecha_creacion']; ?></td>
                            <td><?php echo $fila['rut']; ?></td>
                            <td><?php echo $fila['nombre']; ?></td>
                            <td><?php echo $fila['unidad']; ?></td>
                            <td><?php echo $fila['solicita']; ?></td>
                            <td><?php echo $fila['patente']; ?></td>
                            <td><?php echo $fila['email']; ?></td>
                            <td><?php echo $fila['fono']; ?></td>
                            <td><?php echo $fila['tipo_usuario']; ?></td>
                            <td>
                                <a href="generar_pdf.php?id=<?php echo $fila['id']; ?>" 
                                target="_blank" 
                                class="btn-icon" 
                                title="Generar PDF">🧾</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay registros para mostrar</td></tr>";
                }
                ?>

        </tbody>
    </table>
</div>
</body>
</html>
