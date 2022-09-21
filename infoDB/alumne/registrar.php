<?php
function crearAlumne($formCreacio,$foto){
    if(!empty($formCreacio["dni"]) && !empty($formCreacio["contrasenya"]) && !empty($formCreacio["nom"]) && !empty($formCreacio["edat"]) && !empty($formCreacio["cognoms"]) && !empty($formCreacio["correuElectronic"]) && !empty($foto)){
        if(comprobarDniCorreu($formCreacio["correuElectronic"],$formCreacio["dni"])){
            if(comprovarImatge($_POST["dni"],$foto["foto"])){
               //genera nou nom per la foto
               $newName = generateFileName($_POST["dni"],$foto["foto"]["name"]);
               $target_dir = "media/alumnes/";
               $target_file = $target_dir . $newName;
               if (move_uploaded_file($foto["foto"]["tmp_name"], $target_file)) {
                   if(insetNouAlumne($_POST,$newName)){
                       echo "<p class='success'>Profesor Creat Correctament!</p>";
                   }
                   else{
                       echo "<p class='errorFormulari'>La Peticio no s'ha pogut prosessar!</p>";
                   }
                   
               }
            }
        }else{
            echo "<p class='warning'>Correu o dni ja existeixen!</p>";
        }
    }else{
        echo "<p class='warning'>Tots els camps son obligatoris!</p>";
        var_dump($formCreacio);
    }
    
}
//comprobar existencia de dni en creacio de profesor i codi de curs
function comprobarDniCorreu($correu,$dni){
    $conexion = concetarBD();
    $sql = "SELECT dni FROM alumnes WHERE dni='$dni' AND correuElectronic='$correu'";
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
function insetNouAlumne($formCreacio,$fotoNom){
    $conexion = concetarBD();
    $pass = md5($formCreacio['contrasenya']); 
    $dni=$formCreacio["dni"];
    $edat=$formCreacio["edat"];
    $correuElectronic=$formCreacio["correuElectronic"];
    $nom=$formCreacio["nom"];
    $cognom=$formCreacio["cognoms"];
    $sql = "INSERT INTO `alumnes`(`dni`, `nom`, `cognom`, `fotografia`, `edat`, `correuElectronic`, `contrasenya) VALUES ('$dni','$nom','$cognom','$fotoNom','$edat','$correuElectronic','$pass')";
    $consulta = mysqli_query($conexion,$sql);

    
    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }else{
        echo "<p class='succes'>Usuari creat Correctament!</p>";

    }
} 
function generateFileName($dni,$fotoName){
    $newnameExplode=explode(".",$fotoName);
    $positionType=count($newnameExplode);
    $extencion=$newnameExplode[$positionType-1];
    $newName = $dni . "." . $extencion;
    return($newName);
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