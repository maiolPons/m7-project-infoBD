<?php
    function menuAlunes(){
        echo"<nav>";
            echo "<a href='menu.php?opcio=" . "alta" . "'>Donarme d'alta</a>";
            echo '<a href="menu.php?opcio=' . 'baixa' . '">Donarme de baixa</a>';
            echo '<a href="menu.php?opcio=' . 'notes' . '">Notes</a>';
            echo '<a href="functions/closeSession.php">Tancar Sessio</a>';
        echo"</nav>";
    }
    function menuProfessors(){
        echo"<nav>";
            echo "<a href='menu.php?opcio=" . "Llistar_cursos" . "'>Cursos</a>";
            echo '<a href="functions/closeSession.php">Tancar Sessio</a>';
        echo"</nav>";
    }
    function monstrarTaula(){
        echo "<div class='buscadorTaula'>";
            echo "<div class='buscadorf'>";
                buscador();
            echo "<div>";
            echo "<div class='contenidorTaula'>";
                echo "<div class='contenidorTaula2'>";
                llistarTaulacursosPerAlumne();
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }
    function buscador(){
        ?>
            <div class="main">
                <!--Formulari de creacio de profesorat-->
                <form class="buscadorform" action="menu.php" method="POST" enctype="multipart/form-data">
                    <label for="buscar">Buscar: </label><br>
                    <input type="text" name="buscar" require><br>
                    <input name="buscarcheck" type="hidden" value="buscarcheck">
                    <?php
                    if(isset($_GET["curs"])){
                        echo '<input name="cursCodi" type="hidden" value="'.$_GET["curs"].'">';
                    }
                    ?>
                    <input type="submit" value="buscar">
                </form>
            </div> 
        <?php
    } 
    //llistar cursos
    function llistarTaulacursosPerAlumne(){

        $conexion = concetarBD();
        $today=date('Y-m-d');
        $dni=$_SESSION["usuari"]["dni"];
        
        if($_SESSION["menu"]=="alta"){
            //$sql = "SELECT * FROM cursos C WHERE `dataInici` > DATE '$today' AND estat = '1'  AND codi NOT IN (SELECT FKCursosCODI FROM matricules WHERE FKAlumnesDNI = '$dni') AND CursProfessorFK IS NOT NULL";
            $sql = "SELECT * FROM cursos C WHERE `dataInici` > DATE '$today' AND estat = '1' AND CursProfessorFK IS NOT NULL  AND codi NOT IN (SELECT FKCursosCODI FROM matricules WHERE FKAlumnesDNI = '$dni' AND estatM = '1')";
        }
        if($_SESSION["menu"]=="baixa"){
            $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON C.codi = FKCursosCODI WHERE `dataInici` > DATE '$today' AND `FKAlumnesDNI` = '$dni' AND estatM != '0'";
        }
        if($_SESSION["menu"]=="notes"){
            $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON c.codi = M.FKCursosCODI WHERE `dataFinal` < DATE '$today' AND `FKAlumnesDNI` = '$dni'";
        }
        if(isset($_POST["buscarcheck"])){
            $varQuery="%".$_POST["buscar"]."%";
            
            if($_SESSION["menu"]=="notes"){
                $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON c.codi = M.FKCursosCODI WHERE `dataFinal` < DATE '$today' AND `FKAlumnesDNI` = '$dni' AND (`codi`LIKE'$varQuery' or `nom`LIKE'$varQuery' or `descripcio`LIKE'$varQuery' or `CursProfessorFK`LIKE'$varQuery')";
            }
            if($_SESSION["menu"]=="baixa"){
                $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON C.codi = FKCursosCODI WHERE `dataInici` > DATE '$today' AND `FKAlumnesDNI` = '$dni' AND estatM != '0' AND (`codi`LIKE'$varQuery' or `nom`LIKE'$varQuery' or `descripcio`LIKE'$varQuery' or `CursProfessorFK`LIKE'$varQuery')";
            }
            if($_SESSION["menu"]=="alta"){
                $sql = "SELECT * FROM cursos C WHERE `dataInici` > DATE '$today' AND estat = '1' AND CursProfessorFK IS NOT NULL  AND codi NOT IN (SELECT FKCursosCODI FROM matricules WHERE FKAlumnesDNI = '$dni' AND estatM = '1') AND (`codi`LIKE'$varQuery' or `nom`LIKE'$varQuery' or `descripcio`LIKE'$varQuery' or `CursProfessorFK`LIKE'$varQuery')";
            }
        }
        //var_dump($sql);
        $consulta = mysqli_query($conexion,$sql);
        if($consulta == false){
            mysqli_error($conexion);
        }
        else{
            if(mysqli_num_rows($consulta)==0){
                echo "<p class='warning'>No hi ha cursos disponibles!</p>";
            }else{
                
                echo "<table>";
                    echo "<tr>";
                        echo "<th>codi</th><th>nom</th><th>descripcio</th><th>hores</th><th>Data inici</th><th>Data final</th><th>Profesor</th>";
                        if(isset($_SESSION["menu"])){
                        if($_SESSION["menu"]=="alta"){
                            echo '<th>Donar alta</td>';
                        }
                        if($_SESSION["menu"]=="baixa"){
                            echo '<th>Donar baixa</td>';
                        }
                        if($_SESSION["menu"]=="notes"){
                            echo '<th>nota</td>';
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
                    if(isset($_SESSION["menu"])){
                    if($_SESSION["menu"]=="alta"){
                        //<a href="menu.php?alta=' . $row["codi"] . '">Donar de Alta</a> 
                        echo '<td><button onclick="confirmacioM('.$row["codi"].')">Donar alta</button></td>';
                    }
                    if($_SESSION["menu"]=="baixa"){
                        //echo '<td><a href="menu.php?baixa=' . $row["codi"] . '">Donar de Baixa</a></td>';
                        echo '<td><button onclick="confirmacioD('.$row["codi"].')">Donar de Baixa</button></td>';
                    }
                    if($_SESSION["menu"]=="notes"){
                        //echo '<td><a href="menu.php?baixa=' . $row["codi"] . '">Donar de Baixa</a></td>';
                        echo '<td>'.$row['nota'].'</td>';
                    }
                    echo "</tr>";
                    }           
                }
                echo "</table>";
            }
        }
    }
    //desmatricula el alumne del curs
    function desmatricular(){
        if(isset($_COOKIE["desmatricular"]) && $_COOKIE["desmatricular"] != "x"){
            $curs=$_COOKIE["desmatricular"];
            $dni=$_SESSION["usuari"]["dni"];
            $sql = "UPDATE `matricules` SET `estatM`='0' WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            $conexion = concetarBD();
            $consulta = mysqli_query($conexion,$sql);
            setcookie("desmatricular", "x");
            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=menu.php">';
            if($consulta == false){
                mysqli_error($conexion);
            }
        }
    }
    //matricula alumne al curs
    function matricular(){
        if(isset($_COOKIE["matricular"]) && $_COOKIE["matricular"] != "x"){
            $conexion = concetarBD();
            $curs=$_COOKIE["matricular"];
            
            $dni=$_SESSION["usuari"]["dni"];
            $sql = "SELECT * FROM `matricules` WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            $consulta = mysqli_query($conexion,$sql);
            $registre = mysqli_fetch_row($consulta);

            if($registre == null){
                $sql = "INSERT INTO `matricules`(`FKAlumnesDNI`, `FKCursosCODI`, `nota`, `estatM`) VALUES ('$dni','$curs',null,'1')";
            }else{
                $sql = "UPDATE `matricules` SET `estatM`='1' WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            }
            setcookie("matricular", "x");
            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=menu.php">';
            
            $consulta = mysqli_query($conexion,$sql);
            if($consulta == false){
                mysqli_error($conexion);
            }
        }
    }
    //llista cursos del profesor logat
    function llistarCursos(){
        if(isset($_GET["opcio"]) && $_GET["opcio"]=="Llistar_cursos"){
            $conexion = concetarBD();
            $dni=$_SESSION["usuari"]["dni"];
            $sql = "SELECT `codi`,`nom` FROM `cursos` WHERE `CursProfessorFK`='$dni'";
            $consulta = mysqli_query($conexion,$sql);
            if($consulta == false){
                mysqli_error($conexion);
            }else{
                echo "<div class='cursosP'>";
                while($row = mysqli_fetch_array($consulta)){
                    
                    echo "<div><a href='menu.php?curs=".$row["codi"]."'>".$row["codi"]." : ".$row["nom"]."</a></div>";
                }
                echo "</div>";
            }
        }
    }
    //mostra alumnes del curs seleccionat
    function llistaralumnesCurs(){
        
            if(isset($_GET["curs"]) ||isset($_POST["buscarcheck"])){
                $conexion = concetarBD();
                $curs=$_GET["curs"];
                $sql = "SELECT * FROM `alumnes` INNER JOIN `matricules` ON `dni`=`FKAlumnesDNI` WHERE `FKCursosCODI`='$curs'";
                /*if(isset($_POST["buscarcheck"])){
                    $varquery="%".$_POST["buscar"]."%";
                    $curs=$_POST["cursCodi"];
                    $_GET["curs"]=$_POST["cursCodi"];
                    $sql = "SELECT * FROM `alumnes` INNER JOIN `matricules` ON `dni`=`FKAlumnesDNI` WHERE `FKCursosCODI`='$curs'";
                }*/

                
                $consulta = mysqli_query($conexion,$sql);
                if($consulta == false){
                    mysqli_error($conexion);
                }else{
                echo "<div class='encapsulador'>";
                    echo "<div class='taulaCurs'>";
                    echo "<table>";
                    echo "<tr>";
                        echo "<th>dni</th><th>nom</th><th>cognoms</th><th>edat</th><th>Correu electronic</th><th>Posar nota</th><th>nota</th>";
                        echo "</tr>";
                    }
                while($row = mysqli_fetch_array($consulta)){
                    echo "<tr>";
                    echo "<td>$row[dni]</td>";
                    echo "<td>$row[nom]</td>";
                    echo "<td>$row[cognom]</td>";
                    echo "<td>$row[edat]</td>";
                    echo "<td>$row[correuElectronic]</td>";
                    echo '<td><button onclick="posarNota(\''.$row['dni'].'\',\''.$curs.'\')">Posar Nota</button></td>';
                    echo "<td>$row[nota]</td>";
                    echo "</tr>";
                             
                }
                echo "</table>";
                    echo "</div>";
                echo "</div>";
                }
                if(isset($_POST["buscarcheck"])){
                    echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=menu.php?curs=' .$_POST["curs"]. '">';
                }
                
        }
    
    //posar nota a alumnes
    function updateNota(){
        if(isset($_COOKIE["cursnota"]) && $_COOKIE["cursnota"] != "x"){
            $conexion = concetarBD();
            $curs=$_COOKIE["cursnota"];
            $dni=$_COOKIE["dninota"];
            $nota=$_COOKIE["notanota"];

            $sql = "UPDATE `matricules` SET `nota`=$nota WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            $dni=$_SESSION["usuari"]["dni"];

            setcookie("cursnota", "x");
            setcookie("dninota", "x");
            setcookie("notanota", "x");
            //var_dump($sql);
            $consulta = mysqli_query($conexion,$sql);
            echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=menu.php?curs=' .$curs. '">';
            
            
            if($consulta == false){
                mysqli_error($conexion);
            }
        }
    }
?>