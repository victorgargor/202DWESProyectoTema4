<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exportar Departamentos a XML</title>
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
        </style>
    </head>
    <body>
        <header>
            <h1>Página web que toma datos (código y descripción) de la tabla Departamento y guarda en un fichero departamento.xml. (COPIA DE SEGURIDAD / EXPORTAR). 
                El fichero exportado se encuentra en el directorio .../tmp/ del servidor.</h1>
        </header>
        <main>
            <section>
                <?php
                /**
                 * @author Víctor García Gordón
                 * @version Fecha de última modificación 13/11/2024
                 */
                try {
                    // Importamos la configuración de la base de datos
                    require_once '../config/ConfDBPDO.php';

                    // Establecemos la conexión con la base de datos usando PDO
                    $miDB = new PDO(DSN, USER, PASSWORD);

                    // Variable Heredoc para la consulta
                    $query = <<<SQL
                    SELECT * FROM T02_Departamento
                    SQL;

                    // Preparar la consulta SQL para obtener los datos de la tabla Departamento
                    $oResultadoConsulta = $miDB->prepare($query);

                    // Ejecutar la consulta
                    $oResultadoConsulta->execute();

                    // Comprobamos si existen departamentos
                    if ($oResultadoConsulta->rowCount() > 0) {
                        // Crear un objeto SimpleXMLElement para generar el archivo XML           
                        $xml = new SimpleXMLElement('<departamentos/>'); // La raíz del XML será <departamentos>
                        // Recorrer los departamentos y agregarlos como nodos XML
                        while ($oDepartamento = $oResultadoConsulta->fetchObject()) {
                            // Crear un nodo <departamento> para cada departamento
                            $departamento = $xml->addChild('departamento');
                            $departamento->addChild('CodDepartamento', $oDepartamento->T02_CodDepartamento);
                            $departamento->addChild('DescDepartamento', $oDepartamento->T02_DescDepartamento);
                            $departamento->addChild('FechaCreacionDepartamento', $oDepartamento->T02_FechaCreacionDepartamento);
                            $departamento->addChild('VolumenDeNegocio', $oDepartamento->T02_VolumenDeNegocio);
                            $departamento->addChild('FechaBajaDepartamento', $oDepartamento->T02_FechaBajaDepartamento);
                        }
                        
                        //Variable que recoge la fecha actual y la formatea en formato año mes dia
                        $oFechaActual = date('ymd');
                        
                        // Ruta y nombre del archivo donde se guardará el XML
                        $RutaArchivo = '../tmp/'.$oFechaActual.'departamento.xml';

                        // Guardar el XML en el archivo
                        $xml->asXML($RutaArchivo);

                        echo "<p class='mensaje-exito'>Los datos se han exportado correctamente a <a href='../tmp/'.$oFechaActual.'departamento.xml'>departamento.xml</a>.</p>";
                    } else {
                        echo "<p>No hay departamentos para exportar.</p>";
                    }
                } catch (PDOException $oExcepcion) {
                    echo "<p class='mensaje-error'>Error al conectar o consultar la base de datos: " . $oExcepcion->getMessage() . "</p>";
                } finally {
                    unset($miDB); // Cerramos la conexión
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



