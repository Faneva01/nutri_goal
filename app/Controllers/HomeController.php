<?php

namespace App\Controllers;

class HomeController extends BaseController {
    public function index()
    {
        return view('pages/home', [

            'title' => 'TrustMarket | Accueil',

            'styles' => [
                'style.css'
            ],

            // 'scripts' => [
            //     'home.js'
            // ]
        ]);
    }
}