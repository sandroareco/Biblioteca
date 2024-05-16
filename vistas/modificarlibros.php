<?php

session_start();
if(!isset($_SESSION['usuario_id'])){
    header("location:../login.php");
    exit();
}

$success=false;
$failed=false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../includes/conexion.php";

    $errores = array();

    $modificar=isset($_POST['modificar_cod'])?$_POST['modificar_cod']:null;

    if(empty($modificar)){
        $errores['modificar_cod']="debes ingresar el ISBN a modificar";
    }

    foreach($errores as $error){
        echo "<br/>".$error."</br>";
    }



    if (empty($codigo)) {
        try{
            $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $codigo = trim($_POST["modificar_cod"]);

            $sql = "SELECT * FROM biblioteca WHERE cod = :codigo";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->execute();

            if($stmt->rowCount () > 0){
                $success=true;
            }else{
                $failed=true;
            }

        }catch(PDOException $e){
            echo 'Hubo un error de conexion'.$e->getMessage();
        }
    }else{
        echo "No se registraron los datos, pongase en contacto con el administrador";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar</title>
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
            <form class="bg-secondary-subtle rounded p-3 m-auto form-container-modificar" method="post">
                <h2 class="fw-bold fs-2 text-center text-dark-emphasis mt-4">Modificar</h2>
                <input type="number" class="form-control mt-4" name="modificar_cod" placeholder="Buscar ISBN a modificar" required min="0">
                <div class="text-center mt-4">
                    <button class="btn btn-primary" type="submit">Modificar</button>
                </div>
            </form>
        </div>

        <div>
            <?php if ($failed) {
                echo "<script> Swal.fire({
                text: 'El I.S.B.N ingresado no existe en la biblioteca',
                icon: 'error'
                });</script>";
            } ?>
            <?php if ($success) { ?>
                <h3 class="text-center my-4 text-success fw-bold">Datos encontrados tras la búsqueda:</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="text-center;" scope="col"><b>Codigo</b></td>
                                <td class="text-center;" scope="col"><b>Titulo</b></td>
                                <td class="text-center;" scope="col"><b>Autor</b></td>
                                <td class="text-center;" scope="col"><b>Editorial</b></td>
                                <td class="text-center;" scope="col"><b>Genero</b></td>
                                <td class="text-center;" scope="col"><b>Disponibilidad</b></td>
                                <td class="text-center;" scope="col"><b>Acción</b></td>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td class="text-center;"><?php echo $row['cod'] ?></td>
                                    <td class="text-center;"><?php echo $row['titulo'] ?></td>
                                    <td class="text-center;"><?php echo $row['autor'] ?></td>
                                    <td class="text-center;"><?php echo $row['editorial'] ?></td>
                                    <td class="text-center;"><?php echo $row['genero'] ?></td>
                                    <td class="text-center;"><?php echo $row['disponibilidad'] ?></td>
                                    <td class="text-center;">
                                        <a class="btn btn-primary" style="text-decoration:none;" href="modificar.php?id=<?php echo $row['cod']; ?>">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
    
</body>
</html>