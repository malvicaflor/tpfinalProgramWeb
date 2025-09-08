<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    // Aquí podrías definir datos "hardcodeados" o en el futuro conectar con una tabla
    protected $banners = [
        ['img' => 'assets/img/banner1.jpg', 'titulo' => 'Bienvenido a Marine Store'],
        ['img' => 'assets/img/banner2.jpg', 'titulo' => 'Nuevas tendencias'],
        ['img' => 'assets/img/banner3.jpg', 'titulo' => 'Promociones imperdibles']
    ];

    public function getBanners()
    {
        return $this->banners;
    }
}
