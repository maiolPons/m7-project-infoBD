<?php
include 'functions/loginComprobations.php';
include "alumne/registrar.php";
if(isset($_POST["CrearAlumne"])){
    $resultat=crearAlumne($_POST,$_FILES);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="main">
        <!--Formulari de creacio de profesorat-->
        <form action="registrarAlumne.php" method="POST" enctype="multipart/form-data">
            <label for="dni">DNI:</label><br>
            <input type="text" name="dni" require><br>
            <label for="correuElectronic">Correu Electronic:</label><br>
            <input type="text" name="correuElectronic" require><br>
            <label for="contrasenya">Contrasenya:</label><br>
            <input type="password" name="contrasenya" require><br><br>
            <label for="nom">Nom:</label><br>
            <input type="text" name="nom" require><br><br>
            <label for="cognoms">Cognoms:</label><br>
            <input type="text" name="cognoms" require><br><br>
            <label for="text">Edat</label><br>
            <input type="Edat" name="Edat" require><br><br>
            <label for="foto">Fotografia de perfil:</label><br>
            <label for="foto">formats suportats: .jpg .jpeg .png:</label><br>
            <input type="file" name="foto"><br><br>
            <input name="CrearAlumne" type="hidden" value="CrearAlumne">
            <input type="submit" value="Crear">
        </form>
    
    </div> 
</body>
</html>