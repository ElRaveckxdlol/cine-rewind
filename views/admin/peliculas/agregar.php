<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Película - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-form">
        <h1> Agregar Película</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?ruta=admin_peliculas_agregar" method="POST">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="input-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>

            <div class="input-group">
                <label for="duracion">Duración (minutos)</label>
                <input type="number" id="duracion" name="duracion" required min="1">
            </div>

            <div class="input-group">
                <label for="genero">Género</label>
                <input type="text" id="genero" name="genero" required>
            </div>

            <div class="input-group">
                <label for="clasificacion">Clasificación</label>
                <select id="clasificacion" name="clasificacion" required>
                    <option value="">Seleccionar</option>
                    <option value="G">G (Todos los públicos)</option>
                    <option value="PG">PG (Guía parental)</option>
                    <option value="PG-13">PG-13 (Mayores de 13)</option>
                    <option value="R">R (Mayores de 17)</option>
                    <option value="NC-17">NC-17 (Mayores de 18)</option>
                </select>
            </div>

            <div class="input-group">
                <label for="anio_estreno">Año de Estreno</label>
                <input type="number" id="anio_estreno" name="anio_estreno" required min="1900" max="2030">
            </div>

            <div class="input-group">
                <label for="posterurl">Nombre del archivo de póster</label>
                <input type="text" id="posterurl" name="posterurl" placeholder="ej: pelicula.jpg" required>
                <small>El archivo debe estar en public/imagenes/</small>
            </div>

            <div class="input-group">
                <label for="trailerurl">URL del tráiler (opcional)</label>
                <input type="text" id="trailerurl" name="trailerurl" placeholder="https://www.youtube.com/embed/...">
            </div>

            <div class="input-group checkbox">
                <label>
                    <input type="checkbox" name="destacada" value="1">
                    Marcar como destacada (aparece en el carrusel)
                </label>
            </div>

            <button type="submit" class="btn-login">Agregar Película</button>
            <a href="<?php echo BASE_URL; ?>?ruta=admin_peliculas" class="btn-cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>