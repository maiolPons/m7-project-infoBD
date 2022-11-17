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
        ?>
        <script>
            alert("L'arxiu no es una imatge");
        </script>
        <?php
        $uploadOk = 0;
    }
    if ($foto["size"] > 5000000) {
        ?>
        <script>
            alert("Aquest arxiu pesa massa!");
        </script>
        <?php
        $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        ?>
        <script>
            alert("Format de imatge no soportat.");
        </script>
        <?php
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