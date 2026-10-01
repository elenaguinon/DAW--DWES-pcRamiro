<?php

// ======================================================
// 1. CARGAMOS LOS ARCHIVOS PHP NECESARIOS
// ======================================================

// require_once carga y ejecuta el archivo indicado.
//
// "once" significa que, aunque intentemos incluir el mismo
// archivo varias veces, PHP solamente lo cargará una vez.
//
// funciones.php contendrá las funciones de nuestra aplicación.
// Por ejemplo:
//
// function formatearPrecio($precio) {
//     ...
// }
require_once "funciones.php";


// datos.php contendrá los datos de nuestra aplicación.
//
// Por ejemplo, podría contener un array:
//
// $productos = [
//     ...
// ];
require_once "datos.php";


/*
 * ============================================================
 * ORDENACIÓN DE LOS PRODUCTOS
 * ============================================================
 *
 * Queremos permitir que el usuario pueda decidir cómo se
 * muestran los productos:
 *
 *      - por ID
 *      - por nombre
 *      - por precio
 *
 * El criterio de ordenación se recibirá mediante GET.
 *
 * Por ejemplo:
 *
 *      index.php?orden=nombre
 *      index.php?orden=precio
 *      index.php?orden=id
 *
 * Si visitamos:
 *
 *      index.php?orden=precio
 *
 * PHP tendrá disponible:
 *
 *      $_GET["orden"] = "precio";
 *
 * IMPORTANTE:
 * Los datos que llegan mediante GET proceden del usuario.
 * Por eso no accedemos directamente sin ningún control,
 * sino que utilizamos nuestra función leerCadena().
 */


/*
 * ------------------------------------------------------------
 * 1. LEER EL CRITERIO DE ORDENACIÓN
 * ------------------------------------------------------------
 *
 * leerCadena() recibe:
 *
 *      1. El array donde queremos buscar: $_GET
 *      2. La clave que queremos leer: "orden"
 *
 * Si la URL es:
 *
 *      index.php?orden=precio
 *
 * entonces:
 *
 *      $_GET["orden"] contiene "precio"
 *
 * y después de esta instrucción:
 *
 *      $orden contendrá "precio"
 *
 * Si "orden" no existe, nuestra función leerCadena()
 * devuelve una cadena vacía "".
 */

$orden = leerCadena($_GET, "orden");


/*
 * ------------------------------------------------------------
 * 2. ESTABLECER UN VALOR PREDETERMINADO
 * ------------------------------------------------------------
 *
 * Puede ocurrir que el usuario entre simplemente en:
 *
 *      index.php
 *
 * En ese caso no existe:
 *
 *      ?orden=...
 *
 * Por tanto, leerCadena() habrá devuelto:
 *
 *      ""
 *
 * Comprobamos si $orden contiene una cadena vacía.
 *
 * Utilizamos === porque queremos una comparación estricta:
 *
 *      valor + tipo
 */

if ($orden === "") {

    /*
     * Si el usuario no ha indicado ningún criterio,
     * utilizaremos el ID como orden predeterminado.
     */

    $orden = "id";
}


/*
 * ------------------------------------------------------------
 * 3. CREAR UNA COPIA DEL ARRAY DE PRODUCTOS
 * ------------------------------------------------------------
 *
 * $productos contiene los datos originales de la aplicación.
 *
 * En lugar de ordenar directamente:
 *
 *      $productos
 *
 * creamos otra variable:
 *
 *      $productosOrdenados
 *
 * que inicialmente contiene los mismos productos.
 *
 * Así podemos trabajar con una colección destinada
 * específicamente a la ordenación.
 */

$productosOrdenados = $productos;


/*
 * En este momento podemos imaginar:
 *
 *      $productos
 *          │
 *          │ copia
 *          ▼
 *      $productosOrdenados
 *
 * A partir de ahora ordenaremos:
 *
 *      $productosOrdenados
 */


