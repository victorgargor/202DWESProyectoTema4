<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/formularios.css" type="text/css">
        <title>Búsqueda de Departamentos</title>
    </head>
    <body>
        <header>
            <h1 id="inicio">Formulario para buscar departamentos por descripción</h1>
        </header>
        <main>
            <section>
                <?php
                /**
                 * @author Víctor García Gordón
                 * @version Fecha de última modificación 11/11/2024
                 */
                //Importamos la configuracion de la base de datos
                require_once '../config/ConfDBPDO.php';
                //Incluimos la libreria de validación de formularios
                require_once '../core/231018libreriaValidacion.php';

                 //Definición de constantes que utilizaremos en prácticamente todos los métodos de la librería
                define('OBLIGATORIO', 1);
                define('OPCIONAL', 0);
                //Definición de constantes para comprobarAlfabético
                define('T_MAX_ALFABETICO', 3);
                define('T_MIN_ALFABETICO', 3);
                //Definición de constantes para comprobarAlfaNumérico
                define('T_MAX_ALFANUMERICO', 255);
                define('T_MIN_ALFANUMERICO', 1);
                
                //Inicialización de variables
                $entradaOK = true; //Variable que nos indica que todo va bien
                $aErrores = ['T02_DescDepartamento' => '']; //Para almacenar los errores de validación
                $aRespuestas = ['T02_DescDepartamento' => '']; //Respuestas correctas
                $departamentos = []; // Array para almacenar los resultados de la búsqueda

                // Verifica si el formulario ha sido enviado
                if (isset($_REQUEST['buscar'])) {
                    // Validar la entrada del campo de búsqueda
                    $aErrores['T02_DescDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['T02_DescDepartamento'], T_MAX_ALFANUMERICO, T_MIN_ALFANUMERICO, OPCIONAL);

                    // Recorremos los errores para marcar que la entrada no es correcta
                    foreach ($aErrores as $clave => $valor) {
                        if ($valor != null) {
                            $entradaOK = false;
                            $_REQUEST[$clave] = ''; // Limpiamos el campo si hay un error
                        }
                    }

                    // Si la entrada es correcta, realizamos la búsqueda
                    if ($entradaOK) {
                        try {
                            $miDB = new PDO(DSN, USER, PASSWORD); // Establecemos la conexión con la base de datos

                            // Construimos la consulta SQL de búsqueda
                            $descDepartamento = $_REQUEST['T02_DescDepartamento'] ? "%" . $_REQUEST['T02_DescDepartamento'] . "%" : "%";
                            $resultadoConsulta = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_DescDepartamento LIKE '{$descDepartamento}'");

                            // Recuperamos los departamentos que coinciden con la búsqueda
                            while ($oDepartamento = $resultadoConsulta->fetchObject()) {
                                $departamentos[] = $oDepartamento;
                            }
                        } catch (PDOException $excepcion) {
                            echo 'Error: ' . $excepcion->getMessage() . "<br>";
                            echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                        } finally {
                            unset($miDB); // Cerramos la conexión
                        }
                    }
                }

                // Si hubo algún resultado, los mostramos en una tabla
                if (count($departamentos) > 0) {
                    echo "<h2>Resultados de la búsqueda:</h2>";
                    echo "<table>";
                    echo "<thead><tr><th>Codigo</th><th>Descripción</th><th>Fecha Alta</th><th>Volumen Negocio</th><th>Fecha Baja</th></tr></thead>";
                    echo "<tbody>";
                    foreach ($departamentos as $oDepartamento) {
                        echo "<tr>";
                        echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_FechaCreacionDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>";
                        echo "<td>" . $oDepartamento->T02_FechaBajaDepartamento . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } elseif (isset($_REQUEST['buscar'])) {
                    echo "<p>No se encontraron departamentos que coincidan con la búsqueda.</p>";
                }

                // Formulario de búsqueda
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
                    <div class="form-group">
                        <label for="T02_DescDepartamento">Descripción de Departamento (parte del nombre):</label>
                        <input type="text" id="T02_DescDepartamento" name="T02_DescDepartamento" style="background-color: lightyellow" value="<?php echo (isset($_REQUEST['T02_DescDepartamento']) ? $_REQUEST['T02_DescDepartamento'] : ''); ?>">
                        <?php if (!empty($aErrores['T02_DescDepartamento'])) { ?>
                            <span style="color: red"><?php echo $aErrores['T02_DescDepartamento']; ?></span>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <input id="buscar" name="buscar" type="submit" value="Buscar">
                    </div>
                </form>
            </section>
        </main>
        <footer>
            <div>
                <a href="../indexProyectoTema4.php">Tema 4</a> 
                <a target="blank" href="../doc/curriculum.pdf"><img src="../doc/curriculum.jpg" alt="curriculum"></a>
                <a target="blank" href="https://github.com/victorgargor/202DWESProyectoTema4"><img src="../doc/github.png" alt="github"></a>
                <a target="blank" href="https://github.com">Web Imitada</a>
            </div>
        </footer>
    </body>
</html>

