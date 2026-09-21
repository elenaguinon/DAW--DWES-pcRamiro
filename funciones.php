<?php

// ======================================================
// FUNCIÓN PARA FORMATEAR UN PRECIO
// ======================================================

// La función recibe un precio expresado en CÉNTIMOS.
//
// Ejemplo:
// 2999 céntimos → 29,99 €
//
// int $centimos
//     Indica que el parámetro debe ser un número entero.
//
// : string
//     Indica que la función devolverá un string.

function formatearPrecio(int $centimos): string
{

    // Convertimos los céntimos a euros.
    //
    // Ejemplo:
    //
    // 2999 / 100 = 29.99

    $euros = $centimos / 100;


    // number_format() permite dar formato a un número.
    //
    // En este caso:
    //
    // number_format(
    //     $euros,  // número que queremos formatear
    //     2,       // número de decimales
    //     ",",     // separador decimal
    //     "."      // separador de miles
    // )
    //
    // Ejemplo:
    //
    // 123456 céntimos
    //       ↓
    // 1234.56 euros
    //       ↓
    // "1.234,56"
    //
    // Finalmente concatenamos " €".

    return number_format(
        $euros,
        2,
        ",",
        "."
    ) . " €";
}
<?php

/*
|--------------------------------------------------------------------------
| FUNCIÓN: obtenerEstadoStock()
|--------------------------------------------------------------------------
|
| OBJETIVO:
| Esta función recibe el número de unidades disponibles de un producto
| y devuelve un texto que describe su estado.
|
| Ejemplos:
|
|     obtenerEstadoStock(0)   → "Agotado"
|     obtenerEstadoStock(3)   → "Últimas unidades"
|     obtenerEstadoStock(5)   → "Últimas unidades"
|     obtenerEstadoStock(10)  → "Disponible"
|
*/


function obtenerEstadoStock(int $stock): string
{
    /*
     * int $stock
     * ----------
     * La función recibe un parámetro llamado $stock.
     *
     * Hemos indicado "int", por lo que esperamos trabajar con
     * un número entero.
     *
     * Por ejemplo:
     *
     *     obtenerEstadoStock(7);
     *
     * Dentro de la función:
     *
     *     $stock = 7
     *
     *
     * : string
     * --------
     * Después de los paréntesis aparece ": string".
     *
     * Esto indica que la función debe DEVOLVER una cadena de texto.
     *
     * Por ejemplo:
     *
     *     "Agotado"
     *     "Últimas unidades"
     *     "Disponible"
     */


    /*
     * PRIMER CASO:
     * Comprobamos si el stock es exactamente 0.
     *
     * === es el operador de comparación estricta.
     *
     * Compara tanto el VALOR como el TIPO.
     *
     * En este caso estamos preguntando:
     *
     *     ¿$stock es exactamente el entero 0?
     */

    if ($stock === 0) {

        /*
         * Si la condición anterior es verdadera,
         * devolvemos el texto "Agotado".
         *
         * IMPORTANTE:
         *
         * return no solamente devuelve un valor.
         * También TERMINA inmediatamente la ejecución de la función.
         *
         * Por tanto, si $stock vale 0:
         *
         *     return "Agotado";
         *
         * y PHP ya no continúa ejecutando las siguientes
         * instrucciones de esta función.
         */

        return "Agotado";
    }


    /*
     * Si hemos llegado hasta aquí significa que el stock NO era 0.
     *
     * Ahora comprobamos si quedan 5 unidades o menos.
     *
     * <= significa "menor o igual que".
     *
     * Algunos ejemplos:
     *
     *     1 <= 5  → true
     *     3 <= 5  → true
     *     5 <= 5  → true
     *     6 <= 5  → false
     */

    if ($stock <= 5) {

        /*
         * Si quedan entre 1 y 5 unidades,
         * consideramos que quedan pocas unidades.
         */

        return "Últimas unidades";
    }


    /*
     * Si hemos llegado a esta línea significa que:
     *
     *     - $stock NO es 0.
     *     - $stock NO es menor o igual que 5.
     *
     * Por tanto, necesariamente tenemos más de 5 unidades.
     *
     * No necesitamos escribir:
     *
     *     if ($stock > 5)
     *
     * porque los casos anteriores ya han sido descartados.
     */

    return "Disponible";
}



