<?= view('templates/header'); ?>
<h2 style="text-align:center; margin:20px;">Registro de usuario</h2>

<?php if(session()->getFlashdata('mensaje')): ?>
<p style="color:red; text-align:center;"><?= session()->getFlashdata('mensaje') ?></p>
<?php endif; ?>

<form action="<?= base_url('usuarios/registro_post') ?>" method="post" style="max-width:400px; margin:auto; display:flex; flex-direction:column; gap:15px;">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="password" name="password_confirm" placeholder="Repetir contraseña" required>
    <button type="submit" style="background:#A68E6D; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Registrarse</button>
</form>

<p style="text-align:center; margin-top:10px;">¿Ya tenés cuenta? <a href="<?= base_url('usuarios/login') ?>">Inicia sesión</a></p>
