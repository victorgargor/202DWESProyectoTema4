<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Departamentos desde Array</title>
        <link rel="stylesheet" href="../webroot/css/formularios.css" type="text/css">
        <style>
            /* Mensajes de éxito */
            .mensaje-exito {
                color: green;
                font-weight: bold;
            }

            /* Mensajes de error */
            .mensaje-error {
                color: red;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Pagina web que carga departamentos en la tabla desde un array usando consultas preparadas</h1>
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

                // Definir un array de departamentos nuevos
                $departamentosnuevos = [
                    ['T02_CodDepartamento' => 'DDD', 'T02_DescDepartamento' => 'Departamento 4', 'T02_FechaCreacionDepartamento' => date('Y-m-d'), 'T02_VolumenDeNegocio' => 80000],
                    ['T02_CodDepartamento' => 'EEE', 'T02_DescDepartamento' => 'Departamento 5', 'T02_FechaCreacionDepartamento' => date('Y-m-d'), 'T02_VolumenDeNegocio' => 90000],
                    ['T02_CodDepartamento' => 'FFF', 'T02_DescDepartamento' => 'Departamento 6', 'T02_FechaCreacionDepartamento' => date('Y-m-d'), 'T02_VolumenDeNegocio' => 100000]
                ];

                try {
                    // Establecemos la conexión con la base de datos
                    $miDB = new PDO(DSN, USER, PASSWORD);

                    // Iniciamos la transacción
                    $miDB->beginTransaction();

                    // Consulta con parámetros nombrados
                    $consulta = $miDB->prepare("INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio) 
                                VALUES (:T02_CodDepartamento, :T02_DescDepartamento, :T02_FechaCreacionDepartamento, :T02_VolumenDeNegocio)");

                    // Recorrer el array de departamentos para ejecutar las consultas
                    foreach ($departamentosnuevos as $departamento) {
                        // Array de parámetros con claves correspondientes
                        $parametros = [
                            ':T02_CodDepartamento' => $departamento['T02_CodDepartamento'],
                            ':T02_DescDepartamento' => $departamento['T02_DescDepartamento'],
                            ':T02_FechaCreacionDepartamento' => $departamento['T02_FechaCreacionDepartamento'],
                            ':T02_VolumenDeNegocio' => $departamento['T02_VolumenDeNegocio']
                        ];

                        // Ejecutar la consulta pasando los parámetros
                        $consulta->execute($parametros);
                    }

                    // Si todo salió bien, confirmamos la transacción
                    $miDB->commit();
                    echo "<p class='mensaje-exito'>Los departamentos se han agregado correctamente.</p>";
                } catch (PDOException $excepcion) {
                    // Si ocurre un error, deshacemos la transacción
                    $miDB->rollBack();
                    echo "<p class='mensaje-error'>Error: " . $excepcion->getMessage() . "</p>";
                    echo "<p class='mensaje-error'>Código de error: " . $excepcion->getCode() . "</p><br/>";
                    echo "<p class='mensaje-error'>No se ha agregado ningún departamento.</p>";
                } finally {
                    unset($miDB); // Cerramos la conexión
                }

                // Mostrar los departamentos existentes
                try {
                    // Establecemos la conexión con la base de datos
                    $miDB = new PDO(DSN, USER, PASSWORD);

                    // Consultar todos los departamentos
                    $consulta = $miDB->query("SELECT * FROM T02_Departamento");

                    // Recuperamos todos los registros
                    $departamentos = $consulta->fetchAll(PDO::FETCH_OBJ);

                    if (count($departamentos) > 0) {
                        echo "<h2>Todos los Departamentos</h2>";
                        echo "<table>";
                        echo "<thead><tr><th>Código</th><th>Descripción</th><th>Fecha Alta</th><th>Volumen de Negocio</th><th>Fecha Baja</th></tr></thead>";
                        echo "<tbody>";

                        // Mostrar todos los departamentos
                        foreach ($departamentos as $oDepartamento) {
                            $claseFila = empty($oDepartamento->T02_FechaBajaDepartamento) ? 'fecha-activa' : 'fecha-baja';
                            echo '<tr class="' . $claseFila . '">';
                            echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                            echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                            echo "<td>" . date_format(new DateTime($oDepartamento->T02_FechaCreacionDepartamento), 'd/m/Y') . "</td>";
                            echo "<td>" . number_format($oDepartamento->T02_VolumenDeNegocio, 2, '.', '.') . " €</td>";
                            echo "<td>" . ($oDepartamento->T02_FechaBajaDepartamento ? date_format(new DateTime($oDepartamento->T02_FechaBajaDepartamento), 'd/m/Y') : '') . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
                    } else {
                        echo "<p>No hay departamentos para mostrar.</p>";
                    }
                } catch (PDOException $excepcion) {
                    echo "<p>Error al recuperar los departamentos: " . $excepcion->getMessage() . "</p>";
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
