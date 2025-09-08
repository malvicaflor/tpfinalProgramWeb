

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="assets/img/logo.jpg" type="image/png">
    <meta charset="UTF-8" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/img/logo.jpg') ?>" type="image/png">


    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        .breadcrumb {
            margin: 100px;
            font-size: 14px;
            color: #544b4b;
            text-align: center;
        }
        .contacto-container {
            max-width: 600px;
            margin: 80px auto 0; 
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }


        .contacto-container h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #444;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            font-family: 'Montserrat', sans-serif;
        }

        .form-group textarea {
            resize: vertical;
            height: 120px;
        }

        .btn-enviar {
            background-color: #A68E6D;
            color: #fff;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-enviar:hover {
            background-color: #8b7456;
        }

        .mensaje-confirmacion {
            background-color:rgba(139, 116, 86, 0.16);
            color:rgb(255, 255, 255);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }

    </style>
</head>
<body>
<div class="breadcrumb" style="margin: 20px;">
        <a href="<?= base_url() ?>">home</a> / <a href="<?= base_url('contact') ?>">contact</a> 
</div>
<?php echo view('templates/header'); ?>
<div class="contacto-container">
    <h1>Contacto</h1>
    <div id="mensaje-exito" class="mensaje-confirmacion">¡Gracias por tu mensaje! Te responderemos pronto.</div>
    <form id="form-contacto" method="POST" action="enviar_contacto.php">
        <div class="form-group">
            <label for="nombre">Nombre completo</label>
            <input type="text" name="nombre" id="nombre" required />
        </div>
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" required />
        </div>
        <div class="form-group">
            <label for="mensaje">Mensaje</label>
            <textarea name="mensaje" id="mensaje" required></textarea>
        </div>
        <button type="submit" class="btn-enviar">Enviar mensaje</button>
    </form>
</div>

<script>
document.getElementById("form-contacto").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);

    fetch('enviar_contacto.php', {
        method: 'POST',
        body: data
    })
    .then(res => res.text())
    .then(res => {
        document.getElementById("mensaje-exito").style.display = 'block';
        form.reset();
    })
    .catch(err => {
        alert("Ocurrió un error al enviar el mensaje.");
        console.error(err);
    });
});
</script>
</body>
</html>

