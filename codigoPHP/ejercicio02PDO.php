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
        <h1 id="inicio">Mostrar el contenido de la tabla Departamento y el número de registros</h1>
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
            
            try {                                                   
                $miDB = new PDO(DSN, USER, PASSWORD); // Establecemos la conexión con los datos correctos
                
                // Se guarda el query de consulta en una variable
                $resultadoConsulta = $miDB->query('SELECT * FROM T02_Departamento');
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Fecha Alta</th>
                            <th>Volumen Negocio</th>
                            <th>Fecha Baja</th>
                        </tr>
                    </thead>
                <?php
                // Asignamos a la variable oDepartamento el 1er resultado de las respuestas recibidas del query
                while ($oDepartamento = $resultadoConsulta->fetchObject()) {
                    // Formateamos las fechas
                    $fechaCreacion = $oDepartamento->T02_FechaCreacionDepartamento;
                    $fechaBaja = $oDepartamento->T02_FechaBajaDepartamento;

                    // Usamos strtotime y date para formatear las fechas
                    $fechaCreacionFormateada = $fechaCreacion ? date('d/m/Y', strtotime($fechaCreacion)) : 'N/A';
                    $fechaBajaFormateada = $fechaBaja ? date('d/m/Y', strtotime($fechaBaja)) : 'N/A';

                    // Mostrar los datos de la tabla
                    echo '<tr>';
                    echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                    echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                    echo "<td>" . $fechaCreacionFormateada . "</td>";
                    echo "<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>";
                    echo "<td>" . $fechaBajaFormateada . "</td>";
                    echo '</tr>';
                }
                ?>
                </table>
                <?php
            } catch (PDOException $excepcion) {
                echo 'Error: ' . $excepcion->getMessage() . "<br>";
                echo 'Código de error: ' . $excepcion->getCode() . "<br>";
            } finally {
                unset($miDB); // Cerramos la base de datos
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


