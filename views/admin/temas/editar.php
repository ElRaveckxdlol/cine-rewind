<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tema - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-form">
        <h1>Editar Tema</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?ruta=admin_temas_editar&id=<?php echo $tema['id_tema']; ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="input-group">
                <label for="nombre">Nombre del tema</label>
                <input type="text" id="nombre" name="nombre" 
                       value="<?php echo e($tema['nombre']); ?>" required>
            </div>

            <h3 style="margin: 25px 0 15px;">Colores</h3>

            <div class="input-group">
                <label for="color_fondo">Fondo general</label>
                <input type="color" id="color_fondo" name="color_fondo" 
                       value="<?php echo e($tema['color_fondo']); ?>" required>
            </div>

            <div class="input-group">
                <label for="color_primario">Color primario</label>
                <input type="color" id="color_primario" name="color_primario" 
                       value="<?php echo e($tema['color_primario']); ?>" required>
            </div>

            <div class="input-group">
                <label for="color_acento">Color de acento</label>
                <input type="color" id="color_acento" name="color_acento" 
                       value="<?php echo e($tema['color_acento']); ?>" required>
            </div>

            <div class="input-group">
                <label for="color_acento_hover">Acento hover</label>
                <input type="color" id="color_acento_hover" name="color_acento_hover" 
                       value="<?php echo e($tema['color_acento_hover']); ?>" required>
            </div>

            <div class="input-group">
                <label for="color_texto">Color de texto</label>
                <input type="color" id="color_texto" name="color_texto" 
                       value="<?php echo e($tema['color_texto']); ?>" required>
            </div>

            <div class="input-group">
                <label for="color_texto_claro">Color de texto claro</label>
                <input type="color" id="color_texto_claro" name="color_texto_claro" 
                       value="<?php echo e($tema['color_texto_claro']); ?>" required>
            </div>

            <button type="submit" class="btn-login">Actualizar Tema</button>
            <a href="<?php echo BASE_URL; ?>?ruta=admin_temas" class="btn-cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>