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
    <title>Document</title>
</head>
<body>
    <?php

    if($_SESSION["login"]=="professor"){
        
    }
    if($_SESSION["login"]=="alumne"){
        menuAlunes();
        if($_SESSION["menu"]=="alta"){
            menuAlta();
        }
        if($_SESSION["menu"]=="baixa"){

        }
        if($_SESSION["menu"]=="notes"){

        }
    }
    ?>
</body>
</html>