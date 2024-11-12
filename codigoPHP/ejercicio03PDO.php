<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../webroot/css/formularios.css" type="text/css">
    <title>Víctor García Gordón</title>
    <script>
        // Función para convertir el valor a mayúsculas antes de enviar el formulario
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("form").addEventListener("submit", function(event) {
                var codDepartamento = document.getElementById("T02_CodDepartamento");
                var descDepartamento = document.getElementById("T02_DescDepartamento");

                // Convertir a mayúsculas antes de enviar
                codDepartamento.value = codDepartamento.value.toUpperCase();
                descDepartamento.value = descDepartamento.value.toUpperCase();
            });
        });
    </script>
</head>
<body>
    <header>
        <h1 id="inicio">Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.</h1>
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
            define('T_MAX_ALFABETICO', 3);
            define('T_MIN_ALFABETICO', 3);
            define('T_MAX_ALFANUMERICO', 255);
            define('T_MIN_ALFANUMERICO', 1);

            // Inicialización de las variables
            $entradaOK = true; // Variable que nos indica que todo va bien
            $oFechaActual = new DateTime("now"); // Variable que recoge la fecha actual
            $aErrores = [
                'T02_CodDepartamento' => '',
                'T02_DescDepartamento' => '',
                'T02_FechaCreacionDepartamento' => '',
                'T02_VolumenDeNegocio' => '',
                'T02_FechaBajaDepartamento' => ''
            ];

            // Array donde recogeremos las respuestas correctas (si $entradaOK)
            $aRespuestas = [
                'T02_CodDepartamento' => '',
                'T02_DescDepartamento' => '',
                'T02_FechaCreacionDepartamento' => '',
                'T02_VolumenDeNegocio' => '',
                'T02_FechaBajaDepartamento' => ''
            ];

            // Verifica si el formulario ha sido enviado
            if (isset($_REQUEST['enviar'])) {
                // Para cada campo del formulario: Validar entrada y actuar en consecuencia
                $aErrores['T02_DescDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['T02_DescDepartamento'], T_MAX_ALFANUMERICO, T_MIN_ALFANUMERICO, OBLIGATORIO);
                $aErrores['T02_VolumenDeNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['T02_VolumenDeNegocio'], PHP_FLOAT_MAX, PHP_FLOAT_MIN, OBLIGATORIO);
                $aErrores['T02_CodDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['T02_CodDepartamento'], T_MAX_ALFABETICO, T_MIN_ALFABETICO, OBLIGATORIO);

                // Ahora validamos que el código introducido no exista en la BD, haciendo una consulta preparada
                if ($aErrores['T02_CodDepartamento'] == null) {
                    try {
                        $miDB = new PDO(DSN, USER, PASSWORD); // Establecemos la conexión con la base de datos
                        $codDepartamento = $_REQUEST['T02_CodDepartamento']; // No es necesario el uso de quote en este caso con consultas preparadas

                        // Consulta preparada para comprobar si el código ya existe
                        $consultaCodigoExistente = $miDB->prepare("SELECT T02_CodDepartamento FROM T02_Departamento WHERE T02_CodDepartamento = :codDepartamento");
                        $consultaCodigoExistente->bindParam(':codDepartamento', $codDepartamento, PDO::PARAM_STR);
                        $consultaCodigoExistente->execute();

                        // Comprobar si el código ya existe
                        if ($consultaCodigoExistente->fetch(PDO::FETCH_ASSOC)) {
                            $aErrores['T02_CodDepartamento'] = "Ya existe ese código de departamento";
                        }
                    } catch (PDOException $excepcion) {
                        echo 'Error: ' . $excepcion->getMessage() . "<br>";
                        echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                    } finally {
                        unset($miDB); // Se cierra la conexión
                    }
                }

                // Recorremos el array de errores
                foreach ($aErrores as $clave => $valor) {
                    if ($valor != null) {
                        $entradaOK = false;
                        // Limpiamos el campo si hay un error
                        $_REQUEST[$clave] = '';
                    }
                }
            } else {
                // El formulario no se ha rellenado nunca
                $entradaOK = false;
            }

            // Tratamiento del formulario
            if ($entradaOK) {
                try {
                    $miDB = new PDO(DSN, USER, PASSWORD); // Establecemos la conexión con la base de datos

                    // Cargamos las respuestas del formulario
                    $aRespuestas['T02_CodDepartamento'] = $_REQUEST['T02_CodDepartamento'];
                    $aRespuestas['T02_DescDepartamento'] = $_REQUEST['T02_DescDepartamento'];
                    $aRespuestas['T02_FechaCreacionDepartamento'] = $oFechaActual->format('Y-m-d H:i:s');
                    $aRespuestas['T02_VolumenDeNegocio'] = $_REQUEST['T02_VolumenDeNegocio'];
                    $aRespuestas['T02_FechaBajaDepartamento'] = !empty($_REQUEST['T02_FechaBajaDepartamento']) ? (new DateTime($_REQUEST['T02_FechaBajaDepartamento']))->format('Y-m-d H:i:s') : NULL;

                    // Consulta preparada para insertar los datos
                    $consultaInsercionDepartamento = $miDB->prepare("INSERT INTO T02_Departamento 
                        (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento)
                        VALUES (:codDepartamento, :descDepartamento, :fechaCreacion, :volumenNegocio, :fechaBaja)");

                    // Vinculamos los parámetros
                    $consultaInsercionDepartamento->bindParam(':codDepartamento', $aRespuestas['T02_CodDepartamento']);
                    $consultaInsercionDepartamento->bindParam(':descDepartamento', $aRespuestas['T02_DescDepartamento']);
                    $consultaInsercionDepartamento->bindParam(':fechaCreacion', $aRespuestas['T02_FechaCreacionDepartamento']);
                    $consultaInsercionDepartamento->bindParam(':volumenNegocio', $aRespuestas['T02_VolumenDeNegocio']);
                    $consultaInsercionDepartamento->bindParam(':fechaBaja', $aRespuestas['T02_FechaBajaDepartamento'], PDO::PARAM_NULL);

                    // Ejecutamos la inserción
                    if ($consultaInsercionDepartamento->execute()) {
                        echo "Los datos se han insertado correctamente.";
                    } else {
                        echo "Hubo un error al insertar los datos.";
                    }
                } catch (PDOException $excepcion) {
                    echo 'Error: ' . $excepcion->getMessage() . "<br>";
                    echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                } finally {
                    unset($miDB); // Para cerrar la conexión
                }
            }

            // Mostrar el formulario
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
                <div class="form-group">
                    <label for="T02_CodDepartamento">Código de Departamento:</label>
                    <input type="text" id="T02_CodDepartamento" name="T02_CodDepartamento" required value="<?php echo $_REQUEST['T02_CodDepartamento'] ?? ''; ?>">
                    <?php if (!empty($aErrores['T02_CodDepartamento'])) { ?> 
                        <span style="color: red"><?php echo $aErrores['T02_CodDepartamento']; ?></span> 
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="T02_DescDepartamento">Descripción de Departamento:</label>
                    <textarea id="T02_DescDepartamento" name="T02_DescDepartamento" rows="4" cols="50" required><?php echo $_REQUEST['T02_DescDepartamento'] ?? ''; ?></textarea>
                    <?php if (!empty($aErrores['T02_DescDepartamento'])) { ?> 
                        <span style="color: red"><?php echo $aErrores['T02_DescDepartamento']; ?></span> 
                    <?php } ?>
                </div>
                <div class="form-group">
                    <label for="T02_VolumenDeNegocio">Volumen de Negocio:</label>
                    <input type="text" id="T02_VolumenDeNegocio" name="T02_VolumenDeNegocio" step="0.01" required value="<?php echo $_REQUEST['T02_VolumenDeNegocio'] ?? ''; ?>">
                    <?php if (!empty($aErrores['T02_VolumenDeNegocio'])) { ?> 
                        <span style="color: red"><?php echo $aErrores['T02_VolumenDeNegocio']; ?></span> 
                    <?php } ?>
                </div>
                <button type="submit" name="enviar">Enviar</button>
            </form>

            <!-- Mostrar los datos de la tabla siempre -->
            <?php
            try {
                $miDB = new PDO(DSN, USER, PASSWORD); // Establecemos la conexión con la base de datos
                $consultaDatosDepartamento = $miDB->query("SELECT * FROM T02_Departamento");

                // Crear la tabla
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Fecha Alta</th>
                                <th>Volumen Negocio</th>
                                <th>Fecha Baja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($oDepartamento = $consultaDatosDepartamento->fetchObject()) {
                                echo '<tr>';
                                echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                                echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                                echo "<td>" . date_format(new DateTime($oDepartamento->T02_FechaCreacionDepartamento), 'd/m/Y') . "</td>";
                                echo "<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>";
                                echo "<td>" . ($oDepartamento->T02_FechaBajaDepartamento ? date_format(new DateTime($oDepartamento->T02_FechaBajaDepartamento), 'd/m/Y') : 'No disponible') . "</td>";
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } catch (PDOException $excepcion) {
                echo 'Error: ' . $excepcion->getMessage() . "<br>";
                echo 'Código de error: ' . $excepcion->getCode() . "<br>";
            } finally {
                unset($miDB); // Para cerrar la conexión
            }
            ?>
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




