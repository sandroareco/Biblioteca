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
    
        $searchCod = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

        if(empty($searchCod)){
            $errores['keyword']="debes ingresar la palabra clave a buscar";
        }

        if (empty($errores)) {
            try{
                $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $keyword = "%$searchCod%";
    
                $sql = "SELECT * FROM biblioteca WHERE 
                cod LIKE :keyword OR
                titulo LIKE :keyword OR
                autor LIKE :keyword OR
                editorial LIKE :keyword OR
                genero LIKE :keyword OR
                disponibilidad LIKE :keyword";
    
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $success=true;
                } else {
                    $failed=true;
                }
            }catch(PDOException $e){
                echo 'Hubo un error de conexion'.$e->getMessage();
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header class="my-3 text-center">
            <a class="fs-1" href="../index.php"><i class="bi bi-house-add"></i></a>
        </header>
        <div class="d-flex justify-content-center h-auto">
            <form action="buscarlibros.php" class="bg-secondary-subtle rounded p-3 m-auto form-container-buscar" id="formularioDeRegistroBus" method="post">
                <h2 class="fw-bold fs-2 text-center text-dark-emphasis mt-4">Buscar</h2>
                <input type="text" class="form-control mt-4" name="keyword" id="keyword" placeholder="Buscar..." required>
                <div class="invalid-feedback"></div>
                <div class="text-center mt-4">
                    <button class="btn btn-primary" type="submit" name="buscar">Buscar catálogo</button>
                </div>
            </form>
        </div>
    </div>
        <div>
            <?php
            if($failed || (isset($_POST['keyword']) && $searchCod !== '' && !$success)){
                echo "<script> Swal.fire({
                text: 'El libro ingresado no existe en la biblioteca',
                icon: 'error'
                });</script>";
            }
            ?>
            <?php if($success){ ?>
                <h3 class="text-center my-4 text-success fw-bold">Datos encontrados tras la busqueda:</h3>
                <div class="container-md table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col"><b>Codigo</b></th>
                                <th class="text-center" scope="col"><b>Titulo</b></th>
                                <th class="text-center" scope="col"><b>Autor</b></th>
                                <th class="text-center" scope="col"><b>Editorial</b></th>
                                <th class="text-center" scope="col"><b>Genero</b></th>
                                <th class="text-center" scope="col"><b>Disponibilidad</b></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td class="text-center"><?php echo $row['cod']?></td>
                                <td class="text-center"><?php echo $row['titulo']?></td>
                                <td class="text-center"><?php echo $row['autor']?></td>
                                <td class="text-center"><?php echo $row['editorial']?></td>
                                <td class="text-center"><?php echo $row['genero']?></td>
                                <td class="text-center"><?php echo $row['disponibilidad']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <br>
            <?php } ?>
        </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../js/search.js"></script>
</body>
</html>