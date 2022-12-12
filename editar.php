<?php

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header("Location: gestion.php");
    }

    require 'config/database.php';
    $db = conectarDB();

    $sql = "SELECT * FROM peliculas WHERE id = ${id}";
    $res = mysqli_query($db, $sql);
    $pelicula = mysqli_fetch_assoc($res);

    $consulta = "SELECT * FROM generos";
    $resultado = mysqli_query($db, $consulta);

    $errores = [];

    $titulo = $pelicula['titulo'];
    $precio = $pelicula['precio'];
    $descripcion = $pelicula['descripcion'];
    $imagen = $pelicula['imagen'];
    $generos = $pelicula['genero'];

    if($_SERVER["REQUEST_METHOD"] === "POST") {

        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        // exit;

        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $imagen = $_FILES['imagen'];
        $precio = mysqli_real_escape_string($db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $genero = mysqli_real_escape_string($db, $_POST['genero']);

        // echo "<pre>";
        // var_dump($imagen);
        // echo "</pre>";

        if(!$titulo) {
            $errores[] = "Debes añadir un titulo";
        }

        if(!$precio) {
            $errores[] = "Debes añadir el precio";
        }

        if(strlen($descripcion) < 50 ) {
            $errores[] = "Debes añadir la descripcion (Min: 50 caracteres)";
        }

        if(!$genero) {
            $errores[] = "Elige un genero";
        }


        if(empty($errores)) {

            $carpetaIMG = "imagenes/";

            if(!is_dir($carpetaIMG)) {
                mkdir($carpetaIMG);
            }

            $nombreImagen = "";

            if($imagen['name']) {
            
                // Eliminar la imagen previa
                unlink($carpetaIMG . $pelicula['imagen']);

                // Nombre unico para imagenes
                $nombreImagen = md5( uniqid( rand(), true) ) . ".jpg";

                // Subir la imagen
                move_uploaded_file($imagen['tmp_name'], $carpetaIMG . $nombreImagen);
            } else {
                $nombreImagen = $pelicula['imagen'];
            }


            // Insertar
            $query = " UPDATE peliculas SET titulo = '${titulo}', precio = ${precio},
            imagen = '${nombreImagen}', descripcion = '${descripcion}', generos_id = ${genero} WHERE id = ${id} ";

            // echo $query;

            $resultado = mysqli_query($db, $query);

            if($resultado) {
                header("Location: gestion.php?resultado=2");
            }
        }


    }

    include 'templates/header.php';
?>

    <main class="contenedor">
        <h1>Editar Registro</h1>

        <form class="formulario" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>

                <label for="titulo">Titulo</label>
                <input type="text" name="titulo" id="titulo" placeholder="Ingrese Titulo" value="<?php echo $titulo; ?>">

                <label for="imagen">Imagen</label>
                <input type="file" name="imagen" id="imagen" accept="image/jpeg, image/png">

                <img class="imagen-pequeña" src="imagenes/<?php echo $imagen; ?>">

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Ingrese Precio" value="<?php echo $precio; ?>">

                <label for="descripcion">Descripcion</label>
                <textarea id="descripcion" name="descripcion" placeholder="Ingrese Descripcion"><?php echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Genero</legend>
                <select name="genero">
                    <option value="">--- Seleccione ---</option>
                    <?php while ($generos = mysqli_fetch_assoc($resultado)): ?>
                        <option <?php echo $genero === $generos['id'] ? 'selected' : ''; ?>  value="<?php echo $generos['id']; ?>"><?php echo $generos['genero']; ?></option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <div class="botones">
                <input class="boton boton-azul" type="submit" value="Editar">
                <a href="gestion.php" class="boton boton-verde">Gestionar Peliculas</a>    
            </div>
            
        </form>

    </main>

<?php
    include 'templates/footer.php';
?>