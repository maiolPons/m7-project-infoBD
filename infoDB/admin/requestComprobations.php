<?php
include "../functions/mysqlLogin.php";
//comprobar existencia de dni en creacio de profesor i codi de curs
function comprobardni($taula,$id){
    $conexion = concetarBD();
    if($taula=="professors"){
        $sql = "SELECT dni FROM $taula WHERE dni='$id'";
    }else{
        $sql = "SELECT codi FROM $taula WHERE codi='$id'";
    }
    
    $consulta = mysqli_query($conexion,$sql);
    $registre = mysqli_fetch_array($consulta);
    
    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }
    else{
        if($registre==null){
            return(true);
        }
        else{
            return(false);
        }
    }
} 
//comprobar existencia de dni en creacio de profesor
function comprobardniModificar($taula,$id,$oldId){
    $conexion = concetarBD();
    if($taula=="professors"){
        $sql = "SELECT dni FROM $taula WHERE dni='$id'";
    }
    else{
        $sql = "SELECT codi FROM $taula WHERE codi='$id'";
    }
    
    $consulta = mysqli_query($conexion,$sql);
    $registre = mysqli_fetch_array($consulta);
    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }
    else{
        if($registre==null){
            return(true);
        }
        else{
            if($taula=="professors"){
                if($registre["dni"]==$oldId){
                    return(true);
                }
                else{
                    return(false);
                }
            }else{
                if($registre["codi"]==$oldId){
                    return(true);
                }
                else{
                    return(false);
                }
            }
            
        }
    }
} 
//Comprovacions de fotografia
function comprovarImatge($foto){;
    $target_file = basename($foto["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    $check = getimagesize($foto["tmp_name"]);
    if($check !== false) {;
        $uploadOk = 1;
    } else {
        echo "<p class='errorFormulari'>L'arxiu no es una imatge</p><br>";
        $uploadOk = 0;
    }
    if ($foto["size"] > 500000) {
        echo "<p class='errorFormulari'>Aquest arxiu pesa massa!</p><br>";
        $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "<p class='errorFormulari'>Format de imatge no soportat.</p><br>";
        $uploadOk = 0;
    }
    if($uploadOk == 1){
        return(true);
    }
    else{
        return(false);
    }
}
?>