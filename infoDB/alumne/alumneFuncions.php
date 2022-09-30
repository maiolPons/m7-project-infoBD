<?php
    function menuAlunes(){
        echo"<nav>";
            echo "<a href='menu.php?opcio=" . "alta" . "'>Donarme d'alta</a>";
            echo '<a href="menu.php?opcio=' . 'baixa' . '">Donarme de baixa</a>';
            echo '<a href="menu.php?opcio=' . 'notes' . '">Notes</a>';
            echo '<a href="functions/closeSession.php">Tancar Sessio</a>';
        echo"</nav>";
    }
    function menuAlta(){
        monstrarTaula();
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
                    if(isset($_SESSION["menu"])){
                        if($_SESSION["menu"]=="alta"){
                            echo '<input name="opcio" type="hidden" value="alta">';
                        }
                        if($_SESSION["menu"]=="baixa"){
                            echo '<input name="opcio" type="hidden" value="baixa">';
                        }else{
                            echo '<input name="opcio" type="hidden" value="notes">';
                        }
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
            $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON C.codi = FKCursosCODI WHERE `dataInici` > DATE '$today' AND (`FKAlumnesDNI` != '$dni' OR estatM != '1')";
        }
        if($_SESSION["menu"]=="baixa"){
            $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON C.codi = FKCursosCODI WHERE `dataInici` > DATE '$today' AND `FKAlumnesDNI` = '$dni' AND estatM != '0'";
        }
        if($_SESSION["menu"]=="notes"){
            $sql = "SELECT * FROM cursos C INNER JOIN matricules M ON c.codi = M.FKCursosCODI WHERE `dataFinal` < DATE '$today' AND `FKAlumnesDNI` = '$dni'";
        }
        
var_dump($sql);
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
                    if(isset($_SESSION["menu"])){
                    if($_SESSION["menu"]=="alta"){
                        //<a href="menu.php?alta=' . $row["codi"] . '">Donar de Alta</a> 
                        echo '<td><button onclick="confirmacioM('.$row["codi"].')">Donar alta</button> </td>';
                    }
                    if($_SESSION["menu"]=="baixa"){
                        //echo '<td><a href="menu.php?baixa=' . $row["codi"] . '">Donar de Baixa</a></td>';
                        echo '<td><button onclick="confirmacioD('.$row["codi"].')">Donar de Baixa</button></td>';
                    }
                    echo "</tr>";
                    }           
                }
                echo "</table>";
            }
        }
    }
    function desmatricular(){
        if(isset($_COOKIE["desmatricular"])){
            $curs=$_COOKIE["desmatricular"];
            $dni=$_SESSION["usuari"]["dni"];
            $sql = "UPDATE `matricules` SET `estatM`='false' WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            var_dump($sql);
            $conexion = concetarBD();
            $consulta = mysqli_query($conexion,$sql);
            var_dump($sql);
            if($consulta == false){
                mysqli_error($conexion);
            }
        }
    }
    function matricular(){
        if(isset($_COOKIE["matricular"])){
            $conexion = concetarBD();
            $curs=$_COOKIE["matricular"];
            $dni=$_SESSION["usuari"]["dni"];
            $sql = "SELECT * FROM `matricules` WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            $consulta = mysqli_query($conexion,$sql);
            $registre = mysqli_fetch_row($consulta);
            if($registre == null){
                $sql = "INSERT INTO `matricules`(`FKAlumnesDNI`, `FKCursosCODI`, `nota`, `estatM`) VALUES ('$dni','$curs','0','1')";
            }else{
                $sql = "UPDATE `matricules` SET `estatM`='false' WHERE `FKAlumnesDNI`='$dni' AND `FKCursosCODI`='$curs'";
            }
            
            var_dump($sql);
            $consulta = mysqli_query($conexion,$sql);
            if($consulta == false){
                mysqli_error($conexion);
            }
        }
    }

?>