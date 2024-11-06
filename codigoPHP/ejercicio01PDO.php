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
             * @version Fecha de última modificación 06/11/2024
             */
            
             //Importamos la configuracion de la base de datos
            require_once '../config/ConfDBPDO.php';
            
            //Array que incluye los atributos PDO
            $atributosPDO = [
                'AUTOCOMMIT', 
                'CASE', 
                'CLIENT_VERSION', 
                'CONNECTION_STATUS', 
                'DRIVER_NAME',
                'ERRMODE', 
                'ORACLE_NULLS', 
                'PERSISTENT',
                'SERVER_INFO',
                'SERVER_VERSION',
            ];

            try {              
                echo '<h2>Conexion Correcta</h2>';                          
                $miDB = new PDO(DSN, USER, PASSWORD);//Establecemos la conexión con los datos correctos
                echo ('Conexión exitosa <br>');//Mensaje si conecta bien

                echo '<h2>Atributos PDO</h2>';
                //Se recorren los atricutos y se muestra el valor de cada uno
                foreach ($atributosPDO as $valor) {
                    echo ('PDO::ATTR_' . $valor . ' => ' . $miDB->getAttribute(constant("PDO::ATTR_$valor")). '<br>');
                }
                //Con PDOException mostramos un mensaje de error cuando salte la excepción
            } catch (PDOException $excepcion) {
                echo 'Error: ' . $excepcion->getMessage() . "<br>";
                echo 'Código de error: ' . $excepcion->getCode() . "<br>";
            } finally {
                unset($miDB);//Cerramos la base de datos
            }
            
            try {                            
                echo '<h2>Conexion Incorrecta</h2>';
                $miDB = new PDO(DSN, 'dsfdsal', PASSWORD);//Establecemos la conexión con los datos incorrectos
               
                echo '<h2>Atributos PDO</h2>';
                //Se recorren los atricutos y se muestra el valor de cada uno
                foreach ($atributosPDO as $valor) {
                    echo ('PDO::ATTR_' . $valor . ' => ' . $miDB->getAttribute(constant("PDO::ATTR_$valor")). '<br>');
                }
                //Con PDOException mostramos un mensaje de error cuando salte la excepción
            } catch (PDOException $excepcion) {
                echo 'Error: ' . $excepcion->getMessage() . "<br>";
                echo 'Código de error: ' . $excepcion->getCode() . "<br>";
            } finally {
                unset($miDB);//Cerramos la base de datos
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