/*
 * ============================================================
 * 4. DECIDIR CÓMO ORDENAR
 * ============================================================
 *
 * Comprobamos el contenido de $orden.
 *
 * Tenemos tres posibilidades:
 *
 *      "nombre"
 *      "precio"
 *      "id"
 *
 * Dependiendo del valor recibido utilizaremos un criterio
 * diferente para ordenar el array.
 */


/*
 * ------------------------------------------------------------
 * CASO 1: ORDENAR POR NOMBRE
 * ------------------------------------------------------------
 *
 * Entraremos aquí si la URL es:
 *
 *      index.php?orden=nombre
 */

if ($orden === "nombre") {

    /*
     * usort() es una función proporcionada por PHP que permite
     * ordenar un array utilizando nuestro propio criterio.
     *
     * Recibe principalmente dos elementos:
     *
     *      usort(
     *          array que queremos ordenar,
     *          función que indica cómo comparar
     *      );
     *
     * En nuestro caso, el array que queremos ordenar es:
     *
     *      $productosOrdenados
     */

    usort(
        $productosOrdenados,

        /*
         * El segundo argumento de usort() es una función.
         *
         * En este caso estamos utilizando una FUNCIÓN ANÓNIMA.
         *
         * Se llama "anónima" porque no tiene nombre.
         *
         * No hacemos:
         *
         *      function compararProductos(...)
         *
         * sino directamente:
         *
         *      function (...)
         *
         * porque esta función solamente la necesitamos aquí.
         *
         *
         * usort() irá tomando DOS productos del array
         * y se los proporcionará a nuestra función.
         *
         * Los llamamos:
         *
         *      $a
         *      $b
         *
         * Cada uno de ellos es un producto completo.
         *
         * Por ejemplo, $a podría ser:
         *
         *      [
         *          "id" => 1,
         *          "nombre" => "Teclado",
         *          "precio" => 7990
         *      ]
         *
         * y $b podría ser:
         *
         *      [
         *          "id" => 2,
         *          "nombre" => "Ratón",
         *          "precio" => 3990
         *      ]
         *
         * Indicamos "array $a" y "array $b" porque esperamos
         * recibir dos arrays.
         *
         * El ": int" indica que esta función debe devolver
         * un número entero.
         */

        function (array $a, array $b): int {

            /*
             * Para ordenar por nombre comparamos:
             *
             *      $a["nombre"]
             *
             * con:
             *
             *      $b["nombre"]
             *
             *
             * Utilizamos el operador:
             *
             *      <=>
             *
             * conocido como "operador nave espacial"
             * (spaceship operator).
             *
             * Este operador devuelve un valor:
             *
             *      negativo  → $a debe ir antes que $b
             *
             *      0         → ambos valores son iguales
             *
             *      positivo  → $a debe ir después que $b
             *
             * Ese resultado es precisamente lo que necesita
             * usort() para decidir el orden.
             */

            return $a["nombre"] <=> $b["nombre"];
        }
    );


/*
 * ------------------------------------------------------------
 * CASO 2: ORDENAR POR PRECIO
 * ------------------------------------------------------------
 *
 * Si no estamos ordenando por nombre, comprobamos si el
 * usuario ha solicitado ordenar por precio.
 *
 * Por ejemplo:
 *
 *      index.php?orden=precio
 */

} elseif ($orden === "precio") {

    /*
     * Volvemos a utilizar usort().
     *
     * La estructura es exactamente la misma que antes.
     *
     * Lo único que cambia es el CAMPO utilizado para comparar.
     */

    usort(
        $productosOrdenados,

        function (array $a, array $b): int {

            /*
             * Antes comparábamos:
             *
             *      nombre <=> nombre
             *
             * Ahora comparamos:
             *
             *      precio <=> precio
             *
             * Por ejemplo:
             *
             *      3990 <=> 7990
             *
             * devuelve un valor negativo porque:
             *
             *      3990 < 7990
             *
             * Por tanto, el producto de 3990 se colocará antes.
             *
             * El resultado será una ordenación ascendente:
             *
             *      precio menor
             *          ↓
             *      precio mayor
             */

            return $a["precio"] <=> $b["precio"];
        }
    );


/*
 * ------------------------------------------------------------
 * CASO 3: ORDENAR POR ID
 * ------------------------------------------------------------
 *
 * Llegaremos al else cuando $orden no sea:
 *
 *      "nombre"
 *
 * ni:
 *
 *      "precio"
 *
 * Esto incluye el caso:
 *
 *      $orden = "id"
 *
 * pero también protege frente a valores que nuestra
 * aplicación no reconoce.
 *
 * Por ejemplo, alguien podría escribir manualmente:
 *
 *      index.php?orden=patata
 */

} else {

    /*
     * Establecemos "id" como criterio.
     *
     * Esto es especialmente útil si alguien ha enviado un
     * valor no reconocido.
     *
     * Por ejemplo:
     *
     *      ?orden=patata
     *
     * terminará comportándose como:
     *
     *      ?orden=id
     */

    $orden = "id";


    /*
     * Ordenamos ahora los productos utilizando su ID.
     */

    usort(
        $productosOrdenados,

        function (array $a, array $b): int {

            /*
             * Comparamos:
             *
             *      ID del producto A
             *
             * con:
             *
             *      ID del producto B
             *
             * Por ejemplo:
             *
             *      1 <=> 3
             *
             * devolverá un valor negativo.
             *
             * Por tanto, el producto con ID 1 aparecerá
             * antes que el producto con ID 3.
             */

            return $a["id"] <=> $b["id"];
        }
    );
}


