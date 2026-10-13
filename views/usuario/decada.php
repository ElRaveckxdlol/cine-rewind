<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Década <?php echo $titulo_decada; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
	<?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="cinedeoro-section">
        <h2> Década de los <?php echo $titulo_decada; ?></h2>
        <p class="cinedeoro-texto">
            Descubrí los clásicos de esta época dorada del cine.<br>
            Inolvidables historias y directores legendarios.
        </p>

        <?php if (empty($peliculas)): ?>
            <p class="no-peliculas">No hay películas disponibles para esta década.</p>
        <?php else: ?>
            <div class="movies-container">
                <?php foreach ($peliculas as $pelicula): ?>
                    <div class="movie-card">
                        <img src="<?php echo BASE_URL; ?>imagenes/<?php echo $pelicula['posterurl']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                        <h3><?php echo $pelicula['titulo']; ?></h3>
                        <span><?php echo $pelicula['anio_estreno']; ?></span>
                        <div class="pelicula-botones">
                            <a href="<?php echo BASE_URL; ?>?ruta=detalle&id=<?php echo $pelicula['id_pelicula']; ?>" 
                            class="btn-detalle">Ver detalle</a>
                            <a href="<?php echo BASE_URL; ?>?ruta=reservar&id=<?php echo $pelicula['id_pelicula']; ?>" 
                            class="btn-reservar">Reservar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>