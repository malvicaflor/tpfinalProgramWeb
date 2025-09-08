<?php echo view('templates/header'); ?>

    <link rel="stylesheet" href="<?= base_url('assets/css/index.css') ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div class="marquesina">
  <div class="marquesina-contenedor">
    <div class="marquesina-contenido" id="marquesinaContenido">
      <span>✨ Envíos gratis desde $250.000 | 3 cuotas sin interés ✨</span>
      <span>✨ Envíos gratis desde $250.000 | 3 cuotas sin interés ✨</span>
      <span>✨ Envíos gratis desde $250.000 | 3 cuotas sin interés ✨</span>
      <span>✨ Envíos gratis desde $250.000 | 3 cuotas sin interés ✨</span>
    </div>
  </div>
</div>

<style>
.marquesina {
  width: 100%;
  overflow: hidden;
  background-color: #a68e6d;
  color: white;
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
  font-size: 15px;
  padding: 10px 0;
  position: relative;
}

.marquesina-contenedor {
  display: flex;
  width: max-content;
  animation: scrollMarquesina 15s linear infinite;
}

.marquesina-contenido {
  display: flex;
  gap: 2px;
  white-space: nowrap;
}
</style>

<script>
window.addEventListener("DOMContentLoaded", () => {
  const contenido = document.getElementById("marquesinaContenido");
  const contenedor = contenido.parentElement;

  const clone = contenido.cloneNode(true);
  contenedor.appendChild(clone);
});
</script>

<style>
@keyframes scrollMarquesina {
  0% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(-50%);
  }
}
</style>

<section class="fondorepetido">
  <div class="overlay"></div>
  <div class="contenido-texto">
    <h1>ZAFIRA STORE</h1>
    <button class="btn-explore" onclick="window.location.href='<?= base_url('productos'); ?>'">
      Explore now
    </button>
  </div>
</section>
<br>


<section class="seccion-categorias">
    <a href="<?= base_url('productos/categoria/Denim') ?>" class="categoria">
        <img src="<?= base_url('assets/img/denim.jpg') ?>" alt="Denim">
        <h2>DENIM</h2>
    </a>
    <a href="<?= base_url('productos/categoria/Sweaters') ?>" class="categoria">
        <img src="<?= base_url('assets/img/sweaters.jpg') ?>" alt="Sweaters">
        <h2>SWEATERS</h2>
    </a>
    <a href="<?= base_url('productos/categoria/Abrigos') ?>" class="categoria">
        <img src="<?= base_url('assets/img/abrigos.jpg') ?>" alt="Abrigos">
        <h2>ABRIGOS</h2>
    </a>
    <a href="<?= base_url('productos/categoria/Tops%20y%20Bodys') ?>" class="categoria">
        <img src="<?= base_url('assets/img/tops.jpg') ?>" alt="Tops y Bodys">
        <h2>TOPS Y BODYS</h2>
    </a>
</section>
</body>