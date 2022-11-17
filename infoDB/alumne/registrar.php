<?php
function crearAlumne($formCreacio,$foto){
    if(!empty($_POST["dni"]) && !empty($_POST["contrasenya"]) && !empty($_POST["nom"]) && !empty($_POST["cognoms"]) && !empty($_POST["correuElectronic"]) && !empty($_FILES)){
        if(comprobarDniCorreu($formCreacio["correuElectronic"],$formCreacio["dni"])){
            if(comprovarImatge($foto["foto"])){
               //genera nou nom per la foto
               $newName = generateFileName($_POST["dni"],$foto["foto"]["name"]);
               $target_dir = "media/alumnes/";
               $target_file = $target_dir . $newName;
               if (move_uploaded_file($foto["foto"]["tmp_name"], $target_file)) {
                   if(insetNouAlumne($_POST,$newName)){
                    ?>
                    <script>
                        alert("Usuari Creat Correctament!");
                    </script>
                    <?php
                   }
                   else{
                    ?>
                    <script>
                        alert("La Peticio no s'ha pogut prosessar!");
                    </script>
                    <?php
                   }
                   
               }
            }
        }else{
            ?>
            <script>
                alert("Correu o dni ja existeixen!");
            </script>
            <?php
        }
    }else{
        ?>
        <script>
            alert("Tots els camps son obligatoris!");
        </script>
        <?php
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
    $sql = "INSERT INTO `alumnes`(`dni`, `nom`, `cognom`, `fotografia`, `edat`, `correuElectronic`, `contrasenya`,`estat`) VALUES ('$dni','$nom','$cognom','$fotoNom','$edat','$correuElectronic','$pass','1')";
    $consulta = mysqli_query($conexion,$sql);

    
    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }else{
        header("Location: index.php");
        return(true);
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
function comprovarImatge($foto){
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