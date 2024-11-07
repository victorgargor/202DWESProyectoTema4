<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/index.css" type="text/css">
        <title>Víctor García Gordón</title>
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
                 * @version Fecha de última modificación 06/11/2024
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

                //Inicialización de las variables
                $entradaOK = true; //Variable que nos indica que todo va bien
                $oFechaActual = new DateTime("now"); //Variable que recoge la fecha actual
                //Array donde recogemos los mensajes de error
                $aErrores = [
                'T02_CodDepartamento' => '',
                'T02_DescDepartamento' => '',
                'T02_FechaCreacionDepartamento' => '',
                'T02_VolumenDeNegocio' => '',
                'T02_FechaBajaDepartamento' => ''
                ];

                //Array donde recogeremos las respuestas correctas (si $entradaOK)
                $aRespuestas = [
                'T02_CodDepartamento' => '',
                'T02_DescDepartamento' => '',
                'T02_FechaCreacionDepartamento' => '',
                'T02_VolumenDeNegocio' => '',
                'T02_FechaBajaDepartamento' => ''
                ];

                // Verifica si el formulario ha sido enviado
                if (isset($_REQUEST['enviar'])) {
                //Para cada campo del formulario: Validar entrada y actuar en consecuencia
                $aErrores['T02_DescDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['T02_DescDepartamento'], T_MAX_ALFANUMERICO, T_MIN_ALFANUMERICO, OBLIGATORIO);
                $aErrores['T02_VolumenDeNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['T02_VolumenDeNegocio'], PHP_FLOAT_MAX, PHP_FLOAT_MIN, OBLIGATORIO);
                $aErrores['T02_CodDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['T02_CodDepartamento'], T_MAX_ALFABETICO, T_MIN_ALFABETICO, OBLIGATORIO);

                // Ahora validamos que el codigo introducido no exista en la BD, haciendo una consulta 
                if ($aErrores['T02_CodDepartamento'] == null) {
                    try {
                        $miDB = new PDO(DSN, USER, PASSWORD); //Establecemos la conexión con la base de datos             
                        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Configuro las excepciones               
                        $codDepartamento = $miDB->quote($_REQUEST['T02_CodDepartamento']); //Elimino los caracteres especiales y coloca comillas alrededor si es necesario con el quote
                        //Se guarda el query de consulta en una variable
                        $resultadoConsulta = $miDB->query("SELECT T02_CodDepartamento FROM T02_Departamento WHERE T02_CodDepartamento = $codDepartamento");
                        //Comprobar de que exista el departamento y  mensaje de error
                        if ($resultadoConsulta->fetchObject()) {
                        $aErrores['T02_CodDepartamento'] = "Ya existe ese código de departamento";
                        }
                    } catch (PDOException $excepcion) {
                        echo 'Error: ' . $excepcion->getMessage() . "<br>";
                        echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                    } finally {
                        unset($miDB); //Se cierra la conexión
                    }
                }
                //Recorremos el array de errores
                foreach ($aErrores as $clave => $valor) {
                    if ($valor != null) {
                    $entradaOK = false;
                    //Limpiamos el campo si hay un error
                    $_REQUEST[$clave] = '';
                    }
                }
                } else {
                //El formulario no se ha rellenado nunca
                $entradaOK = false;
                }

                //Tratamiento del formulario
                if ($entradaOK) {
                try {
                    $miDB = new PDO(DSN, USER, PASSWORD); //Establecemos la conexión con la base de datos             
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Configuro las excepciones  
                    // Cargo el array con las respuestas
                    $aRespuestas['T02_CodDepartamento'] = strtoupper($_REQUEST['T02_CodDepartamento']);
                    $aRespuestas['T02_DescDepartamento'] = $_REQUEST['T02_DescDepartamento'];
                    $aRespuestas['T02_FechaCreacionDepartamento'] = $oFechaActual->format('Y-m-d H:i:s');
                    $aRespuestas['T02_VolumenDeNegocio'] = $_REQUEST['T02_VolumenDeNegocio'];
                    $aRespuestas['T02_FechaBajaDepartamento'] = $aRespuestas['T02_FechaBajaDepartamento'] ?: 'NULL';

                    //Se guarda el query de consulta en una variable               
                   $resultadoInsercion = $miDB->exec(<<<SQL
                        INSERT INTO T02_Departamento 
                        VALUES (
                            '{$aRespuestas['T02_CodDepartamento']}',
                            '{$aRespuestas['T02_DescDepartamento']}',
                            '{$aRespuestas['T02_FechaCreacionDepartamento']}',
                            '{$aRespuestas['T02_VolumenDeNegocio']}',
                            {$aRespuestas['T02_FechaBajaDepartamento']}  -- Aquí se maneja el NULL correctamente
                        );
                    SQL
                    );
                    //Mensaje si va todo correctamente
                    if ($resultadoInsercion > 0) {
                    echo "Los datos se han insertado correctamente.";

                    $verDatosTabla = $miDB->query("SELECT * FROM T02_Departamento");

                    //Creamos la tabla
                    ?>
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
                        <?php
                        /* Asignamos a la variable oDepartamento el 1er resultado de las respuestas recibidas del query, mientras el objeto contenga valores, se ejecutara el bucle
                          y se recorre el objeto mostrando el nombre del campo y su valor */
                        while ($oDepartamento = $verDatosTabla->fetchObject()){
                        echo '<tr>';
                        echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_FechaCreacionDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>";
                        echo "<td>" . $oDepartamento->T02_FechaBajaDepartamento . "</td>";
                        echo '</tr>';
                        }
                        ?>
                    </table>
                <?php
                } else {
                    echo "Hubo un error al insertar los datos.";
                }    
                }catch (PDOException $excepcion) {
                    echo 'Error: ' . $excepcion->getMessage() . "<br>";
                    echo 'Código de error: ' . $excepcion->getCode() . "<br>";
                } finally {
                    unset($miDB); //Para cerrar la conexión
                }
                } else {
                //Mostrar el formulario hasta que lo rellenemos correctamente
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
                    <div class="form-group">
                    <label for="T02_CodDepartamento">Código de Departamento:</label>
                    <input type="text" id="T02_CodDepartamento" name="T02_CodDepartamento" style="background-color: lightyellow" required value="<?php echo (isset($_REQUEST['T02_CodDepartamento']) ? $_REQUEST['T02_CodDepartamento'] : ''); ?>">
                    <?php if (!empty($aErrores['T02_CodDepartamento'])) { ?> <span style="color: red"><?php echo $aErrores['T02_CodDepartamento']; ?></span> <?php } ?>
                    </div>
                    <div class="form-group">
                    <label for="T02_DescDepartamento">Descripción de Departamento:</label>
                    <textarea id="T02_DescDepartamento" name="T02_DescDepartamento" rows="4" cols="50"  style="background-color: lightyellow" required><?php echo (isset($_REQUEST['T02_DescDepartamento']) ? $_REQUEST['T02_DescDepartamento'] : ''); ?></textarea>
                    <?php if (!empty($aErrores['T02_DescDepartamento'])) { ?> <span style="color: red"><?php echo $aErrores['T02_DescDepartamento']; ?></span> <?php } ?>
                    </div>       
                    <div class="form-group">
                    <label for="T02_VolumenDeNegocio">Volumen de Negocio:</label>
                    <input type="text" id="T02_VolumenDeNegocio" name="T02_VolumenDeNegocio" step="0.01" style="background-color: lightyellow" required value="<?php echo (isset($_REQUEST['T02_VolumenDeNegocio']) ? $_REQUEST['T02_VolumenDeNegocio'] : ''); ?>">
                    <?php if (!empty($aErrores['T02_VolumenDeNegocio'])) { ?> <span style="color: red"><?php echo $aErrores['T02_VolumenDeNegocio']; ?></span> <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="fechaActual">Fecha Actual:
                            <input type="text" id="fechaActual" name="fechaActual" value="<?php echo date_format($oFechaActual, 'd-m-Y') ?>" style="background-color: lightgray" disabled>
                        </label>
                    </div>
                    <div class="form-group">
                        <input id="enviar" name="enviar" type="submit" value="Añadir">
                    </div>
                </form>
                <?php
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

