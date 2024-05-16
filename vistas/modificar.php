<?php
session_start();
    
if(!isset($_SESSION['usuario_id'])){
    header("location:../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
    include '../includes/conexion.php';

    if(isset($_GET["id"])){
        $id = $_GET["id"];
    
        try {
            $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $usuarios = "SELECT * FROM biblioteca WHERE cod = :id";
            $stmt = $pdo->prepare($usuarios);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            }
    
        } catch(PDOException $e){
            echo 'Hubo un error de conexion'.$e->getMessage();
        }
    }

    ?>
    <header class="my-3 text-center">
        <a class="fs-1" href="./modificarlibros.php"><i class="bi bi-arrow-left"></i></a>
    </header>
    <div class="container d-flex justify-content-center h-auto">

        <form class="bg-secondary-subtle rounded p-3 m-lg-auto form-container" action="procesar_modificacion.php" method="post" id="formularioDeRegistroAgr">

            <h2 class="fw-bold fs-2 text-center text-dark-emphasis mt-1">Datos del usuario a modificar</h2>

            <input class="form-control mt-3" type="hidden" value="<?php echo $row['cod']?>" name="id">
            <input class="form-control mt-3" type="text" id="titulo" placeholder="Titulo" value="<?php echo $row['titulo']?>" name="titulo" required>
            <div class="invalid-feedback"></div>
            <input class="form-control mt-3" type="text" id="autor" placeholder="Autor" value="<?php echo $row['autor']?>" name="autor" required>
            <div class="invalid-feedback"></div>
            <input class="form-control mt-3" type="text" id="editorial" placeholder="Editorial" value="<?php echo $row['editorial']?>" name="editorial" required>
            <div class="invalid-feedback"></div>
            <input class="form-control mt-3" type="text" id="genero" placeholder="Genero" value="<?php echo $row['genero']?>" name="genero" required>
            <div class="invalid-feedback"></div>
            <p class="mt-3 text-center fw-bold text-dark-emphasis">Detalles por si el libro fue prestado sino [Si/No]:</p>
            <input class="form-control mt-3" type="text" id="disponibilidad" placeholder="Disponibilidad" value="<?php echo $row['disponibilidad']?>" name="disponibilidad" required>
            <div class="invalid-feedback"></div>

            <div class="text-center mt-3">
                <button class="btn btn-primary" type="submit" name="modificar">Modificar</button>
            </div>

        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="../js/edit.js"></script>
</body>
</html>
