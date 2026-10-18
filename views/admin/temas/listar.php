<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Temas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../../shared/navbar.php'; ?>

    <div class="admin-section">
        <h1>Gestionar Temas</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>?ruta=admin_temas_agregar" class="btn-add">+ Crear Tema</a>

        <div class="temas-grid">
            <?php foreach ($temas as $t): ?>
                <div class="tema-card <?php echo ($tema_activo && $tema_activo['id_tema'] == $t['id_tema']) ? 'tema-activo' : ''; ?>">
                    <div class="tema-preview">
                        <div class="preview-nav" style="background: <?php echo e($t['color_primario']); ?>; color: <?php echo e($t['color_acento']); ?>;">
                            <span>RETRO REWIND</span>
                        </div>
                        <div class="preview-body" style="background: <?php echo e($t['color_fondo']); ?>; color: <?php echo e($t['color_texto']); ?>;">
                            <p style="color: <?php echo e($t['color_texto']); ?>;">Texto de ejemplo</p>
                            <span class="preview-btn" style="background: <?php echo e($t['color_acento']); ?>; color: <?php echo e($t['color_primario']); ?>;">
                                Botón
                            </span>
                        </div>
                    </div>

                    <div class="tema-info">
                        <h3><?php echo e($t['nombre']); ?>
                            <?php if ($t['es_predeterminado']): ?>
                                <small class="badge badge-user">Predeterminado</small>
                            <?php endif; ?>
                        </h3>

                        <?php if ($tema_activo && $tema_activo['id_tema'] == $t['id_tema']): ?>
                            <span class="badge badge-aprobado">Activo</span>
                        <?php else: ?>
                            <form method="POST" action="<?php echo BASE_URL; ?>?ruta=admin_temas_activar" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo $t['id_tema']; ?>">
                                <button type="submit" class="btn-approve">Activar</button>
                            </form>
                        <?php endif; ?>

                        <a href="<?php echo BASE_URL; ?>?ruta=admin_temas_editar&id=<?php echo $t['id_tema']; ?>" class="btn-edit">Editar</a>

                        <?php if (!$t['es_predeterminado'] && (!$tema_activo || $tema_activo['id_tema'] != $t['id_tema'])): ?>
                            <a href="<?php echo BASE_URL; ?>?ruta=admin_temas_eliminar&id=<?php echo $t['id_tema']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Eliminar este tema?')">Eliminar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>