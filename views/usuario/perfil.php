<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="perfil-container">
        <h1> Mi Perfil</h1>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <div class="perfil-card">
            <h2>Datos Personales</h2>
            <form action="<?php echo BASE_URL; ?>?ruta=actualizar_perfil" method="POST">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo e($usuario['nombre']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo e($usuario['email']); ?>" required>
                </div>

                <button type="submit" class="btn-login">Actualizar Perfil</button>
            </form>
        </div>

        <div class="perfil-card">
            <h2>Cambiar Contraseña</h2>
            <form action="<?php echo BASE_URL; ?>?ruta=cambiar_password" method="POST">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <label for="password_actual">Contraseña Actual</label>
                    <input type="password" id="password_actual" name="password_actual" placeholder="••••••••" required>
                </div>

                <div class="input-group">
                    <label for="password_nueva">Nueva Contraseña</label>
                    <input type="password" id="password_nueva" name="password_nueva" placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="input-group">
                    <label for="password_confirm">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Repite tu contraseña" required>
                </div>

                <button type="submit" class="btn-login">Cambiar Contraseña</button>
            </form>
        </div>
    </div>
</body>
</html>