<?php

namespace App\Controllers;

use App\Models\ProduitModel;
class Produit extends BaseController
{
    public function index()
    {
        $model = new ProduitModel();
        $data['produits'] = null;
        return view('produits', $data);
    }
    public function show($id): string 
    {
    return "Produit ID : " . $id;
    }
}
