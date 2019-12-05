<?php

namespace App\Controllers;

use \Core\View;

class Tomatoes extends \Core\Controller
{
    public function customTomatoAction()
    {
        View::renderTemplate(
            'Tomatoes/custom_tomato.xml.twig',
            [
                'id' => $this->route_params['id'],
                'leaf_color' => $this->route_params['leaf'],
                'core_color' => $this->route_params['core'],
                'weight' => $this->route_params['weight']
            ],
            'text/xml'
        );
    }

    public function tomatoesAction()
    {
        View::render('Tomatoes/tomatoes.xml', [], 'text/xml');
    }
}
