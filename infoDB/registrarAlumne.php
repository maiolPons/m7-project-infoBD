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
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
    <div class="globalRegistre">
        <a href="index.php"><img src="media/logo/back.png" class="backimg"></a>
    <div class="registerborder">
    <div class="registermainform">
        <!--Formulari de creacio de profesorat-->
        <h3>Registrar</h3>
        <form action="registrarAlumne.php" method="POST" enctype="multipart/form-data">
            <input placeholder="Dni" type="text" name="dni" require>
            <input placeholder="Correu Electronic" type="text" name="correuElectronic" require>
            <input placeholder="Contrasenya" type="password" name="contrasenya" require>
            <input placeholder="Nom" type="text" name="nom" require>
            <input placeholder="cognoms" type="text" name="cognoms" require>
            <input placeholder="Edat" type="Edat" name="Edat" require>
            <br>
            <label for="foto">Fotografia de perfil:</label>
            <label for="foto">formats suportats: .jpg .jpeg .png:</label><br><br>
            <input type="file" name="foto">
            <input name="CrearAlumne" type="hidden" value="CrearAlumne">
            <input type="submit" value="Crear">
        </form>
    </div> 
    </div>
    </div>
    <footer><p>Info BDN</p><p>Telefon: 932 20 03 77</p><p>Contacta amb l'encarregat : admin@gmail.ru</p></footer>

</body>
</html>