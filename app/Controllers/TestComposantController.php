<?php

namespace App\Controllers;

class TestComposantController extends BaseController
{
    public function index()
    {
        return view('pages/test_composant', [

            'title' => 'Test Composants',

            'styles' => [
                'style.css'
            ]
        ]);
    }
}