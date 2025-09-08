<?php

$pagina_actual = "SHOP";
?>
<?php echo view('templates/header'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('assets/img/logo.jpg') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/css/styleproductos.css') ?>">
    <style>
        .carrusel { position: relative; max-width: 250px; margin: 0 auto 15px; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .carrusel img { width: 100%; display: none; user-select: none; }
        .carrusel img:first-child { display: block; }
        .carrusel button.prev, .carrusel button.next { position: absolute; top: 50%; transform: translateY(-50%); font-size: 18px; background: rgba(0,0,0,0.5); color: #fff; border: none; border-radius: 50%; cursor: pointer; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; }
        .carrusel button.prev { left: 5px; } .carrusel button.next { right: 5px; }
        .precio-original { text-decoration: line-through; color: #999; font-size: 0.9em; margin-right: 6px; }
        .precio-descuento { color: #a68e6d; font-weight: bold; margin-right: 6px; }
        .precio-final { font-size: 1.2em; font-weight: bold; color: #333; }
        #toast { position: fixed; top: 100px; left: 50%; transform: translateX(-50%); background-color: rgba(166,142,109,0.9); color: white; padding: 12px 20px; border-radius: 8px; display: none; font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 9999; max-width: 90%; text-align: center; }
    </style>
</head>
<body>

<div class="breadcrumb" style="margin: 20px;">
    <a href="<?= base_url() ?>">home</a> / <a href="<?= base_url('productos') ?>">shop</a> 
</div>

<section class="productos">
    <div class="grid-productos">
        <?php if (empty($productos)): ?>
            <p>No se encontraron productos.</p>
        <?php else: ?>
            <?php foreach ($productos as $producto): ?>
                <?php
                    $imagenes = [];
                    if (!empty($producto['imagenes'])) {
                        $imagenes = json_decode($producto['imagenes'], true);
                        if (!is_array($imagenes)) $imagenes = [$producto['imagenes']];
                    } else {
                        $imagenes = ['default.jpg'];
                    }

                    $precio_original = floatval($producto['precio']);
                    $precio_final = $precio_original;
                    $descuento_str = '';

                    $talles = []; 
                ?>
                <article class="producto">
                    <a href="<?= base_url("detalle/".$producto['id']) ?>" class="imagen-link">
                        <div class="carrusel" data-id="<?= $producto['id'] ?>">
                            <button class="prev" onclick="cambiarImagenes(<?= $producto['id'] ?>, -1)">&#10094;</button>
                            <?php foreach ($imagenes as $i => $img): ?>
                                <img src="<?= base_url('assets/img/'.$img) ?>" alt="Imagen <?= $i+1 ?>" style="<?= $i===0?'display:block;':'display:none;' ?>">
                            <?php endforeach; ?>
                            <button class="next" onclick="cambiarImagenes(<?= $producto['id'] ?>, 1)">&#10095;</button>
                        </div>
                    </a>
                    <h3><?= esc($producto['nombre']) ?></h3>
                    <p class="precio">
                        <?php if (!empty($descuento_str)): ?>
                            <span class="precio-original">$ <?= number_format($precio_original, 0, ',', '.') ?></span>
                            <span class="precio-descuento"><?= $descuento_str ?></span>
                            <br>
                            <span class="precio-final">$ <?= number_format($precio_final, 0, ',', '.') ?></span>
                        <?php else: ?>
                            $ <?= number_format($precio_final, 0, ',', '.') ?>
                        <?php endif; ?>
                    </p>

                    <?php if (!empty($producto['talles'])): ?>
                        <div>
                            <label class="talle" for="talle-<?= $producto['id'] ?>">Talle:</label>
                            <select id="talle-<?= $producto['id'] ?>" class="select-talle">
                                <?php foreach ($producto['talles'] as $t): ?>
                                    <option value="<?= esc($t) ?>"><?= esc($t) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <button class="agregar-carrito"
                        data-id="<?= $producto['id'] ?>"
                        data-nombre="<?= esc($producto['nombre']) ?>"
                        data-precio="<?= $precio_final ?>"
                        data-imagenes="<?= htmlspecialchars(json_encode($imagenes)) ?>"
                        <?= empty($producto['talles']) ? 'disabled' : '' ?>>
                        ADD TO CART
                    </button>

                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<div id="toast"></div>

<script>
function mostrarToast(mensaje) {
    const toast = document.getElementById('toast');
    toast.textContent = mensaje;
    toast.style.display = 'block';
    setTimeout(()=>{ toast.style.display='none'; }, 3000);
}

document.querySelectorAll('.agregar-carrito').forEach(btn=>{
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const selectTalle = document.querySelector(`#talle-${id}`);
        const talle = selectTalle ? selectTalle.value : '';

        if (!talle && selectTalle) {
            mostrarToast("⚠ Por favor seleccioná un talle");
            return;
        }

        const data = {
            id: this.dataset.id,
            nombre: this.dataset.nombre,
            precio: this.dataset.precio,
            imagenes: this.dataset.imagenes,
            talle: talle
        };

        fetch('<?= base_url("ajax_agregar") ?>', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: new URLSearchParams(data)
        })
        .then(r=>r.json())
        .then(res=>{
            if (res.success) {
                mostrarToast(`✔ Se agregó '${res.nombre}' talle ${res.talle}`);
                const contador = document.getElementById('contador-carrito');
                if (contador) contador.textContent = res.cantidad_total;
            } else {
                mostrarToast(res.mensaje);
            }
        })
        .catch(err=>{
            mostrarToast("❌ Error al agregar al carrito: "+err.message);
            console.error(err);
        });
    });
});

// Carrusel
const carruseles = {};
document.querySelectorAll('.carrusel').forEach(c=>{
    const id = c.getAttribute('data-id');
    carruseles[id] = { index:0, slides: c.querySelectorAll('img') };
});

function cambiarImagenes(id,n){
    const c = carruseles[id]; if(!c) return;
    c.slides[c.index].style.display='none';
    c.index+=n;
    if(c.index<0)c.index=c.slides.length-1;
    if(c.index>=c.slides.length)c.index=0;
    c.slides[c.index].style.display='block';
}
</script>

</body>
</html>
