<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($pelicula['titulo']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="detalle-container">
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <div class="detalle-pelicula">
            <img src="<?php echo BASE_URL; ?>imagenes/<?php echo e($pelicula['posterurl']); ?>" 
                 alt="<?php echo e($pelicula['titulo']); ?>" class="detalle-poster">
            <div class="detalle-info">
                <h1><?php echo e($pelicula['titulo']); ?></h1>
                <p class="detalle-meta">
                    <?php echo $pelicula['anio_estreno']; ?> · 
                    <?php echo e($pelicula['genero']); ?> · 
                    <?php echo $pelicula['duracion']; ?> min · 
                    <?php echo e($pelicula['clasificacion']); ?>
                </p>
                <p><?php echo nl2br(e($pelicula['descripcion'])); ?></p>

                <?php if ($promedio['total'] > 0): ?>
                    <p class="puntaje-promedio">
                        ⭐ <?php echo number_format($promedio['promedio'], 1); ?>/5 
                        (<?php echo $promedio['total']; ?> reseñas)
                    </p>
                <?php endif; ?>

                <a href="<?php echo BASE_URL; ?>?ruta=reservar&id=<?php echo $pelicula['id_pelicula']; ?>" 
                   class="btn-reservar">Reservar entradas</a>
            </div>
        </div>

        <div class="comentarios-section">
            <h2> Reseñas (<?php echo count($comentarios); ?>)</h2>

            <?php if ($yaComento): ?>
                <p class="aviso">Ya enviaste una reseña para esta película.</p>
            <?php else: ?>
                <form action="<?php echo BASE_URL; ?>?ruta=comentar" method="POST" class="form-comentario">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_pelicula" value="<?php echo $pelicula['id_pelicula']; ?>">
                    
                    <div class="input-group">
                        <label>Puntaje</label>
                        <select name="puntaje" required>
                            <option value="">— Elegí —</option>
                            <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                            <option value="4">⭐⭐⭐⭐ Muy buena</option>
                            <option value="3">⭐⭐⭐ Buena</option>
                            <option value="2">⭐⭐ Regular</option>
                            <option value="1">⭐ Mala</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Tu reseña</label>
                        <textarea name="comentario" rows="4" required 
                                  maxlength="500" placeholder="Contá tu experiencia..."></textarea>
                    </div>

                    <button type="submit" class="btn-login">Enviar reseña</button>
                </form>
            <?php endif; ?>

            <div class="lista-comentarios">
                <?php if (empty($comentarios)): ?>
                    <p class="no-comentarios">Todavía no hay reseñas aprobadas.</p>
                <?php else: ?>
                    <?php foreach ($comentarios as $c): ?>
                        <div class="comentario-card">
                            <div class="comentario-header">
                                <strong><?php echo e($c['usuario_nombre']); ?></strong>
                                <span class="estrellas">
                                    <?php echo str_repeat('⭐', $c['puntaje']); ?>
                                </span>
                                <span class="fecha"><?php echo formatearFecha($c['fecha']); ?></span>
                            </div>
                            <p><?php echo nl2br(e($c['comentario'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>