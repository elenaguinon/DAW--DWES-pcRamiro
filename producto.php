<?php

/*
|--------------------------------------------------------------------------
| DETALLE DE UN PRODUCTO
|--------------------------------------------------------------------------
|
| Este archivo se encarga de mostrar la información de UN producto concreto.
|
| El producto que queremos mostrar se indica mediante un parámetro GET
| llamado "id".
|
| Por ejemplo:
|
|     producto.php?id=2
|
| Cuando el navegador solicita esa URL, PHP recibe el parámetro mediante
| el array superglobal $_GET:
|
|     $_GET["id"]
|
| IMPORTANTE:
| Los datos que llegan desde una URL son datos externos introducidos por
| el usuario, por lo que NO debemos confiar directamente en ellos.
|
| El proceso que seguimos en este archivo es:
|
|     URL
|      |
|      v
|     $_GET["id"]
|      |
|      v
|     $idBruto
|      |
|      v
|     Validamos que sea un número entero
|      |
|      v
|     $id
|      |
|      +---- ID incorrecto ----------> Error 400
|      |
|      v
|     Buscamos el producto
|      |
|      +---- No existe --------------> Error 404
|      |
|      v
|     Producto encontrado
|
|
| Ejemplos:
|
|     producto.php?id=2
|         -> El ID es válido.
|         -> Buscamos el producto 2.
|
|     producto.php?id=hola
|         -> "hola" no es un entero.
|         -> Respondemos con HTTP 400.
|
|     producto.php?id=-5
|         -> Es un entero, pero no aceptamos IDs menores que 1.
|         -> Respondemos con HTTP 400.
|
|     producto.php?id=999
|         -> El ID tiene un formato válido.
|         -> Pero el producto 999 no existe.
|         -> Respondemos con HTTP 404.
|
|
| DIFERENCIA IMPORTANTE:
|
|     400 Bad Request
|     ----------------
|     La petición contiene un ID que consideramos inválido.
|
|     Ejemplo:
|
|         producto.php?id=hola
|
|
|     404 Not Found
|     -------------
|     El ID es válido, pero no existe ningún producto con ese ID.
|
|     Ejemplo:
|
|         producto.php?id=999
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 1. CARGAMOS LOS ARCHIVOS NECESARIOS
|--------------------------------------------------------------------------
|
| datos.php contiene el array $productos.
|
| funciones.php contiene funciones reutilizables, por ejemplo:
|
|     buscarProductoPorId()
|
| Utilizamos require_once porque estos archivos son necesarios para que
| esta página pueda funcionar y queremos cargarlos una sola vez.
|
*/

require_once "datos.php";
require_once "funciones.php";


/*
|--------------------------------------------------------------------------
| 2. RECOGEMOS EL ID QUE LLEGA POR GET
|--------------------------------------------------------------------------
|
| Si visitamos:
|
|     producto.php?id=3
|
| PHP tendrá aproximadamente:
|
|     $_GET["id"] = "3";
|
| Utilizamos el operador ?? (fusión de null) para evitar problemas si
| el parámetro "id" no existe.
|
| Es decir:
|
|     $_GET["id"] ?? ""
|
| significa:
|
|     "Si existe $_GET['id'], utiliza su valor.
|      Si no existe, utiliza una cadena vacía."
|
| Lo llamamos $idBruto porque todavía NO lo hemos validado.
|
*/

$idBruto = $_GET["id"] ?? "";


/*
|--------------------------------------------------------------------------
| 3. VALIDAMOS EL ID
|--------------------------------------------------------------------------
|
| filter_var() permite validar o filtrar valores.
|
| FILTER_VALIDATE_INT indica que queremos comprobar si el valor
| representa un número entero válido.
|
| Ejemplo:
|
|     $idBruto = "3";
|
|     filter_var("3", FILTER_VALIDATE_INT)
|
| devuelve:
|
|     3
|
| como número entero.
|
| Pero:
|
|     filter_var("hola", FILTER_VALIDATE_INT)
|
| devuelve:
|
|     false
|
| Por eso $id puede contener:
|
|     int     -> si el dato es válido
|     false   -> si el dato NO es un entero válido
|
*/

