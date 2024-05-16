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

        $cod=(isset($_POST['codigo']))?$_POST['codigo']:null;
        $nombre=(isset($_POST['nombre']))?$_POST['nombre']:null;
        $autor=(isset($_POST['autor']))?$_POST['autor']:null;
        $editorial=(isset($_POST['editorial']))?$_POST['editorial']:null;
        $genero=(isset($_POST['genero']))?$_POST['genero']:null;
        $disp=(isset($_POST['disponibilidad']))?$_POST['disponibilidad']:null;
    
        if(empty($cod)){
            $errores['codigo']="debes ingresar el codigo";
        }
        if(empty($nombre)){
            $errores['nombre']="debes ingresar el nombre del libro";
        }
        if(empty($autor)){
            $errores['autor']="debes ingresar el autor del libro";
        }
        if(empty($editorial)){
            $errores['editorial']="debes ingresar la editorial del libro";
        }
        if(empty($genero)){
            $errores['genero']="debes ingresar el genero del libro";
        }
        if(empty($disp)){
            $errores['disponibilidad']="debes ingresar si el libro esta disponible o no";
        }
    
        foreach($errores as $error){
            echo "<br/>".$error."</br>";
        }
    
        if(empty($errores)){
            try{
                $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $queryCod = "SELECT cod FROM biblioteca WHERE cod = :cod";
                $stmt = $pdo->prepare($queryCod);
                $stmt->bindParam(':cod', $cod);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $failed = true;
                } else {
                    $sql = "INSERT INTO `biblioteca` (`cod`, `titulo`, `autor`, `editorial`, `genero`, `disponibilidad`)
                            VALUES (:codigo, :nombre, :autor, :editorial, :genero, :disponibilidad)";
                    $resultado = $pdo->prepare($sql);
                    $resultado->execute(array(
                        ':codigo' => $cod,
                        ':nombre' => $nombre,
                        ':autor' => $autor,
                        ':editorial' => $editorial,
                        ':genero' => $genero,
                        ':disponibilidad' => $disp
                    ));
                    $success = true;
                }

            }catch(PDOException $e){
                echo 'Hubo un error de conexion '.$e->getMessage();
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
    <title>Agregar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="my-3 text-center">
        <a class="fs-1" href="../index.php"><i class="bi bi-house-add"></i></a>
    </header>
    <div>
        <!--FORM REGISTER CSV-->

        <?php include "agregarLibrosArchivos.php";
        $failedCSV = isset($_GET['failedCSV']) ? $_GET['failedCSV'] : false;
        $successCSV = isset($_GET['successCSV']) ? $_GET['successCSV'] : false;
        ?>
        <?php if($failedCSV){ ?>
            <h4 class="text-center fs-5 text-danger"><strong>¡El registro debe tener 6 campos!</strong> Verifique su archivo</h4>
        <?php } ?>
        <?php if($successCSV){ ?>
            <div class="text-center">
                <h4 class="fs-5 text-success"><strong>¡Libros en el Archivo CSV registrados con exito!</strong>
                <a href="./buscarlibros.php" class="btn btn-outline-success px-2">Buscar</a>
                </h4>
            </div>
        <?php } ?>
        
        <!--FORM REGISTER-->

        <?php if($failed){ ?>
            <h4 class="text-center fs-5 text-danger"><strong>¡El ISBN ya existe!</strong> Ingrese un codigo valido</h4>
        <?php } ?>
        <?php if($success){ ?>
            <div class="text-center">
                <h4 class="fs-5 text-success"><strong>¡Libro registrado con exito!</strong>
                <a href="./buscarlibros.php" class="btn btn-outline-success px-2">Buscar</a>
                </h4>
            </div>
        <?php } ?>
    </div>
    <div class="container-lg">

        <div class="row">

            <div class="col-lg-6 h-auto d-lg-flex justify-content-center text-center">
                <form action="agregarLibros.php" method="post" id="formularioDeRegistroAgr" class="bg-secondary-subtle rounded p-3 form-container">
                    <h2 class="fw-bold fs-2 text-center text-dark-emphasis mt-1">Registrar</h2>
                        <input class="form-control mt-3" type="number" name="codigo" placeholder="ISBN" required min="0">
                        <input class="form-control mt-3" type="text" name="nombre" id="titulo" placeholder="Titulo" value="<?php echo isset($success) && $failed === false ? '' : (isset($nombre) ? $nombre : ''); ?>" required>
                        <div class="invalid-feedback"></div>
                        <input class="form-control mt-3" type="text" name="autor" id="autor" placeholder="Autor" value="<?php echo isset($success) && $failed === false ? '' : (isset($autor) ? $autor : ''); ?>" required>
                        <div class="invalid-feedback"></div>
                        <input class="form-control mt-3" type="text" name="editorial" id="editorial" placeholder="Editorial" value="<?php echo isset($success) && $failed === false ? '' : (isset($editorial) ? $editorial : ''); ?>" required>
                        <div class="invalid-feedback"></div>
                        <input class="form-control mt-3" type="text" name="genero" id="genero" placeholder="Genero" value="<?php echo isset($success) && $failed === false ? '' : (isset($genero) ? $genero : ''); ?>" required>
                        <div class="invalid-feedback"></div>
                        <div class="mt-3">
                            <select class="form-select" name="disponibilidad" id="disponibilidad" required>
                                <option value="" selected>Seleccione disponibilidad</option>
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                </form>
            </div>
            
            <div class="col-lg-6 mt-3 mt-lg-0 h-auto d-lg-flex justify-content-center text-center">
                <form action="agregarLibrosArchivos.php" method="post" class="bg-secondary-subtle rounded p-3 form-container-csv" enctype='multipart/form-data'>
                    <h2 class="fw-bold fs-2 text-center text-dark-emphasis mt-2">Registrar CSV</h2>
                    <p class="fs-5 text-center text-dark-emphasis mt-2">Respetar los 6 campos obligatorios de lo contrario no se subiran los archivos</p>
                    <h3 class="fw-bold fs-5 text-center text-dark-emphasis mt-2">Ejemplo:</h3>
                    <p class="fs-6 text-center text-dark-emphasis">9789504970934,El Duelo,Gabriel Rolón,Booket,Psicología,si</p>
                    <div class="text-center mt-4">
                        <input class="form-control" type="file"  name="archivo_csv" accept=".csv" required>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>

        </div>

    </div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../js/add.js"></script>
</body>
</html>