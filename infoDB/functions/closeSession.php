<?php
    #inicia la sesio
    session_start();
    #destruir la sessio
    session_destroy();
    #reenviar a la pagina index.php
    header("Location: ../index.php");
?>