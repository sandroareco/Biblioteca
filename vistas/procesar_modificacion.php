<?php

include_once '../includes/conexion.php';

session_start();
if(!isset($_SESSION['usuario_id'])){
    header("location:../login.php");
    exit();
}

$cod = $_POST['id'];
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$editorial = $_POST['editorial'];
$genero = $_POST['genero'];
$disponibilidad = $_POST['disponibilidad'];

try {
    $pdo=new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos,$usuarioBD,$contraseniaBD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $actualizar = "UPDATE biblioteca SET titulo=:titulo, autor=:autor, editorial=:editorial, genero=:genero, disponibilidad=:disponibilidad WHERE cod = :id";
    $stmt = $pdo->prepare($actualizar);

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':editorial', $editorial);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':disponibilidad', $disponibilidad);
    $stmt->bindParam(':id', $cod);

    
    $resultado = $stmt->execute();

    if ($resultado) {
        echo "<script>alert('Se han actualizado los cambios correctamente, actualice la página para ver los cambios'); window.location='/Proyecto_cecilia./vistas/modificarlibros.php';</script>";
    } else {
        echo "<script>alert('No se pudieron actualizar los datos'); window.history.go(-1);</script>";
    }

    } catch(PDOException $e){
        echo 'Hubo un error de conexion'.$e->getMessage();
    }
?>
