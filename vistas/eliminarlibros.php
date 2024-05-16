<?php

    session_start();

    if(!isset($_SESSION['usuario_id'])){
        header("location:../login.php");
        exit();
    }

    $success = false;
    $failed = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include "../includes/conexion.php";

        $errores = array();
    
        $eliminarIsbn=isset($_POST['eliminar'])?$_POST['eliminar']:null;

        if(empty($eliminarIsbn)){
            $errores['eliminar']="debes ingresar el ISBN a eliminar";
        }

        foreach($errores as $error){
            echo "<br/>".$error."</br>";
        }
        
        if (empty($errores)) {
            try {
                $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $eliminar = trim($_POST["eliminar"]);
                $consulta_existencia = "SELECT cod,titulo FROM biblioteca WHERE cod = :eliminar";
                $stmt = $pdo->prepare($consulta_existencia);
                $stmt->bindParam(':eliminar', $eliminar, PDO::PARAM_STR);
                $stmt->execute();

                if($stmt->rowCount()>0){
                    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                    $nombre_a_eliminar = $fila['titulo'];

                    $consulta_eliminar = 'DELETE FROM biblioteca WHERE cod = :eliminar';
                    $stmt_eliminar = $pdo->prepare($consulta_eliminar);
                    $stmt_eliminar->bindParam(':eliminar', $eliminar, PDO::PARAM_STR);
                    $stmt_eliminar->execute();

                    $success=true;
                }else{
                    $failed=true;
                }
            } catch (PDOException $e) {
                echo 'Hubo un error de conexion'.$e->getMessage();
            }
        } else {
            echo "No se registraron los datos, pongase en contacto con el administrador";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header class="my-3 text-center">
            <a class="fs-1" href="../index.php"><i class="bi bi-house-add"></i></a>
        </header>
        <div class="d-flex justify-content-center">
            <form class="bg-secondary-subtle rounded p-3 m-auto form-container-eliminar" method="post" id="formulario">
                <h2 class="fw-bold fs-2 text-center text-dark-emphasis mt-4">Eliminar</h2>
                <input type="number" class="form-control mt-4" name="eliminar" placeholder="ISBN a eliminar" required min="0">
                <div class="text-center mt-4">
                    <button class="btn btn-primary" type="submit">Eliminar</button>
                </div>
            </form>
        </div>
        <?php

        if ($success) {
            echo "<script>Swal.fire({
                text: 'El libro ".$nombre_a_eliminar." ha sido eliminado con éxito',
                icon: 'success'
            });</script>";
        }

        if ($failed) {
            echo "<script> Swal.fire({
                text: 'El ISBN ingresado no existe en la biblioteca',
                icon: 'error'
            });</script>";
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>