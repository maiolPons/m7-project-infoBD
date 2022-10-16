<?php
include 'functions/loginComprobations.php';
#crea la sesio
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <link rel="icon" type="image/x-icon" href="img/fav.png">
    <title>Login</title>
</head>
    <body>
        <div class="bigBorder">
        <div class="bigBorder">
        <div class="bigBorder">
            <div class="borderdiv">
                <div class="loginformmain">
                    <!--Formulari de login-->
                    <div class="formlogin">
                        <form action="indexProfessor.php" method="POST">
                            <label for="Usuari">Iniciar sessio Professor</label><br><br>
                            <input placeholder="Nom d'usuari" type="text" name="Usuari" require><br><br>
                            <input placeholder="Contrasenya" type="password" name="pass" require><br><br>
                            <input type="submit" value="Continuar">
                        </form>
                    </div>
                    <div class="linkslogin">
                        <p>Cambiar login</p>
                        <a href="index.php">Login Alumne</a>
                    </div>
                </div>
            </div>
            <?php 
            #comprovar la contrasenya i el correu
            if(isset($_POST["Usuari"]) && isset($_POST["pass"])){
                $registre=comprobarProfessor($_POST["Usuari"],$_POST["pass"]);
                if($registre!=null){
                    #crear variables de sessio
                    $_SESSION["login"]="professor";
                    $_SESSION["usuari"]=$registre;
                    $_SESSION["nom"]=$_POST["Usuari"];
                    $_SESSION["menu"]='principal';
                    #reenviar a la pagina portal.php
                    header("Location: menu.php");
                }
            else{
                echo "<p class='error'>Error amb l'usuari</p>";
            }
        }
        ?>
        </div>
        </div>   
        </div>
        <footer><p>Info BDN</p><p>Telefon: 932 20 03 77</p><p>Contacta amb l'encarregat : admin@gmail.ru</p></footer>
    </body>
    
</html>