$id = filter_var($idBruto, FILTER_VALIDATE_INT);


/*
|--------------------------------------------------------------------------
| 4. PREPARAMOS LAS VARIABLES QUE UTILIZAREMOS DESPUÉS
|--------------------------------------------------------------------------
|
| Inicialmente todavía no tenemos ningún producto.
|
| Por eso:
|
|     $producto = null;
|
| null significa que actualmente no tenemos un producto asignado.
|
| También preparamos una variable para almacenar un posible mensaje
| de error.
|
| Al principio no existe ningún error, así que contiene una cadena vacía.
|
*/

$producto = null;
$error = "";


/*
|--------------------------------------------------------------------------
| 5. COMPROBAMOS SI EL ID ES VÁLIDO
|--------------------------------------------------------------------------
|
| Hay dos situaciones que consideramos incorrectas:
|
|     $id === false
|
| El dato recibido no representa un entero válido.
|
| Por ejemplo:
|
|     producto.php?id=hola
|
|
| O bien:
|
|     $id < 1
|
| Es un entero, pero no aceptamos identificadores menores que 1.
|
| Por ejemplo:
|
|     producto.php?id=-3
|
|
| Utilizamos ||, que significa OR ("o").
|
| Por tanto:
|
|     if ($id === false || $id < 1)
|
| significa:
|
|     "Si el ID no es un entero válido
|      O
|      el ID es menor que 1..."
|
*/

if ($id === false || $id < 1) {

    /*
     * Indicamos que la petición realizada por el cliente
     * contiene datos incorrectos.
     *
     * HTTP 400 = Bad Request
     */

    http_response_code(400);

    /*
     * Guardamos el mensaje que podremos mostrar posteriormente
     * en el HTML.
     */

    $error = "El id de producto no es válido";

} else {

    /*
    |--------------------------------------------------------------------------
    | 6. EL ID ES VÁLIDO: BUSCAMOS EL PRODUCTO
    |--------------------------------------------------------------------------
    |
    | Si hemos llegado hasta aquí sabemos que $id contiene un entero
    | válido y mayor o igual que 1.
    |
    | Ahora buscamos dentro del array $productos.
    |
    | La función buscarProductoPorId() recibe:
    |
    |     1. El array de productos.
    |     2. El ID que queremos encontrar.
    |
    | Por ejemplo:
    |
    |     buscarProductoPorId($productos, 3);
    |
    | La función puede devolver:
    |
    |     array  -> si encuentra el producto.
    |
    |     null   -> si no encuentra ningún producto con ese ID.
    |
    */

    $producto = buscarProductoPorId($productos, $id);


    /*
    |--------------------------------------------------------------------------
    | 7. COMPROBAMOS SI EL PRODUCTO EXISTE
    |--------------------------------------------------------------------------
    |
    | Si buscarProductoPorId() devuelve null significa:
    |
    |     "El ID era válido, pero no existe ese producto."
    |
    | Por ejemplo:
    |
    |     producto.php?id=999
    |
    | 999 puede ser perfectamente un entero válido.
    |
    | Pero si no existe ningún producto con id 999, devolvemos:
    |
    |     HTTP 404 = Not Found
    |
    */

    if ($producto === null) {

        http_response_code(404);

        $error = "El producto no existe";
    }
}

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto - DWES Store</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

<header class="cabecera">
    <div class="contenedor">
        <h1>DWES Store</h1>

        <nav class="navegacion">
            <a href="index.php">Inicio</a>
            <a href="buscar.php">Buscar</a>
            <a href="compra.php">Comprar</a>
        </nav>
    </div>
</header>

<main class="contenedor">

    <article class="producto">

        <h2>Teclado mecánico</h2>

        <p>Categoría: Periféricos</p>

        <p class="precio">79,90 €</p>

        <p>Stock: 7</p>

        <p>
            Estado:
            <span class="estado disponible">Disponible</span>
        </p>

        <div class="acciones">
            <a class="boton" href="compra.php">
                Comprar
            </a>
        </div>

    </article>

</main>

</body>
</html>