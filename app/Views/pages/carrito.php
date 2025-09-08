<?php
$session = session();
$carrito = $session->get('carrito') ?? [];

$subtotal = 0;
foreach ($carrito as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}

$codigo_cupon = '';
$descuento = 0;
$total = $subtotal;
$cupon_aplicado = null;
$error_cupon = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_cupon'])) {
    $codigo_cupon = trim($_POST['codigo_cupon']);

    $conexion = new mysqli("localhost", "root", "", "marine");
    if ($conexion->connect_errno) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    $stmt = $conexion->prepare("SELECT * FROM cupones WHERE codigo = ?");
    $stmt->bind_param("s", $codigo_cupon);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $cupon_aplicado = $resultado->fetch_assoc();
        $hoy = date('Y-m-d');

        if ($cupon_aplicado['activo'] && $cupon_aplicado['fecha_expiracion'] >= $hoy) {
            $categoria_cupon = trim($cupon_aplicado['categoria']);

            if ($cupon_aplicado['tipo_descuento'] === 'porcentaje') {
                $porcentaje_descuento = floatval($cupon_aplicado['descuento']) / 100;
                $descuento = $subtotal * $porcentaje_descuento;
                $total = $subtotal - $descuento;

            } elseif ($cupon_aplicado['tipo_descuento'] === 'monto') {
                $descuento = floatval($cupon_aplicado['descuento']);
                $total = $subtotal - $descuento;
                if ($total < 0) $total = 0;

            } elseif ($cupon_aplicado['tipo_descuento'] === '2x1' || $cupon_aplicado['tipo_descuento'] === '3x2') {
                $tipo_promo = $cupon_aplicado['tipo_descuento'];
                $factor = ($tipo_promo === '2x1') ? 2 : 3;
                $pagar = ($tipo_promo === '2x1') ? 1 : 2;

                $descuento_promo = 0;
                $productos_agrupados = [];

                foreach ($carrito as $item) {
                    if ($categoria_cupon !== '' && strcasecmp($categoria_cupon, $item['categoria'] ?? '') !== 0) {
                        continue;
                    }
                    $key = $item['id'] . '-' . ($item['talle'] ?? '');
                    if (!isset($productos_agrupados[$key])) {
                        $productos_agrupados[$key] = [
                            'cantidad' => 0,
                            'precio' => $item['precio'],
                        ];
                    }
                    $productos_agrupados[$key]['cantidad'] += $item['cantidad'];
                }

                foreach ($productos_agrupados as $prod) {
                    $cantidad = $prod['cantidad'];
                    $precio = $prod['precio'];
                    $grupos = intdiv($cantidad, $factor);
                    $gratis = $grupos * ($factor - $pagar);
                    $descuento_promo += $gratis * $precio;
                }

                $descuento = $descuento_promo;
                $total = $subtotal - $descuento;

            } elseif ($cupon_aplicado['tipo_descuento'] === 'envio_gratis') {
                $descuento = 0;
                $total = $subtotal;
            }

            $_SESSION['cupon'] = $codigo_cupon;
        } else {
            $error_cupon = "Este cupón ya expiró o está inactivo.";
            $total = $subtotal;
            unset($_SESSION['cupon']);
        }
    } else {
        $error_cupon = "El código de cupón no es válido.";
        $total = $subtotal;
        unset($_SESSION['cupon']);
    }

    $stmt->close();
    $conexion->close();
}

$_SESSION['total'] = $total;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="<?= base_url('assets/img/logo.jpg') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/css/carrito.css') ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
</head>
<body>
<?= view('templates/header'); ?>

<div class="breadcrumb" style="margin: 20px;">
    <a href="<?= base_url() ?>">home</a> / <a href="<?= base_url('carrito') ?>">cart</a>
</div>