/*
 * ============================================================
 * RESULTADO
 * ============================================================
 *
 * Al terminar este bloque tenemos dos variables importantes:
 *
 *
 *      $orden
 *
 *          Contiene el criterio finalmente utilizado:
 *
 *              "id"
 *              "nombre"
 *              "precio"
 *
 *
 *      $productosOrdenados
 *
 *          Contiene los productos ya ordenados según
 *          el criterio seleccionado.
 *
 *
 * Por tanto, cuando posteriormente generemos el HTML,
 * debemos recorrer:
 *
 *      $productosOrdenados
 *
 * y NO $productos.
 *
 * Por ejemplo:
 *
 *      foreach ($productosOrdenados as $producto) {
 *          ...
 *      }
 */


/*
 * ============================================================
 * RESUMEN DEL FLUJO
 * ============================================================
 *
 * Usuario pulsa:
 *
 *      "Por precio"
 *
 *              ↓
 *
 * Navegador solicita:
 *
 *      index.php?orden=precio
 *
 *              ↓
 *
 * PHP recibe:
 *
 *      $_GET["orden"] = "precio"
 *
 *              ↓
 *
 * leerCadena()
 *
 *              ↓
 *
 *      $orden = "precio"
 *
 *              ↓
 *
 * if / elseif / else
 *
 *              ↓
 *
 * entra en:
 *
 *      elseif ($orden === "precio")
 *
 *              ↓
 *
 * usort()
 *
 *              ↓
 *
 * compara:
 *
 *      $a["precio"] <=> $b["precio"]
 *
 *              ↓
 *
 *      $productosOrdenados
 *
 * queda ordenado de menor a mayor precio.
 */

?>

<!DOCTYPE html>

<!--
    A partir de aquí tenemos principalmente HTML.

    PHP se ejecuta EN EL SERVIDOR.

    El navegador NO recibe este código PHP.
    El navegador recibirá únicamente el HTML generado.
-->
<html lang="es">

<head>

    <!-- Codificación de caracteres -->
    <meta charset="UTF-8">

    <!-- Adaptación a dispositivos móviles -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <!-- Título mostrado en la pestaña del navegador -->
    <title>DWES Store</title>

    <!-- Hoja de estilos CSS externa -->
    <link
        rel="stylesheet"
        href="estilos.css"
    >

