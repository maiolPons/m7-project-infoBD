<?php
include 'mysqlLogin.php';
//FUNCIONS DE CONEXIO I LOGIN//
///////////////////////////////

//crea la conexio a la base de dades

//La funcio comprova que l'usuari existeixi
function comprobarAdmin($usuari,$pass){
    $conexion = concetarBD();
    $pass = md5($pass); 
    $sql = "SELECT * FROM administrador WHERE nomUsuari='$usuari' AND Contrasenya='$pass'";
    $consulta = mysqli_query($conexion,$sql);
    $registre = mysqli_fetch_array($consulta);
    if($consulta == false){
        mysqli_error($conexion);
    }
    else{
        return($registre);
    }
}
function comprobarAlmnes($usuari,$pass){
    $conexion = concetarBD();
    $pass = md5($pass); 
    $sql = "SELECT * FROM alumnes WHERE correuElectronic='$usuari' AND contrasenya='$pass'";
    $consulta = mysqli_query($conexion,$sql);
    $registre = mysqli_fetch_array($consulta);
    if($consulta == false){
        mysqli_error($conexion);
    }
    else{
        return($registre);
    }
}
function comprobarProfessor($usuari,$pass){
    $conexion = concetarBD();
    $pass = md5($pass); 
    $sql = "SELECT * FROM professors WHERE dni='$usuari' AND contrasenya='$pass'";
    $consulta = mysqli_query($conexion,$sql);
    $registre = mysqli_fetch_array($consulta);
    if($consulta == false){
        mysqli_error($conexion);
    }
    else{
        return($registre);
    }
}
?>

