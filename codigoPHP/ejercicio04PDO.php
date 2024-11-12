<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/formularios.css" type="text/css">
        <title>Búsqueda de Departamentos</title>
        <style>
            /* Regla interna para ajustar el ancho y la alineación del formulario */
            .form-group {
                display: flex; /* Utiliza flexbox para colocar el input y el botón en la misma línea */
                align-items: center; /* Alinea verticalmente los elementos */
            }

            .form-group input {
                width: 60%; /* El input ocupa el 80% del espacio disponible */
                padding: 8px; /* Agrega un poco de espacio interno para el input */
            }

            .form-group button {
                width: auto; /* El botón se ajusta automáticamente a su contenido */
                margin-left: 50px; /* Añade un espacio entre el input y el botón */
                padding: 8px 16px; /* Ajusta el padding para que el botón sea más grande */
            }
        </style>
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
                 * @version Fecha de última modificación 12/11/2024
                 */
                // Importamos la configuración de la base de datos
                require_once '../config/ConfDBPDO.php';
                // Incluimos la librería de validación de formularios
                require_once '../core/231018libreriaValidacion.php';

                // Definición de constantes
                define('OBLIGATORIO', 1);
                define('OPCIONAL', 0);
                define('T_MAX_ALFANUMERICO', 255);
                define('T_MIN_ALFANUMERICO', 1);
                
                // Inicialización de variables
                $entradaOK = true; // Variable que nos indica que todo va bien
                $aErrores = ['T02_DescDepartamento' => '']; // Para almacenar los errores de validación
                $aRespuestas = ['T02_DescDepartamento' => '']; // Respuestas correctas
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
                }

                // Conexión a la base de datos y consulta
                try {
                    $miDB = new PDO(DSN, USER, PASSWORD); // Establecemos la conexión con la base de datos
                    
                    // Si se realizó una búsqueda, filtramos por la descripción, de lo contrario, mostramos todos los departamentos
                    if (isset($_REQUEST['buscar']) && $entradaOK) {
                        $descDepartamento = "%" . $_REQUEST['T02_DescDepartamento'] . "%";
                        $consulta = $miDB->prepare("SELECT * FROM T02_Departamento WHERE T02_DescDepartamento LIKE :descDepartamento");
                        $consulta->bindParam(':descDepartamento', $descDepartamento);
                    } else {
                        // Si no hay búsqueda, mostramos todos los departamentos
                        $consulta = $miDB->query("SELECT * FROM T02_Departamento");
                    }

                    // Ejecutamos la consulta
                    $consulta->execute();

                    // Recuperamos todos los departamentos
                    while ($oDepartamento = $consulta->fetchObject()) {
                        $departamentos[] = $oDepartamento;
                    }

                } catch (PDOException $excepcion) {
                    echo 'Error: ' . $excepcion->getMessage() . "<br>";
                    echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                } finally {
                    unset($miDB); // Cerramos la conexión
                }

                // Formulario de búsqueda
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
                    <div class="form-group">
                        <label for="T02_DescDepartamento">Descripción de Departamento:</label>
                        <input type="text" id="T02_DescDepartamento" name="T02_DescDepartamento" style="background-color: white" value="<?php echo (isset($_REQUEST['T02_DescDepartamento']) ? $_REQUEST['T02_DescDepartamento'] : ''); ?>">
                        <?php if (!empty($aErrores['T02_DescDepartamento'])) { ?>
                            <span style="color: red"><?php echo $aErrores['T02_DescDepartamento']; ?></span>
                        <?php } ?>
                        <button id="buscar" name="buscar" type="submit">Buscar</button>
                    </div>
                </form>

                <!-- Mostrar los resultados de la búsqueda en la tabla -->
                <h2>Resultados de la búsqueda:</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Fecha Alta</th>
                            <th>Volumen de Negocio</th>
                            <th>Fecha Baja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar todos los departamentos o solo los que coincidan con la búsqueda
                        if (count($departamentos) > 0) {
                            foreach ($departamentos as $oDepartamento) {
                                if (empty($oDepartamento->T02_FechaBajaDepartamento)) {
                                        // Si no tiene fecha de baja, aplicamos la clase 'fecha-activa' (verde claro)
                                        $claseFila = 'fecha-activa';
                                    } else {
                                        // Si tiene fecha de baja, aplicamos la clase 'fecha-baja' (rojo claro)
                                        $claseFila = 'fecha-baja';
                                    }

                                echo '<tr class="' . $claseFila . '">';
                                echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                                echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                                echo "<td>" . date_format(new DateTime($oDepartamento->T02_FechaCreacionDepartamento), 'd/m/Y') . "</td>";
                                echo "<td>" . number_format($oDepartamento->T02_VolumenDeNegocio, 2, '.', '.') . " €</td>";
                                echo "<td>" . ($oDepartamento->T02_FechaBajaDepartamento ? date_format(new DateTime($oDepartamento->T02_FechaBajaDepartamento), 'd/m/Y') : '') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Si no hay resultados, mostramos un mensaje
                            echo "<tr><td colspan='5'>No se encontraron departamentos que coincidan con la búsqueda.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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



