<?php echo view('templates/header'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('assets/img/logo.jpg') ?>" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/detalle.css') ?>">
    <style>
        .carrusel { position: relative; max-width: 250px; margin: 0 auto 15px; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .carrusel img { width: 100%; display: none; user-select: none; }
        .carrusel img:first-child { display: block; }
        .carrusel button.prev, .carrusel button.next { position: absolute; top: 50%; transform: translateY(-50%); font-size: 18px; background: rgba(0,0,0,0.5); color: #fff; border: none; border-radius: 50%; cursor: pointer; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; }
        .carrusel button.prev { left: 5px; } 
        .carrusel button.next { right: 5px; }
        .precio-final { font-size: 1.2em; color: #333; }
    </style>
</head>
<body>


<div class="breadcrumb" style="margin: 20px;">
    <a href="<?= base_url() ?>">home</a> / <a href="<?= base_url('productos') ?>">shop</a> / <span><?= esc($producto['nombre']) ?></span>
</div>

<section class="detalle-producto">
    <!---------------------- Carrusel ------------------------------->
    <div class="carrusel">
        <?php
            $imagenes = !empty($producto['imagenes']) ? json_decode($producto['imagenes'], true) : ['default.jpg'];
        ?>
        <button class="prev" onclick="cambiarImagenes(-1)">&#10094;</button>
        <?php foreach ($imagenes as $i => $img): ?>
            <img src="<?= base_url('assets/img/'.$img) ?>" alt="Imagen <?= $i+1 ?>" <?= $i===0?'style="display:block;"':'style="display:none;"' ?>>
        <?php endforeach; ?>
        <button class="next" onclick="cambiarImagenes(1)">&#10095;</button>
    </div>

    <!---------------------- Información del producto ------------------------>
    <div class="detalle-texto">
        <h1><?= esc($producto['nombre']) ?></h1>
        <p class="descripcion"><?= esc($producto['descripcion']) ?></p>
        <p class="precio-final">$ <?= number_format($producto['precio'],0,',','.') ?></p>

        <?php if (!empty($tallesDisponibles)): ?>
            <div class="selector-talle">
                <label for="talle">Talle:</label>
                <select id="talle">
                    <?php foreach ($tallesDisponibles as $t): ?>
                        <option value="<?= esc($t['talle']) ?>"><?= esc($t['talle']) ?> (<?= $t['cantidad'] ?> disponibles)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <hr>
    </div>
</section>

<div id="toast"></div>

<script>
let carruselIndex = 0;
const slides = document.querySelectorAll('.carrusel img');

function cambiarImagenes(n){
    slides[carruselIndex].style.display='none';
    carruselIndex += n;
    if(carruselIndex < 0) carruselIndex = slides.length - 1;
    if(carruselIndex >= slides.length) carruselIndex = 0;
    slides[carruselIndex].style.display='block';
}

function mostrarToast(mensaje){
    const toast = document.getElementById('toast');
    toast.textContent = mensaje;
    toast.style.display='block';
    setTimeout(()=>{ toast.style.display='none'; }, 3000);
}
</script>


