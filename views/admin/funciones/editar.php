<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Función - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-form">
        <h1> Editar Función</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?ruta=admin_funciones_editar&id=<?php echo $funcion['id_funcion']; ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="id_pelicula">Película</label>
                <select id="id_pelicula" name="id_pelicula" required>
                    <?php foreach ($peliculas as $pelicula): ?>
                        <option value="<?php echo $pelicula['id_pelicula']; ?>" <?php echo $pelicula['id_pelicula'] == $funcion['id_pelicula'] ? 'selected' : ''; ?>>
                            <?php echo e($pelicula['titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label for="id_sala">Sala</label>
                <select id="id_sala" name="id_sala" required>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?php echo $sala['id_sala']; ?>" <?php echo $sala['id_sala'] == $funcion['id_sala'] ? 'selected' : ''; ?>>
                            <?php echo e($sala['nombre']); ?> (Cap: <?php echo $sala['capacidad']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="<?php echo $funcion['fecha']; ?>" required>
            </div>

            <div class="input-group">
                <label for="horario">Horario</label>
                <input type="time" id="horario" name="horario" value="<?php echo substr($funcion['horario'], 0, 5); ?>" required>
            </div>

            <div class="input-group">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $funcion['precio']; ?>" required>
            </div>

            <button type="submit" class="btn-login">Actualizar Función</button>
            <a href="<?php echo BASE_URL; ?>?ruta=admin_funciones" class="btn-cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>