</head>


<body>


<!-- ==================================================
     CABECERA DE LA PÁGINA
     ================================================== -->

<header class="cabecera">

    <div class="contenedor">

        <h1>DWES Store</h1>

        <p>Versión estática en HTML y CSS</p>


        <!--
            Menú de navegación.

            Cada enlace realizará una nueva petición
            HTTP al servidor.
        -->
        <nav class="navegacion">

            <a href="index.php">
                Inicio
            </a>

            <a href="buscar.php">
                Buscar
            </a>

            <a href="compra.php">
                Comprar
            </a>

        </nav>

    </div>

</header>



<!-- ==================================================
     CONTENIDO PRINCIPAL
     ================================================== -->

<main class="contenedor">


    <!-- Título de la sección -->
    <section class="panel">

        <h2>Catálogo</h2>
        <p>Orden actual:  </p>
        <nav class="navegacion">
            <a href="index.php?orden=id">Por id</a>
            <a href="index.php?orden=nombre">Por Nombre</a>
            <a href="index.php?orden=precio">Por Precio</a>
        </nav>

    </section>



    <!-- ==================================================
         CATÁLOGO DE PRODUCTOS
         ================================================== -->

    <section class="grid-productos">


        <?php

        // Recorremos el array $productos.
        //
        // En cada iteración, $producto contendrá
        // uno de los productos del array.
        //
        // Si tenemos:
        //
        // $productos = [
        //     ["nombre" => "Teclado", ...],
        //     ["nombre" => "Ratón", ...],
        //     ["nombre" => "Monitor", ...]
        // ];
        //
        // foreach realizará 3 iteraciones.
        //
        // Primera:
        // $producto → Teclado
        //
        // Segunda:
        // $producto → Ratón
        //
        // Tercera:
        // $producto → Monitor

        foreach ($productosOrdenados as $producto) {

        ?>


            <!--
                Este <article> se generará una vez
                por cada producto existente en el array.
            -->
            <article class="producto">


                <!-- ==============================
                     NOMBRE DEL PRODUCTO
                     ============================== -->

                <h2>

                    <?=
                        // <?= es una forma abreviada de:
                        //
                        // <?php echo ...;
                        //
                        // Mostramos el nombre del producto.
                        $producto["nombre"]
                    ?>

                </h2>



                <!-- ==============================
                     CATEGORÍA
                     ============================== -->

                <p>

                    Categoria:

                    <?=
                        // Accedemos al valor asociado
                        // a la clave "categoria".
                        $producto["categoria"]
                    ?>

                </p>



                <!-- ==============================
                     PRECIO
                     ============================== -->

                <p class="precio">

                    <?=
                        // Llamamos a nuestra función
                        // formatearPrecio().
                        //
                        // Le pasamos como argumento
                        // el precio del producto.
                        //
                        // Por ejemplo:
                        //
                        // 29.9
                        //
                        // podría convertirse en:
                        //
                        // 29,90 €

                        formatearPrecio(
                            $producto["precio"]
                        )
                    ?>

                </p>



                <!-- ==============================
                     STOCK
                     ============================== -->

                <p>

                    Stock:

                    <?=
                        // Mostramos el stock disponible.
                        $producto["stock"]
                    ?>

                </p>

                <p class="estado <?= obtenerClaseEstado($producto["stock"]); ?>">



                    Estado:

                    <?=
                        // Mostramos el stock disponible.
                        obtenerEstadoStock($producto["stock"]);
                    ?>

                </p>


            </article>


        <?php

        // Cerramos el bloque correspondiente
        // al foreach.

        /// foreach ($usuarios $usuairo):
            //echo $usuario
            //endforeach
                       
                        }

        ?>


    </section>

</main>



<!-- ==================================================
     PIE DE PÁGINA
     ================================================== -->

<footer class="pie">

    <div class="contenedor">

        Proyecto de Desarrollo Web en Entorno Servidor

    </div>

</footer>


</body>

</html>