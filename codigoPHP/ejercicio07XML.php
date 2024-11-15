<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Importar Departamentos desde XML</title>
        <link rel="stylesheet" href="../webroot/css/formularios.css" type="text/css">
        <style>
            /* Estilos para los mensajes de éxito y error */
            .mensaje-exito {
                color: green;
                font-weight: bold;
            }

            .mensaje-error {
                color: red;
                font-weight: bold;
            }
            .form-group button {
                width: auto;
                margin-left: 187px;
                padding: 8px 16px;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Página web que toma datos de un fichero XML y los añade a la tabla Departamento en la base de datos.</h1>
        </header>
        <main>
            <section>
                <?php
                /**
                 * @author Víctor García Gordón
                 * @version Fecha de última modificación 14/11/2024
                 */

                // Importamos la configuración de la base de datos
                require_once '../config/ConfDBPDO.php';

                // Establecemos la conexión con la base de datos usando PDO fuera del bloque try-catch
                try {
                    $miDB = new PDO(DSN, USER, PASSWORD);
                    // Configurar PDO para lanzar excepciones en caso de errores
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Verificar si se ha enviado una ruta de archivo
                    if (isset($_REQUEST['rutaXML']) && !empty($_REQUEST['rutaXML'])) {
                        // Obtener la ruta del archivo XML desde el formulario
                        $rutaArchivoXML = $_REQUEST['rutaXML'];

                        // Verificamos si el archivo XML existe
                        if (file_exists($rutaArchivoXML)) {
                            // Cargar el archivo XML
                            $xml = simplexml_load_file($rutaArchivoXML);

                            // Comenzamos una transacción para importar los datos
                            $miDB->beginTransaction();

                            // Definir la consulta SQL usando una variable heredoc
                            $query = <<<SQL
                            INSERT INTO T02_Departamento 
                            (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento) 
                            VALUES (:CodDepartamento, :DescDepartamento, :FechaCreacionDepartamento, :VolumenDeNegocio, :FechaBajaDepartamento)
                            SQL;

                            // Variable para contar los registros importados
                            $registrosImportados = 0;

                            // Recorremos cada nodo <departamento> del XML
                            foreach ($xml->departamento as $oDepartamento) {
                                // Preparar la sentencia SQL
                                $sentencia = $miDB->prepare($query);

                                if (empty($oDepartamento->FechaBajaDepartamento)) {
                                    // Si el campo está vacío o no existe, asignamos NULL
                                    $fechaBaja = NULL;
                                } else {
                                    // Si existe y no está vacío, usamos el valor 
                                    $fechaBaja = $oDepartamento->FechaBajaDepartamento;
                                }

                                // Bindear los parámetros
                                $sentencia->bindParam(':CodDepartamento', $oDepartamento->CodDepartamento);
                                $sentencia->bindParam(':DescDepartamento', $oDepartamento->DescDepartamento);
                                $sentencia->bindParam(':FechaCreacionDepartamento', $oDepartamento->FechaCreacionDepartamento);
                                $sentencia->bindParam(':VolumenDeNegocio', $oDepartamento->VolumenDeNegocio);
                                $sentencia->bindParam(':FechaBajaDepartamento', $fechaBaja);

                                // Ejecutar la consulta
                                if ($sentencia->execute()) {
                                    // Incrementar el contador si la inserción es exitosa
                                    $registrosImportados++;
                                }
                            }

                            // Confirmamos los cambios
                            $miDB->commit();

                            // Mostrar mensaje de éxito con el número de registros importados
                            echo "<p class='mensaje-exito'>Se han importado $registrosImportados registros correctamente desde el archivo XML.</p>";
                        } else {
                            echo "<p class='mensaje-error'>No se encuentra el archivo XML en la ruta especificada.</p>";
                        }
                    } else {
                        echo "<p class='mensaje-error'>Por favor, introduzca una ruta válida para el archivo XML.</p>";
                    }

                    // Consulta para obtener los departamentos desde la base de datos
                    $querySelect = "SELECT * FROM T02_Departamento";
                    $sentenciaSelect = $miDB->prepare($querySelect);
                    $sentenciaSelect->execute();

                    // Asignamos los resultados a la variable $departamentos
                    $departamentos = $sentenciaSelect->fetchAll(PDO::FETCH_OBJ);

                } catch (PDOException $oExcepcion) {
                    // Si ocurre un error con la base de datos, revertimos la transacción
                    if (isset($miDB)) {
                        $miDB->rollBack();
                    }
                    echo "<p class='mensaje-error'>Error al conectar o consultar la base de datos: " . $oExcepcion->getMessage() . "</p>";
                } catch (Exception $oExcepcion) {
                    // En caso de error con el archivo XML
                    echo "<p class='mensaje-error'>Error al procesar el archivo XML: " . $oExcepcion->getMessage() . "</p>";
                } finally {
                    unset($miDB); // Cerramos la conexión
                }
                ?>
                <!-- Formulario para introducir la URL del archivo XML -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
                    <div class="form-group">
                        <label for="rutaXML">Introduzca la ruta del archivo XML:</label>
                        <input type="text" id="rutaXML" name="rutaXML" style="background: lightyellow" required value="<?php echo (isset($_REQUEST['rutaXML']) ? $_REQUEST['rutaXML'] : ''); ?>">
                        Formato de entrada: ../tmp/AAMMDDdepartamento.xml
                        </input>
                        <button type="submit">Cargar XML</button>
                    </div>
                </form>

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
                        // Verificamos si la variable $departamentos está definida y contiene datos
                        if (isset($departamentos) && count($departamentos) > 0) {
                            foreach ($departamentos as $oDepartamento) {
                                // Si no tiene fecha de baja, aplicamos la clase 'fecha-activa' (verde claro)
                                $claseFila = empty($oDepartamento->T02_FechaBajaDepartamento) ? 'fecha-activa' : 'fecha-baja';

                                echo '<tr class="' . $claseFila . '">';
                                echo "<td>" . htmlspecialchars($oDepartamento->T02_CodDepartamento) . "</td>";
                                echo "<td>" . htmlspecialchars($oDepartamento->T02_DescDepartamento) . "</td>";
                                echo "<td>" . date_format(new DateTime($oDepartamento->T02_FechaCreacionDepartamento), 'd/m/Y') . "</td>";
                                echo "<td>" . number_format($oDepartamento->T02_VolumenDeNegocio, 2, '.', '.') . " €</td>";
                                echo "<td>" . ($oDepartamento->T02_FechaBajaDepartamento ? date_format(new DateTime($oDepartamento->T02_FechaBajaDepartamento), 'd/m/Y') : '') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No se encontraron departamentos en la base de datos.</td></tr>";
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





