<?php

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['usuario_id'])){
        header("location:../login.php");
        exit();
    }

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_FILES["archivo_csv"]["name"])) {
        $archivo_temporal = $_FILES["archivo_csv"]["tmp_name"];

        $handle = fopen($archivo_temporal, "r");

        include "../includes/conexion.php";

        try {
            $pdo = new PDO('mysql:host='.$direccionServidor.';dbname='.$baseDatos, $usuarioBD, $contraseniaBD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $error = false; 
            $successCSV = false;
            $failedCSV = false;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if(count($data) == 6) {
                    $stmt = $pdo->prepare("INSERT INTO biblioteca (cod, titulo, autor, editorial, genero, disponibilidad) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute($data);
                } else {
                    $error = true;
                }
            }

            if ($error) {
                $failedCSV = true;
                header("location: ../vistas/agregarLibros.php?failedCSV=true");
            } else {
                $successCSV = true;
                header("location: ../vistas/agregarLibros.php?successCSV=true");
            }

            fclose($handle);
        } catch(PDOException $e) {
            echo 'Hubo un error de conexión '.$e->getMessage();
        }
    }
}

?>



