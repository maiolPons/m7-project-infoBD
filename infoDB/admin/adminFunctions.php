<?php

//mostrar menu principal admin
function menuPrincipal(){
    echo '<div class="navegacio">';
    echo '<a href="adminMenu.php?menu=' . 'principal' . '"><img src="../media/logo/InfoBDN-1.png" class="logo"></a>';
    echo "<nav>";   
    echo '<a href="adminMenu.php?menu=' . 'Profesorat' . '">Profesorat</a>';
    echo '<a href="adminMenu.php?menu=' . 'cursos' . '">cursos</a>'; 
    echo '<a href="../functions/closeSession.php">Tancar sessio</a>';
    echo "</nav>";
    echo '</div>';
}
//mostra per defecte 
function principal(){
    echo "PLACEHOLDER";
}
function mostrarMenuLateral(){
    echo"<div class='navegaciolateral'>";
        echo"<nav>";
            echo '<a href="adminMenu.php?opcio=' . 'llistar' . '">llistar</a>';
            echo '<a href="adminMenu.php?opcio=' . 'crear' . '">crear</a>';
            echo '<a href="adminMenu.php?opcio=' . 'modificar' . '">modificar</a>';
            echo '<a href="adminMenu.php?opcio=' . 'eliminar' . '">eliminar</a>';
        echo"</nav>";
    echo"</div>";
}
//menu opcions admin de profesorat
function Profesorat(){
    //en cas de modificacio
    formulariModificarProfesor();
    comprobacioErrorsModifcarProfesor();
    confirmacioEliminar();
    if(isset($_GET['opcio'])!=null){
        //llistar profesorat
        $_SESSION["opcio"]=$_GET['opcio'];
        if($_SESSION["opcio"]=="llistar"){
            mostrarLlistaprofesors();
        }
        //crea sesssio per a la opcio del menu de creacio
        if($_SESSION["opcio"]=="crear"){
            ?>
            <div class="main">
                <!--Formulari de creacio de profesorat-->
                
                <form action="adminmenu.php" method="POST" enctype="multipart/form-data">
                <h3>Crear profesor<h3>
                    <input placeholder="DNI" type="text" name="dni" require><br><br>
                    <input placeholder="Contrasenya" type="password" name="contrasenya" require><br><br>
                    <input placeholder="Nom" type="text" name="nom" require><br><br>
                    <input placeholder="Cognoms" type="text" name="cognoms" require><br><br>
                    <input placeholder="Titol academin" type="titol" name="titol" require><br><br>
                    <label for="foto">Fotografia de perfil:</label><br>
                    <label for="foto">formats suportats: .jpg .jpeg .png:</label><br>
                    <input type="file" name="foto"><br><br>
                    <input name="crearprof" type="hidden" value="crearprof">
                    <input type="submit" value="Crear">
                </form>
    
        </div> 
            <?php
        }
        if($_SESSION["opcio"]=="modificar"){
            modificarProfesor();
        }
        if($_SESSION["opcio"]=="eliminar"){
            EliminarProfesor();
        }
    }
    
}
//Controll de creacio de profesor
function mainCreatorProfessor(){
    if(isset($_POST["crearprof"])){
        if(!empty($_POST["dni"]) && !empty($_POST["contrasenya"]) && !empty($_POST["nom"]) && !empty($_POST["cognoms"]) && !empty($_POST["titol"]) && !empty($_FILES)){
            if(comprobardni('professors',$_POST["dni"])){
                if(comprovarImatge($_FILES["foto"])){
                    //genera nou nom per la foto
                    $newName = generateFileName($_POST["dni"],$_FILES["foto"]["name"]);
                    $target_dir = "../media/professors/";
                    $target_file = $target_dir . $newName;
                    
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        if(crearProfesor($_POST,$newName)){
                            ?>
                            <script>
                                alert("Profesor Creat Correctament!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
                        }
                        else{
                            ?>
                            <script>
                                alert("La Peticio no s'ha pogut prosessar!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
                        }
                        
                    } else {
                        ?>
                        <script>
                            alert("Error amb la imatge!");
                        </script>
                        <?php
                        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
                    }
                }
            }
            else{
                ?>
                <script>
                    alert("El dni ja esta registrat!");
                </script>
                <?php
                echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
            }
        }
        else{
            ?>
            <script>
                alert("Tots els camps son obligatoris!");
            </script>
            <?php
            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
        }
    }
}
//Creacio de usuari profesor nou
function crearProfesor($informacioProfesor,$newName){
    $conexion = concetarBD();
    //sequencia sql per insertar profesor
    $pass = md5($informacioProfesor['contrasenya']); 
    $dni=$informacioProfesor["dni"];
    $titolAcademic=$informacioProfesor["titol"];
    $nom=$informacioProfesor["nom"];
    $cognom=$informacioProfesor["cognoms"];
    $sql = "INSERT INTO `professors`(`dni`, `contrasenya`, `nom`, `cognom`, `fotografia`, `titolAcademic`) VALUES ('$dni','$pass','$nom','$cognom','$newName','$titolAcademic')";
    $consulta = mysqli_query($conexion,$sql);

    if($consulta == false){
        mysqli_error($conexion);
        @unlink( "../media/professors/".$newName);
        return(false);
    }
    else{
        return(true);
    }
}
//mostrarllista amb opcio per modificar
function modificarProfesor(){
    if(isset($_GET['opcio'])){
        if($_SESSION["menu"]=="Profesorat"){
            if($_GET["opcio"]=="modificar"){
                mostrarLlistaprofesors();
            }
        }
    }
}
//formulari de modificar profesor
function formulariModificarProfesor(){
    if(isset($_GET['modificarDni'])){
        $conexion = concetarBD();
        $dni=$_GET['modificarDni'];
        $sql = "SELECT * FROM professors WHERE dni='$dni'";
        $consulta = mysqli_query($conexion,$sql);
        $registre = mysqli_fetch_array($consulta);
        if($consulta == false){
            mysqli_error($conexion);
            return(false);
        }
        else{
            $fotoname=$registre["fotografia"];
            $rutafoto="../media/professors/" . $fotoname;
            echo' <form action="adminmenu.php" method="POST" enctype="multipart/form-data">';
                echo'<label for="dni">DNI:</label><br>';
                echo'<input value='.$registre["dni"].' type="text" name="dni" require><br>';
                echo'<label for="contrasenya">Contrasenya:</label><br>';
                echo'<input value='.$registre["contrasenya"].' type="password" name="contrasenya" require><br><br>';
                echo'<label for="nom">Nom:</label><br>';
                echo'<input value='.$registre["nom"].' type="text" name="nom" require><br><br>';
                echo'<label for="cognoms">Cognoms:</label><br>';
                echo'<input value='.$registre["cognom"].' type="text" name="cognoms" require><br><br>';
                echo'<label for="text">Titol academic</label><br>';
                echo'<input value='.$registre["titolAcademic"].' type="titol" name="titol" require><br><br>';
                echo'<label for="fotoCanvi">Seleccionar una altre imatge de perfil:</label><br>';
                echo '<div class="radioButonDiv">';
                echo'<label for="fotoCanvi">Si</label>';
                echo'<input type="radio" name="fotoCanvi" value="si">';
                echo'<label for="fotoCanvi">No</label>';
                echo'<input type="radio" name="fotoCanvi" value="no" checked><br>';
                echo'</div>';
                echo '<img class="fotoMostra" src='.$rutafoto.'><br>';
                echo'<label for="foto">Fotografia de perfil:</label><br>';
                echo'<label placeholder='.$rutafoto.' for="foto">formats suportats: .jpg .jpeg .png:</label><br>';
                echo'<input type="file" name="foto"><br><br>';
                echo'<input name="modificarProf" type="hidden" value="modificarProf">';
                echo'<input name="oldFotoName" type="hidden" value="'.$rutafoto.'">';
                echo'<input name="oldDni" type="hidden" value='.$registre["dni"].'>';
                echo'<input type="submit" value="modificar">';
            echo'</form>';
            echo "<br><br>";
        }
    }
}
//comprobacio de error al modificar
function comprobacioErrorsModifcarProfesor(){
    if(isset($_POST["modificarProf"])){
        if(!empty($_POST["dni"]) && !empty($_POST["contrasenya"]) && !empty($_POST["nom"]) && !empty($_POST["cognoms"]) && !empty($_POST["titol"]) && !empty($_FILES) && $_POST["fotoCanvi"]=="si"){
            if(comprovarImatge($_FILES["foto"])){
                if(comprobardniModificar('professors',$_POST["dni"],$_POST["oldDni"])){
                    @unlink($_POST["oldFotoName"]);
                    $newName = generateFileName($_POST["dni"],$_FILES["foto"]["name"]);
                    $target_dir = "../media/professors/";
                    $target_file = $target_dir . $newName;
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        if(updateProfesorInfo($_POST,$newName,$_POST["oldDni"])){
                            ?>
                            <script>
                                alert("El profesor s'ha actualitzat correctament!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
                        }else{
                            ?>
                            <script>
                                alert("Error al actualitzar el profesor!");
                            </script>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <script>
                            alert("Error amb la imatge!");
                        </script>
                        <?php
                    }
                }
                else{
                    ?>
                    <script>
                        alert("El dni ja existeix!");
                    </script>
                    <?php
                }                  
            }
        }
        if($_POST["fotoCanvi"]=="no"){
            if(!empty($_POST["dni"]) && !empty($_POST["contrasenya"]) && !empty($_POST["nom"]) && !empty($_POST["cognoms"]) && !empty($_POST["titol"])){
                if(comprobardniModificar('professors',$_POST["dni"],$_POST["oldDni"])){
                    if($_POST["oldDni"]!=$_POST["dni"]){
                        $newName=generateFileName($_POST["dni"],$_POST["oldFotoName"]);
                        rename($_POST["oldFotoName"],"../media/professors/".$newName);
                        if(updateProfesorInfo($_POST,$newName,$_POST["oldDni"])){
                            ?>
                            <script>
                                alert("El profesor s'ha actualitzat correctament!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
                        }
                        else{
                            ?>
                            <script>
                                alert("Error al actualitzar el profesor!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
                        }
                    }else{
                        if(updateProfesorInfo($_POST,$_POST["oldFotoName"],$_POST["oldDni"])){
                            ?>
                            <script>
                                alert("El profesor s'ha actualitzat correctament!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
                        }
                        else{
                            ?>
                            <script>
                                alert("Error al actualitzar el profesor!");
                            </script>
                            <?php 
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
                        }
                    }
                }
                else{
                    ?>
                    <script>
                        alert("El dni ja existeix!");
                    </script>
                    <?php 
                    echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
                }
            }
            else{
                ?>
                <script>
                    alert("Tots els camps son obligatoris!");
                </script>
                <?php
                echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=modificar">';
            }
        }
    }
}
//enviar update a la base de dades
function updateProfesorInfo($informacioProfesor,$foto,$oldDni){
    $pass = md5($informacioProfesor['contrasenya']); 
    $dni=$informacioProfesor["dni"];
    $cognoms=$informacioProfesor["cognoms"];
    $titol=$informacioProfesor["titol"];
    $dni=$informacioProfesor["dni"];
    $nom=$informacioProfesor["nom"];
    $conexion = concetarBD();
    $sql = "UPDATE `professors` SET `dni`='$dni',`contrasenya`='$pass',`nom`='$nom',`cognom`='$cognoms',`fotografia`='$foto',`titolAcademic`='$titol' WHERE dni='$oldDni'";

    $consulta = mysqli_query($conexion,$sql);
    
    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }else{
        return(true);
    }
}
//cambia el nom de les fotos
function generateFileName($dni,$fotoName){
    $newnameExplode=explode(".",$fotoName);
    $positionType=count($newnameExplode);
    $extencion=$newnameExplode[$positionType-1];
    $newName = $dni . "." . $extencion;
    return($newName);
}
//mostrar llista profesors
function mostrarLlistaprofesors(){
    echo "<div class='buscadorTaula'>";
                echo "<div class='buscadorf'>";
                    buscador();
                echo "<div>";
                echo "<div class='contenidorTaula'>";
                    echo "<div class='contenidorTaula2'>";
                        llistarTaulaProfessors();
                    echo "</div>";
                echo "</div>";
            echo "</div>";
}
//Eliminar profesor
function EliminarProfesor(){
    if(isset($_GET['opcio'])){
        if($_SESSION["menu"]=="Profesorat"){
            if($_GET["opcio"]=="eliminar"){
                mostrarLlistaprofesors();
            }
        }
    }
}
//formulari de confirmacio i peticio per elimiar profesor
function confirmacioEliminar(){
    if(isset($_GET['eliminarDni'])){
        echo "<p class='warning'>Estas segur que vols eliminar Aquest usuari?</p>";
        echo' <form class="formEliminar" action="adminmenu.php" method="POST" enctype="multipart/form-data">';
        echo '<div class="radioButonDiv">';
                echo'<label for="eliminarDni">Si</label>';
                echo'<input type="radio" name="eliminarDni" value="si">';
                echo'<label for="eliminarDni">No</label>';
                echo'<input type="radio" name="eliminarDni" value="no" checked><br>';
                echo'<input name="eliminarDni" type="hidden" value='.$_GET['eliminarDni'].'>';
        echo '</div>';
                echo'<input type="submit" value="eliminar">';
            echo'</form>';
    }
    if(isset($_POST["eliminarDni"])){
        $foto=$_POST["eliminarDni"];
        $newnameExplode=explode(".",$_POST["eliminarDni"]);
        $dni= $newnameExplode[0];

        $conexion = concetarBD();
        $sql = "DELETE FROM `professors` WHERE `dni`='$dni'";
        $consulta = mysqli_query($conexion,$sql);
        if($consulta == false){
            mysqli_error($conexion);
        }else{
            @unlink("../media/professors/".$foto);
            ?>
            <script>
                alert("Profesor eliminar correctament");
            </script>
            <?php 
            $_GET['opcio']="eliminar";
        }
    }
}
//llistar profesors
function llistarTaulaProfessors(){
    $conexion = concetarBD();
    $sql = "SELECT * FROM professors";
    $consulta = mysqli_query($conexion,$sql);

    if($consulta == false){
        mysqli_error($conexion);
    }
    else{
        if(mysqli_num_rows($consulta)==0){
            echo "<p class='warning'>0 professors registrats!</p>";
        }else{
            echo "<table>";
                echo "<tr>";
                    echo "<th>DNI</th><th>Nom</th><th>Cognom</th><th>fotografia</th><th>titol Academic</th>";
                    if($_GET["opcio"]=="modificar"){
                        echo '<th>Modificar</td>';
                    }
                    if($_GET["opcio"]=="eliminar"){
                        echo '<th>Eliminar</td>';
                    }
                echo "</tr>";
            while($row = mysqli_fetch_array($consulta)){
                $pass=md5($row["contrasenya"]);
                echo "<tr>";
                echo "<td>$row[dni]</td>";
                echo "<td>$row[nom]</td>";
                echo "<td>$row[cognom]</td>";
                echo "<td class='fotoTd'><img class='fotoLlista' src='../media/professors/$row[fotografia]'></td>";
                echo "<td>$row[titolAcademic]</td>";
                if($_GET["opcio"]=="modificar"){
                    echo '<td><a href="adminMenu.php?modificarDni=' . $row["dni"] . '">Modificar</a></td>';
                }
                if($_GET["opcio"]=="eliminar"){
                    echo '<td><a href="adminMenu.php?eliminarDni=' . $row["fotografia"] . '">Eliminar</a></td>';
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}
//menu opcions admin de cursos
function cursos(){
    //if(isset($_GET['opcio'])){
    //    $_SESSION["opcio"]=$_GET['opcio'];
    //}
    if(isset($_GET['opcio'])){
        if($_GET["opcio"]=="llistar"){
            mostrarLlistacursos();
        }
        if($_GET["opcio"]=="crear"){
                    $sql2 = "SELECT dni, nom FROM professors";
                    $conexion = concetarBD();
                    $consulta2 = mysqli_query($conexion,$sql2);
            ?>
            <div class="main">
                <!--Formulari de creacio de curs-->
                <form action="adminmenu.php" method="POST">
                    <label for="codi">Codi:</label><br>
                    <input type="text" name="codi" require><br>
                    <label for="nom">Nom:</label><br>
                    <input type="text" name="nom" require><br><br>
                    <label for="descripcio">Descripcio:</label><br>
                    <input type="textarea" name="descripcio" require><br><br>
                    <label for="hores">Hores:</label><br>
                    <input type="text" name="hores" require><br><br>
                    <label for="dataInici">Data Inici:</label><br>
                    <input type="date" name="dataInici" require><br><br>
                    <label for="dataFinal">Data Final:</label><br>
                    <input type="date" name="dataFinal" require><br><br>
                    <?php
                    echo "<select name='llistaProfessors'>";
                    echo '<option value=NULL></option>';
                    while($row = mysqli_fetch_array($consulta2)){
                        $dni=$row["dni"];
                        $name="DNI: ".$dni.' NOM: '.$row["nom"];
                        echo '<option value='.$dni.'>'.$name.'</option>';
                    }
                    echo "</select><br><br>";
                    ?>
                    <input name="crearCurs" type="hidden" value="crearCurs">
                    <input type="submit" value="Crear">
                </form>
    
        </div> 
            <?php
        }
        if($_GET["opcio"]=="modificar"){
            modificarcurs();
        }
        if($_GET["opcio"]=="eliminar"){
            
        }
    }
    //modificar curs
    formulariModificarCurs();
    comprobacioErrorsModifcarCursos();
    //Crear curs
    mainCreatorCursos();
    //Eliminar curs
    confirmacioEliminarCurs();
    Eliminarcurs();
}
//Controll de creacio de curs
function mainCreatorCursos(){
    if(isset($_POST["crearCurs"])){
        if(!empty($_POST["codi"]) && !empty($_POST["nom"]) && !empty($_POST["descripcio"]) && !empty($_POST["hores"]) && !empty($_POST["dataInici"])&& !empty($_POST["dataFinal"])){
            if(comprobardni('cursos',$_POST["codi"])){
                $today=date('d-m-Y',strtotime("-1 days"));
                if($_POST["dataInici"]>$today){
                    if($_POST["dataInici"]<$_POST["dataFinal"]){
                        if(crearcurs($_POST)){
                            ?>
                            <script>
                                alert("Curs Creat Correctament!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=llistar">';
                            
                        }
                        else{
                            ?>
                            <script>
                                alert("La Peticio no s'ha pogut prosessar!");
                            </script>
                            <?php
                            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
                        }
                    }
                    else{
                        ?>
                        <script>
                            alert("La data d'inici no pot ser menor a la data final!");
                        </script>
                        <?php
                        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
                    }
                }
                else{
                    ?>
                    <script>
                        alert("La data no pot ser mes petita que la data actual!");
                    </script>
                    <?php
                    echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
                }
            }
            else{
                ?>
                <script>
                    alert("Error amb el codi!");
                </script>
                <?php
                echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
            }
        }
        else{
            ?>
            <script>
                alert("Tots els camps son obligatoris!");
            </script>
            <?php
            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=adminMenu.php?opcio=crear">';
        }
    }
}
//Creacio de usuari profesor nou
function crearcurs($informacioCurs){
    $conexion = concetarBD();
    //sequencia sql per insertar Curs
    $codi=$informacioCurs["codi"];
    $nom=$informacioCurs["nom"];
    $descripcio=$informacioCurs["descripcio"];
    $hores=$informacioCurs["hores"];
    $dataInici=$informacioCurs["dataInici"];
    $dataFinal=$informacioCurs["dataFinal"];
    $professor=$informacioCurs["llistaProfessors"];
    $sql = "INSERT INTO `cursos`(`codi`, `nom`, `descripcio`, `hores`, `dataInici`, `dataFinal`, `CursProfessorFK`,`estat`) VALUES ('$codi','$nom','$descripcio','$hores','$dataInici','$dataFinal','$professor','1')";

    $consulta = mysqli_query($conexion,$sql);

    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }
    else{
        return(true);
    }
}
















//mostrarllista amb opcio per modificar
function modificarcurs(){
    if(isset($_GET['opcio'])){
        if($_SESSION["menu"]=="cursos"){
            if($_GET["opcio"]=="modificar"){
                mostrarLlistacursos();
            }
        }
    }
}
//formulari de modificar profesor
function formulariModificarCurs(){
    if(isset($_GET['modificarCodi'])){
        $conexion = concetarBD();
        $codi=$_GET['modificarCodi'];
        $sql = "SELECT * FROM cursos WHERE codi='$codi'";
        $consulta = mysqli_query($conexion,$sql);
        $registre = mysqli_fetch_array($consulta);

        $sql2 = "SELECT dni, nom FROM professors";
        $consulta2 = mysqli_query($conexion,$sql2);

        if($consulta == false and $consulta2 == false){
            mysqli_error($conexion);
            return(false);
        }
        else{
            echo' <form action="adminmenu.php" method="POST" enctype="multipart/form-data">';
                echo'<label for="codi">Codi:</label><br>';
                echo'<input value='.$registre["codi"].' type="text" name="codi" require><br>';
                echo'<label for="nom">Nom:</label><br>';
                echo'<input value='.$registre["nom"].' type="text" name="nom" require><br><br>';
                echo'<label for="descripcio">Descripcio :</label><br>';
                echo'<input value='.$registre["descripcio"].' type="textarea" name="descripcio" require><br><br>';
                echo'<label for="hores">Hores:</label><br>';
                echo'<input value='.$registre["hores"].' type="text" name="hores" require><br><br>';
                echo'<label for="text">Data Inici</label><br>';
                echo'<input value='.$registre["dataInici"].' type="date" name="dataInici" require><br><br>';
                echo'<label for="text">Data Final</label><br>';
                echo'<input value='.$registre["dataFinal"].' type="date" name="dataFinal" require><br><br>';
                echo "<select name='llistaProfesors'>";
                echo '<option value='.$registre["CursProfessorFK"].'></option>';
                while($row = mysqli_fetch_array($consulta2)){
                    $dni=$row["dni"];
                    $name="DNI: ".$dni.' NOM: '.$row["nom"];
                    echo '<option value='.$dni.'>'.$name.'</option>';
                }
                echo "</select'>";
                echo'<input name="modificarcurs" type="hidden" value="modificarcurs">';
                echo'<input name="oldCodi" type="hidden" value='.$registre["codi"].'>';
                echo'<br><br><input type="submit" value="modificar">';
            echo'</form>';
        }
    }
}
function comprobacioErrorsModifcarCursos(){
    if(isset($_POST["modificarcurs"])){
        if(!empty($_POST["codi"]) && !empty($_POST["nom"]) && !empty($_POST["descripcio"]) && (!empty($_POST["hores"]) || $_POST["hores"]==0) && !empty($_POST["dataInici"])&& !empty($_POST["dataFinal"])){
            if(comprobardniModificar('cursos',$_POST["codi"],$_POST["oldCodi"])){
                if($_POST["dataInici"]<$_POST["dataFinal"]){
                    if(updateProfesorCursos($_POST)){
                        ?>
                        <script>
                            alert("Curs modificat correctament");
                        </script>
                        <?php
                        echo '<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=adminMenu.php?opcio=modificar">';
                        $_GET['opcio']="modificar";
                    }else{
                        ?>
                        <script>
                            alert("Error en la modificacio!");
                        </script>
                        <?php
                        echo '<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=adminMenu.php?modificarCodi='.$_POST["codi"].'">';
                    }
                }
                else{
                    ?>
                    <script>
                        alert("Error amb la data!");
                    </script>
                    <?php
                    echo '<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=adminMenu.php?modificarCodi='.$_POST["codi"].'">';
                }
            }else{
                ?>
                <script>
                    alert("Codi invalid!");
                </script>
                <?php
                echo '<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=adminMenu.php?modificarCodi='.$_POST["codi"].'">';
            }
        }
        else{
            ?>
            <script>
                alert("Tots el camps son obligatoris!");
            </script>
            <?php
            echo '<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=adminMenu.php?modificarCodi='.$_POST["codi"].'">';
            //var_dump($_POST);
        }
    }
}
//enviar update a la base de dades
function updateProfesorCursos($informacioCurs){
    $codi=$informacioCurs["codi"];
    $nom=$informacioCurs["nom"];
    $descripcio=$informacioCurs["descripcio"];
    $hores=$informacioCurs["hores"];
    $dataInici=$informacioCurs["dataInici"];
    $dataFinal=$informacioCurs["dataFinal"];
    $llistaProfesors=$informacioCurs["llistaProfesors"];
    $oldcodi=$informacioCurs["oldCodi"];
    $conexion = concetarBD();
    $sql = "UPDATE `cursos` SET `codi`='$codi',`nom`='$nom',`descripcio`='$descripcio',`hores`='$hores',`dataInici`='$dataInici',`dataFinal`='$dataFinal',`CursProfessorFK`='$llistaProfesors' WHERE codi='$oldcodi'";
    if($llistaProfesors=="NULL"){
        $sql = "UPDATE `cursos` SET `codi`='$codi',`nom`='$nom',`descripcio`='$descripcio',`hores`='$hores',`dataInici`='$dataInici',`dataFinal`='$dataFinal',`CursProfessorFK`=NULL WHERE codi='$oldcodi'";
    }

    $consulta = mysqli_query($conexion,$sql);
    
    if($consulta == false){
        mysqli_error($conexion);
        return(false);
    }else{
        return(true);
    }
}




//formulari de confirmacio i peticio per elimiar profesor
function confirmacioEliminarCurs(){
    if(isset($_GET['eliminarCodi'])){
        echo "<p class='warning'>Estas segur que vols Cambiar l'estat Aquest Curs?</p>";
        echo' <form class="formEliminar" action="adminmenu.php" method="POST" enctype="multipart/form-data">';
            echo '<div class="radioButonDiv">';
                echo'<label for="eliminarCodi">Si</label>';
                echo'<input type="radio" name="eliminarCodi" value="si">';
                echo'<label for="eliminarCodi">No</label>';
                echo'<input type="radio" name="eliminarCodi" value="no" checked><br>';
            echo '</div>';
                echo'<input name="eliminarCodi" type="hidden" value='.$_GET['eliminarCodi'].'>';
                echo'<input type="submit" value="eliminar">';
            echo'</form>';
    }
    if(isset($_POST["eliminarCodi"])){
        $codi= $_POST["eliminarCodi"];
        $conexion = concetarBD();
        //$sql = "DELETE FROM `cursos` WHERE `codi`='$codi'";
        
        

        try {
            $sql = "SELECT `estat` FROM cursos WHERE `codi`='$codi'";
            $consulta = mysqli_query($conexion,$sql);
            $registre = mysqli_fetch_row($consulta);
            if($registre[0]==1){
                $sql = "UPDATE `cursos` SET `estat`='0' WHERE `codi`='$codi'";
            }else{
                $sql = "UPDATE `cursos` SET `estat`='1' WHERE `codi`='$codi'";
            }
            mysqli_query($conexion,$sql);
        } catch (Exception $e) {
            echo 'Excepcio capturada: ',  $e->getMessage(), "\n";
        }
        if($consulta == false){
            mysqli_error($conexion);
        }else{
            ?>
            <script>
                alert("Curs Des/Activat correctament");
            </script>
            <?php
            $_GET['opcio']="eliminar";
        }
    }
}
//mostrarllista amb opcio per Eliminar
function Eliminarcurs(){
    if(isset($_GET['opcio'])){
        if($_SESSION["menu"]=="cursos"){
            if($_GET["opcio"]=="eliminar"){
                mostrarLlistacursos();
            }
        }
    }
}







//mostrar cursos
function mostrarLlistacursos(){
            echo "<div class='buscadorTaula'>";
                echo "<div class='buscadorf'>";
                    buscador();
                echo "<div>";
                echo "<div class='contenidorTaula'>";
                    echo "<div class='contenidorTaula2'>";
                    llistarTaulacursos();
                    echo "</div>";
                echo "</div>";
            echo "</div>";
}
//llistar cursos
function llistarTaulacursos(){
    $conexion = concetarBD();
    $sql = "SELECT * FROM cursos";
    $consulta = mysqli_query($conexion,$sql);
    if($consulta == false){
        mysqli_error($conexion);
    }
    else{
        if(mysqli_num_rows($consulta)==0){
            echo "<p class='warning'>0 cursos registrats!</p>";
        }else{
            echo "<table>";
                echo "<tr>";
                
                    echo "<th>codi</th><th>nom</th><th>descripcio</th><th>hores</th><th>Data inici</th><th>Data final</th><th>Profesor</th><th>Estat</th>";
                    if(isset($_GET["opcio"])){
                    if($_GET["opcio"]=="modificar"){
                        echo '<th>Modificar</td>';
                    }
                    if($_GET["opcio"]=="eliminar"){
                        echo '<th>Des/Activar</td>';
                    }
                    echo "</tr>";
                }
                    
            while($row = mysqli_fetch_array($consulta)){

                echo "<tr>";
                echo "<td>$row[codi]</td>";
                echo "<td>$row[nom]</td>";
                echo "<td>$row[descripcio]</td>";
                echo "<td>$row[hores]</td>";
                echo "<td>$row[dataInici]</td>";
                echo "<td>$row[dataFinal]</td>";
                echo "<td>$row[CursProfessorFK]</td>";
                $estat = "Desactivat";
                if($row["estat"]==1){
                    $estat = "Activat";
                }
                echo "<td>$estat</td>";
                if(isset($_GET["opcio"])){
                if($_GET["opcio"]=="modificar"){
                    echo '<td><a href="adminMenu.php?modificarCodi=' . $row["codi"] . '">Modificar</a></td>';
                }
                if($_GET["opcio"]=="eliminar"){
                    echo '<td><a href="adminMenu.php?eliminarCodi=' . $row["codi"] . '">Des/Activar</a></td>';
                }
                echo "</tr>";
                }           
            }
            echo "</table>";
        }
    }
}










function buscador(){
    ?>
        <!--Formulari de creacio de profesorat-->
        <form class="buscadorform" action='adminmenu.php' method="POST" enctype="multipart/form-data">
            <label for="buscar">Buscar: </label><br>
            <input type="text" name="buscar" require><br>
            <input name="buscarcheck" type="hidden" value="buscarcheck">
            <input type="submit" value="buscar">
        </form>
    <?php
} 
//peticio de buscador
function buscarNormal(){
    if(isset($_POST["buscarcheck"])){
        //comprobracio errors peticio i seleccio de query
        $queryVar="%".$_POST["buscar"]."%";
        if($_SESSION["menu"]=="Profesorat"){
            $sql = "SELECT * FROM professors WHERE dni LIKE '$queryVar' or nom like '$queryVar' or cognom like '$queryVar' or titolAcademic like '$queryVar'";
        } 
        else{
            $sql = "SELECT * FROM cursos WHERE codi LIKE '$queryVar' or nom like '$queryVar' or descripcio like '$queryVar' or hores like '$queryVar'";
        }
        $conexion = concetarBD();
        $consulta = mysqli_query($conexion,$sql);
        if($consulta == false){
            mysqli_error($conexion);
        }
        //mostrar resultat de la peticio
        if(mysqli_num_rows($consulta)==0){
            echo "<p class='warning'>0 registres trobats!</p>";
        }
        else{
            if($_SESSION["menu"]=="Profesorat"){
                echo "<div class='contenidorTaula3'>";
                buscador();
                echo "<div class='contenidorTaula'>";
                echo "<div class='contenidorTaula2'>";
                    echo "<table>";
                        echo "<tr>";
                            echo "<th>DNI</th><th>Nom</th><th>Cognom</th><th>fotografia</th><th>titol Academic</th>";
                            if(isset($_SESSION["opcio"])){
                                if($_SESSION["opcio"]=="modificar"){
                                    echo '<th>Modificar</td>';
                                }
                                if($_SESSION["opcio"]=="eliminar"){
                                    echo '<th>Eliminar</td>';
                                }
                            }
                            if(isset($_POST["opcio"])){
                                if($_POST["opcio"]=="modificar"){
                                    echo '<th>Modificar</td>';
                                }
                                if($_POST["opcio"]=="eliminar"){
                                    echo '<th>Eliminar</td>';
                                }
                            }
                            
                        echo "</tr>";
                    while($row = mysqli_fetch_array($consulta)){
        
                        echo "<tr>";
                        echo "<td>$row[dni]</td>";
                        echo "<td>$row[nom]</td>";
                        echo "<td>$row[cognom]</td>";
                        echo "<td class='fotoTd'><img class='fotoLlista' src='../media/professors/$row[fotografia]'></td>";
                        echo "<td>$row[titolAcademic]</td>";
                        if(isset($_SESSION["opcio"])){
                            if($_SESSION["opcio"]=="modificar"){
                                echo '<td><a href="adminMenu.php?modificarDni=' . $row["dni"] . '">Modificar</a></td>';
                            }
                            if($_SESSION["opcio"]=="eliminar"){
                                echo '<td><a href="adminMenu.php?eliminarCodi=' .$row["dni"] . '">Eliminar</a></td>';
                            }
                        }
                        
                        echo "</tr>";      
                    }
                    echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            if($_SESSION["menu"]=="cursos"){
                echo "<div class='contenidorTaula3'>";
                buscador();
                echo "<div class='contenidorTaula'>";
                echo "<div class='contenidorTaula2'>";
                echo "<table>";
                echo "<tr>";
                
                    echo "<th>codi</th><th>nom</th><th>descripcio</th><th>hores</th><th>Data inici</th><th>Data final</th><th>Profesor</th>";
                    if(isset($_SESSION["opcio"])){
                    if($_SESSION["opcio"]=="modificar"){
                        echo '<th>Modificar</td>';
                    }
                    if($_SESSION["opcio"]=="eliminar"){
                        echo '<th>Des/Activar</td>';
                    }
                    if($_SESSION["opcio"]=="llistar"){
                        echo '<th>estat</td>';
                    }
                    
                    echo "</tr>";
                }
                    
            while($row = mysqli_fetch_array($consulta)){

                echo "<tr>";
                echo "<td>$row[codi]</td>";
                echo "<td>$row[nom]</td>";
                echo "<td>$row[descripcio]</td>";
                echo "<td>$row[hores]</td>";
                echo "<td>$row[dataInici]</td>";
                echo "<td>$row[dataFinal]</td>";
                echo "<td>$row[CursProfessorFK]</td>";
                if(isset($_SESSION["opcio"])){
                if($_SESSION["opcio"]=="modificar"){
                    echo '<td><a href="adminMenu.php?modificarCodi=' . $row["codi"] . '">Modificar</a></td>';
                }
                if($_SESSION["opcio"]=="eliminar"){
                    echo '<td><a href="adminMenu.php?eliminarCodi=' . $row["codi"] . '">Des/Activar</a></td>';
                }
                if($_SESSION["opcio"]=="llistar"){
                    $estat = "Desactivat";
                if($row["estat"]==1){
                    $estat = "Activat";
                }
                    echo '<td>' . $estat. '</td>';
                }
                echo "</tr>";
                }           
            }
            echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
            
    }
    
}

?>