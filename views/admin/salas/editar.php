<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-form">
        <h1> Editar Sala</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?ruta=admin_salas_editar&id=<?php echo $sala['id_sala']; ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="nombre">Nombre de la Sala</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo e($sala['nombre']); ?>" required>
            </div>

            <div class="input-group">
                <label for="capacidad">Capacidad</label>
                <input type="number" id="capacidad" name="capacidad" value="<?php echo $sala['capacidad']; ?>" required min="1">
            </div>

            <button type="submit" class="btn-login">Actualizar Sala</button>
            <a href="<?php echo BASE_URL; ?>?ruta=admin_salas" class="btn-cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>