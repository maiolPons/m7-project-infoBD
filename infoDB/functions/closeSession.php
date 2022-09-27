<?php
    #inicia la sesio
    session_start();
    #destruir la sessio
    if($_SESSION["admin"]){
        session_destroy();
        #reenviar a la pagina index.php
        header("Location: ../admin/index.php");
    }else{
        session_destroy();
        #reenviar a la pagina index.php
        header("Location: ../index.php");
    }
    
    
    
?>