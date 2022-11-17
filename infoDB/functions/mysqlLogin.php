<?php
function concetarBD(){
    $conexion = mysqli_connect("localhost","root","","infobdn");
    if($conexion == false){
        mysqli_connect_error();
    }
    else{
        return($conexion);
    }
}

?>