<?php

session_start();
if(!isset($_SESSION['usuario_id'])){
    header("location:../login.php");
    exit();
}

$success = false;
$failed = false;

include "../includes/conexion.php";

try{
    $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT cod,titulo,autor,genero FROM biblioteca;";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $success=true;
    } else {
        $failed=true;
    }
}catch(PDOException $e){
    echo 'Hubo un error de conexion'.$e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="text-center">
        <a class="fs-1" href="../index.php"><i class="bi bi-house-add"></i></a>
    </header>
    <main class="container text-center">
        <h3 class="text-center text-dark-emphasis my-3">Catalogo actualizado al <span id="fecha"></span></h3>
        <?php if($failed){ ?>
            <p class="text-center text-dark-emphasis fs-5">Aun no tiene libros registrados</p>
            <div class="text-center">
                <a href="./agregarLibros.php" class="btn btn-primary">Registrar libros</a>
            </div> 
        <?php } ?>

        <?php if($success){ ?>
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="text-center d-inline-block card-info">
                        <div class="card text-bg-dark mb-3" style="width: 18rem;height:14rem;">
                        <div class="card-header card-content">ISBN: <?php echo $row['cod'];?></div>
                        <div class="card-body">
                            <h5 class="card-title card-content"><?php echo $row['titulo'];?></h5>
                            <p class="card-text mt-3 card-content"><?php echo $row['autor'];?></p>
                            <p class="card-text mt-3 card-content"><?php echo $row['genero'];?></p>
                        </div>
                        </div>
                    </div>
            <?php } ?>
        <?php } ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../js/catalogo.js"></script>
</body>
</html>