<?php echo view('templates/header'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="<?= base_url('assets/img/logo.jpg') ?>" type="image/png">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= base_url('assets/css/inspo.css') ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>MARINE STORE - INSPO</title>
</head>
<body>
<div class="breadcrumb" style="margin: 20px;">
    <a href="<?= base_url() ?>">home</a> / <a href="<?= base_url('inspo') ?>">inspo</a> 
</div>
<section class="seccion-inspo">
  <div class="inspo-grid">
    <?php foreach($imagenes as $img): ?>
        <img src="<?= base_url('assets/img/' . $img) ?>" alt="Inspiración" />
    <?php endforeach; ?>
  </div>
</section>

</body>
</html>
