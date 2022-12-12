<?php

    require 'config/database.php';
    $db = conectarDB();

    // echo "<pre>";
    // var_dump($_SERVER);
    // echo "</pre>";

    $consulta = "SELECT * FROM generos";
    $resultado = mysqli_query($db, $consulta);

    $errores = [];

    $titulo = "";
    $precio = "";
    $descripcion = "";
    $generosID = "";

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        
        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $imagen = $_FILES['imagen'];
        $precio = mysqli_real_escape_string($db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $generosID = mysqli_real_escape_string($db, $_POST['genero']);

        // echo "<pre>";
        // var_dump($imagen);
        // echo "</pre>";

        if(!$titulo) {
            $errores[] = "Debes añadir un titulo";
        }

        if(!$imagen['name'] || $imagen['error']) {
            $errores[] = "Debes añadir una imagen";
        }

        if(!$precio) {
            $errores[] = "Debes añadir el precio";
        }

        if(strlen($descripcion) < 50 ) {
            $errores[] = "Debes añadir la descripcion (Min: 50 caracteres)";
        }

        if(!$generosID) {
            $errores[] = "Elige un genero";
        }

        $carpetaIMG = "imagenes/";

        if(!is_dir($carpetaIMG)) {
            mkdir($carpetaIMG);
        }

        // Nombre unico para imagenes
        $nombreImagen = md5( uniqid( rand(), true) ) . ".jpg";

        // Subir la imagen
        move_uploaded_file($imagen['tmp_name'], $carpetaIMG . $nombreImagen);

        if(empty($errores)) {

            // Insertar
            $query = "INSERT INTO peliculas (titulo, imagen, precio, descripcion, generos_id) 
            VALUES ('$titulo', '$nombreImagen', '$precio', '$descripcion', '$generosID')";

            echo $query;

            $resultado = mysqli_query($db, $query);
        }
        
    }

    // Incluir el header
    include 'templates/header.php';
?>
    <main class="contenedor">
        <h1>Ingreso de Peliculas</h1>

        <?php foreach($errores as $error): ?>
                <div class="alerta error">
                    <?php echo $error; ?>
                </div>
        <?php endforeach; ?>

        <form class="formulario" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>

                <label for="titulo">Titulo</label>
                <input type="text" name="titulo" id="titulo" placeholder="Ingrese Titulo">

                <label for="imagen">Imagen</label>
                <input type="file" name="imagen" id="imagen" accept="image/jpeg, image/png">

                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Ingrese Precio">

                <label for="descripcion">Descripcion</label>
                <textarea id="descripcion" name="descripcion" placeholder="Ingrese Descripcion"></textarea>
            </fieldset>

            <fieldset>
                <legend>Genero</legend>
                <select name="genero">
                    <option value="">--- Seleccione ---</option>
                    <?php while ($generos = mysqli_fetch_assoc($resultado)): ?>
                        <option <?php $genero === $generos['id'] ? 'selected' : '';  ?> value="<?php echo $generos['id']; ?>"><?php echo $generos['genero']; ?></option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <div class="botones">
                <input class="boton boton-azul" type="submit" value="Añadir">
                <a href="gestion.php" class="boton boton-verde">Gestionar Peliculas</a>    
            </div>
            
        </form>
            
            
        
    </main>

<?php 

    // Incluir el footer
    include 'templates/footer.php';
    mysqli_close($db);
?>

    