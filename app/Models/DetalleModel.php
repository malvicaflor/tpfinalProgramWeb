<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'descripcion', 'precio', 'imagenes', 'categoria_id'];

    public function index()
    {
        return view('detalle'); 
    }
    public function obtenerProductoDetalle($id)
    {
        $producto = $this->find($id);

        if (!$producto) {
            return null; 
        }

        $stockModel = new StockModel();
        $stock = $stockModel
            ->select('talle, cantidad')
            ->where('producto_id', $id)
            ->where('cantidad >', 0) 
            ->findAll();

        $producto['talles'] = array_column($stock, 'talle');

        if (!empty($producto['imagenes'])) {
            $imagenes = json_decode($producto['imagenes'], true);
            if (!is_array($imagenes)) $imagenes = [$producto['imagenes']];
        } else {
            $imagenes = ['default.jpg'];
        }

        $producto['imagenes'] = $imagenes;

        return $producto;
    }
}

