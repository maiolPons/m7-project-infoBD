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
            $sql = "SELECT * FROM cursos C INNER JOIN matricules m ON c.codi = m.FKCursosCODI WHERE `dataInici` > DATE '$today' AND `FKAlumnesDNI` != '$dni'";
        }
        if($_SESSION["menu"]=="baixa"){
            $sql = "SELECT * FROM cursos C INNER JOIN matricules m ON c.codi = m.FKCursosCODI WHERE `dataFinal` > DATE '$today' AND `FKAlumnesDNI` = '$dni'";
        }
        if($_SESSION["menu"]=="notes"){
            $sql = "SELECT * FROM cursos C INNER JOIN matricules m ON c.codi = m.FKCursosCODI WHERE `dataFinal` < DATE '$today' AND `FKAlumnesDNI` = '$dni'";
        }
        

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

?>