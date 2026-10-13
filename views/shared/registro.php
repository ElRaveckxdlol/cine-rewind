<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="register-container">
        <h2> Crear Cuenta</h2>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>?ruta=registro_post" method="POST">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" required>
            </div>

            <div class="input-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
            </div>

            <div class="input-group">
                <label for="password_confirm">Confirmar Contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="Repite tu contraseña" required>
            </div>

            <div class="input-group checkbox">
                <label>
                    <input type="checkbox" name="terminos" required>
                    Acepto los términos y condiciones.
                </label>
            </div>

            <button type="submit" class="btn-login">Crear Cuenta</button>
            <button type="reset" class="btn-reset">Limpiar formulario</button>
        </form>

        <div class="register-link">
            ¿Ya tienes cuenta? <a href="<?php echo BASE_URL; ?>?ruta=login">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>