<?php

namespace App\Controllers;

use \Core\View;

class StaticFiles extends \Core\Controller
{
    public function customTomatoAction()
    {
        View::renderTemplate(
            'Static/custom_tomato.xml.twig',
            'text/xml',
            [
                'id' => $this->route_params['id'],
                'leaf_color' => $this->route_params['leaf'],
                'core_color' => $this->route_params['core'],
                'weight' => $this->route_params['weight']
            ]
        );
    }

    public function tomatoesAction()
    {
        View::render('Static/tomatoes.xml', 'text/xml');
    }
}