/*
|--------------------------------------------------------------------------
| FUNCIÓN: obtenerClaseEstado()
|--------------------------------------------------------------------------
|
| OBJETIVO:
|
| Esta función utiliza exactamente la misma lógica de stock que la
| función anterior, pero tiene una finalidad diferente.
|
| obtenerEstadoStock() devuelve el TEXTO que verá el usuario:
|
|     "Agotado"
|     "Últimas unidades"
|     "Disponible"
|
| obtenerClaseEstado() devuelve el NOMBRE DE UNA CLASE CSS:
|
|     "agotado"
|     "aviso"
|     "disponible"
|
| Esto nos permitirá generar HTML dinámicamente.
|
| Por ejemplo:
|
|     <span class="estado aviso">
|         Últimas unidades
|     </span>
|
*/


function obtenerClaseEstado(int $stock): string
{
    /*
     * Recibimos nuevamente el stock como un entero.
     *
     * La función devuelve un string porque los nombres
     * de las clases CSS son cadenas de texto.
     */


    /*
     * Si no queda ninguna unidad...
     */

    if ($stock === 0) {

        /*
         * Devolvemos el nombre de la clase CSS:
         *
         *     agotado
         *
         * Esta clase existe en estilos.css:
         *
         *     .agotado {
         *         color: var(--color-error);
         *     }
         */

        return "agotado";
    }


    /*
     * Si quedan 5 unidades o menos...
     *
     * Como el caso 0 ya terminó anteriormente con return,
     * aquí realmente estaremos trabajando con valores
     * comprendidos entre 1 y 5.
     */

    if ($stock <= 5) {

        /*
         * Devolvemos la clase CSS "aviso".
         */

        return "aviso";
    }


    /*
     * Si no se cumplió ninguna condición anterior,
     * quedan más de 5 unidades.
     *
     * Devolvemos la clase CSS "disponible".
     */

    return "disponible";
}



/*
|--------------------------------------------------------------------------
| FUNCIÓN: escapar()
|--------------------------------------------------------------------------
|
| OBJETIVO:
|
| Preparar una cadena de texto para mostrarla de forma segura
| dentro del HTML.
|
| Es especialmente importante cuando los datos pueden proceder
| de formularios, parámetros GET, bases de datos, etc.
|
| La idea fundamental es evitar que determinados caracteres sean
| interpretados por el navegador como código HTML.
|
*/


function escapar(string $texto): string
{
    /*
     * La función recibe:
     *
     *     string $texto
     *
     * Es decir, una cadena de texto.
     *
     * Y devuelve:
     *
     *     : string
     *
     * otra cadena de texto.
     */


    /*
     * htmlspecialchars() es una función incorporada en PHP.
     *
     * Convierte caracteres que tienen un significado especial
     * en HTML en entidades HTML.
     *
     * Por ejemplo, si tenemos:
     *
     *     $texto = "<h1>Hola</h1>";
     *
     * sin escapar, el navegador interpretaría <h1> como
     * una etiqueta HTML.
     *
     * Al utilizar htmlspecialchars(), los símbolos < y >
     * se convierten en una representación segura.
     *
     * De esta manera el navegador mostrará:
     *
     *     <h1>Hola</h1>
     *
     * como TEXTO, en lugar de interpretarlo como una etiqueta.
     */

    return htmlspecialchars(

        /*
         * Primer argumento:
         *
         * El texto que queremos escapar.
         */

        $texto,


        /*
         * Segundo argumento:
         *
         * Son opciones que modifican el comportamiento
         * de htmlspecialchars().
         *
         * ENT_QUOTES:
         *
         * Indica que también queremos convertir tanto las
         * comillas dobles como las comillas simples.
         *
         *
         * ENT_SUBSTITUTE:
         *
         * Si PHP encuentra una secuencia de caracteres inválida
         * para la codificación utilizada, la sustituye por un
         * carácter de reemplazo.
         *
         *
         * El símbolo | permite combinar ambas opciones.
         *
         * IMPORTANTE:
         *
         * Aquí | NO es lo mismo que ||.
         *
         * || es el OR lógico que utilizamos normalmente
         * en condiciones.
         *
         * | permite aquí combinar flags/opciones.
         */

        ENT_QUOTES | ENT_SUBSTITUTE,


        /*
         * Tercer argumento:
         *
         * Indicamos la codificación de caracteres utilizada.
         *
         * UTF-8 permite trabajar correctamente con caracteres
         * como:
         *
         *     á é í ó ú
         *     ñ
         *     €
         *
         * Además coincide con lo que normalmente tenemos
         * en nuestro HTML:
         *
         *     <meta charset="UTF-8">
         */

        "UTF-8"
    );
}



