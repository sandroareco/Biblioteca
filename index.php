<?php

session_start();
if(!isset($_SESSION['usuario_id'])){
    header("location:login.php");
    exit();
}

$total_libros = 0; 
include "includes/conexion.php";

try{
    $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consulta = "SELECT COUNT(cod) as total_libros FROM biblioteca";
    $stmt = $pdo->prepare($consulta);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_libros = $resultado['total_libros'];
}catch (PDOException $e) {
    echo 'Hubo un error de conexion'.$e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-lg">
        <header class="d-flex justify-content-between align-items-center mt-1 border-bottom border-primary-subtle border-3">
            <div>
                <p class="fs-5 fw-bold text-dark-emphasis mb-0">Hola <?php echo $_SESSION['usuario_nombre'];?>👋</p>
            </div>
            <div>
                <h1 class="fs-2 fw-bold text-dark-emphasis mb-0">Biblioteca ISFT Nº232</h1>
            </div>
            <div class="mb-1">
                <a href="includes/logout.php" class="btn btn-dark">cerrar sesion</a>
            </div>
        </header>
        <main>
            <div class="my-3">
                <h2 class="fs-4 text-dark-emphasis text-center">Sistema de registro, búsqueda, modificación y eliminación de libros</h2>
                <div class="d-flex justify-content-center align-items-center">
                    <p class="fs-5 text-dark-emphasis text-center mb-0">Libros registrados en la biblioteca: <?php echo $total_libros;?> </p>
                    <a href="vistas/catalogo.php" class="btn btn-primary ms-5">Catalogo completo</a>
                </div>
            </div>
            <div class="text-center mt-4">
                <div class="row justify-content-lg-center">
                    <div class="col-lg-4 mx-lg-5 p-4 shadow rounded mt-4 card-options">
                        <h3 class="fs-4 fw-bold mt-4 text-dark-emphasis">Registrar</h3>
                        <p class="fs-5 mt-3 mb-4 text-dark-emphasis">Registrar un libro en la biblioteca</p>
                        <div>
                            <a href="vistas/agregarLibros.php" class="btn btn-primary">ingresa</a>
                        </div>
                    </div>
                    <div class="col-lg-4 mx-lg-5 p-4 shadow rounded mt-4 card-options">
                        <h3 class="fs-4 fw-bold mt-4 text-dark-emphasis">Buscar</h3>
                        <p class="fs-5 mt-3 mb-4 text-dark-emphasis">Buscar un libro en la biblioteca</p>
                        <div>
                            <a href="vistas/buscarlibros.php" class="btn btn-primary">ingresa</a>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-lg-center mt-lg-4">
                    <div class="col-lg-4 mx-lg-5 p-4 shadow rounded mt-4 card-options">
                        <h3 class="fs-4 fw-bold mt-4 text-dark-emphasis">Modificar</h3>
                        <p class="fs-5 mt-3 mb-4 text-dark-emphasis">Modificar un libro de la biblioteca</p>
                        <div>
                            <a href="vistas/modificarlibros.php" class="btn btn-primary">ingresa</a>
                        </div>
                    </div>
                    <div class="col-lg-4 mx-lg-5 p-4 shadow rounded mt-4 card-options">
                        <h3 class="fs-4 fw-bold mt-4 text-dark-emphasis">Eliminar</h3>
                        <p class="fs-5 mt-3 mb-4 text-dark-emphasis">Eliminar un libro de la biblioteca</p>
                        <div>
                            <a href="vistas/eliminarlibros.php" class="btn btn-primary">ingresa</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>