<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\StockModel;

class Carrito extends BaseController
{
    public function index()
    {
        $session = session();
        $carrito = $session->get('carrito') ?? [];
        return view('pages/carrito', ['carrito' => $carrito]);
    }

    public function agregar()
    {
        $session = session();
        $producto_id = $this->request->getPost('producto_id');
        $talle = $this->request->getPost('talle');
        $cantidad = (int)$this->request->getPost('cantidad');

        $stockModel = new StockModel();
        $stockDisponible = $stockModel
            ->where('producto_id', $producto_id)
            ->where('talle', $talle)
            ->first();

        if (!$stockDisponible || $stockDisponible['cantidad'] < $cantidad) {
            return redirect()->back()->with('error', 'No hay stock suficiente.');
        }

        $carrito = $session->get('carrito') ?? [];
        $clave = $producto_id . '_' . $talle;

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] += $cantidad;
        } else {
            $productoModel = new ProductoModel();
            $producto = $productoModel->find($producto_id);

            $carrito[$clave] = [
                'producto_id' => $producto_id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'talle' => $talle,
                'cantidad' => $cantidad
            ];
        }

        $session->set('carrito', $carrito);
        return redirect()->back()->with('success', 'Producto agregado al carrito.');
    }

    public function eliminar($clave)
    {
        $session = session();
        $carrito = $session->get('carrito') ?? [];

        if (isset($carrito[$clave])) {
            unset($carrito[$clave]);
            $session->set('carrito', $carrito);
        }
        return redirect()->back();
    }

    public function ajax_agregar()
    {
        helper('url');
        $session = session();

        $id     = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $precio = $this->request->getPost('precio');
        $talle  = $this->request->getPost('talle');
        $imagenes = json_decode($this->request->getPost('imagenes'), true);

        $carrito = $session->get('carrito') ?? [];
        $key = $id.'_'.$talle;

        if(isset($carrito[$key])) {
            $carrito[$key]['cantidad'] += 1;
        } else {
            $carrito[$key] = [
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'talle' => $talle,
                'imagenes' => $imagenes,
                'cantidad' => 1
            ];
        }

        $session->set('carrito', $carrito);

        $cantidad_total = array_sum(array_column($carrito, 'cantidad'));

        return $this->response->setJSON([
            'success' => true,
            'nombre' => $nombre,
            'talle' => $talle,
            'cantidad_total' => $cantidad_total
        ]);
    }

    public function vaciarCarrito()
    {
        $session = session();
        $session->remove('carrito'); // elimina el carrito de la sesión
        return redirect()->to(base_url('carrito')); // redirige al carrito vacío
    }

    public function eliminarProducto()
    {
        $session = session();
        $id = $this->request->getPost('id');
        $talle = $this->request->getPost('talle');

        $carrito = $session->get('carrito') ?? [];
        foreach ($carrito as $key => $item) {
            if ($item['id'] == $id && ($item['talle'] ?? '') == $talle) {
                unset($carrito[$key]);
                break;
            }
        }
        $carrito = array_values($carrito);
        $session->set('carrito', $carrito);
        return redirect()->to(base_url('carrito'));
    }

    public function procesarPago()
    {
        $session = session();
        $carrito = $session->get('carrito') ?? [];

        if (empty($carrito)) {
            return redirect()->to(base_url('carrito'))->with('error', 'El carrito está vacío.');
        }
        $conexion = new \mysqli("localhost", "root", "", "tpfinalprogramweb");
        if ($conexion->connect_errno) {
            die("Error al conectar a la base de datos: " . $conexion->connect_error);
        }

        $total = $_POST['total_con_descuento'] ?? 0;
        $usuario_id = $session->get('usuario_id'); 
        $fecha_compra = date('Y-m-d H:i:s');

        $stmtVenta = $conexion->prepare(
            "INSERT INTO ventas (usuario_id, total, fecha) VALUES (?, ?, ?)"
        );
        $stmtVenta->bind_param("ids", $usuario_id, $total, $fecha_compra);
        $stmtVenta->execute();
        $venta_id = $stmtVenta->insert_id;

        foreach ($carrito as $item) {
            $stmtStock = $conexion->prepare(
                "UPDATE stocks 
                SET cantidad = cantidad - ? 
                WHERE producto_id = ? AND cantidad >= ?"
            );
            $stmtStock->bind_param("iii", $item['cantidad'], $item['id'], $item['cantidad']);
            $stmtStock->execute();

            if ($stmtStock->affected_rows === 0) {
                return redirect()->to(base_url('carrito'))->with('error', 'No hay suficiente stock de '.$item['nombre']);
            }

            $stmtDetalle = $conexion->prepare(
                "INSERT INTO ventas_detalle (venta_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)"
            );
            $stmtDetalle->bind_param("iiid", $venta_id, $item['id'], $item['cantidad'], $item['precio']);
            $stmtDetalle->execute();
        }

        $session->remove('carrito');

        return view('pages/confirmacion_pago', [
            'total' => $total,
            'venta_id' => $venta_id,
        ]);
    }
}
