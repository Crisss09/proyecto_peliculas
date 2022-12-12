<?php

    require 'config/database.php';
    $db = conectarDB();

    $consulta = "SELECT * FROM peliculas";
    $resultado = mysqli_query($db, $consulta);

    if($_SERVER['REQUEST_METHOD'] === "POST") {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id){

            $sql = "SELECT * FROM peliculas WHERE id = ${id}";
            $res = mysqli_query($db, $sql);
            $propiedad = mysqli_fetch_assoc($res);

            unlink("imagenes/" . $propiedad['imagen']);

            // Eliminar pelicula
            $sql = "DELETE FROM peliculas WHERE id = ${id}";
            $res = mysqli_query($db, $sql);

            if($res) {
                header("Location: gestion.php");
            }
        }
    }

    $resCambio = $_GET['resultado'] ?? null;

    include 'templates/header.php';
?>

    <main class="contenedor">
        <h1>Gestion</h1>

        <?php if(intval($resCambio == 2)): ?>
            <p class="alerta exito">Se Actualizo el Registro</p>
        <?php endif; ?>

        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($propiedad = mysqli_fetch_assoc($resultado)): ?>

                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td><img src="imagenes/<?php echo $propiedad['imagen']; ?>" alt="poster"></td>
                    <td>$ <?php echo $propiedad['precio']; ?></td>
                    <td>
                        <form method="post">
                            <div class="botones-lista">
                                <a href="editar.php?id=<?php echo $propiedad['id']; ?>" class="boton boton-amarillo">Editar</a>

                                <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                                <input class="boton boton-rojo" type="submit" value="Eliminar">    
                            </div>
                            
                        </form>
                    </td>
                </tr>

                <?php endwhile; ?>
            </tbody>
        </table>
    </main>


<?php
    include 'templates/footer.php';
?>