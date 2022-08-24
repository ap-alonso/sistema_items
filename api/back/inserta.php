<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insertar datos</title>
</head>

<body>
    <div>
        <div></div>
        <form action="inserta.php" method="POST">
            <div><label for="nombre">Nombre</label></div>
            <div><input type="text" name="nombre" id="nombre"></div>
            <div><label for="nombre">Apellido</label></div>
            <div><input type="text" name="apellido" id="apellido"></div>
            <div><input type="submit" value="Agregar"></div>
        </form>
    </div>
    <?php
    include 'database.php';
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];

        $sql = "INSERT INTO empleados(nombre,apellido)
                VALUES('$nombre','$apellido')";
        if ($conexion->query($sql) === true) {
            echo '<div>Dato ingresado correctamente</div>';
        } else {
            die("Error al insertar datos: " . $conexion->error);
        }
        $conexion->close();
    }
    ?>
</body>

</html>