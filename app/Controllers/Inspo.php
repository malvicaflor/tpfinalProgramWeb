<?php

namespace App\Controllers;

use App\Models\InspoModel;

class Inspo extends BaseController
{
    public function index()
    {
        $model = new InspoModel();
        $data['imagenes'] = $model->getImagenes();

        return view('pages/inspo', $data);
    }
}
