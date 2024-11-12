<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Departamentos con Transacción</title>
    <link rel="stylesheet" href="../webroot/css/formularios.css" type="text/css">
</head>
<body>
    <header>
        <h1 id="inicio">Formulario para agregar departamentos con transacción</h1>
    </header>
    <main>
        <section>
            <?php
            /**
             * Página que agrega tres registros en la tabla Departamento usando transacciones al presionar el botón.
             */

                // Definir los tres registros a insertar
                $departamentos = [
                    ['T02_CodDepartamento' => 'AAA', 'T02_DescDepartamento' => 'Departamento 1', 'T02_FechaCreacionDepartamento' => date('Y-m-d'), 'T02_VolumenDeNegocio' => 50000],
                    ['T02_CodDepartamento' => 'BBB', 'T02_DescDepartamento' => 'Departamento 2', 'T02_FechaCreacionDepartamento' => date('Y-m-d'), 'T02_VolumenDeNegocio' => 60000],
                    ['T02_CodDepartamento' => 'CCC', 'T02_DescDepartamento' => 'Departamento 3', 'T02_FechaCreacionDepartamento' => date('Y-m-d'), 'T02_VolumenDeNegocio' => 70000]
                ];

                try {
                    // Importamos la configuración de la base de datos
                    require_once '../config/ConfDBPDO.php';

                    // Establecemos la conexión con la base de datos
                    $miDB = new PDO(DSN, USER, PASSWORD);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Iniciamos la transacción
                    $miDB->beginTransaction();

                    // Insertar los tres departamentos
                    foreach ($departamentos as $departamento) {
                        $consulta = $miDB->prepare("INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio) 
                        VALUES (:codDepartamento, :descDepartamento, :fechaCreacion, :volumenDeNegocio)");

                        $consulta->bindParam(':codDepartamento', $departamento['T02_CodDepartamento'], PDO::PARAM_STR);
                        $consulta->bindParam(':descDepartamento', $departamento['T02_DescDepartamento'], PDO::PARAM_STR);
                        $consulta->bindParam(':fechaCreacion', $departamento['T02_FechaCreacionDepartamento'], PDO::PARAM_STR);
                        $consulta->bindParam(':volumenDeNegocio', $departamento['T02_VolumenDeNegocio'], PDO::PARAM_INT);

                        // Ejecutamos la consulta de inserción
                        $consulta->execute();
                    }

                    // Si todo salió bien, confirmamos la transacción
                    $miDB->commit();
                    echo "<p>Los tres departamentos se han agregado correctamente.</p>";

                } catch (PDOException $excepcion) {
                    // Si hay un error, deshacemos la transacción
                    $miDB->rollBack();
                    echo "<p>Error: " . $excepcion->getMessage() . "</p>";
                }
                finally {
                    unset($miDB); // Cerramos la conexión
                }

            // Mostrar los departamentos existentes
            try {
                // Importamos la configuración de la base de datos
                require_once '../config/ConfDBPDO.php';
                // Establecemos la conexión con la base de datos
                $miDB = new PDO(DSN, USER, PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                        echo "<tr>";
                        echo "<td>" . $oDepartamento->T02_CodDepartamento . "</td>";
                        echo "<td>" . $oDepartamento->T02_DescDepartamento . "</td>";
                        echo "<td>" . date_format(new DateTime($oDepartamento->T02_FechaCreacionDepartamento), 'd/m/Y') . "</td>";
                        echo "<td>" . $oDepartamento->T02_VolumenDeNegocio . "</td>";
                        echo "<td>" . ($oDepartamento->T02_FechaBajaDepartamento ? date_format(new DateTime($oDepartamento->T02_FechaBajaDepartamento), 'd/m/Y') : 'N/A') . "</td>";
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



