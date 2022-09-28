<?php
include "adminFunctions.php";
include "requestComprobations.php";

session_start();
if(!$_SESSION["login"] && !$_SESSION["admin"]){
    header("Location: index.php");
}
//controll de menu 
if(isset($_GET['menu'])!=null){
    if($_GET['menu']=='principal'){
        $_SESSION["menu"]='principal';
    }
    if($_GET['menu']=='Profesorat'){
        $_SESSION["menu"]='Profesorat';
    }
    if($_GET['menu']=='cursos'){
        $_SESSION["menu"]='cursos';
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
    <link rel="stylesheet" href="../style/styles.css">
</head>
    <body>
        <div class="main">
        <header><?php echo "Benvingut administrador";  ?></header>
        <?php
        menuPrincipal();
        
        if($_SESSION["menu"]=='principal'){
            principal();
        }
        if($_SESSION["menu"]=='Profesorat'){
            //mostrar menu opcions admin profesors
            Profesorat();
            //controll de errors creacio de profesor i creacio de profesors
            mainCreatorProfessor();
            buscarNormal();
        }
        if($_SESSION["menu"]=='cursos'){
            //mostrar menu opcions admin cursos
            cursos();
            buscarNormal();
        }
        
        
        ?>
        </div> 
        <footer><p>Info BDN</p><p>Telefon: 932 20 03 77</p><p>Contacta amb l'encarregat : admin@gmail.ru</p></footer>
    </body>
    
</html>