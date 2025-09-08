<?= view('templates/header'); ?>
<h2 style="text-align:center; margin:20px;">Iniciar sesión</h2>

<?php if(session()->getFlashdata('mensaje')): ?>
<p style="color:red; text-align:center;"><?= session()->getFlashdata('mensaje') ?></p>
<?php endif; ?>

<form action="<?= base_url('usuarios/login_post') ?>" method="post" style="max-width:400px; margin:auto; display:flex; flex-direction:column; gap:15px;">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" style="background:#A68E6D; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Ingresar</button>
</form>

<p style="text-align:center; margin-top:10px;">¿No tenés cuenta? <a href="<?= base_url('usuarios/registro') ?>">Registrate</a></p>
