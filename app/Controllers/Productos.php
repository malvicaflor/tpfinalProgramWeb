<?php
namespace App\Controllers;
use App\Models\ProductoModel;
use App\Models\StockModel;

class Productos extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $stockModel = new StockModel();

        $productos = $productoModel->findAll();

        foreach ($productos as &$prod) {
            $stock = $stockModel
                ->select('talle, cantidad')
                ->where('producto_id', $prod['id'])
                ->where('cantidad >', 0) 
                ->findAll();

            $prod['talles'] = array_column($stock, 'talle');
        }

        return view('pages/productos', ['productos' => $productos]);
    }

    public function categoria($categoria)
    {
        $productoModel = new ProductoModel();
        $stockModel = new StockModel();

        $productos = $productoModel
            ->where('categoria', $categoria)
            ->findAll();

        foreach ($productos as &$prod) {
            $stock = $stockModel
                ->select('talle, cantidad')
                ->where('producto_id', $prod['id'])
                ->where('cantidad >', 0)
                ->findAll();

            $prod['talles'] = array_column($stock, 'talle');
        }

        return view('pages/productos', [
            'productos' => $productos,
            'categoria' => $categoria
        ]);
    }
}
