<?php
include 'functions/loginComprobations.php';
include "alumne/alumneFuncions.php";
session_start();
if(!$_SESSION["login"]=="professor" || !$_SESSION["login"]=="alumne"){
    header("Location: index.php");
}
if(isset($_GET["opcio"])){
    if($_GET["opcio"]=="alta"){$_SESSION["menu"]="alta";}
    if($_GET["opcio"]=="baixa"){$_SESSION["menu"]="baixa";}
    if($_GET["opcio"]=="notes"){$_SESSION["menu"]="notes";}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/styles.css">
    <title>Document</title>
</head>
<body>
    <header><?php echo "Benvingut ".$_SESSION["login"]." : ".$_SESSION["usuari"]["nom"];  ?></header>
    <div class="mainMenu">
        <div class="navegacio">
            <img src="media/logo/InfoBDN-1.png" class="logo">
            <?php if($_SESSION["login"]=="professor"){menuAlunes();}?>
            <?php if($_SESSION["login"]=="alumne"){menuAlunes();}?>
        </div>
        <footer><p>Info BDN</p><p>Telefon: 932 20 03 77</p><p>Contacta amb l'encarregat : admin@gmail.ru</p></footer>
    <?php
        if($_SESSION["login"]=="professor"){
            
        }
        if($_SESSION["login"]=="alumne"){
            
            if($_SESSION["menu"]=="alta"){
                menuAlta();
            }
            if($_SESSION["menu"]=="baixa"){
                menuAlta();
            }
            if($_SESSION["menu"]=="notes"){
                menuAlta();
            }
        }
        ?>
    </div>
    
</body>
</html>