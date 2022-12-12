<?php 

    require 'config/database.php';
    $db = conectarDB();

    $consulta = "SELECT * FROM peliculas";

    $resultado = mysqli_query($db, $consulta);


    include 'templates/header.php';
?>

    <main class="contenedor">
        <h1>Peliculas en Venta</h1>

        <div class="contenedor-peliculas">
            <?php while($pelicula = mysqli_fetch_assoc($resultado)): ?>
            <div class="pelicula">
                <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>">
                    <img src="imagenes/<?php echo $pelicula['imagen']; ?>" alt="poster" loading="lazy">
                </a>
                

                <div class="info-pelicula">
                    <h2><?php echo $pelicula['titulo']; ?></h2>
                    <p class="precio">$ <?php echo $pelicula['precio']; ?></p>

                    <a href="pelicula.php?id=<?php echo $pelicula['id']; ?>" class="boton boton-azul">Ver Detalles</a>
                </div>

            </div>

            <!-- <div class="pelicula">
                <img src="imagenes_raiz/avatar2.jpg" alt="poster" loading="lazy">

                <div class="info-pelicula">
                    <h2>Avatar 2</h2>
                    <p class="precio">$ 25000</p>

                    <a href="pelicula.php" class="boton boton-azul">Ver Detalles</a>
                </div>

            </div>

            <div class="pelicula">
                <img src="imagenes_raiz/terrifier2.jpg" alt="poster" loading="lazy">

                <div class="info-pelicula">
                    <h2>Terrifier 2</h2>
                    <p class="precio">$ 25000</p>

                    <a href="pelicula.php" class="boton boton-azul">Ver Detalles</a>
                </div>

            </div> -->
            <?php endwhile; ?>
        </div>
        
    </main>
    
<?php 
    mysqli_close($db);
    include 'templates/footer.php';
?>