<div class="fondo-contenido">
  <div class="contenido">

    <?php if (count($carrito) > 0): ?>
        <form action="<?= base_url('vaciar_carrito') ?>" method="post" style="text-align: right; margin: 10px 20px; font-family:Montserrat;">
            <button type="submit" class="btn-vaciar">🗑 Vaciar carrito</button>
        </form>

        <table border="1" cellpadding="10" cellspacing="0" style="width: 90%; margin: auto; border-collapse: collapse;  text-align: center;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito as $producto): ?>
                <tr>
                    <td>
                        <?php if (!empty($producto['imagen'])): ?>
                            <img src="<?= base_url('assets/img/'.$producto['imagen']) ?>" alt="<?= esc($producto['nombre']) ?>" style="width:50px; height:auto; vertical-align:middle; margin-right:10px;">
                        <?php endif; ?>
                        <a href="<?= base_url('detalle/'.$producto['id']) ?>" style="text-decoration:none; color:#000;">
                            <?= esc($producto['nombre']) ?>
                        </a>
                        <?php if (!empty($producto['talle'])): ?>
                            <br><small style="font-weight: normal; font-size: 0.9em; color: #555;">Talle: <?= esc($producto['talle']) ?></small>
                            <br><small style="font-weight: normal; font-size: 0.9em; color: #555;">Precio: $<?= esc($producto['precio']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center; font-size: 1.1em;">
                        <?= $producto['cantidad'] ?>
                    </td>
                    <td>$ <?= number_format($producto['precio'] * $producto['cantidad'], 2, ',', '.') ?></td>
                    <td>
                        <form action="<?= base_url('eliminar_producto') ?>" method="post">
                            <input type="hidden" name="id" value="<?= esc($producto['id']) ?>">
                            <input type="hidden" name="talle" value="<?= esc($producto['talle']) ?>">
                            <button type="submit" class="btn-eliminar" style="background:#c0392b; color:#fff; border:none; padding:5px 10px; cursor:pointer;font-family:Montserrat;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!----- cupón ------>
        <div style="width: 90%; margin: 20px auto; text-align: center;">
            <form action="<?= base_url('carrito') ?>" method="post" class="form-cupon" style="display: inline-flex; gap: 10px; align-items: center;">
                <input type="text" id="codigo_cupon" name="codigo_cupon" value="<?= esc($codigo_cupon) ?>" placeholder="Código de cupón" />
                <button type="submit">Aplicar</button>
            </form>
            <?php if (!empty($error_cupon)): ?>
                <p style="color: red; margin-top: 5px;"><?= esc($error_cupon) ?></p>
            <?php endif; ?>
        </div>

        <div style="width: 90%; margin: 20px auto; text-align: center;">
            <p>Subtotal: $ <?= number_format($subtotal, 2, ',', '.') ?></p>
            <?php if ($descuento > 0): ?>
                <p>Descuento por cupón: - $ <?= number_format($descuento, 2, ',', '.') ?></p>
            <?php endif; ?>
            <p>Total: $ <span id="total-sin-envio"><?= number_format($total, 2, ',', '.') ?></span></p>
        </div>

        <?php if ($session->get('logged_in')): ?>
            <div style="max-width: 450px; margin: 0 auto; display:flex; flex-direction:column; gap:15px; align-items:center;">
                <form id="form-pago" action="<?= base_url('carrito/procesar_pago') ?>" method="POST" style="width:100%; display:flex; flex-direction:column; gap:10px; align-items:center;">
                    <input type="hidden" name="total_con_descuento" value="<?= $total ?>">
                    <button type="submit" style="font-family: Montserrat; background-color: #A68E6D; color: white; padding: 12px 25px; border-radius: 5px; min-width: 180px; cursor: pointer;">Pagar→</button>
                </form>

                <form action="<?= base_url('logout') ?>" method="post" style="width:100%; text-align:center;">
                    <button type="submit" style="font-family: Montserrat; background-color: #c0392b; color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        <?php else: ?>
            <p style="text-align:center; margin-top:20px;">
                Debes <a href="<?= base_url('usuarios/login') ?>">iniciar sesión</a> o <a href="<?= base_url('usuarios/registro') ?>">registrarte</a> para pagar.
            </p>
        <?php endif; ?>

    <?php else: ?>
        <p style="text-align:center; margin:40px;">Tu carrito está vacío.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
