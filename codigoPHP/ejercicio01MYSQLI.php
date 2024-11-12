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
        <h1 id="inicio">Conexión a la base de datos con la cuenta usuario y tratamiento de errores. 
                Utilizar excepciones automáticas siempre que sea posible en todos los ejercicios.</h1>
    </header>
    <main>
        <section>
            <?php
            /**
             * @author Víctor García Gordón
             * @version Fecha de última modificación 12/11/2024
             */
            
            // Importamos la configuración de la base de datos
            require_once '../config/ConfDBMYSQLI.php';

            // Array que incluye los atributos mysqli
            $atributosMySQLi = [
                'client_version',
                'host_info',
                'protocol_version',
                'server_info',
                'server_version',
            ];

            // Conexión correcta con mysqli
            try {
                echo '<h2>Conexión Correcta</h2>';
                // Establecemos la conexión con los datos correctos
                $miDB = new mysqli(HOST, USER, PASSWORD, DATABASE);

                // Verificamos si hubo error en la conexión
                if ($miDB->connect_error) {
                    throw new Exception('Error en la conexión: ' . $miDB->connect_error);
                }
                echo 'Conexión exitosa <br>'; // Mensaje si conecta bien

                echo '<h2>Atributos mysqli</h2>';
                // Se recorren los atributos y se muestra el valor de cada uno
                foreach ($atributosMySQLi as $atributo) {
                    echo ('mysqli::' . strtoupper($atributo) . ' => ' . $miDB->$atributo . '<br>');
                }
            } catch (Exception $excepcion) {
                echo 'Error: ' . $excepcion->getMessage() . "<br>";
            } finally {
                // Solo intentamos cerrar la conexión si se estableció correctamente
                if ($miDB !== null && !$miDB->connect_error) {
                    $miDB->close();
                }
            }

            // Conexión incorrecta con mysqli
            try {
                echo '<h2>Conexión Incorrecta</h2>';
                // Intentamos establecer la conexión con datos incorrectos
                $miDB = new mysqli(HOST, 'dsfdsal', PASSWORD, DATABASE);

                // Verificamos si hubo error en la conexión
                if ($miDB->connect_error) {
                    throw new Exception('Error en la conexión: ' . $miDB->connect_error);
                }

                echo '<h2>Atributos mysqli</h2>';
                // Se recorren los atributos y se muestra el valor de cada uno
                foreach ($atributosMySQLi as $atributo) {
                    echo ('mysqli::' . strtoupper($atributo) . ' => ' . $miDB->$atributo . '<br>');
                }
            } catch (Exception $excepcion) {
                echo 'Error: ' . $excepcion->getMessage() . "<br>";
            } finally {
                // Solo intentamos cerrar la conexión si se estableció correctamente
                if ($miDB !== null && !$miDB->connect_error) {
                    $miDB->close();
                }
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


