<?php

    require 'config/database.php';
    $db = conectarDB();

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    $consulta = "SELECT titulo, imagen, precio, descripcion, genero FROM peliculas, generos 
    WHERE generos.id = peliculas.generos_id AND peliculas.id = ${id}";
    $resultado = mysqli_query($db, $consulta);
    $pelicula = mysqli_fetch_assoc($resultado);

    include 'templates/header.php';
?>

    <main class="contenedor">
        <div class="contenido-pelicula">
            <img src="imagenes/<?php echo $pelicula['imagen']; ?>" alt="poster pelicula" loading="lazy">

            <div class="desc-pelicula">
                <h2><?php echo $pelicula['titulo']; ?></h2>
                <p>Genero: <span class="genero"><?php echo $pelicula['genero']; ?></span></p>
                <p><?php echo $pelicula['descripcion']; ?></p>
            </div>
        </div>
    </main>


<?php
    mysqli_close($db);
    include 'templates/footer.php';
?>