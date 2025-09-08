<?php

namespace App\Controllers;
use App\Models\ProductoModel;
use App\Models\StockModel;

class Detalle extends BaseController
{
    public function index($id = null)
    {
        // Si no hay ID, redirige a productos
        if (!$id) return redirect()->to('/productos');

        $productoModel = new ProductoModel();
        $stockModel = new StockModel();

        $producto = $productoModel->find($id);
        if (!$producto) return redirect()->to('/productos');

        $tallesDisponibles = $stockModel
            ->select('talle, cantidad')
            ->where('producto_id', $id)
            ->where('cantidad >', 0)
            ->findAll();

        $talles = [];
        foreach ($tallesDisponibles as $t) {
            $talles[] = $t['talle'];
        }

        $producto['talles'] = $talles;

        $data = [
            'producto' => $producto
        ];

        return view('pages/detalle', $data);
    }
}
