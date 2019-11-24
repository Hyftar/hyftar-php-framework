<?php

namespace App\Controllers;

use \Core\View;

class StaticFiles extends \Core\Controller
{
    public function tomatoesAction()
    {
        View::render('Static/tomatoes.xml', 'text/xml');
    }
}
