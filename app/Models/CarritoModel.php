<?php

namespace App\Models;

use CodeIgniter\Model;

class CarritoModel extends Model
{
    protected $table = 'carrito';        
    protected $primaryKey = 'id';         
    protected $allowedFields = [
        'producto_id',
        'usuario_id',
        'cantidad',
        'fecha_agregado'
    ];                                   
    protected $useTimestamps = true;      

    protected $createdField  = 'fecha_agregado';
    protected $updatedField  = '';

    public function obtenerCarritoUsuario($usuario_id)
    {
        return $this->where('usuario_id', $usuario_id)->findAll();
    }

    public function agregarProducto($usuario_id, $producto_id, $cantidad = 1)
    {
        $item = $this->where('usuario_id', $usuario_id)
                     ->where('producto_id', $producto_id)
                     ->first();

        if ($item) {
            $item['cantidad'] += $cantidad;
            $this->update($item['id'], $item);
        } else {
            $this->insert([
                'usuario_id' => $usuario_id,
                'producto_id' => $producto_id,
                'cantidad' => $cantidad,
                'fecha_agregado' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function eliminarProducto($id)
    {
        return $this->delete($id);
    }

    public function vaciarCarrito($usuario_id)
    {
        return $this->where('usuario_id', $usuario_id)->delete();
    }
}
