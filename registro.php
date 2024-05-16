<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "includes/conexion.php";
    
    $errores = array();

    $success = false;
    $emailSuccess=false;

    $nombre=(isset($_POST['nombres']))?$_POST['nombres']:null;
    $apellido=(isset($_POST['apellidos']))?$_POST['apellidos']:null;
    $correo=(isset($_POST['email']))?$_POST['email']:null;
    $password=(isset($_POST['password']))?$_POST['password']:null;
    $confirmarPassword=(isset($_POST['confirmarPassword']))?$_POST['confirmarPassword']:null;
    $genero=(isset($_POST['genero']))?$_POST['genero']:null;


    if(empty($nombre)){
        $errores['nombres']="debes ingresar tu nombre";
    }
    if(empty($apellido)){
        $errores['apellidos']="debes ingresar tu apellido";
    }
    if(empty($genero)){
        $errores['genero']="debes ingresar genero";
    }
    if(empty($correo)){
        $errores['email']="el email es obligatorio";
    }elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $errores['email']="Formato de email incorrecto";
    }

    if(empty($password)){
        $errores['password']="la contraseña es obligatoria";
    }

    if(empty($confirmarPassword)){
        $errores['confirmarPassword']="confirma la contraseña";
    }elseif($password!=$confirmarPassword){
        $errores['confirmarPassword']="las contraseñas no coinciden";
    }

    if(empty($errores)){
        try{
            $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $queryEmail = "SELECT email FROM sistema WHERE email = :email";
            $stmt = $pdo->prepare($queryEmail);
            $stmt->bindParam(':email', $correo);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                $emailSuccess=true;
            }else{
                $nuevoPassword=password_hash($password,PASSWORD_DEFAULT);

                $sql="INSERT INTO `sistema` (`id`, `nombres`, `apellidos`, `email`, `password`, `genero`) 
                VALUES (NULL,:nombres,:apellidos,:email,:password,:genero)";
                $resultado=$pdo->prepare($sql);
                $resultado->execute(array(
                    ':nombres'=>$nombre,
                    ':apellidos'=>$apellido,
                    ':email'=>$correo,
                    ':password'=>$nuevoPassword,
                    ':genero'=>$genero
                ));
                $success=true;    
            }
    
        }catch(PDOException $e){
            echo 'Hubo un error de conexion '.$e->getMessage();
        }
    }
    else{
        echo "Error de envio de datos, por favor comuniquese con el administrador";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="bg-secondary-subtle rounded p-4 col-md-8 col-lg-8">
                <?php if (isset($success) && !$emailSuccess) { ?>

                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Registro realizado con exito!</strong> 
                    <a href="login.php" class="btn btn-outline-success px-2">Iniciar sesión</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <?php } ?>

                <h2 class="text-body-secondary mb-4">
                    Crea una cuenta
                </h2>

                <form class="row g-3" action="registro.php" id="formularioDeRegistro" method="post">

                    <div class="col-md-6">
                        <label for="" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombres" id="nombres" value="<?php echo isset($success) && $emailSuccess === false ? '' : (isset($nombre) ? $nombre : ''); ?>"  required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">Apellido:</label>
                        <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?php echo isset($success) && $emailSuccess === false ? '' : (isset($apellido) ? $apellido : ''); ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-12">
                        <label for="" class="form-label">Correo electrónico:</label>
                        <input type="email" class="form-control <?php echo $emailSuccess ? 'is-invalid' : '' ;?>" name="email" id="email" placeholder="123@gmail.com" value="<?php echo isset($success) && $emailSuccess === false ? '' : (isset($correo) ? $correo : ''); ?>" required>
            
                        <?php if(isset($emailSuccess)){?>
                            <div class="invalid-feedback">El correo electrónico ya está registrado</div>
                        <?php } else { ?>
                            <div class="invalid-feedback"></div>
                        <?php } ?>
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" name="password" id="password" value="<?php echo isset($success) && $emailSuccess === false ? '' : (isset($password) ? $password : ''); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="" class="form-label">Confirmar contraseña:</label>
                        <input type="password" class="form-control" name="confirmarPassword" id="confirmarPassword" value="<?php echo isset($success) && $emailSuccess === false ? '' : (isset($confirmarPassword) ? $confirmarPassword : ''); ?>" required>
                        <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="" class="form-label">Género:</label>
                        <select class="form-select" name="genero" id="genero" required>
                            <option value="" selected>Seleccione su género</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex p-2">
                        <button type="submit" class="btn btn-success">Registrarme</button>
                        <a href="login.php" class="d-flex align-items-center text-decoration-none px-2">¿Ya tienes una cuenta?</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="js/register.js"></script>
</body>
</html>
