<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="webroot/css/index.css" type="text/css">
        <title>Víctor García Gordón</title>
    </head>
    <body>
        <header>      
            <h1 id="inicio">TEMA 4 TÉCNICAS DE ACCESO A DATOS EN PHP</h1>
        </header>
        <main>
            <nav>
                <table>
                    <tr>
                        <th>Script creación BD</th>
                        <th>Script creación BD</th>
                        <th>Script creación BD</th>
                    </tr>
                    <tr>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <th>nº</th>
                        <th>Enunciado</th>
                        <th colspan="2">PDO</th>
                        <th colspan="2">mysqli</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Conexión a la base de datos con la cuenta usuario y tratamiento de errores.
                        Utilizar excepciones automáticas siempre que sea posible en todos los ejercicios.</td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Mostrar el contenido de la tabla Departamento y el número de registros</td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Formulario para añadir un departamento a la tabla Departamento con validación de entrada y
                        control de errores.</td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Formulario de búsqueda de departamentos por descripción (por una parte del campo
                        DescDepartamento, si el usuario no pone nada deben aparecer todos los departamentos).
                        </td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Pagina web que añade tres registros a nuestra tabla Departamento utilizando tres instrucciones
                        insert y una transacción, de tal forma que se añadan los tres registros o no se añada ninguno.
                        </td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Pagina web que cargue registros en la tabla Departamento desde un array departamentosnuevos
                        utilizando una consulta preparada. (Después de programar y entender este ejercicio, modificar los
                        ejercicios anteriores para que utilicen consultas preparadas). Probar consultas preparadas sin bind,
                        pasando los parámetros en un array a execute. </td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Página web que toma datos (código y descripción) de un fichero xml y los añade a la tabla
                        Departamento de nuestra base de datos. (IMPORTAR). El fichero importado se encuentra en el
                        directorio .../tmp/ del servidor</td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Página web que toma datos (código y descripción) de la tabla Departamento y guarda en un
                        fichero departamento.xml. (COPIA DE SEGURIDAD / EXPORTAR). El fichero exportado se
                        encuentra en el directorio .../tmp/ del servidor.
                        </td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Aplicación resumen MtoDeDepartamentosTema4. (Incluir PHPDoc y versionado en el repositorio
                        GIT)</td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Aplicación resumen MtoDeDepartamentos POO y multicapa.</td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                        <td><a href=""><img src="doc/play.png" alt="play"></a></td>
                        <td><a href=""><img src="doc/show.png" alt="show"></a></td>
                    </tr>
                </table>
            </nav>       
        </main>
        <footer>
            <div>
                <a href="/index.html">Víctor García Gordón</a>
                <a href="/202DWESProyectoDWES/indexProyectoDWES.php">DWES</a>
                <a target="blank" href="doc/curriculum.pdf"><img src="doc/curriculum.jpg" alt="curriculum"></a>
                <a target="blank" href="https://github.com/victorgargor/202DWESProyectoTema4"><img src="doc/github.png" alt="github"></a>
            </div>
        </footer>
    </body>
</html>


