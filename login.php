<?php

session_start();

$failed=false;

if($_SERVER['REQUEST_METHOD']=="POST"){
    include "includes/conexion.php";

    $errores=array();

    $email=(isset($_POST['email']))?htmlspecialchars($_POST['email']):null;
    $password=(isset($_POST['password']))?$_POST['password']:null;

    if(empty($email)){
        $errores['email']="el email es obligatorio";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errores['email']="Formato de email incorrecto";
    }

    if(empty($password)){
        $errores['password']="la contraseña es obligatoria";
    }

    if(empty($errores)){
        try {
            $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql="SELECT * FROM sistema WHERE email=:email";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute(['email'=>$email]);
    
            $usuarios= $sentencia->fetchAll(PDO::FETCH_ASSOC);
    
            $login=false;
            foreach($usuarios as $user){  
                if(password_verify($password,$user['password'])){
                    $_SESSION['usuario_id']=$user['id'];
                    $_SESSION['usuario_nombre']=$user['nombres'];
                    $login=true;   
                }
            }

            if($login){
                header("location:index.php");
            }else{
                $failed=true;
            }
    
        }catch(PDOEXCEPTION $e){
            echo 'Hubo un error de conexion '.$e->getMessage();
        }
    }else{
        $failed=true;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 login-section">
            <div class="col-lg-4 mb-lg-5 ms-lg-5 pe-lg-0 ps-lg-5">
                <div class="text-center mb-lg-5">
                    <img class="mb-lg-5" style="width: 200px;" src="assets/isft232.webp" alt="isft Nº232">
                </div>
            </div>
            <div class="col-lg-4 mb-lg-5 me-lg-5 ps-lg-0 pe-lg-5 d-flex justify-content-center align-items-center">
                <div class="bg-secondary-subtle rounded px-4 pt-5 mb-lg-5 login-form">
                    <h2 class="text-body-secondary text-center mb-4">
                        Iniciar sesión
                    </h2>
                    <form action="login.php" id="formularioDeRegistro" method="post">
                        <div class="mb-4">
                            <input id="email" type="email" name="email" class="form-control" placeholder="correo electrónico" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-4">
                            <input id="clave" type="password" name="password" class="form-control" placeholder="contraseña" required>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" name="btningresar" class="btn btn-primary">Iniciar sesión</button>
                        </div>
                        <div class=" mt-3 d-lg-flex align-items-center">
                            <p class="m-0">¿No tienes una cuenta?</p>
                            <a href="registro.php" class="ps-2 text-decoration-none">Registrate</a>
                        </div>
                        <?php if($failed){ ?>
                            <div class="alert alert-primary alert-dismissible fade show p-2 my-1" role="alert">
                                No pudimos encontrar tu cuenta.
                                <button type="button" class="btn-close pe-2 pt-1" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="js/login.js"></script>
</body>
</html>