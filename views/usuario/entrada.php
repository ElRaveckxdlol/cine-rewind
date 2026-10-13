<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Butaca - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/estilos.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
</head>
<body>
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>

    <div class="entrada-container">
        <h1>Seleccionar Butacas</h1>
        <h2><?php echo e($funcion['pelicula_titulo']); ?></h2>
        <p><strong>Fecha:</strong> <?php echo formatearFecha($funcion['fecha'], 'd/m/Y'); ?></p>
        <p><strong>Hora:</strong> <?php echo substr($funcion['horario'], 0, 5); ?></p>
        <p><strong>Sala:</strong> <?php echo e($funcion['sala_nombre']); ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($funcion['precio'], 2); ?></p>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $mensaje['tipo']; ?>">
                <?php echo $mensaje['texto']; ?>
            </div>
        <?php endif; ?>

        <div class="leyenda-butacas">
            <span class="leyenda-item"><span class="butaca-color disponible"></span> Disponible</span>
            <span class="leyenda-item"><span class="butaca-color reservada"></span> Reservada</span>
            <span class="leyenda-item"><span class="butaca-color ocupada"></span> Ocupada</span>
            <span class="leyenda-item"><span class="butaca-color seleccionada"></span> Tu selección</span>
        </div>

        <div class="pantalla">PANTALLA</div>

        <form action="<?php echo BASE_URL; ?>?ruta=procesar_compra" method="POST" id="form-compra">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_funcion" value="<?php echo $funcion['id_funcion']; ?>">

            <div class="butacas-grid">
                <?php foreach ($butacas as $butaca): ?>
                    <?php
                        $estado = $butaca['estado'];
                        $disabled = ($estado !== 'disponible') ? 'disabled' : '';
                    ?>
                    <label class="butaca-label <?php echo $estado; ?>">
                        <input type="checkbox" 
                               name="butacas[]" 
                               value="<?php echo $butaca['id_butaca']; ?>"
                               class="butaca-checkbox"
                               <?php echo $disabled; ?>>
                        <span class="butaca-num">
                            <?php echo e($butaca['numero_butaca']); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="resumen-compra">
                <p><strong>Butacas seleccionadas:</strong> <span id="contador">0</span></p>
                <p><strong>Total:</strong> $<span id="total">0.00</span></p>
            </div>

            <button type="submit" class="btn-login" id="btn-confirmar" disabled>
                Confirmar Compra
            </button>
        </form>
    </div>

    <script>
    const PRECIO = <?php echo floatval($funcion['precio']); ?>;
    const checkboxes = document.querySelectorAll('.butaca-checkbox');

    function actualizarResumen() {
        const seleccionadas = document.querySelectorAll('.butaca-checkbox:checked');
        const total = seleccionadas.length * PRECIO;
        document.getElementById('contador').textContent = seleccionadas.length;
        document.getElementById('total').textContent = total.toFixed(2);
        document.getElementById('btn-confirmar').disabled = seleccionadas.length === 0;

        checkboxes.forEach(cb => {
            cb.closest('.butaca-label').classList.toggle('seleccionada', cb.checked);
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', actualizarResumen));
    </script>
</body>
</html>