/*
|--------------------------------------------------------------------------
| FUNCIÓN: buscarProductoPorId()
|--------------------------------------------------------------------------
|
| OBJETIVO:
|
| Buscar un producto concreto dentro del array $productos
| utilizando su identificador.
|
| Ejemplo:
|
| Tenemos:
|
|     $productos = [
|         [
|             "id" => 1,
|             "nombre" => "Teclado"
|         ],
|         [
|             "id" => 2,
|             "nombre" => "Ratón"
|         ]
|     ];
|
| Y hacemos:
|
|     $producto = buscarProductoPorId($productos, 2);
|
| La función recorrerá los productos hasta encontrar:
|
|     "id" => 2
|
| y devolverá el array correspondiente al Ratón.
|
*/


function buscarProductoPorId(array $productos, int $id): ?array
{
    /*
     * Esta función recibe DOS parámetros:
     *
     * 1. array $productos
     *
     *    El array que contiene todos nuestros productos.
     *
     *
     * 2. int $id
     *
     *    El identificador del producto que queremos encontrar.
     *
     *
     * El tipo de retorno es:
     *
     *     ?array
     *
     * El signo ? delante de array significa:
     *
     *     array O null
     *
     * Es decir, la función puede devolver:
     *
     *     - un array si encuentra el producto;
     *
     *     - null si no encuentra ningún producto con ese ID.
     */


    /*
     * foreach permite recorrer todos los elementos
     * contenidos en $productos.
     *
     * En cada vuelta:
     *
     *     $producto
     *
     * contiene UNO de los productos.
     *
     * Si tenemos cinco productos, el foreach podrá
     * realizar hasta cinco vueltas.
     */

    foreach ($productos as $producto) {


        /*
         * En cada vuelta comprobamos el ID del producto actual.
         *
         * Por ejemplo:
         *
         *     $producto["id"]
         *
         * podría valer:
         *
         *     1
         *     2
         *     3
         *     ...
         *
         * Lo comparamos con el ID que estamos buscando.
         *
         * Utilizamos === para realizar una comparación estricta.
         */

        if ($producto["id"] === $id) {


            /*
             * ¡Producto encontrado!
             *
             * Devolvemos el array completo del producto.
             *
             * IMPORTANTE:
             *
             * return termina inmediatamente la función.
             *
             * Por tanto, una vez encontrado el producto,
             * no necesitamos seguir recorriendo el array.
             */

            return $producto;
        }
    }


    /*
     * Esta línea solamente se ejecutará si el foreach
     * ha terminado completamente SIN encontrar ningún
     * producto cuyo ID coincida con $id.
     *
     * En ese caso devolvemos:
     *
     *     null
     *
     * null representa aquí:
     *
     *     "No se ha encontrado ningún producto".
     */

    return null;
}