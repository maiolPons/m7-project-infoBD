<?php
include "../functions/loginComprobations.php";
#crea la sesio
session_start();
#comprovar la contrasenya i el correu
if(isset($_POST["Usuari"]) && isset($_POST["pass"])){
    $registre=comprobarAdmin($_POST["Usuari"],$_POST["pass"]);
    if($registre!=null){
        #crear variables de sessio
        $_SESSION["login"]=true;
        $_SESSION["usuari"]=$registre;
        $_SESSION["menu"]='principal';
        #reenviar a la pagina portal.php
        header("Location: adminMenu.php");
    }
    else{
        echo "<p class='error'>Error amb l'usuari</p>";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/x-icon" href="img/fav.png">
    <title>Login</title>
</head>
    <body>
        <div class="main">
            
                <!--Formulari de login-->
                <form action="index.php" method="POST">
                    <label for="Usuari">Nom d'usuari:</label><br>
                    <input type="text" name="Usuari" require><br>
                    <label for="passwd">Introdueix la contrasenya:</label><br>
                    <input type="password" name="pass" require><br><br>
                    <input type="submit" value="Entrar">
                </form>

        </div> 
    </body>
    
</html>