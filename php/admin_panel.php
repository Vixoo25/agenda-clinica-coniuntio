<?php
include('conn.php');

// Consultamos las solicitudes pendientes
$query = "SELECT * FROM solicitudes_agenda WHERE estado = 'pendiente'";
$resultado = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrativo - Clínica Coniuntio</title>
    <link rel="stylesheet" href="../styles.css"> </head>
<body>
    <h1>Gestión de Solicitudes</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Servicio</th>
            <th>Acción</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($resultado)): ?>
        <tr>
            <td><?php echo $row['nombre_completo']; ?></td>
            <td><?php echo $row['servicio_solicitado']; ?></td>
            <td>
                <form action="procesar_correo.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="accion" value="aceptar">
                    <input type="date" name="fecha" required>
                    <input type="time" name="hora" required>
                    <button type="submit" style="background: green; color: white;">Aceptar</button>
                </form>

                <form action="procesar_correo.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="accion" value="declinar">
                    <button type="submit" style="background: red; color: white;">Declinar</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>