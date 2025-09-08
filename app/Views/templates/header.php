<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "tpfinal";

$conexion = mysqli_connect($host, $user, $pass, $db);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$categorias = [];
$query = "SELECT DISTINCT categoria FROM productos";
$res = mysqli_query($conexion, $query);
while ($row = mysqli_fetch_assoc($res)) {
    $categorias[] = $row['categoria'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ZAFIRA STORE</title>
    <link rel="icon" href="<?= base_url('assets/img/logo.png') ?>" type="image/png" />    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            padding-top: 70px;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }

        .menu-principal {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 20px;
            background-color: rgba(255,255,255,0.37);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 10000;
            height: 60px;
            box-sizing: border-box;
        }

        .logo-link {
            flex-shrink: 0;
        }

        .logo-img {
            height: 48px;
            width: auto;
            cursor: pointer;
            display: block;
        }

        .menu-contenido {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-grow: 1;
        }

        .menu-contenido a,
        .menu-contenido button {
            color: #A68E6D;
            font-weight: 600;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .menu-contenido a:hover,
        .menu-contenido button:hover {
            color: #7a5f2d;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-radius: 4px;
            min-width: 160px;
            z-index: 10001;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .right-icons {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-shrink: 0;
        }

        #carrito-icono {
            font-size: 20px;
            position: relative;
            text-decoration: none;
            color: #A68E6D;
        }

        #carrito-icono:hover {
            color: #7a5f2d;
        }

        #btn-buscar {
            background: none;
            border: none;
            cursor: pointer;
            width: 36px;
            height: 36px;
            padding: 0;
        }

        #btn-buscar svg {
            width: 24px;
            height: 24px;
            fill: #A68E6D;
        }

        .menu-principal.scrolled {
            background-color: rgba(195, 190, 190, 0.486);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .menu-principal.scrolled a,
        .menu-principal.scrolled button {
            color: rgb(74, 74, 74);
        }

        @media (max-width: 1000px) {
            .menu-principal {
                justify-content: space-between;
                height: 50px;
            }

            .logo-img {
                height: 40px;
            }

            .menu-contenido {
                display: none;
                position: absolute;
                top: 50px;
                left: 0;
                right: 0;
                background: rgba(255,255,255,0.95);
                flex-direction: column;
                gap: 15px;
                padding: 10px 0;
                z-index: 9999;
            }

            .menu-contenido.activo {
                display: flex;
            }

            #carrito-icono,
            #btn-buscar {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<nav class="menu-principal">
    <a href="index.php" class="logo-link" id="logo-toggle">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt=" ZAFIRA STORE" class="logo-img" />
    </a>

    <div class="menu-contenido" id="menu-contenido">
        <a href="<?= site_url('home') ?>">home</a>

    <div class="dropdown">
        <a href="<?= site_url('productos') ?>">productos</a>
    </div>

        <a href="<?= site_url('inspo') ?>">inspo</a>
        <a href="<?= site_url('contact') ?>">contact</a>
    </div>

    <div class="right-icons">
    <a href="<?= base_url('carrito') ?>" id="carrito-icono" aria-label="Carrito de compras">🛒</a>

    <button id="btn-buscar" aria-label="Abrir búsqueda"> </button>
    </div>
</nav>

<div id="modal-buscar" style="
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(3px);
    z-index: 9999;
    justify-content: center;
    align-items: center;
">
    <form action="productos.php" method="GET" style="
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        display: flex;
        gap: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    ">
        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar productos..."
            style="flex-grow: 1; padding: 8px; font-size: 16px;"
            autofocus
        />
        <button type="submit" style="
            background: #A68E6D; 
            border: none; 
            color: white; 
            padding: 8px 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        ">Buscar</button>
        <button type="button" id="cerrar-buscar" style="
            background: transparent;
            border: none;
            color: #A68E6D;
            font-size: 20px;
            cursor: pointer;
        ">&times;</button>
    </form>
</div>

<script>
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('.menu-principal');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    const btnBuscar = document.getElementById('btn-buscar');
    const modalBuscar = document.getElementById('modal-buscar');
    const cerrarBuscar = document.getElementById('cerrar-buscar');

    btnBuscar.addEventListener('click', () => {
        modalBuscar.style.display = 'flex';
    });
    cerrarBuscar.addEventListener('click', () => {
        modalBuscar.style.display = 'none';
    });
    modalBuscar.addEventListener('click', e => {
        if (e.target === modalBuscar) {
            modalBuscar.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const logo = document.getElementById('logo-toggle');
        const menu = document.getElementById('menu-contenido');

        logo.addEventListener('click', (e) => {
            if (window.innerWidth <= 1000) {
                e.preventDefault();
                menu.classList.toggle('activo');
            }
        });
    });

    let submenuAbierto = false;
    const btnProductos = document.getElementById('btn-productos');
    const submenu = document.getElementById('submenu-productos');

    btnProductos.addEventListener('click', function (e) {
        e.preventDefault();

        if (!submenuAbierto) {
            submenu.style.display = 'block';
            submenuAbierto = true;
        } else {
            window.location.href = 'productos.php';
        }
    });

    submenu.addEventListener('mouseleave', () => {
        submenu.style.display = 'none';
        submenuAbierto = false;
    });
</script>

</body>
</html>
